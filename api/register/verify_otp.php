<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id) && !empty($data->otp)) {
    // Konversi user_id ke integer
    $user_id = (int)$data->user_id;
    
    // Ambil data OTP terbaru dari database
    $query = "SELECT id, otp_code, expired_at, is_used 
              FROM otp_codes 
              WHERE user_id = :user_id 
              ORDER BY created_at DESC 
              LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $otp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otp) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Kode OTP tidak ditemukan"]);
        exit();
    }

    // Validasi OTP
    $current_time = date('Y-m-d H:i:s');
    if ($otp['expired_at'] < $current_time) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Kode OTP kadaluarsa"]);
    } elseif ($otp['is_used'] == 1) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Kode OTP sudah digunakan"]);
    } elseif ($otp['otp_code'] !== $data->otp) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Kode OTP tidak valid"]);
    } else {
        // Tandai OTP sebagai digunakan
        $updateOtpQuery = "UPDATE otp_codes SET is_used = 1 WHERE id = :id";
        $updateOtpStmt = $pdo->prepare($updateOtpQuery);
        $updateOtpStmt->bindParam(':id', $otp['id'], PDO::PARAM_INT);
        $updateOtpStmt->execute();

        // Update status verifikasi user
        $updateUserQuery = "UPDATE users SET is_verified = 1 WHERE id = :id";
        $updateUserStmt = $pdo->prepare($updateUserQuery);
        $updateUserStmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $updateUserStmt->execute();

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Nomor HP berhasil diverifikasi"
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>