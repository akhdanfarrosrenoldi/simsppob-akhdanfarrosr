<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'RegistrationController::index');
$routes->get('registration', 'RegistrationController::index');
$routes->post('registration/register', 'RegistrationController::register');

$routes->get('login', 'LoginController::index');
$routes->post('login', 'LoginController::authenticate');
$routes->get('logout', 'LoginController::logout');

$routes->get('home', 'HomepageController::index');
$routes->get('service/(:segment)', 'HomepageController::service/$1');

$routes->get('pembayaran/(:segment)', 'PembayaranController::index/$1');
$routes->post('pembayaran/submit', 'PembayaranController::submit');

$routes->get('topup', 'TopUpController::index');
$routes->post('topup/topUp', 'TopupController::topUp');

$routes->get('history', 'HistoryController::index');
$routes->get('history/loadMoreHistory', 'HistoryController::loadMoreHistory');


$routes->get('profile', 'ProfileController::index');
$routes->get('profile/edit', 'ProfileController::edit');
$routes->post('profile/update', 'ProfileController::updateProfile');
$routes->post('profile/image', 'ProfileController::updateProfileImage');
