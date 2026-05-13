<?php
namespace App\Models;
use CodeIgniter\Model;
class DemandeDecision extends Model
{
    protected $table = 'DemandeDecision';
    protected $primaryKey = 'id';
    protected $fillable = ['id_demande', 'id_user'];

    public function getUserQuiaPrisDecision($id_demande)
    {
        return $this->join('Utilisateur', 'DemandeDecision.id_user = Utilisateur.id_user')
            ->where('DemandeDecision.id_demande', $id_demande)
            ->first();
    }
}
?>