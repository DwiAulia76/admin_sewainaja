document.addEventListener("DOMContentLoaded", function () {
  const calendar = document.getElementById("calendar");
  const monthSelect = document.getElementById("monthSelect");
  const yearSelect = document.getElementById("yearSelect");

  const today = new Date();
  let currentMonth = today.getMonth();
  let currentYear = today.getFullYear();

  function initMonthYearSelects() {
    for (let m = 0; m < 12; m++) {
      const option = document.createElement("option");
      option.value = m;
      option.text = new Date(2020, m).toLocaleString("id-ID", {
        month: "long",
      });
      monthSelect.appendChild(option);
    }

    for (let y = currentYear - 3; y <= currentYear + 3; y++) {
      const option = document.createElement("option");
      option.value = y;
      option.text = y;
      yearSelect.appendChild(option);
    }

    monthSelect.value = currentMonth;
    yearSelect.value = currentYear;
  }

  function renderCalendar(month, year) {
    calendar.innerHTML = "";
    const firstDay = new Date(year, month).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const calendarTable = document.createElement("table");
    calendarTable.classList.add("calendar-table");

    const headerRow = document.createElement("tr");
    ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"].forEach((day) => {
      const th = document.createElement("th");
      th.textContent = day;
      headerRow.appendChild(th);
    });
    calendarTable.appendChild(headerRow);

    let date = 1;
    for (let i = 0; i < 6; i++) {
      const row = document.createElement("tr");
      for (let j = 0; j < 7; j++) {
        const cell = document.createElement("td");

        if (i === 0 && j < firstDay) {
          cell.innerHTML = "";
        } else if (date > daysInMonth) {
          break;
        } else {
          const cellDate = new Date(year, month, date);
          cell.innerHTML = `<div class="date-number">${date}</div>`;

          // Cek apakah ada transaksi pada tanggal ini
          const dateStr = cellDate.toISOString().slice(0, 10); // YYYY-MM-DD
          rentalData.forEach((rental) => {
            const start = new Date(rental.start_date);
            const end = new Date(rental.end_date);
            if (cellDate >= start && cellDate <= end) {
              const tag = document.createElement("div");
              tag.className = `event-tag ${rental.status}`;
              tag.innerText = rental.product_name;
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
  }

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

  initMonthYearSelects();
  renderCalendar(currentMonth, currentYear);
});
