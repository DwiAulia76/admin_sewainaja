<!-- Modal Tambah Produk -->
<div id="addItemModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Tambah Produk</h3>
      <span class="close">&times;</span>
    </div>
    <form method="POST" action="./backweb/tambah_produk.php" enctype="multipart/form-data">
      <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="name" required />
      </div>
      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" required></textarea>
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <!-- PERUBAHAN DISINI -->
        <select name="category" required>
          <option value="">Pilih Kategori</option>
          <option value="Alat Pesta & Acara">Alat Pesta & Acara</option>
          <option value="Perlengkapan Bayi & Anak">Perlengkapan Bayi & Anak</option>
          <option value="Alat Konstruksi & Perkakas">Alat Konstruksi & Perkakas</option>
          <option value="Perlengkapan Outdoor & Camping">Perlengkapan Outdoor & Camping</option>
          <option value="Elektronik Khusus">Elektronik Khusus</option>
          <option value="Peralatan Olahraga">Peralatan Olahraga</option>
          <option value="Perabot Rumah Sementara">Perabot Rumah Sementara</option>
          <option value="Alat Kebersihan & Perawatan">Alat Kebersihan & Perawatan</option>
        </select>
      </div>
      <div class="form-group">
        <label>Harga per Hari</label>
        <input type="number" name="price_per_day" required />
      </div>
      <div class="form-group">
        <label>Status</label>
        <select name="status" required>
          <option value="available">Tersedia</option>
          <option value="rented">Disewa</option>
        </select>
      </div>
      <div class="form-group">
        <label>Foto Produk</label>
        <input type="file" name="photo" accept="image/*" required />
      </div>
      <div class="form-actions">
        <button type="submit" class="btn-primary">Simpan</button>
        <button type="button" class="btn-secondary close">Batal</button>
      </div>
    </form>
  </div>
</div>