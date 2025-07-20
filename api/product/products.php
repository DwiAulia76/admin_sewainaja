<?php
// File: get_products_with_images.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

// Query untuk mengambil semua data produk
$query = "SELECT 
            id,
            name,
            description,
            price_per_day,
            category,
            image,
            owner_id,
            created_at,
            updated_at,
            status 
          FROM products";

$stmt = $pdo->prepare($query);
$stmt->execute();

$products = [];
$baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/admin_sewainaja/";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Tambahkan URL lengkap gambar
    $row['image_url'] = $baseUrl . $row['image'];
    
    // Format harga ke dua desimal
    $row['price_per_day'] = number_format((float)$row['price_per_day'], 2, '.', '');
    
    // Potong deskripsi jika terlalu panjang
    if (strlen($row['description']) > 100) {
        $row['short_description'] = substr($row['description'], 0, 97) . '...';
    } else {
        $row['short_description'] = $row['description'];
    }
    
    $products[] = $row;
}

// Format output JSON
$response = [
    "status" => "success",
    "timestamp" => date('Y-m-d H:i:s'),
    "data" => $products,
    "image_base_path" => $baseUrl . "uploads/images/"
];

http_response_code(200);
echo json_encode($response);
?>