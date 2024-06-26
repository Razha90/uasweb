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
    $logs = new Logger();

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

  public function getBookById($id)
  {
    $book = new Book();
    header('Content-Type: application/json');

    $bookData = $book->getBookById($id);

    if ($bookData) {
      echo json_encode([
        'status' => 'success',
        'data' => $bookData
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Data buku tidak ditemukan.'
      ]);
    }
  }

  public function bookDelete() {
    $book = new Book();
    $logs = new Logger();
    $id = $_POST['id'] ?? '';
    file_put_contents(__DIR__ . '/../logs/app.log', $_POST['id'], FILE_APPEND);

    if ($id === '') {
      $logs->warning('Invalid request');
      header("Location: {$_SERVER['HTTP_REFERER']}");
      return;
    }

    if ($_SESSION['role'] == 'admin') {
      if (empty($id)) {
        $logs->warning('Invalid request');
      header("Location: {$_SERVER['HTTP_REFERER']}");
        return;
      }

      $success = $book->deleteBook($id);

      if ($success) {
        $logs->success('Book deleted successfully');
        header("Location: /");
      } else {
        $logs->error('Failed to delete book');
        header("Location: {$_SERVER['HTTP_REFERER']}");

      }
    } else {
      $logs->warning('User Delete Not Admin');
      header("Location: {$_SERVER['HTTP_REFERER']}");
    }
  }

  public function bookUpdate()
  {
    $book = new Book();
    $logs = new Logger();

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'] ?? 'Razha';
      $author = $_POST['author'] ?? 'Unknown';
      $synopsis = $_POST['synopsis'] ?? 'No synopsis';
      $published_year = $_POST['published_year'] ?? 0;
      $isImage = false;
      $id = $_POST['id'] ?? null;

      if ($id === null) {
        $logs->warning('Invalid request');
        echo json_encode([
          'status' => 'error',
          'message' => 'Invalid request.'
        ]);
        return;
      }

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
          $isImage = true;
        } else {
          $isImage = false;
        }
      }

      if ($isImage) {
        $success = $book->updateBook($id, $title, $author, $synopsis, $published_year, $image_url);
      } else {
      $success = $book->updateBook($id, $title, $author, $synopsis, $published_year);
      }

      if ($success) {
        $logs->success('Book added successfully');
        echo json_encode([
          'status' => 'success',
          'message' => 'Data buku berhasil disimpan.'
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
}

