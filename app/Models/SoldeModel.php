<?php
    namespace App\Models;
    use CodeIgniter\Model;
    class SoldeModel extends Model{
        protected $table = 'Solde';
        protected $primaryKey = 'id_solde';
        protected $fillable = ['valeur','id_user', 'id_type'];

        public function getSoldeByUserIdByType($id_user, $id_type)
        {
            return $this->where('id_user', $id_user)->where('id_type', $id_type)->first();
        }
        public function updateSolde($id_user, $id_type, $valeur)
        {
            $solde = $this->getSoldeByUserIdByType($id_user, $id_type);
            if ($solde) {
                $newValeur = $solde['valeur'] + $valeur;
                return $this->update($solde['id_solde'], ['valeur' => $newValeur]);
            } else {
                return $this->insert(['id_user' => $id_user, 'id_type' => $id_type, 'valeur' => $valeur]);
            }
        }
    }
?>