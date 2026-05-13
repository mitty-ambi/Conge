-- Supprimer et recréer la base
DROP DATABASE IF EXISTS Notes;
CREATE DATABASE Notes;
USE Notes;

-- Création des tables
CREATE TABLE Eleves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    Matricule VARCHAR(100),
    Nom VARCHAR(100),
    Prenom VARCHAR(100),
    Parcours VARCHAR(50)
);

CREATE TABLE UE (
    id_ue INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    id_semestre INT
);

CREATE TABLE Matiere(
    id_matiere INT PRIMARY KEY AUTO_INCREMENT,
    codeMatiere VARCHAR(100),
    nom VARCHAR(100),
    credit INT,
    id_ue INT
);

CREATE TABLE Note (
    id_note INT PRIMARY KEY AUTO_INCREMENT,
    valeur DECIMAL(10,2),
    id_eleve INT,
    id_matiere INT
);

CREATE TABLE Semestre (
    id_semestre INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(10)
);

CREATE TABLE SemestreFille (
    id_fille INT PRIMARY KEY AUTO_INCREMENT,
    id_mere INT,
    id_matiere INT
);

-- =====================================================
-- 1. Insertion des semestres
-- =====================================================
INSERT INTO Semestre (nom) VALUES ('Semestre 3'), ('Semestre 4');

-- =====================================================
-- 2. Insertion des UE (Unités d'Enseignement)
-- =====================================================
INSERT INTO UE (nom, id_semestre) VALUES
-- Semestre 3 (id_semestre = 1)
('Programmation et Algorithmique', 1),
('Bases de Données et Réseaux', 1),
('Mathématiques et Gestion', 1),
-- Semestre 4 (id_semestre = 2)
('Développement et SIG', 2),
('Interface et Données', 2),
('Projets Spécialisés', 2),
('Mathématiques Appliquées', 2);

-- =====================================================
-- 3. Insertion des matières avec UE
-- =====================================================
INSERT INTO Matiere (codeMatiere, nom, credit, id_ue) VALUES
-- Semestre 3 (tronc commun)
('INF201', 'Programmation orientée objet', 6, 1),
('INF202', 'Bases de données objets', 6, 2),
('INF203', 'Programmation système', 4, 1),
('INF208', 'Réseaux informatiques', 6, 2),
('MTH201', 'Méthodes numériques', 4, 3),
('ORG201', 'Bases de gestion', 4, 3),

-- Semestre 4 - Parcours Développement
('INF204', 'Système d’information géographique', 6, 4),
('INF205', 'Système d’information', 6, 5),
('INF206', 'Interface Homme/Machine', 6, 5),
('INF207', 'Eléments d’algorithmique', 6, 1),
('INF210', 'Mini-projet de développement', 10, 6),

-- Semestre 4 - Parcours BD/Réseaux
('INF211', 'Mini-projet de bases de données et/ou de réseaux', 10, 6),

-- Semestre 4 - Parcours Web/Design
('INF209', 'Web dynamique', 6, 5),
('INF212', 'Mini-projet de Web et design', 10, 6),

-- Semestre 4 - Maths communes
('MTH202', 'Analyse des données', 4, 7),
('MTH203', 'MAO', 4, 7),
('MTH204', 'Géométrie', 4, 7),
('MTH205', 'Equations différentielles', 4, 7),
('MTH206', 'Optimisation', 4, 7);

-- =====================================================
-- 4. Liaison matières ↔ semestre
-- =====================================================
-- Semestre 3 (id_mere = 1)
INSERT INTO SemestreFille (id_mere, id_matiere) VALUES
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF201')),
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF202')),
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF203')),
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF208')),
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH201')),
(1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'ORG201'));

-- Semestre 4 (id_mere = 2)
INSERT INTO SemestreFille (id_mere, id_matiere) VALUES
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF204')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF206')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF207')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF209')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF210')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF211')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF212')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH202')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH203')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH204')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH205')),
(2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH206'));

-- =====================================================
-- 5. Insertion des 5 étudiants
-- =====================================================
INSERT INTO Eleves (Matricule, Nom, Prenom, Parcours) VALUES
('E001', 'Rakoto', 'Jean', 'Developpement'),
('E002', 'Rabe', 'Miary', 'BD_Reseaux'),
('E003', 'Andriamaholy', 'Tahiana', 'Web_Design'),
('E004', 'Randria', 'Faneva', 'Developpement'),
('E005', 'Ravelo', 'Hery', 'BD_Reseaux');

-- =====================================================
-- 6. Insertion des notes SEMESTRE 3
-- =====================================================
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
-- Jean Rakoto (1)
(14.50, 1, 1), (12.00, 1, 2), (10.50, 1, 3), (15.00, 1, 4), (13.00, 1, 5), (11.00, 1, 6),
-- Miary Rabe (2)
(16.00, 2, 1), (14.00, 2, 2), (13.50, 2, 3), (17.00, 2, 4), (15.00, 2, 5), (14.00, 2, 6),
-- Tahiana Andriamaholy (3)
(09.50, 3, 1), (10.00, 3, 2), (11.00, 3, 3), (08.50, 3, 4), (12.00, 3, 5), (10.00, 3, 6),
-- Faneva Randria (4)
(18.00, 4, 1), (17.50, 4, 2), (16.00, 4, 3), (19.00, 4, 4), (15.50, 4, 5), (17.00, 4, 6),
-- Hery Ravelo (5)
(13.00, 5, 1), (11.50, 5, 2), (12.00, 5, 3), (14.00, 5, 4), (10.50, 5, 5), (12.50, 5, 6);

-- =====================================================
-- 7. Insertion des notes SEMESTRE 4
-- =====================================================

-- Parcours Développement - Jean Rakoto (1) - maths: MTH204
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
(13.00, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF204')),
(14.00, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(12.50, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF206')),
(11.00, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF207')),
(15.00, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF210')),
(14.00, 1, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH204'));

-- Parcours Développement - Faneva Randria (4) - maths: MTH205
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
(17.00, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF204')),
(18.50, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(16.00, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF206')),
(17.00, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF207')),
(19.00, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF210')),
(18.00, 4, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH205'));

-- Parcours BD_Réseaux - Miary Rabe (2) - choix: INF206, maths: MTH202
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
(16.00, 2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(14.50, 2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF206')),
(17.00, 2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF211')),
(15.00, 2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH202')),
(14.00, 2, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH203'));

-- Parcours BD_Réseaux - Hery Ravelo (5) - choix: INF204, maths: MTH205
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
(13.00, 5, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(12.00, 5, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF204')),
(14.00, 5, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF211')),
(12.50, 5, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH205')),
(11.00, 5, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH203'));

-- Parcours Web_Design - Tahiana Andriamaholy (3) - choix: INF205, maths: MTH206
INSERT INTO Note (valeur, id_eleve, id_matiere) VALUES
(11.00, 3, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF205')),
(10.00, 3, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF209')),
(12.00, 3, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'INF212')),
(11.00, 3, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH206')),
(10.50, 3, (SELECT id_matiere FROM Matiere WHERE codeMatiere = 'MTH203'));

-- =====================================================
-- 8. Requête de test
-- =====================================================
SELECT 
    Eleves.id,
    Eleves.Matricule,
    Eleves.Nom,
    Eleves.Prenom,
    Matiere.codeMatiere,
    Matiere.nom as matiere_nom,
    Matiere.credit,
    Note.valeur,
    UE.nom as UE_nom,
    Semestre.nom as semestre_nom
FROM Note 
JOIN Matiere ON Note.id_matiere = Matiere.id_matiere 
JOIN Eleves ON Eleves.id = Note.id_eleve 
JOIN UE ON UE.id_ue = Matiere.id_ue
JOIN SemestreFille ON SemestreFille.id_matiere = Matiere.id_matiere
JOIN Semestre ON Semestre.id_semestre = SemestreFille.id_mere
WHERE Eleves.id = 1;