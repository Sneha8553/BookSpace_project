<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("SELECT username, email, first_name, last_name FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $currentUser = $stmt->fetch();
    } catch (PDOException $e) {
        header("Location: my_profile.php?error=dberror");
        exit();
    }
        $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];

    if ($currentUser['username'] !== $newUsername || 
        $currentUser['email'] !== $newEmail || 
        $currentUser['first_name'] !== $newFirstName || 
        $currentUser['last_name'] !== $newLastName) {
        
        try {
            $sql = "UPDATE users SET username = ?, email = ?, first_name = ?, last_name = ? WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$newUsername, $newEmail, $newFirstName, $newLastName, $user_id]);

            $_SESSION['username'] = $newUsername;
            
            header("Location: my_profile.php?status=success");
            exit();

        } catch (PDOException $e) {
            header("Location: my_profile.php?error=dberror");
            exit();
        }

    } else {
        header("Location: my_profile.php?status=nochange");
        exit();
    }

} else {
    header("Location: login.html");
    exit();
}
?>