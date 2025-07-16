<div class="container">
    <div class="header-bar">
        <h1>Manajemen Produk</h1>
        <div class="search-container">
            <input type="text" placeholder="Cari produk..." id="searchInput">
            <button id="searchButton">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </div>
        <button class="add-item" id="addItemBtn">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
      </svg>
      <span>Tambah Produk</span>
    </button>
    </div>

    <div class="meta-container">
        <div class="status-container">
            <div class="status-badge available">
                Tersedia (<?= count(array_filter($items, fn($item) => $item['status'] === 'available')) ?>)
            </div>
            <div class="status-badge rented">
                Disewa (<?= count(array_filter($items, fn($item) => $item['status'] === 'rented')) ?>)
            </div>
        </div>
        <div class="total">Total: <?= count($items) ?> produk</div>
    </div>

    <div class="card-grid">
        <?php if (count($items) > 0): ?>
            <?php foreach ($items as $item): ?>
                <div class="card" data-id="<?= $item['id'] ?>">
                    <div class="card-header">
                        <div class="status-badge <?= $item['status'] === 'available' ? 'available' : 'rented' ?>">
                            <?= $item['status'] === 'available' ? 'Tersedia' : 'Disewa' ?>
                        </div>
                        <div class="category"><?= $item['category'] ?></div>
                    </div>
                    
                    <div class="image-container">
                        <?php if (!empty($item['image'])): ?>
                            <img 
                                src="/admin_sewainaja/<?= $item['image'] ?>" 
                                alt="Foto Produk" 
                                class="product-image">
                        <?php else: ?>
                            <div class="no-image">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h3><?= $item['name'] ?></h3>
                        <p class="description"><?= $item['description'] ?></p>
                        <div class="price">Rp <?= number_format($item['price_per_day'], 0, ',', '.') ?>/hari</div>
                    </div>
                    
                    <div class="actions">
                        <!-- Tombol Edit dengan atribut data lengkap -->
                        <button class="btn-edit" 
          data-id="<?= $item['id'] ?>" 
          data-name="<?= htmlspecialchars($item['name']) ?>" 
          data-description="<?= htmlspecialchars($item['description']) ?>" 
          data-category="<?= htmlspecialchars($item['category']) ?>" 
          data-price="<?= $item['price_per_day'] ?>" 
          data-status="<?= $item['status'] ?>" 
          data-image="<?= htmlspecialchars($item['image'] ?? '') ?>">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
    </svg>
    <span>Edit</span>
  </button>
                        
                        <button class="delete" data-id="<?= $item['id'] ?>">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p>Tidak ada produk yang ditemukan</p>
            </div>
        <?php endif; ?>
    </div>
</div>