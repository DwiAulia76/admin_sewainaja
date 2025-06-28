<?php
session_start();
require_once './auth/auth.php';
require_once './config/database.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$active_page = 'kalender_penyewaan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kalender Penyewaan</title>
    <link rel="stylesheet" href="public/assets/css/sidebarStyle.css">
    <link rel="stylesheet" href="public/assets/css/kalender.css">
</head>
<body>

<?php include 'views/components/sidebar.php'; ?>

<main class="main-content">
    <?php include 'views/penyewaan/kalender.php'; ?>
    <?php include 'views/penyewaan/modal_detail.php'; ?>
</main>

<script src="public/assets/js/kalender.js"></script>
<script src="public/assets/js/sidebarScript.js"></script>

</body>
</html>