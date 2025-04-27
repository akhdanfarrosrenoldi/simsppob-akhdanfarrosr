<!doctype html>
<html lang="id">
<head>
  <title>SIMS PPOB - Akhdan</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      </button>
    </div>
  </div>

  <!-- Top Up Form -->
  <div class="bg-white rounded-xl shadow-lg p-8 mt-10 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6 text-center">Silakan masukan nominal top up</h2>

    <div class="mb-6">
      <input type="number" id="nominal" min="10000" max="1000000"
             placeholder="Masukkan nominal (Minimal 10.000)"
             class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-400" required>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
      <?php $presetNominal = [10000, 20000, 50000, 100000, 250000, 500000];
      foreach ($presetNominal as $value): ?>
        <button type="button" class="topup-preset bg-gray-100 hover:bg-red-100 font-bold py-3 rounded-lg"
                data-value="<?= $value ?>">
          Rp <?= number_format($value, 0, ',', '.') ?>
        </button>
      <?php endforeach; ?>
    </div>

    <button id="btnSubmit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg" disabled>
      Top Up
    </button>
  </div>
</div>

<!-- Modal Konfirmasi -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
  <div class="bg-white rounded-xl p-8 shadow-lg max-w-md w-full text-center">
    <h2 class="text-xl font-bold mb-6">Konfirmasi Top Up</h2>
    <p class="text-gray-600 mb-6">Top Up sebesar <span id="confirmAmount" class="font-bold"></span>?</p>
    <div class="flex justify-center gap-4">
      <button id="cancelModal" class="px-4 py-2 border rounded-lg">Batal</button>
      <button id="confirmTopUp" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Konfirmasi</button>
    </div>
  </div>
</div>

<!-- Modal Notification -->
<div id="notifModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div id="notifContent" class="bg-white rounded-xl p-8 shadow-lg w-full max-w-md text-center">
    <div id="notifIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-green-500">
      <svg id="notifSvg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path id="notifPath" d="" />
      </svg>
    </div>
    <h2 id="notifTitle" class="text-2xl font-bold mb-2"></h2>
    <p id="notifMessage" class="text-gray-600 mb-6"></p>
    <button onclick="closeNotif()" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">OK</button>
  </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
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

document.querySelectorAll('.topup-preset').forEach(button => {
  button.addEventListener('click', function() {
    document.getElementById('nominal').value = this.dataset.value;
    document.getElementById('btnSubmit').disabled = false;
  });
});

document.getElementById('nominal').addEventListener('input', function() {
  const nominal = parseInt(this.value);
  document.getElementById('btnSubmit').disabled = !(nominal >= 10000 && nominal <= 1000000);
});

document.getElementById('btnSubmit').addEventListener('click', function() {
  const nominal = document.getElementById('nominal').value;
  if (nominal >= 10000 && nominal <= 1000000) {
    document.getElementById('confirmAmount').innerText = 'Rp ' + parseInt(nominal).toLocaleString('id-ID');
    document.getElementById('confirmModal').classList.remove('hidden');
  }
});

document.getElementById('cancelModal').addEventListener('click', function() {
  document.getElementById('confirmModal').classList.add('hidden');
});

document.getElementById('confirmTopUp').addEventListener('click', function () {
  const nominal = parseInt(document.getElementById('nominal').value);
  $.ajax({
    url: "<?= base_url('topup/topUp') ?>",
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify({ top_up_amount: nominal }),
    success: function (response) {
      if (response.status === 0) {
        showNotif('Berhasil', 'Top Up berhasil! Saldo baru: Rp ' + response.data.balance.toLocaleString('id-ID'), true);
        setTimeout(() => location.reload(), 2000);
      } else {
        showNotif('Gagal', response.message || 'Top Up gagal.', false);
      }
    },
    error: function() {
      showNotif('Error', 'Terjadi kesalahan server.', false);
    }
  });
  document.getElementById('confirmModal').classList.add('hidden');
});

function showNotif(title, message, success) {
  document.getElementById('notifTitle').innerText = title;
  document.getElementById('notifMessage').innerText = message;
  const icon = document.getElementById('notifIcon');
  const path = document.getElementById('notifPath');
  if (success) {
    icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-green-500';
    path.setAttribute('d', 'M5 13l4 4L19 7');
  } else {
    icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500';
    path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
  }
  document.getElementById('notifModal').classList.remove('hidden');
}

function closeNotif() {
  document.getElementById('notifModal').classList.add('hidden');
}
</script>

</body>
</html>
