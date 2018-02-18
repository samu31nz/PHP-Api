<?php

//Database::delete('Users');

$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$id = Database::insert('Users', [
    'firstName' => $_POST['firstName'],
    'lastName' => $_POST['lastName'],
    'email' => $_POST['email'],
    'password' => $password_hash,
    'dob' => strtotime($_POST['dob']),
    'phone' => $_POST['phone'],
    'pets' => false,
    'smokes' => false,
    'addressStreet' => $_POST['addressStreet'],
    'addressSuburb' => $_POST['addressSuburb'],
    'addressTown' => $_POST['addressTown'],
    'addressZip' => $_POST['addressZip'],
]);

$return = [];
if(!is_null($id)) {
    $user = Database::get('Users', ['*'], 'id=?', [$id]);
    $uuid = Session::generate($user['id']);
    if(!is_null($uuid)) {
        $user['token'] = $uuid;
    }

    $return = $user;

    http_response_code(200);
    header('Content-Type: application/json');

    print(json_encode($return));
} else {
    http_response_code(409);
}

exit(0);
