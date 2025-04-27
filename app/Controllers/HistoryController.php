<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HistoryController extends Controller
{
    public function index()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        // Ambil data profil dan saldo user
        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);
        // Ambil 5 transaksi pertama
        $history = $this->getHistory($token, 0, 5);

        return view('history', [
            'profile' => $profile,
            'balance' => $balance,
            'history' => $history['records'] ?? [], // Pastikan history dalam bentuk array
            'limit'   => 5,
            'months'  => $this->getMonths(),
        ]);
    }

    public function loadMoreHistory()
    {
        $token = session()->get('token');
        if (!$token) {
            return $this->response->setJSON([
                'status' => 401,
                'message' => 'Unauthorized'
            ]);
        }

        // Ambil offset dan limit dari query params
        $offset = (int) $this->request->getGet('offset');
        $limit  = (int) $this->request->getGet('limit');

        // Ambil data history sesuai offset dan limit
        $history = $this->getHistory($token, $offset, $limit);

        // Kirim data transaksi tambahan ke client
        return $this->response->setJSON([
            'status' => 200,
            'data'   => $history['records'] ?? []
        ]);
    }

    private function getProfile($token)
    {
        $response = $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/profile', $token);
        return $response['data'] ?? [];
    }

    private function getBalance($token)
    {
        $response = $this->sendRequest('https://take-home-test-api.nutech-integrasi.com/balance', $token);
        return $response['data'] ?? [];
    }

    private function getHistory($token, $offset = 0, $limit = 5)
    {
        $url = "https://take-home-test-api.nutech-integrasi.com/transaction/history?offset={$offset}&limit={$limit}";
        $response = $this->sendRequest($url, $token);
        return $response['data'] ?? [];
    }

    private function sendRequest($url, $token)
    {
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept'        => 'application/json'
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return ['status' => 500, 'message' => 'Internal Server Error'];
        }
    }

    private function getMonths()
    {
        return [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
    }

    public function filterByMonth()
{
    $token = session()->get('token');
    if (!$token) {
        return $this->response->setJSON([
            'status' => 401,
            'message' => 'Unauthorized'
        ]);
    }

    $month = $this->request->getGet('month');
    $history = $this->getHistory($token);  // Ambil seluruh data transaksi
    $filtered = [];

    // Filter transaksi berdasarkan bulan yang dipilih
    if (!empty($history['records'])) {
        foreach ($history['records'] as $item) {
            if (date('m', strtotime($item['created_on'])) == $month) {
                $filtered[] = $item;
            }
        }
    }

    // Kirimkan hanya transaksi yang sesuai bulan yang dipilih
    return $this->response->setJSON([
        'status' => 200,
        'data'   => $filtered
    ]);
}



}
