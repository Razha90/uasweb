<?php
require_once __DIR__ . '/../model/quiz.php';
require_once __DIR__ . '/../database/logger.php';

final class QuizController
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger();
  }

  function addQuiz()
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $input = file_get_contents('php://input');

      if (!$input) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Tidak ada data yang diterima.',
        ]);
        return;
      }

      $decodedData = json_decode($input, true);

      // Akses nilai 'title' dan 'data'
      $title = $decodedData['title'];
      $data = $decodedData['data'];

      if ($title === '') {
        $logs->warning('Empty title');
        echo json_encode([
          'status' => 'error',
          'message' => 'Judul kuis tidak boleh kosong.'
        ]);
        return;
      }
      $logs->error('Title: ' . $title);
      if (empty($data)) {
        $logs->warning('Empty data');
        echo json_encode([
          'status' => 'error',
          'message' => 'Data pertanyaan tidak boleh kosong.'
        ]);
        return;
      }

      $success = $quiz->addQuiz($title);

      if ($success !== null) {

        foreach ($data as $key => $question) {
          $order_number = $question['id'];
          $question_text = $question['question'];
          $option1 = $question['option1'];
          $option2 = $question['option2'];
          $option3 = $question['option3'];
          $option4 = $question['option4'];
          $answer = $question['answer'];

          // Tambahkan pertanyaan
          $quiz->addQuestion($success, $order_number, $question_text, $option1, $option2, $option3, $option4, $answer);

          // Periksa apakah quiz berhasil ditambahkan
          if (!$quiz) {
            $quiz->deleteQuiz($success);
            $this->logger->error('Failed to add question');
            echo json_encode([
              'status' => 'error',
              'message' => 'Gagal menyimpan data pertanyaan.'
            ]);
            return;
          }
        }

        $logs->success('Quiz added successfully');
        echo json_encode([
          'status' => 'success',
          'message' => 'Data quiz berhasil disimpan.',
          'data' => [
            'title' => $title
          ]
        ]);
        return;

      } else {
        $logs->error('Failed to add quiz');
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal menyimpan data quiz.'
        ]);
        return;
      }
    } else {
      $logs->warning('Invalid request method');

      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
      ]);
      return;
    }
  }


  function updateQuiz($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $input = file_get_contents('php://input');

      if (!$input) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Tidak ada data yang diterima.',
        ]);
        return;
      }

      $decodedData = json_decode($input, true);

      // Akses nilai 'title' dan 'data'
      $title = $decodedData['title'];
      $data = $decodedData['data'];

      if ($title === '') {
        $logs->warning('Empty title');
        echo json_encode([
          'status' => 'error',
          'message' => 'Judul kuis tidak boleh kosong.'
        ]);
        return;
      }
      if (empty($data)) {
        $logs->warning('Empty data');
        echo json_encode([
          'status' => 'error',
          'message' => 'Data pertanyaan tidak boleh kosong.'
        ]);
        return;
      }

      $success = $quiz->updateQuiz($title, $id);

      if ($success) {
        foreach ($data as $key => $question) {
          $id_ques = $question['id'];
          $order_number = $question['order_number'];
          $ques = $question['question'];
          $option1 = $question['option1'];
          $option2 = $question['option2'];
          $option3 = $question['option3'];
          $option4 = $question['option4'];
          $answer = $question['answer'];
          $type = $question['type'] ?? null;

          if ($type === 'new') {
            $quiz->addQuestion($id, $order_number, $ques, $option1, $option2, $option3, $option4, $answer);
            if (!$quiz) {
              $this->logger->error('Failed to add question');
              echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data pertanyaan.'
              ]);
              return;
            }
          }

          // Tambahkan pertanyaan
          $quiz->updateQuestion($id_ques, $order_number, $ques, $option1, $option2, $option3, $option4, $answer);

          // Periksa apakah quiz berhasil ditambahkan
          if (!$quiz) {
            $this->logger->error('Failed to add question');
            echo json_encode([
              'status' => 'error',
              'message' => 'Gagal menyimpan data pertanyaan.'
            ]);
            return;
          }
        }

        $logs->success('Quiz added successfully');
        echo json_encode([
          'status' => 'success',
          'message' => 'Data quiz berhasil disimpan.',
          'data' => [
            'title' => $title
          ]
        ]);
        return;

      } else {
        $logs->error('Failed to add quiz');
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal menyimpan data quiz.'
        ]);
        return;
      }
    } else {
      $logs->warning('Invalid request method');

      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
      ]);
      return;
    }
  }

  function deleteQuiz($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();
    header('Content-Type: application/json');


    $success = $quiz->deleteQuiz($id);

    if ($success) {
      echo json_encode([
        'status' => 'success',
        'message' => 'Data quiz berhasil dihapus.'
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghapus data quiz.'
      ]);
    }
  }

  function getQuizById($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    $quizData = $quiz->detailQuiz($id);
    $logs->warning('Detail quiz id: ' . $id);

    if ($quizData) {
      echo json_encode([
        'status' => 'success',
        'data' => $quizData
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data quiz tidak ditemukan.'
      ]);
    }
  }

  function deleteQuestion($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    $success = $quiz->deleteQuestion($id);

    if ($success) {
      echo json_encode([
        'status' => 'success',
        'message' => 'Data pertanyaan berhasil dihapus.'
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Gagal menghapus data pertanyaan.'
      ]);
    }
  }

  function addScore()
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $input = file_get_contents('php://input');

      if (!$input) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Tidak ada data yang diterima.',
        ]);
        return;
      }

      $decodedData = json_decode($input, true);

      $user_id = $decodedData['user_id'];
      $quiz_id = $decodedData['quiz_id'];
      $score = $decodedData['score'];

      if ($user_id === '') {
        $logs->warning('Empty user_id');
        echo json_encode([
          'status' => 'error',
          'message' => 'User ID tidak boleh kosong.'
        ]);
        return;
      }
      if ($quiz_id === '') {
        $logs->warning('Empty quiz_id');
        echo json_encode([
          'status' => 'error',
          'message' => 'Quiz ID tidak boleh kosong.'
        ]);
        return;
      }
      if ($score === '') {
        $logs->warning('Empty score');
        echo json_encode([
          'status' => 'error',
          'message' => 'Skor tidak boleh kosong.'
        ]);
        return;
      }
      $logs->error('User ID: ' . $user_id);

      $success = $quiz->addScore($user_id, $quiz_id, $score);

      if ($success) {
        $logs->success('Score added successfully');
        echo json_encode([
          'status' => 'success',
          'message' => 'Data skor berhasil disimpan.',
          'data' => [
            'user_id' => $user_id,
            'quiz_id' => $quiz_id,
            'score' => $score
          ]
        ]);
        return;
      } else {
        $logs->error('Failed to add score');
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal menyimpan data skor.'
        ]);
        return;
      }
    } else {
      $logs->warning('Invalid request method');

      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
      ]);
    }

  }

  function getScoreDetail()
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $input = file_get_contents('php://input');

      if (!$input) {
        echo json_encode([
          'status' => 'error',
          'message' => 'Tidak ada data yang diterima.',
        ]);
        return;
      }

      $decodedData = json_decode($input, true);

      $user_id = $decodedData['user_id'];
      $quiz_id = $decodedData['quiz_id'];

      if ($user_id === '') {
        $logs->warning('Empty user_id');
        echo json_encode([
          'status' => 'error',
          'message' => 'User ID tidak boleh kosong.'
        ]);
        return;
      }
      if ($quiz_id === '') {
        $logs->warning('Empty quiz_id');
        echo json_encode([
          'status' => 'error',
          'message' => 'Quiz ID tidak boleh kosong.'
        ]);
        return;
      }
      $logs->error('User ID: ' . $user_id);

      $score = $quiz->getScoreDetail($user_id, $quiz_id);

      if ($score) {
        $logs->success('Score retrieved successfully');
        echo json_encode([
          'status' => 'success',
          'data' => $score
        ]);
        return;
      } else {
        $logs->error('Failed to retrieve score');
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal mengambil data skor.'
        ]);
        return;
      }
    } else {
      $logs->warning('Invalid request method');

      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
      ]);
    }
  }

  public function getScore($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    $score = $quiz->getScoreUser($id); // Mengambil skor berdasarkan ID pengguna

    if ($score) {
      echo json_encode([
        'status' => 'success',
        'data' => $score
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data skor tidak ditemukan.'
      ]);
    }
  }

  public function getAllQuizScore($id) {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    $score = $quiz->getQuizScore($id); // Mengambil skor berdasarkan ID pengguna

    if ($score) {
      echo json_encode([
        'status' => 'success',
        'data' => $score
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data skor tidak ditemukan.'
      ]);
    }
  }


}