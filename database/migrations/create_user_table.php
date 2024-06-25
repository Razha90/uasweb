<?php
require_once __DIR__ . '/../logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/../con_database.php';
    
    $db = new SQLite3($dbPath);

    $query = "
        CREATE TABLE IF NOT EXISTS users (
            id VARCHAR(12) PRIMARY KEY, 
            username VARCHAR(100),
            password VARCHAR(100),
            display_name VARCHAR(150),
            role VARCHAR(10) DEFAULT 'guest',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $db->exec($query);

    $triggerQuery = "
        CREATE TRIGGER update_users_updated_at
        AFTER UPDATE ON users
        FOR EACH ROW
        BEGIN
            UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";

    $db->exec($triggerQuery);

} catch (PDOException $e) {
    $logs->error($e->getMessage());
}
