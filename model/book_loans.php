<?php
require_once __DIR__ . '/../database/con_database.php';
require_once __DIR__ . '/../database/logger.php';

class BookLoans
{
    private $con;
    private $logger;

    public function __construct()
    {
        global $db;
        $this->con = $db;
        $this->logger = new Logger();
    }

    public function addBookLoan(string $id, string $userId, string $bookId): bool
    {
        try {
          $returnDate = null;
            $sql = "INSERT INTO book_loans (id, user_id, book_id, return_date) 
                    VALUES (?, ?, ?, ?)";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $id, SQLITE3_TEXT);
            $stmt->bindParam(2, $userId, SQLITE3_TEXT);
            $stmt->bindParam(3, $bookId, SQLITE3_TEXT);
            $stmt->bindParam(4, $returnDate, SQLITE3_TEXT);
            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function getUserBookLoan($id): array
    {
        try {
          $sql = "SELECT * FROM book_loans WHERE user_id = :id";
          $stmt = $this->con->prepare($sql);
          $stmt->bindParam(':id', $id, SQLITE3_TEXT);
          $result = $stmt->execute();
          $bookData = [];
          while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
              $bookData[] = $row;
          }
          // Jika tidak ada baris yang dikembalikan, kirim array kosong
          if (empty($bookData)) {
              return [];
          } else {
              return $bookData;
          }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return [];
        }
    }

    public function deleteBookLoan($id): bool
    {
        try {
            $sql = "DELETE FROM book_loans WHERE id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id, SQLITE3_TEXT);
            $stmt->execute();
            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }
}