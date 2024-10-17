<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controller/userController.php';
require_once __DIR__ . '/../middleware/authLogin.php';
require_once __DIR__ . '/../middleware/authLogout.php';
require_once __DIR__ . '/../middleware/authAdmin.php';



$router = new AltoRouter();
$userController = new UserController();

$router->map('GET', '/', function() {
    require __DIR__ . '/../views/home.php';
});

$router->map('GET', '/chat', function() {
    authAdmin(function(){
        require __DIR__ . '/../views/chat.php';
    });
    
});

$router->map('GET', '/register', function() {
    authMiddleware();
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


$router->map('GET', '/add-book', function() {
    require __DIR__ . '/../views/addBook.php';
});

$router->map('GET', '/404', function() {
    require __DIR__ . '/../views/404.php';
});

$router->map('GET', '/phpinfo', function() {
    require __DIR__ . '/../views/phpInfo.php';
});

$router->map('GET', '/book-detail', function() {
    require __DIR__ . '/../views/bookDetail.php';
});

// $router->map('GET', '/user/[i:id]', function($id) {
//     echo 'User ID: ' . $id;
// });

return $router;
