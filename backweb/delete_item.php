<?php
session_start();
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $pdo = (new Database())->getConnection();
    
    // Hapus dari database
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $success = $stmt->execute([$id]);
    
    if ($success) {
        $_SESSION['success'] = "Produk berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus produk.";
    }
}

header('Location: ../views/barang.php');
exit;
?>