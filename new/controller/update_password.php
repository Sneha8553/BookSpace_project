<?php
session_start();
require_once '../controller/db/database.php';

// Security: Check if user is logged in and form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    
    $user_id = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // --- 1. Fetch the user's current hashed password from the database ---
    try {
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } catch (PDOException $e) {
        header("Location: ../view/my_profile.php?error=dberror");
        exit();
    }
    
    // --- 2. Verify the "Current Password" they typed is correct ---
    if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
        // If the password is wrong, redirect with an error
        header("Location: ../view/my_profile.php?error=wrongpwd");
        exit();
    }

    // --- 3. Check if the "New Password" and "Confirm New Password" fields match ---
    if ($newPassword !== $confirmNewPassword) {
        // If they don't match, redirect with an error
        header("Location: ../view/my_profile.php?error=pwdnomatch");
        exit();
    }

    // --- 4. All checks passed! Hash the new password and update the database ---
    try {
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newPasswordHash, $user_id]);

        // Redirect back with a password success message
        header("Location: ../view/my_profile.php?status=pwdsuccess");
        exit();

    } catch (PDOException $e) {
        header("Location: ../view/my_profile.php?error=dberror");
        exit();
    }

} else {
    // If not a POST request or not logged in, redirect away
    header("Location: ../index.php");
    exit();
}
?>