<?php
    namespace App\Models;
    use CodeIgniter\Model;
    class Statut extends Model{
        protected $table = 'Statut';
        protected $primaryKey = 'id_statut';
        protected $fillable = ['libelle'];

        public function getAllStatuts()
        {
            return $this->findAll();
        }
    }

?>