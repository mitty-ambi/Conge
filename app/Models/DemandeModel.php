<?php
namespace App\Models;
use CodeIgniter\Model;
class DemandeModel extends Model
{
    protected $table = 'DemandeConge';
    protected $primaryKey = 'id_demande';
    protected $allowedFields = ['id_type', 'id_user', 'date_debut', 'date_fin'];

    public function getAllDemandes()
    {
        return $this->findAll();
    }
    public function getDemandeByStatut($id_statut)
    {
        // DemandeStatus est une table d'historique: on filtre sur le dernier statut par demande.
        $sql = "SELECT dc.*
                FROM DemandeConge dc
                JOIN DemandeStatus ds ON ds.id_demande = dc.id_demande
                WHERE ds.id_demande_status = (
                    SELECT MAX(ds2.id_demande_status)
                    FROM DemandeStatus ds2
                    WHERE ds2.id_demande = dc.id_demande
                )
                AND ds.id_status = ?";

        return $this->db->query($sql, [$id_statut])->getResultArray();
    }
    public function getDemandeByDepartement($id_departement)
    {
        return $this->join('Utilisateur', 'DemandeConge.id_user = Utilisateur.id_user')
                    ->where('Utilisateur.id_departement', $id_departement)
                    ->findAll();
    }
    public function modifyStatut($id_demande, $id_statut)
    {
        $demandeStatutModel = new DemandeStatutModel();
        $data = [
            'id_demande' => $id_demande,
            'id_status' => $id_statut,
            'date' => date('Y-m-d'),
        ];
        return $demandeStatutModel->insert($data);
    }
    public function getNombresDemandesByStatut($id_statut)
    {
        $sql = "SELECT COUNT(*) as cnt
                FROM DemandeConge dc
                JOIN DemandeStatus ds ON ds.id_demande = dc.id_demande
                WHERE ds.id_demande_status = (
                    SELECT MAX(ds2.id_demande_status)
                    FROM DemandeStatus ds2
                    WHERE ds2.id_demande = dc.id_demande
                )
                AND ds.id_status = ?";

        $row = $this->db->query($sql, [$id_statut])->getRowArray();
        return (int) ($row['cnt'] ?? 0);
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

    public function getStatutByDemandeId($id_demande)
    {
        $demandeStatutModel = new DemandeStatutModel();
        $statutRow = $demandeStatutModel->getStatutByDemandeId($id_demande);
        if (empty($statutRow) || empty($statutRow['id_status'])) {
            return null;
        }

        $statutModel = new Statut();
        $statut = $statutModel->find($statutRow['id_status']);
        return $statut['nom'] ?? null;
    }

    public function getDecisionByDemandeId($id_demande)
    {
        $demandeDecisionModel = new DemandeDecision();
        return $demandeDecisionModel->getUserQuiaPrisDecision($id_demande);
    }
}
?>