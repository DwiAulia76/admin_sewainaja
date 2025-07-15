document.addEventListener("DOMContentLoaded", function () {
  const addItemModal = document.getElementById("addItemModal");
  const editItemModal = document.getElementById("editItemModal");
  const closeButtons = document.querySelectorAll(".close");
  const addItemBtn = document.getElementById("addItemBtn");

  if (addItemBtn) {
    addItemBtn.addEventListener("click", function () {
      addItemModal.style.display = "block";
    });
  }

  closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      addItemModal.style.display = "none";
      editItemModal.style.display = "none";
    });
  });

  window.addEventListener("click", function (event) {
    if (event.target === addItemModal) {
      addItemModal.style.display = "none";
    }
    if (event.target === editItemModal) {
      editItemModal.style.display = "none";
    }
  });
});
