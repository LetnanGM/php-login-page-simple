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


<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f5f0">
    <title>Daftar | Simple Login</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>
    <main class="auth-layout">
        <section class="auth-intro" aria-labelledby="intro-title">
            <a class="brand" href="/index.php">
                <span class="brand-mark" aria-hidden="true">S</span>
                <span>Simple Login</span>
            </a>
            <p class="eyebrow">AKUN / AKSES</p>
            <h1 id="intro-title">Mulai dengan akunmu.</h1>
            <p class="intro-copy">Buat akun untuk masuk ke ruang pribadimu.</p>
        </section>
        <section class="auth-panel" aria-labelledby="form-title">
            <header class="panel-heading">
                <p class="eyebrow">BUAT AKUN</p>
                <h2 id="form-title">Daftar ke Simple Login</h2>
                <p>Isi detail berikut untuk memulai.</p>
            </header>
            <?php if ($error !== null): ?>
                <div class="form-error" role="alert" aria-live="polite">
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
                <div class="form-field">
                    <label for="username">Nama pengguna</label>
                    <input id="username" type="text" name="username" autocomplete="username" placeholder="Nama pengguna" value="<?= htmlspecialchars($_POST["username"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" autocomplete="email" placeholder="nama@contoh.com" value="<?= htmlspecialchars($_POST["email"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" minlength="8" placeholder="Minimal 8 karakter" required>
                </div>
                <button class="button button--full" type="submit">Buat akun</button>
            </form>
            <p class="form-footer">Sudah punya akun? <a href="/index.php">Masuk</a></p>
        </section>
    </main>
</body>
</html>