<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pembayaran - SIMS PPOB</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">

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
            <img src="<?= !empty($profile['profile_image']) ? $profile['profile_image'] : base_url('assets/images/default-profile.png') ?>" alt="Profile Image" class="w-24 h-24 rounded-full mb-4 object-cover">
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

  <!-- Form Pembayaran -->
  <div class="bg-white rounded-xl shadow-lg p-8 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Pembayaran</h2>

    <div class="flex items-center mb-8 space-x-3">
      <img src="<?= base_url('assets/images/icon-listrik.png') ?>" alt="Service Icon" class="w-8 h-8">
      <span class="font-semibold text-gray-700"><?= esc($service['service_name']) ?></span>
    </div>

    <input type="hidden" id="service_code" value="<?= esc($service['service_code']) ?>">

    <div class="mb-6">
      <input type="text" value="<?= number_format($service['service_tariff'], 0, ',', '.') ?>" readonly
        class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-400">
    </div>

    <button id="btnBayar" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded transition">
      Bayar
    </button>
  </div>

</div>

<!-- Modal Konfirmasi -->
<div id="modalConfirm" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-8 shadow-lg w-full max-w-md">
    <h2 class="text-xl font-bold mb-4">Konfirmasi Pembayaran</h2>
    <p class="mb-6 text-gray-700">Apakah anda yakin ingin melakukan pembayaran ini?</p>
    <div class="flex justify-end gap-4">
      <button id="cancelConfirm" class="px-4 py-2 border rounded-lg">Batal</button>
      <button id="confirmBayar" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Ya, Bayar</button>
    </div>
  </div>
</div>

<!-- Modal Berhasil -->
<div id="modalSuccess" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl p-8 shadow-lg w-full max-w-md text-center">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Pembayaran Berhasil!</h2>
    <p class="mb-6 text-gray-700">Transaksi Anda telah berhasil diproses.</p>
    <button id="gotoHome" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600">Kembali ke Home</button>
  </div>
</div>

<!-- JS -->
<script>
  const balance = document.getElementById('balance');
    const toggleButton = document.getElementById('toggleBalance');

    toggleButton.addEventListener('click', function () {
        if (balance.innerText.includes('•')) {
            balance.innerText = '<?= number_format($balance['balance'] ?? 0, 0, ',', '.') ?>';
            this.innerText = 'Sembunyikan Saldo';
        } else {
            balance.innerText = '••••••••';
            this.innerHTML = 'Lihat Saldo <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 inline" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2C5 2 1.73 6.11 1 10c.73 3.89 4 8 9 8s8.27-4.11 9-8c-.73-3.89-4-8-9-8zM10 16c-2.5 0-4.71-1.28-6-3.22C5.29 10.28 7.5 9 10 9s4.71 1.28 6 3.22C14.71 14.72 12.5 16 10 16z"/></svg>';
        }
    });
// Tombol bayar
$('#btnBayar').click(function () {
  $('#modalConfirm').removeClass('hidden');
});

// Tombol batal konfirmasi
$('#cancelConfirm').click(function () {
  $('#modalConfirm').addClass('hidden');
});

// Konfirmasi bayar
$('#confirmBayar').click(function () {
  const serviceCode = $('#service_code').val();
  $.post("<?= base_url('pembayaran/submit') ?>", { service_code: serviceCode }, function(response) {
    if (response.status === 0) {
      $('#modalConfirm').addClass('hidden');
      $('#modalSuccess').removeClass('hidden');
    } else {
      alert('Gagal: ' + (response.message || 'Terjadi kesalahan'));
      $('#modalConfirm').addClass('hidden');
    }
  }).fail(function() {
    alert('Gagal koneksi ke server');
    $('#modalConfirm').addClass('hidden');
  });
});



// Tombol ke Home setelah berhasil
$('#gotoHome').click(function () {
  window.location.href = "<?= base_url('home') ?>";
});
</script>

</body>
</html>
