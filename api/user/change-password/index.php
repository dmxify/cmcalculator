<?php
header('Content-type:application/json;charset=utf-8');
session_start();

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."


// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../mail.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
require_once(__DIR__.'/../../../lib/random.php');

if (!isset($_SESSION)) {
    session_start();
}

$msg = <<<EOS

There was a problem changing your password.
<br/>
Please <a href="#" onclick="open_modal('modalForgotPassword'); document.getElementById('modalChangePassword').style.display='none';">try again</a> later or contact an admin in the telegram channel: <a href="https://t.me/cmcalculator" target="_blank">t.me/cmcalculator</a>
EOS;

// Exit and return error if token is not provided
if (!isset($_SESSION['password_reset_token'])) {
    $data = ['error'=> true, 'errorMsg'=>$msg];
    echo json_encode($data);
    exit();
}

// Exit and return error if email is not passed in request json body
if (!isset($request['changePassword_password'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password missing.'];
    echo json_encode($data);
    exit();
}
// Exit and return error if register_confirm_password is not passed in request json body
if (!isset($request['changePassword_confirm_password'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password confirmation is missing.'];
    echo json_encode($data);
    exit();
}
// Exit and return error if changePassword_captcha_response_token is not passed in request json body
if (!isset($request['changePassword_captcha_response_token'])) {
    $data = ['error'=> true, 'errorMsg'=>'Captcha not solved.'];
    echo json_encode($data);
    exit();
}

// validate captcha with server:
$is_captcha_verified = false;
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => '6LesXKYZAAAAADT9tJUn104cB-kD-omPtOuln-Cd',
    'response' => $request['changePassword_captcha_response_token'],
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

if (/*!$uppercase || !$lowercase || !$number || !$specialChars || */strlen($request['changePassword_password']) < 8) {
    $data = ['error'=> true, 'errorMsg'=>'Password must be at least 8 characters in length'];
    echo json_encode($data);
    exit();
}

// Exit and return error if password is not valid
// Validate password strength
// $uppercase = preg_match('@[A-Z]@', $request['password']);
// $lowercase = preg_match('@[a-z]@', $request['password']);
// $number    = preg_match('@[0-9]@', $request['password']);
// $specialChars = preg_match('@[^\w]@', $request['password']);

if (/*!$uppercase || !$lowercase || !$number || !$specialChars || */strlen($request['changePassword_confirm_password']) < 8) {
    $data = ['error'=> true, 'errorMsg'=>'Password must be at least 8 characters in length'];
    echo json_encode($data);
    exit();
}


if ($request['changePassword_password'] != $request['changePassword_confirm_password']) {
    $data = ['error'=> true, 'errorMsg'=>'Passwords do not match.'];
    echo json_encode($data);
    exit();
}

$changed = false;
$error = true;
$_SESSION['message'] = $msg;
$_SESSION['message-type'] = 'failure-message';
$_SESSION['action'] = 'forgot-password';

$password_hash = password_hash($request['changePassword_password'], PASSWORD_BCRYPT);
$stmt = $mysqli->prepare("UPDATE user SET password_hash = ?, password_reset_token=NULL, password_reset_timeout=NULL WHERE password_reset_token = ? AND password_reset_timeout > NOW()");
$stmt->bind_param("ss", $password_hash, $_SESSION['password_reset_token']);
$stmt->execute();
// echo $password_hash;
// echo '==========';
// echo $password_reset_token;
if (isset($stmt->affected_rows)) {
    if ($stmt->affected_rows == 1) {
        $changed = true;
        $error = false;
        $msg = <<<EOS
        <br/>
        <b>Your password has been changed!</b>
        <br/>
        <br/>
        You can now log in with your new password...
        <br/>
        <br/>
EOS;

        $_SESSION['message-type'] = 'success-message';
        $_SESSION['action'] = 'login';
        $_SESSION['message'] = $msg;
    }
    $stmt->close();
} else {
    $_SESSION['error_msg'] = "Database error: Could not change password for user!";
}
$data = ['error'=> $error, 'errorMsg'=>$msg, 'changed'=> $changed, 'msg'=> $msg];
echo json_encode($data);
exit();
