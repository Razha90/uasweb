<?php
require_once __DIR__ . '/../model/user.php';
require_once __DIR__ . '/../database/logger.php';

class UserController
{
  public function register()
  {
    $logger = new Logger();
    $user = new User();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $display_name = $_POST['display_name'] ?? '';
    if (empty($username) || empty($password) || empty($display_name)) {
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit;
    }
    $randomId = bin2hex(random_bytes(12));
    $createUser = $user->register($randomId, $password, $username, $display_name);
    if ($createUser){
      $logger->success("User $username has been registered");
      header("Location: /login");
      exit();
      } else {
      $logger->error("Failed to register user $username");
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit();
    }

  }

  public function login()
  {
    $user = new User();
    $logger = new Logger();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit;
    }
    $login = $user->login($username, $password);

    if ($login !== null) {

      $logger->success("User $username has been logged in");
      $_SESSION['display_name'] = $login['display_name'];
      $_SESSION['role'] = $login['role'];
      $_SESSION['username'] = $username;
      file_put_contents(__DIR__ . '/../logs/app.log', sprintf("[%s][%s] %s\n", date('Y-m-d H:i:s'), '[ERROR]', $login['id']), FILE_APPEND);
      $_SESSION['id'] = $login['id'];
      header("Location: /");
      exit();
    } else {
      $logger->error("Failed to login user $username");
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit();
    }
  }

  public function logout()
  {
    session_destroy();
    header("Location: /");
    exit();
  }
}