<?php
/**
 * CSRF (Cross-Site Request Forgery) protection functions.
 * @return string The generated CSRF token.
 * 
 */
function create_csrf_token(): string {
    if (!isset($_SESSION["csrf_token"]) || !is_string($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}
/**
 * Validates the CSRF token from the form submission against the token stored in the session.
 * @return bool True if the tokens match, false otherwise.
 */
function csrf_token_is_valid(): bool {
    $saved_token = $_SESSION["csrf_token"] ?? "";
    $form_token = $_POST["csrf_token"] ?? "";

    return is_string($saved_token)
        && is_string($form_token)
        && $saved_token !== ""
        && hash_equals($saved_token, $form_token);
}

/**
 * Requires a valid CSRF token for POST requests. If the token is invalid, it will show a 403 page.
 */
function require_valid_csrf_token(): void {
    if (csrf_token_is_valid()) {
        return;
    }

    http_response_code(403);
    require __DIR__ . "/../public/403.php";
    exit;
}
