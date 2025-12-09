<link rel="stylesheet" href="/css/main.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



<?php

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

                <?php if (!isset($_SESSION['user'])): ?>
                    <li class="nav-item w-100">
                        <a href="/login" class="btn btn-light mx-auto" style="display: block; width: fit-content;">Connexion</a>
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

            <?php if (isset($_SESSION['user'])): ?>
                <li class="nav-item mt-3 mt-lg-0 list-unstyled">
                    <a href="/logout" class="btn btn-danger">Déconnexion</a>
                </li>
            <?php endif; ?>
        </div>
    </div>
</nav>