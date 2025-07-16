<?php
require_once './config/database.php';

$pdo = (new Database())->getConnection();

// Ambil data transaksi lengkap untuk kalender
$stmt = $pdo->prepare("
    SELECT 
        t.id AS transaction_id,
        t.start_date,
        t.end_date,
        t.status,
        t.total_price,
        p.name AS product_name,
        u.name AS user_name
    FROM transactions t
    JOIN products p ON t.product_id = p.id
    JOIN users u ON t.user_id = u.id
");
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Kalender Penyewaan -->
<div class="kalender-container">
    <div class="header">
        <h1>Kalender Penyewaan</h1>
        
        <!-- Fitur Pencarian -->
        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari penyewaan...">
                <div class="search-actions">
                    <button id="searchButton" class="search-btn">
                        <i class="search-icon">🔍</i> Cari
                    </button>
                    <button id="resetButton" class="reset-btn">Reset</button>
                </div>
            </div>
            <div class="search-filters">
                <div class="filter-group">
                    <label>Filter berdasarkan:</label>
                    <select id="filterBy">
                        <option value="all">Semua</option>
                        <option value="product_name">Nama Alat</option>
                        <option value="user_name">Nama Penyewa</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="disewa">Disewa</option>
                        <option value="pending">Menunggu</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="controls">
            <div class="calendar-nav">
                <button id="prevMonth" class="btn-nav">←</button>
                <select id="monthSelect"></select>
                <select id="yearSelect"></select>
                <button id="nextMonth" class="btn-nav">→</button>
                <button id="todayBtn" class="today-btn">Hari Ini</button>
            </div>
            
            <div class="legend">
                <div class="legend-item">
                    <span class="legend-color approved"></span> Disewa
                </div>
                <div class="legend-item">
                    <span class="legend-color pending"></span> Menunggu
                </div>
                <div class="legend-item">
                    <span class="legend-color processing"></span> Diproses
                </div>
                <div class="legend-item">
                    <span class="legend-color completed"></span> Selesai
                </div>
                <div class="legend-item">
                    <span class="legend-color cancelled"></span> Dibatalkan
                </div>
            </div>
        </div>

        <!-- JSON data untuk JavaScript -->
        <script>
            const rentalData = <?= json_encode($rentals) ?>;
            let filteredRentalData = [...rentalData]; // duplikasi untuk filter/pencarian
        </script>

        <div id="calendar" class="calendar">
            <!-- Kalender akan digenerate oleh JS -->
        </div>
    </div>
</div>
