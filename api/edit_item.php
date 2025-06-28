<?php
session_start();
require_once 'database.php';

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
    $status = $_POST['status'] ?? 'tersedia';
    $existingPhoto = $_POST['existing_photo'] ?? '';
    $adminId = $_SESSION['user_id'];
    $currentDateTime = date('Y-m-d H:i:s');

    // Handle file upload
    $photoPath = $existingPhoto;
    if (!empty($_FILES['photo']['name'])) {
    $uploadDir = __DIR__ . '/../public/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $uploadPath = $uploadDir . $photoName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
        $photoPath = 'uploads/' . $photoName;

        // Hapus foto lama jika ada
        if (!empty($existingPhoto) && file_exists(__DIR__ . '/../public/' . $existingPhoto)) {
            unlink(__DIR__ . '/../public/' . $existingPhoto);
        }
    } else {
        $_SESSION['error'] = "Gagal mengupload foto baru";
        header("Location: ../barang.php");
        exit();
    }
}

    // Update database
    try {
        $stmt = $pdo->prepare("UPDATE items SET name = ?, description = ?, category = ?, price_per_day = ?, status = ?, photo = ?, updated_at = ? WHERE id = ? AND admin_id = ?");
        $stmt->execute([$name, $desc, $cat, $price, $status, $photoPath, $currentDateTime, $id, $adminId]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = "Barang berhasil diperbarui";
        } else {
            $_SESSION['error'] = "Tidak ada perubahan atau barang tidak ditemukan";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}