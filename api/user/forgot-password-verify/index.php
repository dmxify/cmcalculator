<?php
require("../logout/index.php");
session_start();
require(__DIR__.'/../../../config.php');
require(__DIR__.'/../../../db.php'); // creates database connection $mysqli

$token_valid = false;

// defaults
$_SESSION['message'] = "The password reset link you used is no longer valid... provide your email address again to receive a valid reset link.";
$_SESSION['message-type'] = 'error-message';
$_SESSION['action'] = 'forgot-password';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // VALIDATE TOKEN AND GET USER
    $stmt = $mysqli->prepare("SELECT id FROM user WHERE password_reset_token = ? AND password_reset_timeout > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt -> close();
    if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['id'] = $row['id'];
            $token_valid = true;
        }
    }

    if ($token_valid) {
        // user verified
        $_SESSION['password_reset_token'] = $token;
        $_SESSION['message'] = "Please provide a new password for your account below...";
        $_SESSION['message-type'] = 'success-message';
        $_SESSION['action'] = 'change-password';
    }
}

// navigate back to site index
header('Location: ../../../');
exit;
