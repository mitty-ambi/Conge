<?php
namespace App\Models;
use CodeIgniter\Model;

class DemandeConge extends Model
{
    protected $table = "DemandeConge";
    protected $primaryKey = 'id_demande';
    protected $allowedFields = ['id_user', 'id_type', 'date_debut', 'date_fin'];
    protected $useTimestamps = false;
}
?>