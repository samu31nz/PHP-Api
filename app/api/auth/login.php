<?php

$input = file_get_contents('php://input');
$json = json_decode($input, true);

$data = $json;

$users = Database::get('Users', ['*'], '`email`=?', [$data['email']]);
if(count($users) == 0) {
    http_response_code(409);
    exit(0);
}

$user = array_pop($users);

$auth = password_verify($data['password'], $user['password']);
$return = [];

if($auth == false) {
    http_response_code(409);
    exit(0);
}

unset($user['password']);

$uuid = Session::generate($user['id']);
if(!is_null($uuid)) {
    $user['token'] = $uuid;
}

$return = $user;

http_response_code(200);
header('Content-Type: application/json');

print(json_encode($return));

exit(0);
