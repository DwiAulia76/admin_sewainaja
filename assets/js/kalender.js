document.addEventListener("DOMContentLoaded", function () {
  const calendar = document.getElementById("calendar");
  const monthSelect = document.getElementById("monthSelect");
  const yearSelect = document.getElementById("yearSelect");
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resetButton = document.getElementById("resetButton");
  const filterBy = document.getElementById("filterBy");
  const statusFilter = document.getElementById("statusFilter");
  const prevMonthBtn = document.getElementById("prevMonth");
  const nextMonthBtn = document.getElementById("nextMonth");
  const todayBtn = document.getElementById("todayBtn");

  const today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();

  function initMonthYearSelects() {
    monthSelect.innerHTML = "";
    yearSelect.innerHTML = "";

    // Generate bulan
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

    monthNames.forEach((month, index) => {
      const option = document.createElement("option");
      option.value = index;
      option.textContent = month;
      monthSelect.appendChild(option);
    });

    // Generate tahun (5 tahun terakhir dan 3 tahun mendatang)
    for (let y = currentYear - 5; y <= currentYear + 3; y++) {
      const option = document.createElement("option");
      option.value = y;
      option.textContent = y;
      yearSelect.appendChild(option);
    }

    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
  }

  // Format tanggal ke YYYY-MM-DD
  function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  function renderCalendar(month, year) {
    calendar.innerHTML = "";

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const dayNames = [
      "Minggu",
      "Senin",
      "Selasa",
      "Rabu",
      "Kamis",
      "Jumat",
      "Sabtu",
    ];

    // Buat header hari
    dayNames.forEach((day) => {
      const dayHeader = document.createElement("div");
      dayHeader.className = "day-header";
      dayHeader.textContent = day;
      calendar.appendChild(dayHeader);
    });

    // Sel kosong untuk hari sebelum tanggal 1
    for (let i = 0; i < firstDay; i++) {
      const emptyCell = document.createElement("div");
      emptyCell.className = "day-cell empty";
      calendar.appendChild(emptyCell);
    }

    // Isi tanggal
    for (let date = 1; date <= daysInMonth; date++) {
      const dayCell = document.createElement("div");
      dayCell.className = "day-cell";

      const numberDiv = document.createElement("div");
      numberDiv.className = "day-number";
      numberDiv.textContent = date;
      dayCell.appendChild(numberDiv);

      const cellDate = new Date(year, month, date);
      const cellDateStr = formatDate(cellDate);

      // Tambahkan event jika ada
      filteredRentalData.forEach((rental) => {
        const startDate = formatDate(rental.start_date);
        const endDate = formatDate(rental.end_date);

        if (cellDateStr >= startDate && cellDateStr <= endDate) {
          const tag = document.createElement("div");
          tag.className = `event-tag ${rental.status}`;
          tag.textContent = rental.product_name;
          tag.title = `${rental.user_name} (${rental.status})`;
          tag.dataset.rentalId = rental.id;
          dayCell.appendChild(tag);
        }
      });

      calendar.appendChild(dayCell);
    }

    // Tambahkan event listener untuk detail penyewaan
    document.querySelectorAll(".event-tag").forEach((tag) => {
      tag.addEventListener("click", function () {
        const rentalId = this.dataset.rentalId;
        // Panggil fungsi dari modal.js
        if (typeof showRentalDetail === "function") {
          showRentalDetail(rentalId);
        }
      });
    });
  }

  function filterRentals() {
    const searchTerm = searchInput.value.toLowerCase();
    const filterByValue = filterBy.value;
    const statusValue = statusFilter.value;

    filteredRentalData = rentalData.filter((rental) => {
      // Filter berdasarkan status
      if (statusValue !== "all" && rental.status !== statusValue) {
        return false;
      }

      // Filter berdasarkan pencarian
      if (searchTerm) {
        if (filterByValue === "all") {
          return (
            rental.product_name.toLowerCase().includes(searchTerm) ||
            rental.user_name.toLowerCase().includes(searchTerm) ||
            rental.status.toLowerCase().includes(searchTerm)
          );
        } else {
          return String(rental[filterByValue])
            .toLowerCase()
            .includes(searchTerm);
        }
      }

      return true;
    });

    renderCalendar(currentMonth, currentYear);
  }

  // Event Listeners
  prevMonthBtn.addEventListener("click", () => {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
    renderCalendar(currentMonth, currentYear);
  });

  nextMonthBtn.addEventListener("click", () => {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
    renderCalendar(currentMonth, currentYear);
  });

  todayBtn.addEventListener("click", () => {
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
    renderCalendar(currentMonth, currentYear);
  });

  monthSelect.addEventListener("change", () => {
    currentMonth = parseInt(monthSelect.value);
    renderCalendar(currentMonth, currentYear);
  });

  yearSelect.addEventListener("change", () => {
    currentYear = parseInt(yearSelect.value);
    renderCalendar(currentMonth, currentYear);
  });

  // Pencarian dan filter
  searchButton.addEventListener("click", filterRentals);

  resetButton.addEventListener("click", () => {
    searchInput.value = "";
    filterBy.value = "all";
    statusFilter.value = "all";
    filteredRentalData = [...rentalData];
    renderCalendar(currentMonth, currentYear);
  });

  // Inisialisasi
  initMonthYearSelects();
  renderCalendar(currentMonth, currentYear);
});
