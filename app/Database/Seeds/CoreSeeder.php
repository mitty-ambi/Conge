<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CoreSeeder extends Seeder
{
    public function run()
    {
        $db = db_connect();
        $year = (int) date('Y');

        // Ensure a clean state: disable foreign key checks, delete existing rows.
        // SQLite: use PRAGMA to toggle foreign_keys
        try {
            $db->query('PRAGMA foreign_keys = OFF');
        } catch (\Throwable $e) {
            // ignore if not supported
        }
        $db->query('DELETE FROM conges');
        $db->query('DELETE FROM soldes');
        $db->query('DELETE FROM employees');
        $db->query('DELETE FROM types_conge');
        $db->query('DELETE FROM departments');
        try {
            $db->query('PRAGMA foreign_keys = ON');
        } catch (\Throwable $e) {
            // ignore
        }

        $db->table('departments')->insertBatch([
            ['nom' => 'IT', 'description' => 'Technologie et support'],
            ['nom' => 'Finance', 'description' => 'Comptabilite et budget'],
            ['nom' => 'Marketing', 'description' => 'Communication et marque'],
            ['nom' => 'RH', 'description' => 'Ressources humaines'],
        ]);

        $departments = $db->table('departments')->get()->getResultArray();
        $deptByName = [];
        foreach ($departments as $dept) {
            $deptByName[$dept['nom']] = $dept['id'];
        }

        $db->table('types_conge')->insertBatch([
            ['libelle' => 'Annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['libelle' => 'Maladie', 'jours_annuels' => 10, 'deductible' => 1],
            ['libelle' => 'Special', 'jours_annuels' => 5, 'deductible' => 1],
        ]);

        $types = $db->table('types_conge')->get()->getResultArray();
        $typeByLabel = [];
        foreach ($types as $type) {
            $typeByLabel[$type['libelle']] = $type['id'];
        }

        $db->table('employees')->insertBatch([
            [
                'nom' => 'Administrateur',
                'prenom' => 'TechMada',
                'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'department_id' => $deptByName['RH'],
                'date_embauche' => '2022-01-10',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Rabe',
                'prenom' => 'Marie',
                'email' => 'rh@techmada.mg',
                'password' => password_hash('rh123', PASSWORD_DEFAULT),
                'role' => 'rh',
                'department_id' => $deptByName['RH'],
                'date_embauche' => '2020-01-15',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Rakoto',
                'prenom' => 'Soa',
                'email' => 'employe@techmada.mg',
                'password' => password_hash('emp123', PASSWORD_DEFAULT),
                'role' => 'employe',
                'department_id' => $deptByName['IT'],
                'date_embauche' => '2022-03-01',
                'actif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        $employees = $db->table('employees')->get()->getResultArray();
        $employeeByEmail = [];
        foreach ($employees as $employee) {
            $employeeByEmail[$employee['email']] = $employee;
        }

        $soldes = [];
        foreach ($employees as $employee) {
            if (!in_array($employee['role'], ['employe', 'rh'], true)) {
                continue;
            }
            foreach ($types as $type) {
                $soldes[] = [
                    'employe_id' => $employee['id'],
                    'type_conge_id' => $type['id'],
                    'annee' => $year,
                    'jours_attribues' => $type['jours_annuels'],
                    'jours_pris' => $type['libelle'] === 'Annuel' ? 12 : 0,
                ];
            }
        }
        if (!empty($soldes)) {
            foreach ($soldes as $s) {
                $db->table('soldes')->insert($s);
            }
        }

        $conges = [
            [
                'employe_id' => $employeeByEmail['employe@techmada.mg']['id'],
                'type_conge_id' => $typeByLabel['Annuel'],
                'date_debut' => $year . '-06-16',
                'date_fin' => $year . '-06-20',
                'nb_jours' => 5,
                'motif' => 'Repos annuel',
                'statut' => 'en_attente',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'employe_id' => $employeeByEmail['employe@techmada.mg']['id'],
                'type_conge_id' => $typeByLabel['Maladie'],
                'date_debut' => $year . '-06-02',
                'date_fin' => $year . '-06-03',
                'nb_jours' => 2,
                'motif' => 'Maladie',
                'statut' => 'approuvee',
                'traite_par' => $employeeByEmail['rh@techmada.mg']['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'employe_id' => $employeeByEmail['employe@techmada.mg']['id'],
                'type_conge_id' => $typeByLabel['Special'],
                'date_debut' => $year . '-04-05',
                'date_fin' => $year . '-04-05',
                'nb_jours' => 1,
                'motif' => 'Evenement familial',
                'statut' => 'refusee',
                'commentaire_rh' => 'Chevauchement detecte',
                'traite_par' => $employeeByEmail['rh@techmada.mg']['id'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        foreach ($conges as $c) {
            $db->table('conges')->insert($c);
        }
    }
}
