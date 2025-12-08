<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Touche pas au klaxon</title>
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <!-- Messages flash -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="w-90 bg-light border rounded p-3">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger text-center">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <h2 class=" mt-4 text-center">Trajets proposés</h2>
    <?php if (empty($trajets)) : ?>
        <p class="mt-2 text-center">Aucun trajet prévu pour le moment.</p>
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
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($trajets as $trajet): ?>
                        <tr>
                            <td><?= htmlspecialchars($trajet['depart']) ?></td>
                            <td><?= date('d/m/Y', strtotime($trajet['date_heure_depart'])) ?></td>
                            <td><?= date('H:i', strtotime($trajet['date_heure_depart'])) ?></td>
                            <td><?= htmlspecialchars($trajet['arrivee']) ?></td>
                            <td><?= date('d/m/Y', strtotime($trajet['date_heure_arrivee'])) ?></td>
                            <td><?= date('H:i', strtotime($trajet['date_heure_arrivee'])) ?></td>
                            <td><?= $trajet['nb_places_disponibles'] ?></td>
                            <td>
                                <a href="/trajet/edit/<?= $trajet['id_trajet'] ?>" class="btn btn-sm btn-primary me-2">Modifier</a>
                                <form method="POST" action="/trajet/delete/<?= $trajet['id_trajet'] ?>"
                                    style="display:inline;"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>

</html>