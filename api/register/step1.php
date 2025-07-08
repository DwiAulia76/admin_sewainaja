<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password) && !empty($data->name)) {
    // Validasi email
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Format email tidak valid"]);
        exit();
    }

    // Cek email sudah terdaftar
    $checkQuery = "SELECT id FROM users WHERE email = :email";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':email', $data->email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Email sudah terdaftar"]);
        exit();
    }

    // Hash password
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    
    // Insert user baru
    $query = "INSERT INTO users 
              (name, email, password, role) 
              VALUES 
              (:name, :email, :password, 'penyewa')";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $data->name);
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        $user_id = $pdo->lastInsertId();
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Registrasi awal berhasil",
            "user_id" => (int)$user_id,
            "next_step" => "verify_phone"
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal melakukan registrasi"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
}
?>