<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    $query = "SELECT id, name, image, price_per_day FROM products WHERE status = 'available' ORDER BY created_at DESC LIMIT 6";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $products = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'photo' => 'http://10.0.2.2/admin_sewainaja/images/' . $row['image'], // Ganti image → photo untuk frontend
            'price_per_day' => number_format($row['price_per_day'], 0, ',', '.'),
        ];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data: ' . $e->getMessage()
    ]);
}