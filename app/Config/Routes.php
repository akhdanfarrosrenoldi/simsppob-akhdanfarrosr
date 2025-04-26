<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes untuk Registration
$routes->get('registration', 'RegistrationController::index');
$routes->post('registration/register', 'RegistrationController::register');

// Routes untuk Login
$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::authenticate');

// Route Logout (GET method)
$routes->get('logout', 'LoginController::logout'); // Gunakan GET untuk logout

// Routes untuk Homepage
$routes->get('home', 'HomepageController::index');
