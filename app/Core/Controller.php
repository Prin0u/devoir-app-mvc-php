<?php

namespace Prin0u\DevoirAppMvcPhp\Core;

class Controller
{
    protected function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $url)
    {
        header ("Location: " . $url);
        exit;
    }

    protected function setFlash(string $key, string $message)
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash(string $key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}