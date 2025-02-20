<?php
include "functions.php";
sendMail("ahansal2005@gmail.com","test code php", "hh wa sla3");
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

// $mail = new PHPMailer(true);

// try {
//     // إعدادات SMTP
//     $mail->isSMTP();
//     $mail->Host       = 'smtp.gmail.com'; // SMTP server
//     $mail->SMTPAuth   = true;
//     $mail->Username   = 'anohamed05@gmail.com'; // بريدك
//     $mail->Password   = 'zpwc ipcx mgpl ijye'; // استخدم كلمة مرور التطبيقات App Password
//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//     $mail->Port       = 587;

//     // إعدادات البريد
//     $mail->setFrom('anohamed05@gmail.com', 'hamza');
//     $mail->addAddress('walghlid@gmail.com'); // البريد المُستقبِل
//     $mail->Subject = 'Test Email from Localhost';
//     $mail->Body    = 'This is a test email sent from localhost using PHPMailer.';

//     $mail->send();
//     echo 'Email sent successfully!';
// } catch (Exception $e) {
//     echo "Email could not be sent. Error: {$mail->ErrorInfo}";
// }


?>