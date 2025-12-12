<?php

/**
 * Fichier Session.php
 * * Classe utilitaire pour la gestion des données de session, principalement utilisée pour
 * l'enregistrement et la récupération des messages flash (messages éphémères).
 * @package Prin0u\DevoirAppMvcPhp\Core
 */

namespace Prin0u\DevoirAppMvcPhp\Core;

class Session
{

    /**
     * Définit un message flash dans la session.
     * Démarre la session si elle n'est pas déjà active.
     * @param string $key La clé unique du message flash (ex: 'flash_success', 'flash_error').
     * @param string $message Le contenu du message à stocker.
     * @return void
     */
    public static function setFlash(string $key, string $message)
    {
        // Démarre la session uniquement si elle n'est pas active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Récupère et supprime un message flash de la session.
     * Le message n'est accessible qu'une seule fois.
     * @param string $key La clé du message flash à récupérer.
     * @return string|null Le contenu du message, ou null s'il n'existe pas.
     */
    public static function getFlash(string $key)
    {
        // Démarre la session uniquement si elle n'est pas active
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
