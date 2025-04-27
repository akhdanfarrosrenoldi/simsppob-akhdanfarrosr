<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMS PPOB - Akhdan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<main class="w-full h-screen flex flex-col md:flex-row">

  <!-- Left Section -->
  <div class="w-full md:w-1/2 flex flex-col justify-center px-8 py-12 bg-white">
    <div class="flex items-center mb-10">
      <img src="<?= base_url('assets/images/Logo.png') ?>" class="w-10 h-10 rounded-full mr-3 object-cover" alt="Logo">
      <span class="text-2xl font-bold text-gray-800">SIMS PPOB</span>
    </div>

    <h1 class="text-3xl font-bold mb-8">Lengkapi data untuk membuat akun</h1>

    <!-- Flashdata Success -->
    <?php if (session()->getFlashdata('success')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <!-- Flashdata Errors -->
    <?php if (session()->getFlashdata('errors')) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?php foreach (session('errors') as $error) : ?>
          <div><?= $error ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('registration/register') ?>" method="post" class="space-y-4">

      <!-- Email -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-email text-xl"></i>
        </span>
        <input type="email" name="email" value="<?= old('email') ?>" placeholder="Masukkan email anda"
               class="w-full pl-10 p-3 border <?= session('errors.email') ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>

      <!-- Nama Depan -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-account text-xl"></i>
        </span>
        <input type="text" name="first_name" value="<?= old('first_name') ?>" placeholder="Nama depan"
               class="w-full pl-10 p-3 border <?= session('errors.first_name') ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>

      <!-- Nama Belakang -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-account text-xl"></i>
        </span>
        <input type="text" name="last_name" value="<?= old('last_name') ?>" placeholder="Nama belakang"
               class="w-full pl-10 p-3 border <?= session('errors.last_name') ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-400">
      </div>

      <!-- Password -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-lock text-xl"></i>
        </span>
        <input type="password" name="password" id="password" placeholder="Buat password"
               class="w-full pl-10 pr-10 p-3 border <?= session('errors.password') ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-500">
          <i class="mdi mdi-eye text-2xl"></i>
        </button>
      </div>

      <!-- Konfirmasi Password -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-lock text-xl"></i>
        </span>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi password"
               class="w-full pl-10 pr-10 p-3 border <?= session('errors.confirm_password') ? 'border-red-500' : 'border-gray-300' ?> rounded focus:outline-none focus:ring-2 focus:ring-red-400">
        <button type="button" id="toggleConfirmPassword" class="absolute right-3 top-3 text-gray-500">
          <i class="mdi mdi-eye text-2xl"></i>
        </button>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded hover:bg-red-600 transition">
        Registrasi
      </button>

    </form>

    <p class="mt-6 text-sm text-gray-600 text-center">
      Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-red-500 font-semibold">Login di sini</a>
    </p>

  </div>

  <!-- Right Section -->
  <div class="hidden md:block md:w-1/2">
    <img src="<?= base_url('assets/images/Illustrasi Login.png') ?>" class="h-full w-full object-cover" alt="Illustration">
  </div>

</main>

<!-- Script Show/Hide Password -->
<script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    const pwd = document.getElementById('password');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
    this.querySelector('i').classList.toggle('mdi-eye-off');
  });

  document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
    const pwd = document.getElementById('confirm_password');
    pwd.type = pwd.type === 'password' ? 'text' : 'password';
    this.querySelector('i').classList.toggle('mdi-eye-off');
  });
</script>

</body>
</html>
