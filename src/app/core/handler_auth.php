<?php
require_once __DIR__ . "/../config/koneksi.php";

function do_register(PDO $db, string $username, string $email, string $password): bool {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT OR IGNORE INTO users (username, email, password, role, last_login, last_logout, created_at, updated_at) VALUES (:username, :email, :password, 'user', CURRENT_TIMESTAMP, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
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
    $_SESSION["email"] = $email;
    $_SESSION["role"] = "user";
    $_SESSION["last_login"] = date("Y-m-d H:i:s");
    $_SESSION["last_logout"] = null;

    return true;
}


function do_login(PDO $db, string $email, string $password): bool {
    $statement = $db->prepare("SELECT username, password, role, last_login, last_logout FROM users WHERE email = :email");
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
    $lastLogin = date("Y-m-d H:i:s");
    $update = $db->prepare("UPDATE users SET last_login = :last_login, updated_at = CURRENT_TIMESTAMP WHERE email = :email");
    $update->execute([
        ':last_login' => $lastLogin,
        ':email' => $email,
    ]);
    $_SESSION["username"] = $user["username"];
    $_SESSION["email"] = $email;
    $_SESSION["role"] = $user["role"] ?? "user";
    $_SESSION["last_login"] = $lastLogin;
    $_SESSION["last_logout"] = $user["last_logout"];

    return true;
}

function do_logout(PDO $db, string $username): void {
    $stmt = $db->prepare("UPDATE users SET last_logout = :last_logout WHERE username = :username");
    $stmt->execute([
        ':last_logout' => date("Y-m-d H:i:s"),
        ':username' => $username
    ]);

    $_SESSION = [];
    session_destroy();
}

?>