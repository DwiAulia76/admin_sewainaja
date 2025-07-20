<?php
// File: recommendation.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->getConnection();

try {
    $query = "
        SELECT 
            id, 
            name, 
            description,
            price_per_day, 
            category,
            image,
            status
        FROM products 
        WHERE status = 'available' 
        ORDER BY created_at DESC 
        LIMIT 6
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $products = [];

    // URL dasar gambar
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/admin_sewainaja/";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['image_url'] = $baseUrl . $row['image'];
        $row['price_per_day'] = number_format((float)$row['price_per_day'], 2, '.', '');

        // Potong deskripsi jika perlu
        if (strlen($row['description']) > 100) {
            $row['short_description'] = substr($row['description'], 0, 97) . '...';
        } else {
            $row['short_description'] = $row['description'];
        }

        $products[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => $products,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data: ' . $e->getMessage()
    ]);
}
?>
