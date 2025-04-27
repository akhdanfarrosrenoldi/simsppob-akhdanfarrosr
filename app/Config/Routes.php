<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'RegistrationController::index');
$routes->get('registration', 'RegistrationController::index');
$routes->post('registration/register', 'RegistrationController::register');

$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::authenticate');
$routes->get('logout', 'LoginController::logout');

// Homepage
$routes->get('home', 'HomepageController::index');

// Service detail (opsional, kalau masih butuh)
$routes->get('service/(:segment)', 'HomepageController::service/$1');

// Pembayaran: tiap kali user klik layanan, masuk ke sini
$routes->get('pembayaran/(:segment)', 'PembayaranController::index/$1');
// untuk handle form pembayaran via POST
$routes->post('pembayaran/submit', 'PembayaranController::submit');

// Topup
$routes->get('topup', 'TopupController::index');
$routes->post('topup/topUp', 'TopupController::topUp');


// History
$routes->get('history', 'HistoryController::index');
$routes->get('history/loadMoreHistory', 'HistoryController::loadMoreHistory');

// Profile
$routes->get('profile', 'ProfileController::index');
$routes->get('profile/edit', 'ProfileController::edit');
$routes->put('profile/update', 'ProfileController::updateProfile');
$routes->post('profile/image', 'ProfileController::updateProfileImage');

$routes->get('pembayaran/(:segment)', 'PembayaranController::index/$1');
$routes->post('pembayaran/submit', 'PembayaranController::submit');

