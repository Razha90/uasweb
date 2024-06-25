<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$fullUrl = "$protocol://$host$uri";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="/register" method="post">
    <div>
      <label for="display_name">Display Name</label>
      <input type="text" name="display_name" id="display_name" required>
    </div>
    <div>
      <label for="username">Username</label>
      <input type="text" name="username" id="username" required>
    </div>
    <div>
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <button type="submit">Register</button>
  </form>
</body>
</html>