<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php require __DIR__ . '/../partials/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<div class="container mt-5">
    <h1 class="text-center mb-4">Liste des agences</h1>

    <?php if (empty($agences)) : ?>
        <div class="alert alert-info text-center">
            Aucune agence trouvé.
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Agences</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agences as $agence): ?>
                        <tr>
                            <td><?= htmlspecialchars($agence['nom']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>