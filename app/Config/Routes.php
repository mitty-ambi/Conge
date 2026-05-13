<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/eleve/create', 'ElevesController::create');
$routes->post('/eleve/create', 'ElevesController::store');
$routes->get('/eleve/success', 'ElevesController::success');
$routes->get('/eleve/notes/(:num)', 'ElevesController::notesEleve/$1');
$routes->get('/eleve/notes', 'ElevesController::notesEleve/1');