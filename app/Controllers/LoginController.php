<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class LoginController extends Controller
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('login');
    }

    public function authenticate()
    {
        $validation = Services::validation();

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password tidak valid.');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $client = Services::curlrequest();

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/login', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'email'    => $email,
                    'password' => $password,
                ])
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['status']) && $result['status'] === 0) {
                session()->set([
                    'isLoggedIn' => true,
                    'token'      => $result['data']['token'],
                ]);
                return redirect()->to('/home');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $result['message'] ?? 'Login gagal.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Login API Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak dapat menghubungi server API.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
