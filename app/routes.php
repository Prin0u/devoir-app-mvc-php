<?php

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;
use Prin0u\DevoirAppMvcPhp\Controllers\TrajetController;
use Prin0u\DevoirAppMvcPhp\Controllers\AdminController;

// Route de la page d'accueil

$router->get('/', [HomeController::class, 'index']);

// Routes de la page de connexion

$router->get('/login', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@login');
$router->post('/login', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@loginPost');
$router->get('/logout', 'Prin0u\DevoirAppMvcPhp\Controllers\AuthController@logout');

// Routes de la page trajet

$router->get('/trajet/create', [TrajetController::class, 'create']);
$router->post('/trajet/create', [TrajetController::class, 'store']);
$router->get('/trajets', [TrajetController::class, 'index']);
$router->get('/trajet/edit/:id', [TrajetController::class, 'edit']);
$router->post('/trajet/update/:id', [TrajetController::class, 'update']);
$router->get('/trajet/delete/:id', [TrajetController::class, 'delete']);
$router->post('/trajet/delete/:id', [TrajetController::class, 'delete']);


// Routes des pages admin

$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/agences', [AdminController::class, 'agences']);
$router->get('/admin/trajets', [AdminController::class, 'trajets']);
$router->post('/admin/trajets/:id', [AdminController::class, 'delete']);
