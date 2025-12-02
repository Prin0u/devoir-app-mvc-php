<?php

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;

   

// Chargement de l'autoload Composer

require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des fichiers de configuration

$config = require __DIR__ . '/../config/config.php';
$dbConfig = require __DIR__ . '/../config/database.php';

// Lancement de la session

session_start();

// Initialisation du routeur Izniburak
$router = new \Buki\Router\Router([
    'paths' => [
        'controllers' => '../app/Controllers/',
        'middlewares' => '../app/Middlewares/',
    ],
    'namespaces' => [
        'controllers' => 'Prin0u\DevoirAppMvcPhp\Controllers',
        'middlewares' => 'Prin0u\DevoirAppMvcPhp\Middlewares',
    ],
]);

// Chargement des routes
require __DIR__ . '/../app/routes.php';

// Lancement du routeur

$router->run();