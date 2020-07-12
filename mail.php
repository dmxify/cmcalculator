<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require("lib/PHPMailer/PHPMailer.php");
require("lib/PHPMailer/SMTP.php");
require("lib/PHPMailer/Exception.php");

include("config.php");

function send($to, $subject, $body_html, $body_plaintext)
{
    global $mail_smtp_server;
    global $mail_address_info;
    global $mail_address_info_name;
    global $mail_password;

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
    $mail->FromName = $mail_address_info_name;

    $mail->addAddress($to);//, "Recepient Name");

    $mail->isHTML(true);
    $mail->CharSet = "text/html; charset=UTF-8;";

    $mail->Subject = $subject;
    $mail->Body = $body_html;
    $mail->AltBody = $body_plaintext;

    try {
        $mail->send();
        return true;
        // return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

function sendVerificationEmail($email, $token)
{
    $body_html = <<<EOS
    <p></b>You're receiving this email because you (or somebody else) registered a user account using your email address on cmcalculator.com</b></p>
    <p>If this wasn't you, please ignore this email - The account will soon be disabled.</p>
    <br />
    <p><b>If this was you, <a href="https://cmcalculator.com/verify.php?token=$token" target="_blank" rel="noreferrer">click here</a> to verify your account.</b></p>
    <br />
    <p>Alternatively, paste the following link in your browser: </p>
    https://cmcalculator.com/verify.php?token=$token
    <br />
    <br />
    <p>Have a nice day!<br />- The CM Calculator team.</p>
    <br />
    <p>P.S. We won't send you any more emails unless you turn on email alerts in your profile!</p>
EOS;

    $body_plaintext = <<<EOS
    You're receiving this email because you (or somebody else) registered a user account using your email address on cmcalculator.com
    If this wasn't you, please ignore this email. The account will soon be disabled.
    \r\n
    If this was you, please paste the following link in your web browser to verify your account
    https://cmcalculator.com/verify.php?token=$token
    \r\n
    We won't send you any more emails unless you turn on email alerts in your profile.
    \r\n
    \r\n
    Thanks!
    \r\n
    -The CM Calculator team.
EOS;

    return send($email, 'Confirm Your Email Address', $body_html, $body_plaintext);
}


function sendPasswordResetEmail($email, $token)
{
  $body_html = <<<EOS
  <p></b>You're receiving this email because you (or somebody else) requested to reset your cmcalculator.com account password </b></p>
  <p>If this wasn't you, please ignore this email - The reset token will expire within an hour.</p>
  <br />
  <p><b>If this was you, <a href="https://cmcalculator.com/api/user/forgot-password-verify?token=$token" target="_blank" rel="noreferrer">click here</a> to reset your password.</b></p>
  <br />
  <p>Alternatively, paste the following link in your browser: </p>
  https://cmcalculator.com/api/user/forgot-password-verify?token=$token
  <br />
  <br />
  <p>Have a nice day!<br />- The CM Calculator team.</p>
  <br />
  <p>P.S. We won't send you any more emails unless you turn on email alerts in your profile!</p>
EOS;

  $body_plaintext = <<<EOS
  You're receiving this email because you (or somebody else) requested to reset your cmcalculator.com account password.
  \r\n
  If this wasn't you, please ignore this email. The reset token will expire within an hour.
  \r\n
  If this was you, please paste the following link in your web browser to proceed with password reset
  \r\n
  https://cmcalculator.com/api/user/forgot-password-verify?token=$token
  \r\n
  \r\n
  We won't send you any more emails unless you turn on email alerts in your profile.
  \r\n
  \r\n
  Thanks!
  \r\n
  -The CM Calculator team.
EOS;
  return send($email, 'Password Reset Request', $body_html, $body_plaintext);
}
