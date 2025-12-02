<?php

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;

// Route de la page d'accueil

$router->get('/', [HomeController::class, 'index']);

