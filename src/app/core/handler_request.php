<?php

/**
 * Allows access only with specific request types and optionally checks for a valid CSRF token.
 *
 * @param array $request_types The allowed request types.
 * @param bool $check_csrf Whether to check for a valid CSRF token.
 * @return void
 */
function only_access_with_and(array $request_types, bool $check_csrf = false): void {
    $current_request_type = $_SERVER["REQUEST_METHOD"] ?? "";

    if (!in_array(strtoupper($current_request_type), array_map("strtoupper", $request_types), true)) {
        show_forbidden_page();
    }

    if ($check_csrf && strtoupper($current_request_type) === "POST") {
        require_once __DIR__ . "/handler_csrf.php";
        require_valid_csrf_token();
    }
}

/**
 * This function help server to handle REQUEST_METHOD
 * if client REQUEST_METHOD not suitable with what server expect
 * it will return 403 Page.
 */
function only_access_with(string $request_type): void {
    $current_request_type = $_SERVER["REQUEST_METHOD"] ?? "";

    if (strtoupper($current_request_type) === strtoupper($request_type)) {
        return;
    }

    show_forbidden_page();
}

function show_forbidden_page(): void {
    http_response_code(403);
    require __DIR__ . "/../public/403.php";
    exit;
}

function only_access_with_post(): void {
    only_access_with("POST");
}

function only_access_with_get(): void {
    only_access_with("GET");
}

function only_access_with_put(): void {
    only_access_with("PUT");
}

function only_access_with_delete(): void {
    only_access_with("DELETE");
}

function only_access_with_patch(): void {
    only_access_with("PATCH");
}

function only_access_with_options(): void {
    only_access_with("OPTIONS");
}

?>