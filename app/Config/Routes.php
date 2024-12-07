<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
$routes->get('/', 'Home::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login/authenticate', 'LoginController::authenticate');
$routes->get('/logout', 'LoginController::logout');

$routes->get('/register', 'LoginController::registerForm');
$routes->post('/registerUser', 'LoginController::registerUser');


//$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'role:1,2']); // Para Admin y User
$routes->get('admin/dashboard', 'Admin\Dashboard::index', ['filter' => 'role:1']); // Solo Admin

$routes->get('/unauthorized', function () {
    return "Unauthorized Access";
});