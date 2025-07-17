
<?php
require_once './config/database.php';
$pdo = (new Database())->getConnection();

$id = $_POST['id'];
$pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

header("Location: tabel_users.php");
