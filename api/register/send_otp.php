<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id) && !empty($data->phone)) {
    // Validasi format nomor HP
    if (!preg_match('/^[0-9]{10,15}$/', $data->phone)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Format nomor HP tidak valid"]);
        exit();
    }

    // Generate OTP 6 digit
    $otp_code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expired_at = date('Y-m-d H:i:s', time() + 300); // 5 menit
    
    // Konversi user_id ke integer
    $user_id = (int)$data->user_id;
    
    // Simpan OTP ke database
    $query = "INSERT INTO otp_codes 
              (user_id, phone, otp_code, expired_at) 
              VALUES 
              (:user_id, :phone, :otp_code, :expired_at)";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':phone', $data->phone);
    $stmt->bindParam(':otp_code', $otp_code);
    $stmt->bindParam(':expired_at', $expired_at);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Kode OTP telah dikirim",
            "otp_debug" => $otp_code // Hanya untuk development
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal mengirim OTP"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>