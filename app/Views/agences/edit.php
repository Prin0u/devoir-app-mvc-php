<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
/**
 * Fichier : Views/agences/edit.php
 * Rôle : Vue affichant le formulaire de modification d'une agence existante.
 * ----------------------------------------------------------------------
 * Description : 
 * Cette page est utilisée par l'administrateur pour mettre à jour le nom d'une agence spécifique.
 * Le formulaire utilise la méthode POST et soumet les données à l'action '/admin/agences/update/{id}'.
 *
 * Variables de contexte attendues (injectées par le Contrôleur Agences) :
 * @var array $agence Tableau contenant les données de l'agence à modifier.
 * Il doit contenir au moins les clés ['id_agence', 'nom'].
 */

require_once __DIR__ . '/../partials/header.php';
?>


<div class="container mt-5">
    <h1 class="mb-4 text-center">Modifier l'agence : <?= htmlspecialchars($agence['nom'] ?? 'Inconnue') ?></h1>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <form method="POST"
        action="/admin/agences/update/<?= htmlspecialchars($agence['id_agence']) ?>"
        class="mx-auto" style="max-width: 500px;">

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'agence</label>
            <input type="text" name="nom" id="nom" class="form-control" required
                value="<?= htmlspecialchars($agence['nom'] ?? '') ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Modifier l'agence</button>
            <a href="/admin/agences" class="btn btn-secondary ms-2">Annuler et Retour</a>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>