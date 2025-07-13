<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Menggunakan path relatif untuk include file
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/rental.php';

$database = new Database();
$db = $database->getConnection();

$rental = new Rental($db);

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->product_id) && 
   !empty($data->user_id) && 
   !empty($data->start_date) && 
   !empty($data->end_date)) {

    $rental->product_id = $data->product_id;
    $rental->user_id = $data->user_id;
    $rental->start_date = date('Y-m-d H:i:s', strtotime($data->start_date));
    $rental->end_date = date('Y-m-d H:i:s', strtotime($data->end_date));
    $rental->status = 'pending';
    $rental->created_at = date('Y-m-d H:i:s');

    if($rental->create()) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Rental berhasil dibuat",
            "rental_id" => $rental->id
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal membuat rental"
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak lengkap"
    ]);
}
?>