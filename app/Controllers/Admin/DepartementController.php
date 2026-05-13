<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DepartementModel;

class DepartementController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $model = new DepartementModel();
        $departments = $model->orderBy('nom', 'ASC')->findAll();

        return view('admin/departements', [
            'title' => 'Departements',
            'departments' => $departments,
        ]);
    }

    public function store()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $rules = [
            'nom' => 'required|min_length[2]',
            'description' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new DepartementModel();
        $model->insert([
            'nom' => (string) $this->request->getPost('nom'),
            'description' => (string) $this->request->getPost('description'),
        ]);

        return redirect()->back()->with('success', 'Departement ajoute.');
    }
}
