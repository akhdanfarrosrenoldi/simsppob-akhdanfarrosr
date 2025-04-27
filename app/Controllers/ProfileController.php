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

    if (empty($profile['profile_image'])) {
        $profile['profile_image'] = base_url('assets/images/default-profile.png');
    }

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
            return $this->response->setJSON(['status' => 1, 'message' => 'Unauthorized']);
        }

        $data = $this->request->getJSON(true);

        if (empty($data['first_name']) || empty($data['last_name'])) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Nama depan dan belakang harus diisi.']);
        }

        $apiResponse = $this->sendUpdateRequest($token, $data);

        if ($apiResponse['status'] === 0) {
            return $this->response->setJSON(['status' => 0, 'message' => 'Profil berhasil diperbarui.']);
        } else {
            return $this->response->setJSON(['status' => 1, 'message' => $apiResponse['message'] ?? 'Gagal memperbarui profil.']);
        }
    }

    public function updateProfileImage()
    {
        $token = session()->get('token');
        if (!$token) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Unauthorized']);
        }

        $file = $this->request->getFile('profile_image');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['status' => 1, 'message' => 'File tidak valid.']);
        }

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png'])) {
            return $this->response->setJSON(['status' => 1, 'message' => 'Format file harus JPEG atau PNG.']);
        }

        $filePath = $file->getTempName();
        $curlFile = new \CURLFile($filePath, $file->getMimeType(), $file->getName());

        $ch = curl_init('https://take-home-test-api.nutech-integrasi.com/profile/image');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => 'PUT', // HARUS PUT!
            CURLOPT_POSTFIELDS => ['file' => $curlFile]
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return $this->response->setJSON(['status' => 1, 'message' => 'Gagal mengirim gambar.']);
        }

        curl_close($ch);

        $response = json_decode($response, true);

        if (isset($response['status']) && $response['status'] === 0) {
            return $this->response->setJSON([
                'status' => 0,
                'message' => 'Foto profil berhasil diperbarui!',
                'data' => $response['data'] ?? []
            ]);
        } else {
            return $this->response->setJSON(['status' => 1, 'message' => $response['message'] ?? 'Gagal update foto profil.']);
        }
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

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Accept: application/json'
            ]
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

        $payload = json_encode([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']
        ]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ],
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
