<?php
require_once __DIR__ . '/../database/con_database.php';
require_once __DIR__ . '/../database/logger.php';

class Message
{
  private $con;
  private $logger;

  public function __construct()
  {
    global $db;
    $this->con = $db;
    $this->logger = new Logger();
  }

  public function addMessage(string $message, string $sender_id, string $receiver_id, string $created_at): bool
  {
    try {
      $this->logger->warning("$message");

      if ($receiver_id === 'admin') {
        $receiver_id = $this->getAdmin();
      }
      // Buat SQL query dengan menggunakan parameterized query untuk keamanan
      $sql = "INSERT INTO messages (message, sender_id, receiver_id, created_at) 
                  VALUES (?, ?, ?, ?)";

      $stmt = $this->con->prepare($sql);

      $stmt->bindParam(1, $message, SQLITE3_TEXT);
      $stmt->bindParam(2, $sender_id, SQLITE3_TEXT);
      $stmt->bindParam(3, $receiver_id, SQLITE3_TEXT);
      $stmt->bindParam(4, $created_at, SQLITE3_TEXT);
      $stmt->execute();

      return true;
    } catch (\Throwable $th) {
      // Tangkap dan log error
      $this->logger->error($th->getMessage());
      return false;
    }
  }

  private function getAdmin()
  {
    try {
      $sql = "SELECT * FROM users WHERE role = 'admin' LIMIT 1";
      $stmt = $this->con->prepare($sql);
      $stmt->bindParam(':id', $id, SQLITE3_TEXT);
      $result = $stmt->execute();
      $message = $result->fetchArray(SQLITE3_ASSOC);

      return $message['id'] ? $message['id'] : '';
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());
      return '';
    }
  }

  public function getMessageUser($id): array
  {
    try {
      $sql = "SELECT * FROM messages WHERE receiver_id = '$id' OR sender_id = '$id'";
      $stmt = $this->con->prepare($sql);
      $stmt->bindParam(':id', $id, SQLITE3_TEXT);
      $result = $stmt->execute();
      $messages = [];
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $messages[] = $row;
      }

      if (empty($messages)) {
        return [];
      } else {
        return $messages;
      }
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());
      return [];
    }
  }

  public function getUser($id)
  {
    try {
      $sql = "
    SELECT DISTINCT u.*
    FROM messages m
    JOIN users u ON u.id = m.sender_id
    WHERE m.receiver_id = :id
";
      $stmt = $this->con->prepare($sql);
      $stmt->bindParam(':id', $id, SQLITE3_TEXT);
      $result = $stmt->execute();
      $messages = [];
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $messages[] = $row;
      }

      if (empty($messages)) {
        return [];
      } else {
        return $messages;
      }
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());
      return [];
    }
  }

  public function getMessageReceive($receiver_id, $sender_id): array
  {
    try {
      $sql = "SELECT * FROM messages WHERE         (receiver_id = :receiver_id AND sender_id = :sender_id)
      OR 
      (receiver_id = :sender_id AND sender_id = :receiver_id)";
      
      $stmt = $this->con->prepare($sql);
      $stmt->bindParam(':receiver_id', $receiver_id, SQLITE3_TEXT);
      $stmt->bindParam(':sender_id', $sender_id, SQLITE3_TEXT);
      $result = $stmt->execute();
      $messages = [];
      while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $messages[] = $row;
      }

      if (empty($messages)) {
        return [];
      } else {
        return $messages;
      }
    } catch (\Throwable $th) {
      $this->logger->error($th->getMessage());
      return [];
    }
  }


  // private function getAdmin(): ?string
  // {
  //     try {
  //         $sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";

  //         $stmt = $this->con->prepare($sql);
  //         $stmt->execute();

  //         // Mengambil hasil query
  //         $result = $stmt->fetch(FETCH_ASSOC);

  //         // Jika hasil ditemukan, kembalikan ID
  //         return $result['id'] ?? null;
  //     } catch (\Throwable $th) {
  //         // Tangkap dan log error
  //         $this->logger->error($th->getMessage());
  //         return null;
  //     }
  // }


  // public function getMessages($searchTerm = null): array
  // {
  //     try {
  //         $sql = "SELECT * FROM messages";

  //         // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
  //         if ($searchTerm !== null) {
  //             $sql .= " WHERE message LIKE :searchTerm
  //                        OR sender LIKE :searchTerm
  //                        OR receiver LIKE :searchTerm
  //                        OR created_at = :searchTerm";
  //         }

  //         $stmt = $this->con->prepare($sql);

  //         // Bind parameter jika ada kata kunci pencarian
  //         if ($searchTerm !== null) {
  //             $searchTermSql = "%{$searchTerm}%";
  //             $stmt->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
  //         }

  //         $result = $stmt->execute();
  //         $messages = [];
  //         while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
  //             $messages[] = $row;
  //         }

  //         return $messages;
  //     } catch (\Throwable $th) {
  //         // Tangkap dan log error
  //         $this->logger->error($th->getMessage());
  //         return [];
  //     }
  // }
}