<?php
require_once __DIR__ . '/../model/book.php';
require_once __DIR__ . '/../database/logger.php';

final class bookController
{
  public function getBooks()
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;
    $book = new Book();
    header('Content-Type: application/json');

    $offset = ($page - 1) * $limit;
    $searchTerm = isset($_GET['name']) ? $_GET['name'] : null;
    $allBooks = $book->getBooks($searchTerm);

    $totalBooks = count($allBooks);

    $books = array_slice($allBooks, $offset, $limit);

    if (!empty($books)) {
        $totalPages = ceil($totalBooks / $limit);

        $prevPage = ($page > 1) ? $page - 1 : null;
        $nextPage = ($page < $totalPages) ? $page + 1 : null;

        // Respons JSON
        echo json_encode([
            'status' => 'success',
            'data' => $books,
            'pagination' => [
                'total_pages' => $totalPages,
                'current_page' => $page,
                'prev_page' => $prevPage,
                'next_page' => $nextPage
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to get books data.'
        ]);
    }
}

  public function addBooks()
  {
    $book = new Book();
    $logs = new Logger(); // Ensure path is correct and writable

    // Set Content-Type ke application/json
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'] ?? 'Razha';
      $author = $_POST['author'] ?? 'Unknown';
      $synopsis = $_POST['synopsis'] ?? 'No synopsis';
      $published_year = $_POST['published_year'] ?? 0;

      $targetDir = __DIR__ . '/../public/img/';
      if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
      }

      $image_url = '';
      if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $targetFile = $targetDir . bin2hex(random_bytes(12)) . '.' . $imageFileType;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
          $image_url = basename($targetFile);
        } else {
          $logs->error('Failed to upload image.');
          echo json_encode([
            'status' => 'error',
            'message' => 'Failed to upload image.'
          ]);
          return;
        }
      }

      $logs->error("Title: $title, Author: $author, Synopsis: $synopsis, Published Year: $published_year, Image URL: $image_url");

      $randomId = bin2hex(random_bytes(12));

      $success = $book->addBook($randomId, $title, $author, $synopsis, $image_url, $published_year);

      if ($success) {
        $logs->success('Book added successfully');
        // Mengirimkan respons JSON
        echo json_encode([
          'status' => 'success',
          'message' => 'Data buku berhasil disimpan.',
          'data' => [
            'title' => $title,
            'author' => $author,
            'synopsis' => $synopsis,
            'published_year' => $published_year,
            'image_url' => $image_url
          ]
        ]);
      } else {
        $logs->error('Failed to add book');
        // Mengirimkan respons JSON untuk kesalahan
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal menyimpan data buku.'
        ]);
      }
    } else {
      $logs->warning('Invalid request method');
      // Mengirimkan respons JSON untuk metode request tidak valid
      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak valid.'
      ]);
    }
  }

  public function deleteBook()
  {
    $book = new Book();
    $logs = new Logger();
    // Set Content-Type ke application/json
    header('Content-Type: application/json');

    if ($_SESSION['role'] == 'admin') {
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        $logs->warning('Invalid request');
        echo json_encode([
          'status' => 'error',
          'message' => 'Invalid request.'
        ]);
        return;
      }

      $success = $book->deleteBook($id);

      if ($success) {
        $logs->success('Book deleted successfully');
        echo json_encode([
          'status' => 'success',
          'message' => 'Data buku berhasil dihapus.'
        ]);
      } else {
        $logs->error('Failed to delete book');
        echo json_encode([
          'status' => 'error',
          'message' => 'Gagal menghapus data buku.'
        ]);
      }
    } else {
      $logs->warning('User Delete Not Admin');
      echo json_encode([
        'status' => 'error',
        'message' => 'Metode request tidak kamu bukan admin.'
      ]);
    }
  }
}

