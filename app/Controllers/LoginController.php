<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        $validation = Services::validation();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]'
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Email atau password tidak valid');
        }

        // Ambil data dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Kirim request ke API login
        $client = \Config\Services::curlrequest();

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/login', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'email' => $email,
                    'password' => $password
                ])
            ]);

            $result = json_decode($response->getBody(), true);

            // Cek apakah login berhasil
            if (isset($result['status']) && $result['status'] === 0) {
                // Simpan token JWT ke session
                session()->set('logged_in', true);
                session()->set('token', $result['data']['token']);
                return redirect()->to('/home'); // Arahkan ke homepage
            } else {
                return redirect()->back()->withInput()->with('error', $result['message'] ?? 'Login gagal');
            }

        } catch (\Exception $e) {
            // Tangani error koneksi API
            return redirect()->back()->withInput()->with('error', 'Gagal terhubung ke server API. Coba lagi nanti.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
