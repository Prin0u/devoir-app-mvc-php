<?php

use Prin0u\DevoirAppMvcPhp\Controllers\HomeController;
use Prin0u\DevoirAppMvcPhp\Controllers\TrajetController;
use Prin0u\DevoirAppMvcPhp\Controllers\AdminController;
use Prin0u\DevoirAppMvcPhp\Controllers\AdminAgencesController;
use Prin0u\DevoirAppMvcPhp\Controllers\AdminTrajetsController;

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

//Routes de la page agences
$router->get('/admin/agences', [AdminAgencesController::class, 'index']);
$router->get('/admin/agences/create', [AdminAgencesController::class, 'create']);
$router->post('/admin/agences/', [AdminAgencesController::class, 'store']);
$router->get('/admin/agences/edit/:id', [AdminAgencesController::class, 'edit']);
$router->post('/admin/agences/update/:id', [AdminAgencesController::class, 'update']);
$router->post('/admin/agences/delete/:id', [AdminAgencesController::class, 'delete']);

// Routes des pages admin
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/trajets', [AdminTrajetsController::class, 'index']);
$router->post('/admin/trajets/:id', [AdminTrajetsController::class, 'delete']);
