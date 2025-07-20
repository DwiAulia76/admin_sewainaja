<?php
header('Content-Type: application/json');
require_once '../config/database.php';

// Tambahkan penanganan CORS untuk development
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Parameter user_id diperlukan']);
    exit;
}

$userId = $_GET['user_id'];
$database = new Database();
$pdo = $database->getConnection();

try {
    // Query utama untuk mendapatkan data user
    $query = "
        SELECT 
            u.id,
            u.name,
            u.email,
            u.phone,
            a.address,
            i.nik,
            i.identity_type,
            i.identity_file,
            i.status AS identity_status
        FROM users u
        LEFT JOIN addresses a ON u.id = a.user_id
        LEFT JOIN identity_verifications i ON u.id = i.user_id
        WHERE u.id = :user_id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Pastikan semua field memiliki nilai
        $user['address'] = $user['address'] ?? null;
        $user['nik'] = $user['nik'] ?? null;
        $user['identity_status'] = $user['identity_status'] ?? null;
        
        // Tambahkan base URL jika ada file gambar KTP
        if (!empty($user['identity_file'])) {
            $user['identity_file'] = 'http://10.0.2.2/admin_sewainaja/' . $user['identity_file'];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $user
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}