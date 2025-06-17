// Toggle sidebar on small screens (if needed)
document
  .querySelector(".mobile-toggle")
  ?.addEventListener("click", function () {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");
  });

// Adjust main content padding on window resize
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
