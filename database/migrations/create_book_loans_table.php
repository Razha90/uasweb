<?php
require_once __DIR__ . '/../logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/../con_database.php';
    
    $db = new SQLite3($dbPath);

    $query = '
    CREATE TABLE IF NOT EXISTS book_loans (
        id VARCHAR(12) PRIMARY KEY,
        user_id VARCHAR(12) REFERENCES users(id) ON DELETE CASCADE,
        book_id VARCHAR(12) REFERENCES books(id) ON DELETE CASCADE,
        loan_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        return_date TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
';


    $db->exec($query);

    $triggerQuery = "
        CREATE TRIGGER update_book_loans_updated_at
        AFTER UPDATE ON book_loans
        FOR EACH ROW
        BEGIN
            UPDATE book_loans SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";

    $db->exec($triggerQuery);

} catch (PDOException $e) {
    $logs->error($e->getMessage());
}
