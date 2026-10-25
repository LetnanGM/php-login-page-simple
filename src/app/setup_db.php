<?php
require_once __DIR__ . "/config/koneksi.php";

$query = "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, email TEXT UNIQUE NOT NULL, password TEXT NOT NULL, role TEXT NOT NULL DEFAULT 'user', last_login DATETIME, last_logout DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP)";
$db->exec($query);

$columns = $db->query("PRAGMA table_info(users)")->fetchAll(PDO::FETCH_COLUMN, 1);
if (!in_array("role", $columns, true)) {
    $db->exec("ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT 'user'");
}

$db->exec("CREATE TABLE IF NOT EXISTS password_reset_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("CREATE TABLE IF NOT EXISTS url_shortener (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    original_url TEXT NOT NULL,
    shortened_url TEXT NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

echo "Database berhasil dibentuk!";