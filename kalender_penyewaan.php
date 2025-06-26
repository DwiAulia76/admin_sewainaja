<?php include './auth/auth.php'; ?>
<?php include './config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <link rel="stylesheet" href="public/assets/css/sidebarStyle.css">
    <link rel="stylesheet" href="public/assets/css/kalender.css">

    <!-- Tambahkan CSS khusus dashboard jika ada -->
</head>
<script>
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
</script>
<body>
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>
    

    <?php 
    $active_page = 'kalender_penyewaan'; // Set halaman aktif
    include 'views/components/sidebar.php'; 
    ?>

    <main class="main-content">
        
        <?php include 'views/penyewaan/kalender.php'; ?>
    </main>

    
    <!-- Tambahkan JS khusus dashboard jika ada -->
    <script src="public/assets/js/kalender.js"></script>
    <script src="public/assets/js/sidebarScript.js"></script>
</body>
</html>