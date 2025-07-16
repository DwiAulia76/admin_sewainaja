<?php
session_start();
require_once '../config/database.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $adminId = $_SESSION['user_id'];
    
    $pdo = (new Database())->getConnection();
    
    try {
        // Dapatkan path foto sebelum menghapus produk
        $stmt = $pdo->prepare('SELECT image FROM products WHERE id = ? AND owner_id = ?');
        $stmt->execute([$id, $adminId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            // Hapus produk dari database
            $deleteStmt = $pdo->prepare('DELETE FROM products WHERE id = ? AND owner_id = ?');
            $success = $deleteStmt->execute([$id, $adminId]);
            
            if ($success && $deleteStmt->rowCount() > 0) {
                // Hapus foto jika ada
                if (!empty($product['image'])) {
                    $filePath = __DIR__ . '/../' . $product['image'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                
                $_SESSION['success'] = "Produk berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Produk tidak ditemukan atau gagal dihapus.";
            }
        } else {
            $_SESSION['error'] = "Produk tidak ditemukan atau Anda tidak memiliki izin.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error database: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID produk tidak valid.";
}

header('Location: ../barang.php');
exit;
?>