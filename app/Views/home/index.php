<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Touche pas au klaxon</title>
</head>
<body>
            <?php require __DIR__ . '/../partials/header.php'; ?>        <h2>Liste des trajets</h2>
        <?php if (empty($trajets)) : ?>
            <p>Aucun trajet prévu pour le moment.</p>
        <?php else : ?>
            <ul>
                <?php foreach ($trajets as $trajet): ?>
                    <li>
                        <?= htmlspecialchars($trajet['depart']) ?> -> <?= htmlspecialchars($trajet['arrivee']) ?>,
                        départ : <?= $trajet['date_heure_depart'] ?>,
                        places disponibles : <?= $trajet['places_disponibles'] ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
</body>
</html>

