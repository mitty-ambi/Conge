<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $year = (int) date('Y');
        $today = date('Y-m-d');

        $employeModel = new EmployeModel();
        $congeModel = new CongeModel();
        $departementModel = new DepartementModel();

        $activeEmployees = $employeModel->where('actif', 1)->countAllResults();
        $pending = $congeModel->where('statut', 'en_attente')->countAllResults();
        $approvedThisMonth = $congeModel
            ->where('statut', 'approuvee')
            ->like('date_debut', date('Y-m'), 'after')
            ->countAllResults();
        $departments = $departementModel->countAllResults();

        $recent = $congeModel
            ->select('conges.*, employees.prenom, employees.nom, types_conge.libelle')
            ->join('employees', 'employees.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->limit(3)
            ->findAll();

        $absents = $congeModel
            ->select('conges.*, employees.prenom, employees.nom, types_conge.libelle')
            ->join('employees', 'employees.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.statut', 'approuvee')
            ->where('conges.date_debut <=', $today)
            ->where('conges.date_fin >=', $today)
            ->orderBy('employees.nom', 'ASC')
            ->findAll();

        $soldeModel = new SoldeModel();
        $soldes = $soldeModel
            ->select('soldes.*, employees.nom, employees.prenom')
            ->join('employees', 'employees.id = soldes.employe_id')
            ->where('soldes.annee', $year)
            ->findAll();

        $criticalCount = 0;
        foreach ($soldes as $solde) {
            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($restant <= 2) {
                $criticalCount++;
            }
        }

        return view('admin/dashboard', [
            'title' => 'Vue d\'ensemble',
            'metrics' => [
                'employees' => $activeEmployees,
                'pending' => $pending,
                'approved_month' => $approvedThisMonth,
                'departments' => $departments,
                'absents' => count($absents),
            ],
            'recent' => $recent,
            'absents' => $absents,
            'criticalCount' => $criticalCount,
        ]);
    }
}
