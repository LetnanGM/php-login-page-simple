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

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f5f0">
    <title>Masuk | Simple Login</title>
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
            <h1 id="intro-title">Senang melihatmu kembali.</h1>
            <p class="intro-copy">Masuk untuk melanjutkan ke ruang akunmu.</p>
        </section>
        <section class="auth-panel" aria-labelledby="form-title">
            <header class="panel-heading">
                <p class="eyebrow">SELAMAT DATANG</p>
                <h2 id="form-title">Masuk ke akun</h2>
                <p>Gunakan email dan password yang terdaftar.</p>
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
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" autocomplete="email" placeholder="nama@contoh.com" value="<?= htmlspecialchars($_POST["email"] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan password" required>
                </div>
                <button class="button button--full" type="submit">Masuk</button>
            </form>
            <p class="form-footer">Belum punya akun? <a href="/register.php">Daftar</a></p>
        </section>
    </main>
</body>
</html>