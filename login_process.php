<?php
session_start();
require_once 'database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    try {
        $sql = "SELECT user_id,username email, password_hash, role FROM users WHERE email = ? and role = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email,$role]);
        
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
           
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username']; 
            $_SESSION['role'] = $user['role'];
            if ($user && password_verify($password, $user['password_hash'])) {

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username']; 
    $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'reader') {
        header("Location: reader_dashboard.php");
    } elseif ($user['role'] === 'author') {
        header("Location: author_dashboard.php");
    }  else {
        header("Location: index.html?error=invalidrole");
    }
    exit();
}

        } else {
            header("Location: index.html?error=invalidcredentials");
            exit();
        }

    } catch (PDOException $e) {
        header("Location: index.html?error=dberror");
        exit();
    }
} else {
    header("Location: index.html");
    exit();
}
?>