<?php

use Database\SQLite;

class App {

    public function __construct() {
        if(isset($_GET['request'])) {

            Database::open('sqlite.db');

            $request = explode('/', $_GET['request']);
            $method = $request[0];
            $view = $request[1];
            switch($method):
                case 'auth':
                    switch($view):
                        case 'login':
                            include('api/auth/login.php');
                            break;
                    endswitch;
                    break;
                case 'get':
                    switch($view):
                        case 'properties':
                            include('api/get/properties.php');
                            break;
                    endswitch;
                    break;
            endswitch;
        }

        http_response_code(404);

        var_dump($_SERVER);
        exit(0);
    }
}
