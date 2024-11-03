<?php
require_once __DIR__ . '/../logger.php';
$logs = new Logger();

try {
    require_once __DIR__ . '/../con_database.php';

    $db = new SQLite3($dbPath);

    // Tabel kuis
    $quizQuery = "
        CREATE TABLE IF NOT EXISTS quiz (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(150),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    $db->exec($quizQuery);

    // Trigger untuk memperbarui timestamp saat kuis diperbarui
    $quizTriggerQuery = "
        CREATE TRIGGER update_quiz_updated_at
        AFTER UPDATE ON quiz
        FOR EACH ROW
        BEGIN
            UPDATE quiz SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";
    $db->exec($quizTriggerQuery);

    // Tabel pertanyaan
    $questionQuery = "
        CREATE TABLE IF NOT EXISTS question (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            quiz_id INTEGER,
            order_number INTEGER,
            question TEXT,
            option1 VARCHAR(255),
            option2 VARCHAR(255),
            option3 VARCHAR(255),
            option4 VARCHAR(255),
            answer VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE
        )
    ";
    $db->exec($questionQuery);

    // Trigger untuk memperbarui timestamp saat pertanyaan diperbarui
    $questionTriggerQuery = "
        CREATE TRIGGER update_question_updated_at
        AFTER UPDATE ON question
        FOR EACH ROW
        BEGIN
            UPDATE question SET updated_at = CURRENT_TIMESTAMP WHERE id = NEW.id;
        END;
    ";
    $db->exec($questionTriggerQuery);

    $scoreQuery = "
    CREATE TABLE IF NOT EXISTS score (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id VARCHAR(12) REFERENCES users(id) ON DELETE CASCADE,
        quiz_id INTEGER,
        score INTEGER,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE
    )
";
    $db->exec($scoreQuery);

} catch (PDOException $e) {
    $logs->error($e->getMessage());
}
