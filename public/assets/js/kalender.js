// DOM elements
const calendar = document.getElementById("calendar");
const monthSelect = document.getElementById("monthSelect");
const yearSelect = document.getElementById("yearSelect");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");
const todayBtn = document.getElementById("todayBtn");

// Current date
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

// Days of the week
const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

// Initialize
populateMonthYearSelect();
renderCalendar(currentMonth, currentYear);

// Event listeners
monthSelect.addEventListener("change", () => {
  currentMonth = parseInt(monthSelect.value);
  renderCalendar(currentMonth, currentYear);
});

yearSelect.addEventListener("change", () => {
  currentYear = parseInt(yearSelect.value);
  renderCalendar(currentMonth, currentYear);
});

prevMonthBtn.addEventListener("click", () => {
  currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
  if (currentMonth === 11) currentYear--;
  updateSelects();
  renderCalendar(currentMonth, currentYear);
});

nextMonthBtn.addEventListener("click", () => {
  currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
  if (currentMonth === 0) currentYear++;
  updateSelects();
  renderCalendar(currentMonth, currentYear);
});

todayBtn.addEventListener("click", () => {
  const now = new Date();
  currentMonth = now.getMonth();
  currentYear = now.getFullYear();
  updateSelects();
  renderCalendar(currentMonth, currentYear);
});

// Functions
function populateMonthYearSelect() {
  const months = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];

  months.forEach((name, i) => {
    let opt = document.createElement("option");
    opt.value = i;
    opt.textContent = name;
    if (i === currentMonth) opt.selected = true;
    monthSelect.appendChild(opt);
  });

  for (let y = currentYear - 5; y <= currentYear + 5; y++) {
    let opt = document.createElement("option");
    opt.value = y;
    opt.textContent = y;
    if (y === currentYear) opt.selected = true;
    yearSelect.appendChild(opt);
  }
}

function updateSelects() {
  monthSelect.value = currentMonth;
  yearSelect.value = currentYear;
}

function renderCalendar(month, year) {
  calendar.innerHTML = "";

  // Create header days
  days.forEach((day) => {
    const headerCell = document.createElement("div");
    headerCell.className = "header-day";
    headerCell.textContent = day;
    calendar.appendChild(headerCell);
  });

  const firstDay = new Date(year, month, 1).getDay();
  const totalDays = new Date(year, month + 1, 0).getDate();
  const today = new Date();

  for (let i = 0; i < 42; i++) {
    const cell = document.createElement("div");
    cell.classList.add("calendar-cell");
    const dateNumber = i - firstDay + 1;
    const isToday =
      today.getDate() === dateNumber &&
      today.getMonth() === month &&
      today.getFullYear() === year;

    if (i >= firstDay && dateNumber <= totalDays) {
      const fullDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(
        dateNumber
      ).padStart(2, "0")}`;

      // Add date number
      const dateNumDiv = document.createElement("div");
      dateNumDiv.className = "date-number";
      if (isToday) dateNumDiv.classList.add("today");
      dateNumDiv.textContent = dateNumber;
      cell.appendChild(dateNumDiv);

      // Check if it's weekend
      const dayOfWeek = new Date(year, month, dateNumber).getDay();
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        cell.classList.add("weekend");
      }

      // Find rentals for this date
      const itemsToday = rentalData.filter((item) => {
        const current = new Date(fullDate);
        const start = new Date(item.start_date);
        const end = new Date(item.end_date);
        return current >= start && current <= end;
      });

      if (itemsToday.length > 0) {
        itemsToday.forEach((item) => {
          const badge = document.createElement("div");
          badge.className = `rental-badge ${getStatusClass(item.status)}`;
          badge.textContent = item.item_name;
          cell.appendChild(badge);
        });

        cell.classList.add("clickable");
        cell.addEventListener("click", () => showDetail(fullDate));
      }
    } else {
      cell.classList.add("empty");
    }

    calendar.appendChild(cell);
  }
}

function getStatusClass(status) {
  switch (status) {
    case "disewa":
    case "selesai":
      return "status-approved";
    case "menunggu pembayaran":
      return "status-pending";
    case "ditolak":
      return "status-rejected";
    default:
      return "";
  }
}

function showDetail(dateStr) {
  const date = new Date(dateStr);
  const options = { day: "numeric", month: "long", year: "numeric" };
  const formattedDate = date.toLocaleDateString("id-ID", options);

  // Update modal title
  document.getElementById(
    "modalTitle"
  ).textContent = `Detail Penyewaan - ${formattedDate}`;

  // Find rentals for this date
  const items = rentalData.filter((item) => {
    const start = new Date(item.start_date);
    const end = new Date(item.end_date);
    const current = new Date(dateStr);
    return current >= start && current <= end;
  });

  const detailContainer = document.getElementById("rentalDetailContent");
  detailContainer.innerHTML = "";

  if (items.length > 0) {
    items.forEach((item) => {
      const div = document.createElement("div");
      div.className = `rental-entry ${getStatusClass(item.status)}`;
      div.innerHTML = `
                <div class="rental-header">
                    <h4>${item.item_name}</h4>
                    <div class="rental-status ${getStatusClass(item.status)}">
                        ${formatStatus(item.status)}
                    </div>
                </div>
                <div class="rental-details">
                    <strong>Penyewa:</strong> ${item.penyewa_name || "N/A"}<br>
                    <strong>Periode:</strong> ${formatDate(
                      item.start_date
                    )} s/d ${formatDate(item.end_date)}<br>
                    <strong>Durasi:</strong> ${calculateDuration(
                      item.start_date,
                      item.end_date
                    )} hari
                </div>
            `;
      detailContainer.appendChild(div);
    });
  } else {
    detailContainer.innerHTML = `
            <div class="empty-state">
                <p>Tidak ada penyewaan di tanggal ini</p>
            </div>
        `;
  }

  // Show modal
  document.getElementById("detailModal").classList.add("active");
}

function formatStatus(status) {
  const statusMap = {
    disewa: "Disetujui",
    selesai: "Selesai",
    "menunggu pembayaran": "Menunggu Pembayaran",
    ditolak: "Ditolak",
  };
  return statusMap[status] || status;
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

function calculateDuration(startDate, endDate) {
  const start = new Date(startDate);
  const end = new Date(endDate);
  const diff = end - start;
  return Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
}

function closeModal() {
  document.getElementById("detailModal").classList.remove("active");
}
