<?php
class Database {
    private $host = 'localhost';
    private $db   = 'adminsewainaja_app';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            die("Koneksi database gagal. Silakan coba lagi nanti.");
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
