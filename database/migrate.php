<?php
require_once __DIR__ . '/logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/con_database.php';

    $migrationsDir = __DIR__ . '/migrations/';
    $files = scandir($migrationsDir);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $filePath = $migrationsDir . $file;
            require_once $filePath;
            $logs->success("File migrasi $file berhasil dijalankan.");
        }
    }

    $logs->success("Semua file migrasi berhasil dijalankan.");
} catch (PDOException $e) {
    $logs->error("Error migrasi: " . $e->getMessage());
}
