<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/login', 'UtilisateurController::go_to_login');

$routes->group('demande', function($routes) {
    $routes->get('/', 'DemandeController::index');
    $routes->get('statut/(:num)', 'DemandeController::getByStatut/$1');
    $routes->get('departement/(:num)', 'DemandeController::getByDepartement/$1');
    $routes->post('accepter/(:num)', 'DemandeController::accepterDemande/$1');
    $routes->post('refuser/(:num)', 'DemandeController::refuserDemande/$1');
});
