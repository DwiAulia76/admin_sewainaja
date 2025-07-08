<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->password) &&
    !empty($data->role) &&
    in_array($data->role, ['admin', 'penyewa'])
) {
    // Validasi email
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Format email tidak valid"]);
        exit();
    }

    $name = $data->name;
    $email = $data->email;
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    $role = $data->role;
    $phone = $data->phone ?? null;
    $address = $data->address ?? null;
    $identity_type = $data->identity_type ?? null;
    $identity_file = $data->identity_file ?? null;
    $nik = $data->nik ?? null;

    // Cek email sudah terdaftar
    $checkQuery = "SELECT email FROM users WHERE email = :email";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Email sudah terdaftar"]);
        exit();
    }

    // Insert user baru (hanya data dasar)
    $query = "INSERT INTO users 
              (name, email, password, role, phone) 
              VALUES 
              (:name, :email, :password, :role, :phone)";
    
    $stmt = $pdo->prepare($query);
    
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':phone', $phone);

    if ($stmt->execute()) {
        $user_id = $pdo->lastInsertId();
        
        // Simpan alamat jika ada
        if (!empty($address)) {
            $addrQuery = "INSERT INTO addresses (user_id, address) VALUES (:user_id, :address)";
            $addrStmt = $pdo->prepare($addrQuery);
            $addrStmt->bindParam(':user_id', $user_id);
            $addrStmt->bindParam(':address', $address);
            $addrStmt->execute();
        }
        
        // Simpan identitas jika ada
        if (!empty($identity_type) && !empty($identity_file) && !empty($nik)) {
            $idQuery = "INSERT INTO identity_verifications 
                        (user_id, nik, identity_type, identity_file, status) 
                        VALUES 
                        (:user_id, :nik, :identity_type, :identity_file, 'pending')";
            $idStmt = $pdo->prepare($idQuery);
            $idStmt->bindParam(':user_id', $user_id);
            $idStmt->bindParam(':nik', $nik);
            $idStmt->bindParam(':identity_type', $identity_type);
            $idStmt->bindParam(':identity_file', $identity_file);
            $idStmt->execute();
        }
        
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "User berhasil didaftarkan",
            "user_id" => $user_id,
            "email" => $email,
            "role" => $role
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Gagal mendaftarkan user"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap atau role tidak valid"]);
}
?>