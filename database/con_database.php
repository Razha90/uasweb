<?php
require_once __DIR__ . '/../database/logger.php';
$logs = new Logger();

$dbPath = __DIR__ . '../database.sqlite';

$db = new SQLite3($dbPath);

if (!$db) {
    $logs->error("Koneksi gagal: " . $db->lastErrorMsg());
    die("Koneksi gagal: " . $db->lastErrorMsg());
}