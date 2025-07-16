<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price_per_day = $_POST['price_per_day'];
    $status = $_POST['status'];
    
    // Handle file upload
    $target_dir = "../public/uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_path = $target_dir . $new_filename;

    $uploadOk = 1;
    
    // Check if image file is a actual image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['error'] = "File bukan gambar.";
        $uploadOk = 0;
    }

    // Check file size (max 2MB)
    if ($_FILES["photo"]["size"] > 2000000) {
        $_SESSION['error'] = "Ukuran file terlalu besar (max 2MB).";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        $_SESSION['error'] = "Hanya format JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $_SESSION['error'] = "File tidak terupload.";
    } else {
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_path)) {
            // Simpan ke database
            $pdo = (new Database())->getConnection();
            $stmt = $pdo->prepare('INSERT INTO products (name, description, category, price_per_day, status, image) VALUES (?, ?, ?, ?, ?, ?)');
            $success = $stmt->execute([$name, $description, $category, $price_per_day, $status, $new_filename]);
            
            if ($success) {
                $_SESSION['success'] = "Produk berhasil ditambahkan.";
            } else {
                $_SESSION['error'] = "Gagal menambahkan produk.";
            }
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat mengupload file.";
        }
    }
}

header('Location: ../views/barang.php');
exit;
?>