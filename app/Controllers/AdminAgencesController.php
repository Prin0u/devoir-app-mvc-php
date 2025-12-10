<?php

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;

class AdminAgencesController extends Controller
{
    private function checkAdmin()
    {
        if (!isset($_SESSION['user']) || !AuthController::isAdmin()) {
            $_SESSION['flash_error'] = "Accès interdit.";
            header('Location: /');
            exit;
        }
    }

    // [R]ead - Liste des agences (Vue: views/admin/agences.php)
    public function index()
    {
        $this->checkAdmin();
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT id_agence, nom FROM agences ORDER BY nom ASC");
        $agences = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->render('admin/agences', ['agences' => $agences]);
    }

    // [C]reate - Affichage du formulaire (Vue: views/agences/create.php)
    public function create()
    {
        $this->checkAdmin();
        $this->render('agences/create');
    }

    // [C]reate - Traitement de l'insertion
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

            $_SESSION['flash_success'] = "L'agence a été créée";
            header('Location: /admin/agences');
            exit();
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur BDD : " . $e->getMessage();
            header('Location: /admin/agences/create');
            exit();
        }
    }

    // Affichage du formulaire pré-rempli
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

    // Traitement de la modification
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

            $_SESSION['flash_success'] = "Agence modifiée";
            header('Location: /admin/agences');
            exit();
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur BDD lors de la mise à jour.";
            header('Location: /admin/agences/edit/' . $id);
            exit();
        }
    }

    // Suppression
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
            header('Location: /admin/agences');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = "Erreur de base de données lors de la suppression.";
        }
    }
}
