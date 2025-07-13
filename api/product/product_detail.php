<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$productId = isset($_GET['id']) ? $_GET['id'] : die();

$response = ["status" => "error", "message" => "Unknown error"];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan"]);
        exit;
    }

    $baseImageUrl = "http://10.0.2.2/admin_sewainaja/images/";
    $imagePath = $product["image"] ? $baseImageUrl . $product["image"] : null;
    if (!$imagePath || !filter_var($imagePath, FILTER_VALIDATE_URL)) {
        $imagePath = "https://via.placeholder.com/300?text=No+Image";
    }

    $formattedProduct = [
        "id" => (int)$product["id"],
        "name" => $product["name"],
        "description" => $product["description"],
        "price_per_day" => (float)$product["price_per_day"],
        "price" => "Rp " . number_format($product["price_per_day"], 0, ',', '.') . "/hari",
        "category" => $product["category"],
        "image" => $imagePath,
        "status" => $product["status"],
        "created_at" => $product["created_at"],
        "updated_at" => $product["updated_at"]
    ];

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $formattedProduct
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
