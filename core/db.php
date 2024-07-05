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

    $db->exec('
        CREATE TABLE IF NOT EXISTS nodes (
            node_id INTEGER PRIMARY KEY,
            node_url TEXT NOT NULL,
            node_title TEXT NOT NULL,
            node_order INTEGER NOT NULL,
            node_location TEXT NOT NULL
        )
    ');

    $db->exec('
        CREATE TABLE IF NOT EXISTS settings (
            option_id INTEGER PRIMARY KEY,
            option_name TEXT NOT NULL,
            option_value TEXT NOT NULL
        )
    ');

    /*
    // Add the node_title column if it does not exist
    $result = $db->query("PRAGMA table_info(nodes)");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);

    $columnExists = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'node_title') {
            $columnExists = true;
            break;
        }
    }

    if (!$columnExists) {
        $db->exec('
            ALTER TABLE nodes
            ADD COLUMN node_title TEXT
        ');
    }
    /**/
} catch (PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
} catch (Exception $e) {
    echo 'General error: ' . $e->getMessage();
}