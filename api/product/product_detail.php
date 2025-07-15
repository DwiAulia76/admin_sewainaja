<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "ID produk tidak valid"
    ]);
    exit;
}

$productId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Produk tidak ditemukan"
        ]);
        exit;
    }

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Siapkan URL gambar
    $baseUrl = '/admin_sewainaja/uploads/images/';
    $baseDir = realpath(__DIR__ . '/../../') . $baseUrl;
    $imageFile = $product['image'];
    $product['image_url'] = $imageFile && file_exists($baseDir . $imageFile)
        ? $baseUrl . $imageFile
        : null;

    echo json_encode([
        "status" => "success",
        "data" => $product
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
