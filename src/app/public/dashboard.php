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
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f5f0">
    <title>Dashboard | Simple Login</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body class="dashboard-page">
    <main class="dashboard-shell">
        <header class="dashboard-header">
            <a class="brand" href="/dashboard.php">
                <span class="brand-mark" aria-hidden="true">S</span>
                <span>Simple Login</span>
            </a>
            <form method="POST">
                <button class="button button--secondary" type="submit" name="logout" value="1">Keluar</button>
            </form>
        </header>
        <section class="dashboard-content" aria-labelledby="dashboard-title">
            <p class="eyebrow">RUANG AKUN</p>
            <h1 id="dashboard-title">Selamat datang, <?= htmlspecialchars($_SESSION["username"], ENT_QUOTES, 'UTF-8') ?>.</h1>
            <p>Kamu berhasil masuk.</p>
        </section>
    </main>
</body>
</html>

