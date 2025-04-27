<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMS PPOB - Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<main class="w-full h-screen flex flex-col md:flex-row">

  <!-- Bagian Kiri -->
  <div class="w-full md:w-1/2 flex flex-col justify-center px-8 py-12 bg-white">
    <div class="flex items-center mb-10">
      <img src="<?= base_url('assets/images/Logo.png') ?>" class="w-10 h-10 rounded-full mr-3 object-cover" alt="Logo">
      <span class="text-2xl font-bold text-gray-800">SIMS PPOB</span>
    </div>

    <h1 class="text-3xl font-bold mb-8">Masuk atau buat akun untuk memulai</h1>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <?= session()->getFlashdata('success') ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <?= session()->getFlashdata('error') ?>
      </div>
    <?php endif; ?>

    <!-- Form Login -->
    <form action="<?= base_url('login') ?>" method="post" class="space-y-5">

      <!-- Input Email -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-email text-xl"></i>
        </span>
        <input type="email" name="email" value="<?= old('email') ?>" placeholder="Masukkan email anda"
               class="w-full pl-10 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400" required>
      </div>

      <!-- Input Password -->
      <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <i class="mdi mdi-lock text-xl"></i>
        </span>
        <input type="password" name="password" id="password" placeholder="Masukkan password anda"
               class="w-full pl-10 pr-10 p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400" required>
        <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-500">
          <i class="mdi mdi-eye text-2xl"></i>
        </button>
      </div>

      <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded hover:bg-red-600 transition">
        Masuk
      </button>
    </form>

    <p class="mt-6 text-sm text-gray-600 text-center">
      Belum punya akun? <a href="<?= base_url('registration') ?>" class="text-red-500 font-semibold">Registrasi di sini</a>
    </p>
  </div>

  <!-- Bagian Kanan -->
  <div class="hidden md:block md:w-1/2 h-full">
    <img src="<?= base_url('assets/images/Illustrasi Login.png') ?>" alt="Ilustrasi Login" class="h-full w-full object-cover">
  </div>

</main>

<!-- Script -->
<script>
  document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordField = document.getElementById("password");
    const icon = this.querySelector("i");
    if (passwordField.type === "password") {
      passwordField.type = "text";
      icon.classList.remove("mdi-eye");
      icon.classList.add("mdi-eye-off");
    } else {
      passwordField.type = "password";
      icon.classList.remove("mdi-eye-off");
      icon.classList.add("mdi-eye");
    }
  });
</script>

</body>
</html>
