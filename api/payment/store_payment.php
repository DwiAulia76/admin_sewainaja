<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../../config/database.php';

$response = ["status" => "error", "message" => "Unknown error"];

try {
    $database = new Database();
    $pdo = $database->getConnection();
    if (!$pdo) throw new Exception("Koneksi database gagal.");

    // Ambil input JSON dari Flutter
    $data = json_decode(file_get_contents("php://input"), true);

    // Validasi field yang wajib
    if (
        empty($data['transaction_id']) ||
        empty($data['payment_proof'])
    ) {
        throw new Exception("Semua field wajib diisi.");
    }

    // Ambil data dari request
    $transaction_id = $data['transaction_id'];
    $payment_method = $data['payment_method'] ?? 'Transfer Bank';
    $amount = $data['amount'] ?? 0;

    // Simpan gambar dari base64
    $base64_proof = $data['payment_proof'];

    // Nama file otomatis: payment_[transaction_id]_[timestamp].png
    $safeId = preg_replace('/[^a-zA-Z0-9]/', '', $transaction_id);
    $file_name = 'payment_' . $safeId . '_' . time() . '.png';
    $file_path = __DIR__ . '/../../uploads/payments/' . $file_name;

    // Pastikan folder uploads/payments ada
    if (!is_dir(dirname($file_path))) {
        mkdir(dirname($file_path), 0777, true);
    }

    // Decode dan simpan gambar
    if (!file_put_contents($file_path, base64_decode($base64_proof))) {
        throw new Exception("Gagal menyimpan bukti pembayaran.");
    }

    // Simpan ke database payments
    $stmt = $pdo->prepare("
        INSERT INTO payments (
            transaction_id, amount, payment_method, payment_proof, status
        ) VALUES (
            :transaction_id, :amount, :payment_method, :payment_proof, 'diterima'
        )
    ");
    $stmt->execute([
        ':transaction_id' => $transaction_id,
        ':amount' => $amount,
        ':payment_method' => $payment_method,
        ':payment_proof' => $file_name,
    ]);

    // Update status transaksi juga (jika perlu)
    $update = $pdo->prepare("UPDATE transactions SET status = 'disewa' WHERE id = :id");
    $update->execute([':id' => $transaction_id]);

    $response = [
        "status" => "success",
        "message" => "Bukti pembayaran berhasil disimpan dan diterima.",
        "file_name" => $file_name
    ];
    http_response_code(200);

} catch (PDOException $e) {
    http_response_code(500);
    $response["message"] = "Database error: " . $e->getMessage();
} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
} finally {
    echo json_encode($response);
    if (isset($pdo)) $pdo = null;
}
