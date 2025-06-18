<div id="modalAddItem" class="modal">
    <div class="modal-content">
        <span class="close-button" id="closeModal">&times;</span>
        <h2>Tambah Barang</h2>
        <form id="addItemForm" action="config/add_item.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="category">Kategori:</label>
                <input type="text" id="category" name="category" required>
            </div>

            <div class="form-group">
                <label for="price_per_day">Harga per Hari:</label>
                <input type="number" id="price_per_day" name="price_per_day" required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="disewa">Disewa</option>
                </select>
            </div>

            <div class="form-group">
                <label for="photo">Foto:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>

            <div class="form-group">
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>