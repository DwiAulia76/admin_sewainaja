// Fungsi untuk menampilkan modal detail penyewaan
function showRentalDetail(rentalId) {
  console.log("Menampilkan detail penyewaan ID:", rentalId);
  const rental = window.rentalData.find((r) => r.id == rentalId);

  if (rental) {
    // Hitung durasi penyewaan
    const startDate = new Date(rental.start_date);
    const endDate = new Date(rental.end_date);
    const duration =
      Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

    // Format harga
    const formatter = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0,
    });

    // Isi data ke modal
    document.getElementById("detail-product").textContent = rental.product_name;
    document.getElementById("detail-user").textContent = rental.user_name;
    document.getElementById("detail-start").textContent =
      startDate.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      });
    document.getElementById("detail-end").textContent =
      endDate.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      });
    document.getElementById("detail-duration").textContent = `${duration} hari`;
    document.getElementById("detail-status").textContent = rental.status;
    document.getElementById("detail-status").className = rental.status;
    document.getElementById("detail-price").textContent = formatter.format(
      rental.total_price
    );

    // Tampilkan modal
    document.getElementById("rentalDetailModal").style.display = "block";
    document.body.classList.add("modal-open");
  } else {
    console.error("Penyewaan tidak ditemukan untuk ID:", rentalId);
  }
}

// Fungsi untuk menutup modal
function closeModal() {
  document.getElementById("rentalDetailModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

// Event listener untuk modal
document.addEventListener("DOMContentLoaded", function () {
  console.log("Modal script loaded");

  // Tutup modal saat tombol close (x) di header diklik
  const closeModalBtn = document.getElementById("closeModalBtn");
  if (closeModalBtn) {
    closeModalBtn.addEventListener("click", closeModal);
  } else {
    console.error("Tombol closeModalBtn tidak ditemukan");
  }

  // Tutup modal saat tombol "Tutup" di footer diklik
  const closeModalFooterBtn = document.getElementById("closeModal");
  if (closeModalFooterBtn) {
    closeModalFooterBtn.addEventListener("click", closeModal);
  } else {
    console.error("Tombol closeModal tidak ditemukan");
  }

  // Tutup modal saat klik di luar konten modal
  window.addEventListener("click", function (event) {
    const modal = document.getElementById("rentalDetailModal");
    if (modal && event.target === modal) {
      closeModal();
    }
  });

  // Tutup modal dengan tombol ESC
  document.addEventListener("keydown", function (event) {
    const modal = document.getElementById("rentalDetailModal");
    if (modal && modal.style.display === "block" && event.key === "Escape") {
      closeModal();
    }
  });

  // Ekspos fungsi ke global scope
  window.showRentalDetail = showRentalDetail;
  window.closeModal = closeModal;
});
