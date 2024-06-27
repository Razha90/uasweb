<?php
require_once __DIR__ . '/../database/con_database.php';
require_once __DIR__ . '/../database/logger.php';

class User
{
    private $con;
    private $logger;

    public function __construct()
    {
        global $db;
        $this->con = $db;
        $this->logger = new Logger();
    }

    public function register(string $id, string $password, string $username, string $display_name): bool
    {
        try {
            
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            // Buat SQL query dengan menggunakan parameterized query untuk keamanan
            $sql = "INSERT INTO users (id, username, password, display_name) 
                    VALUES (?, ?, ?, ?)";
            
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id, SQLITE3_TEXT);
            $stmt->bindParam(2, $username, SQLITE3_TEXT);
            $stmt->bindParam(3, $password_hashed, SQLITE3_TEXT);
            $stmt->bindParam(4, $display_name, SQLITE3_TEXT);
            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            // Tangkap dan log error
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function login(string $username, string $password): ?array
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':username', $username, SQLITE3_TEXT);
            $result = $stmt->execute();
            $user = $result->fetchArray(SQLITE3_ASSOC);

            if (password_verify($password, $user['password'])) {
                return [
                    'display_name' => $user['display_name'],
                    'role' => $user['role'],
                    'id' => $user['id']
                ];
            }
            return null;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }
}

