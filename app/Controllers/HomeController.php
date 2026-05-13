<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        $role = $this->session->get('user_role');
        if (!$role) {
            return redirect()->to('/login');
        }

        return $this->redirectByRole($role);
    }
}
