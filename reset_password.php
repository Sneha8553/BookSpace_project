<?php
require_once 'database.php';
$token = $_GET['token'] ?? null;
$error = null;
$userEmail = null;

// --- Part 1: Verify the token when the page loads ---
if ($token) {
    // Find a token that is not expired
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        $error = "This password reset link is invalid or has expired.";
    } else {
        $userEmail = $resetRequest['email'];
    }
} else {
    $error = "No reset token provided.";
}

// --- Part 2: Process the form when a new password is submitted ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postToken = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Re-verify the token from the form
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$postToken]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        $error = "This password reset link is invalid or has expired.";
    } elseif ($password !== $confirmPassword) {
        $error = "The passwords do not match.";
    } else {
        $userEmail = $resetRequest['email'];
        $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);

        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
        $updateStmt->execute([$newPasswordHash, $userEmail]);

        $deleteStmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $deleteStmt->execute([$postToken]);

        header("Location: login.html?status=passwordupdated");
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="form-container">
        <h1>Choose a New Password</h1>
        
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php else: ?>
            <form action="reset_password.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="input-group">
                    <input type="password" name="password" placeholder="Enter new password" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm-password" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>