<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\DemandeStatutModel;

class DemandeConge extends Model
{
    protected $table = "DemandeConge";
    protected $primaryKey = 'id_demande';
    protected $allowedFields = ['id_user', 'id_type', 'date_debut', 'date_fin', 'motif'];
    protected $useTimestamps = false;
    public function getLastDemandeByIdUser($id_user)
    {
        return $this->where('id_user', $id_user)->orderBy('DemandeConge.date_fin', 'DESC');
    }
    public function getStatusDemande($id_demande)
    {
        $demandeStatus = new DemandeStatutModel();
        return $demandeStatus->select('Status.nom')->join('Status', 'Status.id_status = DemandeStatus.id_status')
            ->where('DemandeStatus.id_demande', $id_demande)
            ->orderBy('DemandeStatus.date', 'DESC')
            ->first();
    }
}

?>