<?php
namespace Prin0u\DevoirAppMvcPhp\Core;

class Session
{

    public static function setFlash(string $key, string $message)
    {
        if(isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key)
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}