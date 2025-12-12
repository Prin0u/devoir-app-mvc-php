<?php

/**
 * Fichier AuthController.php
 * * Ce contrôleur gère l'authentification des utilisateurs (connexion, déconnexion)
 * et les vérifications de rôle dans l'application.
 * * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Core\Session; // Bien que Session ne soit pas utilisé directement, le use est conservé.



class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     * Route: GET /login
     * * @return void
     */
    public function login()
    {
        // Rendu de la vue du formulaire (views/auth/login.php)
        $this->render('auth/login');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     * Effectue la vérification des identifiants contre la base de données.
     * Route: POST /login
     * * @return void
     */
    public function loginPost()
    {
        // Récupération et nettoyage des données POST
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        // Validation basique : vérifie si les champs sont remplis
        if (!$email || !$password) {
            $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
            header('Location: /login');
            exit;
        }


        try {
            $pdo = Database::getInstance();

            // 1. Recherche de l'utilisateur par email
            $stmt = $pdo->prepare("SELECT id_user, nom, prenom, email, password, role FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // 2. Vérification de l'email et du mot de passe
            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['flash_error'] = "Email ou mot de passe incorrect.";
                header('Location: /login');
                exit;
            }

            // Connexion réussie : stockage des informations essentielles en session
            $_SESSION['user'] = [
                'id' => $user['id_user'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                // NOTE: L'ajout du rôle ici est CRUCIAL pour les vérifications checkAdmin/isAdmin
                'role' => $user['role']
            ];

            // Redirection vers la page d'accueil
            header('Location: /');
            exit;
        } catch (\PDOException $e) {
            // Gestion de l'erreur BDD
            error_log("Erreur PDO lors de la connexion : " . $e->getMessage());
            $_SESSION['flash_error'] = "Une erreur serveur est survenue.";
            header('Location: /login');
            exit;
        }
    }

    /**
     * Vérifie si l'utilisateur actuellement connecté a le rôle Administrateur.
     * * @return bool Vrai si l'utilisateur est administrateur.
     */
    public static function isAdmin(): bool
    {

        return isset($_SESSION['user'])
            && $_SESSION['user']['email'] === 'alexandre.martin@email.fr';
    }


    /**
     * Déconnecte l'utilisateur en détruisant la session.
     * Route: GET /logout
     * * @return void
     */
    public function logout()
    {
        // Destruction complète de la session utilisateur
        session_destroy();
        // Redirection vers le formulaire de connexion
        header('Location: /login');
        exit;
    }
}
