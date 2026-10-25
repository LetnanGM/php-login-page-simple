<?php
require_once __DIR__ . "/../config/koneksi.php";
require_once __DIR__ . "/../core/handler_urishortener.php";

$code = $_GET["code"] ?? "";
if (!is_string($code) || !preg_match('/\A[a-zA-Z0-9_-]{3,32}\z/', $code)) {
    http_response_code(404);
    exit("Tautan tidak ditemukan.");
}

try {
    $originalUrl = (new UriShortenerHandler($db))->getOriginalUrl($code);
} catch (Throwable $exception) {
    error_log("[url-shortener-redirect] " . $exception);
    http_response_code(500);
    exit("Tautan belum dapat dibuka. Silakan coba lagi nanti.");
}

if ($originalUrl === null) {
    http_response_code(404);
    exit("Tautan tidak ditemukan.");
}

header("Location: " . $originalUrl, true, 302);
exit;
