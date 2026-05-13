CREATE TABLE Utilisateur(
    id_user INTEGER PRIMARY KEY AUTOINCREMENT,
    Nom VARCHAR(100),
    Prenom VARCHAR(100),
    Role VARCHAR(50),
    CHECK (Role IN ('responsable rh', 'employé', 'admin'))
);

CREATE TABLE Type(
    id_type INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50)
);

CREATE TABLE Solde(
    id_solde INTEGER PRIMARY KEY AUTOINCREMENT,
    valeur INTEGER,
    id_user INTEGER,
    id_type INTEGER,
    FOREIGN KEY (id_user) REFERENCES Utilisateur(id_user),
    FOREIGN KEY (id_type) REFERENCES Type(id_type)
);

CREATE TABLE DemandeConge(
    id_demande INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER,
    id_type INTEGER,
    FOREIGN KEY (id_user) REFERENCES Utilisateur(id_user),
    FOREIGN KEY (id_type) REFERENCES Type(id_type)
);