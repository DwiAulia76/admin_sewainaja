<?php
require_once '/../../config/database.php';
$db = new Database();
$conn = $db->getConnection();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['transaction_id'], $data['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    $status = $data['status'];
    $trxId = $data['transaction_id'];

    // Ambil product_id dari transaksi
    $stmt = $conn->prepare("SELECT product_id FROM transactions WHERE id = :id");
    $stmt->execute([':id' => $trxId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Transaksi tidak ditemukan']);
        exit;
    }

    $productId = $result['product_id'];

    // Update status transaksi
    $stmtUpdate = $conn->prepare("UPDATE transactions SET status = :status WHERE id = :id");
    $stmtUpdate->execute([':status' => $status, ':id' => $trxId]);

    // 🔁 Jika status 'selesai', ubah product ke 'available'
    //    Selain itu (pending, diproses, disewa) → 'rented'
    $newProductStatus = ($status === 'selesai') ? 'available' : 'rented';

    $stmtProduct = $conn->prepare("UPDATE products SET status = :new_status WHERE id = :product_id");
    $stmtProduct->execute([
        ':new_status' => $newProductStatus,
        ':product_id' => $productId
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
