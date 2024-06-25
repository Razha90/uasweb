<?php
require_once __DIR__ . '/../database/con_database.php';
require_once __DIR__ . '/../database/logger.php';

class Book
{
    private $con;
    private $logger;

    public function __construct()
    {
        global $db;
        $this->con = $db;
        $this->logger = new Logger();
    }

    public function addBook(string $id, string $title, string $author, string $synopsis, string $image_url, int $published_year): bool
    {
        try {
            // Buat SQL query dengan menggunakan parameterized query untuk keamanan
            $sql = "INSERT INTO books (id, title, author, synopsis, image_url, published_year) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $id, SQLITE3_TEXT);
            $stmt->bindParam(2, $title, SQLITE3_TEXT);
            $stmt->bindParam(3, $author, SQLITE3_TEXT);
            $stmt->bindParam(4, $synopsis, SQLITE3_TEXT);
            $stmt->bindParam(5, $image_url, SQLITE3_TEXT);
            $stmt->bindParam(6, $published_year, SQLITE3_INTEGER);
            $stmt->execute();

            return true;
        } catch (\Throwable $th) {
            // Tangkap dan log error
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function getBooks($searchTerm = null): array
    {
        try {
            $sql = "SELECT * FROM books";

            // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
            if ($searchTerm !== null) {
                $sql .= " WHERE title LIKE :searchTerm
                           OR author LIKE :searchTerm
                           OR synopsis LIKE :searchTerm
                           OR published_year = :searchTermInt";
            }

            $stmt = $this->con->prepare($sql);

            // Bind parameter jika ada kata kunci pencarian
            if ($searchTerm !== null) {
                $searchTermSql = "%{$searchTerm}%";
                $searchTermInt = intval($searchTerm); // Pastikan integer jika mencari berdasarkan published_year

                $stmt->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
                $stmt->bindParam(':searchTermInt', $searchTermInt, SQLITE3_INTEGER);
            }

            $result = $stmt->execute();
            $books = [];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $books[] = $row;
            }

            return $books;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return [];
        }
    }

    public function deleteBook(string $id): bool
    {
        try {
            $sql = "DELETE FROM books WHERE id = :id";
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
