document.addEventListener("DOMContentLoaded", function () {
  const addItemModal = document.getElementById("addItemModal");
  const addItemBtn = document.getElementById("addItemBtn");
  const editItemModal = document.getElementById("editModal");
  const editButtons = document.querySelectorAll(".edit");
  const closeButtons = document.querySelectorAll(".close");

  // === Tombol TAMBAH PRODUK ===
  if (addItemBtn && addItemModal) {
    addItemBtn.addEventListener("click", function () {
      addItemModal.style.display = "block";
    });
  }

  // === Tombol EDIT PRODUK ===
  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const card = this.closest(".card");
      if (!card) return;

      // Pastikan semua elemen ada sebelum mengambil nilai
      const id = card.dataset.id || "";

      const nameElement = card.querySelector("h3");
      const name = nameElement ? nameElement.innerText.trim() : "";

      const descElement = card.querySelector(".description");
      const description = descElement ? descElement.innerText.trim() : "";

      const categoryElement = card.querySelector(".category");
      const category = categoryElement ? categoryElement.innerText.trim() : "";

      const priceElement = card.querySelector(".price");
      const priceText = priceElement ? priceElement.innerText.trim() : "";
      const price = priceText ? priceText.replace(/\D/g, "") : "";

      const statusBadge = card.querySelector(".status-badge");
      const statusText = statusBadge
        ? statusBadge.innerText.trim().toLowerCase()
        : "";

      const imageElement = card.querySelector(".product-image");
      const image = imageElement ? imageElement.getAttribute("src") : "";

      // Isi form edit
      document.getElementById("editId").value = id;
      document.getElementById("editName").value = name;
      document.getElementById("editDesc").value = description;
      document.getElementById("editCat").value = category;
      document.getElementById("editPrice").value = price;

      // Set status berdasarkan teks badge
      const statusValue = statusText.includes("tersedia")
        ? "available"
        : "rented";
      document.getElementById("editStatus").value = statusValue;

      // Handle foto
      if (image) {
        // Simpan path relatif ke database
        const relativePath = image.replace(window.location.origin, "");
        document.getElementById("existingPhoto").value = relativePath;

        // Tampilkan preview
        document.getElementById("currentPhotoPreview").innerHTML = `
          <img src="${image}" alt="Current Photo" style="max-height:100px;">
        `;
      } else {
        document.getElementById("existingPhoto").value = "";
        document.getElementById("currentPhotoPreview").innerHTML =
          "<p>Tidak ada foto</p>";
      }

      editItemModal.style.display = "block";
    });
  });

  // === Tombol CLOSE (x) ===
  closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      if (addItemModal) addItemModal.style.display = "none";
      if (editItemModal) editItemModal.style.display = "none";
    });
  });

  // === Klik DI LUAR MODAL untuk menutup ===
  window.addEventListener("click", function (event) {
    if (event.target === addItemModal) {
      addItemModal.style.display = "none";
    }
    if (event.target === editItemModal) {
      editItemModal.style.display = "none";
    }
  });
});
