<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$query = "SELECT * FROM products WHERE status = 'available'";
$stmt = $pdo->prepare($query);
$stmt->execute();

$products = [];
$baseUrl = "http://10.0.2.2/admin_sewainaja/uploads/images/";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['image_url'] = $baseUrl . $row['image']; // <--- Tambahkan URL lengkap
    $products[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $products
]);
