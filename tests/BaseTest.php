<?php
// Fichier : tests/BaseTest.php 

namespace Tests;

use PHPUnit\Framework\TestCase;
use Prin0u\DevoirAppMvcPhp\Core\Model;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use PDO;

/**
 * Classe de base pour tous les tests unitaires nécessitant une connexion BDD.
 * Nécessite que le fichier tests/seed.sql existe.
 */
abstract class BaseTest extends TestCase
{
    /**
     * @var array Configuration de la base de données de test.
     */
    protected static array $dbConfig;

    /**
     * @var PDO L'objet PDO de la connexion de test.
     */
    protected PDO $pdo;

    /**
     * Appelé une seule fois avant l'exécution de tous les tests.
     * Charge la configuration de test.
     */
    public static function setUpBeforeClass(): void
    {
        $configPath = __DIR__ . '/../config/database_test.php';

        if (!file_exists($configPath)) {
            self::fail('Le fichier de configuration de test est manquant : ' . $configPath);
        }

        self::$dbConfig = require $configPath;

        // Définir que PHPUnit est en cours (pour gérer les exit/die via la méthode redirect)
        if (!defined('PHPUNIT_RUNNING')) {
            define('PHPUNIT_RUNNING', true);
        }
    }

    /**
     * Appelé avant chaque test.
     * Initialise l'objet PDO et nettoie/peuplage les tables nécessaires.
     */
    protected function setUp(): void
    {
        // 1. DÉFINIR LE FICHIER DE CONFIGURATION DE TEST POUR LA CLASSE DATABASE STATIQUE
        $testConfigPath = __DIR__ . '/../config/database_test.php';
        Database::setConfigFile($testConfigPath);


        $baseModel = new Model(self::$dbConfig);
        $this->pdo = $baseModel->getPdo(); // On obtient l'instance PDO

        // 1. Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            // Utiliser session_id() pour éviter un warning/erreur si une session est déjà démarrée
            if (session_id() === '') {
                session_start();
            }
        }

        // --- NETTOYAGE ET SEEDING ---

        // Vider toutes les tables dans l'ordre inverse des dépendances pour les FK
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;"); // Temporairement désactiver les FK
        $this->pdo->exec("DELETE FROM trajets;");
        $this->pdo->exec("DELETE FROM utilisateurs;");
        $this->pdo->exec("DELETE FROM agences;");

        // Réinitialiser les compteurs d'auto-incrémentation (crucial pour l'ID=1)
        $this->pdo->exec("ALTER TABLE trajets AUTO_INCREMENT = 1;");
        $this->pdo->exec("ALTER TABLE utilisateurs AUTO_INCREMENT = 1;");
        $this->pdo->exec("ALTER TABLE agences AUTO_INCREMENT = 1;");

        // Rétablir les FK
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        // Charger les données de base (seeding)
        $seedPath = __DIR__ . '/seed.sql';
        if (!file_exists($seedPath)) {
            self::fail('Le fichier de seeding de test est manquant : ' . $seedPath . '. Vous devez le créer avec les données utilisateurs et agences.');
        }

        $sqlSeed = file_get_contents($seedPath);
        if ($sqlSeed) {

            $this->pdo->exec($sqlSeed);
        }

        // --- FIN SEEDING ---
    }

    /**
     * Appelé après chaque test.
     */
    protected function tearDown(): void
    {
        // Nettoyage des variables de session
        unset($_SESSION['user'], $_SESSION['flash_success'], $_SESSION['flash_error']);

        // Optionnel : Réinitialiser la session, bien que setUp le redémarre
        if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
        }
    }
}
