<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input['user_id'] ?? null;
$address = $input['address'] ?? null;

if (!$user_id || !$address) {
    echo json_encode(['success' => false, 'message' => 'user_id dan address wajib diisi']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO addresses (user_id, address) VALUES (:user_id, :address)");
    $stmt->execute([
        ':user_id' => $user_id,
        ':address' => $address
    ]);

    echo json_encode(['success' => true, 'message' => 'Alamat disimpan']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

