<?php

namespace App\Controllers;

use App\Models\Type;
use App\Models\SoldeModel;
use App\Models\DemandeConge;

class DemandeCongeController extends BaseController
{
    protected $type;
    protected $solde;
    protected $demandeConge;
    public function __construct()
    {
        $this->type = new Type();
        $this->solde = new SoldeModel();
        $this->demandeConge = new DemandeConge(); // ← Ajoute cette ligne
    }

    public function go_to_crud()
    {
        $id_user = session()->get('id_user');

        $liste_type = $this->type->findAll();

        foreach ($liste_type as &$type) {
            $solde = $this->solde->getSoldeRestant($id_user, $type['id_type']);
            $type['solde_restant'] = $solde;
        }

        $data['liste_type'] = $liste_type;
        return view("employe/demande", $data);
    }
    public function add_demande()
    {
        $id_user = session()->get('id_user');
        $id_type = $this->request->getPost('id_type');
        $date_debut = $this->request->getPost('date_debut');
        $date_fin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('motif');

        if (!$id_type || !$date_debut || !$date_fin) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs obligatoires');
        }

        $data = [
            'id_user' => $id_user,
            'id_type' => $id_type,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'motif' => $motif
        ];

        $this->demandeConge->insert($data);

        return redirect()->to('/employe/dashboard')->with('success', 'Demande envoyée avec succès');
    }
}