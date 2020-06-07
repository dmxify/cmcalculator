<?php

require("../config.php");
require("../db.php"); // creates database connection $mysqli

$stmt = $mysqli->prepare("INSERT INTO user (username, password) VALUES (?, ?)");

if (
	$stmt &&
	$stmt -> bind_param('ss', $username, $password) &&
	$stmt -> execute() &&
  $stmt -> close()
) {
 	// new user added
}


// INSERT INTO `transaction` (`id`, `ledger_id`, `type`) VALUES (NULL, '', '')

// INSERT INTO `contract` (`id`, `transaction_id`, `value_btc`, `interest_rate`, `start_datetime`, `end_datetime`) VALUES (NULL, '', '', '', '', ''), (NULL, '', '', '', '2020-05-08 00:00:00', '2020-05-09 01:06:09')

?>
