document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editItemModal");
  const closeBtns = editModal.querySelectorAll(".close");

  // Fungsi untuk membuka modal edit dan mengisi data
  window.openEditModal = function (item) {
    // Isi form dengan data produk
    document.getElementById("edit_id").value = item.id;
    document.getElementById("edit_name").value = item.name;
    document.getElementById("edit_description").value = item.description;
    document.getElementById("edit_price").value = item.price_per_day;
    document.getElementById("edit_existing_photo").value = item.image;

    // Set kategori
    const categorySelect = document.getElementById("edit_category");
    for (let i = 0; i < categorySelect.options.length; i++) {
      if (categorySelect.options[i].value === item.category) {
        categorySelect.selectedIndex = i;
        break;
      }
    }

    // Set status
    const statusSelect = document.getElementById("edit_status");
    statusSelect.value = item.status;

    // Tampilkan foto saat ini
    const currentPhoto = document.getElementById("current_photo");
    const noPhotoText = document.getElementById("no_photo_text");

    if (item.image && item.image.trim() !== "") {
      currentPhoto.src = `/admin_sewainaja/${item.image}`;
      currentPhoto.style.display = "block";
      noPhotoText.style.display = "none";
    } else {
      currentPhoto.style.display = "none";
      noPhotoText.style.display = "block";
    }

    // Tampilkan modal
    editModal.style.display = "block";
  };

  // Event listener untuk tombol tutup
  closeBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      editModal.style.display = "none";
    });
  });

  // Tutup modal saat klik di luar area modal
  window.addEventListener("click", function (event) {
    if (event.target == editModal) {
      editModal.style.display = "none";
    }
  });
});
