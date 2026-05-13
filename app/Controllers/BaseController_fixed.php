<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $helpers = ['form', 'url', 'conges'];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->session = service('session');
    }

    protected function requireRole(array $roles)
    {
        $role = $this->session->get('user_role');
        if (!$role || !in_array($role, $roles, true)) {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }

        return null;
    }

    protected function redirectByRole(?string $role)
    {
        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        if ($role === 'rh') {
            return redirect()->to('/rh');
        }

        return redirect()->to('/employe');
    }

    protected function view(string $view, array $data = [], ?string $saveData = null): string
    {
        // Add user data automatically to all views
        if (!isset($data['user'])) {
            $data['user'] = [
                'id' => $this->session->get('user_id'),
                'nom' => $this->session->get('user_nom'),
                'prenom' => $this->session->get('user_prenom'),
                'role' => $this->session->get('user_role'),
                'email' => $this->session->get('user_email'),
            ];
        }
        return parent::view($view, $data, $saveData);
    }
}
