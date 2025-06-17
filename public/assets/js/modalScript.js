document.addEventListener("DOMContentLoaded", () => {
  const openBtn = document.getElementById("openModal");
  const closeBtn = document.getElementById("closeModal");
  const modal = document.getElementById("modalAddItem");

  openBtn.addEventListener("click", () => {
    modal.classList.remove("hidden");
  });

  closeBtn.addEventListener("click", () => {
    modal.classList.add("hidden");
  });

  window.addEventListener("click", function (e) {
    if (e.target === modal) {
      modal.classList.add("hidden");
    }
  });
});
