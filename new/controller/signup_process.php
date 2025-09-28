<?php
require_once './db/database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $role = $_POST['role']; 

    if ($password !== $confirmPassword) {
        // header("Location: signup.html?error=passwordsdontmatch");
        echo "<script>alert('Passwords do not match.'); window.location.href='../signup.html';</script>";
        exit();
    }

    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$email]);
    if ($stmt_check->fetch()) {
       // header("Location: signup.html?error=emailtaken");
       echo "<script>alert('Email is already registered.'); window.location.href='../signup.html';</script>";
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $passwordHash, $role]);

    session_start();
    $_SESSION['user_id'] = $pdo->lastInsertId(); 
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    if ($role === 'reader') {
        // header("Location: ../view/reader_dashboard.php");
        header("Location: ../index.php?status=success");
    } elseif ($role === 'author') {
        header("Location: ../index.php?status=success");
        // header("Location: ../view/author_dashboard.php");
    }  else {
        header("Location: ../index.php?error=invalidrole");
    }
   
    exit();

} catch (PDOException $e) {
    header("Location: ../signup.html?error=dberror" . $e->getMessage());
    exit();
}


} else {
    header("Location: ../signup.html");
    exit();
}
?>
