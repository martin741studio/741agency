<?php
// db_init.php
require 'config.php';

$db = getDB();

try {
    // Create Users Table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        role TEXT DEFAULT 'client', -- 'admin' or 'client'
        assigned_folder TEXT -- e.g., 'reload-sanctuary'
    )");

    // Create Comments Table
    $db->exec("CREATE TABLE IF NOT EXISTS comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        report_slug TEXT NOT NULL, -- e.g., 'reload-sanctuary'
        user_id INTEGER NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");

    // Create Dummy Users if not exist
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    if ($stmt->fetchColumn() == 0) {
        // Admin user
        $pass = password_hash('admin123', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'admin')")
            ->execute(['admin', $pass]);
        echo "Created user 'admin' (password: admin123)<br>";
    }

    $stmt->execute(['reload']);
    if ($stmt->fetchColumn() == 0) {
        // Client user
        $pass = password_hash('reload2026', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO users (username, password_hash, role, assigned_folder) VALUES (?, ?, 'client', 'reload-sanctuary')")
            ->execute(['reload', $pass]);
        echo "Created user 'reload' (password: reload2026)<br>";
    }

    echo "Database initialized successfully.";

} catch (PDOException $e) {
    echo "Error initializing database: " . $e->getMessage();
}
?>