<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\SoldeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $employeId = (int) $this->session->get('user_id');
        $year = (int) date('Y');

        $congeModel = new CongeModel();
        $pending = $congeModel->where('employe_id', $employeId)->where('statut', 'en_attente')->countAllResults();
        $approved = $congeModel->where('employe_id', $employeId)->where('statut', 'approuvee')->countAllResults();
        $refused = $congeModel->where('employe_id', $employeId)->where('statut', 'refusee')->countAllResults();

        $soldeModel = new SoldeModel();
        $soldes = $soldeModel
            ->select('soldes.*, types_conge.libelle, types_conge.jours_annuels, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $employeId)
            ->where('soldes.annee', $year)
            ->orderBy('types_conge.libelle')
            ->findAll();

        $totalAttribues = 0;
        $totalRestants = 0;
        foreach ($soldes as &$solde) {
            $solde['restant'] = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            $solde['percent'] = $solde['jours_attribues'] > 0
                ? (int) round(($solde['restant'] / $solde['jours_attribues']) * 100)
                : 0;
            $totalAttribues += (int) $solde['jours_attribues'];
            $totalRestants += (int) $solde['restant'];
        }
        unset($solde);

        $recent = $congeModel
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeId)
            ->orderBy('conges.date_debut', 'DESC')
            ->limit(3)
            ->findAll();

        return view('employe/dashboard', [
            'title' => 'Tableau de bord',
            'metrics' => [
                'pending' => $pending,
                'approved' => $approved,
                'refused' => $refused,
                'remaining' => $totalRestants,
                'total' => $totalAttribues,
            ],
            'soldes' => $soldes,
            'recent' => $recent,
        ]);
    }
}
