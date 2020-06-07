<?php
$data = array();

// Only allow POST requests
if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
    die('validate_json_request.php - HTTP REQUEST_METHOD needs to be POST!');
}

// Make sure Content-Type is application/json
$content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
if (stripos($content_type, 'application/json') === false) {
    die('validate_json_request.php - HTTP CONTENT_TYPE needs to be "application/json"');
}

// Read the input stream
$body = file_get_contents("php://input");

// Decode the JSON object
$object = json_decode($body, true);

// Throw an exception if decoding failed
if (!is_array($object)) {
    die('validate_json_request.php - Request body needs to be valid JSON');
}

return ($object);
exit();
