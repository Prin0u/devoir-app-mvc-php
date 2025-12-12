<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php
/**
 * Vue : Liste des trajets dans l'interface d'administration.
 * ----------------------------------------------------
 * Cette page affiche tous les trajets enregistrés, permettant à l'administrateur
 * de les consulter et de les supprimer.
 *
 * Variables injectées :
 * - $trajets (array): Liste des trajets, chaque élément contenant :
 * ['id_trajet', 'depart', 'arrivee', 'date_heure_depart', 'user_prenom', 'user_nom'].
 */
require __DIR__ . '/../partials/header.php';
?>
<div class="container mt-5">


    <h1 class="text-center mb-4">Gestion des trajets</h1>

    <?php
    if (!empty($_SESSION['flash_success'])): ?>
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


    <?php if (empty($trajets)): ?>
        <div class="alert alert-info">Aucun trajet trouvé.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date / Heure de départ</th>
                    <th>Créateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $t): ?>
                    <tr>
                        <td><?= $t['depart'] ?></td>
                        <td><?= $t['arrivee'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($t['date_heure_depart'])) ?></td>
                        <td><?= $t['user_prenom'] . ' ' . $t['user_nom'] ?></td>
                        <td>
                            <form method="POST"
                                action="/admin/trajets/<?= $t['id_trajet'] ?>"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce trajet ? Cette action est irréversible.');">

                                <button type="submit"
                                    class="btn p-0 border-0 text-danger"
                                    title="Supprimer le trajet">

                                    <i class="bi bi-trash-fill fs-5"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    <?php endif; ?>
</div>
<?php require __DIR__ . '/../partials/footer.php'; ?>