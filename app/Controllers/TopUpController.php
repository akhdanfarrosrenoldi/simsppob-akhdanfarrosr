<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TopUpController extends Controller
{
    public function index()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);

        return view('topup', [
            'profile' => $profile,
            'balance' => $balance
        ]);
    }

    public function topUp()
    {
        $token = session()->get('token');
        if (!$token) {
            return $this->response->setJSON(['status' => 401, 'message' => 'Unauthorized']);
        }

        $request = $this->request->getJSON();
        $topUpAmount = $request->top_up_amount ?? null;

        if ($topUpAmount === null || !is_numeric($topUpAmount) || $topUpAmount < 10000 || $topUpAmount > 1000000) {
            return $this->response->setJSON([
                'status' => 102,
                'message' => 'Nominal top up harus antara 10.000 dan 1.000.000',
                'data' => null
            ]);
        }

        $url = 'https://take-home-test-api.nutech-integrasi.com/topup';
        $response = $this->sendRequest($url, $token, ['top_up_amount' => (int) $topUpAmount]);

        log_message('debug', 'API TopUp Response: ' . json_encode($response));

        if (isset($response['status']) && $response['status'] === 0) {
            return $this->response->setJSON([
                'status' => 0,
                'message' => 'Top Up berhasil!',
                'data' => $response['data']
            ]);
        }

        return $this->response->setJSON([
            'status' => 500,
            'message' => 'Terjadi kesalahan saat proses top up',
            'data' => null
        ]);
    }

    private function getProfile($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile';
        $response = $this->sendRequest($url, $token);
        return $response['data'] ?? null;
    }

    private function getBalance($token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/balance';
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
