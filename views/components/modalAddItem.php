<div id="modalAddItem" class="modal hidden">
    <div class="modal-content">
        <span class="close-button" id="closeModal">&times;</span>
        <h2>Tambah Barang</h2>
        <form id="addItemForm" action="config/add_item.php" method="POST" enctype="multipart/form-data">
            <label>Nama:</label>
            <input type="text" name="name" required>

            <label>Deskripsi:</label>
            <textarea name="description" required></textarea>

            <label>Kategori:</label>
            <input type="text" name="category" required>

            <label>Harga per Hari:</label>
            <input type="number" name="price_per_day" required>

            <label>Status:</label>
            <select name="status" required>
                <option value="tersedia">Tersedia</option>
                <option value="disewa">Disewa</option>
            </select>

            <label>Foto:</label>
            <input type="file" name="photo" accept="image/*" required>

            <button type="submit">Simpan</button>
        </form>
    </div>
</div>
