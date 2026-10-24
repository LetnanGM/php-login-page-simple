<?php
require_once __DIR__ . "/config/koneksi.php";

$query = "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL, email TEXT UNIQUE NOT NULL, password TEXT NOT NULL)";
$db->exec($query);

echo "Database berhasil dibentuk!";