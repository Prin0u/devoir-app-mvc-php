<?php

/**
 * Fichier Model.php
 * * Classe de base pour tous les modèles de l'application.
 * Elle fournit l'objet PDO aux modèles enfants pour les interactions CRUD,
 * en utilisant la connexion centralisée.
 * @package Prin0u\DevoirAppMvcPhp\Core
 */

namespace Prin0u\DevoirAppMvcPhp\Core;

use PDO;
use Prin0u\DevoirAppMvcPhp\Core\Database;

class Model
{
    /**
     * @var PDO L'objet de connexion PDO à la base de données.
     */
    protected PDO $pdo;

    /**
     * Constructeur de la classe Model.
     * Récupère l'instance unique de connexion PDO via la classe Database.
     */
    public function __construct()
    {
        // On récupère la connexion unique centralisée via le singleton
        $this->pdo = Database::getInstance();
    }

    /**
     * Fournit un accès en lecture à l'objet PDO.
     * Utilisé principalement pour les tests unitaires pour manipuler la BDD de test.
     * @return PDO L'objet de connexion PDO.
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
