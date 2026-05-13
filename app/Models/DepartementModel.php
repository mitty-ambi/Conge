<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartementModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'description'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[120]',
        'description' => 'permit_empty|max_length[255]',
    ];
}
