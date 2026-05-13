<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('HomeController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'HomeController::index');

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->get('logout', 'AuthController::logout');

$routes->group('employe', ['filter' => 'auth'], static function ($routes): void {
	$routes->get('/', 'Employe\DashboardController::index');
	$routes->get('conges', 'Employe\CongeController::index');
	$routes->get('conges/nouveau', 'Employe\CongeController::create');
	$routes->post('conges', 'Employe\CongeController::store');
	$routes->post('conges/(:num)/annuler', 'Employe\CongeController::cancel/$1');
	$routes->get('profil', 'Employe\ProfilController::edit');
	$routes->post('profil', 'Employe\ProfilController::update');
});

$routes->group('rh', ['filter' => 'auth'], static function ($routes): void {
	$routes->get('/', 'Rh\DemandeController::index');
	$routes->get('demandes', 'Rh\DemandeController::index');
	$routes->post('demandes/(:num)/approuver', 'Rh\DemandeController::approve/$1');
	$routes->post('demandes/(:num)/refuser', 'Rh\DemandeController::refuse/$1');
	$routes->get('soldes', 'Rh\DemandeController::soldes');
});

$routes->group('admin', ['filter' => 'auth'], static function ($routes): void {
	$routes->get('/', 'Admin\DashboardController::index');
	$routes->get('employes', 'Admin\EmployeController::index');
	$routes->post('employes', 'Admin\EmployeController::store');
	$routes->post('employes/(:num)/toggle', 'Admin\EmployeController::toggle/$1');
	$routes->post('employes/(:num)/update', 'Admin\EmployeController::update/$1');
	$routes->get('departements', 'Admin\DepartementController::index');
	$routes->post('departements', 'Admin\DepartementController::store');
	$routes->get('types', 'Admin\TypeCongeController::index');
	$routes->post('types', 'Admin\TypeCongeController::store');
	$routes->post('soldes', 'Admin\SoldeController::update');
});
