<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../controller/bookController.php';
require __DIR__ . '/../controller/bookLoanController.php';
require __DIR__ . '/../middleware/apiNoLogin.php';

$router = new AltoRouter();
$bookController = new bookController();
$bookLoansController = new BookLoanController();

$router->map('GET', '/api/books', [$bookController, 'getBooks']);

$router->map('GET', '/api/book/[:id]', [$bookController, 'getBookById']);

$router->map('POST', '/api/book', [$bookController, 'addBooks']);

$router->map('POST', '/api/book-delete', [$bookController, 'bookDelete']);

$router->map('POST', '/api/book-update', [$bookController, 'bookUpdate']);

$router->map('GET', '/api/book-loan/[:id]', function($id) use ($bookLoansController) {
    $bookLoansController->getUserBookLoan($id);
});

$router->map('POST', '/api/book-loan', function() use ($bookLoansController) {
    apiNoLogin();
    $bookLoansController->addBookLoan();
});

$router->map('POST', '/api/delete-book-loan', function() use ($bookLoansController) {
    apiNoLogin();
    $bookLoansController->deleteBookLoan();
});

// $router->map('GET', '/api/posts', function() {
//     // Logika untuk mengambil data post dari API
//     echo 'API Endpoint: Posts';
// });

return $router;
