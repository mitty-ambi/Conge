<?php
namespace App\Models;
use CodeIgniter\Model;
class Departement extends Model{
    protected $table = 'Departement';
    protected $primaryKey = 'id_departement';
    protected $allowedFields = ['nom'];

    public function getAllDepartements()
    {
        $rows = $this->findAll();
        return array_map(static function ($row) {
            return [
                'id_departement' => $row['id_departement'] ?? null,
                'libelle'   => $row['nom'] ?? null,
            ];
        }, $rows);
    }
}
?>