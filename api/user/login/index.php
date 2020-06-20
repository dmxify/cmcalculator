<?php
header('Content-type:application/json;charset=utf-8');

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."

$email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
// require(__DIR__.'/../../common/session.php'); // init session
require(__DIR__.'/../logout/index.php');
session_start();

// Get DB hash of password
$sql_result_id = '';
$sql_result_name = '';
$sql_result_password = '';
$sql_result_theme = '';
$stmt = $mysqli->prepare("SELECT id, name, password_hash, theme FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    while ($row = $result->fetch_assoc()) {
        $sql_result_id = $row['id'];
        $sql_result_name = $row['name'];
        $sql_result_password = $row['password_hash'];
        $sql_result_theme = $row['theme'];
    }
}

// Verify password
if (password_verify($request['password'], $sql_result_password)) {
    // Update user last_login datetime
    $stmt = $mysqli->prepare('UPDATE user SET last_login = now() WHERE email = ?');
    if (
      $stmt &&
      $stmt -> bind_param('s', $email) &&
      $stmt -> execute() &&
      $stmt -> close()
  ) {
        // user selected
        $_SESSION['user']['id'] = $sql_result_id;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['name'] = $sql_result_name;
        $_SESSION['theme'] = $sql_result_theme;
    }
} else {
    // User not verified:
    $data = ['verified'=>false];
    echo json_encode($data);
    exit();
}

$data = array();
$data['verified'] = true;
echo json_encode($data);
exit();
