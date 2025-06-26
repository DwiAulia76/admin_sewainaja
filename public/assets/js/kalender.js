// Inisialisasi variabel
const calendarEl = document.getElementById("calendar");
const modal = document.getElementById("detailModal");
const monthSelect = document.getElementById("monthSelect");
const yearSelect = document.getElementById("yearSelect");
const rentalList = document.getElementById("rentalList");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");
const todayBtn = document.getElementById("todayBtn");

const monthNames = [
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
const dayNames = [
  "Minggu",
  "Senin",
  "Selasa",
  "Rabu",
  "Kamis",
  "Jumat",
  "Sabtu",
];

let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

// Ambil data penyewaan dari server
function fetchRentals() {
  fetch("get-rentals.php")
    .then((response) => response.json())
    .then((data) => {
      rentals = data;
      generateCalendar(currentMonth, currentYear);
    })
    .catch((error) => {
      console.error("Error fetching rentals:", error);
      rentals = [];
      generateCalendar(currentMonth, currentYear);
    });
}

// Setup dropdown bulan dan tahun
function setupSelects() {
  monthSelect.innerHTML = "";
  yearSelect.innerHTML = "";

  monthNames.forEach((name, index) => {
    const option = document.createElement("option");
    option.value = index;
    option.textContent = name;
    if (index === currentMonth) {
      option.selected = true;
    }
    monthSelect.appendChild(option);
  });

  for (let year = currentYear - 5; year <= currentYear + 5; year++) {
    const option = document.createElement("option");
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
  calendarEl.innerHTML = "";

  // Header hari
  dayNames.forEach((day) => {
    const headerDay = document.createElement("div");
    headerDay.className = "header-day";
    headerDay.textContent = day;
    calendarEl.appendChild(headerDay);
  });

  const firstDay = new Date(year, month, 1);
  const lastDate = new Date(year, month + 1, 0).getDate();
  const startDay = firstDay.getDay();

  // Hari kosong di awal bulan
  for (let i = 0; i < startDay; i++) {
    const emptyDiv = document.createElement("div");
    emptyDiv.className = "day";
    calendarEl.appendChild(emptyDiv);
  }

  // Hari dalam bulan
  const today = new Date();
  for (let d = 1; d <= lastDate; d++) {
    const dayDiv = document.createElement("div");
    dayDiv.className = "day";

    // Tambahkan kelas khusus untuk weekend
    const dayIndex = new Date(year, month, d).getDay();
    if (dayIndex === 0 || dayIndex === 6) {
      dayDiv.classList.add("weekend");
    }

    // Tambahkan kelas khusus untuk hari ini
    if (
      d === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear()
    ) {
      dayDiv.classList.add("today");
    }

    const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(
      d
    ).padStart(2, "0")}`;
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
  rentals.forEach((rental) => {
    const start = new Date(rental.start_date);
    const end = new Date(rental.end_date);

    let current = new Date(start);
    while (current <= end) {
      const dateStr = current.toISOString().split("T")[0];

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
    const dayNumber = parseInt(dateStr.split("-")[2]);
    const dayEls = document.querySelectorAll(".day .day-number");

    for (const dayEl of dayEls) {
      if (parseInt(dayEl.textContent) === dayNumber) {
        const dayContainer = dayEl.parentElement;
        const badge = dayContainer.querySelector(".event-badge");
        const eventsContainer = dayContainer.querySelector(".events-container");

        badge.textContent = eventsByDate[dateStr].length;

        eventsByDate[dateStr].forEach((rental) => {
          const eventItem = document.createElement("div");
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
  if (rental.status === "ditolak") return "event-rejected";
  if (rental.confirmed_by_admin) return "event-approved";
  return "event-pending";
}

// Dapatkan label status
function getStatusLabel(rental) {
  if (rental.status === "ditolak") return "Ditolak";
  if (rental.confirmed_by_admin) return "Disetujui";
  return "Menunggu Persetujuan";
}

// Buka modal detail
function openModal(dateStr, rentalId = null) {
  document.getElementById("modalDate").textContent = formatDate(dateStr);
  rentalList.innerHTML = "";

  // Filter rentals untuk tanggal yang dipilih
  const date = new Date(dateStr);
  const dailyRentals = rentals.filter((rental) => {
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
    dailyRentals.forEach((rental) => {
      const rentalEntry = document.createElement("div");
      rentalEntry.className = "rental-entry";
      rentalEntry.innerHTML = `
                <div class="rental-header">
                    <h4 class="rental-title">${rental.item_name}</h4>
                    <div class="rental-status ${getStatusClass(
                      rental
                    )}">${getStatusLabel(rental)}</div>
                </div>
                <div class="rental-details">
                    <div><strong>Penyewa:</strong> ${rental.user_name}</div>
                    <div><strong>Periode:</strong> ${formatDate(
                      rental.start_date
                    )} s/d ${formatDate(rental.end_date)}</div>
                </div>
                ${
                  !rental.confirmed_by_admin && rental.status !== "ditolak"
                    ? `
                <div class="rental-actions">
                    <button class="btn btn-approve" onclick="confirmRental(${rental.id}, true)">
                        Setujui
                    </button>
                    <button class="btn btn-reject" onclick="confirmRental(${rental.id}, false)">
                        Tolak
                    </button>
                </div>
                `
                    : ""
                }
            `;
      rentalList.appendChild(rentalEntry);
    });
  }

  modal.classList.add("active");
}

// Dapatkan kelas status
function getStatusClass(rental) {
  if (rental.status === "ditolak") return "status-rejected";
  if (rental.confirmed_by_admin) return "status-approved";
  return "status-pending";
}

// Format tanggal untuk tampilan
function formatDate(dateStr) {
  const date = new Date(dateStr);
  return `${date.getDate()} ${
    monthNames[date.getMonth()]
  } ${date.getFullYear()}`;
}

// Konfirmasi penyewaan (setujui/tolak)
function confirmRental(id, approve) {
  if (
    confirm(
      `Apakah Anda yakin ingin ${
        approve ? "menyetujui" : "menolak"
      } penyewaan ini?`
    )
  ) {
    const url = approve
      ? `approve-rental.php?id=${id}`
      : `reject-rental.php?id=${id}`;

    fetch(url)
      .then((response) => {
        if (response.ok) {
          // Refresh data
          fetchRentals();
          // Tutup modal
          closeModal();
          alert(
            `Penyewaan telah berhasil ${approve ? "disetujui" : "ditolak"}!`
          );
        } else {
          throw new Error("Network response was not ok");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat memproses permintaan");
      });
  }
}

// Tutup modal
function closeModal() {
  modal.classList.remove("active");
}

// Navigasi bulan
function navigateMonth(direction) {
  if (direction === "prev") {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
  } else if (direction === "next") {
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
monthSelect.addEventListener("change", () => {
  currentMonth = parseInt(monthSelect.value);
  generateCalendar(currentMonth, currentYear);
});

yearSelect.addEventListener("change", () => {
  currentYear = parseInt(yearSelect.value);
  generateCalendar(currentMonth, currentYear);
});

prevMonthBtn.addEventListener("click", () => navigateMonth("prev"));
nextMonthBtn.addEventListener("click", () => navigateMonth("next"));
todayBtn.addEventListener("click", goToToday);

// Inisialisasi
setupSelects();
fetchRentals();
