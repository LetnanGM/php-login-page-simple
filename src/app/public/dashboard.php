<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: /index.php", true, 302);
    exit;
}

if (!isset($_SESSION["username"])) {
    header("Location: /index.php", true, 302);
    exit;
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
    <h1>Welcome back! <?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, 'UTF-8') ?>!</h1>
    <form method="POST">
        <button type="submit" name="logout" value="1">Keluar</button>
    </form>
</body>
</html>

