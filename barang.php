<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manajemen Barang</title>
    <link rel="stylesheet" href="public/assets/css/itemsStyle.css">
    <link rel="stylesheet" href="public/assets/css/sidebarStyle.css">
</head>
<body>
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>

    <?php 
    $active_page = 'items'; // Set halaman aktif
    include 'views/layouts/sidebar.php'; 
    ?>

    <main class="main-content">
        <?php include 'views/items/items.php'; ?>
    </main>

    <script src="public/assets/js/itemScript.js"></script>
    <script src="public/assets/js/sidebarScript.js"></script>
</body>
</html>