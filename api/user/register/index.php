<?php
header('Content-type:application/json;charset=utf-8');
session_start();

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."


// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
require(__DIR__.'/../logout/index.php');
if (!isset($_SESSION)) {
    session_start();
}

require('mail.php');

// Exit and return error if email is not passed in request json body
if (!isset($request['email'])) {
    $data = ['error'=> true, 'errorMsg'=>'Email address not set.'];
    echo json_encode($data);
    exit();
}
$email = $request['email'];
// TODO: VALIDATE EMAIL ADDRESS

// Exit and return error if password is not passed in request json body
if (!isset($request['password'])) {
    $data = ['error'=> true, 'errorMsg'=>'Password not set.'];
    echo json_encode($data);
    exit();
}
// TODO: VALIDATE PASSWORD

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
$password_hash = password_hash($request['password']);

$stmt = $mysqli->prepare("INSERT INTO users SET email=?, password_hash=?, verification_token=?");
$stmt->bind_param("sss", $email, $password_hash, $verification_token);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result) {
    $user_id = $stmt->insert_id;
    $stmt->close();

    // TO DO: send verification email to user
    // sendVerificationEmail($email, $token);

    $_SESSION['id'] = $user_id;
    $_SESSION['email'] = $email;
    $_SESSION['verified'] = false;
    $_SESSION['message'] = 'You are logged in!';
    $_SESSION['type'] = 'alert-success';
    header('location: index.php');
} else {
    $_SESSION['error_msg'] = "Database error: Could not register user";
}
