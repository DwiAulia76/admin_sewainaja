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
    $adminId = $_SESSION['user_id'];

    // Ambil informasi foto sebelum menghapus
    $stmt = $pdo->prepare("SELECT photo FROM items WHERE id = ? AND admin_id = ?");
    $stmt->execute([$id, $adminId]);
    $item = $stmt->fetch();

    // Hapus dari database
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ? AND admin_id = ?");
    $stmt->execute([$id, $adminId]);

    // Hapus foto jika ada
    if ($item && $item['photo'] && file_exists('../' . $item['photo'])) {
        unlink('../' . $item['photo']);
    }

    header("Location: ../../barang.php");
    exit();
}