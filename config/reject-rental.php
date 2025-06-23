<?php
require_once 'database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE rentals SET status = 'ditolak' WHERE id = ?");
    $stmt->execute([$id]);
}