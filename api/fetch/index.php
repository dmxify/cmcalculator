<?php
header('Content-type:application/json;charset=utf-8');

// validate request
$request = require(__DIR__.'/../common/validate_json_request.php'); // return json request, or json "error":"..."

$data = ['data'=>array(1,2,3)];
echo json_encode($data);
exit();
