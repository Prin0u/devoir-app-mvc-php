<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php foreach ($trajets as $trajet): ?>
                        <tr>
                            <td><?= htmlspecialchars($trajet['depart']) ?></td>
                            <td><?= htmlspecialchars($trajet['arrivee']) ?></td>
                            <td><?= $trajet['date_heure_depart'] ?></td>
                            <td><?= $trajet['date_heure_arrivee'] ?></td>
                            <td><?= $trajet['nb_places_disponibles'] ?></td>
                            <td>
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $trajet['id_user_createur']): ?>
                                    <a href="/trajet/edit/<?= $trajet['id_trajet'] ?>" class="btn btn-warning btn-sm me-1">
                                        Modifier
                                    </a>

                                    <form method="POST" action="/trajet/delete/<?= $trajet['id_trajet'] ?>"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ?');">
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/trajet/create" class="btn btn-success btn-lg">Créer un trajet</a>
    </div>
</div>