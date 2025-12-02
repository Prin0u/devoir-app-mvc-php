<?php
namespace Prin0u\DevoirAppMvcPhp\Core;

use PDO;
use PDOException;

class Model
{
    protected PDO $pdo;

    public function __construct(array $dbConfig)
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
                $dbConfig['user'],
                $dbConfig['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " / $e->getMessage());
        }
    }
}