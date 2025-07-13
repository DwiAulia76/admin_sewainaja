<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$database = new Database();
$pdo = $database->getConnection();

// Terima input JSON
$input = json_decode(file_get_contents("php://input"), true);

$user_id = $input['user_id'] ?? null;
$nik = $input['nik'] ?? null;
$identity_type = $input['identity_type'] ?? null;
$identity_base64 = $input['identity_file'] ?? null;

// Validasi input
if (!$user_id || !$nik || !$identity_type || !$identity_base64) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua data wajib diisi.'
    ]);
    exit;
}

// Validasi NIK 16 digit
if (!preg_match('/^\d{16}$/', $nik)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'NIK harus 16 digit angka.'
    ]);
    exit;
}

try {
    // Simpan file ke folder
    $folderPath = __DIR__ . '/../../uploads/identity/';
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0777, true);
    }

    $fileName = 'identity_' . $user_id . '_' . time() . '.png';
    $filePath = $folderPath . $fileName;
    $relativePath = 'uploads/identity/' . $fileName;

    // Decode base64 dan simpan sebagai file gambar
    $decoded = base64_decode($identity_base64);

    if (file_put_contents($filePath, $decoded) === false) {
        throw new Exception("Gagal menyimpan file gambar");
    }

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO identity_verifications (user_id, nik, identity_type, identity_file, status, created_at)
        VALUES (:user_id, :nik, :identity_type, :identity_file, 'pending', NOW())");

    $stmt->execute([
        ':user_id' => $user_id,
        ':nik' => $nik,
        ':identity_type' => $identity_type,
        ':identity_file' => $relativePath
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data identitas berhasil disimpan.'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
