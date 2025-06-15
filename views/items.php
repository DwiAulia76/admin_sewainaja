<div class="header-bar">
    <h1>Daftar Barang</h1>
    <button>
        <span>+</span> Tambah Barang
    </button>
</div>

<div class="card-grid">
    <?php foreach ($items as $item): ?>
        <div class="card">
            <h3><?= $item['name'] ?></h3>
            <div class="category"><?= $item['category'] ?></div>
            <div class="image-container">
                <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
            </div>
            <div class="description"><?= $item['description'] ?></div>
            <div class="price">Rp <?= number_format($item['price'], 0, ',', '.') ?> /hari</div>
            <div class="status <?= $item['status'] ?>"><?= ucfirst($item['status']) ?></div>
            <div class="actions">
                <button class="edit">Edit</button>
                <button class="delete">Hapus</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>