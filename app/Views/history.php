<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMS PPOB - Akhdan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>
<body class="bg-white min-h-screen">

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

  <!-- Filter Bulan -->
  <div class="mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Semua Transaksi</h2>

    <!-- Filter Bulan -->
    <div class="flex gap-2 overflow-x-auto mb-4">
      <?php foreach ($months as $key => $month) : ?>
        <button class="month-btn bg-gray-100 p-2 rounded hover:bg-red-500 hover:text-white" data-month="<?= $key ?>">
          <?= $month ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Transaction List -->
    <div id="transactionList">
      <?php foreach ($history as $item): ?>
        <div class="border-b py-4">
          <div class="font-semibold"> <?= esc($item['transaction_type']) ?> - <?= esc($item['description']) ?> </div>
          <div class="text-gray-500 text-sm"> <?= date('d-m-Y H:i', strtotime($item['created_on'])) ?> </div>
          <div class="mt-2 <?= $item['transaction_type'] === 'TOPUP' ? 'text-green-600' : 'text-red-600' ?>">
            Rp <?= number_format($item['total_amount'], 0, ',', '.') ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Show More Button -->
    <div class="text-center mt-6">
      <button id="loadMore" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
        Show more
      </button>
    </div>
  </div>
</div>

<!-- JS Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let offset = <?= count($history) ?>;
const limit = <?= $limit ?>;

// Load More Button
$('#loadMore').click(function() {
  $.get('<?= base_url('history/loadMoreHistory') ?>', { offset, limit }, function(response) {
    if (response.status === 200 && response.data.length) {
      response.data.forEach(item => {
        const html = `
          <div class="border-b py-4">
            <div class="font-semibold">${item.transaction_type} - ${item.description}</div>
            <div class="text-gray-500 text-sm">${new Date(item.created_on).toLocaleString('id-ID')}</div>
            <div class="mt-2 ${item.transaction_type === 'TOPUP' ? 'text-green-600' : 'text-red-600'}">
              Rp ${item.total_amount.toLocaleString('id-ID')}
            </div>
          </div>
        `;
        $('#transactionList').append(html);
      });
      offset += limit;
    } else {
      $('#loadMore').hide(); // Hide button if no more data
    }
  });
});

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

// Filter by Month
$('.month-btn').click(function() {
  // Highlight selected month
  $('.month-btn').removeClass('bg-red-500 text-white').addClass('bg-gray-100 text-black');
  $(this).removeClass('bg-gray-100 text-black').addClass('bg-red-500 text-white');

  const month = $(this).data('month');
  $.get('<?= base_url('history/filterByMonth') ?>', { month }, function(response) {
    $('#transactionList').empty(); // Clear transaction list before appending new results
    if (response.status === 200 && response.data.length) {
      response.data.forEach(item => {
        const html = `
          <div class="border-b py-4">
            <div class="font-semibold">${item.transaction_type} - ${item.description}</div>
            <div class="text-gray-500 text-sm">${new Date(item.created_on).toLocaleString('id-ID')}</div>
            <div class="mt-2 ${item.transaction_type === 'TOPUP' ? 'text-green-600' : 'text-red-600'}">
              Rp ${item.total_amount.toLocaleString('id-ID')}
            </div>
          </div>
        `;
        $('#transactionList').append(html);
      });
    } else {
      $('#transactionList').html('<p class="text-center text-gray-500">Tidak ada transaksi bulan ini.</p>');
      $('#loadMore').hide(); // Hide button if no more data
    }
  });
});
</script>

</body>
</html>
