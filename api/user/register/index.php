<?php
header('Content-type:application/json;charset=utf-8');
session_start();

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."


// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../mail.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
require(__DIR__.'/../logout/index.php');
require_once(__DIR__.'/../../../lib/random.php');

if (!isset($_SESSION)) {
    session_start();
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
    $verify = file_get_contents($url, false, $context);
    $captcha_success=json_decode($verify);

    if ($captcha_success->success==false) {
        $is_captcha_verified = false;
    } elseif ($captcha_success->success==true) {
        $is_captcha_verified = true;
    }

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
echo $email;
echo '<br />';
echo $password_hash;
echo '<br />';
echo $verification_token;
$stmt = $mysqli->prepare("INSERT INTO users SET email=?, password_hash=?, verification_token=?");
$stmt->bind_param("sss", $email, $password_hash, $verification_token);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result) {
    $user_id = $stmt->insert_id;
    $stmt->close();

    // send verification email to user
    sendVerificationEmail($email, $verification_token);

    $_SESSION['id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['verified'] = false;
    $_SESSION['message'] = `<b>Verify Your Email Address</b><br/><br/>We now need to verify your email address. We've sent an email to "$email" to verify your address. Please click the link in that email to continue.<br /><hr /><br />Didn't receive an email?<button>Resend Verification Email</button>`;
    $_SESSION['type'] = 'alert-success';

    header('location: index.php');
} else {
    $_SESSION['error_msg'] = "Database error: Could not register user";
}
