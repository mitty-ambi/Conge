<?php
namespace App\Controllers;
use App\Models\UserModel;

class UtilisateurController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

}