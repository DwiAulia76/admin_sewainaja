document.addEventListener("DOMContentLoaded", function () {
  const editButtons = document.querySelectorAll(".edit");
  const deleteButtons = document.querySelectorAll(".delete");
  const editItemModal = document.getElementById("editItemModal");

  editButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const itemId = this.getAttribute("data-id");
      const card = this.closest(".card");

      const name = card.querySelector("h3").textContent;
      const description = card.querySelector(".description").textContent;
      const price = card
        .querySelector(".price")
        .textContent.replace("Rp ", "")
        .replace(/\./g, "");
      const category = card.querySelector(".category").textContent;
      const status = card
        .querySelector(".status-badge")
        .classList.contains("available")
        ? "available"
        : "rented";
      const imageUrl = card.querySelector(".image-container img")
        ? card.querySelector(".image-container img").src
        : "";

      document.getElementById("editId").value = itemId;
      document.getElementById("editName").value = name;
      document.getElementById("editDescription").value = description;
      document.getElementById("editPrice").value = price;
      document.getElementById("editCategory").value = category;
      document.getElementById("editStatus").value = status;

      const currentImageContainer = document.getElementById(
        "currentImageContainer"
      );
      currentImageContainer.innerHTML = "";
      if (imageUrl) {
        currentImageContainer.innerHTML = `<img src="${imageUrl}" alt="Current Image" style="max-width: 100px; max-height: 100px; border-radius: 4px;">`;
      }

      editItemModal.style.display = "block";
    });
  });

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const itemId = this.getAttribute("data-id");
      if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
        fetch(`delete_item.php?id=${itemId}`, {
          method: "DELETE",
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              const card = this.closest(".card");
              card.remove();
              alert("Produk berhasil dihapus");
              location.reload();
            } else {
              alert("Gagal menghapus produk");
            }
          })
          .catch((error) => console.error("Error:", error));
      }
    });
  });

  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");

  if (searchButton) {
    searchButton.addEventListener("click", performSearch);
  }

  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        performSearch();
      }
    });
  }

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase();
    const cards = document.querySelectorAll(".card");

    cards.forEach((card) => {
      const name = card.querySelector("h3").textContent.toLowerCase();
      const description = card
        .querySelector(".description")
        .textContent.toLowerCase();

      if (name.includes(searchTerm) || description.includes(searchTerm)) {
        card.style.display = "";
      } else {
        card.style.display = "none";
      }
    });
  }
});
