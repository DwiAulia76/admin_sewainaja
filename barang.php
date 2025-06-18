<?php include './auth/auth.php'; ?>
<?php
require_once './config/database.php';
$stmt = $pdo->query("SELECT * FROM items");
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manajemen Barang</title>
    <link rel="stylesheet" href="public/assets/css/itemsStyle.css">
    <link rel="stylesheet" href="public/assets/css/sidebarStyle.css">
    <link rel="stylesheet" href="public/assets/css/modalStyle.css">
</head>
<body>
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>

    <?php 
    $active_page = 'items'; // Set halaman aktif
    include 'views/components/sidebar.php'; 
    ?>

    <main class="main-content">
        <?php include 'views/items/items.php'; ?>
    </main>

    <!-- Pindahkan modal ke luar main-content -->
    <?php include './views/components/modalAddItem.php'; ?>
    <?php include './views/components/modalEditItem.php'; ?>

    <script src="public/assets/js/itemScript.js"></script>
    <script src="public/assets/js/sidebarScript.js"></script>
    <script src="public/assets/js/modalScript.js"></script>
</body>
</html>