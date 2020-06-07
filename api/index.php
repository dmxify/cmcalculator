<?php
header('Content-type:application/json;charset=utf-8');

// validate request
$request = require(__DIR__.'/common/validate_json_request.php'); // return json request, or json "error":"..."



// error_reporting(0);
require(__DIR__.'/../config.php');
require(__DIR__.'/../db.php'); // creates database connection $mysqli






echo json_encode($data);

exit();
