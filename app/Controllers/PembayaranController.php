<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PembayaranController extends Controller
{
    public function index($serviceCode)
{
    $token = session()->get('token');
    if (!$token) {
        return redirect()->to('/login');
    }

    // Ambil data profile dan balance
    $profile = $this->getProfile($token);
    $balance = $this->getBalance($token);

    // Ambil detail layanan berdasarkan serviceCode
    $service = $this->getServiceDetail($token, $serviceCode);

    // Kirim SEMUA data ke view
    return view('pembayaran', [
        'profile' => $profile,
        'balance' => $balance,
        'service' => $service, // <<< ini yang penting
    ]);
}
private function getServiceDetail(string $token, string $serviceCode)
{
    $url = 'https://take-home-test-api.nutech-integrasi.com/services';
    $response = $this->sendRequest($url, $token);

    if (!empty($response['data'])) {
        foreach ($response['data'] as $service) {
            if ($service['service_code'] == $serviceCode) {
                return $service;
            }
        }
    }

    return null;
}



    private function getProfile(string $token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile';
        $response = $this->sendRequest($url, $token);

        return $response['data'] ?? null;
    }

    private function getBalance(string $token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/balance';
        $response = $this->sendRequest($url, $token);

        return $response['data'] ?? null;
    }

    private function sendRequest(string $url, ?string $token = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return null;
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    public function submit()
{
    $token = session()->get('token');
    if (!$token) {
        return $this->response->setJSON([
            'status' => 401,
            'message' => 'Unauthorized'
        ]);
    }

    $serviceCode = $this->request->getPost('service_code');

    if (!$serviceCode) {
        return $this->response->setJSON([
            'status' => 400,
            'message' => 'Service code tidak valid.'
        ]);
    }

    $payload = [
        'service_code' => $serviceCode
    ];

    $ch = curl_init('https://take-home-test-api.nutech-integrasi.com/transaction');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['status']) && $result['status'] === 0) {
        return $this->response->setJSON([
            'status' => 0,
            'message' => 'Pembayaran berhasil.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => $result['status'] ?? 500,
            'message' => $result['message'] ?? 'Terjadi kesalahan saat pembayaran.'
        ]);
    }
}

}
