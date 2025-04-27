<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'RegistrationController::index'); 

$routes->get('registration', 'RegistrationController::index'); 
$routes->post('registration/register', 'RegistrationController::register'); 

$routes->get('login', 'LoginController::index'); 
$routes->post('login', 'LoginController::authenticate'); 


$routes->get('home', 'HomepageController::index');  


$routes->get('logout', 'LoginController::logout'); 

$routes->get('topup', 'TopupController::index');

$routes->get('/history', 'HistoryController::index');
$routes->get('/history/loadMoreHistory', 'HistoryController::loadMoreHistory');


