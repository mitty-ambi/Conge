<?php
namespace App\Models;
use CodeIgniter\Model;
class Type extends Model
{
    protected $table = 'Type';
    protected $primaryKey = 'id_type';
    protected $allowedFields = ['nom'];
}

?>