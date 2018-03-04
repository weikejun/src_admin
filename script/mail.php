<?php
require(ROOT_PATH."/lib/PHPMailer/PHPMailerAutoload.php");

$mail = new PHPMailer();

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
#$mail->Port = 465;  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->CharSet = 'UTF-8';                               // Enable SMTP authentication
$mail->Username = 'wwwppp0801@qq.com';                 // SMTP username
$mail->Password = 'xxx';                           // SMTP password
#$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'wwwppp0801@qq.com';
$mail->FromName = '王芃';
#$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
$mail->addAddress('wwwppp0801@qq.com','wang peng');               // Name is optional
#$mail->addReplyTo('info@example.com', 'Information');
#$mail->addCC('cc@example.com');
#$mail->addBCC('bcc@example.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject中文';
$mail->Body    = 'This is the HTML message body <b>in bold!中文</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

