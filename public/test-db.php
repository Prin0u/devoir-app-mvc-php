<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// Charger la config BDD
$config = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
        $config['user'],
        $config['password']
    );
    echo "<p>Connexion BDD OK!</p>";

    // Récupérer les agences pour tester
    $stmt = $pdo->query("SELECT * FROM agences");
    $agences = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($agences)) {
        echo "<p>Aucune agence trouvée</p>";
    } else {
        echo "<ul>";
        foreach ($agences as $agence) {
            echo "<li>" . htmlspecialchars($agence['nom']) . "</li>";
        }
        echo "</ul>";
    }

} catch (PDOException $e) {
    echo "Erreur BDD : " . $e->getMessage();
}
