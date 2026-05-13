CREATE TABLE Utilisateur(
    id_user INTEGER PRIMARY KEY AUTOINCREMENT,
    Nom VARCHAR(100),
    Prenom VARCHAR(100),
    email VARCHAR(100),
    mdp VARCHAR(100),
    Role VARCHAR(50),
    id_departement INT,
    CHECK (Role IN ('responsable rh', 'employé', 'admin'))
);

CREATE TABLE Departement (
    id_departement INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(10)
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
    date_debut DATE,
    date_fin DATE,
    FOREIGN KEY (id_user) REFERENCES Utilisateur(id_user),
    FOREIGN KEY (id_type) REFERENCES Type(id_type)
);

ALTER TABLE DemandeConge ADD motif VARCHAR(100)

CREATE TABLE Status(
    id_status INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(50)
);

CREATE TABLE DemandeStatus(
    id_demande_status INTEGER PRIMARY KEY AUTOINCREMENT,
    id_demande INTEGER,
    id_status INTEGER,
    date DATE
);

CREATE TABLE DemandeDecision(
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_demande INTEGER,
    id_user INTEGER
);

-- Insérer les départements
INSERT INTO Departement (nom) VALUES ('IT');
INSERT INTO Departement (nom) VALUES ('RH');
INSERT INTO Departement (nom) VALUES ('Finance');
INSERT INTO Departement (nom) VALUES ('Marketing');

-- Insérer les utilisateurs (avec emails et mots de passe)
-- Mot de passe: "emp123" pour tous les employés, "rh123" pour RH, "admin123" pour admin
INSERT INTO Utilisateur (Nom, Prenom, email, mdp, Role, id_departement) 
VALUES ('Rakoto', 'Soa', 'soa@techmada.mg', 'emp123', 'employé', 1);

INSERT INTO Utilisateur (Nom, Prenom, email, mdp, Role, id_departement) 
VALUES ('Rabe', 'Marie', 'marie@techmada.mg', 'rh123', 'responsable rh', 2);

INSERT INTO Utilisateur (Nom, Prenom, email, mdp, Role, id_departement) 
VALUES ('Admin', 'System', 'admin@techmada.mg', 'admin123', 'admin', 1);

INSERT INTO Utilisateur (Nom, Prenom, email, mdp, Role, id_departement) 
VALUES ('Fidy', 'Tsiry', 'tsiry@techmada.mg', 'emp123', 'employé', 3);

INSERT INTO Utilisateur (Nom, Prenom, email, mdp, Role, id_departement) 
VALUES ('Raso', 'Miora', 'miora@techmada.mg', 'emp123', 'employé', 4);

-- Insérer les types de congé
INSERT INTO Type (nom) VALUES ('Congé annuel');
INSERT INTO Type (nom) VALUES ('Congé maladie');
INSERT INTO Type (nom) VALUES ('Congé spécial');

-- Insérer les soldes (valeur, id_user, id_type)
-- Soa (id=1)
INSERT INTO Solde (valeur, id_user, id_type) VALUES (18, 1, 1);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (8, 1, 2);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (1, 1, 3);

-- Marie (id=2)
INSERT INTO Solde (valeur, id_user, id_type) VALUES (25, 2, 1);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (10, 2, 2);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (5, 2, 3);

-- Tsiry (id=4)
INSERT INTO Solde (valeur, id_user, id_type) VALUES (15, 4, 1);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (3, 4, 2);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (2, 4, 3);

-- Miora (id=5)
INSERT INTO Solde (valeur, id_user, id_type) VALUES (20, 5, 1);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (10, 5, 2);
INSERT INTO Solde (valeur, id_user, id_type) VALUES (5, 5, 3);

-- Insérer les statuts
INSERT INTO Status (nom) VALUES ('en attente');
INSERT INTO Status (nom) VALUES ('approuvé');
INSERT INTO Status (nom) VALUES ('refusé');
INSERT INTO Status (nom) VALUES ('annulé');

-- Insérer les demandes de congé
-- Demande 1: Soa - Annuel (en attente)
INSERT INTO DemandeConge (id_user, id_type, date_debut, date_fin) 
VALUES (1, 1, '2025-07-10', '2025-07-20');

-- Demande 2: Soa - Maladie (approuvé)
INSERT INTO DemandeConge (id_user, id_type, date_debut, date_fin) 
VALUES (1, 2, '2025-06-02', '2025-06-03');

-- Demande 3: Tsiry - Maladie (en attente)
INSERT INTO DemandeConge (id_user, id_type, date_debut, date_fin) 
VALUES (4, 2, '2025-07-05', '2025-07-06');

-- Demande 4: Miora - Annuel (approuvé)
INSERT INTO DemandeConge (id_user, id_type, date_debut, date_fin) 
VALUES (5, 1, '2025-08-01', '2025-08-15');

-- Demande 5: Soa - Spécial (refusé)
INSERT INTO DemandeConge (id_user, id_type, date_debut, date_fin) 
VALUES (1, 3, '2025-05-10', '2025-05-10');

-- Insérer l'historique des statuts
-- Demande 1 (en attente)
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (1, 1, '2025-06-20');

-- Demande 2 (approuvé)
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (2, 1, '2025-05-20');
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (2, 2, '2025-05-21');

-- Demande 3 (en attente)
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (3, 1, '2025-06-25');

-- Demande 4 (approuvé)
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (4, 1, '2025-06-10');
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (4, 2, '2025-06-11');

-- Demande 5 (refusé)
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (5, 1, '2025-05-05');
INSERT INTO DemandeStatus (id_demande, id_status, date) VALUES (5, 3, '2025-05-06');

-- Insérer les décisions (qui a approuvé/refusé)
-- Demande 2 approuvée par Marie
INSERT INTO DemandeDecision (id_demande, id_user) VALUES (2, 2);

-- Demande 4 approuvée par Marie
INSERT INTO DemandeDecision (id_demande, id_user) VALUES (4, 2);

-- Demande 5 refusée par Marie
INSERT INTO DemandeDecision (id_demande, id_user) VALUES (5, 2);
insert into Departement (nom) values ('Informatique');
insert into Departement (nom) values ('Ressources Humaines');
insert into Departement (nom) values ('Comptabilité');

insert into Type (nom) values ('Congé Annuel');
insert into Type (nom) values ('Congé Maladie');
insert into Type (nom) values ('Congé Sans Solde');

insert into Utilisateur (Nom, Prenom, Role, id_departement) values ('Doe', 'John', 'employé', 1);
insert into Utilisateur (Nom, Prenom, Role, id_departement) values ('Smith', 'Jane', 'responsable rh', 2);
insert into Utilisateur (Nom, Prenom, Role, id_departement) values ('Admin', 'User', 'admin', 0);

insert into DemandeConge (id_user, id_type, date_debut, date_fin) values (1, 1, '2024-07-01', '2024-07-10');
insert into DemandeStatus (id_demande, id_status, date) values (1, 1, '2024-06-01');
