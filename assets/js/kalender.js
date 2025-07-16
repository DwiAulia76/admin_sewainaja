document.addEventListener("DOMContentLoaded", function () {
  const calendar = document.getElementById("calendar");
  const monthSelect = document.getElementById("monthSelect");
  const yearSelect = document.getElementById("yearSelect");
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const resetButton = document.getElementById("resetButton");
  const filterBy = document.getElementById("filterBy");
  const statusFilter = document.getElementById("statusFilter");

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
    const calendarTable = document.createElement("table");
    calendarTable.className = "calendar-table";

    // Header hari
    const headerRow = document.createElement("tr");
    const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
    dayNames.forEach((day) => {
      const th = document.createElement("th");
      th.textContent = day;
      headerRow.appendChild(th);
    });
    calendarTable.appendChild(headerRow);

    let date = 1;
    let row;

    for (let i = 0; i < 6; i++) {
      if (date > daysInMonth) break;

      row = document.createElement("tr");

      for (let j = 0; j < 7; j++) {
        const cell = document.createElement("td");

        if (i === 0 && j < firstDay) {
          // Sel kosong sebelum tanggal 1
          cell.innerHTML = "";
        } else if (date > daysInMonth) {
          // Sel kosong setelah akhir bulan
          cell.innerHTML = "";
        } else {
          // Format tanggal untuk perbandingan
          const cellDate = new Date(year, month, date);
          const cellDateStr = formatDate(cellDate);

          // Nomor tanggal
          const numberDiv = document.createElement("div");
          numberDiv.className = "date-number";
          numberDiv.textContent = date;
          cell.appendChild(numberDiv);

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
              cell.appendChild(tag);
            }
          });

          date++;
        }

        row.appendChild(cell);
      }

      calendarTable.appendChild(row);
    }

    calendar.appendChild(calendarTable);

    // Tambahkan event listener untuk detail penyewaan
    document.querySelectorAll(".event-tag").forEach((tag) => {
      tag.addEventListener("click", function () {
        const rentalId = this.dataset.rentalId;
        showRentalDetail(rentalId);
      });
    });
  }

  function showRentalDetail(rentalId) {
    const rental = rentalData.find((r) => r.id == rentalId);

    if (rental) {
      alert(`Detail Penyewaan:\n
Produk: ${rental.product_name}\n
Penyewa: ${rental.user_name}\n
Tanggal Mulai: ${new Date(rental.start_date).toLocaleDateString()}\n
Tanggal Selesai: ${new Date(rental.end_date).toLocaleDateString()}\n
Status: ${rental.status}`);
    }
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
  document.getElementById("prevMonth").addEventListener("click", () => {
    currentMonth--;
    if (currentMonth < 0) {
      currentMonth = 11;
      currentYear--;
    }
    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
    renderCalendar(currentMonth, currentYear);
  });

  document.getElementById("nextMonth").addEventListener("click", () => {
    currentMonth++;
    if (currentMonth > 11) {
      currentMonth = 0;
      currentYear++;
    }
    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
    renderCalendar(currentMonth, currentYear);
  });

  document.getElementById("todayBtn").addEventListener("click", () => {
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

  // modal
  // Fungsi untuk menampilkan modal detail
  function showRentalDetail(rentalId) {
    const rental = rentalData.find((r) => r.id == rentalId);

    if (rental) {
      // Hitung durasi penyewaan
      const startDate = new Date(rental.start_date);
      const endDate = new Date(rental.end_date);
      const duration =
        Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

      // Format harga
      const formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
      });

      // Isi data ke modal
      document.getElementById("detail-product").textContent =
        rental.product_name;
      document.getElementById("detail-user").textContent = rental.user_name;
      document.getElementById("detail-start").textContent =
        startDate.toLocaleDateString("id-ID");
      document.getElementById("detail-end").textContent =
        endDate.toLocaleDateString("id-ID");
      document.getElementById(
        "detail-duration"
      ).textContent = `${duration} hari`;
      document.getElementById("detail-status").textContent = rental.status;
      document.getElementById("detail-status").className = rental.status;
      document.getElementById("detail-price").textContent = formatter.format(
        rental.total_price
      );

      // Tampilkan modal
      document.getElementById("rentalDetailModal").style.display = "block";
    }
  }

  // Fungsi untuk menutup modal
  function closeModal() {
    document.getElementById("rentalDetailModal").style.display = "none";
  }

  // Event listener untuk modal
  document.addEventListener("DOMContentLoaded", function () {
    // Tutup modal saat tombol close diklik
    document.querySelector(".close").addEventListener("click", closeModal);

    // Tutup modal saat tombol tutup diklik
    document.getElementById("closeModal").addEventListener("click", closeModal);

    // Tutup modal saat klik di luar konten modal
    window.addEventListener("click", function (event) {
      const modal = document.getElementById("rentalDetailModal");
      if (event.target === modal) {
        closeModal();
      }
    });

    // Tutup modal dengan tombol ESC
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeModal();
      }
    });
  });
});
