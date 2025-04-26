<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('registration', 'RegistrationController::index');
$routes->post('registration/register', 'RegistrationController::register');

$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::authenticate');
$routes->get('logout', 'LoginController::logout');


$routes->get('home', 'HomeController::index'); // Homepage setelah login
