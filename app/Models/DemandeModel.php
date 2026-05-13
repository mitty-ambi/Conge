<?php
namespace App\Models;
use CodeIgniter\Model;
class DemandeModel extends Model
{
    protected $table = 'DemandeConge';
    protected $primaryKey = 'id_demande';
    protected $fillable = ['id_type', 'id'];

    public function getAllDemandes()
    {
        return $this->findAll();
    }
    public function getDemandeByStatut($id_statut)
    {
        return $this->join('DemandeStatut', 'DemandeConge.id_demande = DemandeStatut.id_demande')
            ->where('DemandeStatut.id_statut', $id_statut)
            ->findAll();
    }
    public function getDemandeByDepartement($id_departement)
    {
        return $this->join('Utilisateur', 'DemandeConge.id = Utilisateur.id_user')
            ->where('Utilisateur.id_departement', $id_departement)
            ->findAll();
    }
    public function modifyStatut($id_demande, $id_statut)
    {
        $demandeStatutModel = new DemandeStatutModel();
        $data = [
            'id_demande' => $id_demande,
            'id_statut' => $id_statut
        ];
        return $demandeStatutModel->insert($data);
    }
    public function getNombresDemandesByStatut($id_statut)
    {
        return $this->join('DemandeStatut', 'DemandeConge.id_demande = DemandeStatut.id_demande')
            ->where('DemandeStatut.id_statut', $id_statut)
            ->countAllResults();
    }
    public function getDureeDemande($id_demande)
    {
        $demande = $this->find($id_demande);
        if ($demande) {
            $date_debut = new \DateTime($demande['date_debut']);
            $date_fin = new \DateTime($demande['date_fin']);
            return $date_debut->diff($date_fin)->days + 1;
        }
        return null;
    }

    public function getDemandesEmploye($id_user)
    {
        $demandes = $this
            ->select('DemandeConge.*, DemandeStatus.id_status as statut, Type.nom as type_conge')
            ->join('DemandeStatus', 'DemandeConge.id_demande = DemandeStatus.id_demande', 'left')
            ->join('Type', 'DemandeConge.id_type = Type.id_type', 'left')
            ->where('DemandeConge.id_user', $id_user)
            ->findAll();

        // Ajouter la durée calculée
        foreach ($demandes as &$d) {
            $debut = new \DateTime($d['date_debut']);
            $fin = new \DateTime($d['date_fin']);
            $d['duree'] = $debut->diff($fin)->days + 1;
        }

        return $demandes;
    }

    public function getStatutByDemandeId($id_demande)
    {
        $demandeStatutModel = new DemandeStatutModel();
        $statutRow = $demandeStatutModel->getStatutByDemandeId($id_demande);
        if (empty($statutRow) || empty($statutRow['id_statut'])) {
            return null;
        }

        $statutModel = new Statut();
        $statut = $statutModel->find($statutRow['id_statut']);
        return $statut['libelle'] ?? null;
    }

    public function getDecisionByDemandeId($id_demande)
    {
        $demandeDecisionModel = new DemandeDecision();
        return $demandeDecisionModel->getUserQuiaPrisDecision($id_demande);
    }
}
?>