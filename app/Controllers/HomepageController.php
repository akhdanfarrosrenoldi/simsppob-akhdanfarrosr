<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        // Cek kalau belum login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Ambil token dari session
        $token = session()->get('token');

        // Mendapatkan data profil, saldo, layanan, dan banner
        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);
        $services = $this->getServices($token);
        $banners = $this->getBanners($token);

        // Kirim data ke view
        return view('homepage', [
            'profile' => $profile,
            'balance' => $balance,
            'services' => $services,
            'banners' => $banners
        ]);
    }

    private function getProfile($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile';
        return $this->sendRequest($url, $token)['data'] ?? null;
    }

    private function getBalance($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/balance';
        return $this->sendRequest($url, $token)['data'] ?? null;
    }

    private function getServices($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/services';
        return $this->sendRequest($url, $token)['data'] ?? [];
    }

    private function getBanners($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/banner';
        return $this->sendRequest($url, $token)['data'] ?? [];
    }

    private function sendRequest($url, $token = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Menambahkan token pada header jika ada
        $headers = ['Content-Type: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return null;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
