<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$keyword = $_GET['keyword'] ?? '';
$category = $_GET['category'] ?? '';

try {
    $query = "SELECT 
                id, 
                name, 
                description, 
                category, 
                CAST(price_per_day AS UNSIGNED) AS price_per_day, 
                status, 
                image 
              FROM products 
              WHERE 1=1";

    if (!empty($keyword)) {
        $query .= " AND name LIKE :keyword";
    }
    if (!empty($category)) {
        $query .= " AND category = :category";
    }

    // Prioritaskan yang available, lalu rented
    $query .= " ORDER BY FIELD(status, 'available', 'rented') ASC, name ASC";

    $stmt = $conn->prepare($query);

    if (!empty($keyword)) {
        $kw = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $kw);
    }

    if (!empty($category)) {
        $stmt->bindParam(':category', $category);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'count' => count($results),
        'data' => $results
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal mengambil data: ' . $e->getMessage()
    ]);
}
