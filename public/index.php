<?php

// Chargement de l'autoload Composer

require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des fichiers de configuration

$config = require __DIR__ . '/../config/config.php';
$dbConfig = require __DIR__ . '/../config/database.php';

// Lancement de la session

session_start();
