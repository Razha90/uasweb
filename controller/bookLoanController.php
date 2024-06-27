<?php
require_once __DIR__ . '/../model/book_loans.php';

final class BookLoanController
{
  public function addBookLoan()
  {
    $book = new BookLoans();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $bookId = $_POST['book_id'] ?? '';
      $userId = $_POST['user_id'] ?? '';
      $randomId = bin2hex(random_bytes(12));

      if (empty($bookId) || empty($userId)) {
        file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERRO ADD BOOK LOANS'), FILE_APPEND);
        header("Location: {$_SERVER['HTTP_REFERER']}?status=error");
        return;
      }

      $isBookLoanAdded = $book->addBookLoan($randomId, $userId, $bookId);

      if ($isBookLoanAdded) {
        header("Location: {$_SERVER['HTTP_REFERER']}?status=success");
      } else {
        file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERRO ADD BOOK LOANS'), FILE_APPEND);
        header("Location: {$_SERVER['HTTP_REFERER']}?status=error");

      }
    } else {
      file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', 'ERRO ADD BOOK LOANS'), FILE_APPEND);
      header("Location: {$_SERVER['HTTP_REFERER']}?status=error");
    }
  }

  public function getUserBookLoan($id) {
    $book = new BookLoans();
    header('Content-Type: application/json');

    $bookData = $book->getUserBookLoan($id);
    if (empty($bookData)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data buku tidak ditemukan.'
      ]);
    } else {
      echo json_encode([
        'status' => 'success',
        'data' => $bookData
      ]);
    }
  } 

  public function deleteBookLoan() {
    $book = new BookLoans();
    $logs = new Logger();
    $id = $_POST['id'] ?? '';
    file_put_contents(__DIR__ . '/../logs/app.log', $_POST['id'], FILE_APPEND);

    if ($id === '') {
      $logs->warning('Invalid request');
      header("Location: {$_SERVER['HTTP_REFERER']}");
      return;
    }

      if (empty($id)) {
        $logs->warning('Invalid request');
      header("Location: {$_SERVER['HTTP_REFERER']}");
        return;
      }

      $success = $book->deleteBookLoan($id);

      if ($success) {
        $logs->success('Book deleted successfully');
        header("Location: {$_SERVER['HTTP_REFERER']}");
      } else {
        $logs->error('Failed to delete book');
        header("Location: {$_SERVER['HTTP_REFERER']}");

      }
    
  }
}
