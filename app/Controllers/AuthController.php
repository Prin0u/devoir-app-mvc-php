<?php

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;
use Prin0u\DevoirAppMvcPhp\Core\Session;



class AuthController extends Controller
{
    // Affichage du formulaire
    public function login()
    {
        $this->render('auth/login');
    }
    
    // Traitement du formulaire
    public function loginPost()
    {   
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            $_SESSION['flash_error'] = "Tous les champs sont obligatoires.";
            header('Location: /login');
            exit;
        }

    
        try {
            $pdo = Database::getInstance();
    
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
            if (!$user || !password_verify($password, $user['password'])) {
                $_SESSION['flash_error'] = "Email ou mot de passe incorrect.";
                header('Location: /login');
                exit;
            }
    
            // Connexion réussie
            $_SESSION['user'] = [
                'id' => $user['id_user'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email']
            ];
    
            header('Location: /');
            exit;
    
        } catch (\PDOException $e) {
            // Affiche l’erreur PDO pour debug
            echo "Erreur PDO : " . $e->getMessage();
            exit;
        }
    }
    

    // Deconnexion
    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}