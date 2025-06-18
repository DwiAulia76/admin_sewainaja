<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_SESSION['user_id'] ?? null;
    
    if (!$adminId) {
        die("Error: Admin tidak terautentikasi");
    }

    $name = $_POST['name'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];
    $price = $_POST['price_per_day'];
    $status = $_POST['status'];
    $currentDateTime = date('Y-m-d H:i:s'); // Waktu saat ini

    // Penanganan upload foto
    $photoPath = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../public/storage/';
        
        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $uploadPath = $uploadDir . $photoName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            // Simpan path relatif untuk akses web
            $photoPath = 'public/storage/' . $photoName;
        }
    }

    // Sertakan admin_id dan timestamp
    $stmt = $pdo->prepare("INSERT INTO items (admin_id, name, description, category, price_per_day, status, photo, created_at, updated_at)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $adminId, 
        $name, 
        $desc, 
        $cat, 
        $price, 
        $status, 
        $photoPath,
        $currentDateTime,
        $currentDateTime
    ]);

    header("Location: ../barang.php");
    exit();
}