<?php
require_once 'database.php';

define('DEBUG_MODE', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $role = $_POST['role']; // ADDED: Get role from the form

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    $redirectParams = '';

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        try {
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expires]);

            $resetLink = "http://localhost/BookSpace_project/reset_password.php?token=" . $token;

            if (DEBUG_MODE) {
                $redirectParams = "?debug_link=" . urlencode($resetLink);
            } else {
                $subject = "Password Reset Request for bookSpace";
                $message = "Click the following link to reset your password: " . $resetLink;
                $headers = "From: no-reply@bookspace.com";
                mail($email, $subject, $message, $headers);
            }
        } catch (PDOException $e) {
        }
    }
    header("Location: forgot_password_confirmation.html" . $redirectParams);
    exit();
}
?>