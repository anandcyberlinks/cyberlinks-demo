<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function send_email() {
    require_once("phpmailer/class.phpmailer.php");

    $mail = new PHPMailer();
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'arshad.cyberlinks@gmail.com';      // SMTP username
    $mail->Password = '31513119';                         // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

    $mail->From = 'arshad.faiyaz@cyberlinks.co.in';
    $mail->FromName = 'Arshad Faiyaz';
    $mail->addAddress('shekher.cyberlinks@gmail.com', 'Shekher CYberlinks');    // Add a recipient
    //$mail->addAddress('anand.vyas@cyberlinks.in', 'Anand Sir');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');                       // Reply To.........
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');

    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    //$mail->addAttachment('index.php');                  // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');  // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = 'PHP Mailer Testing';
    $mail->Body = 'This is the Succefull php Mailer Test <b>By Arshad</b>';
    $mail->AltBody = 'Success';

    if (!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
}
