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

        // Define months array
        $months = $this->getMonths();

        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);
        $history = $this->getHistory($token, 0, 5); // Ambil 5 pertama saat load

        return view('history', [
            'profile' => $profile,
            'balance' => $balance,
            'history' => $history['records'] ?? [],
            'limit' => 5,
            'months' => $months // Pass the months array to the view
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

        $offset = (int)($this->request->getGet('offset') ?? 0);
        $limit = (int)($this->request->getGet('limit') ?? 5);

        // Validate limit and offset
        if ($limit <= 0 || $offset < 0) {
            return $this->response->setJSON([
                'status' => 400,
                'message' => 'Invalid offset or limit value'
            ]);
        }

        $history = $this->getHistory($token, $offset, $limit);

        return $this->response->setJSON([
            'status' => 200,
            'data' => $history['records'] ?? []
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

    private function getHistory($token, $offset = 0, $limit = 5)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/transaction/history?offset=' . $offset . '&limit=' . $limit;
        return $this->sendRequest($url, $token)['data'] ?? [];
    }

    private function sendRequest($url, $token = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

    private function getMonths()
    {
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
    }
}
