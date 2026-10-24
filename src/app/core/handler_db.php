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

    if ($stmt->rowCount() > 0) {
        return True;
    } else {
        return False;
    }
}


function do_login(PDO $db, string $email, string $password): string {
    $statement = $db->prepare("SELECT email, password FROM users WHERE email = :email");
    $statement->execute([
        ':email' => $email
    ]);
    
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION["username"] = $user["username"];
            return True;
        } else {
            return False;
        }
    } else {
        return False;
    }
}
?>
