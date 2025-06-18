// Gabungkan semua event listener dalam satu DOMContentLoaded
document.addEventListener("DOMContentLoaded", function () {
  // Modal Add Item
  const addModal = document.getElementById("modalAddItem");
  const openAddBtn = document.getElementById("openModal");
  const closeAddBtn = document.getElementById("closeModal");

  if (openAddBtn && addModal) {
    openAddBtn.addEventListener("click", function (e) {
      e.preventDefault();
      addModal.style.display = "block";
    });
  }

  if (closeAddBtn) {
    closeAddBtn.addEventListener("click", function () {
      addModal.style.display = "none";
    });
  }

  // Modal Edit Item
  const editModal = document.getElementById("editModal");
  const editButtons = document.querySelectorAll(".edit");
  const closeEditBtn = document.querySelector("#editModal .close");

  if (editButtons.length > 0) {
    editButtons.forEach(function (button) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        // Isi data ke form edit
        document.getElementById("editId").value = this.dataset.id;
        document.getElementById("editName").value = this.dataset.name;
        document.getElementById("editDesc").value = this.dataset.desc;
        document.getElementById("editCat").value = this.dataset.cat;
        document.getElementById("editPrice").value = this.dataset.price;
        document.getElementById("editStatus").value = this.dataset.status;
        document.getElementById("existingPhoto").value = this.dataset.photo;

        // Tampilkan foto saat ini
        const photoPreview = document.getElementById("currentPhotoPreview");
        if (this.dataset.photo) {
          photoPreview.innerHTML = `<img src="../${this.dataset.photo}" alt="Current Photo" width="150">`;
        } else {
          photoPreview.innerHTML = "<p>Tidak ada foto</p>";
        }

        editModal.style.display = "block";
      });
    });
  }

  if (closeEditBtn) {
    closeEditBtn.addEventListener("click", function () {
      editModal.style.display = "none";
    });
  }

  // Tutup modal saat klik di luar
  window.addEventListener("click", function (event) {
    if (event.target === addModal) {
      addModal.style.display = "none";
    }
    if (event.target === editModal) {
      editModal.style.display = "none";
    }
  });
});
