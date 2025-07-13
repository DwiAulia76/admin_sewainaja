<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input['user_id'] ?? null;
$phone = $input['phone'] ?? null;

if (!$user_id || !$phone) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'user_id dan phone wajib diisi']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE users SET phone = :phone WHERE id = :id");
    $stmt->execute([
        ':phone' => $phone,
        ':id' => $user_id
    ]);

    echo json_encode(['success' => true, 'message' => 'Nomor HP diperbarui']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}