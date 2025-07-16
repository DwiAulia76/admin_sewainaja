<?php
session_start();
include './auth/auth.php'; 
require_once './config/database.php';

$pdo = (new Database())->getConnection();
$stmt = $pdo->query("SELECT * FROM products");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Manajemen Barang</title>
  
  <!-- PASTIKAN PATH BENAR -->
  <link rel="stylesheet" href="./assets/css/itemsStyle.css">
  <link rel="stylesheet" href="./assets/css/sidebarStyle.css">
  <link rel="stylesheet" href="./assets/css/modal.css">
</head>
<body>
  <!-- Toggle Sidebar (mobile) -->
  <button class="mobile-toggle" id="sidebarToggle">☰</button>

  <!-- Sidebar -->
  <?php 
    $active_page = 'items';
    include './views/components/sidebar.php'; 
  ?>

  <!-- Konten utama -->
  <main class="main-content">
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success">
          <?= $_SESSION['success']; unset($_SESSION['success']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
          <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <!-- Menampilkan daftar item -->
    <?php include './views/items/items.php'; ?>

    <?php include './views/items/modal_tambah.php'; ?>
    <?php include './views/items/modal_edit.php'; ?>
  </main>


  <!-- Scripts -->
  <script src="./assets/js/sidebarScript.js"></script>
  <script src="./views/items/addmodal.js"></script>
  <script src="./views/items/editmodal.js"></script>
  <script>
  // Event listener untuk tombol edit
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
      const itemData = {
        id: this.dataset.id,
        name: this.dataset.name,
        description: this.dataset.description,
        category: this.dataset.category,
        price_per_day: this.dataset.price,
        status: this.dataset.status,
        image: this.dataset.image
      };
      
      openEditModal(itemData);
    });
  });
</script>

</body>
</html>