
<?php
require_once __DIR__ . '/../../config/database.php';

$pdo = (new Database())->getConnection();

$stmt = $pdo->prepare("SELECT * FROM products ORDER BY 
    CASE WHEN status = 'rented' THEN 1 ELSE 0 END, name ASC");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($products);
