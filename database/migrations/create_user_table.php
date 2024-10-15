<?php
require_once __DIR__ . '/../logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/../con_database.php';
    
    $db = new SQLite3($dbPath);

    // Tabel pengguna
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

    // Trigger untuk memperbarui timestamp saat user diperbarui
    $triggerQuery = "
        CREATE TRIGGER update_users_updated_at
        AFTER UPDATE ON users
        FOR EACH ROW
        BEGIN
            UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";
    $db->exec($triggerQuery);

    // Tabel pesan
    $messageQuery = "
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sender_id VARCHAR(12),
            receiver_id VARCHAR(12),
            message TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (sender_id) REFERENCES users (id),
            FOREIGN KEY (receiver_id) REFERENCES users (id)
        )
    ";
    $db->exec($messageQuery);

    // Trigger untuk memperbarui timestamp saat pesan dikirim
    $messageTriggerQuery = "
        CREATE TRIGGER update_messages_created_at
        AFTER INSERT ON messages
        FOR EACH ROW
        BEGIN
            UPDATE messages SET created_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";
    $db->exec($messageTriggerQuery);

} catch (PDOException $e) {
    $logs->error($e->getMessage());
}
