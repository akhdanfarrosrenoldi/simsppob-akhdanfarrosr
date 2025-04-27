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

        return view('profile', compact('profile'));
    }

    public function edit()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        $profile = $this->getProfile($token);

        return view('edit_profile', compact('profile'));
    }

    public function updateProfile()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        $data = $this->request->getPost();

        if (empty($data['first_name']) || empty($data['last_name'])) {
            session()->setFlashdata('error', 'Nama depan dan nama belakang harus diisi.');
            return redirect()->back()->withInput();
        }

        $apiResponse = $this->sendUpdateRequest($token, $data);

        if ($apiResponse['status'] === 0) {
            session()->setFlashdata('success', 'Profil berhasil diperbarui.');
            return redirect()->to('/profile');
        } else {
            session()->setFlashdata('error', $apiResponse['message'] ?? 'Gagal memperbarui profil.');
            return redirect()->back()->withInput();
        }
    }

    public function updateProfileImage()
    {
        $token = session()->get('token');
        if (!$token) {
            return redirect()->to('/login');
        }

        $file = $this->request->getFile('profile_image');

        if (!$file || !$file->isValid()) {
            session()->setFlashdata('error', 'File tidak valid.');
            return redirect()->back();
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            session()->setFlashdata('error', 'Format file harus JPEG atau PNG.');
            return redirect()->back();
        }

        $filePath = $file->getTempName();
        $curlFile = new \CURLFile($filePath, $file->getMimeType(), $file->getName());

        $ch = curl_init('https://take-home-test-api.nutech-integrasi.com/profile/image');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['file' => $curlFile]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            session()->setFlashdata('error', 'Gagal mengirim gambar.');
            return redirect()->back();
        }

        curl_close($ch);

        $response = json_decode($response, true);

        if (isset($response['status']) && $response['status'] === 0) {
            session()->setFlashdata('success', 'Foto profil berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', $response['message'] ?? 'Gagal update foto profil.');
        }

        return redirect()->to('/profile');
    }

    private function getProfile(string $token)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile';
        $response = $this->sendRequest($url, $token);

        return $response['data'] ?? null;
    }

    private function sendRequest(string $url, string $token)
    {
        $ch = curl_init($url);

        $headers = [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ];

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return ['status' => 500, 'message' => 'Request error'];
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    private function sendUpdateRequest(string $token, array $data)
    {
        $url = 'https://take-home-test-api.nutech-integrasi.com/profile/update';

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        $payload = json_encode([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']
        ]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => $payload
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return ['status' => 500, 'message' => 'Request error'];
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
