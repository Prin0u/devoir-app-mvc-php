<?php

/**
 * Fichier : HomeController.php
 * Rôle : Contrôleur gérant la page d'accueil de l'application.
 * Il récupère la liste des trajets disponibles et les enrichit avec les données
 * des utilisateurs pour l'affichage dans la vue.
 * @package Prin0u\DevoirAppMvcPhp\Controllers
 */

namespace Prin0u\DevoirAppMvcPhp\Controllers;

use Prin0u\DevoirAppMvcPhp\Core\Controller;
use Prin0u\DevoirAppMvcPhp\Core\Database;

class HomeController extends Controller
{
    /**
     * Affiche la liste des trajets disponibles sur la page d'accueil.
     * La méthode enrichit la liste des trajets avec les données des créateurs 
     * et les informations nécessaires à la modale.
     * Route: GET /
     * @return void
     */
    public function index()
    {
        // Connexion à la BDD
        $pdo = Database::getInstance();

        // Récupération des trajets avec places disponibles, dates futures ET données du créateur.
        // On sélectionne les informations de l'utilisateur (u) et des agences (a1, a2) via des jointures.
        $stmt = $pdo->prepare("
            SELECT t.*, 
                   a1.nom AS depart, 
                   a2.nom AS arrivee,
                   u.nom AS user_nom,           
                   u.prenom AS user_prenom,     
                   u.email AS user_email,       
                   u.telephone AS user_telephone  
            FROM trajets t
            JOIN agences a1 ON t.id_agence_depart = a1.id_agence
            JOIN agences a2 ON t.id_agence_arrivee = a2.id_agence
            JOIN utilisateurs u ON t.id_user_createur = u.id_user 
            WHERE t.nb_places_disponibles > 0 
              AND t.date_heure_depart > NOW()
            ORDER BY t.date_heure_depart ASC
        ");

        $stmt->execute();
        $trajets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // La liste des trajets est maintenant enrichie avec les clés user_nom, user_prenom, etc.
        // La Vue n'aura plus besoin d'appeler de méthode de contrôleur pour récupérer ces données.

        // Affichage de la vue
        $this->render('home/index', ['trajets' => $trajets]);
    }

    /**
     * Méthode utilitaire (anciennement dans TrajetController) pour récupérer les infos complètes d'un utilisateur.
     * Cette méthode n'est plus strictement nécessaire pour 'index' car les données sont jointes, 
     * mais elle est conservée pour l'exemple. Si elle est utilisée ailleurs, elle devrait être dans un UserRepository.
     * @param int $userId L'ID de l'utilisateur.
     * @return array Informations utilisateur.
     */
    public function getUserInfo(int $userId): array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT nom, prenom, email, telephone FROM utilisateurs WHERE id_user = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Méthode utilitaire (anciennement dans TrajetController) pour compter les places totales proposées par un utilisateur.
     * @param int $userId L'ID de l'utilisateur.
     * @return int Nombre total de places disponibles sur tous les trajets futurs de cet utilisateur.
     */
    public function getNbPlacesByUser(int $userId): int
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT SUM(nb_places_disponibles) 
            FROM trajets 
            WHERE id_user_createur = ? AND date_heure_depart > NOW()
        ");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}
