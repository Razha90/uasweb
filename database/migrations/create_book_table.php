<?php
require_once __DIR__ . '/../logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/../con_database.php';
    
    $db = new SQLite3($dbPath);

    $query = '
        CREATE TABLE IF NOT EXISTS books (
            id VARCHAR(12) PRIMARY KEY, 
            title VARCHAR(100),
            author VARCHAR(100),
            synopsis TEXT,
            image_url TEXT,
            published_year INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ';

    $db->exec($query);

    $triggerQuery = "
        CREATE TRIGGER update_books_updated_at
        AFTER UPDATE ON books
        FOR EACH ROW
        BEGIN
            UPDATE books SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";

    $db->exec($triggerQuery);

} catch (PDOException $e) {
    $logs->error($e->getMessage());
}
