<!-- Kalender Penyewaan -->
<div class="kalender-container">
    <h1>Kalender Penyewaan</h1>
    
    <!-- Fitur Pencarian yang Disederhanakan -->
    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari penyewaan...">
            <div class="search-actions">
                <button id="searchButton" class="search-btn">🔍</button>
                <button id="resetButton" class="reset-btn">↺</button>
            </div>
        </div>
        <div class="search-filters">
            <select id="filterBy">
                <option value="all">Semua</option>
                <option value="product_name">Nama Alat</option>
                <option value="user_name">Nama Penyewa</option>
            </select>
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

    <div class="controls">
        <div class="calendar-nav">
            <button id="prevMonth" class="btn-nav">←</button>
            <select id="monthSelect"></select>
            <select id="yearSelect"></select>
            <button id="nextMonth" class="btn-nav">→</button>
            <button id="todayBtn" class="today-btn">Hari Ini</button>
        </div>

        <div class="legend">
            <div class="legend-item"><span class="legend-color disewa"></span> Disewa</div>
            <div class="legend-item"><span class="legend-color pending"></span> Menunggu</div>
            <div class="legend-item"><span class="legend-color diproses"></span> Diproses</div>
            <div class="legend-item"><span class="legend-color selesai"></span> Selesai</div>
            <div class="legend-item"><span class="legend-color dibatalkan"></span> Dibatalkan</div>
        </div>
    </div>

    <div id="calendar" class="calendar-grid"></div> <!-- Ubah menjadi grid -->
</div>