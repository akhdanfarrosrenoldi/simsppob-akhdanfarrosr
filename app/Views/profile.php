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
      <img src="<?= !empty($profile['profile_image']) ? $profile['profile_image'] : base_url('assets/images/default-profile.png') ?>" 
           onerror="this.onerror=null;this.src='<?= base_url('assets/images/default-profile.png') ?>';" 
           alt="Profile Image" 
           class="w-36 h-36 rounded-full object-cover mb-4">
      <h3 class="text-xl font-semibold"><?= esc($profile['first_name']) ?> <?= esc($profile['last_name']) ?></h3>
    </div>

    <!-- Kanan: Info Profil -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-1">Email</label>
        <p class="text-gray-800"><?= esc($profile['email']) ?></p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-1">Nama Depan</label>
        <p class="text-gray-800"><?= esc($profile['first_name']) ?></p>
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-1">Nama Belakang</label>
        <p class="text-gray-800"><?= esc($profile['last_name']) ?></p>
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

</body>
</html>
