<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

// Ambil data JSON dari request body
$data = json_decode(file_get_contents("php://input"));

// Validasi input dasar
if (
    !isset($data->name) || empty($data->name) ||
    !isset($data->email) || empty($data->email) ||
    !isset($data->password) || empty($data->password)
) {
    http_response_code(400);
    echo json_encode(["message" => "Semua field wajib diisi"]);
    exit;
}

try {
    // Cek apakah email sudah terdaftar
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$data->email]);

    if ($checkStmt->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo json_encode(["message" => "Email sudah terdaftar"]);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);

    // Insert data user baru
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'penyewa')");
    $stmt->execute([$data->name, $data->email, $hashedPassword]);

    // Ambil ID user yang baru saja ditambahkan
    $userId = $pdo->lastInsertId();

    http_response_code(201);
    echo json_encode([
        "message" => "Registrasi berhasil",
        "user_id" => $userId
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error: " . $e->getMessage()]);
}
