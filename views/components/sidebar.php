<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <ul>
        <li><a href="index.php" <?= ($active_page == 'dashboard') ? 'class="active"' : '' ?>>Dashboard</a></li>
        <li><a href="barang.php" <?= ($active_page == 'items') ? 'class="active"' : '' ?>>Manajemen Barang</a></li>
        <li><a href="kalender_penyewaan.php" <?= ($active_page == 'kalender_penyewaan') ? 'class="active"' : '' ?>>Kalender Penyewaan</a></li>
        <li><a href="#" <?= ($active_page == 'manajemen_pengguna') ? 'class="active"' : '' ?>>Manajemen Pengguna</a></li>
        <li><a href="#" <?= ($active_page == 'payment') ? 'class="active"' : '' ?>>Pembayaran</a></li>
        <li><a href="#" <?= ($active_page == 'riwayat_penyewaan') ? 'class="active"' : '' ?>>Riwayat Penyewaan</a></li>
        <li><a href="#" <?= ($active_page == 'laporan') ? 'class="active"' : '' ?>>Laporan</a></li>
        <li><a href="#" <?= ($active_page == 'notification') ? 'class="active"' : '' ?>>Notifikasi <span class="badge">New</span></a></li>
        <li><a href="#" <?= ($active_page == 'message') ? 'class="active"' : '' ?>>Pesan</a></li>
    </ul>
    <form method="post" action="/auth/logout.php" class="logout-form">
    <button type="submit" style="background-color: red; color: white; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; width: 195px; height: 45px;">
        Logout
    </button>
</form>

</form>

</aside>