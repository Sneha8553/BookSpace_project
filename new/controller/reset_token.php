<?php
// request_reset.php
require './db/database.php'; // PHPMailer via Composer
// require '../../vendor/autoload.php'; // Include Composer autoloader for PHPMailer
// Database connection (PDO)

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Include PHPMailer
require './mail/mail_app/src/Exception.php';
require './mail/mail_app/src/PHPMailer.php';
require './mail/mail_app/src/SMTP.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $role = $_POST['role'];
        if (!$email) {
            // echo "Invalid email";
            $_SESSION['mailstatus'] = "noexists";
            header("Location: ../forgotPassword.php?mailstatus=noexistsmm");
            exit;
        }

        // Check user exists
        $stmt = $pdo->prepare("SELECT user_id,username FROM users WHERE email = ? and role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // For privacy, still respond with success message (don’t reveal existence)
            //echo "If an account with that email exists, a reset link was sent.".$role."v".$email;
            //$_SESSION['mailstatus'] = "noexists";   
            header("Location: ../forgotPassword.php?mailstatus=noexists");


            exit;
        }

        // Create secure token
        $token = bin2hex(random_bytes(24)); // 48 hex chars
        $expiresAt = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

        // Save token
        $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user['user_id'], $token, $expiresAt]);
    } catch (PDOException $e) {
        header("Location: ../forgotPassword.php?error=dberror" + $e->getMessage());
        exit();
    }
    // Build reset link (adjust domain)
    $resetLink = "http://192.168.1.105/BookSpace_project/new/reset.php?token=" . urlencode($token);

    $mails = new PHPMailer(true);

    try {
        // Server settings
        $mails->isSMTP();
        $mails->Host = 'smtp.gmail.com';        // Gmail SMTP
        $mails->SMTPAuth = true;
        $mails->Username = 'snehaprajapati8553@gmail.com';  // Your Gmail
        $mails->Password = 'zebgodyabdstxbaw';     // 16-char App Password
        $mails->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
        $mails->Port = 587;                     // TLS port

        // Recipients
        $mails->setFrom('snehaprajapati8553@gmail.com', 'BookSpace');
        $mails->addAddress($email);  // Receiver email

        // Content
        $mails->isHTML(true);
        $mails->Subject = 'Password Reset Link';
        $mails->Body = '<p>Hi ' . $user['username'] . ',</p>
            <p>We received a request to reset your password. Click the link below to reset it:</p>
            <p><a href="' . $resetLink . '">Reset my password</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you didn\'t request a reset, ignore this email.</p>';
        $mails->AltBody = "Reset your password: {$resetLink}";

        $mails->send();
        //echo '✅ Reset email has been sent!';
        $_SESSION['mailstatus'] = "send";
        header("Location: ../forgotPassword.php?mailstatus=send");
    } catch (Exception $e) {
        // echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("Location: ../forgotPassword.php?mailstatus = {$mail->ErrorInfo}");
    }

}
?>
<!-- HTML minimal form -->
<!-- <form method="post">
  <input type="email" name="email" placeholder="Enter your email" required>
  <button type="submit">Send reset link</button>
</form> -->