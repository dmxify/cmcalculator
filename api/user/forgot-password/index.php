<?php
header('Content-type:application/json;charset=utf-8');
session_start();

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."


// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../mail.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
require(__DIR__.'/../logout/index.php'); // make sure the user is logged out
require_once(__DIR__.'/../../../lib/random.php');

if (!isset($_SESSION)) {
    session_start();
}

// Exit and return error if email is not passed in request json body
if (!isset($request['forgotPassword_email'])) {
    $data = ['error'=> true, 'errorMsg'=>'Email address missing.'];
    echo json_encode($data);
    exit();
}
if (!isset($request['forgotPassword_captcha_response_token'])) {
    $data = ['error'=> true, 'errorMsg'=>'Captcha not solved.'];
    echo json_encode($data);
    exit();
}

// validate captcha with server:
$is_captcha_verified = false;
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => '6LesXKYZAAAAADT9tJUn104cB-kD-omPtOuln-Cd',
    'response' => $request['forgotPassword_captcha_response_token'],
'remoteip' => $_SERVER['REMOTE_ADDR']
);
$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$verify = @file_get_contents($url, false, $context);
$captcha_success=json_decode($verify);

if ($captcha_success->success==false) {
    $is_captcha_verified = false;
} elseif ($captcha_success->success==true) {
    $is_captcha_verified = true;
}


// Exit and return error if email is not valid
$email = filter_var($request['forgotPassword_email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $data = ['error'=> true, 'errorMsg'=>'Invalid email address.'];
    echo json_encode($data);
    exit();
}

$password_reset_token = bin2hex(random_bytes(50));
$stmt = $mysqli->prepare("UPDATE user SET password_reset_token=?, password_reset_timeout=NOW() + INTERVAL 1 HOUR WHERE email = ?");
$stmt->bind_param("ss", $password_reset_token, $email);
$stmt->execute();

if (isset($stmt->affected_rows)) {
  if ($stmt->affected_rows == 1) {
    // send password reset email to user
    $emailSent = sendPasswordResetEmail($email, $password_reset_token);
    $_SESSION['email'] = $email;
    $_SESSION['type'] = 'alert-success';
    $_SESSION['action'] = 'password-reset-email';
    $msg = "";
    if ($emailSent) {
        $msg = <<<EOS
        <b>Password Reset Email Sent</b>
        <br/>
        <hr/>
        <br/>
        We've sent an email to <b>"$email"</b> with a link to reset your password.
        <br/>
        <br/>
        Please click the link in that email to continue. The link is only valid for 1 hour.
        <br/>
        <br/>
        <hr/>
        <br/>
        Didn't receive an email? Check your spam folder. Otherwise, you can request password reset again in a few minutes or contact an admin in the telegram channel: <a href="https://t.me/cmcalculator" target="_blank">t.me/cmcalculator</a>
EOS;
    } else {
        $msg = <<<EOS
        <b>Password Reset Request Submitted</b>
        <br/>
        <br/>
        However, there was a problem sending you a password reset email.
        <br/>
        Please try again later or contact an admin in the telegram channel: <a href="https://t.me/cmcalculator" target="_blank">t.me/cmcalculator</a>
EOS;
    }
  }




    $stmt->close();

    $_SESSION['message'] = $msg;
    $data = ['submitted'=> true, 'msg'=> $_SESSION['message'], 'emailSent'=>$emailSent];
    echo json_encode($data);
    exit();
} else {
    $_SESSION['error_msg'] = "Database error: Could not create password reset token for user";
}
