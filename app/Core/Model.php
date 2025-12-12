<?php

/**
 * Fichier Model.php
 * * Classe de base pour tous les modèles de l'application.
 * Elle établit la connexion PDO à la base de données et fournit l'objet PDO
 * aux modèles enfants pour les interactions CRUD.
 * @package Prin0u\DevoirAppMvcPhp\Core
 */

namespace Prin0u\DevoirAppMvcPhp\Core;

use PDO;
use PDOException;

class Model
{
    /**
     * @var PDO L'objet de connexion PDO à la base de données.
     */
    protected PDO $pdo;

    /**
     * Constructeur de la classe Model.
     * Initialise la connexion à la base de données en utilisant les paramètres fournis.
     * @param array $dbConfig Tableau de configuration de la base de données (host, dbname, user, password, charset).
     */
    public function __construct(array $dbConfig)
    {
        try {
            // Création de l'objet PDO
            $this->pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
                $dbConfig['user'],
                $dbConfig['password']
            );
            // Configuration pour lever des exceptions en cas d'erreur SQL
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Arrêt du script et affichage de l'erreur en cas d'échec de connexion
            die("Erreur de connexion à la base de données : " . $e->getMessage()); // CONCATENATION CORRIGÉE : / remplacé par .
        }
    }
}
