<link rel="stylesheet" href="/css/main.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


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

    <!-- Menu classique -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
        <ul class="navbar-nav align-items-center gap-3 flex-column flex-lg-row text-center">

            <!-- Visiteur -->
            <?php if (!isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a href="/login" class="btn btn-light w-100">Connexion</a>
                </li>
            <?php endif; ?>

            <!-- Utilisateur connecté -->
            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a href="/trajet/create" class="btn-create btn-lg">
                        Créer un trajet
                    </a>
                </li>

                <li class="nav-item text-white w-100">
                    Bonjour <?= $_SESSION['user']['prenom'] ?> <?= $_SESSION['user']['nom'] ?>
                </li>
            <?php endif; ?>

            <!-- Admin -->
            <?php if (isset($_SESSION['user']) && AuthController::isAdmin()): ?>
                <li class="nav-item">
                    <a href="/admin/users" class="nav-link text-white w-100">Utilisateurs</a>
                </li>

                <li class="nav-item">
                    <a href="/admin/agences" class="nav-link text-white w-100">Agences</a>
                </li>

                <li class="nav-item">
                    <a href="/admin/trajets" class="nav-link text-white w-100">Trajets</a>
                </li>

                <li class="nav-item ms-0 mt-2 mt-lg-0">
                    <a href="/logout" class="btn btn-danger w-100">Déconnexion</a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</nav>