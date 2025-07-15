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

  <!-- ✅ GUNAKAN PATH ABSOLUT -->
  <link rel="stylesheet" href="/admin_sewainaja/public/assets/css/itemsStyle.css">
  <link rel="stylesheet" href="/admin_sewainaja/public/assets/css/sidebarStyle.css">
  <link rel="stylesheet" href="/admin_sewainaja/public/assets/css/modalStyle.css">
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
  </main>

  <!-- Modal Tambah & Edit -->
  <?php include './views/components/modalAddItem.php'; ?>
  <?php include './views/components/modalEditItem.php'; ?>

  <!-- Scripts -->
  <script src="/admin_sewainaja/public/assets/js/itemScript.js"></script>
  <script src="/admin_sewainaja/public/assets/js/sidebarScript.js"></script>
  <script src="/admin_sewainaja/public/assets/js/modalScript.js"></script>
</body>
</html>
