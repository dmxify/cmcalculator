<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("src/PHPMailer/PHPMailer.php");
require("src/PHPMailer/SMTP.php");
require("src/PHPMailer/Exception.php");

require("config.php");

function send($to, $subject, $body_html, $body_plaintext)
{
    $mail = new PHPMailer(true);

    //Set PHPMailer to use SMTP.
    $mail->isSMTP();
    //Set SMTP host name
    $mail->Host = $mail_smtp_server;
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password
    $mail->Username = $mail_address_info;
    $mail->Password = $mail_password;
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "ssl";
    //Set TCP port to connect to
    $mail->Port = 465;

    $mail->From = $mail_address_info;
    $mail->FromName = "CM Calculator";

    $mail->addAddress($to);//, "Recepient Name");

    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $body_html;
    $mail->AltBody = $body_plaintext;

    try {
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}
