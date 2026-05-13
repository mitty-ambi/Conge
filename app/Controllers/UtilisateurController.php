<?php
namespace App\Controllers;
use App\Models\Utilisateur;

class UtilisateurController extends BaseController
{
    protected $utilisateur;

    public function __construct()
    {
        $this->utilisateur = new Utilisateur();
    }
    public function go_to_login()
    {
        return view("login");
    }

}