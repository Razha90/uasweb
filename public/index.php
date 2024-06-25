<?php
session_start();
// Direktori public
$publicDir = __DIR__ . '/public';

// Ambil URI dari request
$requestUri = $_SERVER['REQUEST_URI'];

// Buat path lengkap untuk file yang diminta
$filePath = realpath($publicDir . parse_url($requestUri, PHP_URL_PATH));

// Periksa apakah file tersebut ada dan berada di dalam folder public
if ($filePath && strpos($filePath, $publicDir) === 0 && is_file($filePath)) {
    // Tentukan MIME type file yang diminta
    $mimeType = mime_content_type($filePath);
    header('Content-Type: ' . $mimeType);
    readfile($filePath);
    exit;
}

// Load routers
$webRouter = require_once __DIR__ . '/../router/web.php';
$apiRouter = require_once __DIR__ . '/../router/api.php';

// Periksa apakah URI dimulai dengan '/api'
if (strpos($requestUri, '/api') === 0) {
    // Jika URI dimulai dengan '/api', gunakan router API
    $apiMatch = $apiRouter->match($requestUri);
    if ($apiMatch && is_callable($apiMatch['target'])) {
        call_user_func_array($apiMatch['target'], $apiMatch['params']);
    } else {
        // Rute API tidak ditemukan
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['error' => 'API route not found']);
    }
} else {
    // Jika URI tidak dimulai dengan '/api', gunakan router web
    $webMatch = $webRouter->match($requestUri);
    if ($webMatch && is_callable($webMatch['target'])) {
        call_user_func_array($webMatch['target'], $webMatch['params']);
    } else {
        // Rute web tidak ditemukan
        header("Location: /404");
        exit();
    }
}
