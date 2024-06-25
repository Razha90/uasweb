<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controller/userController.php';
require_once __DIR__ . '/../middleware/authLogin.php';
require_once __DIR__ . '/../middleware/authLogout.php';
require_once __DIR__ . '/../controller/bookController.php';



$router = new AltoRouter();
$userController = new UserController();
$bookController = new bookController();


$router->map('GET', '/', function() {
    require __DIR__ . '/../views/home.php';
});

$router->map('GET', '/register', function() {
    require __DIR__ . '/../views/register.php';
});
$router->map('POST', '/register', [$userController, 'register']);

$router->map('GET', '/login', function() {
    authMiddleware();
    require __DIR__ . '/../views/login.php';
});
$router->map('POST', '/login', [$userController, 'login']);

$router->map('GET', '/logout', function () use ($userController){
    logoutMiddleware();
    $userController->logout();
});


$router->map('GET', '/404', function() {
    require __DIR__ . '/../views/404.php';
});

$router->map('GET', '/phpinfo', function() {
    require __DIR__ . '/../views/phpInfo.php';
});


$router->map('GET', '/api/books', [$bookController, 'getBooks']);

$router->map('POST', '/api/book', [$bookController, 'addBooks']);

$router->map('POST', '/api/delete', [$userController, 'deleteBook']);

$router->map('GET', '/api/posts', function() {
    // Logika untuk mengambil data post dari API
    echo 'API Endpoint: Posts';
});

// $router->map('GET', '/user/[i:id]', function($id) {
//     echo 'User ID: ' . $id;
// });

return $router;
