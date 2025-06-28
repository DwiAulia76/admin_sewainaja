<?php
$stmt = $pdo->prepare("
    SELECT r.*, i.name AS item_name, u.name AS penyewa_name
    FROM rentals r
    JOIN items i ON r.item_id = i.id
    JOIN users u ON r.penyewa_id = u.id
");
$stmt->execute();
$rentals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="kalender-container">
    <div class="header">
        <h1>Kalender Penyewaan</h1>
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
        </script>
        
        <div id="calendar" class="calendar">
            <!-- Calendar cells will be generated here -->
        </div>
    </div>
</div>