<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

// Inisialisasi respons default
$response = [
    "status" => "error",
    "message" => "Unknown error"
];

try {
    $database = new Database();
    $pdo = $database->getConnection();

    // Validasi koneksi database
    if (!$pdo) {
        throw new Exception("Database connection failed");
    }

    // Get search parameters
    $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $category = isset($_GET['category']) ? trim($_GET['category']) : '';

    // Build SQL query
    // Modified: Allow both 'tersedia' and 'available' statuses
    $sql = "SELECT * FROM products WHERE status IN ('tersedia', 'available')"; 
    $params = [];

    if (!empty($keyword)) {
        $sql .= " AND (name LIKE :keyword OR description LIKE :keyword)";
        $params[':keyword'] = '%' . $keyword . '%';
    }

    if (!empty($category) && $category != 'Semua' && $category != '') {
        $sql .= " AND category = :category";
        $params[':category'] = $category;
    }

    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Query execution failed");
    }
    
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format products
    $formattedProducts = [];
    $baseUrl = "http://10.0.2.2/admin_sewainaja/images/";
    
    foreach ($products as $product) {
        // Validasi URL gambar
        $imagePath = $product["image"] ? $baseUrl . $product["image"] : null;
        if (!$imagePath || !filter_var($imagePath, FILTER_VALIDATE_URL)) {
            $imagePath = "https://via.placeholder.com/300?text=No+Image";
        }

        $formattedProducts[] = [
            "id" => (int)$product["id"],
            "name" => $product["name"],
            "description" => $product["description"],
            "price" => "Rp " . number_format($product["price_per_day"], 0, ',', '.') . "/hari",
            "price_per_day" => (float)$product["price_per_day"],
            "category" => $product["category"],
            "image" => $imagePath,
            "status" => $product["status"],
            "owner_id" => (int)$product["owner_id"],
            "created_at" => $product["created_at"],
            "updated_at" => $product["updated_at"]
        ];
    }

    http_response_code(200);
    $response = [
        "status" => "success",
        "data" => $formattedProducts
    ];
    
} catch (PDOException $e) {
    http_response_code(500);
    $response["message"] = "Database error: " . $e->getMessage();
    error_log("PDOException: " . $e->getMessage());
    
} catch (Exception $e) {
    http_response_code(500);
    $response["message"] = "Server error: " . $e->getMessage();
    error_log("Exception: " . $e->getMessage());
} finally {
    header('Content-Type: application/json');
    echo json_encode($response);
    if (isset($pdo)) {
        $pdo = null;
    }
    exit;
}