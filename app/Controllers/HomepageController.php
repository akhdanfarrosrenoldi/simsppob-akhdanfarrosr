<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Pastikan hanya pengguna yang sudah login yang bisa mengakses halaman ini
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('home');
    }
}
