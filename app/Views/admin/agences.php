<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<?php
/**
 * Vue : Liste et gestion des agences.
 * ----------------------------------------------------
 * Cette page affiche la liste de toutes les agences de la base de données
 * et fournit les options pour créer, modifier ou supprimer une agence.
 *
 * Variables injectées :
 * - $agences (array): Liste des agences, chacune contenant ['id_agence', 'nom'].
 */
require __DIR__ . '/../partials/header.php';
?>


<div class="container mt-5">
    <h1 class="text-center mb-4">Gestion des Agences</h1>

    <div class="d-flex justify-content-center mb-3">
        <a href="/admin/agences/create" class="btn btn-primary">
            Créer une agence
        </a>
    </div>

    <?php
    // Début de la gestion des messages flash
    // Affiche le message de succès s'il existe et le supprime de la session
    if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php
    // Affiche le message d'erreur s'il existe et le supprime de la session
    if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif;
    // Fin de la gestion des messages flash
    ?>

    <?php if (empty($agences)) : ?>
        <div class="alert alert-info text-center">
            Aucune agence trouvée.
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Agence</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agences as $agence): ?>
                        <tr>
                            <td><?= htmlspecialchars($agence['nom']) ?></td>
                            <td>
                                <a href="/admin/agences/edit/<?= htmlspecialchars($agence['id_agence']) ?>" class="btn p-0 border-0 text-warning" title="Modifier">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </a>

                                <form method="POST" action="/admin/agences/delete/<?= htmlspecialchars($agence['id_agence']) ?>" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette agence ?');">
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
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>