<?php
require_once '../config/database.php';

$pdo = (new Database())->getConnection();

$stmt = $pdo->prepare("
    SELECT t.*, p.name AS product_name, u.name AS user_name
    FROM transactions t
    JOIN products p ON t.product_id = p.id
    JOIN users u ON t.user_id = u.id
");
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

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
                        <option value="item_name">Nama Alat</option>
                        <option value="penyewa_name">Nama Penyewa</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="disewa">Disetujui</option>
                        <option value="menunggu pembayaran">Menunggu Pembayaran</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="selesai">Selesai</option>
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
                    <span class="legend-color approved"></span>
                    Disetujui
                </div>
                <div class="legend-item">
                    <span class="legend-color pending"></span>
                    Menunggu
                </div>
                <div class="legend-item">
                    <span class="legend-color rejected"></span>
                    Ditolak
                </div>
            </div>
        </div>

        <script>
            const rentalData = <?= json_encode($rentals) ?>;
            let filteredRentalData = [...rentalData]; // Salin data untuk pencarian
        </script>
        
        <div id="calendar" class="calendar">
            <!-- Calendar cells will be generated here -->
        </div>
    </div>
</div>