<?php
// === FILE: get-rental-detail.php ===
require_once 'database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT r.*, i.name AS item_name, u.name AS user_name 
                           FROM rentals r 
                           JOIN items i ON r.item_id = i.id 
                           JOIN users u ON r.penyewa_id = u.id 
                           WHERE r.id = ?");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

