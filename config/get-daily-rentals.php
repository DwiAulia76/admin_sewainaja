<?php
// === FILE: get-daily-rentals.php ===
require_once 'config/database.php';

$date = $_GET['date'] ?? null;
if ($date) {
    $stmt = $pdo->prepare("SELECT r.*, i.name AS item_name, u.name AS user_name 
                           FROM rentals r 
                           JOIN items i ON r.item_id = i.id 
                           JOIN users u ON r.penyewa_id = u.id 
                           WHERE ? BETWEEN r.start_date AND r.end_date");
    $stmt->execute([$date]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}