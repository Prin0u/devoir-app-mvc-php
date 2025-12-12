<?php

/**
 * Fichier TrajetController.php
 * Ce contrôleur gère la création, la modification et la suppression (CRUD) des trajets
 * par les utilisateurs connectés (auteurs). Les fonctionnalités d'administration
 * et la liste complète sont déplacées vers AdminTrajetsController.
 * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;

class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de création d'un nouveau trajet.
     * Route: GET /trajet/create
     * @return void
     */
    public function create()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $pdo = Database::getInstance();

        $stmt = $pdo->query("SELECT id_agence, nom FROM agences ORDER BY nom ASC");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('trajet/create', [
            'agences' => $agences
        ]);
    }

    /**
     * Traite la soumission du formulaire et insère un nouveau trajet en base de données.
     * Create - Route: POST /trajet/store
     * @return void
     */
    public function store()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $departId = $_POST['agence_depart'] ?? null;
        $arriveeId = $_POST['agence_arrivee'] ?? null;
        $dateDepart = $_POST['date_heure_depart'] ?? null;
        $dateArrivee = $_POST['date_heure_arrivee'] ?? null;
        $nbPlaces = $_POST['nb_places'] ?? null;

        if (!$departId || !$arriveeId || !$dateDepart || !$dateArrivee || !$nbPlaces) {
            $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
            header('Location: /trajet/create');
            exit;
        }

        if ($departId == $arriveeId) {
            $_SESSION['flash_error'] = "L'agence de départ et l'agence d'arrivée doivent être différentes.";
            header('Location: /trajet/create');
            exit;
        }

        if ($dateArrivee <= $dateDepart) {
            $_SESSION['flash_error'] = "La date d'arrivée doit être après la date de départ.";
            header('Location: /trajet/create');
            exit;
        }

        if (!is_numeric($nbPlaces) || $nbPlaces <= 0) {
            $_SESSION['flash_error'] = "Le nombre de places doit être supérieur à 0.";
            header('Location: /trajet/create');
            exit;
        }

        try {
            $pdo = Database::getInstance();

            $stmt = $pdo->prepare("
                INSERT INTO trajets
                (id_agence_depart, id_agence_arrivee, date_heure_depart, date_heure_arrivee, nb_places_total, nb_places_disponibles, id_user_createur, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ");

            $stmt->execute([
                $departId,
                $arriveeId,
                $dateDepart,
                $dateArrivee,
                $nbPlaces,
                $nbPlaces,
                $_SESSION['user']['id']
            ]);

            $_SESSION['flash_success'] = "Le trajet a été créé avec succès.";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la création du trajet : " . $e->getMessage();
            header('Location: /trajet/create');
            exit;
        }
    }

    /**
     * Affiche le formulaire de modification pré-rempli pour un trajet spécifique.
     * Update - Route: GET /trajet/edit/{id}
     * @param int $id L'identifiant du trajet à modifier.
     * @return void
     */
    public function edit($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT id_user_createur FROM trajets WHERE id_trajet = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header('Location: /');
            exit;
        }

        if ($trajet['id_user_createur'] != $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = "Accès interdit. Ce trajet ne vous appartient pas.";
            header('Location: /');
            exit;
        }

        $stmt = $pdo->query("SELECT id_agence, nom FROM agences ORDER BY nom ASC");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM trajets WHERE id_trajet = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->render('trajet/edit', [
            'trajet' => $trajet,
            'agences' => $agences
        ]);
    }


    /**
     * Traite la soumission du formulaire et met à jour le trajet en base de données.
     * Update - Route: POST /trajet/update/{id}
     * @param int $id L'identifiant du trajet à modifier.
     * @return void
     */
    public function update($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $departId   = $_POST['agence_depart'] ?? null;
        $arriveeId  = $_POST['agence_arrivee'] ?? null;
        $dateDepart = $_POST['date_heure_depart'] ?? null;
        $dateArrivee = $_POST['date_heure_arrivee'] ?? null;
        $nbPlaces   = $_POST['nb_places'] ?? null;

        if (!$departId || !$arriveeId || !$dateDepart || !$dateArrivee || !$nbPlaces) {
            $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
            header("Location: /trajet/edit/$id");
            exit;
        }

        if ($departId == $arriveeId) {
            $_SESSION['flash_error'] = "Les agences doivent être différentes.";
            header("Location: /trajet/edit/$id");
            exit;
        }

        if ($dateArrivee <= $dateDepart) {
            $_SESSION['flash_error'] = "La date d'arrivée doit être après le départ.";
            header("Location: /trajet/edit/$id");
            exit;
        }

        if (!is_numeric($nbPlaces) || $nbPlaces <= 0) {
            $_SESSION['flash_error'] = "Nombre de places invalide.";
            header("Location: /trajet/edit/$id");
            exit;
        }

        try {
            $pdo = Database::getInstance();

            $stmt = $pdo->prepare("
            UPDATE trajets
            SET id_agence_depart = ?, 
                id_agence_arrivee = ?, 
                date_heure_depart = ?, 
                date_heure_arrivee = ?, 
                nb_places_total = ?, 
                nb_places_disponibles = ?, 
                updated_at = NOW()
            WHERE id_trajet = ? 
            ");

            $stmt->execute([
                $departId,
                $arriveeId,
                $dateDepart,
                $dateArrivee,
                $nbPlaces,
                $nbPlaces,
                $id
            ]);

            $_SESSION['flash_success'] = "Le trajet a été modifié avec succès.";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la modification.";
            header("Location: /trajet/edit/$id");
            exit;
        }
    }

    /**
     * Supprime un trajet spécifique.
     * Uniquement accessible par l'auteur du trajet.
     * Delete - Route: POST /trajet/delete/{id}
     * @param int $id L'identifiant du trajet à supprimer.
     * @return void
     */
    public function delete($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['flash_error'] = "Requête non autorisée.";
            header('Location: /');
            exit;
        }

        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT id_user_createur FROM trajets WHERE id_trajet = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header('Location: /');
            exit;
        }

        $isCreator = ($trajet['id_user_createur'] == $_SESSION['user']['id']);

        if (!$isCreator) {
            $_SESSION['flash_error'] = "Accès interdit. Ce trajet ne vous appartient pas.";
            header('Location: /');
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM trajets WHERE id_trajet = ?");
            $stmt->execute([$id]);

            $_SESSION['flash_success'] = "Le trajet a été supprimé avec succès.";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la suppression.";
            header('Location: /');
            exit;
        }
    }

    /**
     * Récupère les informations de contact d'un utilisateur.
     * Utilisé pour la modale de détail d'un trajet.
     * @param int $id_user L'identifiant de l'utilisateur.
     * @return array Les données de l'utilisateur (nom, prenom, email, telephone).
     */
    public function getUserInfo(int $id_user): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT nom, prenom, email, telephone FROM utilisateurs WHERE id_user = :id");
        $stmt->execute(['id' => $id_user]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $userData ?: [];
    }

    /**
     * Récupère le nombre total de places disponibles pour les trajets créés par un utilisateur.
     * @param int $id_user L'identifiant de l'utilisateur.
     * @return int Le nombre total de places disponibles.
     */
    public function getNbPlacesByUser(int $id_user): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT SUM(nb_places_disponibles) as total FROM trajets WHERE id_user_createur = :id");
        $stmt->execute(['id' => $id_user]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }
}
