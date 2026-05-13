<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->session->get('user_id')) {
            return $this->redirectByRole($this->session->get('user_role'));
        }

        return view('auth/login', [
            'title' => 'Connexion',
        ]);
    }

    public function attempt()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new EmployeModel();
        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $user = $model->where('email', $email)->where('actif', 1)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Identifiants incorrects.');
        }

        $this->session->set([
            'user_id' => $user['id'],
            'user_name' => $user['prenom'] . ' ' . $user['nom'],
            'user_role' => $user['role'],
            'department_id' => $user['department_id'],
        ]);

        return $this->redirectByRole($user['role']);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
