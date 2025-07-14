<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '/../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // 1. Set produk yang masih aktif (pending, diproses, disewa) jadi 'rented'
    $stmt1 = $conn->prepare("
        UPDATE products
        SET status = 'rented'
        WHERE id IN (
            SELECT product_id FROM transactions
            WHERE status IN ('pending', 'diproses', 'disewa')
        )
    ");
    $stmt1->execute();

    // 2. Set produk yang tidak aktif lagi (hanya 'selesai') jadi 'available'
    $stmt2 = $conn->prepare("
        UPDATE products
        SET status = 'available'
        WHERE id IN (
            SELECT p.id FROM products p
            WHERE NOT EXISTS (
                SELECT 1 FROM transactions t
                WHERE t.product_id = p.id
                AND t.status IN ('pending', 'diproses', 'disewa')
            )
        )
    ");
    $stmt2->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Status produk berhasil diperbarui.'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Gagal update: ' . $e->getMessage()
    ]);
}
