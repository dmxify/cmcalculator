<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("lib/PHPMailer/PHPMailer.php");
require("lib/PHPMailer/SMTP.php");
require("lib/PHPMailer/Exception.php");

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
    $mail->CharSet = "text/html; charset=UTF-8;";

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

function sendVerificationEmail($email, $token)
{
    $body_html = `<p>You're receiving this email because you (or somebody else) registered a user account using your email address on cmcalculator.com</p>`;
    $body_html .= `<p>If this wasn't you, please ignore this email. The account will soon be disabled.</p>`;
    $body_html .= `<br />`;
    $body_html .= `<p>If this was you, <a href="https://cmcalculator.com/verify?token=$token" target="_blank">click here</a> to verify your account.</p>`;
    $body_html .= `<p>Alternatively, paste the following link in your browser: </p>`;
    $body_html .= `https://cmcalculator.com/verify?token=$token`;
    $body_html .= `<br />`;
    $body_html .= `<p>We won't send you any more emails unless you turn on email alerts in your profile.</p>`;
    $body_html .= `<br />`;
    $body_html .= `<br />`;
    $body_html .= `<br />`;
    $body_html .= `<p>Thanks!<br />The CM Calculator team.</p>`;


    $body_plaintext = `You're receiving this email because you (or somebody else) registered a user account using your email address on cmcalculator.com`;
    $body_plaintext .= `If this wasn't you, please ignore this email. The account will soon be disabled.`;
    $body_plaintext .= `If this was you, please paste the following link in your web browser to verify your account`;
    $body_plaintext .= `https://cmcalculator.com/verify?token=$token`;
    $body_plaintext .= `We won't send you any more emails unless you turn on email alerts in your profile.`;

    return send($email, 'Confirm Your Email Address', $body_html, $body_plaintext);
}
