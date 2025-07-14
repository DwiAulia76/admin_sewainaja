<?php
header("Content-Type: application/json");
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

$user_id     = $data['user_id'] ?? null;
$product_id  = $data['product_id'] ?? null;
$start_date  = $data['start_date'] ?? null;
$end_date    = $data['end_date'] ?? null;
$total_price = $data['total_price'] ?? 0;

if (!$user_id || !$product_id || !$start_date || !$end_date) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    // Ambil ID terakhir (TRXxxx)
    $stmt = $conn->query("SELECT id FROM transactions WHERE id LIKE 'TRX%' ORDER BY id DESC LIMIT 1");
    $lastRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($lastRow) {
        // Ambil angka dari TRXxxx -> TRX005 => 5
        $lastNumber = (int)substr($lastRow['id'], 3);
        $newNumber = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    // Format jadi TRX001, TRX002, ...
    $newId = 'TRX' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    // Simpan transaksi dengan ID yang sudah digenerate
    $stmt = $conn->prepare("INSERT INTO transactions (id, user_id, product_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$newId, $user_id, $product_id, $start_date, $end_date, $total_price]);

    echo json_encode([
        'status' => 'success',
        'transaction_id' => $newId
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()]);
}
