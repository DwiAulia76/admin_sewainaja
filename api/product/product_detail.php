<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once '../../config/database.php';
$pdo = (new Database())->getConnection();

// Validasi parameter ID
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "ID produk tidak valid"
    ]);
    exit;
}

$productId = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Produk tidak ditemukan"
        ]);
        exit;
    }

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Ambil nama file gambar (bisa sudah mengandung folder atau belum)
    $imageFilename = $product['image'] ?? '';

    // Hapus prefix uploads/images/ jika ada, agar bisa dikontrol manual
    $cleanFilename = str_replace(['uploads/images/', './uploads/images/'], '', $imageFilename);

    // Path absolut untuk cek file
    $imagePath = realpath(__DIR__ . "/../../uploads/images/" . $cleanFilename);

    // URL publik gambar
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $basePath = dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))); // /api/products -> naik 2 folder

    if ($cleanFilename && $imagePath && file_exists($imagePath)) {
        $product['image_url'] = "{$scheme}://{$host}{$basePath}/uploads/images/{$cleanFilename}";
    } else {
        $product['image_url'] = null;
    }

    // Tambahkan link WhatsApp Admin
    $adminPhone = '6281234567890'; // Ganti dengan nomor admin aktif
    $productNameEncoded = urlencode($product['name']);
    $product['whatsapp_admin'] = "https://wa.me/{$adminPhone}?text=Halo%20Admin,%20saya%20tertarik%20dengan%20produk%20{$productNameEncoded}";

    echo json_encode([
        "status" => "success",
        "data" => $product
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
}
