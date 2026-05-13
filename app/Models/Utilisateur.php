<?php
namespace App\Models;
use CodeIgniter\Model;

class Utilisateur extends Model
{
    protected $table = "Utilisateur";
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['Nom', 'Prenom', 'Role'];
    protected $useTimestamps = false;
}
?>