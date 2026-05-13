<?php
namespace App\Controllers;
use App\Models\DemandeConge;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class DemandeCongeController extends BaseController
{
    protected $demande;

    public function __construct()
    {
        $this->demande = new DemandeConge();
        helper('session');
    }
}