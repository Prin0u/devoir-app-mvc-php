<?php

/**
 * Fichier : partials/header.php
 * Rôle : Fournit la barre de navigation principale de l'application (en-tête).
 * Description : 
 * - Contient la structure HTML de la navbar Bootstrap 5.
 * - Gère l'affichage conditionnel des liens (Connexion/Déconnexion, Créer un trajet) 
 * en fonction de l'état de la session utilisateur.
 * - Affiche les liens d'administration si l'utilisateur est administrateur.
 * * Dépendances :
 * @uses \Prin0u\DevoirAppMvcPhp\Controllers\AuthController Nécessaire pour vérifier les droits d'administrateur.
 * * Variables de session utilisées :
 * @uses $_SESSION['user'] Pour l'authentification et l'affichage du nom de l'utilisateur.
 */

?>
<link rel="stylesheet" href="/css/main.css">



<?php

// Importation de la classe AuthController pour vérifier le rôle de l'utilisateur (isAdmin).
use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;
?>

<nav class="navbar navbar-expand-lg navbar-dark px-4">
    <a class="navbar-brand fw-bold" href="/">
        TOUCHE PAS AU KLAXON
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse w-100" id="navbarContent">

        <div class="d-flex flex-column flex-lg-row w-100 align-items-center justify-content-lg-start">

            <ul class="navbar-nav mx-auto mx-lg-0 ms-lg-auto gap-3 flex-column flex-lg-row text-center">

                <?php
                /**
                 * Affichage conditionnel : Utilisateur DÉCONNECTÉ
                 */
                if (!isset($_SESSION['user'])): ?>
                    <li class="nav-item w-100">
                        <a href="/login" class="btn btn-light mx-auto" style="display: block; width: fit-content;">Connexion</a>
                    </li>
                <?php
                    /**
                     * Affichage conditionnel : Utilisateur CONNECTÉ
                     */
                else: ?>
                    <li class="nav-item">
                        <a href="/trajet/create" class="btn-create btn-lg">Créer un trajet</a>
                    </li>

                    <li class="nav-item text-white">
                        Bonjour <?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                    </li>

                    <?php
                    /**
                     * Affichage conditionnel : Liens d'Administration (si l'utilisateur est admin)
                     */
                    if (AuthController::isAdmin()): ?>
                        <li class="nav-item">
                            <a href="/admin/users" class="nav-link text-white">Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/agences" class="nav-link text-white">Agences</a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/trajets" class="nav-link text-white">Trajets</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <?php
            /**
             * Bouton de déconnexion (toujours visible pour les utilisateurs connectés)
             */
            if (isset($_SESSION['user'])): ?>
                <li class="nav-item mt-3 mt-lg-0 list-unstyled">
                    <a href="/logout" class="btn btn-danger">Déconnexion</a>
                </li>
            <?php endif; ?>
        </div>
    </div>
</nav>