<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = $_POST['id'] ?? '';
    $name     = $_POST['name'] ?? '';
    $desc     = $_POST['description'] ?? '';
    $cat      = $_POST['category'] ?? '';
    $price    = $_POST['price_per_day'] ?? 0;
    $status   = $_POST['status'] ?? 'available';
    $existingPhoto = $_POST['existing_photo'] ?? '';
    $adminId  = $_SESSION['user_id'];

    // Validasi input dasar
    if (empty($name) || empty($desc) || empty($cat) || $price <= 0) {
        $_SESSION['error'] = "Semua field harus diisi dengan benar.";
        header("Location: ../barang.php");
        exit();
    }

    // Siapkan foto lama sebagai default
    $photoPath = $existingPhoto;

    // Jika user upload foto baru
    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['photo']['type'];

        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Hanya file gambar (JPEG, PNG, GIF) yang diizinkan.";
            header("Location: ../barang.php");
            exit();
        }

        $uploadDir = __DIR__ . '/../public/uploads/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'public/uploads/images/' . $photoName;

            // Hapus foto lama jika ada
            $oldPhotoPath = __DIR__ . '/../' . $existingPhoto;
            if (!empty($existingPhoto) && file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload foto baru.";
            header("Location: ../barang.php");
            exit();
        }
    }

    // Simpan perubahan ke database
    try {
        $pdo = (new Database())->getConnection();

        $stmt = $pdo->prepare("UPDATE products SET 
            name = ?, description = ?, category = ?, price_per_day = ?, 
            image = ?, status = ?, updated_at = NOW() 
            WHERE id = ? AND owner_id = ?");

        $stmt->execute([$name, $desc, $cat, $price, $photoPath, $status, $id, $adminId]);

        $_SESSION['success'] = "Barang berhasil diperbarui.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal memperbarui data: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}
