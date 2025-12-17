<?php

/**
 * Fichier AdminTrajetsController.php
 * Gère les fonctionnalités administratives sur les trajets (Liste complète et Suppression).
 * Nécessite des droits d'administrateur.
 * * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController; // Nécessaire pour vérifier les droits admin

class AdminTrajetsController extends Controller
{
    /**
     * Vérifie si l'utilisateur est connecté et est administrateur.
     * Si ce n'est pas le cas, redirige.
     * @return void
     */
    private function checkAdminAccess()
    {
        // 1. Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash_error'] = "Veuillez vous connecter pour accéder à l'administration.";
            header('Location: /login');
            exit;
        }

        // 2. Vérifie si l'utilisateur a le rôle d'administrateur
        if (!AuthController::isAdmin()) {
            $_SESSION['flash_error'] = "Accès refusé. Vous devez être administrateur.";
            header('Location: /');
            exit;
        }
    }

    /**
     * Affiche la liste complète de TOUS les trajets (fonction administrative).
     * Route: GET /admin/trajets
     * @return void
     */
    public function index()
    {
        $this->checkAdminAccess(); // Vérifie les droits avant l'exécution

        $pdo = Database::getInstance();

        // Requête ajustée pour produire les alias que VOTRE vue attend : user_nom et user_prenom.
        $stmt = $pdo->prepare("
            SELECT 
                t.*, 
                a1.nom AS depart, 
                a2.nom AS arrivee,
                u.nom AS user_nom,       
                u.prenom AS user_prenom
            FROM trajets t
            JOIN agences a1 ON t.id_agence_depart = a1.id_agence
            JOIN agences a2 ON t.id_agence_arrivee = a2.id_agence
            JOIN utilisateurs u ON t.id_user_createur = u.id_user
            ORDER BY t.date_heure_depart DESC
        ");
        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('admin/trajets', ['trajets' => $trajets]);
    }

    /**
     * Supprime un trajet spécifique (fonction administrative).
     * Route: POST /admin/trajets/delete/{id}
     * @param int $id L'identifiant du trajet à supprimer.
     * @return void
     */
    public function delete($id)
    {
        $this->checkAdminAccess(); // Vérifie les droits avant l'exécution

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = "Requête non autorisée.";
            header('Location: /admin/trajets');
            exit;
        }

        $pdo = Database::getInstance();

        try {
            // Suppression sans vérification d'auteur, car c'est une action administrative.
            $stmt = $pdo->prepare("DELETE FROM trajets WHERE id_trajet = ?");
            $stmt->execute([$id]);

            $_SESSION['flash_success'] = "Trajet supprimé.";
            header('Location: /admin/trajets');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la suppression administrative : " . $e->getMessage();
            header('Location: /admin/trajets');
            exit;
        }
    }
}
