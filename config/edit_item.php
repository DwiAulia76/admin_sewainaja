<?php
session_start();
require_once '../database.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];
    $price = $_POST['price_per_day'];
    $status = $_POST['status'];
    $existingPhoto = $_POST['existing_photo'];
    $adminId = $_SESSION['user_id'];
    $currentDateTime = date('Y-m-d H:i:s');

    // Penanganan upload foto baru
    $photoPath = $existingPhoto;
    
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadDir = '../public/storage/';
        
        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $uploadPath = $uploadDir . $photoName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photoPath = 'public/storage/' . $photoName;
            
            // Hapus foto lama jika ada
            if (!empty($existingPhoto) && file_exists('../' . $existingPhoto)) {
                unlink('../' . $existingPhoto);
            }
        }
    }

    // Update data di database
    $stmt = $pdo->prepare("UPDATE items SET 
                          name = ?, 
                          description = ?, 
                          category = ?, 
                          price_per_day = ?, 
                          status = ?, 
                          photo = ?, 
                          updated_at = ?
                          WHERE id = ? AND admin_id = ?");
    
    $stmt->execute([
        $name, 
        $desc, 
        $cat, 
        $price, 
        $status, 
        $photoPath,
        $currentDateTime,
        $id,
        $adminId
    ]);

    header("Location: ../../barang.php");
    exit();
}