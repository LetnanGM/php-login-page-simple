<?php
require_once __DIR__ . "/../core/handler_request.php";
session_start();
require_once __DIR__ . "/../core/handler_csrf.php";
only_access_with_and(["GET", "POST"], true);
require_once __DIR__ . "/../config/koneksi.php";
require_once __DIR__ . "/../core/handler_password_reset.php";

$error = null;
$success = false;
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedEmail = $_POST["email"] ?? "";
    $email = is_string($submittedEmail) ? trim($submittedEmail) : "";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Masukkan alamat email yang valid.";
    } else {
        try {
            store_password_reset_request($db, $email);
            $success = true;
        } catch (Throwable $exception) {
            error_log("[password-reset-request] " . $exception);
            http_response_code(500);
            $error = "Permintaan belum dapat disimpan. Silakan coba lagi nanti.";
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f2f5f0">
    <title>Lupa password | Simple Login</title>
    <link rel="stylesheet" href="/app.css">
</head>
<body>
    <main class="auth-layout">
        <section class="auth-intro" aria-labelledby="intro-title">
            <a class="brand" href="/index.php">
                <span class="brand-mark" aria-hidden="true">S</span>
                <span>Simple Login</span>
            </a>
            <p class="eyebrow">BANTUAN AKUN</p>
            <h1 id="intro-title">Perlu bantuan masuk?</h1>
            <p class="intro-copy">Kirim permintaan pemulihan. Untuk demo ini, permintaan akan masuk ke mailbox admin dan belum mengirim email.</p>
        </section>
        <section class="auth-panel" aria-labelledby="form-title">
            <header class="panel-heading">
                <p class="eyebrow">PEMULIHAN PASSWORD</p>
                <h2 id="form-title">Kirim permintaan</h2>
                <p>Admin akan meninjau permintaan dari mailbox dashboard.</p>
            </header>
            <?php if ($success): ?>
                <div class="form-success" role="status">
                    Jika permintaan dapat diproses, admin akan meninjaunya melalui mailbox dashboard.
                </div>
            <?php elseif ($error !== null): ?>
                <div class="form-error" role="alert">
                    <p><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></p>
                </div>
            <?php endif; ?>
            <?php if (!$success): ?>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(create_csrf_token(), ENT_QUOTES, "UTF-8") ?>">
                    <div class="form-field">
                        <label for="email">Email akun</label>
                        <input id="email" type="email" name="email" autocomplete="email" placeholder="nama@contoh.com" value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>" required>
                    </div>
                    <button class="button button--full" type="submit">Kirim permintaan</button>
                </form>
            <?php endif; ?>
            <p class="form-footer"><a href="/index.php">Kembali ke halaman masuk</a></p>
        </section>
    </main>
</body>
</html>
