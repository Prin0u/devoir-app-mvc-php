CREATE TYPE user_role AS ENUM ('user', 'admin');

-- TABLE : AGENCES

CREATE TABLE agences (
    id_agence SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- TABLE : UTILISATEURS

CREATE TABLE utilisateurs (
    id_user SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role user_role DEFAULT 'user'
);

-- TABLE : TRAJETS

CREATE TABLE trajets (
    id_trajet SERIAL PRIMARY KEY,
    id_agence_depart INT NOT NULL,
    id_agence_arrivee INT NOT NULL,
    date_heure_depart TIMESTAMP NOT NULL,
    date_heure_arrivee TIMESTAMP NOT NULL,
    nb_places_total INT NOT NULL,
    nb_places_disponibles INT NOT NULL,
    id_user_createur INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_agence_depart) REFERENCES agences(id_agence),
    FOREIGN KEY (id_agence_arrivee) REFERENCES agences(id_agence),
    FOREIGN KEY (id_user_createur) REFERENCES utilisateurs (id_user),

    CONSTRAINT check_agences CHECK (id_agence_depart <> id_agence_arrivee),
    CONSTRAINT check_places_total CHECK (nb_places_total >= 1),
    CONSTRAINT check_places_dispo CHECK (nb_places_disponibles BETWEEN 0 AND nb_places_total)
);

-- GESTION DE LA MISE A JOUR DE LA DATE
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_trajets_modtime
    BEFORE UPDATE ON trajets
    FOR EACH ROW
    EXECUTE PROCEDURE update_updated_at_column();