<!-- Tabel Penyewaan -->
<div class="rental-container">
  <h2 class="title">Daftar Penyewaan</h2>
  <table class="rental-table">
    <thead>
      <tr>
        <th>ID Transaksi</th>
        <th>Produk</th>
        <th>Penyewa</th>
        <th>Tgl Mulai</th>
        <th>Tgl Selesai</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($transactions as $i => $trx): ?>
      <tr>
        <td><?=  str_pad($trx['id'], 3, '0', STR_PAD_LEFT) ?></td>
        <td>
          <div class="product-info">
            <img src="/admin_sewainaja/<?= $trx['product_image'] ?>" class="product-img" alt="Produk"> 
            
            <span><?= htmlspecialchars($trx['product_name']) ?></span>
          </div>
        </td>
        <td><?= htmlspecialchars($trx['user_name']) ?></td>
        <td><?= date('d-m-Y', strtotime($trx['start_date'])) ?></td>
        <td><?= date('d-m-Y', strtotime($trx['end_date'])) ?></td>
        <td>Rp<?= number_format($trx['total_price'], 0, ',', '.') ?></td>
        <td>
          <span class="status <?= strtolower($trx['status']) ?>"><?= $trx['status'] ?></span>
        </td>
        <td>
          <button class="btn btn-light">Lihat</button>
          <button class="btn btn-blue">Ubah</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
