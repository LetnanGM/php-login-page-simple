<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (!isset($_SESSION["username"]) || !is_string($_SESSION["username"])) {
    http_response_code(403);
    exit("Silakan masuk untuk menggunakan pemendek URL.");
}

require_once __DIR__ . "/../../../core/handler_csrf.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_valid_csrf_token();
}

require_once __DIR__ . "/../../../config/koneksi.php";
require_once __DIR__ . "/../../../core/handler_urishortener.php";

$uriShortenerHandler = new UriShortenerHandler($db);
$originalUrl = "";
$customAlias = "";
$result = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $submittedUrl = $_POST["original_url"] ?? "";
    $submittedAlias = $_POST["custom_alias"] ?? "";
    $originalUrl = is_string($submittedUrl) ? trim($submittedUrl) : "";
    $customAlias = is_string($submittedAlias) ? trim($submittedAlias) : "";

    try {
        $result = $uriShortenerHandler->shortenUrl($originalUrl, $customAlias);
        if (!$result["success"]) {
            $error = $result["message"];
        }
    } catch (Throwable $exception) {
        error_log("[url-shortener] " . $exception);
        http_response_code(500);
        $error = "URL belum dapat dipendekkan. Silakan coba lagi nanti.";
        $result = ["success" => false, "message" => $error];
    }

    if (str_contains($_SERVER["HTTP_ACCEPT"] ?? "", "application/json")) {
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($result, JSON_THROW_ON_ERROR);
        exit;
    }
}

$shortUrl = isset($result["shortened_url"])
    ? "/go.php?code=" . rawurlencode($result["shortened_url"])
    : null;
?>
<section class="shortener-page" aria-labelledby="shortener-title">
    <header class="shortener-heading">
        <div>
            <p class="eyebrow">ALAT PRAKTIS</p>
            <h1 class="h2 mb-2" id="shortener-title">Pemendek URL</h1>
            <p class="text-secondary mb-0">Buat tautan ringkas yang mudah dibagikan dan dikelola.</p>
        </div>
        <span class="shortener-icon" aria-hidden="true">↗</span>
    </header>

    <div class="shortener-layout">
        <div class="card shortener-card">
            <div class="card-body p-4 p-lg-5">
                <h2 class="h5 mb-1">Buat tautan baru</h2>
                <p class="text-secondary mb-4">Tempel URL tujuanmu untuk mulai.</p>

                <?php if ($error !== null): ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?></div>
                <?php elseif ($shortUrl !== null): ?>
                    <div class="alert alert-success" role="status">
                        Tautan berhasil dibuat. Siap dibagikan!
                    </div>
                <?php endif; ?>

                <form
                    id="shortener-form"
                    method="POST"
                    action="/dashboard/dashboard.php?feature=uri_short"
                >
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(create_csrf_token(), ENT_QUOTES, "UTF-8") ?>">
                    <div class="form-field">
                        <label for="original-url">URL tujuan</label>
                        <input
                            id="original-url"
                            type="url"
                            name="original_url"
                            placeholder="https://contoh.com/halaman-panjang"
                            value="<?= htmlspecialchars($originalUrl, ENT_QUOTES, "UTF-8") ?>"
                            autocomplete="url"
                            required
                        >
                        <span class="form-hint">Pastikan URL diawali dengan https:// atau http://</span>
                    </div>
                    <div class="form-field">
                        <label for="custom-alias">Alias khusus <span class="optional-label">(opsional)</span></label>
                        <div class="alias-input">
                            <span aria-hidden="true">/</span>
                            <input
                                id="custom-alias"
                                type="text"
                                name="custom_alias"
                                placeholder="promo-kamu"
                                value="<?= htmlspecialchars($customAlias, ENT_QUOTES, "UTF-8") ?>"
                                minlength="3"
                                maxlength="32"
                                pattern="[a-zA-Z0-9_-]{3,32}"
                                aria-describedby="alias-help"
                            >
                        </div>
                        <span class="form-hint" id="alias-help">3–32 karakter: huruf, angka, tanda hubung, atau garis bawah.</span>
                    </div>
                    <button class="button button--full shortener-submit" type="submit">
                        <span>Buat tautan ringkas</span>
                        <span aria-hidden="true">→</span>
                    </button>
                    <p class="shortener-status" id="shortener-status" role="status" aria-live="polite"></p>
                </form>

                <?php if ($shortUrl !== null): ?>
                    <div class="shortener-result" id="shortener-result">
                        <p class="eyebrow mb-2">TAUTAN RINGKASMU</p>
                        <div class="shortener-result-row">
                            <a href="<?= htmlspecialchars($shortUrl, ENT_QUOTES, "UTF-8") ?>" target="_blank" rel="noopener noreferrer">
                                <?= htmlspecialchars($shortUrl, ENT_QUOTES, "UTF-8") ?>
                            </a>
                            <button class="btn btn-sm btn-outline-success" type="button" data-copy-url="<?= htmlspecialchars($shortUrl, ENT_QUOTES, "UTF-8") ?>">Salin</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <aside class="shortener-aside">
            <div class="shortener-aside-mark" aria-hidden="true">↗</div>
            <h2 class="h5">Lebih ringkas, lebih mudah diingat.</h2>
            <p class="text-secondary mb-0">Gunakan alias khusus agar tautanmu terasa personal dan gampang dikenali.</p>
        </aside>
    </div>
</section>
<script>
    if (!window.uriShortenerUiBound) {
        window.uriShortenerUiBound = true;

        document.body.addEventListener("submit", async (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || form.id !== "shortener-form") {
                return;
            }

            event.preventDefault();
            const button = form.querySelector("button[type=submit]");
            const status = document.getElementById("shortener-status");
            const originalButtonText = button.querySelector("span").textContent;
            button.disabled = true;
            button.querySelector("span").textContent = "Membuat tautan...";
            status.textContent = "";

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                    headers: { Accept: "application/json" },
                    credentials: "same-origin",
                });
                const result = await response.json();
                if (!response.ok || !result.success) {
                    throw new Error(result.message || "URL belum dapat dipendekkan. Silakan coba lagi.");
                }

                const shortUrl = new URL("/go.php", window.location.origin);
                shortUrl.searchParams.set("code", result.shortened_url);
                const output = document.createElement("div");
                output.className = "shortener-result";
                output.id = "shortener-result";
                output.innerHTML = '<p class="eyebrow mb-2">TAUTAN RINGKASMU</p><div class="shortener-result-row"><a target="_blank" rel="noopener noreferrer"></a><button class="btn btn-sm btn-outline-success" type="button">Salin</button></div>';
                const link = output.querySelector("a");
                link.href = shortUrl.href;
                link.textContent = shortUrl.pathname + shortUrl.search;
                output.querySelector("button").dataset.copyUrl = shortUrl.href;
                document.getElementById("shortener-result")?.remove();
                form.insertAdjacentElement("afterend", output);
                status.textContent = "Tautan berhasil dibuat. Siap dibagikan!";
                status.className = "shortener-status text-success";
            } catch (error) {
                status.textContent = error instanceof Error ? error.message : "Terjadi kesalahan. Silakan coba lagi.";
                status.className = "shortener-status text-danger";
            } finally {
                button.disabled = false;
                button.querySelector("span").textContent = originalButtonText;
            }
        });

        document.body.addEventListener("click", async (event) => {
            const button = event.target instanceof Element
                ? event.target.closest("[data-copy-url]")
                : null;
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            const status = document.getElementById("shortener-status");
            try {
                await navigator.clipboard.writeText(new URL(button.dataset.copyUrl, window.location.origin).href);
                status.textContent = "Tautan berhasil disalin.";
                status.className = "shortener-status text-success";
            } catch {
                status.textContent = "Tidak dapat menyalin otomatis. Pilih tautannya lalu salin.";
                status.className = "shortener-status text-danger";
            }
        });
    }
</script>
