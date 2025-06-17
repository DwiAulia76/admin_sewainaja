<?php
$host = 'localhost';
$db   = 'adminsewainaja_app';
$user = 'root'; // Ganti dengan username database Anda
$pass = '';     // Ganti dengan password database Anda
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Simpan error ke log
    error_log("Database error: " . $e->getMessage());
    // Tampilkan pesan ramah pengguna
    die("Koneksi database gagal. Silakan coba lagi nanti.");

}
?>