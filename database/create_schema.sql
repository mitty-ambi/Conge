PRAGMA foreign_keys = OFF;
BEGIN TRANSACTION;

-- Departments
CREATE TABLE IF NOT EXISTS departments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(120) NOT NULL,
    description VARCHAR(255)
);

-- Types de congé
CREATE TABLE IF NOT EXISTS types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle VARCHAR(120) NOT NULL,
    jours_annuels INTEGER NOT NULL,
    deductible INTEGER DEFAULT 1
);

-- Employees
CREATE TABLE IF NOT EXISTS employees (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom VARCHAR(120) NOT NULL,
    prenom VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    department_id INTEGER NOT NULL,
    date_embauche DATE,
    actif INTEGER DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE ON UPDATE RESTRICT
);
CREATE UNIQUE INDEX IF NOT EXISTS idx_employees_email ON employees(email);

-- Soldes
CREATE TABLE IF NOT EXISTS soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    jours_attribues INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (employe_id) REFERENCES employees(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE UNIQUE INDEX IF NOT EXISTS soldes_employe_type_annee ON soldes(employe_id, type_conge_id, annee);

-- Congés
CREATE TABLE IF NOT EXISTS conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours INTEGER NOT NULL,
    motif TEXT,
    statut VARCHAR(20) DEFAULT 'en_attente',
    commentaire_rh TEXT,
    traite_par INTEGER,
    created_at DATETIME,
    updated_at DATETIME,
    FOREIGN KEY (employe_id) REFERENCES employees(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (traite_par) REFERENCES employees(id) ON DELETE SET NULL ON UPDATE SET NULL
);

-- Insert test data
INSERT OR IGNORE INTO departments (id, nom, description) VALUES
(1, 'IT', 'Technologie et support'),
(2, 'Finance', 'Comptabilite et budget'),
(3, 'Marketing', 'Communication et marque'),
(4, 'RH', 'Ressources humaines');

INSERT OR IGNORE INTO types_conge (id, libelle, jours_annuels, deductible) VALUES
(1, 'Annuel', 30, 1),
(2, 'Maladie', 10, 1),
(3, 'Special', 5, 1);

INSERT OR IGNORE INTO employees (id, nom, prenom, email, password, role, department_id, date_embauche, actif, created_at) VALUES
(1, 'Administrateur', 'TechMada', 'admin@techmada.mg', '$2y$10$cDgQFu0iil/IkNIVETSVducrdllhMFeit308emL1pj5DIj1xGvBNu', 'admin', 4, '2022-01-10', 1, datetime('now')),
(2, 'Rabe', 'Marie', 'rh@techmada.mg', '$2y$10$Ze/rs7xVboBfss3c6kk8w.LtM2xvdC7f2zLTFHOIJgW0/dm.KH5/W', 'rh', 4, '2020-01-15', 1, datetime('now')),
(3, 'Rakoto', 'Soa', 'employe@techmada.mg', '$2y$10$Ld7dymno4bs8qk9Sd2sSTOWttNT.LJdkSqjVKJ9ZoWjbD0PFDSpsi', 'employe', 1, '2022-03-01', 1, datetime('now'));

INSERT OR IGNORE INTO soldes (employe_id, type_conge_id, annee, jours_attribues, jours_pris) VALUES
(2, 1, 2026, 30, 0),
(2, 2, 2026, 10, 0),
(2, 3, 2026, 5, 0),
(3, 1, 2026, 30, 12),
(3, 2, 2026, 10, 0),
(3, 3, 2026, 5, 0);

INSERT OR IGNORE INTO conges (employe_id, type_conge_id, date_debut, date_fin, nb_jours, motif, statut, traite_par, created_at) VALUES
(3, 1, '2026-06-16', '2026-06-20', 5, 'Repos annuel', 'en_attente', NULL, datetime('now')),
(3, 2, '2026-06-02', '2026-06-03', 2, 'Maladie', 'approuvee', 2, datetime('now')),
(3, 3, '2026-04-05', '2026-04-05', 1, 'Evenement familial', 'refusee', 2, datetime('now'));

COMMIT;
PRAGMA foreign_keys = ON;
