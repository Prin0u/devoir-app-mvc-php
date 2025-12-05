USE database_tpak;

-- TABLE : AGENCES

CREATE TABLE agences (
    id_agence INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- TABLE : UTILISATEURS

CREATE TABLE utilisateurs (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- TABLE : TRAJETS

CREATE TABLE trajets (
    id_trajet INT AUTO_INCREMENT PRIMARY KEY,
    id_agence_depart INT NOT NULL,
    id_agence_arrivee INT NOT NULL,
    date_heure_depart DATETIME NOT NULL,
    date_heure_arrivee DATETIME NOT NULL,
    nb_places_total INT NOT NULL,
    nb_places_disponibles INT NOT NULL,
    id_user_createur INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (id_agence_depart) REFERENCES agences(id_agence),
    FOREIGN KEY (id_agence_arrivee) REFERENCES agences(id_agence),
    FOREIGN KEY (id_user_createur) REFERENCES utilisateurs (id_user),

    CHECK (id_agence_depart <> id_agence_arrivee),
    CHECK (nb_places_total >= 1),
    CHECK (nb_places_disponibles BETWEEN 0 AND nb_places_total)
);
