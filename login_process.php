<?php
session_start();

require_once 'database.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve Data from Form ---
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // You can use this to add extra checks if you want

    // --- Find User in Database ---
    try {
        $sql = "SELECT user_id,username email, password_hash, role FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        
        $user = $stmt->fetch();

        // --- Verify User and Password ---
        // Check if a user was found AND if the submitted password matches the stored hash
        if ($user && password_verify($password, $user['password_hash'])) {
            // Login Successful!

            // --- Store User Info in Session ---
            // This "remembers" the user is logged in
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username']; // Assuming you add username to your SELECT
            $_SESSION['role'] = $user['role'];

            // --- Redirect to a protected dashboard page ---
            if ($user && password_verify($password, $user['password_hash'])) {
    // Login Successful!

    // --- Store User Info in Session ---
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username']; // make sure you SELECT username in SQL
    $_SESSION['role'] = $user['role'];

    // --- Redirect based on role ---
    if ($user['role'] === 'reader') {
        header("Location: reader.html");
    } elseif ($user['role'] === 'author') {
        header("Location: author_dashboard.php");
    }  else {
        header("Location: login.html?error=invalidrole");
    }
    exit();
}

        } else {
            // Invalid credentials
            header("Location: login.html?error=invalidcredentials");
            exit();
        }

    } catch (PDOException $e) {
        // Database error
        header("Location: login.html?error=dberror");
        exit();
    }
} else {
    // Redirect if accessed directly
    header("Location: login.html");
    exit();
}
?>