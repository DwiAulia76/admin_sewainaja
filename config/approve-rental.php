<?php

require_once 'database.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("UPDATE rentals SET confirmed_by_admin = 1 WHERE id = ?");
    $stmt->execute([$id]);
}