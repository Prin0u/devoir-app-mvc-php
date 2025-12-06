<?php

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;

// Route de la page d'accueil

$router->get('/', [HomeController::class, 'index']);

// Routes de la page de connexion

$router->get('/login', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@login');
$router->post('/login', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@loginPost');
$router->get('/logout', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@logout');

