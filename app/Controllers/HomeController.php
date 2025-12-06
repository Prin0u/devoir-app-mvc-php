<?php
namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;

class HomeController extends Controller
{
    public function index()
    {
        // Connexion à la BDD
        $pdo = Database::getInstance();

        // Récupération des trajets avec places disponibles et dates futures
        $stmt = $pdo->prepare("
            SELECT t.*, 
                   a1.nom AS depart, 
                   a2.nom AS arrivee
            FROM trajets t
            JOIN agences a1 ON t.id_agence_depart = a1.id_agence
            JOIN agences a2 ON t.id_agence_arrivee = a2.id_agence
            WHERE t.nb_places_disponibles > 0 
              AND t.date_heure_depart > NOW()
            ORDER BY t.date_heure_depart ASC
        ");

        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Affichage de la vue
        $this->render('home/index', ['trajets' => $trajets]);
    }
}
