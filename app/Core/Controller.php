<?php

/**
 * Fichier Controller.php
 * * Classe de base pour tous les contrôleurs de l'application (Architecture MVC).
 * Fournit les méthodes utilitaires communes pour le rendu des vues, les redirections
 * et la gestion des messages flash.
 * @package Prin0u\DevoirAppMvcPhp\Core
 */

namespace Prin0u\DevoirAppMvcPhp\Core;

class Controller
{
    /**
     * Charge et affiche une vue spécifique, en y injectant des données.
     * @param string $view Le chemin de la vue à charger (ex: 'home/index').
     * @param array $data Les données à rendre disponibles dans la vue.
     * @return void
     */
    protected function render(string $view, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }

    /**
     * Redirige l'utilisateur vers une URL spécifiée.
     * @param string $url L'URL de destination.
     * @return void
     */
    protected function redirect(string $url)
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Définit un message flash dans la session.
     * Utile pour afficher des messages de succès ou d'erreur après une redirection.
     * @param string $key La clé unique du message flash (ex: 'flash_success', 'flash_error').
     * @param string $message Le contenu du message.
     * @return void
     */
    protected function setFlash(string $key, string $message)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Récupère un message flash de la session et le supprime immédiatement.
     * Assure que le message n'est affiché qu'une seule fois.
     * @param string $key La clé du message flash à récupérer.
     * @return string|null Le contenu du message, ou null s'il n'existe pas.
     */
    protected function getFlash(string $key)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}
