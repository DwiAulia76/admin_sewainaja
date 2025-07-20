<?php
header('Content-Type: application/json');
require_once '../config/database.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['message' => 'Parameter user_id diperlukan']);
    exit;
}

$userId = $_GET['user_id'];
$database = new Database();
$pdo = $database->getConnection();

try {
    $query = "
        SELECT 
            t.id,
            p.name AS product_name,
            p.image,
            t.start_date,
            t.end_date,
            t.total_price,
            t.status
        FROM transactions t
        JOIN products p ON t.product_id = p.id
        WHERE t.user_id = :user_id
        ORDER BY t.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    $result = [];
    $imageBaseUrl = 'http://10.0.2.2/admin_sewainaja/';

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!empty($row['image'])) {
            $row['image'] = $imageBaseUrl . $row['image'];
        } else {
            $row['image'] = ''; // kosong jika tidak ada gambar
        }
        $result[] = $row;
    }

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}
?>
