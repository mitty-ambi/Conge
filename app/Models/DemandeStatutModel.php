<?php
    namespace App\Models;
    use CodeIgniter\Model;
    class DemandeStatutModel extends Model{
        protected $table = 'DemandeStatus';
        protected $primaryKey = 'id_demande_status';
        protected $allowedFields = ['id_demande', 'id_status', 'date'];

        public function getStatutByDemandeId($id_demande)
        {
            return $this->where('id_demande', $id_demande)->orderBy('id_demande_status', 'DESC')->first();
        }
    }
?>