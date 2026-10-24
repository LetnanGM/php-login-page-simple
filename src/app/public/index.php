<?php
session_start();
$error = null;
$debugError = null;

try {
    require_once __DIR__ . "/../config/koneksi.php";
    require_once __DIR__ . "/../core/handler_db.php";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if ($email === '' || $password === '') {
            $error = "Email dan password wajib diisi.";
        } elseif (do_login($db, $email, $password)) {
            header("Location: /dashboard.php", true, 302);
            exit;
        } else {
            $error = "Email atau password tidak cocok.";
        }
    }
} catch (Throwable $exception) {
    error_log("[login] " . $exception);
    $error = "Login tidak dapat diproses. Coba lagi atau periksa log aplikasi.";
    $debugError = getenv("APP_DEBUG") === "1" ? $exception->getMessage() : null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk</title>
</head>
<body>
    <h1>Masuk</h1>
    <?php if ($error !== null): ?>
        <div role="alert">
            <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
            <?php if ($debugError !== null): ?>
                <details>
                    <summary>Detail debugging</summary>
                    <pre><?= htmlspecialchars($debugError, ENT_QUOTES, 'UTF-8') ?></pre>
                </details>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
        <label for="email">Email : </label>
        <input id="email" type="email" name="email" placeholder="Masukkan Email" value="<?= htmlspecialchars($_POST["email"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        <label for="password">Password : </label>
        <input id="password" type="password" name="password" placeholder="Masukkan Password" required>
        
        <button type="submit">Masuk</button>
    </form>
    <p>Belum punya akun? <a href="/register.php">Daftar</a></p>
</body>
</html>