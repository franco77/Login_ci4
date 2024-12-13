<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(true);
//$routes->get('/', 'Home::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login/authenticate', 'LoginController::authenticate');
$routes->get('/logout', 'LoginController::logout');

$routes->get('/register', 'LoginController::registerForm');
$routes->post('/registerUser', 'LoginController::registerUser');



$routes->group('admin', ['filter' => 'role:1'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index'); // Solo Admin
    $routes->get('users', 'Admin\Users::index');         // Solo Admin
    $routes->get('profile', 'Admin\Profile::index');         // Solo Admin
    $routes->get('settings', 'Admin\Settings::index');   // Solo Admin

    $routes->get('todo', 'Admin\Todo::index');
    $routes->post('todo/add', 'Admin\Todo::add');
    $routes->post('todo/toggle/(:num)', 'Admin\Todo::toggle/$1');
    $routes->post('todo/delete/(:num)', 'Admin\Todo::delete/$1');
});


$routes->get('/unauthorized', function () {
    return "Unauthorized Access";
});