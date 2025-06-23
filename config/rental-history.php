<?php

require_once 'database.php';

$stmt = $pdo->query("SELECT r.*, i.name AS item_name, u.name AS user_name 
                     FROM rentals r 
                     JOIN items i ON r.item_id = i.id 
                     JOIN users u ON r.penyewa_id = u.id 
                     WHERE r.status IN ('selesai', 'terlambat')
                     ORDER BY r.end_date DESC");

echo "<h2>Riwayat Sewa</h2><table border='1' cellpadding='8'>";
echo "<tr><th>Barang</th><th>Penyewa</th><th>Tgl Mulai</th><th>Tgl Selesai</th><th>Status</th></tr>";
while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>{$row['item_name']}</td>";
    echo "<td>{$row['user_name']}</td>";
    echo "<td>{$row['start_date']}</td>";
    echo "<td>{$row['end_date']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "</tr>";
}
echo "</table>";