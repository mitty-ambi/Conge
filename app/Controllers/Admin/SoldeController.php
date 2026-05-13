<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SoldeModel;

class SoldeController extends BaseController
{
    public function update()
    {
        if ($response = $this->requireRole(['admin'])) {
            return $response;
        }

        $rules = [
            'employe_id' => 'required|integer',
            'type_conge_id' => 'required|integer',
            'annee' => 'required|integer',
            'jours_attribues' => 'required|integer|greater_than_equal_to[0]',
            'jours_pris' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'employe_id' => (int) $this->request->getPost('employe_id'),
            'type_conge_id' => (int) $this->request->getPost('type_conge_id'),
            'annee' => (int) $this->request->getPost('annee'),
            'jours_attribues' => (int) $this->request->getPost('jours_attribues'),
            'jours_pris' => (int) $this->request->getPost('jours_pris'),
        ];

        $model = new SoldeModel();
        $existing = $model
            ->where('employe_id', $data['employe_id'])
            ->where('type_conge_id', $data['type_conge_id'])
            ->where('annee', $data['annee'])
            ->first();

        if ($existing) {
            $model->update($existing['id'], [
                'jours_attribues' => $data['jours_attribues'],
                'jours_pris' => $data['jours_pris'],
            ]);
        } else {
            $model->insert($data);
        }

        return redirect()->back()->with('success', 'Solde mis a jour.');
    }
}
