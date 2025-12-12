<?php

/**
 * Fichier AdminAgencesController.php
 * * Ce contrôleur gère toutes les opérations CRUD (Créer, Lire, Modifier, Supprimer)
 * pour l'entité Agences (villes), et est exclusivement accessible aux Administrateurs.
 * * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;

class AdminAgencesController extends Controller
{
    private function checkAdmin()
    {
        /**
         * Vérifie si l'utilisateur a les droits d'administration.
         * Si l'utilisateur n'est pas admin, il est redirigé vers la page d'accueil.
         * * @return void
         */
        if (!isset($_SESSION['user']) || !AuthController::isAdmin()) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: /');
            exit;
        }
    }

    /**
     * Affiche la liste de toutes les agences.
     * Read - Route: GET /admin/agences
     * * @return void
     */
    public function index()
    {
        $this->checkAdmin();
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT id_agence, nom FROM agences ORDER BY nom ASC");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('admin/agences', ['agences' => $agences]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle agence.
     * Create - Route: GET /admin/agences/create
     * * @return void
     */
    public function create()
    {
        $this->checkAdmin();
        $this->render('agences/create');
    }

    /**
     * Traite la soumission du formulaire et insère une nouvelle agence en BDD.
     * Create - Route: POST /admin/agences/store
     * * @return void
     */
    public function store()
    {
        $this->checkAdmin();
        if (empty($_POST['nom'])) {
            $_SESSION['flash_error'] = "Le nom est obligatoire.";
            header('Location: /admin/agences/create');
            exit();
        }

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("INSERT INTO agences (nom) VALUES (?)");
            $stmt->execute([htmlspecialchars($_POST['nom'])]);

            $_SESSION['flash_success'] = "Agence créée.";
            header('Location: /admin/agences');
            exit();
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur BDD : " . $e->getMessage();
            header('Location: /admin/agences/create');
            exit();
        }
    }

    /**
     * Affiche le formulaire de modification pré-rempli pour une agence.
     * Update - Route: GET /admin/agences/edit/{id}
     * * @param int $id L'identifiant de l'agence à modifier.
     * @return void
     */
    public function edit(int $id)
    {
        $this->checkAdmin();
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT id_agence, nom FROM agences WHERE id_agence = ?");
        $stmt->execute([$id]);
        $agence = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$agence) {
            $_SESSION['flash_error'] = "Agence non trouvée.";
            header('Location: /admin/agences');
            exit();
        }
        $this->render('agences/edit', ['agence' => $agence]);
    }

    /**
     * Traite la soumission du formulaire et met à jour l'agence correspondante.
     * Update - Route: POST /admin/agences/update/{id}
     * * @param int $id L'identifiant de l'agence à mettre à jour.
     * @return void
     */
    public function update(int $id)
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['nom'])) {
            $_SESSION['flash_error'] = "Nom invalide ou manquant.";
            header('Location: /admin/agences/edit/' . $id);
            exit();
        }

        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE agences SET nom = ? WHERE id_agence = ?");
            $stmt->execute([htmlspecialchars($_POST['nom']), $id]);

            $_SESSION['flash_success'] = "Agence modifiée.";
            header('Location: /admin/agences');
            exit();
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur BDD lors de la mise à jour.";
            header('Location: /admin/agences/edit/' . $id);
            exit();
        }
    }

    /**
     * Supprime une agence spécifique de la base de données.
     * Delete - Route: POST /admin/agences/delete/{id}
     * * @param int $id L'identifiant de l'agence à supprimer.
     * @return void
     */
    public function delete(int $id)
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = "Requête non autorisée.";
            header('Location: /admin/agences');
            exit;
        }

        try {
            $pdo = Database::getInstance();
            $stmt_delete = $pdo->prepare("DELETE FROM agences WHERE id_agence = ?");
            $stmt_delete->execute([$id]);

            $_SESSION['flash_success'] = "Agence supprimée.";
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur de base de données lors de la suppression.";
        }

        // La redirection vers la liste se fait dans tous les cas
        header('Location: /admin/agences');
        exit;
    }
}
