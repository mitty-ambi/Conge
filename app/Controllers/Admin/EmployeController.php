<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $year = (int) date('Y');
        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();
        $typeModel = new TypeCongeModel();
        $soldeModel = new SoldeModel();

        $departments = $departementModel->orderBy('nom', 'ASC')->findAll();
        $types = $typeModel->orderBy('libelle', 'ASC')->findAll();

        $employees = $employeModel
            ->select('employees.*, departments.nom as departement')
            ->join('departments', 'departments.id = employees.department_id', 'left')
            ->orderBy('employees.nom', 'ASC')
            ->findAll();

        $soldes = $soldeModel
            ->select('soldes.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $year)
            ->findAll();

        $soldeMap = [];
        foreach ($soldes as $solde) {
            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            $soldeMap[$solde['employe_id']][$solde['libelle']] = $restant . ' / ' . $solde['jours_attribues'] . ' j';
        }

        return view('admin/employes', [
            'title' => 'Gestion des employes',
            'employees' => $employees,
            'departments' => $departments,
            'types' => $types,
            'soldeMap' => $soldeMap,
            'year' => $year,
        ]);
    }

    public function store()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $rules = [
            'prenom' => 'required|min_length[2]',
            'nom' => 'required|min_length[2]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[employe,rh,admin]',
            'department_id' => 'required|integer',
            'date_embauche' => 'permit_empty|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $email = (string) $this->request->getPost('email');
        if ($employeModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Email deja utilise.');
        }

        $data = [
            'prenom' => (string) $this->request->getPost('prenom'),
            'nom' => (string) $this->request->getPost('nom'),
            'email' => $email,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => (string) $this->request->getPost('role'),
            'department_id' => (int) $this->request->getPost('department_id'),
            'date_embauche' => (string) $this->request->getPost('date_embauche'),
            'actif' => 1,
        ];

        $employeModel->insert($data);
        $employeId = (int) $employeModel->getInsertID();

        $typeModel = new TypeCongeModel();
        $types = $typeModel->findAll();
        $year = (int) date('Y');

        $soldes = [];
        foreach ($types as $type) {
            $soldes[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $type['id'],
                'annee' => $year,
                'jours_attribues' => (int) $type['jours_annuels'],
                'jours_pris' => 0,
            ];
        }

        if (!empty($soldes)) {
            $soldeModel = new SoldeModel();
            $soldeModel->insertBatch($soldes);
        }

        return redirect()->back()->with('success', 'Employe cree et soldes initialises.');
    }

    public function toggle(int $id)
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $employeModel = new EmployeModel();
        $employee = $employeModel->find($id);
        if (!$employee) {
            return redirect()->back()->with('error', 'Employe introuvable.');
        }

        $newStatus = $employee['actif'] ? 0 : 1;
        $employeModel->update($id, ['actif' => $newStatus]);

        return redirect()->back()->with('success', 'Statut employe mis a jour.');
    }

    public function update(int $id)
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $rules = [
            'prenom' => 'required|min_length[2]',
            'nom' => 'required|min_length[2]',
            'role' => 'required|in_list[employe,rh,admin]',
            'department_id' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'prenom' => (string) $this->request->getPost('prenom'),
            'nom' => (string) $this->request->getPost('nom'),
            'role' => (string) $this->request->getPost('role'),
            'department_id' => (int) $this->request->getPost('department_id'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel = new EmployeModel();
        $employeModel->update($id, $data);

        return redirect()->back()->with('success', 'Employe mis a jour.');
    }
}
