<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $email = $data->email;
    $password = $data->password;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Format email tidak valid"]);
        exit();
    }

    $query = "SELECT id, name, email, password, role, is_verified FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $row['password'];
        
        if (password_verify($password, $hashed_password)) {
            // Periksa status verifikasi HP
            if ($row['is_verified'] != 1) {
                http_response_code(401);
                echo json_encode(["status" => "error", "message" => "Nomor HP belum terverifikasi"]);
                exit();
            }
            
            // Periksa status verifikasi identitas
            $verifQuery = "SELECT status FROM identity_verifications 
                           WHERE user_id = :user_id 
                           ORDER BY created_at DESC 
                           LIMIT 1";
            $verifStmt = $pdo->prepare($verifQuery);
            $verifStmt->bindParam(':user_id', $row['id']);
            $verifStmt->execute();
            $verification = $verifStmt->fetch(PDO::FETCH_ASSOC);
            
            $identity_status = $verification ? $verification['status'] : 'pending';
            
            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Login berhasil",
                "user_id" => $row['id'],
                "name" => $row['name'],
                "email" => $row['email'],
                "role" => $row['role'],
                "identity_status" => $identity_status
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Password salah"]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "User tidak ditemukan"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi"]);
}
?>