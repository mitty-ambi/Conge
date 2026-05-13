<?php
namespace App\Models;
use CodeIgniter\Model;

class Status extends Model
{
    protected $table = "Status";
    protected $primaryKey = 'id_status';
    protected $allowedFields = ['nom'];
    protected $useTimestamps = false;

}
?>