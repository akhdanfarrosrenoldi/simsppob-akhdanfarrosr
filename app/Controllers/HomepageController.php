<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HomepageController extends Controller
{
    public function index()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
    
        $token = session()->get('token');
    
        $profile  = $this->getProfile($token);
        
        // Tambahkan pengecekan profile image
        if (empty($profile['profile_image'])) {
            $profile['profile_image'] = base_url('assets/images/default-profile.png');
        }
    
        $balance  = $this->getBalance($token);
        $services = $this->getServices($token);
        $banners  = $this->getBanners($token);
    
        return view('homepage', [
            'profile'  => $profile,
            'balance'  => $balance,
            'services' => $services,
            'banners'  => $banners,
        ]);
    }
    

    public function service($serviceCode)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $token = session()->get('token');
        $service = $this->getServiceDetail($token, $serviceCode);

        return view('service-detail', [
            'service' => $service,
        ]);
    }

    private function getProfile($token)
    {
        return $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/profile', $token)['data'] ?? null;
    }

    private function getBalance($token)
    {
        return $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/balance', $token)['data'] ?? null;
    }

    private function getServices($token)
    {
        return $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/services', $token)['data'] ?? [];
    }

    private function getBanners($token)
    {
        return $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/banner', $token)['data'] ?? [];
    }

    private function getServiceDetail($token, $serviceCode)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/services/' . urlencode($serviceCode);
        return $this->sendRequest($url, $token)['data'] ?? null;
    }

    private function sendRequest($url, $token = null)
    {
        $ch = curl_init($url);

        $headers = ['Accept: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'Curl error: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $decodedResponse = json_decode($response, true);
        curl_close($ch);

        if ($httpCode == 401 || ($decodedResponse['status'] ?? null) == 108) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Sesi anda habis. Silakan login kembali.');
        }

        return $decodedResponse;
    }
}
