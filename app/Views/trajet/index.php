<?php

/**
 * Fichier : Views/trajet/index.php (ou Views/home/index.php si c'est la page d'accueil)
 * Rôle : Vue affichant la liste des trajets disponibles.
 * Description : 
 * - La colonne "Actions" et le bouton "Créer un trajet" sont masqués si l'utilisateur est déconnecté (visiteur).
 * * Variables de contexte attendues (injectées par HomeController::index()):
 * @var array $trajets Liste des trajets à afficher.
 * * Variables de session utilisées :
 * @uses $_SESSION['user'] Pour vérifier l'authentification et l'ID de l'utilisateur actuel.
 */
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des trajets</h1>

    <?php if (empty($trajets)) : ?>
        <div class="alert alert-info text-center">
            Aucun trajet prévu pour le moment.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Date / Heure départ</th>
                        <th>Date / Heure arrivée</th>
                        <th>Places disponibles</th>
                        <?php
                        // 1. CONDITION POUR L'EN-TÊTE DE LA COLONNE
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
                            <td><?= htmlspecialchars($trajet['arrivee']) ?></td>
                            <td><?= $trajet['date_heure_depart'] ?></td>
                            <td><?= $trajet['date_heure_arrivee'] ?></td>
                            <td><?= $trajet['nb_places_disponibles'] ?></td>

                            <?php
                            // 2. CONDITION POUR LA CELLULE DE DONNÉES (TD)
                            if (isset($_SESSION['user'])): ?>
                                <td>
                                    <?php
                                    // Condition interne pour afficher les boutons Modifier/Supprimer
                                    if ($_SESSION['user']['id'] == $trajet['id_user_createur']): ?>
                                        <a href="/trajet/edit/<?= $trajet['id_trajet'] ?>" class="btn btn-warning btn-sm me-1">Modifier</a>
                                        <form method="POST" action="/trajet/delete/<?= $trajet['id_trajet'] ?>"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <?php
        // 3. CONDITION POUR LE BOUTON 'CRÉER UN TRAJET'
        if (isset($_SESSION['user'])): ?>
            <a href="/trajet/create" class="btn btn-success btn-lg">Créer un trajet</a>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>