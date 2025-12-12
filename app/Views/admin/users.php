<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
/**
 * Vue : Liste des utilisateurs dans l'interface d'administration.
 * ----------------------------------------------------
 * Cette page affiche les informations de base de tous les utilisateurs enregistrés
 * (nom, prénom, email, téléphone) à des fins de consultation par l'administrateur.
 *
 * Variables injectées :
 * - $users (array): Liste des utilisateurs, chaque élément contenant :
 * ['nom', 'prenom', 'email', 'telephone'].
 */
require __DIR__ . '/../partials/header.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des utilisateurs</h1>

    <?php if (empty($users)) : ?>
        <div class="alert alert-info text-center">
            Aucun utilisateur trouvé.
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['telephone']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>