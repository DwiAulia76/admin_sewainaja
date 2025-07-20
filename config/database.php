<?php
class Database {
    private $host = 'localhost';
    private $db   = 'adminsewainaja_app';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;

    //     private $host = '01p30u.h.filess.io';
    // private $db   = 'adminsewainaja_warndodown';
    // private $user = 'adminsewainaja_warndodown';
    // private $pass = 'a2625aa6d8a427bbc7b7b961f32d4ed1ef969f42';
    // private $charset = 'utf8mb4';

    private $port = 3307;

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
