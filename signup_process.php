<?php
// Include your database connection file
require_once 'database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL); // Make sure you have this file from our previous discussions

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Retrieve Data from Form ---
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $role = $_POST['role']; // This comes from your hidden input!

    // --- Server-Side Validation ---
    if ($password !== $confirmPassword) {
        // Passwords do not match, redirect back with an error
        header("Location: signup.html?error=passwordsdontmatch");
        exit();
    }

    // Check if email already exists
    $sql_check = "SELECT user_id FROM users WHERE email = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$email]);
    if ($stmt_check->fetch()) {
        // Email already exists, redirect back with an error
        header("Location: signup.html?error=emailtaken");
        exit();
    }

    // --- Hash the Password (VERY IMPORTANT for security) ---
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // --- Insert User into Database ---
  // --- Insert User into Database ---
try {
    $sql = "INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email, $passwordHash, $role]);

    // ✅ Start session & auto-login
    session_start();
    $_SESSION['user_id'] = $pdo->lastInsertId(); // get new user ID
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;

    // ✅ Redirect to role-based dashboard
    if ($role === 'reader') {
        header("Location: reader.html");
    } elseif ($role === 'author') {
        header("Location: author_dashboard.php");
    }  else {
        header("Location: login.html?error=invalidrole");
    }
    exit();

} catch (PDOException $e) {
    header("Location: signup.html?error=dberror");
    exit();
}


} else {
    // If the page was accessed directly without POST data, redirect to the signup page
    header("Location: signup.html");
    exit();
}
?>
