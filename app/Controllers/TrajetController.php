<?php

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;

class TrajetController extends Controller
{
    // Affichage du formulaire
    public function create()
    {
        // Pour utilisateurs connectés uniquement
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $pdo = Database::getInstance();

        // Récupération des agences
        $stmt = $pdo->query("SELECT * FROM agences");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('trajet/create', [
            'agences' => $agences
        ]);
    }

    // Traitement du formulaire
    public function store()
    {
        // Vérification connexion
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Récupération des valeurs du formulaire
        $departId = $_POST['agence_depart'] ?? null;
        $arriveeId = $_POST['agence_arrivee'] ?? null;
        $dateDepart = $_POST['date_heure_depart'] ?? null;
        $dateArrivee = $_POST['date_heure_arrivee'] ?? null;
        $nbPlaces = $_POST['nb_places'] ?? null;

        // Vérifications simples
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

        // Insertion en base
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
                $nbPlaces,           // nb_places_total
                $nbPlaces,           // nb_places_disponibles
                $_SESSION['user']['id']
            ]);

            $_SESSION['flash_success'] = "Le trajet a été créé";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la création du trajet : " . $e->getMessage();
            header('Location: /trajet/create');
            exit;
        }
    }

    public function index()
    {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            SELECT t.*, a1.nom AS depart, a2.nom AS arrivee
            FROM trajets t
            JOIN agences a1 ON t.id_agence_depart = a1.id
            JOIN agences a2 ON t.id_agence_arrivee = a2.id
            ORDER BY t.date_heure_depart ASC
            ");
        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('trajet/index', ['trajets' => $trajets]);
    }

    // Affichage du formulaire de modification
    public function edit($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $pdo = Database::getInstance();

        // Récupération du trajet
        $stmt = $pdo->prepare("SELECT * FROM trajets WHERE id_trajet = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header('Location: /');
            exit;
        }

        // Vérifier que l'utilisateur est le créateur

        if ($trajet['id_user_createur'] != $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: /');
            exit;
        }

        // Récupération des agences
        $stmt = $pdo->query("SELECT * FROM agences");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->render('trajet/edit', [
            'trajet' => $trajet,
            'agences' => $agences
        ]);
    }



    // Traitement de la modification
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

            $_SESSION['flash_success'] = "Le trajet a été modifié";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur de la modification.";
            header("Location: /trajet/edit/$id");
            exit;
        }
    }

    // Suppression d’un trajet
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

        // Récupérer le trajet
        $stmt = $pdo->prepare("SELECT * FROM trajets WHERE id_trajet = ?");
        $stmt->execute([$id]);
        $trajet = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header('Location: /');
            exit;
        }

        // Vérifier que l'utilisateur est le créateur
        if ($trajet['id_user_createur'] != $_SESSION['user']['id'] && !AuthController::isAdmin()) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: /');
            exit;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM trajets WHERE id_trajet = ?");
            $stmt->execute([$id]);

            $_SESSION['flash_success'] = "Le trajet a été supprimé";
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur lors de la suppression.";
            header('Location: /');
            exit;
        }
    }
    // Récupérer les infos utilisateur
    public function getUserInfo(int $id_user): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT nom, prenom, email, telephone FROM utilisateurs WHERE id_user = :id");
        $stmt->execute(['id' => $id_user]);
        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $userData ?: [];
    }

    // Récupérer le nombre total de places pour cet utilisateur
    public function getNbPlacesByUser(int $id_user): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT SUM(nb_places_disponibles) as total FROM trajets WHERE id_user_createur = :id");
        $stmt->execute(['id' => $id_user]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }
}
