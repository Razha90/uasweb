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
      $logs->error('Title: ' . $title);
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
          $question = $question['question'];
          $option1 = $question['option1'];
          $option2 = $question['option2'];
          $option3 = $question['option3'];
          $option4 = $question['option4'];
          $answer = $question['answer'];

          // Tambahkan pertanyaan
          $quiz->updateQuestion($id_ques, $order_number, $question, $option1, $option2, $option3, $option4, $answer);

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

  function getQuizById($id)
  {
    $quiz = new Quiz();
    $logs = new Logger();

    header('Content-Type: application/json');

    $quizData = $quiz->detailQuiz($id);

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

}