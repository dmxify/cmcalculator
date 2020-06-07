<?php

require("../config.php");
require("../db.php"); // creates database connection $mysqli

$stmt = $mysqli->prepare("INSERT INTO transaction (ip, country_name, state_prov, city, latitude, longitude, country_flag, timezone_offset, time_current, time_current_unix) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssiss", $decodedLocation['ip'], $decodedLocation['country_name'], $decodedLocation['state_prov'], $decodedLocation['city'], $decodedLocation['latitude'], $decodedLocation['longitude'], $decodedLocation['country_flag'], $decodedLocation['time_zone']['offset'], $decodedLocation['time_zone']['current_time'], $decodedLocation['time_zone']['current_time_unix']);
$stmt->execute();
$stmt->close();

// INSERT INTO `transaction` (`id`, `ledger_id`, `type`) VALUES (NULL, '', '')

// INSERT INTO `contract` (`id`, `transaction_id`, `value_btc`, `interest_rate`, `start_datetime`, `end_datetime`) VALUES (NULL, '', '', '', '', ''), (NULL, '', '', '', '2020-05-08 00:00:00', '2020-05-09 01:06:09')

?>
