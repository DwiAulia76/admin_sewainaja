document.addEventListener("DOMContentLoaded", function () {
  // === Inisialisasi Elemen ===
  const addItemModal = document.getElementById("addItemModal");
  const addItemBtn = document.getElementById("addItemBtn");
  const editModal = document.getElementById("editModal"); // Pastikan ID sesuai
  const closeButtons = document.querySelectorAll(".close");

  // Selektor khusus untuk tombol edit
  const editButtons = document.querySelectorAll(".editBtn");

  // === Handler Tombol TAMBAH PRODUK ===
  if (addItemBtn && addItemModal) {
    addItemBtn.addEventListener("click", function () {
      addItemModal.style.display = "block";
    });
  }

  // === Handler Tombol EDIT PRODUK ===
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Dapatkan card terdekat dari tombol yang diklik
      const card = this.closest(".card");

      // Ambil data dari elemen card
      const id = this.getAttribute("data-id") || "";
      const name = card.querySelector("h3").textContent || "";
      const description = card.querySelector(".description").textContent || "";
      const category = card.querySelector(".category").textContent || "";

      // Ambil harga (dengan pembersihan teks)
      const priceText = card.querySelector(".price").textContent || "";
      const price = priceText
        .replace("Rp ", "")
        .replace(/\./g, "")
        .replace("/hari", "")
        .trim();

      // Ambil status dari badge
      const statusBadge = card.querySelector(".status-badge");
      let status = "available";
      if (statusBadge) {
        status = statusBadge.classList.contains("available")
          ? "available"
          : "rented";
      }
      // Ambil foto (dari atribut data tombol)
      const photo = this.getAttribute("data-photo") || "";

      // Isi form edit
      document.getElementById("editId").value = id;
      document.getElementById("editName").value = name;
      document.getElementById("editDesc").value = description; // Deskripsi
      document.getElementById("editCat").value = category; // Kategori
      document.getElementById("editPrice").value = price;
      document.getElementById("editStatus").value = status;

      // Handle foto
      const photoPreview = document.getElementById("currentPhotoPreview");
      document.getElementById("existingPhoto").value = photo;

      if (photo) {
        // Path relatif untuk preview
        const imageUrl = `/admin_sewainaja/${photo}`;
        photoPreview.innerHTML = `<img src="${imageUrl}" alt="Current Photo" style="max-height:100px; border-radius:4px;">`;
      } else {
        photoPreview.innerHTML = "<p>Tidak ada foto</p>";
      }

      // Tampilkan modal
      const editModal = document.getElementById("editModal");
      if (editModal) {
        editModal.style.display = "block";
      }
    });
  });

  // === Handler Tombol CLOSE (x) ===
  closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (addItemModal) addItemModal.style.display = "none";
      if (editModal) editModal.style.display = "none";
    });
  });

  // === Handler Klik DI LUAR MODAL ===
  window.addEventListener("click", function (event) {
    if (event.target === addItemModal) {
      addItemModal.style.display = "none";
    }
    if (event.target === editModal) {
      editModal.style.display = "none";
    }
  });
});
