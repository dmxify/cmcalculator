<?php
header('Content-type:application/json;charset=utf-8');
$request = require(__DIR__.'/common/validate_json_request.php'); // return json request, or json "error":"..."
require(__DIR__.'/resources.php');

require(__DIR__.'/../config.php');
require(__DIR__.'/../db.php'); // creates database connection $mysqli

if (!isset($_SESSION)) {
    session_start();
}

// validate request
if (!isset($request['resource'])) {
    $data = ['error'=> true, 'errorMsg'=>'No resource requested.'];
    echo json_encode($data);
    exit();
}

// validate permissions
if (!user_can_access($request['resource'])) {
    $data = ['error'=> true, 'errorMsg'=>'Insufficient privileges to access this resource.'];
    echo json_encode($data);
    exit();
}

$query = get_resource_query($request['resource']);

if (is_stored_procedure($request['resource'])) {
    $result = $mysqli->multi_query($query);
    echo json_encode($result);
    exit();
} else {
    $result = $mysqli->query($query);
}

//mysqli_multi_query
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
}

$result -> close();
$mysqli -> close();
echo json_encode($data);
exit();
