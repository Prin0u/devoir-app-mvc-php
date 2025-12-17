<?php
// Fichier : tests/TrajetTest.php

namespace Tests;

use Prin0u\DevoirAppMvcPhp\Controllers\TrajetController;
use Prin0u\DevoirAppMvcPhp\Core\Database;

/**
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 */
final class TrajetTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Assurer un utilisateur dans la session
        $_SESSION['user'] = ['id' => 1];

        // Assurer que $_POST est vide au début
        $_POST = [];
    }



    /**
     * Teste la création réussie d'un trajet par son créateur.
     */    public function testStoreSuccessfullyCreatesNewTrajet(): void
    {
        // Préparer les données POST
        $_POST['agence_depart'] = 1;
        $_POST['agence_arrivee'] = 2;
        $_POST['date_heure_depart'] = '2026-01-01 10:00:00';
        $_POST['date_heure_arrivee'] = '2026-01-01 12:00:00';
        $_POST['nb_places'] = 5;

        $controller = new TrajetController();

        // Appel de store()
        try {
            $controller->store();
        } catch (\Throwable $e) {
            $this->fail("Erreur lors de l'appel à store() : " . $e->getMessage());
        }

        // Vérifier si l'INSERT a réussi
        $stmt = $this->pdo->query("
            SELECT COUNT(*) 
            FROM trajets 
            WHERE id_user_createur = 1 
              AND nb_places_total = 5
              AND id_agence_depart = 1
              AND id_agence_arrivee = 2
        ");
        $count = $stmt->fetchColumn();

        // Debug si échec
        if ($count == 0) {
            $errorInfo = $this->pdo->errorInfo();
            var_dump($errorInfo);
        }

        $this->assertEquals(
            1,
            $count,
            "Le TrajetController n'a pas réussi à insérer le nouveau trajet."
        );

        // Vérifier le flash message
        $this->assertArrayHasKey('flash_success', $_SESSION);
        $this->assertStringContainsString('créé avec succès', $_SESSION['flash_success']);

        // Nettoyage
        unset($_SESSION['user'], $_SESSION['flash_success']);
        $_POST = [];
    }



    /**
     * Teste la suppression réussie d'un trajet par son créateur.
     */
    public function testDeleteSuccessfullyRemovesTrajet(): void
    {
        // 1. Préparation des données de base
        // Utilisateur 1 (créateur) est déjà dans la session grâce à setUp()
        $_SESSION['user'] = ['id' => 1];

        // 2. Insérer le trajet à supprimer (ID = 1 car AUTO_INCREMENT est réinitialisé)
        $date = date('Y-m-d H:i:s', strtotime('+1 day'));
        $this->pdo->exec("
            INSERT INTO trajets 
            (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee, 
             nb_places_total, nb_places_disponibles, id_user_createur, created_at, updated_at) 
            VALUES (1, 2, '{$date}', '{$date}', 4, 4, 1, NOW(), NOW())
        ");

        // Vérification préliminaire : La ligne existe
        $initialCount = $this->pdo->query("SELECT COUNT(*) FROM trajets WHERE id_trajet = 1")->fetchColumn();
        $this->assertEquals(1, $initialCount, "Erreur : Le trajet à supprimer n'a pas été inséré.");

        // 3. Simuler la requête POST pour la suppression
        // La méthode delete vérifie que la requête est POST
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // L'ID du trajet à supprimer est 1 (car c'est le premier inséré)
        $trajetIdToDelete = 1;

        // 4. Exécuter le contrôleur (suppression)
        $controller = new TrajetController();
        $controller->delete($trajetIdToDelete);

        // 5. Assertions

        // A. Vérifier que la ligne a été supprimée
        $finalCount = $this->pdo->query("SELECT COUNT(*) FROM trajets WHERE id_trajet = 1")->fetchColumn();
        $this->assertEquals(0, $finalCount, "L'assertion a échoué : Le trajet n'a pas été supprimé.");

        // B. Vérifier le message flash de succès
        $this->assertArrayHasKey('flash_success', $_SESSION);
        $this->assertStringContainsString('supprimé avec succès', $_SESSION['flash_success']);

        // Rétablir l'état initial pour $_SERVER
        unset($_SERVER['REQUEST_METHOD']);
    }



    /**
     * Teste la mise à jour réussie d'un trajet par son créateur.
     */
    public function testUpdateSuccessfullyModifiesTrajet(): void
    {
        // 1. PRÉPARATION : Insérer le trajet initial à modifier
        $initialPlaces = 4;
        $dateDepart = date('Y-m-d H:i:s', strtotime('+1 day'));
        $dateArrivee = date('Y-m-d H:i:s', strtotime('+2 days'));

        $this->pdo->exec("
            INSERT INTO trajets 
            (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee, 
             nb_places_total, nb_places_disponibles, id_user_createur, created_at, updated_at) 
            VALUES (1, 2, '{$dateDepart}', '{$dateArrivee}', {$initialPlaces}, {$initialPlaces}, 1, NOW(), NOW())
        ");

        $trajetIdToUpdate = 1;

        // 2. SIMULATION : Préparer les nouvelles données (POST)
        $newPlaces = 8;
        $newDateDepart = date('Y-m-d H:i:s', strtotime('+3 days'));

        $_SESSION['user'] = ['id' => 1]; // L'utilisateur connecté est bien le créateur
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'agence_depart' => 1,
            'agence_arrivee' => 3, // Changement d'agence d'arrivée (de 2 à 3)
            'date_heure_depart' => $newDateDepart,
            'date_heure_arrivee' => date('Y-m-d H:i:s', strtotime('+4 days')),
            'nb_places' => $newPlaces
        ];

        // 3. EXÉCUTION : Appeler le contrôleur pour la mise à jour
        $controller = new TrajetController();
        $controller->update($trajetIdToUpdate);

        // 4. ASSERTIONS : Vérifier que les données ont bien changé dans la BDD

        // A. Récupérer le trajet après modification
        $stmt = $this->pdo->query("SELECT * FROM trajets WHERE id_trajet = {$trajetIdToUpdate}");
        $updatedTrajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        // B. Vérifier le changement de places
        $this->assertEquals(
            $newPlaces,
            (int)$updatedTrajet['nb_places_total'],
            "L'assertion a échoué : Le nombre de places n'a pas été mis à jour."
        );

        // C. Vérifier le changement d'agence d'arrivée
        $this->assertEquals(
            3,
            (int)$updatedTrajet['id_agence_arrivee'],
            "L'assertion a échoué : L'agence d'arrivée n'a pas été changée."
        );

        // D. Vérifier le message flash de succès
        $this->assertArrayHasKey('flash_success', $_SESSION);
        $this->assertStringContainsString('modifié avec succès', $_SESSION['flash_success']);

        // 5. Nettoyage
        unset($_SERVER['REQUEST_METHOD'], $_POST);
    }
}
