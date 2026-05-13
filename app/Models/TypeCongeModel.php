<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'types_conge';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['libelle', 'jours_annuels', 'deductible'];

    protected $validationRules = [
        'libelle' => 'required|min_length[2]|max_length[120]',
        'jours_annuels' => 'required|integer|greater_than_equal_to[0]',
        'deductible' => 'permit_empty|in_list[0,1]',
    ];
}
