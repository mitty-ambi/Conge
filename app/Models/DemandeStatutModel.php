<?php
    namespace App\Models;
    use CodeIgniter\Model;
    class DemandeStatutModel extends Model{
        protected $table = 'DemandeStatut';
        protected $primaryKey = 'id_demande_statut';
        protected $fillable = ['id_demande', 'id_statut'];

        public function getStatutByDemandeId($id_demande)
        {
            return $this->where('id_demande', $id_demande)->orderBy('id_demande_statut', 'DESC')->first();
        }
    }
?>