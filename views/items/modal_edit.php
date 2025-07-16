<!-- Modal Edit Produk -->
<div id="editItemModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h3>Edit Produk</h3>
      <span class="close">&times;</span>
    </div>
    <form method="POST" action="./backweb/edit_produk.php" enctype="multipart/form-data">
      <input type="hidden" name="id" id="edit_id">
      
      <div class="form-group">
        <label>Nama Produk</label>
        <input type="text" name="name" id="edit_name" required />
      </div>
      
      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="description" id="edit_description" required></textarea>
      </div>
      
      <div class="form-group">
        <label>Kategori</label>
        <select name="category" id="edit_category" required>
          <option value="">Pilih Kategori</option>
          <option value="Alat Pesta & Acara">Alat Pesta & Acara</option>
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
        <input type="number" name="price_per_day" id="edit_price" required />
      </div>
      
      <div class="form-group">
        <label>Status</label>
        <select name="status" id="edit_status" required>
          <option value="available">Tersedia</option>
          <option value="rented">Disewa</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>Foto Produk</label>
        <div class="current-photo">
          <img id="current_photo" src="" alt="Foto Saat Ini" style="max-width: 200px; display: none;">
          <p id="no_photo_text" style="display: none;">Tidak ada foto</p>
        </div>
        <input type="file" name="photo" accept="image/*" />
        <small class="form-hint">Biarkan kosong jika tidak ingin mengubah foto</small>
      </div>
      
      <div class="form-actions">
        <button type="submit" class="btn-primary">Simpan Perubahan</button>
        <button type="button" class="btn-secondary close">Batal</button>
      </div>
    </form>
  </div>
</div>