<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TypeCongeModel;

class TypeCongeController extends BaseController
{
    public function index()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $model = new TypeCongeModel();
        $types = $model->orderBy('libelle', 'ASC')->findAll();

        return view('admin/types', [
            'title' => 'Types de conge',
            'types' => $types,
        ]);
    }

    public function store()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $rules = [
            'libelle' => 'required|min_length[2]',
            'jours_annuels' => 'required|integer|greater_than_equal_to[0]',
            'deductible' => 'permit_empty|in_list[0,1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new TypeCongeModel();
        $model->insert([
            'libelle' => (string) $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible' => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->back()->with('success', 'Type de conge ajoute.');
    }
}
