<?php

namespace App\Core;

abstract class Model
{
    protected \PDO $db;

    public function __construct()
    {
        // Setup database connection via PDO using environment variables
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $db   = $_ENV['DB_DATABASE'] ?? 'cyberkavach';
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->db = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // Log error instead of echoing it in production
            error_log("Database connection failed: " . $e->getMessage());
        }
    }
}
