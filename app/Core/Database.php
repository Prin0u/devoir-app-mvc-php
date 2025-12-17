<?php

namespace Prin0u\DevoirAppMvcPhp\Core;

use PDO;
use PDOException;

class Database
{
    /**
     * @var PDO|null $instance L'instance unique de la connexion PDO.
     * Initialisé à null, il contiendra l'objet PDO après le premier appel à getInstance().
     */
    private static ?PDO $instance = null;
    // Propriété pour stocker le chemin du fichier de configuration
    private static string $configFile = __DIR__ . '/../../config/database.php';

    /**
     * Permet de définir un fichier de configuration alternatif (utile pour les tests).
     * @param string $path Chemin vers le fichier de configuration (ex: database_test.php).
     */
    public static function setConfigFile(string $path): void
    {
        self::$configFile = $path;
        self::$instance = null; // Réinitialise l'instance pour forcer une nouvelle connexion
    }
    /**
     * Retourne l'instance unique de la connexion PDO.
     * Si l'instance n'existe pas, elle est créée en utilisant les configurations du fichier database.php.
     * @return PDO L'objet de connexion PDO.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Chargement du tableau de configuration depuis le fichier externe
            $config = require self::$configFile;

            // Construction du Data Source Name (DSN)
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            try {
                // Création et stockage de l'instance PDO
                self::$instance = new PDO(
                    $dsn,
                    $config['user'],
                    $config['password'],
                    [
                        // Configuration des attributs PDO
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Active les exceptions en cas d'erreur SQL
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Définit le mode de récupération par défaut (tableau associatif)
                    ]
                );
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return self::$instance;
    }
}
