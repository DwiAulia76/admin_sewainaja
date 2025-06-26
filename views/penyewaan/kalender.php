
    <div class="kalender-container">
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