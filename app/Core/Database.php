<?php

namespace Prin0u\DevoirAppMvcPhp\Core;

use PDO;
use PDOException;

class Database 
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if(self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                    );
            } catch (PDOException $e) {
                die('Erreur de connexion BDD : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}