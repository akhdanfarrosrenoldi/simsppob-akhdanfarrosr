<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Ubah route untuk halaman utama agar langsung mengarah ke registration
$routes->get('/', 'RegistrationController::index'); // Halaman pertama langsung ke registration

// Routes untuk registrasi
$routes->get('registration', 'RegistrationController::index'); // Menampilkan form registrasi
$routes->post('registration/register', 'RegistrationController::register'); // Proses registrasi

// Routes untuk login
$routes->get('login', 'LoginController::index'); // Menampilkan form login
$routes->post('login', 'LoginController::authenticate'); // Proses autentikasi login

// Route untuk homepage
$routes->get('home', 'HomepageController::index');  // Route ke homepage setelah login

// Route untuk logout
$routes->get('logout', 'LoginController::logout'); // Logout user

$routes->get('topup', 'TopupController::index');
