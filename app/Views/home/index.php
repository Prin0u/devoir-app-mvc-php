<?php

/**
 * Fichier : home/index.php
 * Rôle : Point d'entrée de l'application (Front Controller) et Vue d'accueil.
 * Description : 
 * - Affiche la structure HTML principale et la liste des trajets disponibles.
 *
 * Variables de contexte attendues (injectées par HomeController) :
 * @var array $trajets Liste des trajets futurs, enrichie des données des créateurs.
 */


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title>Touche pas au klaxon</title>
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <?php
    /**
     * Bloc d'affichage des messages flash de succès ou d'erreur stockés en session.
     */
    ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="alert alert-success text-center my-3">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger text-center my-3">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div class="container mt-4">
        <?php
        /**
         * Titre conditionnel basé sur l'état de la connexion.
         */
        if (isset($_SESSION['user'])): ?>
            <h2 class="text-center mb-4">Trajets proposés</h2>
        <?php else: ?>
            <h2 class="text-center mb-4">Pour obtenir plus d'informations sur un trajet, veuillez vous connecter.</h2>
        <?php endif; ?>
        <?php if (empty($trajets)) : ?>
            <p class="text-center">Aucun trajet prévu pour le moment.</p>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Départ</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Heure</th>
                            <th>Places</th>

                            <?php
                            if (isset($_SESSION['user'])): ?>
                                <th>Actions</th>
                            <?php endif; ?>

                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php
                        foreach ($trajets as $trajet): ?>
                            <tr>
                                <td><?= htmlspecialchars($trajet['depart']) ?></td>
                                <td><?= date('d/m/Y', strtotime($trajet['date_heure_depart'])) ?></td>
                                <td><?= date('H:i', strtotime($trajet['date_heure_depart'])) ?></td>
                                <td><?= htmlspecialchars($trajet['arrivee']) ?></td>
                                <td><?= date('d/m/Y', strtotime($trajet['date_heure_arrivee'])) ?></td>
                                <td><?= date('H:i', strtotime($trajet['date_heure_arrivee'])) ?></td>
                                <td><?= $trajet['nb_places_disponibles'] ?></td>

                                <?php
                                // MASQUER LA CELLULE D'ACTIONS SI DÉCONNECTÉ
                                if (isset($_SESSION['user'])): ?>
                                    <td class="d-flex justify-content-center align-items-center gap-2">

                                        <a href="#" class="text-info" title="Voir" data-bs-toggle="modal" data-bs-target="#userModal<?= $trajet['id_trajet'] ?>">
                                            <i class="bi bi-eye fs-5"></i>
                                        </a>

                                        <?php
                                        // 2. MODIFIER/SUPPRIMER : Visible seulement par le créateur du trajet
                                        if ($_SESSION['user']['id'] == $trajet['id_user_createur']): ?>
                                            <a href="/trajet/edit/<?= $trajet['id_trajet'] ?>" class="text-warning" title="Modifier">
                                                <i class="bi bi-pencil-square fs-5"></i>
                                            </a>

                                            <form method="POST" action="/trajet/delete/<?= $trajet['id_trajet'] ?>"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
                                                <button type="submit" class="btn p-0 border-0 text-danger" title="Supprimer">
                                                    <i class="bi bi-trash-fill fs-5"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>

                            </tr>

                            <?php
                            // 3. LA MODALE ENTIÈRE DOIT ÊTRE MASQUÉE SI DÉCONNECTÉ
                            if (isset($_SESSION['user'])): ?>
                                <div class="modal fade" id="userModal<?= $trajet['id_trajet'] ?>" tabindex="-1" aria-labelledby="userModalLabel<?= $trajet['id_trajet'] ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="userModalLabel<?= $trajet['id_trajet'] ?>">Informations utilisateur</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <div class="modal-body">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><strong>Nom :</strong> <?= htmlspecialchars($trajet['user_nom']) ?></li>
                                                    <li class="list-group-item"><strong>Prénom :</strong> <?= htmlspecialchars($trajet['user_prenom']) ?></li>
                                                    <li class="list-group-item"><strong>Email :</strong> <?= htmlspecialchars($trajet['user_email']) ?></li>
                                                    <li class="list-group-item"><strong>Téléphone :</strong> <?= htmlspecialchars($trajet['user_telephone']) ?></li>
                                                    <li class="list-group-item"><strong>Places disponibles :</strong> <?= $trajet['nb_places_disponibles'] ?></li>
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-modal" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>