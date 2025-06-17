// Search functionality
document.getElementById("searchInput").addEventListener("input", function () {
  const searchTerm = this.value.toLowerCase().trim();
  const cards = document.querySelectorAll(".card");

  cards.forEach((card) => {
    const name = card.dataset.name;
    const category = card.dataset.category;
    const status = card.dataset.status;

    // Check if search term exists in name, category or status
    if (
      name.includes(searchTerm) ||
      category.includes(searchTerm) ||
      status.includes(searchTerm) ||
      searchTerm === ""
    ) {
      card.style.display = "flex";
    } else {
      card.style.display = "none";
    }
  });
});

// Add focus style to search input
document.getElementById("searchInput").addEventListener("focus", function () {
  this.parentElement.style.boxShadow = "0 0 0 3px rgba(147, 197, 253, 0.3)";
  this.style.borderColor = "#93c5fd";
});

document.getElementById("searchInput").addEventListener("blur", function () {
  this.parentElement.style.boxShadow = "none";
  this.style.borderColor = "#cbd5e1";
});

// Search button click focuses input
document.getElementById("searchButton").addEventListener("click", function () {
  document.getElementById("searchInput").focus();
});

// Adjust main content padding on resize
window.addEventListener("resize", function () {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector(".main-content");

  if (window.innerWidth > 768) {
    sidebar.classList.remove("active");
    mainContent.style.marginLeft = "240px";
  } else {
    mainContent.style.marginLeft = "0";
  }
});

// Enhanced search with animation
document.getElementById("searchInput").addEventListener("input", function () {
  const searchTerm = this.value.toLowerCase().trim();
  const cards = document.querySelectorAll(".card");
  let visibleCount = 0;

  cards.forEach((card, index) => {
    const name = card.dataset.name;
    const category = card.dataset.category;
    const status = card.dataset.status;

    if (
      name.includes(searchTerm) ||
      category.includes(searchTerm) ||
      status.includes(searchTerm) ||
      searchTerm === ""
    ) {
      card.style.display = "flex";
      card.style.animationDelay = `${visibleCount * 0.05}s`;
      card.classList.add("animate");
      visibleCount++;
    } else {
      card.style.display = "none";
      card.classList.remove("animate");
    }
  });

  // Update heading with search info
  const heading = document.querySelector(".header-bar h1");
  if (searchTerm !== "") {
    heading.textContent = `Daftar Barang (${visibleCount} hasil ditemukan)`;
  } else {
    heading.textContent = "Daftar Barang";
  }
});
