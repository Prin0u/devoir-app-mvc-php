<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;

$controller = new HomeController();
$controller->index();