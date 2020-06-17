<?php
header('Content-type:application/json;charset=utf-8');

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."

// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
// require(__DIR__.'/../../common/session.php'); // init session
require(__DIR__.'/../logout/index.php');
session_start();

// Get DB hash of password
$sql_result_id = '';
$sql_result_name = '';
$sql_result_surname = '';
$sql_result_password = '';
$stmt = $mysqli->prepare("SELECT id, password_hash FROM user WHERE email = ?");
$stmt->bind_param("s", $request['email']);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    while ($row = $result->fetch_assoc()) {
        $sql_result_id = $row['id'];
        $sql_result_password = $row['password_hash'];
    }
}

// Verify password
if (password_verify($request['password'], $sql_result_password)) {
    // Update user last_login datetime
    $stmt = $mysqli->prepare('UPDATE user SET last_login = now() WHERE email = ?');
    if (
      $stmt &&
      $stmt -> bind_param('s', $request['email']) &&
      $stmt -> execute() &&
      $stmt -> close()
  ) {
        // user selected
        $_SESSION['user']['id'] = $sql_result_id;
        $_SESSION['user']['email'] = $request['email'];
    }
} else {
    // User not verified:
    $data = ['verified'=>false];
    echo json_encode($data);
    exit();
}

$data = array();
$data['verified'] = true;
$data['email'] = $request['email'];
echo json_encode($data);
exit();
// $arr_response["verified"] = false;
// echo `{"verified":true,"name":"$sql_result_name","surname":"$sql_result_surname","email":"$email"}`;
// exit();
