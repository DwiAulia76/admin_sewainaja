<?php
// File: store_otp.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input['user_id'] ?? null;
$phone = $input['phone'] ?? null;
$otp_code = $input['otp_code'] ?? null;

if (!$user_id || !$phone || !$otp_code) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO otp_codes (user_id, phone, otp_code, is_used, expired_at) 
        VALUES (:user_id, :phone, :otp_code, 0, DATE_ADD(NOW(), INTERVAL 5 MINUTE))");
    $stmt->execute([
        ':user_id' => $user_id,
        ':phone' => $phone,
        ':otp_code' => $otp_code
    ]);

    echo json_encode(['success' => true, 'message' => 'OTP disimpan']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}