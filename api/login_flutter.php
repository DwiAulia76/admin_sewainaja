<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? null;
$password = $data['password'] ?? null;

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Format email tidak valid"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email, password, role, is_verified FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
        exit;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Password salah"]);
        exit;
    }

    if ((int)$user['is_verified'] !== 1) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Nomor HP belum terverifikasi"]);
        exit;
    }

    // Cek status verifikasi identitas terakhir
    $verifStmt = $pdo->prepare("SELECT status FROM identity_verifications 
                                WHERE user_id = :user_id 
                                ORDER BY created_at DESC 
                                LIMIT 1");
    $verifStmt->execute([':user_id' => $user['id']]);
    $verif = $verifStmt->fetch(PDO::FETCH_ASSOC);
    $identity_status = $verif ? $verif['status'] : 'pending';

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Login berhasil",
        "user_id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "role" => $user['role'],
        "identity_status" => $identity_status
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
