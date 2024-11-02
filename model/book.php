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

    // public function getBooks($searchTerm = null): array
    // {
    //     try {
    //         $sql = "SELECT * FROM books";

    //         // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
    //         if ($searchTerm !== null) {
    //             $sql .= " WHERE title LIKE :searchTerm
    //                        OR author LIKE :searchTerm
    //                        OR synopsis LIKE :searchTerm
    //                        OR published_year = :searchTermInt";
    //         }

    //         $stmt = $this->con->prepare($sql);

    //         // Bind parameter jika ada kata kunci pencarian
    //         if ($searchTerm !== null) {
    //             $searchTermSql = "%{$searchTerm}%";
    //             $searchTermInt = intval($searchTerm); // Pastikan integer jika mencari berdasarkan published_year

    //             $stmt->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
    //             $stmt->bindParam(':searchTermInt', $searchTermInt, SQLITE3_INTEGER);
    //         }

    //         $result = $stmt->execute();
    //         $books = [];

    //         while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    //             $books[] = $row;
    //         }

    //         return $books;
    //     } catch (\Throwable $th) {
    //         $this->logger->error($th->getMessage());
    //         return [];
    //     }
    // }

    // public function getBooks($searchTerm = null): array
    // {
    //     try {
    //         // Pertama, ambil data dari tabel books
    //         $sqlBooks = "SELECT * FROM books";
    //         if ($searchTerm !== null) {
    //             $sqlBooks .= " WHERE title LIKE :searchTerm
    //                        OR author LIKE :searchTerm
    //                        OR synopsis LIKE :searchTerm
    //                        OR published_year = :searchTermInt";
    //         }

    //         $stmtBooks = $this->con->prepare($sqlBooks);

    //         if ($searchTerm !== null) {
    //             $searchTermSql = "%{$searchTerm}%";
    //             $searchTermInt = intval($searchTerm);

    //             $stmtBooks->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
    //             $stmtBooks->bindParam(':searchTermInt', $searchTermInt, SQLITE3_INTEGER);
    //         }

    //         $resultBooks = $stmtBooks->execute();
    //         $books = [];

    //         while ($row = $resultBooks->fetchArray(SQLITE3_ASSOC)) {
    //             $row['type'] = 'materi'; // Tambahkan type 'materi' untuk data books
    //             $books[] = $row;
    //         }
    //         $logger = new Logger();
    //         $logger->success('Data book: ' . json_encode($books));

    //         // Kedua, ambil data dari tabel quiz dan gabungkan dengan relasi questions
    //         $sqlQuiz = "SELECT quiz.*, question.* 
    //                 FROM quiz
    //                 LEFT JOIN question ON quiz.id = question.quiz_id";

    //         if ($searchTerm !== null) {
    //             $sqlQuiz .= " WHERE quiz.title LIKE :searchTerm
    //                        OR question.content LIKE :searchTerm";
    //         }

    //         $stmtQuiz = $this->con->prepare($sqlQuiz);

    //         if ($searchTerm !== null) {
    //             $stmtQuiz->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
    //         }

    //         $resultQuiz = $stmtQuiz->execute();
    //         $quizzes = [];

    //         while ($row = $resultQuiz->fetchArray(SQLITE3_ASSOC)) {
    //             $row['type'] = 'quiz'; // Tambahkan type 'quiz' untuk data quiz
    //             $quizzes[] = $row;
    //         }
    //         $logger = new Logger();
    //         $logger->success('Data Quiz: ' . json_encode($quizzes));

    //         // Gabungkan kedua array menjadi satu
    //         $combinedData = array_merge($books, $quizzes);

    //         return $combinedData;

    //     } catch (\Throwable $th) {
    //         $this->logger->error($th->getMessage());
    //         return [];
    //     }
    // }


    public function getBooks($searchTerm = null): string
    {
        try {
            // Ambil data dari tabel books
            $sqlBooks = "SELECT * FROM books";
            if ($searchTerm !== null) {
                $sqlBooks .= " WHERE title LIKE :searchTerm
                           OR author LIKE :searchTerm
                           OR synopsis LIKE :searchTerm
                           OR published_year = :searchTermInt";
            }

            $stmtBooks = $this->con->prepare($sqlBooks);

            if ($searchTerm !== null) {
                $searchTermSql = "%{$searchTerm}%";
                $searchTermInt = intval($searchTerm);

                $stmtBooks->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
                $stmtBooks->bindParam(':searchTermInt', $searchTermInt, SQLITE3_INTEGER);
            }

            $resultBooks = $stmtBooks->execute();
            $books = [];

            while ($row = $resultBooks->fetchArray(SQLITE3_ASSOC)) {
                $row['type'] = 'materi'; // Tambahkan type 'materi' untuk data books
                $books[] = $row;
            }

            $logger = new Logger();
            $logger->success('Data book: ' . json_encode($books));

            // Ambil data dari tabel quiz dan question
            $sqlQuiz = "
            SELECT quiz.id AS quiz_id, quiz.title AS quiz_title, 
                   question.id AS question_id, question.order_number, 
                   question.question, question.option1, question.option2, 
                   question.option3, question.option4, question.answer 
            FROM quiz
            LEFT JOIN question ON quiz.id = question.quiz_id
        ";

            if ($searchTerm !== null) {
                $sqlQuiz .= " WHERE quiz.title LIKE :searchTerm
                           OR question.question LIKE :searchTerm";
            }

            $stmtQuiz = $this->con->prepare($sqlQuiz);

            if ($searchTerm !== null) {
                $stmtQuiz->bindParam(':searchTerm', $searchTermSql, SQLITE3_TEXT);
            }

            $resultQuiz = $stmtQuiz->execute();
            $quizzes = [];

            // Mengelompokkan pertanyaan berdasarkan quiz_id
            while ($row = $resultQuiz->fetchArray(SQLITE3_ASSOC)) {
                $quizId = $row['quiz_id'];

                if (!isset($quizzes[$quizId])) {
                    $quizzes[$quizId] = [
                        'id' => $quizId,
                        'title' => $row['quiz_title'],
                        'type' => 'quiz',  // Tambahkan type 'quiz' untuk data quiz
                        'questions' => []
                    ];
                }

                // Menambahkan pertanyaan ke dalam array questions dari quiz yang bersangkutan
                $quizzes[$quizId]['questions'][] = [
                    'id' => $row['question_id'],
                    'order_number' => $row['order_number'],
                    'question' => $row['question'],
                    'option1' => $row['option1'],
                    'option2' => $row['option2'],
                    'option3' => $row['option3'],
                    'option4' => $row['option4'],
                    'answer' => $row['answer']
                ];
            }

            // Mengonversi nilai associative array menjadi indexed array untuk quiz
            $quizzes = array_values($quizzes);
            // $logger = new Logger();
            // $logger->success('Data book: ' . json_encode($quizzes));

            // Gabungkan kedua array menjadi satu
            $combinedData = array_merge($books, $quizzes);

            // Encode hasil gabungan menjadi JSON
            return json_encode($combinedData);
            

        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return json_encode(['error' => 'Failed to retrieve data']);
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

    public function getBookById(string $id): array
    {
        try {
            $sql = "SELECT * FROM books WHERE id = :id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(':id', $id, SQLITE3_TEXT);
            $result = $stmt->execute();
            $book = $result->fetchArray(SQLITE3_ASSOC);

            return $book ? $book : [];
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return [];
        }
    }

    public function updateBook(string $id, string $title, string $author, string $synopsis, int $published_year, ?string $image_url = null): bool
    {
        try {
            if ($image_url === null) {
                $sql = "UPDATE books SET title = :title, author = :author, synopsis = :synopsis, published_year = :published_year WHERE id = :id";
                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(':title', $title, SQLITE3_TEXT);
                $stmt->bindParam(':author', $author, SQLITE3_TEXT);
                $stmt->bindParam(':synopsis', $synopsis, SQLITE3_TEXT);
                $stmt->bindParam(':published_year', $published_year, SQLITE3_INTEGER);
                $stmt->bindParam(':id', $id, SQLITE3_TEXT);
                $stmt->execute();

                return true;
            } else {
                $sql = "UPDATE books SET title = :title, author = :author, synopsis = :synopsis, image_url = :image_url, published_year = :published_year WHERE id = :id";
                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(':title', $title, SQLITE3_TEXT);
                $stmt->bindParam(':author', $author, SQLITE3_TEXT);
                $stmt->bindParam(':synopsis', $synopsis, SQLITE3_TEXT);
                $stmt->bindParam(':image_url', $image_url, SQLITE3_TEXT);
                $stmt->bindParam(':published_year', $published_year, SQLITE3_INTEGER);
                $stmt->bindParam(':id', $id, SQLITE3_TEXT);
                $stmt->execute();

                return true;
            }
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

}
