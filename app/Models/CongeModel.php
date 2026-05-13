<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'traite_par',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'nb_jours' => 'required|integer|greater_than[0]',
        'motif' => 'permit_empty|max_length[1000]',
        'statut' => 'permit_empty|in_list[en_attente,approuvee,refusee,annulee]',
        'commentaire_rh' => 'permit_empty|max_length[1000]',
        'traite_par' => 'permit_empty|integer',
    ];
}
