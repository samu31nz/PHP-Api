<?php

function autoload_handler(string $class):void {
    $object = str_replace(str_split('\\/'), DIRECTORY_SEPARATOR, $class);
    $file_name = __DIR__ . DIRECTORY_SEPARATOR . $object . '.php';

    require_once($file_name);
}

spl_autoload_register('autoload_handler');
