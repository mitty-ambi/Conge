<?php
    namespace App\Models;
    use CodeIgniter\Model;
    class Statut extends Model{
        protected $table = 'Status';
        protected $primaryKey = 'id_status';
        protected $allowedFields = ['nom'];

        public function getAllStatuts()
        {
            $rows = $this->findAll();
            return array_map(static function ($row) {
                return [
                    'id_statut' => $row['id_status'] ?? null,
                    'libelle'   => $row['nom'] ?? null,
                ];
            }, $rows);
        }
    }

?>