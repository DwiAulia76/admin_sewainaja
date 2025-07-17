document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");
  const cards = document.querySelectorAll(".card");
  const noResults = document.querySelector(".no-results");

  function searchProducts() {
    const keyword = searchInput.value.toLowerCase().trim();
    let hasResult = false;

    cards.forEach((card) => {
      const name = card.querySelector("h3")?.textContent.toLowerCase() || "";
      const description =
        card.querySelector(".description")?.textContent.toLowerCase() || "";
      const category =
        card.querySelector(".category")?.textContent.toLowerCase() || "";

      if (
        name.includes(keyword) ||
        description.includes(keyword) ||
        category.includes(keyword)
      ) {
        card.style.display = "block";
        hasResult = true;
      } else {
        card.style.display = "none";
      }
    });

    // Tampilkan / sembunyikan .no-results
    if (noResults) {
      noResults.style.display = hasResult ? "none" : "flex";
    }
  }

  // Klik tombol search
  searchButton.addEventListener("click", searchProducts);

  // Ketik di input
  searchInput.addEventListener("keyup", function () {
    searchProducts();
  });
});
