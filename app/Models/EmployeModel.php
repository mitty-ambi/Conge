<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employees';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'department_id',
        'date_embauche',
        'actif',
    ];
    protected $useTimestamps = true;

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[120]',
        'prenom' => 'required|min_length[2]|max_length[120]',
        'email' => 'required|valid_email|max_length[180]',
        'password' => 'permit_empty|min_length[6]',
        'role' => 'required|in_list[employe,rh,admin]',
        'department_id' => 'required|integer',
        'date_embauche' => 'permit_empty|valid_date',
        'actif' => 'permit_empty|in_list[0,1]',
    ];
}
