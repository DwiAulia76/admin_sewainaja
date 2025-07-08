<?php
include './auth/auth.php'; 
require_once '../config/database.php';

$pdo = (new Database())->getConnection();
$stmt = $pdo->query("SELECT * FROM products");
$items = $stmt->fetchAll();
?>

<div class="header-bar">
    <h1>Daftar Barang</h1>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari barang...">
        <button id="searchButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
            </svg>
        </button>
    </div>
    <button class="add-item" id="openModal">
        <span>+</span> Tambah Barang
    </button>
</div>

<div class="card-grid" id="cardGrid">
    <?php foreach ($items as $item): ?>
        <div class="card" data-name="<?= htmlspecialchars(strtolower($item['name'])) ?>" 
            data-category="<?= htmlspecialchars(strtolower($item['category'])) ?>">
            
            <div class="meta-container">
                <div class="category"><?= htmlspecialchars($item['category']) ?></div>
                <div class="stock">Stok: <?= $item['stock'] ?></div>
            </div>
            
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            
            <div class="image-container">
                <?php if (!empty($item['image'])): ?>
                    <img src="/admin_sewainaja/public/<?= htmlspecialchars($item['image']) ?>" 
                         alt="<?= htmlspecialchars($item['name']) ?>">
                <?php else: ?>
                    <div class="no-image">Tidak ada gambar</div>
                <?php endif; ?>
            </div>
            
            <div class="description"><?= htmlspecialchars($item['description']) ?></div>
            <div class="price">Rp <?= number_format($item['price_per_day'], 0, ',', '.') ?> /hari</div>
            <div class="actions">
                <button class="edit" data-id="<?= $item['id'] ?>" 
                        data-name="<?= htmlspecialchars($item['name']) ?>"
                        data-desc="<?= htmlspecialchars($item['description']) ?>"
                        data-cat="<?= htmlspecialchars($item['category']) ?>"
                        data-price="<?= $item['price_per_day'] ?>"
                        data-stock="<?= $item['stock'] ?>"
                        data-photo="<?= htmlspecialchars($item['image']) ?>">
                    Edit
                </button>
                <form method="post" action="./config/delete_item.php" class="delete-form">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                    <button type="submit" class="delete">Hapus</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
