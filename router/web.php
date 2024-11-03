<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controller/userController.php';
require_once __DIR__ . '/../middleware/authLogin.php';
require_once __DIR__ . '/../middleware/authLogout.php';
require_once __DIR__ . '/../middleware/authAdmin.php';



$router = new AltoRouter();
$userController = new UserController();

$router->map('GET', '/', function () {
    require __DIR__ . '/../views/home.php';
});

$router->map('GET', '/chat', function () {
    authAdmin(function () {
        require __DIR__ . '/../views/chat.php';
    });

});

$router->map('GET', '/register', function () {
    authLogin(function () {
        require __DIR__ . '/../views/register.php';
    });
});

$router->map('GET', '/login', function () {
    authLogin(function () {
        require __DIR__ . '/../views/login.php';
    });
});

$router->map('POST', '/register', [$userController, 'register']);

$router->map('POST', '/login', [$userController, 'login']);

$router->map('GET', '/logout', function () use ($userController) {
    logoutMiddleware(function () use ($userController) {
        $userController->logout();
    });
});


$router->map('GET', '/add-materi', function () {
    authAdmin(function () {
        require __DIR__ . '/../views/addMateri.php';
    });
});

$router->map('GET', '/quiz-edit/[:id]', function ($id) {
    authAdmin(function () {
        require __DIR__ . '/../views/quizEdit.php';
    });
});

$router->map('GET', '/add-quiz', function () {
    authAdmin(function () {
        require __DIR__ . '/../views/addQuiz.php';
    });
});

$router->map('GET', '/quiz/[:id]', function ($id) {
    apiNoLogin( function () {
        require __DIR__ . '/../views/quiz.php';
    });
});

$router->map('GET', '/404', function () {
    require __DIR__ . '/../views/404.php';
});

$router->map('GET', '/phpinfo', function () {
    require __DIR__ . '/../views/phpInfo.php';
});

$router->map('GET', '/book-detail', function () {
    apiNoLogin( function () {
        require __DIR__ . '/../views/bookDetail.php';
    });
});

// $router->map('GET', '/user/[i:id]', function($id) {
//     echo 'User ID: ' . $id;
// });

return $router;
