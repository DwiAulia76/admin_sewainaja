
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
    <!-- Mobile Toggle Button (Hidden on Desktop) -->
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>

    <!-- Include Sidebar -->
    <?php include 'views/layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Include Items Content -->
        <?php include 'views/items/items.php'; ?>
    </main>

    <script src="public/assets/js/itemScript.js"></script>
</body>
</html>