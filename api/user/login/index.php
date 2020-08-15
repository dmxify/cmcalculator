<?php
header('Content-type:application/json;charset=utf-8');

// validate request
$request = require(__DIR__.'/../../common/validate_json_request.php'); // return json request, or json "error":"..."

$email = filter_var($request['email'], FILTER_SANITIZE_EMAIL);
// error_reporting(0);
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli
// require(__DIR__.'/../../common/session.php'); // init session
require(__DIR__.'/../../../track.php');
require(__DIR__.'/../logout/index.php');
session_start();

// Get DB hash of password
$user_id = '';
$user_name = '';
$user_password = '';
$user_theme = '';
$user_is_verified = '';
$user_is_admin = '';
$user_is_premium = '';
$stmt = $mysqli->prepare("SELECT id, name, password_hash, is_email_verified, is_admin, is_premium, theme FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt -> close();
if ($result->num_rows === 1) {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $user_name = $row['name'];
        $user_password = $row['password_hash'];
        $user_is_email_verified = $row['is_email_verified'];
        $user_is_admin = $row['is_admin'];
        $user_is_premium = $row['is_premium'];
        $user_theme = $row['theme'];
    }
}
// Verify password
if (password_verify($request['password'], $user_password)) {
    $arr_trk = track_get_decoded_location();

    if (is_array($arr_trk)) {
        $trk_ip = $arr_trk['ip'];
        $trk_country_name = $arr_trk['country_name'];
        $trk_state_prov = $arr_trk['state_prov'];
        $trk_city = $arr_trk['city'];
        $trk_latitude = $arr_trk['latitude'];
        $trk_longitude = $arr_trk['longitude'];

        $stmt = $mysqli->prepare("INSERT INTO user_login SET user_id=?, login_datetime=NOW(), ip=?, country_name=?, state_prov=?, city=?, latitude=?, longitude=?");
        $stmt->bind_param("issssss", $user_id, $trk_ip, $trk_country_name, $trk_state_prov, $trk_city, $trk_latitude, $trk_longitude);
        $stmt->execute();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO user_login SET user_id=?, login_datetime=NOW()");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    // insert new entry into `user_login` table and save new user_login.id into user.user_login_id

    if (isset($stmt->insert_id) && $stmt->insert_id > 0) {
        $user_login_id = $stmt->insert_id;

        // Update user last_login datetime
        $stmt = $mysqli->prepare('UPDATE user SET user_login_id = ? WHERE id = ?');
        if (
          $stmt &&
          $stmt -> bind_param('ii', $user_login_id, $user_id) &&
          $stmt -> execute() &&
          $stmt -> close()
        ) {
            // user selected
            $_SESSION['user']['id'] = $user_id;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['name'] = $user_name;
            $_SESSION['user']['email_verified'] = $user_is_email_verified;
            $_SESSION['user']['is_admin'] = $user_is_admin;
            $_SESSION['user']['is_premium'] = $user_is_premium;
            $_SESSION['theme'] = $user_theme;

            $data = array();
            $data['verified'] = true;
            echo json_encode($data);
            exit();
        }
    } else {
        // User not verified:
        $data = ['verified'=>false];
        echo json_encode($data);
        exit();
    }
}
$data = array();
$data['verified'] = false;
echo json_encode($data);
exit();
