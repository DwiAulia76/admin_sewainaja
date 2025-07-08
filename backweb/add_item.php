<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['category'] ?? '';
    $price = $_POST['price_per_day'] ?? 0;
    $stock = $_POST['stock'] ?? 1; // Tambahkan stok

    // Validasi input
    if (empty($name) || empty($desc) || empty($cat) || $price <= 0) {
        $_SESSION['error'] = "Semua field harus diisi dengan benar";
        header("Location: ../barang.php");
        exit();
    }

    // Upload file foto
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['photo']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Hanya file gambar (JPEG, PNG, GIF) yang diizinkan";
            header("Location: ../barang.php");
            exit();
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'uploads/' . $photoName;
        } else {
            $_SESSION['error'] = "Gagal mengupload foto";
            header("Location: ../barang.php");
            exit();
        }
    }

    try {
        $pdo = (new Database())->getConnection();
        $stmt = $pdo->prepare("INSERT INTO products 
            (name, description, price_per_day, category, image, stock, owner_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        $stmt->execute([$name, $desc, $price, $cat, $photoPath, $stock, $adminId]);

        $_SESSION['success'] = "Barang berhasil ditambahkan";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}