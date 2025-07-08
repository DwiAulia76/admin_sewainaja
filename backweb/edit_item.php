<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['category'] ?? '';
    $price = $_POST['price_per_day'] ?? 0;
    $stock = $_POST['stock'] ?? 1;
    $existingPhoto = $_POST['existing_photo'] ?? '';
    $adminId = $_SESSION['user_id'];

    // Validasi input
    if (empty($name) || empty($desc) || empty($cat) || $price <= 0 || $stock <= 0) {
        $_SESSION['error'] = "Semua field harus diisi dengan benar";
        header("Location: ../barang.php");
        exit();
    }

    // Handle file upload
    $photoPath = $existingPhoto;
    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['photo']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Hanya file gambar (JPEG, PNG, GIF) yang diizinkan";
            header("Location: ../barang.php");
            exit();
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'uploads/' . $photoName;

            // Hapus foto lama jika ada
            if (!empty($existingPhoto) && file_exists('../public/' . $existingPhoto)) {
                unlink('../public/' . $existingPhoto);
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload foto baru";
            header("Location: ../barang.php");
            exit();
        }
    }

    // Update database
    try {
        $pdo = (new Database())->getConnection();
        $stmt = $pdo->prepare("UPDATE products SET 
            name = ?, description = ?, category = ?, price_per_day = ?, 
            image = ?, stock = ?, updated_at = NOW() 
            WHERE id = ? AND owner_id = ?");
        
        $stmt->execute([$name, $desc, $cat, $price, $photoPath, $stock, $id, $adminId]);

        $_SESSION['success'] = "Barang berhasil diperbarui";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}