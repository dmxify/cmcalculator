<?php
require("api/user/logout/index.php");
session_start();
include("config.php");
include("db.php");

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // GET EMAIL ADDRESS FOR "AUTOCOMPLETE"
    $stmt = $mysqli->prepare("SELECT email FROM user WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt -> close();
    if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
            $_SESSION['email'] = $row['email'];
        }
    }

    // VERIFY
    $stmt = $mysqli->prepare('UPDATE user SET is_email_verified = 1 WHERE verification_token = ?');
    if (
      $stmt &&
      $stmt -> bind_param('s', $token) &&
      $stmt -> execute() &&
      $stmt -> close()
  ) {
        // user verified
        $_SESSION['message'] = "Your email address has been verified successfully! Please login to continue.";
        $_SESSION['message-type'] = 'success-message';
        $_SESSION['action'] = 'login';
        header('location: index.php');
        exit;
    }
} else {
  $_SESSION['message'] = "Email verification failed due to invalid verification token.";
  $_SESSION['message-type'] = 'error-message';
  $_SESSION['action'] = 'login';
  header('location: index.php');
  exit;
}
