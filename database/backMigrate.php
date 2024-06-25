<?php
require_once __DIR__ . '/logger.php';
$logs = new Logger();

try {
require_once __DIR__ . '/con_database.php';
    $db = new SQLite3($dbPath);

    $tables = ['books','users'];

    foreach ($tables as $table) {
        $query = "DROP TABLE IF EXISTS $table";
        $db->exec($query);
    }
} catch (Exception $e) {
    $logs->error("Error: " . $e->getMessage());
}