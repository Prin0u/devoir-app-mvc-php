# Touche pas au klaxon : Plateforme de Covoiturage

## 📌 Description du Projet

Touche pas au klaxon est une application web de covoiturage interne développée en PHP selon le modèle **MVC (Modèle-Vue-Contrôleur)**.

Elle permet aux utilisateurs de créer, modifier et supprimer des trajets entre différentes agences, tout en offrant une interface propre et sécurisée pour la consultation des trajets, y compris pour les utilisateurs déconnectés (visiteurs).

### Fonctionnalités Clés

- **Gestion des Trajets :** Création et affichage des trajets futurs avec gestion des agences de départ/arrivée.
- **Sécurité et Sessions :** Système d'authentification et de déconnexion utilisant les sessions PHP.
- **Protection des Données :** Masquage des coordonnées privées (email, téléphone) pour les visiteurs.
- **Architecture MVC :** Séparation claire des préoccupations (logique, données, présentation) via des Contrôleurs, Vues et un Modèle de base de données simple.
- **Interface Utilisateur :** Design réactif basé sur Bootstrap 5.

## 🛠️ Technologies Utilisées

| Technologie         | Rôle                                            |
| :------------------ | :---------------------------------------------- |
| **PHP**             | Langage de programmation principal.             |
| **MySQL / MariaDB** | Système de gestion de base de données.          |
| **Composer**        | Gestionnaire de dépendances pour l'autoloading. |
| **Bootstrap 5**     | Framework CSS pour la mise en page et le style. |
| **Buki/Router**     | Composant pour le routage des requêtes.         |

## 🚀 Installation et Configuration

Pour démarrer le projet en local, suivez les étapes ci-dessous.

### Prérequis

- Serveur Web (Apache)
- PHP
- MySQL ou MariaDB
- Composer

### 1. Cloner le Dépôt

```bash
git clone https://github.com/Prin0u/devoir-app-mvc-php
cd devoir-app-mvc-php
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configuration de la base de données

Créér une base de données vide et l'alimenter avec ces fichiers :

- [schema.sql](database/schema.sql)
- [seed.sql](database/seed.sql)

Les identifiants pour se connecter à la base de données sont dans ce fichier : [database.php](config/database.php)

## 4. Démarrage de l'application

Faire la commande dans le dossier racine 'devoir-app-mvc-php :

```bash
php -S localhost:8000 -t public
```

L'application sera accessible à l'adresse : http://localhost:8000
