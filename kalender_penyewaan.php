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

// Query untuk mengambil data penyewaan yang lengkap
$stmt = $pdo->query("
    SELECT 
        t.id,
        p.name AS product_name,
        u.name AS user_name,
        t.start_date,
        t.end_date,
        t.status,
        t.total_price
    FROM transactions t
    JOIN products p ON t.product_id = p.id
    JOIN users u ON t.user_id = u.id
    ORDER BY t.start_date ASC
");
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kalender Penyewaan</title>
    <link rel="stylesheet" href="assets/css/sidebarStyle.css">
    <link rel="stylesheet" href="assets/css/kalender.css">
    <link rel="stylesheet" href="views/penyewaan/modalDetail.css"> <!-- CSS modal di penyewaan -->
</head>
<body>
    <button class="mobile-toggle" id="sidebarToggle">☰</button>

    <?php 
    $active_page = 'kalender_penyewaan';
    include 'views/components/sidebar.php'; 
    ?>

    <main class="main-content">
        <?php include 'views/penyewaan/kalender.php'; ?>
        <?php include 'views/penyewaan/modal_detail.php'; ?>
    </main>

    <script>
    // Kirim data ke JavaScript dengan format yang diperlukan
    const rentalData = <?= json_encode($rentals) ?>;
    let filteredRentalData = [...rentalData];
    window.rentalData = rentalData; // Pastikan global
    </script>
    <script src="assets/js/kalender.js"></script>
    <script src="views/penyewaan/modalDetail.js"></script> <!-- JS modal di penyewaan -->
    <script src="assets/js/sidebarScript.js"></script>
</body>
</html>