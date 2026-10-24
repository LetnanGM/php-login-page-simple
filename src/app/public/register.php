<?php
session_start();
$error = null;
$debugError = null;

try {
    require_once __DIR__ . "/../config/koneksi.php";
    require_once __DIR__ . "/../core/handler_db.php";

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $username = trim($_POST["username"] ?? '');
        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            $error = "Semua kolom wajib diisi.";
        } elseif (strlen($password) < 8) {
            $error = "Password harus memiliki minimal 8 karakter.";
        } elseif (do_register($db, $username, $email, $password)) {
            header("Location: /dashboard.php", true, 302);
            exit;
        } else {
            $error = "Email tersebut sudah terdaftar. Silakan masuk.";
        }
    }
} catch (Throwable $exception) {
    error_log("[register] " . $exception);
    $error = "Pendaftaran tidak dapat diproses. Coba lagi atau periksa log aplikasi.";
    $debugError = getenv("APP_DEBUG") === "1" ? $exception->getMessage() : null;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
</head>
<body>
    <h1>Buat akun</h1>
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
        <label for="username">Username : </label>
        <input id="username" type="text" name="username" placeholder="Masukkan Username" value="<?= htmlspecialchars($_POST["username"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        <label for="email">Email : </label>
        <input id="email" type="email" name="email" placeholder="Masukkan Email" value="<?= htmlspecialchars($_POST["email"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        <label for="password">Password : </label>
        <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
        
        <button type="submit">Buat akun</button>
    </form>
    <p>Sudah punya akun? <a href="/index.php">Masuk</a></p>
</body>
</html>