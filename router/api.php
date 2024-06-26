<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../controller/bookController.php';

$router = new AltoRouter();
$bookController = new bookController();
$userController = new userController();

$router->map('GET', '/api/books', [$bookController, 'getBooks']);

$router->map('GET', '/api/book/[:id]', [$bookController, 'getBookById']);

$router->map('POST', '/api/book', [$bookController, 'addBooks']);

$router->map('POST', '/api/book-delete', [$bookController, 'bookDelete']);

$router->map('POST', '/api/book-update', [$bookController, 'bookUpdate']);


$router->map('GET', '/api/posts', function() {
    // Logika untuk mengambil data post dari API
    echo 'API Endpoint: Posts';
});

return $router;
