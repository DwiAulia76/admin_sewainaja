<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id) && !empty($data->nik) && !empty($data->identity_type) && !empty($data->identity_file)) {
    // Validasi NIK (16 digit)
    if (!preg_match('/^\d{16}$/', $data->nik)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "NIK harus 16 digit angka"]);
        exit();
    }

    // Validasi tipe identitas
    $allowed_types = ['KTP', 'SIM'];
    if (!in_array($data->identity_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Tipe identitas tidak valid"]);
        exit();
    }

    // Simpan ke tabel identity_verifications
    $query = "INSERT INTO identity_verifications 
              (user_id, nik, identity_type, identity_file, status) 
              VALUES 
              (:user_id, :nik, :identity_type, :identity_file, 'pending')";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $data->user_id, PDO::PARAM_INT);
    $stmt->bindParam(':nik', $data->nik);
    $stmt->bindParam(':identity_type', $data->identity_type);
    $stmt->bindParam(':identity_file', $data->identity_file);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Identitas berhasil diunggah, menunggu verifikasi admin"
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal mengunggah identitas"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>