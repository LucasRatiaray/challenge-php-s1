<?php

namespace App\Core;

class Error
{
    public function customError($errno, $errstr)
    {
        if($errno === 404) {
            header("HTTP/1.0 404 Not Found", true, $errno);
            die($errstr);
        } elseif ($errno === 500) {
            header("HTTP/1.0 500 Internal Server Error", true, $errno);
            die($errstr);
        }
    }
}