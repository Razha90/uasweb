<?php
require_once __DIR__ . '/../database/con_database.php';
require_once __DIR__ . '/../database/logger.php';

class Quiz
{
    private $con;
    private $logger;

    public function __construct()
    {
        global $db;
        $this->con = $db;
        $this->logger = new Logger();
    }

    public function detailQuiz(int $id): array|null
    {
        try {
            // Ambil data kuis berdasarkan ID
            $sqlQuiz = "
                SELECT quiz.id AS quiz_id, quiz.title AS quiz_title, 
                       question.id AS question_id, question.order_number, 
                       question.question, question.option1, question.option2, 
                       question.option3, question.option4, question.answer 
                FROM quiz
                LEFT JOIN question ON quiz.id = question.quiz_id
                WHERE quiz.id = :id
            ";

            $stmtQuiz = $this->con->prepare($sqlQuiz);
            $stmtQuiz->bindParam(':id', $id, SQLITE3_INTEGER);
            $resultQuiz = $stmtQuiz->execute();

            $quizData = null;

            // Memproses hasil untuk mengelompokkan data kuis dan pertanyaannya
            while ($row = $resultQuiz->fetchArray(SQLITE3_ASSOC)) {
                // Jika quizData belum diinisialisasi, buat entri baru untuk kuis ini
                if (!$quizData) {
                    $quizData = [
                        'id' => $row['quiz_id'],
                        'title' => $row['quiz_title'],
                        'questions' => []
                    ];
                }

                // Tambahkan pertanyaan ke dalam array questions dari kuis yang bersangkutan
                $quizData['questions'][] = [
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

            // Jika tidak ada data yang ditemukan, return null
            return $quizData ?: null;

        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }



    public function addQuiz(string $title): int|null
    {
        try {
            $sql = "INSERT INTO quiz (title) VALUES (?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $title, SQLITE3_TEXT);
            $stmt->execute();
            $lastId = $this->con->lastInsertRowID();
            return $lastId;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }

    public function addQuestion(int $key, int $order_number, string $question, string $option1, string $option2, string $option3, string $option4, string $answer)
    {
        try {
            $sql = "INSERT INTO question (quiz_id,order_number,question, option1, option2, option3, option4, answer) VALUES (?,?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $key, SQLITE3_INTEGER);
            $stmt->bindParam(2, $order_number, SQLITE3_INTEGER);
            $stmt->bindParam(3, $question, SQLITE3_TEXT);
            $stmt->bindParam(4, $option1, SQLITE3_TEXT);
            $stmt->bindParam(5, $option2, SQLITE3_TEXT);
            $stmt->bindParam(6, $option3, SQLITE3_TEXT);
            $stmt->bindParam(7, $option4, SQLITE3_TEXT);
            $stmt->bindParam(8, $answer, SQLITE3_TEXT);
            $stmt->execute();
            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function updateQuiz(string $title, int $id)
    {
        try {
            $sql = "UPDATE quiz SET title = ? WHERE id = ?";
            $stmt = $this->con->prepare($sql);

            $stmt->bindParam(1, $title, SQLITE3_TEXT);
            $stmt->bindParam(2, $id, SQLITE3_INTEGER);

            if (!$stmt->execute()) {
                $this->logger->error("Error executing update query");
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }


    public function updateQuestion(int $id, int $order_number, string $question, string $option1, string $option2, string $option3, string $option4, string $answer)
    {
        try {
            // Mengubah query dari INSERT menjadi UPDATE
            $sql = "UPDATE question SET order_number = ?, question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, answer = ? WHERE id = ?";
            $stmt = $this->con->prepare($sql);

            // Menyusun parameter untuk di-bind
            $stmt->bindParam(1, $order_number, SQLITE3_INTEGER);
            $stmt->bindParam(2, $question, SQLITE3_TEXT);
            $stmt->bindParam(3, $option1, SQLITE3_TEXT);
            $stmt->bindParam(4, $option2, SQLITE3_TEXT);
            $stmt->bindParam(5, $option3, SQLITE3_TEXT);
            $stmt->bindParam(6, $option4, SQLITE3_TEXT);
            $stmt->bindParam(7, $answer, SQLITE3_TEXT);
            $stmt->bindParam(8, $id, SQLITE3_INTEGER); // Bind ID untuk kondisi WHERE

            // Menjalankan query update
            if (!$stmt->execute()) {
                $this->logger->error("Error executing update query: ");
                return false;
            }

            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function deleteQuiz(int $id)
    {
        try {
            $sql = "DELETE FROM quiz WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id, SQLITE3_INTEGER);
            $stmt->execute();
            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function deleteQuestion(int $id)
    {
        try {
            $sql = "DELETE FROM question WHERE id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id, SQLITE3_INTEGER);
            $stmt->execute();
            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }
    }

    public function addScore(string $idUser, int $idQuiz, int $score): bool
    {
        try {
            $this->logger->warning("Adding score for user $idUser on quiz $idQuiz with scre $score");
            $sql = "INSERT INTO score (user_id, quiz_id, score) VALUES (?, ?, ?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $idUser, SQLITE3_TEXT);
            $stmt->bindParam(2, $idQuiz, SQLITE3_INTEGER);
            $stmt->bindParam(3, $score, SQLITE3_INTEGER);
            $stmt->execute();
            return true;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return false;
        }

    }

    public function getScoreDetail(string $idUser, int $idQuiz): array|null
    {
        try {
            $sql = "SELECT score FROM score WHERE user_id = ? AND quiz_id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $idUser, SQLITE3_TEXT);
            $stmt->bindParam(2, $idQuiz, SQLITE3_INTEGER);
            $result = $stmt->execute();
            $score = $result->fetchArray(SQLITE3_ASSOC);
            return $score ?: null;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }

    public function getScoreUser(string $id)
    {
        try {
            $sql = "SELECT * FROM score WHERE user_id = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id, SQLITE3_TEXT);
            $result = $stmt->execute();
            $scores = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $scores[] = $row;
            }
            return $scores;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }

    public function getQuizScore(int $id): array|null
    {
        try {
            // Query dengan JOIN untuk menggabungkan data dari tabel score dan users
            $sql = "
                SELECT score.*, users.display_name 
                FROM score
                JOIN users ON score.user_id = users.id
                WHERE score.quiz_id = ?
            ";

            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(1, $id, SQLITE3_INTEGER);
            $result = $stmt->execute();

            $scores = [];
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $scores[] = $row;
            }

            return $scores;
        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return null;
        }
    }


}