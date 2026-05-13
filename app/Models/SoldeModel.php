<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'annee',
        'jours_attribues',
        'jours_pris',
    ];

    protected $validationRules = [
        'employe_id' => 'required|integer',
        'type_conge_id' => 'required|integer',
        'annee' => 'required|integer',
        'jours_attribues' => 'required|integer|greater_than_equal_to[0]',
        'jours_pris' => 'permit_empty|integer|greater_than_equal_to[0]',
    ];
}
