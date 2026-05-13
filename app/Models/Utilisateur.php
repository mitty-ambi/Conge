<?php
namespace App\Models;
use CodeIgniter\Model;

class Utilisateur extends Model
{
    protected $table = "Utilisateur";
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['Nom', 'Prenom', 'Role'];
    protected $useTimestamps = false;

    public function logged_in($email, $mdp)
    {
        $user = $this->where('email', $email)->where('mdp', $mdp)->first();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
}
?>