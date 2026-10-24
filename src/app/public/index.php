<?php
require_once __DIR__ . "/../config/koneksi.php";
require_once __DIR__ . "/../core/handle_db.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["email"] ?? '');

    if (!empty($email) && !empty($password)) {
        $result = do_login($db, $email, $password);

        if ($result !== False) {
            Header("Location: /dashboard.php", true, 302);
        } else {
            Header("Location: /index.php");
        }
    } else {
        return "<h2>Data Harus diisi semua!</h2>";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        <label for="email">Email : </label>
        <input type="email" name="email" placeholder="Masukkan Email" required>
        <label for="password">Password : </label>
        <input type="password" name="password" placeholder="Masukkan Password" required>
        
        <button type="submit">Login!</button>
    </form>
</body>
</html>