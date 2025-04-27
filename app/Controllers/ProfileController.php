<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        $profile = $this->getProfile($token);

        return view('topup', [
            'profile' => $profile,
        ]);
    }


    private function getProfile($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile';
        $response = $this->sendRequest($url, $token);
        return $response['data'] ?? null;
    }


    private function sendRequest($url, $token, $data = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return ['status' => 500, 'message' => 'Request error'];
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
