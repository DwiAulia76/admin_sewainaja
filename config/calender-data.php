<?php
// === FILE: calendar-data.php ===
require_once 'database.php';

$stmt = $pdo->query("SELECT r.id, i.name AS item_name, u.name AS user_name, r.start_date, r.end_date, r.status, r.confirmed_by_admin
                     FROM rentals r
                     JOIN items i ON r.item_id = i.id
                     JOIN users u ON r.penyewa_id = u.id");

$events = [];
while ($row = $stmt->fetch()) {
    $statusLabel = $row['confirmed_by_admin'] ? 'Diterima' : ($row['status'] === 'ditolak' ? 'Ditolak' : 'Belum Diterima');
    $color = $row['status'] === 'ditolak' ? '#f87171' : ($row['confirmed_by_admin'] ? '#34d399' : '#facc15');

    $events[] = [
        'id' => $row['id'],
        'title' => $row['item_name'] . ' - ' . $row['user_name'] . ' (' . $statusLabel . ')',
        'start' => $row['start_date'],
        'end' => date('Y-m-d', strtotime($row['end_date'] . ' +1 day')),
        'color' => $color
    ];
}
header('Content-Type: application/json');
echo json_encode($events);