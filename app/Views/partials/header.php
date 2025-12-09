<link rel="stylesheet" href="/css/main.css">


<?php

use Prin0u\DevoirAppMvcPhp\Controllers\AuthController;
?>

<nav class="navbar navbar-expand-lg navbar-dark px-4">
    <a class="navbar-brand fw-bold" href="/">
        TOUCHE PAS AU KLAXON
    </a>

    <!-- Menu burger -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu classique / collapsible -->
    <div class="collapse navbar-collapse w-100" id="navbarContent">
        <div class="d-flex w-100 justify-content-between align-items-center">

            <!-- Partie gauche / centre : menu -->
            <ul class="navbar-nav mx-auto align-items-center gap-3 flex-column flex-lg-row text-center">
                <?php if (!isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a href="/login" class="btn btn-light">Connexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/trajet/create" class="btn-create btn-lg">Créer un trajet</a>
                    </li>

                    <li class="nav-item text-white">
                        Bonjour <?= htmlspecialchars($_SESSION['user']['prenom']) ?> <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                    </li>

                    <?php if (AuthController::isAdmin()): ?>
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

            <!-- Bouton déconnexion à droite -->
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/logout" class="btn btn-danger ms-3">Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>