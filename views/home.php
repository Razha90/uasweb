<?php
if (isset($_SESSION['display_name'])) {
    $logged_in = true;
    $display_name = $_SESSION['display_name'];
    $username = $_SESSION['username'];
} else {
    $logged_in = false;
}

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
<?php if ($logged_in): ?>
    <p>Welcome, <?= $display_name ?> You are logged in.</p>
    <p><a href="/logout">Logout</a></p>
  <?php else: ?>
    <p>You are not logged in. Please login to continue.</p>
    <h3></h3>
    <p><a href="/login">Login</a></p>
  <?php endif; ?>

  <form id="bookForm" class="text-3xl">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>
    <br>
    <label for="author">Author</label>
    <input type="text" name="author" id="author" required>
    <br>
    <label for="synopsis">Synopsis</label>
    <textarea name="synopsis" id="synopsis" required></textarea>
    <br>
    <label for="published_year">Published Year</label>
    <input type="number" name="published_year" id="published_year" required>
    <br>
    <label for="image_url">Image URL</label>
    <input type="file" name="image" id="image" required>
    <br>
    <button type="submit">Submit</button>
  </form>

  <script>
    document.getElementById('bookForm').addEventListener('submit', async function(event) {
      event.preventDefault(); // Mencegah form dari submit default

      const form = event.target;
      const formData = new FormData(form);

      const response = await fetch('/api/book', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();
      console.log(result);
    });
  </script>
</body>
</html>
