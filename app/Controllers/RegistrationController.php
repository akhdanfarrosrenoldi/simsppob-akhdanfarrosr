<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registration');
    }

    public function register()
    {
        $validation = Services::validation();

        $rules = [
            'first_name'        => 'required',
            'last_name'         => 'required',
            'email'             => 'required|valid_email',
            'password'          => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[password]'
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }

        // Ambil data dari form
        $firstName = $this->request->getPost('first_name');
        $lastName = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Cek apakah password dan confirm password sama
        if ($password !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error_confirm_password', 'Password dan konfirmasi password tidak cocok.');
        }

        // Kirim data ke API eksternal untuk registrasi
        $client = Services::curlrequest();
        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'password'   => $password
                ])
            ]);

            $result = json_decode($response->getBody(), true);

            // Jika status berhasil
            if (isset($result['status']) && $result['status'] === 0) {
                // Registrasi berhasil
                return redirect()->to('/login');
            } else {
                // Registrasi gagal
                return redirect()->back()->withInput()->with('error', $result['message'] ?? 'Registrasi gagal');
            }

        } catch (\Exception $e) {
            // Jika gagal menghubungi API, tampilkan pesan error
            return redirect()->back()->withInput()->with('error', 'Gagal terhubung ke server API.');
        }
    }
}
