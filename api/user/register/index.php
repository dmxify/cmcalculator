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

if (!isset($request['register_name'])) {
    $data = ['error'=> true, 'errorMsg'=>'Name is missing.'];
    echo json_encode($data);
    exit();
}
// Exit and return error if email is not passed in request json body
if (!isset($request['register_email'])) {
    $data = ['error'=> true, 'errorMsg'=>'Email address missing.'];
    echo json_encode($data);
    exit();
}
if (!isset($request['register_password'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password missing.'];
    echo json_encode($data);
    exit();
}
if (!isset($request['register_confirm_password'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password confirmation is missing.'];
    echo json_encode($data);
    exit();
}
if (!isset($request['captcha_response_token'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password confirmation is missing.'];
    echo json_encode($data);
    exit();
}

if ($request['register_password'] != $request['register_confirm_password']) {
    $data = ['error'=> true, 'errorMsg'=>'Passwords do not match.'];
    echo json_encode($data);
    exit();
}

// validate captcha with server:
$is_captcha_verified = false;
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
    'secret' => '6LesXKYZAAAAADT9tJUn104cB-kD-omPtOuln-Cd',
    'response' => $request['captcha_response_token'],
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


$name = filter_var($request['register_name'], FILTER_SANITIZE_STRING);

// Exit and return error if email is not valid
$email = filter_var($request['register_email'], FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $data = ['error'=> true, 'errorMsg'=>'Invalid email address.'];
    echo json_encode($data);
    exit();
}

// Exit and return error if password is not valid
// Validate password strength
// $uppercase = preg_match('@[A-Z]@', $request['password']);
// $lowercase = preg_match('@[a-z]@', $request['password']);
// $number    = preg_match('@[0-9]@', $request['password']);
// $specialChars = preg_match('@[^\w]@', $request['password']);

if (/*!$uppercase || !$lowercase || !$number || !$specialChars || */strlen($request['register_password']) < 8) {
    $data = ['error'=> true, 'errorMsg'=>'Password should be at least 8 characters in length'];
    echo json_encode($data);
    exit();
}

// Exit and return error if email address is already taken
$stmt = $mysqli->prepare("SELECT id FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    $data = ['error'=> true, 'errorMsg'=>'Email address already in use.'];
    echo json_encode($data);
    exit();
}

// Generate token
$verification_token = bin2hex(random_bytes(50));

// Hash password for DB insert
$password_hash = password_hash($request['register_password'], PASSWORD_BCRYPT);
$stmt = $mysqli->prepare("INSERT INTO user SET name=?, email=?, password_hash=?, verification_token=?");
$stmt->bind_param("ssss", $name, $email, $password_hash, $verification_token);
$stmt->execute();

if (isset($stmt->insert_id)) {
    $user_id = $stmt->insert_id;
    $stmt->close();

    // send verification email to user
    $emailSent = sendVerificationEmail($email, $verification_token);
    $_SESSION['id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['verified'] = false;
    $_SESSION['type'] = 'alert-success';
    $_SESSION['action'] = 'verify-email';
    $msg = "";
    if ($emailSent) {
        $msg = <<<EOS
        <b>Verify Your Email Address</b>
        <br/>
        <hr/>
        <br/>
        We now need to verify your email address. We've sent an email to <b>"$email"</b> to verify your address.
        <br/>
        <br/>
        Please click the link in that email to continue.
        <br/>
        <br/>
        <hr/>
        <br/>
        Didn't receive an email? Check your spam folder. Otherwise, you can request email verification in your profile settings at any point in time.
EOS;
    } else {
        $msg = <<<EOS
        <b>Registration Complete</b>
        <br/>
        <br/>
        However, there was a problem sending you a verification email.
        <br/>
        You can request email verification in your profile settings at any point in time.
EOS;
    }
    $_SESSION['message'] = $msg;
    $data = ['registered'=> true, 'msg'=> $_SESSION['message'], 'emailSent'=>$emailSent];
    echo json_encode($data);
    exit();
} else {
    $_SESSION['error_msg'] = "Database error: Could not register user";
}
