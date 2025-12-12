<?php

/**
 * Fichier : Views/trajet/create.php
 * Rôle : Vue du formulaire de création d'un nouveau trajet.
 * Description : 
 * - Affiche le formulaire permettant à l'utilisateur connecté de proposer un nouveau covoiturage.
 * - Récupère la liste des agences pour les champs de sélection de départ et d'arrivée.
 * - Gère l'affichage des messages flash de succès ou d'erreur après la soumission.
 * * Variables de contexte attendues (injectées par TrajetController::create()):
 * @var array $agences Liste des agences disponibles (id_agence, nom) pour les champs SELECT.
 * * Variables de session utilisées :
 * @uses $_SESSION['flash_error'] Pour afficher les erreurs de validation ou de base de données.
 * @uses $_SESSION['flash_success'] Pour afficher la confirmation de création du trajet.
 */
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php require_once __DIR__ . '/../partials/header.php'; ?>


<div class="container mt-5">
    <h1 class="mb-4 text-center">Créer un trajet</h1>

    <?php
    /**
     * Affichage et suppression des messages flash d'erreur.
     */
    if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error'] ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php
    /**
     * Affichage et suppression des messages flash de succès.
     */
    if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_success'] ?></div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <form method="POST" action="/trajet/create" class="mx-auto" style="max-width: 500px;">

        <div class="mb-3">
            <label for="agence_depart" class="form-label">Agence de départ</label>
            <select name="agence_depart" id="agence_depart" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php
                /**
                 * Remplissage du champ de sélection de l'agence de départ avec les données $agences fournies par le contrôleur.
                 */
                foreach ($agences as $agence): ?>
                    <option value="<?= (int) $agence['id_agence'] ?>">
                        <?= htmlspecialchars($agence['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="agence_arrivee" class="form-label">Agence d'arrivée</label>
            <select name="agence_arrivee" id="agence_arrivee" class="form-select" required>
                <option value="">-- Choisir --</option>
                <?php
                /**
                 * Remplissage du champ de sélection de l'agence d'arrivée.
                 */
                foreach ($agences as $agence): ?>
                    <option value="<?= (int) $agence['id_agence'] ?>">
                        <?= htmlspecialchars($agence['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <div class="mb-3">
            <label for="date_heure_depart" class="form-label">Date et heure de départ</label>
            <input type="datetime-local" name="date_heure_depart" id="date_heure_depart" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date_heure_arrivee" class="form-label">Date et heure d'arrivée</label>
            <input type="datetime-local" name="date_heure_arrivee" id="date_heure_arrivee" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nb_places" class="form-label">Nombre de places</label>
            <input type="number" name="nb_places" id="nb_places" class="form-control" min="1" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary ">Créer le trajet</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>