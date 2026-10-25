<?php

function store_password_reset_request(PDO $db, string $email): bool {
    $recentRequest = $db->prepare(
        "SELECT id FROM password_reset_requests
        WHERE email = :email
        AND created_at >= datetime('now', '-15 minutes')
        LIMIT 1"
    );
    $recentRequest->execute([':email' => $email]);

    if ($recentRequest->fetchColumn() !== false) {
        return false;
    }

    $statement = $db->prepare(
        "INSERT INTO password_reset_requests (email, status)
        VALUES (:email, 'pending')"
    );
    $statement->execute([':email' => $email]);

    return true;
}

function get_password_reset_requests(PDO $db): array {
    $statement = $db->query(
        "SELECT id, email, status, created_at
        FROM password_reset_requests
        ORDER BY created_at DESC, id DESC"
    );

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>
