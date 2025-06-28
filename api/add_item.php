<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_SESSION['user_id'] ?? null;

    if (!$adminId) {
        $_SESSION['error'] = "Admin tidak terautentikasi";
        header("Location: ../barang.php");
        exit();
    }

    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['category'] ?? '';
    $price = $_POST['price_per_day'] ?? 0;
    $status = $_POST['status'] ?? 'tersedia';
    $createdAt = date('Y-m-d H:i:s');

    // Upload file foto
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            // Simpan path relatif dari public/
            $photoPath = 'uploads/' . $photoName;
        } else {
            $_SESSION['error'] = "Gagal mengupload foto";
            header("Location: ../barang.php");
            exit();
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO items (admin_id, name, description, category, price_per_day, status, photo, created_at, updated_at)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$adminId, $name, $desc, $cat, $price, $status, $photoPath, $createdAt, $createdAt]);

        $_SESSION['success'] = "Barang berhasil ditambahkan";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}
