<?php
// File: verify_otp.php
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
    $stmt = $pdo->prepare("SELECT * FROM otp_codes 
        WHERE user_id = :user_id AND phone = :phone AND otp_code = :otp_code 
        AND is_used = 0 AND expired_at > NOW() 
        ORDER BY created_at DESC LIMIT 1");

    $stmt->execute([
        ':user_id' => $user_id,
        ':phone' => $phone,
        ':otp_code' => $otp_code
    ]);

    $otp = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($otp) {
        $updateStmt = $pdo->prepare("UPDATE otp_codes SET is_used = 1 WHERE id = :id");
        $updateStmt->execute([':id' => $otp['id']]);

        // Update users.is_verified = 1
        $verifyStmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = :id");
        $verifyStmt->execute([':id' => $user_id]);

        echo json_encode(['success' => true, 'message' => 'OTP valid dan verifikasi berhasil']);
    } else {
        echo json_encode(['success' => false, 'message' => 'OTP tidak valid atau sudah kadaluarsa']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
