<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Admin Panel</h2>
    </div>
    <ul>
        <li><a href="index.php" <?= ($active_page == 'dashboard') ? 'class="active"' : '' ?>>Dashboard</a></li>
        <li><a href="barang.php" <?= ($active_page == 'items') ? 'class="active"' : '' ?>>Kelola Barang</a></li>
        <li><a href="kalender_penyewaan.php" <?= ($active_page == 'kalender_penyewaan') ? 'class="active"' : '' ?>>Kalender Penyewaan</a></li>
        <li><a href="Tabel_penyewaan.php" <?= ($active_page == 'tabel_penyewaan') ? 'class="active"' : '' ?>>Tabel Penyewaan</a></li>
        <li><a href="tabel_users.php" <?= ($active_page == 'tabel_users') ? 'class="active"' : '' ?>>Manajemen Pengguna</a></li>

    </ul>
    <form method="post" action="/auth/logout.php" class="logout-form">
    <button type="submit" style="background-color: red; color: white; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; width: 195px; height: 45px;">
        Logout
    </button>
</form>

</form>

</aside>