<?php
session_start();
require_once '../config/database.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

$start = date("$year-$month-01");
$end = date("Y-m-t", strtotime($start));

$stmt = $pdo->prepare("
    SELECT r.*, i.name AS item_name, u.name AS user_name
    FROM rentals r
    JOIN items i ON r.item_id = i.id
    JOIN users u ON r.penyewa_id = u.id
    WHERE r.start_date <= :end AND r.end_date >= :start
");
$stmt->execute(['start' => $start, 'end' => $end]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
