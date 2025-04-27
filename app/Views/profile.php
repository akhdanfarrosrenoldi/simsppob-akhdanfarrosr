<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMS PPOB - Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
<!-- Navbar -->
<nav class="bg-white shadow-sm">
    <div class="container mx-auto flex justify-between items-center px-6 py-4">
        <div class="flex items-center space-x-2">
            <a href="<?= base_url('home') ?>" class="flex items-center space-x-2">
                <img src="<?= base_url('assets/images/Logo.png') ?>" alt="Logo" class="w-8 h-8">
                <span class="font-bold text-lg">SIMS PPOB</span>
            </a>
        </div>
        <div class="flex space-x-8 font-semibold">
            <a href="<?= base_url('topup') ?>" class="hover:text-red-500">Top Up</a>
            <a href="<?= base_url('history') ?>" class="hover:text-red-500">Transaction</a>
            <a href="<?= base_url('profile') ?>" class="hover:text-red-500">Akun</a>
        </div>
    </div>
</nav>

  <!-- Profile Section -->
  <div class="container mx-auto mt-10 px-4">
    <div class="grid md:grid-cols-2 gap-6">
      
      <!-- Kiri: Foto Profil -->
      <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <img id="profileImage" class="w-36 h-36 rounded-full object-cover cursor-pointer mb-4" 
          src="<?= !empty($profile['profile_image']) ? $profile['profile_image'] : base_url('assets/images/default-profile.png') ?>" 
          alt="Profile Image" onclick="uploadImage()">
        <input type="file" id="fileInput" class="hidden" accept="image/*" onchange="updateProfileImage(event)">
        <h3 class="text-xl font-semibold"><?= $profile['first_name'] ?> <?= $profile['last_name'] ?></h3>
      </div>

      <!-- Kanan: Info Profil -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-1">Email</label>
          <p class="text-gray-800"><?= $profile['email'] ?></p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-1">Nama Depan</label>
          <p class="text-gray-800"><?= $profile['first_name'] ?></p>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-1">Nama Belakang</label>
          <p class="text-gray-800"><?= $profile['last_name'] ?></p>
        </div>

        <div class="flex flex-col gap-3 mt-6">
          <a href="<?= base_url('profile/edit'); ?>" class="w-full text-center py-2 bg-white text-red-500 border border-red-500 rounded hover:bg-red-50 font-bold">
            Edit Profile
          </a>
          <a href="<?= base_url('logout'); ?>" class="w-full text-center py-2 bg-red-500 text-white rounded hover:bg-red-600 font-bold">
            Log Out
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Script -->
  <script>
    function uploadImage() {
      document.getElementById('fileInput').click();
    }

    function updateProfileImage(event) {
      const file = event.target.files[0];
      if (file) {
        const formData = new FormData();
        formData.append('file', file);

        fetch('<?= base_url('profile/image'); ?>', {
          method: 'PUT',
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
          },
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 0) {
            document.getElementById('profileImage').src = data.data.profile_image;
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Gagal memperbarui gambar profil.');
        });
      }
    }
  </script>
</body>
</html>
