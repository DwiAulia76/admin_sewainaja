<?php
session_start();
require_once './auth/auth.php';
require_once './config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$database = new Database();
$pdo = $database->getConnection();

$active_page = 'tabel_penyewaan';
$pdo = (new Database())->getConnection();

$stmt = $pdo->prepare("
    SELECT t.*, p.name AS product_name, p.image AS product_image, u.name AS user_name
FROM transactions t
JOIN products p ON t.product_id = p.id
JOIN users u ON t.user_id = u.id
ORDER BY t.created_at DESC

");

$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Tabel Penyewaan</title>
    <link rel="stylesheet" href="assets/css/sidebarStyle.css">
    <link rel="stylesheet" href="assets/css/tabelPenyewaan.css">
</head>
<body>
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>

    <?php include 'views/components/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'views/penyewaan/tabelPenyewaan.php'; ?>
    </main>

    
    <script src="assets/js/sidebarScript.js"></script>
</body>
</html>