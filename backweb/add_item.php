<?php
session_start();
require_once '../config/database.php';

// Cek apakah user sudah login sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

// Proses hanya jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_SESSION['user_id'];
    $name = $_POST['name'] ?? '';
    $desc = $_POST['description'] ?? '';
    $cat = $_POST['category'] ?? '';
    $price = $_POST['price_per_day'] ?? 0;
    $stock = $_POST['stock'] ?? 1;

    // Validasi sederhana
    if (empty($name) || empty($desc) || empty($cat) || $price <= 0) {
        $_SESSION['error'] = "Semua field harus diisi dengan benar.";
        header("Location: ../barang.php");
        exit;
    }

    // Upload gambar
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'uploads/' . $photoName;
        } else {
            $_SESSION['error'] = "Gagal mengupload foto.";
            header("Location: ../barang.php");
            exit;
        }
    }

    // Simpan ke database
    try {
        $pdo = (new Database())->getConnection();

        $stmt = $pdo->prepare("INSERT INTO products 
            (name, description, price_per_day, category, image, stock, owner_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        $stmt->execute([$name, $desc, $price, $cat, $photoPath, $stock, $adminId]);

        $_SESSION['success'] = "Barang berhasil ditambahkan.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data.";
    }

    header("Location: ../barang.php");
    exit;
}
