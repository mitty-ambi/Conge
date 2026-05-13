<?php
use CodeIgniter\Model;
class DemandeModel extends Model
{
    protected $table = 'DemandeConge';
    protected $primaryKey = 'id_demande';
    protected $fillable = ['id_type', 'id'];
}
?>