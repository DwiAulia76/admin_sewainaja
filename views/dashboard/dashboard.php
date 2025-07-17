<div class="dashboard-content">
    <h2>Ringkasan Sistem</h2>
    <div class="stats">
        <?php
        require_once './config/database.php';
        $pdo = (new Database())->getConnection();
        
        // Total Barang
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        $totalProducts = $stmt->fetchColumn();
        
        // Penyewaan Aktif
        $stmt = $pdo->query("SELECT COUNT(*) FROM transactions WHERE status IN ('diproses', 'disewa')");
        $activeRentals = $stmt->fetchColumn();
        
        // Pembayaran Baru
        $stmt = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'pending'");
        $newPayments = $stmt->fetchColumn();
        ?>
        <div class="stat-card">
            <h3>Total Barang</h3>
            <p><?= $totalProducts ?></p>
        </div>
        <div class="stat-card">
            <h3>Penyewaan Aktif</h3>
            <p><?= $activeRentals ?></p>
        </div>
        <div class="stat-card">
            <h3>Pembayaran Baru</h3>
            <p><?= $newPayments ?></p>
        </div>
    </div>
</div>