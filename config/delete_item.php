<?php
session_start();
require_once './database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $adminId = $_SESSION['user_id'];

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Ambil informasi foto sebelum menghapus
        $stmt = $pdo->prepare("SELECT photo FROM items WHERE id = ? AND admin_id = ?");
        $stmt->execute([$id, $adminId]);
        $item = $stmt->fetch();

        // Hapus dari database
        $stmt = $pdo->prepare("DELETE FROM items WHERE id = ? AND admin_id = ?");
        $stmt->execute([$id, $adminId]);

        // Hapus foto jika ada
        if ($item && !empty($item['photo']) && file_exists('../' . $item['photo'])) {
            unlink('../' . $item['photo']);
        }

        // Commit transaksi
        $pdo->commit();

        $_SESSION['success'] = "Barang berhasil dihapus";
    } catch (Exception $e) {
        // Rollback jika ada error
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal menghapus barang: " . $e->getMessage();
    }

    header("Location: ../barang.php");
    exit();
}