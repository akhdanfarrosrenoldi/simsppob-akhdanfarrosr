<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TopUpController extends Controller
{
    public function index()
    {
        // Ambil token dari session
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        // Ambil profile dan balance
        $profile = $this->getProfile($token);
        $balance = $this->getBalance($token);

        // Kirim ke view
        return view('topup', [
            'profile' => $profile,
            'balance' => $balance
        ]);
    }

    public function topUp()
    {
        // Ambil token
        $token = session()->get('token');
        if (!$token) {
            return $this->response->setJSON(['status' => 401, 'message' => 'Unauthorized']);
        }

        // Ambil data JSON dari request
        $request = $this->request->getJSON();
        $topUpAmount = $request->top_up_amount ?? null;

        // Validasi nominal
        if ($topUpAmount === null || !is_numeric($topUpAmount) || $topUpAmount < 10000 || $topUpAmount > 1000000) {
            return $this->response->setJSON([
                'status' => 102,
                'message' => 'Nominal top up harus antara 10.000 dan 1.000.000',
                'data' => null
            ]);
        }

        // Kirim request ke API top up
        $url = 'https://take-home-test-api.nutech-integrasi.com/topup';
        $response = $this->sendRequest($url, $token, [
            'top_up_amount' => (int) $topUpAmount
        ]);

        // Log respons untuk debugging
        log_message('debug', 'API TopUp Response: ' . json_encode($response));

        // Pastikan respons dari API valid
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
