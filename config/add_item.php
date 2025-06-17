<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $cat = $_POST['category'];
    $price = $_POST['price_per_day'];
    $status = $_POST['status'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $uploadPath = '../public/storage' . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath);
    } else {
        $uploadPath = '';
    }

    $stmt = $pdo->prepare("INSERT INTO items (name, description, category, price_per_day, status, photo)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $desc, $cat, $price, $status, $uploadPath]);

    header("Location: ../barang.php");
    exit();
}
