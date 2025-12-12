<?php

/**
 * Fichier AdminController.php
 * * Ce contrôleur gère les vues d'administration principales
 * (utilisateurs et trajets) ainsi que la suppression de trajets.
 * * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;

class AdminController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs du système.
     * Read - Route: GET /admin/users
     * * @return void
     */    public function users()
    {
        $this->checkAdmin();

        $pdo = Database::getInstance();
        // Récupération des données des utilisateurs pour la consultation
        $stmt = $pdo->query("SELECT id_user, nom, prenom, email, telephone FROM utilisateurs ORDER BY nom ASC");
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Rendu de la vue 'views/admin/users.php'
        $this->render('admin/users', ['users' => $users]);
    }

    /**
     * Affiche la liste détaillée de tous les trajets existants.
     * Read - Route: GET /admin/trajets
     * * @return void
     */
    public function trajets()
    {
        $this->checkAdmin();

        $pdo = Database::getInstance();

        // Requête complexe avec JOINTURES pour afficher les noms des agences et de l'utilisateur
        $stmt = $pdo->prepare("
         SELECT 
             t.id_trajet, 
             a1.nom AS depart, 
             t.date_heure_depart, 
             a2.nom AS arrivee, 
             t.date_heure_arrivee, 
             t.nb_places_disponibles, 
             u.nom AS user_nom, 
             u.prenom AS user_prenom
         FROM trajets t
         
         LEFT JOIN agences a1 ON t.id_agence_depart = a1.id_agence 
         LEFT JOIN agences a2 ON t.id_agence_arrivee = a2.id_agence
         LEFT JOIN utilisateurs u ON t.id_user_createur = u.id_user 
         
         ORDER BY t.date_heure_depart ASC
     ");
        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Rendu de la vue 'views/admin/trajets.php'
        $this->render('admin/trajets', ['trajets' => $trajets]);
    }

    /**
     * Supprime un trajet spécifique de la base de données.
     * Delete - Route: POST /admin/trajets/delete/{id}
     * * @param int $id L'identifiant du trajet à supprimer.
     * @return void
     */
    public function delete($id)
    {
        // 1. Vérification des droits d'administrateur
        $this->checkAdmin();

        // 2. Vérification de la méthode POST (sécurité contre les suppressions GET)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = "Requête non autorisée.";
            header('Location: /admin/trajets');
            exit;
        }

        try {
            $pdo = Database::getInstance();

            // 3. (Optionnel mais recommandé) Vérification de l'existence du trajet
            $stmt_check = $pdo->prepare("SELECT id_trajet FROM trajets WHERE id_trajet = ?");
            $stmt_check->execute([$id]);
            if (!$stmt_check->fetch()) {
                $_SESSION['flash_error'] = "Trajet introuvable.";
                header('Location: /admin/trajets');
                exit;
            }

            // 4. Suppression en base de données
            $stmt_delete = $pdo->prepare("DELETE FROM trajets WHERE id_trajet = ?");
            $stmt_delete->execute([$id]);

            $_SESSION['flash_success'] = "Trajet supprimé.";
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du trajet : " . $e->getMessage());
            $_SESSION['flash_error'] = "Erreur de base de données lors de la suppression.";
        }

        // 6. Redirection vers la liste des trajets de l'administration
        header('Location: /admin/trajets');
        exit;
    }


    /**
     * Vérifie si l'utilisateur est connecté et possède le rôle Administrateur.
     * Si les conditions ne sont pas remplies, redirige vers la page d'accueil.
     * * @return void
     */
    private function checkAdmin()
    {
        if (!isset($_SESSION['user']) || !AuthController::isAdmin()) {
            $_SESSION['flash_error'] = "Accès interdit. Vous devez être administrateur.";
            header('Location: /');
            exit;
        }
    }
}
