<?php
require_once '../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$stmt = $pdo->query("SELECT t.id, p.name AS item_name, u.name AS user_name, 
                     t.start_date, t.end_date, t.status
                     FROM transactions t
                     JOIN products p ON t.product_id = p.id
                     JOIN users u ON t.user_id = u.id");

$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $statusLabel = $row['status'];
    $color = match($row['status']) {
        'dibatalkan' => '#f87171',   // merah
        'selesai'    => '#34d399',    // hijau
        'disewa'     => '#60a5fa',    // biru
        default      => '#facc15'     // kuning (default)
    };

    $events[] = [
        'id' => $row['id'],
        'title' => $row['item_name'] . ' - ' . $row['user_name'],
        'start' => $row['start_date'],
        'end' => date('Y-m-d', strtotime($row['end_date'] . ' +1 day')),
        'color' => $color,
        'status' => $statusLabel
    ];
}

header('Content-Type: application/json');
echo json_encode($events);