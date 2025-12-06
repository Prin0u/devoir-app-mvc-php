<header>
    <div class="logo">
        <h1><a href="/">Touche Pas Au Klaxon</a></h1>
    </div>
    <div class="nav">
        <?php if (isset($SESSION['user'])): ?>
            <a href="/trajet/create">Créer un trajet</a>
            <span><?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?></span>
            <a href="/logout">Déconnexion</a>
        <?php else: ?>
            <a href="/login">Connexion</a>
        <?php endif; ?>
    </div>
</header>