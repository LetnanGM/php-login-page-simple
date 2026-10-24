<?php
require_once __DIR__ . "/config.php";

try {
    $db = new PDO("sqlite:" . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database berhasil dibuat/dihubungkan!";
} catch (PDOException $e) {
    echo "Koneksi/pembuatan database gagal: " . $e->getMessage();
}
