CREATE TABLE Utilisateur(
    id_user INTEGER PRIMARY KEY AUTOINCREMENT,
    Nom VARCHAR(100),
    Prenom VARCHAR(100),
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

insert into DemandeConge (id_user, id_type, date_debut, date_fin) values (1, 1, '2024-07-01', '2024-07-10');
insert into DemandeStatus (id_demande, id_status, date) values (2, 1, '2024-06-01');

insert into DemandeConge (id_user, id_type, date_debut, date_fin) values (1, 1, '2024-07-01', '2024-07-10');
insert into DemandeStatus (id_demande, id_status, date) values (3, 1, '2024-06-01');

insert into DemandeConge (id_user, id_type, date_debut, date_fin) values (1, 1, '2024-07-01', '2024-07-11');
insert into DemandeStatus (id_demande, id_status, date) values (4, 1, '2024-06-01');

insert into Status (nom) values ('En attente');
insert into Status (nom) values ('Accepté');
insert into Status (nom) values ('Refusé');

insert into Solde (valeur, id_user, id_type) values (20, 1, 1);
insert into Solde (valeur, id_user, id_type) values (10, 1, 2);
insert into Solde (valeur, id_user, id_type) values (5, 1, 3);