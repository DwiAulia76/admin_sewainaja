<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$conn = $database->getConnection();

// Ambil user_id dari parameter GET
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id === 0) {
    http_response_code(400);
    echo json_encode(["message" => "User ID tidak valid."]);
    exit;
}

$query = "
    SELECT 
        transactions.id AS transaction_id,
        transactions.start_date,
        transactions.end_date,
        transactions.status,
        transactions.total_price,
        products.name AS product_name,
        products.image AS product_image
    FROM transactions
    JOIN products ON transactions.product_id = products.id
    WHERE transactions.user_id = :user_id
    ORDER BY transactions.created_at DESC
";

try {
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $results = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $results[] = [
            "id"            => $row['transaction_id'],      // TRX001
            "product_name"  => $row['product_name'],
            "image"         => $row['product_image'],
            "start_date"    => $row['start_date'],
            "end_date"      => $row['end_date'],
            "status"        => $row['status'],
            "total_price"   => (float) $row['total_price'],
        ];
    }

    echo json_encode($results);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Gagal mengambil data transaksi.",
        "error" => $e->getMessage()
    ]);
}
