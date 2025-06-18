<?php
// Periksa apakah sesi belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: /admin_sewainaja/auth/login.php');
    exit;
}

// Cek timeout sesi (20 menit = 1200 detik)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1200)) {
    // Hapus sesi dan redirect ke login
    session_unset();
    session_destroy();
    header('Location: /admin_sewainaja/auth/login.php?timeout=1');
    exit;
}

// Update waktu aktivitas terakhir
$_SESSION['last_activity'] = time();

// Cek apakah user adalah admin
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: /admin_sewainaja/auth/login.php?error=access');
    exit;
}