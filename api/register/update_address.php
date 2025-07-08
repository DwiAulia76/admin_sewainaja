<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->user_id) && !empty($data->address)) {
    // Simpan/update alamat di tabel addresses
    $query = "INSERT INTO addresses (user_id, address) 
              VALUES (:user_id, :address)
              ON DUPLICATE KEY UPDATE address = :address";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $data->user_id, PDO::PARAM_INT);
    $stmt->bindParam(':address', $data->address);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Alamat berhasil disimpan"
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan alamat"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>