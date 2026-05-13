<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class CongeController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $employeId = (int) $this->session->get('user_id');
        $statut = (string) $this->request->getGet('statut');

        $congeModel = new CongeModel();
        $builder = $congeModel
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeId);

        if ($statut !== '') {
            $builder->where('conges.statut', $statut);
        }

        $conges = $builder->orderBy('conges.date_debut', 'DESC')->findAll();

        return view('employe/index', [
            'title' => 'Mes demandes',
            'conges' => $conges,
            'filter' => $statut,
        ]);
    }

    public function create()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $employeId = (int) $this->session->get('user_id');
        $year = (int) date('Y');

        $soldeModel = new SoldeModel();
        $types = $soldeModel
            ->select('types_conge.id, types_conge.libelle, types_conge.deductible, soldes.jours_attribues, soldes.jours_pris')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $employeId)
            ->where('soldes.annee', $year)
            ->orderBy('types_conge.libelle')
            ->findAll();

        foreach ($types as &$type) {
            $type['restant'] = (int) $type['jours_attribues'] - (int) $type['jours_pris'];
        }
        unset($type);

        return view('employe/form', [
            'title' => 'Nouvelle demande',
            'types' => $types,
        ]);
    }

    public function store()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
            'motif' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeId = (int) $this->session->get('user_id');
        $typeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = (string) $this->request->getPost('motif');

        if (strtotime($dateDebut) > strtotime($dateFin)) {
            return redirect()->back()->withInput()->with('error', 'La date de debut doit preceder la date de fin.');
        }

        $nbJours = calculate_business_days($dateDebut, $dateFin);
        if ($nbJours <= 0) {
            return redirect()->back()->withInput()->with('error', 'Aucun jour ouvrable detecte sur cette periode.');
        }

        $congeModel = new CongeModel();
        $overlap = $congeModel
            ->where('employe_id', $employeId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
            ->where('date_debut <=', $dateFin)
            ->where('date_fin >=', $dateDebut)
            ->groupEnd()
            ->first();

        if ($overlap) {
            return redirect()->back()->withInput()->with('error', 'Chevauchement detecte avec une demande existante.');
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($typeId);
        if (!$type) {
            return redirect()->back()->withInput()->with('error', 'Type de conge invalide.');
        }

        if ((int) $type['deductible'] === 1) {
            $soldeModel = new SoldeModel();
            $year = (int) date('Y', strtotime($dateDebut));
            $solde = $soldeModel
                ->where('employe_id', $employeId)
                ->where('type_conge_id', $typeId)
                ->where('annee', $year)
                ->first();

            if (!$solde) {
                return redirect()->back()->withInput()->with('error', 'Solde introuvable pour ce type de conge.');
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($nbJours > $restant) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour cette demande.');
            }
        }

        $congeModel->insert([
            'employe_id' => $employeId,
            'type_conge_id' => $typeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        return redirect()->to('/employe')->with('success', 'Votre demande a bien ete soumise.');
    }

    public function cancel(int $id)
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $employeId = (int) $this->session->get('user_id');
        $congeModel = new CongeModel();
        $conge = $congeModel->where('id', $id)->where('employe_id', $employeId)->first();

        if (!$conge || $conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Annulation impossible pour cette demande.');
        }

        $congeModel->update($id, ['statut' => 'annulee']);

        return redirect()->back()->with('success', 'La demande a ete annulee.');
    }
}
