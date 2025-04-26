<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Services;

class TopUpController extends Controller
{
    public function index()
    {
        // Ambil token dari session
        $token = session()->get('token');
        
        // Pastikan token tersedia
        if (!$token) {
            return redirect()->to('/login'); // Redirect ke halaman login jika token tidak ada
        }

        // Mendapatkan data profil dan saldo
        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);

        // Kirim data ke view
        return view('topup', [
            'profile' => $profile,
            'balance' => $balance
        ]);
    }

    private function getProfile($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile'; // API URL yang benar
        $response = $this->sendRequest($url, $token);

        return $response['data'] ?? null;
    }

    private function getBalance($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/balance'; // API URL yang benar
        $response = $this->sendRequest($url, $token);

        return $response['data'] ?? null;
    }

    private function sendRequest($url, $token = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Menambahkan token pada header jika ada
        if ($token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token
            ]);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
