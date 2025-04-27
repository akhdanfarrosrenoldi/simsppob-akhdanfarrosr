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
            'confirm_password'  => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $postData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password')
        ];

        $client = Services::curlrequest();

        try {
            $response = $client->post('https://take-home-test-api.nutech-integrasi.com/registration', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'timeout' => 10, 
                'body' => json_encode($postData)
            ]);

            $result = json_decode($response->getBody(), true);

            if (!empty($result) && isset($result['status']) && $result['status'] === 0) {
                session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
                return redirect()->to('/login');
            } else {
                $errorMessage = $result['message'] ?? 'Gagal registrasi.';
                return redirect()->back()
                    ->withInput()
                    ->with('errors', ['email' => $errorMessage]);
            }

        } catch (\CodeIgniter\HTTP\Exceptions\HTTPException $e) {
            log_message('error', 'Registration API HTTP Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('errors', ['server' => 'Server lambat atau tidak merespon. Silakan coba lagi.']);
        } catch (\Exception $e) {
            log_message('error', 'Registration API Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('errors', ['server' => 'Gagal terhubung ke server. Silakan coba lagi nanti.']);
        }
    }
}
