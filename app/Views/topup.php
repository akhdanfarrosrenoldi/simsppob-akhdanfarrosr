<!doctype html>
<html lang="id">
<head>
  <title>Top Up - SIMS PPOB</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- TailwindCSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
</head>

<body class="bg-white text-gray-800">

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


<!-- Main Content -->
<div class="container mx-auto px-6 py-10">

       <!-- Profile and Balance -->
       <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="flex flex-col items-center md:items-start">
        <img src="<?= $profile['profile_image'] ?? base_url('assets/images/default-profile.png') ?>" 
     onerror="this.onerror=null;this.src='<?= base_url('assets/images/default-profile.png') ?>';"
     alt="Profile Image" 
     class="w-24 h-24 rounded-full mb-4 object-cover">
            <h2 class="text-gray-600 text-lg">Selamat datang,</h2>
            <h1 class="text-2xl font-bold"><?= esc($profile['first_name']) ?> <?= esc($profile['last_name']) ?></h1>
        </div>

        <div class="bg-red-500 text-white rounded-2xl p-6 flex flex-col items-start justify-center">
            <h4 class="text-lg">Saldo anda</h4>
            <p class="text-3xl mt-2 font-bold">Rp <span id="balance">••••••••</span></p>
            <button id="toggleBalance" class="flex items-center mt-4 text-white font-semibold hover:underline">
                Lihat Saldo
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2C5 2 1.73 6.11 1 10c.73 3.89 4 8 9 8s8.27-4.11 9-8c-.73-3.89-4-8-9-8zM10 16c-2.5 0-4.71-1.28-6-3.22C5.29 10.28 7.5 9 10 9s4.71 1.28 6 3.22C14.71 14.72 12.5 16 10 16z"/>
                </svg>
            </button>
        </div>
    </div>
  <!-- Top Up Form -->
  <div class="bg-white rounded-xl shadow-lg p-8 mt-10 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6 text-center">Silakan masukan nominal top up</h2>

    <div id="alert" class="hidden mb-4 p-4 text-center rounded-lg font-semibold"></div>

    <div class="mb-6">
      <input type="number" id="nominal" min="10000" max="1000000" placeholder="Masukkan nominal (Minimal 10.000)" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-400" required>
    </div>

    <!-- Preset Buttons -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
      <?php
        $presetNominal = [10000, 20000, 50000, 100000, 250000, 500000];
        foreach ($presetNominal as $value):
      ?>
        <button type="button" class="topup-preset bg-gray-100 hover:bg-red-100 font-bold py-3 rounded-lg" data-value="<?= $value ?>">
          Rp <?= number_format($value, 0, ',', '.') ?>
        </button>
      <?php endforeach; ?>
    </div>

    <button id="btnSubmit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg" disabled>
      Top Up Sekarang
    </button>
  </div>

</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center">
  <div class="bg-white rounded-xl p-8 shadow-lg max-w-md w-full">
    <h2 class="text-xl font-bold mb-6">Konfirmasi Top Up</h2>
    <p class="text-gray-600 mb-6">Apakah Anda yakin ingin Top Up sebesar <span id="confirmAmount" class="font-bold"></span>?</p>
    <div class="flex justify-end gap-4">
      <button id="cancelModal" class="px-6 py-2 border border-gray-400 rounded-lg">Batal</button>
      <button id="confirmTopUp" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Konfirmasi</button>
    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
  // Toggle Saldo
  const balance = document.getElementById('balance');
  const toggleButton = document.getElementById('toggleBalance');
  toggleButton.addEventListener('click', function () {
    if (balance.innerText.includes('•')) {
      balance.innerText = '<?= number_format($balance['balance'], 0, ',', '.') ?>';
      this.innerText = 'Sembunyikan Saldo';
    } else {
      balance.innerText = '••••••••';
      this.innerText = 'Lihat Saldo';
    }
  });

  // Preset Buttons
  document.querySelectorAll('.topup-preset').forEach(button => {
    button.addEventListener('click', function() {
      document.getElementById('nominal').value = this.dataset.value;
      document.getElementById('btnSubmit').disabled = false;
    });
  });

  // Enable Submit Button
  document.getElementById('nominal').addEventListener('input', function() {
    const nominal = parseInt(this.value);
    document.getElementById('btnSubmit').disabled = !(nominal >= 10000 && nominal <= 1000000);
  });

  // Show Confirmation Modal
  document.getElementById('btnSubmit').addEventListener('click', function() {
    const nominal = document.getElementById('nominal').value;
    if (nominal >= 10000 && nominal <= 1000000) {
      document.getElementById('confirmAmount').innerText = 'Rp ' + parseInt(nominal).toLocaleString('id-ID');
      document.getElementById('confirmModal').classList.remove('hidden');
    }
  });

  // Cancel Modal
  document.getElementById('cancelModal').addEventListener('click', function() {
    document.getElementById('confirmModal').classList.add('hidden');
  });

  // Confirm TopUp
  document.getElementById('confirmTopUp').addEventListener('click', function () {
    const nominal = parseInt(document.getElementById('nominal').value);
    $.ajax({
      url: "<?= base_url('topup/topUp') ?>",
      method: "POST",
      contentType: "application/json",
      data: JSON.stringify({ top_up_amount: nominal }),
      success: function (response) {
        const alert = document.getElementById('alert');
        if (response.status === 0) {
          alert.classList.remove('hidden', 'bg-red-100', 'text-red-700');
          alert.classList.add('bg-green-100', 'text-green-700');
          alert.innerText = "Top Up berhasil! Saldo baru: Rp " + response.data.balance.toLocaleString('id-ID');
          setTimeout(() => location.reload(), 2000);
        } else {
          alert.classList.remove('hidden', 'bg-green-100', 'text-green-700');
          alert.classList.add('bg-red-100', 'text-red-700');
          alert.innerText = "Gagal: " + response.message;
        }
      },
      error: function() {
        alert('Terjadi kesalahan server.');
      }
    });
    document.getElementById('confirmModal').classList.add('hidden');
  });
</script>

</body>
</html>
