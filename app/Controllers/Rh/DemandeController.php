<?php

namespace App\Controllers\Rh;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class DemandeController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['rh'])) {
            return $response;
        }

        $statut = (string) $this->request->getGet('statut');
        $departmentId = (string) $this->request->getGet('department');
        $refuseId = (int) $this->request->getGet('refuse_id');

        $congeModel = new CongeModel();
        $builder = $congeModel
            ->select('conges.*, types_conge.libelle as type_libelle, types_conge.deductible, employees.prenom, employees.nom, employees.department_id, departments.nom as departement')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->join('employees', 'employees.id = conges.employe_id')
            ->join('departments', 'departments.id = employees.department_id', 'left');

        if ($statut !== '') {
            $builder->where('conges.statut', $statut);
        }
        if ($departmentId !== '') {
            $builder->where('employees.department_id', (int) $departmentId);
        }

        $conges = $builder->orderBy('conges.created_at', 'DESC')->findAll();

        $year = (int) date('Y');
        $employeeIds = array_values(array_unique(array_map(static function ($row) {
            return $row['employe_id'];
        }, $conges)));

        $soldeMap = [];
        if (!empty($employeeIds)) {
            $soldeModel = new SoldeModel();
            $soldes = $soldeModel
                ->whereIn('employe_id', $employeeIds)
                ->where('annee', $year)
                ->findAll();

            foreach ($soldes as $solde) {
                $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
                $soldeMap[$solde['employe_id']][$solde['type_conge_id']] = $restant;
            }
        }

        foreach ($conges as &$conge) {
            $restant = $soldeMap[$conge['employe_id']][$conge['type_conge_id']] ?? null;
            $conge['solde_restant'] = $restant;
            $conge['solde_insuffisant'] = $restant !== null && $restant < (int) $conge['nb_jours'];
        }
        unset($conge);

        $departementModel = new DepartementModel();
        $departments = $departementModel->orderBy('nom', 'ASC')->findAll();

        $refuseDemande = null;
        if ($refuseId > 0) {
            foreach ($conges as $conge) {
                if ((int) $conge['id'] === $refuseId) {
                    $refuseDemande = $conge;
                    break;
                }
            }
        }

        return view('rh/index', [
            'title' => 'Demandes a traiter',
            'conges' => $conges,
            'departments' => $departments,
            'filters' => [
                'statut' => $statut,
                'department' => $departmentId,
            ],
            'refuseDemande' => $refuseDemande,
        ]);
    }

    public function approve(int $id)
    {
        if ($response = $this->requireRole(['rh'])) {
            return $response;
        }

        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);
        if (!$conge || $conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Demande invalide ou deja traitee.');
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find((int) $conge['type_conge_id']);
        if (!$type) {
            return redirect()->back()->with('error', 'Type de conge introuvable.');
        }

        if ((int) $type['deductible'] === 1) {
            $year = (int) date('Y', strtotime($conge['date_debut']));
            $soldeModel = new SoldeModel();
            $solde = $soldeModel
                ->where('employe_id', (int) $conge['employe_id'])
                ->where('type_conge_id', (int) $conge['type_conge_id'])
                ->where('annee', $year)
                ->first();

            if (!$solde) {
                return redirect()->back()->with('error', 'Solde introuvable.');
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ((int) $conge['nb_jours'] > $restant) {
                return redirect()->back()->with('error', 'Solde insuffisant pour approuver.');
            }

            $soldeModel->update($solde['id'], [
                'jours_pris' => (int) $solde['jours_pris'] + (int) $conge['nb_jours'],
            ]);
        }

        $commentaire = (string) $this->request->getPost('commentaire_rh');
        $congeModel->update($id, [
            'statut' => 'approuvee',
            'commentaire_rh' => $commentaire !== '' ? $commentaire : null,
            'traite_par' => (int) $this->session->get('user_id'),
        ]);

        return redirect()->back()->with('success', 'Demande approuvee et solde mis a jour.');
    }

    public function refuse(int $id)
    {
        if ($response = $this->requireRole(['rh'])) {
            return $response;
        }

        $congeModel = new CongeModel();
        $conge = $congeModel->find($id);
        if (!$conge) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        $commentaire = (string) $this->request->getPost('commentaire_rh');

        if ($conge['statut'] === 'approuvee') {
            $typeModel = new TypeCongeModel();
            $type = $typeModel->find((int) $conge['type_conge_id']);

            if ($type && (int) $type['deductible'] === 1) {
                $year = (int) date('Y', strtotime($conge['date_debut']));
                $soldeModel = new SoldeModel();
                $solde = $soldeModel
                    ->where('employe_id', (int) $conge['employe_id'])
                    ->where('type_conge_id', (int) $conge['type_conge_id'])
                    ->where('annee', $year)
                    ->first();

                if ($solde) {
                    $newPris = (int) $solde['jours_pris'] - (int) $conge['nb_jours'];
                    if ($newPris < 0) {
                        $newPris = 0;
                    }
                    $soldeModel->update($solde['id'], ['jours_pris' => $newPris]);
                }
            }
        }

        if (!in_array($conge['statut'], ['en_attente', 'approuvee'], true)) {
            return redirect()->back()->with('error', 'Demande deja traitee.');
        }

        $congeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire !== '' ? $commentaire : null,
            'traite_par' => (int) $this->session->get('user_id'),
        ]);

        return redirect()->to('/rh')->with('success', 'Demande refusee.');
    }

    public function soldes()
    {
        if ($response = $this->requireRole(['rh'])) {
            return $response;
        }

        $year = (int) date('Y');
        $soldeModel = new SoldeModel();
        $soldes = $soldeModel
            ->select('soldes.*, employees.prenom, employees.nom, types_conge.libelle')
            ->join('employees', 'employees.id = soldes.employe_id')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $year)
            ->orderBy('employees.nom', 'ASC')
            ->findAll();

        return view('rh/soldes', [
            'title' => 'Soldes employes',
            'soldes' => $soldes,
            'year' => $year,
        ]);
    }
}
