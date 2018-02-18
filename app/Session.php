<?php

class Session {

    public static function start():void {
        session_start();
    }

    public static function clear():void {
        $_SESSION = [];
        session_destroy();
        session_start();
    }

    public static function generate($user_id):?string {
        $unique = Crypto::gen_UUID();
        $id = Database::insert('Sessions', [
            'uuid' => $unique,
            'userID' => $user_id,
            'userAgent' => $_SERVER['HTTP_USER_AGENT'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'lastActive' => microtime(true),
        ]);

        return $unique;
    }
}
