<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php
    /**
     * Vue : Formulaire de connexion.
     * ----------------------------------------------------
     * Cette page présente le formulaire pour l'authentification des utilisateurs.
     * Le formulaire utilise la méthode POST et soumet les données (email, mot de passe)
     * à l'action '/login' (gérée par AuthController::loginPost()).
     *
     * Variables utilisées (via la session) :
     * - $_SESSION['flash_error'] : Message d'erreur affiché en cas d'échec de la connexion.
     */
    // Inclusion des éléments de l'en-tête (balises de script, etc.)
    require_once __DIR__ . '/../partials/header.php';
    ?>

    <title>Connexion</title>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <h2 class="text-center mb-4">Connexion</h2>

                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <form method="POST" action="/login" class="card p-4 shadow">

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary">
                            Se connecter
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>

</html>