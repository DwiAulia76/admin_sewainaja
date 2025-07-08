<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $adminId = $_SESSION['user_id'];

    try {
        $pdo = (new Database())->getConnection();
        
        // Ambil informasi foto sebelum menghapus
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ? AND owner_id = ?");
        $stmt->execute([$id, $adminId]);
        $item = $stmt->fetch();

        // Hapus dari database
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND owner_id = ?");
        $stmt->execute([$id, $adminId]);

        // Hapus foto jika ada
        if ($item && !empty($item['image']) && file_exists('../public/' . $item['image'])) {
            unlink('../public/' . $item['image']);
        }

        $_SESSION['success'] = "Barang berhasil dihapus";
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus barang: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}