<?php

/**
 * Fichier : Views/trajet/edit.php
 * Rôle : Vue du formulaire d'édition d'un trajet existant.
 * Description : 
 * - Affiche un formulaire pré-rempli avec les données actuelles du trajet sélectionné.
 * - Permet à l'utilisateur de modifier les agences, les dates/heures et le nombre de places.
 * - Gère l'affichage des messages flash d'erreur.
 * * Variables de contexte attendues (injectées par TrajetController::edit()):
 * @var array $trajet Détails du trajet à modifier (doit inclure id_trajet, id_agence_depart, date_heure_depart, etc.).
 * @var array $agences Liste complète des agences disponibles (id_agence, nom) pour les champs SELECT.
 * * Variables de session utilisées :
 * @uses $_SESSION['flash_error'] Pour afficher les erreurs de validation ou de base de données.
 */
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">

    <h1 class="text-center mb-4">Modifier le trajet</h1>

    <?php
    /**
     * Affichage et suppression des messages flash d'erreur provenant du contrôleur.
     */
    if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['flash_error'];
            unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mx-auto" style="max-width: 550px;">
        <div class="card-body">

            <form method="POST" action="/trajet/update/<?= $trajet['id_trajet'] ?>">

                <div class="mb-3">
                    <label class="form-label">Agence de départ</label>
                    <select name="agence_depart" class="form-select" required>
                        <?php
                        /**
                         * Remplissage du SELECT de départ. 
                         * L'option correspondante à $trajet['id_agence_depart'] est marquée 'selected'.
                         */
                        foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id_agence'] ?>"
                                <?= $trajet['id_agence_depart'] == $agence['id_agence'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Agence d'arrivée</label>
                    <select name="agence_arrivee" class="form-select" required>
                        <?php
                        /**
                         * Remplissage du SELECT d'arrivée.
                         */
                        foreach ($agences as $agence): ?>
                            <option value="<?= $agence['id_agence'] ?>"
                                <?= $trajet['id_agence_arrivee'] == $agence['id_agence'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date & heure de départ</label>
                    <input type="datetime-local" class="form-control" name="date_heure_depart"
                        value="<?= date('Y-m-d\TH:i', strtotime($trajet['date_heure_depart'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date & heure d'arrivée</label>
                    <input type="datetime-local" class="form-control" name="date_heure_arrivee"
                        value="<?= date('Y-m-d\TH:i', strtotime($trajet['date_heure_arrivee'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre de places</label>
                    <input type="number" class="form-control" name="nb_places"
                        value="<?= $trajet['nb_places_total'] ?>" min="1" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Modifier le trajet
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>