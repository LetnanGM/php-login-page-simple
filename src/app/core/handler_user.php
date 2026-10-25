<?php
require_once __DIR__ . "/../config/koneksi.php";

function get_information(PDO $db, string $username): ?array {
    $stmt = $db->prepare("SELECT username, email, last_login, last_logout FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

    return [
        'username' => htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'),
        'last_login' => htmlspecialchars($user['last_login'], ENT_QUOTES, 'UTF-8'),
        'last_logout' => htmlspecialchars($user['last_logout'], ENT_QUOTES, 'UTF-8'),
    ];
}

?>