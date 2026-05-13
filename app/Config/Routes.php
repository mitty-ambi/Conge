<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UtilisateurController::go_to_login');
$routes->get('/login', 'UtilisateurController::go_to_login');
$routes->post('/auth/attemptLogin', 'UtilisateurController::login');
$routes->get('/logout', 'UtilisateurController::logout');

// Routes protégées (à ajouter avec filtre)
$routes->get('/admin/dashboard', 'UtilisateurController::admin_dashboard');
$routes->get('/rh/dashboard', 'UtilisateurController::rh_dashboard');
$routes->get('/employe/dashboard', 'UtilisateurController::employe_dashboard');
$routes->get('/employe/addDemande', 'UtilisateurController::employe_dashboard');