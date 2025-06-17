<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <ul>
        <li><a href="index.php" <?= ($active_page == 'dashboard') ? 'class="active"' : '' ?>>Dashboard</a></li>
        <li><a href="barang.php" <?= ($active_page == 'items') ? 'class="active"' : '' ?>>Manajemen Barang</a></li>
        <li><a href="#" <?= ($active_page == 'rental') ? 'class="active"' : '' ?>>Penyewaan</a></li>
        <li><a href="#" <?= ($active_page == 'payment') ? 'class="active"' : '' ?>>Pembayaran</a></li>
        <li><a href="#" <?= ($active_page == 'notification') ? 'class="active"' : '' ?>>Notifikasi <span class="badge">New</span></a></li>
        <li><a href="#" <?= ($active_page == 'message') ? 'class="active"' : '' ?>>Pesan</a></li>
    </ul>
    <form method="post" action="/admin_sewainaja/auth/logout.php" class="logout-form">
        <button type="submit">Logout</button>
    </form>
</aside>