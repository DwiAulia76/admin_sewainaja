// Toggle sidebar on mobile
document.getElementById("sidebarToggle").addEventListener("click", function () {
  document.getElementById("sidebar").classList.toggle("active");
});

// Hide sidebar when clicking outside on mobile
document.addEventListener("click", function (event) {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("sidebarToggle");

  if (
    window.innerWidth <= 768 &&
    sidebar.classList.contains("active") &&
    !sidebar.contains(event.target) &&
    event.target !== toggleBtn
  ) {
    sidebar.classList.remove("active");
  }
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
