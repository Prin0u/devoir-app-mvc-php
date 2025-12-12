<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
/**
 * Vue : Formulaire de création d'une nouvelle agence.
 * ----------------------------------------------------
 * Cette page est utilisée par l'administrateur pour ajouter une nouvelle agence.
 * Le formulaire utilise la méthode POST et soumet les données à l'action '/admin/agences'
 * (qui est gérée par la méthode store() de AdminAgencesController).
 */
// Assure-toi que le chemin vers header.php est correct par rapport à la racine
require_once __DIR__ . '/../partials/header.php';
?>


<div class="container mt-5">
    <h1 class="mb-4 text-center">Créer une agence</h1>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <form method="POST" action="/admin/agences" class="mx-auto" style="max-width: 500px;">

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de l'agence</label>
            <input type="text" name="nom" id="nom" class="form-control" required
                value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary ">Créer l'agence</button>
            <a href="/admin/agences" class="btn btn-secondary ms-2">Retour à la liste</a>
        </div>
    </form>
</div>

<?php
include __DIR__ . '/../partials/footer.php';
?>