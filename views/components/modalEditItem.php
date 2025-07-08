<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Barang</h2>
        <form method="post" action="./backweb/edit_item.php" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editId">
            <input type="hidden" name="existing_photo" id="existingPhoto">
            
            <div class="form-group">
                <label for="editName">Nama Barang</label>
                <input type="text" id="editName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="editDesc">Deskripsi</label>
                <textarea id="editDesc" name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="editCat">Kategori</label>
                <input type="text" id="editCat" name="category" required>
            </div>
            
            <div class="form-group">
                <label for="editPrice">Harga per Hari (Rp)</label>
                <input type="number" id="editPrice" name="price_per_day" step="1000" required>
            </div>
            
            <div class="form-group">
                <label for="editStatus">Status</label>
                <select id="editStatus" name="status" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="disewa">Disewa</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="editPhoto">Foto Baru (Opsional)</label>
                <input type="file" id="editPhoto" name="photo" accept="image/*">
            </div>
            
            <div class="form-group">
                <label>Foto Saat Ini:</label>
                <div id="currentPhotoPreview"></div>
            </div>
            
            <div class="form-group">
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>