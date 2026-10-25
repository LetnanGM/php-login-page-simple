<?php
require_once __DIR__ . "/../config/koneksi.php";

function do_register(PDO $db, string $username, string $email, string $password): bool {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword
    ]);

    if ($stmt->rowCount() === 0) {
        return false;
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
    $_SESSION["username"] = $username;

    return true;
}


function do_login(PDO $db, string $email, string $password): bool {
    $statement = $db->prepare("SELECT username, password FROM users WHERE email = :email");
    $statement->execute([
        ':email' => $email
    ]);
    
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
    $_SESSION["username"] = $user["username"];

    return true;
}
?>
