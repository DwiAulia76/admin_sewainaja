document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("addItemModal");
  const openBtn = document.getElementById("addItemBtn");
  const closeBtns = modal.querySelectorAll(".close");

  openBtn.addEventListener("click", function () {
    modal.style.display = "block";
  });

  closeBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      modal.style.display = "none";
    });
  });

  window.addEventListener("click", function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  });
});
