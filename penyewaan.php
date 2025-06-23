<?php

// dashboard.php
require_once './auth/auth.php';

// Koneksi database
require_once './config/database.php';

// Tangani aksi persetujuan/penolakan
if (isset($_GET['action'])) {
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        if ($_GET['action'] === 'approve') {
            $stmt = $pdo->prepare("UPDATE rentals SET confirmed_by_admin = 1 WHERE id = ?");
            $stmt->execute([$id]);
        } elseif ($_GET['action'] === 'reject') {
            $stmt = $pdo->prepare("UPDATE rentals SET status = 'ditolak' WHERE id = ?");
            $stmt->execute([$id]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - SewaInAja</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary: #4361ee;
            --success: #06d6a0;
            --warning: #ffd166;
            --danger: #ef476f;
            --dark: #1e293b;
            --light: #f8f9fa;
        }
        
        body {
            background: #f0f2f5;
            color: #333;
            line-height: 1.6;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styling */
        .sidebar {
            width: 250px;
            background: var(--dark);
            color: white;
            padding: 20px 0;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        
        .sidebar-menu li a {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .sidebar-menu li a:hover, 
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        /* Controls Styling */
        .controls {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }
        
        select, button {
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: white;
            font-size: 14px;
        }
        
        .btn-nav {
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Calendar Styling */
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-day {
            background: var(--primary);
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: 600;
        }
        
        .day {
            background: white;
            padding: 10px;
            min-height: 120px;
            position: relative;
            transition: all 0.2s;
        }
        
        .day:hover {
            background: #f8f9ff;
            z-index: 1;
        }
        
        .day-number {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .event-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
        
        .events-container {
            max-height: 85px;
            overflow-y: auto;
        }
        
        .event-item {
            font-size: 0.8rem;
            padding: 4px 6px;
            margin-bottom: 4px;
            border-radius: 4px;
            background: #f0f4ff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }
        
        .event-pending {
            background: #fff8e1;
            border-left: 3px solid var(--warning);
        }
        
        .event-approved {
            background: #e8f5e9;
            border-left: 3px solid var(--success);
        }
        
        .event-rejected {
            background: #ffebee;
            border-left: 3px solid var(--danger);
        }
        
        .today .day-number {
            background: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .weekend {
            background: #f9f9ff;
        }
        
        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #888;
        }
        
        .rental-entry {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            background: #f9f9f9;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .rental-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .rental-title {
            font-weight: 600;
            color: var(--primary);
            margin: 0;
        }
        
        .rental-status {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-pending {
            background: #fff8e1;
            color: #b38b00;
        }
        
        .status-approved {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-rejected {
            background: #ffebee;
            color: #c62828;
        }
        
        .rental-details {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            margin-top: 8px;
            margin-right: 8px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .btn-approve {
            background: var(--success);
            color: white;
        }
        
        .btn-reject {
            background: var(--danger);
            color: white;
        }
        
        .btn-close {
            background: #e0e0e0;
            color: #333;
            margin-top: 15px;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #888;
        }
        
        /* Mobile Responsive */
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -250px;
                height: 100%;
                z-index: 1000;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
            }
            
            .mobile-toggle {
                display: block;
            }
            
            .calendar {
                grid-template-columns: repeat(1, 1fr);
            }
            
            .header-day {
                display: none;
            }
            
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .calendar-nav {
                justify-content: center;
                margin-bottom: 10px;
            }
        }
        
        @media (max-width: 480px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" id="sidebarToggle">☰</button>

    <div class="dashboard-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>SewaInAja Admin</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active">Kalender Penyewaan</a></li>
                <li><a href="#">Manajemen Barang</a></li>
                <li><a href="#">Manajemen Pengguna</a></li>
                <li><a href="#">Riwayat Penyewaan</a></li>
                <li><a href="#">Laporan</a></li>
                <li><a href="#">Pengaturan</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Kalender Penyewaan</h1>
                <div class="user-info">
                    <div class="user-avatar"><?= substr($_SESSION['user_email'], 0, 1) ?></div>
                    <div>
                        <div><?= htmlspecialchars($_SESSION['user_email']) ?></div>
                        <div style="font-size: 0.8rem; color: #666;">Administrator</div>
                    </div>
                </div>
            </div>
            
            <div class="controls">
                <div class="calendar-nav">
                    <button class="btn btn-nav" id="prevMonth">←</button>
                    <select id="monthSelect"></select>
                    <select id="yearSelect"></select>
                    <button class="btn btn-nav" id="nextMonth">→</button>
                    <button class="btn" id="todayBtn" style="margin-left: 10px;">Hari Ini</button>
                </div>
                <div>
                    <span style="display: inline-block; margin-right: 10px;">
                        <span style="background:#e8f5e9; padding:2px 8px; border-radius:4px;">Disetujui</span>
                    </span>
                    <span style="display: inline-block; margin-right: 10px;">
                        <span style="background:#fff8e1; padding:2px 8px; border-radius:4px;">Menunggu</span>
                    </span>
                    <span>
                        <span style="background:#ffebee; padding:2px 8px; border-radius:4px;">Ditolak</span>
                    </span>
                </div>
            </div>
            
            <div id="calendar" class="calendar"></div>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Penyewaan</h3>
                <button class="close-modal" onclick="closeModal()">×</button>
            </div>
            <h4>Penyewaan pada <span id="modalDate" style="color: #4361ee;"></span></h4>
            <div id="rentalList"></div>
            <button class="btn btn-close" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script>
        // Inisialisasi variabel
        const calendarEl = document.getElementById('calendar');
        const modal = document.getElementById('detailModal');
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');
        const rentalList = document.getElementById('rentalList');
        const prevMonthBtn = document.getElementById('prevMonth');
        const nextMonthBtn = document.getElementById('nextMonth');
        const todayBtn = document.getElementById('todayBtn');
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        
        // Data penyewaan (akan diambil dari database)
        let rentals = [];
        
        // Ambil data penyewaan dari server
        function fetchRentals() {
            fetch('get-rentals.php')
                .then(response => response.json())
                .then(data => {
                    rentals = data;
                    generateCalendar(currentMonth, currentYear);
                })
                .catch(error => {
                    console.error('Error fetching rentals:', error);
                    rentals = [];
                    generateCalendar(currentMonth, currentYear);
                });
        }
        
        // Setup dropdown bulan dan tahun
        function setupSelects() {
            monthSelect.innerHTML = '';
            yearSelect.innerHTML = '';

            monthNames.forEach((name, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = name;
                if (index === currentMonth) {
                    option.selected = true;
                }
                monthSelect.appendChild(option);
            });

            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                if (year === currentYear) {
                    option.selected = true;
                }
                yearSelect.appendChild(option);
            }
        }

        // Generate kalender
        function generateCalendar(month, year) {
            calendarEl.innerHTML = '';

            // Header hari
            dayNames.forEach(day => {
                const headerDay = document.createElement('div');
                headerDay.className = 'header-day';
                headerDay.textContent = day;
                calendarEl.appendChild(headerDay);
            });

            const firstDay = new Date(year, month, 1);
            const lastDate = new Date(year, month + 1, 0).getDate();
            const startDay = firstDay.getDay();

            // Hari kosong di awal bulan
            for (let i = 0; i < startDay; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'day';
                calendarEl.appendChild(emptyDiv);
            }

            // Hari dalam bulan
            const today = new Date();
            for (let d = 1; d <= lastDate; d++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'day';
                
                // Tambahkan kelas khusus untuk weekend
                const dayIndex = new Date(year, month, d).getDay();
                if (dayIndex === 0 || dayIndex === 6) {
                    dayDiv.classList.add('weekend');
                }
                
                // Tambahkan kelas khusus untuk hari ini
                if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                    dayDiv.classList.add('today');
                }
                
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                dayDiv.innerHTML = `
                    <div class="day-number">${d}</div>
                    <div class="event-badge">0</div>
                    <div class="events-container"></div>
                `;
                
                dayDiv.onclick = () => openModal(dateStr);
                calendarEl.appendChild(dayDiv);
            }
            
            // Load events
            loadEvents(month, year);
        }

        // Load events untuk bulan tertentu
        function loadEvents(month, year) {
            const eventsByDate = {};
            
            // Proses setiap event
            rentals.forEach(rental => {
                const start = new Date(rental.start_date);
                const end = new Date(rental.end_date);
                
                let current = new Date(start);
                while (current <= end) {
                    const dateStr = current.toISOString().split('T')[0];
                    
                    // Hanya proses jika dalam bulan dan tahun yang ditampilkan
                    if (current.getMonth() === month && current.getFullYear() === year) {
                        if (!eventsByDate[dateStr]) {
                            eventsByDate[dateStr] = [];
                        }
                        eventsByDate[dateStr].push(rental);
                    }
                    current.setDate(current.getDate() + 1);
                }
            });
            
            // Tampilkan events di kalender
            for (const dateStr in eventsByDate) {
                const dayNumber = parseInt(dateStr.split('-')[2]);
                const dayEls = document.querySelectorAll('.day .day-number');
                
                for (const dayEl of dayEls) {
                    if (parseInt(dayEl.textContent) === dayNumber) {
                        const dayContainer = dayEl.parentElement;
                        const badge = dayContainer.querySelector('.event-badge');
                        const eventsContainer = dayContainer.querySelector('.events-container');
                        
                        badge.textContent = eventsByDate[dateStr].length;
                        
                        eventsByDate[dateStr].forEach(rental => {
                            const eventItem = document.createElement('div');
                            eventItem.className = `event-item ${getEventClass(rental)}`;
                            eventItem.textContent = `${rental.item_name} - ${rental.user_name}`;
                            eventItem.onclick = (e) => {
                                e.stopPropagation();
                                openModal(dateStr, rental.id);
                            };
                            eventsContainer.appendChild(eventItem);
                        });
                    }
                }
            }
        }

        // Dapatkan kelas CSS berdasarkan status event
        function getEventClass(rental) {
            if (rental.status === 'ditolak') return 'event-rejected';
            if (rental.confirmed_by_admin) return 'event-approved';
            return 'event-pending';
        }

        // Dapatkan label status
        function getStatusLabel(rental) {
            if (rental.status === 'ditolak') return 'Ditolak';
            if (rental.confirmed_by_admin) return 'Disetujui';
            return 'Menunggu Persetujuan';
        }

        // Buka modal detail
        function openModal(dateStr, rentalId = null) {
            document.getElementById('modalDate').textContent = formatDate(dateStr);
            rentalList.innerHTML = '';
            
            // Filter rentals untuk tanggal yang dipilih
            const date = new Date(dateStr);
            const dailyRentals = rentals.filter(rental => {
                const start = new Date(rental.start_date);
                const end = new Date(rental.end_date);
                return date >= start && date <= end;
            });
            
            if (dailyRentals.length === 0) {
                rentalList.innerHTML = `
                    <div class="empty-state">
                        <h4>Tidak ada penyewaan</h4>
                        <p>Tidak ada penyewaan yang ditemukan pada tanggal ini.</p>
                    </div>
                `;
            } else {
                dailyRentals.forEach(rental => {
                    const rentalEntry = document.createElement('div');
                    rentalEntry.className = 'rental-entry';
                    rentalEntry.innerHTML = `
                        <div class="rental-header">
                            <h4 class="rental-title">${rental.item_name}</h4>
                            <div class="rental-status ${getStatusClass(rental)}">${getStatusLabel(rental)}</div>
                        </div>
                        <div class="rental-details">
                            <div><strong>Penyewa:</strong> ${rental.user_name}</div>
                            <div><strong>Periode:</strong> ${formatDate(rental.start_date)} s/d ${formatDate(rental.end_date)}</div>
                        </div>
                        ${!rental.confirmed_by_admin && rental.status !== 'ditolak' ? `
                        <div class="rental-actions">
                            <button class="btn btn-approve" onclick="confirmRental(${rental.id}, true)">
                                Setujui
                            </button>
                            <button class="btn btn-reject" onclick="confirmRental(${rental.id}, false)">
                                Tolak
                            </button>
                        </div>
                        ` : ''}
                    `;
                    rentalList.appendChild(rentalEntry);
                });
            }
            
            modal.classList.add('active');
        }

        // Dapatkan kelas status
        function getStatusClass(rental) {
            if (rental.status === 'ditolak') return 'status-rejected';
            if (rental.confirmed_by_admin) return 'status-approved';
            return 'status-pending';
        }

        // Format tanggal untuk tampilan
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return `${date.getDate()} ${monthNames[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Konfirmasi penyewaan (setujui/tolak)
        function confirmRental(id, approve) {
            if (confirm(`Apakah Anda yakin ingin ${approve ? 'menyetujui' : 'menolak'} penyewaan ini?`)) {
                const url = approve ? 
                    `approve-rental.php?id=${id}` : 
                    `reject-rental.php?id=${id}`;
                
                fetch(url)
                    .then(response => {
                        if (response.ok) {
                            // Refresh data
                            fetchRentals();
                            
                            // Tutup modal
                            closeModal();
                            
                            alert(`Penyewaan telah berhasil ${approve ? 'disetujui' : 'ditolak'}!`);
                        } else {
                            throw new Error('Network response was not ok');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat memproses permintaan');
                    });
            }
        }

        // Tutup modal
        function closeModal() {
            modal.classList.remove('active');
        }

        // Navigasi bulan
        function navigateMonth(direction) {
            if (direction === 'prev') {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
            } else if (direction === 'next') {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
            }
            
            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
            generateCalendar(currentMonth, currentYear);
        }

        // Kembali ke bulan saat ini
        function goToToday() {
            const today = new Date();
            currentMonth = today.getMonth();
            currentYear = today.getFullYear();
            
            monthSelect.value = currentMonth;
            yearSelect.value = currentYear;
            generateCalendar(currentMonth, currentYear);
        }

        // Event listeners
        monthSelect.addEventListener('change', () => {
            currentMonth = parseInt(monthSelect.value);
            generateCalendar(currentMonth, currentYear);
        });
        
        yearSelect.addEventListener('change', () => {
            currentYear = parseInt(yearSelect.value);
            generateCalendar(currentMonth, currentYear);
        });
        
        prevMonthBtn.addEventListener('click', () => navigateMonth('prev'));
        nextMonthBtn.addEventListener('click', () => navigateMonth('next'));
        todayBtn.addEventListener('click', goToToday);
        
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Inisialisasi
        setupSelects();
        fetchRentals();
    </script>
</body>
</html>