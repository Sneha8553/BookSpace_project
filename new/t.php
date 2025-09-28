<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require './controller/mail/mail_app/src/Exception.php';
require './controller/mail/mail_app/src/PHPMailer.php';
require './controller/mail/mail_app/src/SMTP.php';

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';        // Gmail SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'parjapti3@gmail.com';  // Your Gmail
    $mail->Password   = 'pmkynlrnkixpcefb';     // 16-char App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
    $mail->Port       = 587;                     // TLS port

    // Recipients
    $mail->setFrom('parjapti3@gmail.com', 'BookSpace');
    $mail->addAddress('snehaprajapati8553@gmail.com');   // Receiver email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Link';
    $mail->Body    = 'Click here to reset your password: <a href="http://localhost/BookSpace_project/reset.php?token=12345">Reset Password</a>';
    $mail->AltBody = 'Click here to reset your password: http://localhost/BookSpace_project/reset.php?token=12345';

    $mail->send();
    echo '✅ Reset email has been sent!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
