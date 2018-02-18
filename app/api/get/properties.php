<?php

$input = file_get_contents('php://input');
$json = json_decode($input, true);

$data = $json;

$properties = Database::get('Properties', ['*']);
if(count($properties) == 0) {
    http_response_code(409);
    exit(0);
}

$return = $properties;

http_response_code(200);
header('Content-Type: application/json');

print(json_encode($return));

exit(0);
