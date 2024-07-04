<?php
try {
    if (!file_exists(HOUND_DB_PATH)) {
        $dbFile = fopen(HOUND_DB_PATH, 'w');
        if ($dbFile) {
            fclose($dbFile);
        } else {
            throw new Exception('Unable to create database file.');
        }
    }

    $db = new PDO('sqlite:' . HOUND_DB_PATH);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec('
        CREATE TABLE IF NOT EXISTS posts (
            post_id INTEGER PRIMARY KEY,
            post_slug TEXT NOT NULL,
            post_type TEXT NOT NULL,
            post_title TEXT NOT NULL,
            post_content TEXT NOT NULL,
            post_template TEXT,
            post_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            post_author TEXT
        )
    ');
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'General error: ' . $e->getMessage();
}