<?php
require_once __DIR__ . "/config.php";

$db = new PDO("sqlite:" . $db_path);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
