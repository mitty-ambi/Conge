<?php
namespace App\Controllers;
use App\Models\Utilisateur;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class UtilisateurController extends BaseController
{
    protected $utilisateur;

    public function __construct()
    {
        $this->utilisateur = new Utilisateur();
        helper('session');
    }

    public function go_to_login()
    {
        return view("login");
    }

    public function login()
    {
        $email = $this->request->getPost("email");
        $mdp = $this->request->getPost("password");

        $user = $this->utilisateur->logged_in($email, $mdp);

        if ($user) {
            session()->set([
                'id_user' => $user['id_user'],
                'nom' => $user['Nom'],
                'prenom' => $user['Prenom'],
                'email' => $user['email'],
                'role' => $user['Role'],
                'logged_in' => true
            ]);

            switch ($user['Role']) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                    break;
                case 'responsable rh':
                    return redirect()->to('/rh/dashboard');
                    break;
                default:
                    return redirect()->to('/employe/dashboard');
            }
        } else {
            session()->setFlashdata('error', 'Email ou mot de passe incorrect');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
    public function go_to_dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        switch ($role) {
            case 'admin':
                return view('admin/dashboard');
            case 'responsable rh':
                return view('rh/dashboard');
            default:
                return view('employe/dashboard');
        }
    }

    public function employe_dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('employe/dashboard');
    }

    public function admin_dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('admin/dashboard');
    }

    public function rh_dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        return view('rh/dashboard');
    }
}