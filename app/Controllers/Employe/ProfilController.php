<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\EmployeModel;

class ProfilController extends BaseController
{
    public function edit()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $model = new EmployeModel();
        $user = $model->find((int) $this->session->get('user_id'));

        return view('employe/profil', [
            'title' => 'Mon profil',
            'user' => $user,
        ]);
    }

    public function update()
    {
        if ($response = $this->requireRole(['employe'])) {
            return $response;
        }

        $rules = [
            'prenom' => 'required|min_length[2]',
            'nom' => 'required|min_length[2]',
            'password' => 'permit_empty|min_length[6]',
            'password_confirm' => 'matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'prenom' => (string) $this->request->getPost('prenom'),
            'nom' => (string) $this->request->getPost('nom'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model = new EmployeModel();
        $model->update((int) $this->session->get('user_id'), $data);

        $this->session->set('user_name', $data['prenom'] . ' ' . $data['nom']);

        return redirect()->back()->with('success', 'Profil mis a jour.');
    }
}
