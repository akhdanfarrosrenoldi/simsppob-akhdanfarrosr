<!doctype html>
<html lang="id">
<head>
  <title>SIMS PPOB Transaction History</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
  <style>
    .navbar {
      padding: 0;
      height: 100px;
      align-items: center;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 24px;
      color: black;
      margin-left: 20px;
    }
    .navbar-nav .nav-link {
      font-weight: bold;
      color: black !important;
      margin-right: 20px;
      font-size: 16px;
    }
    .card {
      margin-bottom: 20px;
    }
    .transaction-item {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
    .btn-group .btn {
      margin-right: 10px;
      border: none;
    }
    .month-btn.active {
      background-color: #007bff;
      color: white;
    }
    .btn-show-more {
      background-color: #dc3545; 
      color: white;
    }
    .btn-show-more:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
<section class="ftco-section">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="<?= session()->get('isLoggedIn') ? base_url('home') : base_url('registration') ?>">
        <img src="<?= base_url(); ?>/assets/images/Logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">
        SIMS PPOB
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="fa fa-bars"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a href="<?= base_url('topup') ?>" class="nav-link">Top Up</a></li>
          <li class="nav-item"><a href="<?= base_url('history') ?>" class="nav-link">Transaction</a></li>
          <li class="nav-item"><a href="<?= base_url('profile') ?>" class="nav-link">Akun</a></li>
          <li class="nav-item"><a href="<?= base_url('logout'); ?>" class="nav-link">Log Out</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="row">
      <!-- Profil -->
      <div class="col-md-6 mb-4">
        <div class="card p-4">
          <div class="text-left">
            <img src="<?= $profile['profile_image'] ?>" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="width: 100px;">
            <h4>Selamat datang, <?= $profile['first_name'] ?> <?= $profile['last_name'] ?></h4>
          </div>
        </div>
      </div>

      <!-- Saldo -->
      <div class="col-md-6 mb-4">
        <div class="card text-white" style="background-image: url('<?= base_url('assets/images/Background Saldo.png'); ?>'); background-size: cover; background-position: center;">
          <div class="card-body d-flex flex-column justify-content-left text-left" style="background-color: rgba(0, 0, 0, 0); border-radius: 0.5rem;">
            <h4>Saldo Anda</h4>
            <p class="h3">Rp <span id="balance"><?= number_format($balance['balance'], 0, ',', '.') ?></span></p>
            <button class="btn btn-light mt-3" id="toggleBalance">Lihat Saldo</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Transaction History -->
    <div class="card p-4 mt-4">
      <h4>Semua Transaksi</h4>

      <!-- Pilihan Bulan -->
      <div class="btn-group mt-3" role="group" aria-label="Pilih Bulan">
        <?php foreach ($months as $key => $value) : ?>
          <button type="button" class="btn btn-outline-primary month-btn" data-month="<?= $key; ?>"><?= $value; ?></button>
        <?php endforeach; ?>
      </div>

      <div id="transactionList" class="mt-4">
        <?php if (!empty($history)) : ?>
          <?php foreach ($history as $item) : ?>
            <div class="transaction-item">
              <strong><?= $item['transaction_type']; ?>:</strong> <?= $item['description']; ?> <br>
              <small><?= date('d-m-Y H:i:s', strtotime($item['created_on'])); ?></small> <br>
              <strong>Total:</strong> 
              <span class="<?= ($item['transaction_type'] === 'TOPUP') ? 'text-success' : 'text-danger'; ?>">
                Rp <?= number_format($item['total_amount'], 0, ',', '.'); ?>
              </span>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p>Tidak ada transaksi.</p>
        <?php endif; ?>
      </div>

      <button class="btn btn-show-more mt-3" id="loadMore">Tampilkan Lebih Banyak</button>
    </div>
  </div>
</section>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

<script>
  const balance = document.getElementById('balance');
  balance.style.filter = 'blur(5px)';

  document.getElementById('toggleBalance').addEventListener('click', function () {
    const isBlurred = balance.style.filter === 'blur(5px)';
    balance.style.filter = isBlurred ? 'none' : 'blur(5px)';
    this.textContent = isBlurred ? 'Sembunyikan Saldo' : 'Lihat Saldo';
  });

  let offset = <?= count($history) ?>;
  const limit = <?= $limit ?>;

  $('#loadMore').click(function() {
    const selectedMonth = $('.month-btn.active').data('month');
    $.ajax({
      url: "<?= base_url('history/loadMoreHistory') ?>",
      method: "GET",
      data: {
        offset: offset,
        limit: limit,
        month: selectedMonth
      },
      success: function(response) {
        if (response.status === 200) {
          if (response.data.length > 0) {
            response.data.forEach(function(item) {
              const amountClass = item.transaction_type === 'TOPUP' ? 'text-success' : 'text-danger';
              $('#transactionList').append(`
                <div class="transaction-item">
                  <strong>${item.transaction_type}:</strong> ${item.description} <br>
                  <small>${new Date(item.created_on).toLocaleString('id-ID')}</small> <br>
                  <strong>Total:</strong> 
                  <span class="${amountClass}">
                    Rp ${item.total_amount.toLocaleString('id-ID')}
                  </span>
                </div>
              `);
            });
            offset += limit;
          } else {
            $('#loadMore').hide();
          }
        }
      }
    });
  });

  // Pilih bulan dengan klik
  $('.month-btn').click(function() {
    $('.month-btn').removeClass('active');
    $(this).addClass('active');
    
    const selectedMonth = $(this).data('month');
    $.ajax({
      url: "<?= base_url('history/filterByMonth') ?>",
      method: "GET",
      data: { month: selectedMonth },
      success: function(response) {
        $('#transactionList').empty();
        if (response.status === 200 && response.data.length > 0) {
          response.data.forEach(function(item) {
            const amountClass = item.transaction_type === 'TOPUP' ? 'text-success' : 'text-danger';
            $('#transactionList').append(`
              <div class="transaction-item">
                <strong>${item.transaction_type}:</strong> ${item.description} <br>
                <small>${new Date(item.created_on).toLocaleString('id-ID')}</small> <br>
                <strong>Total:</strong> 
                <span class="${amountClass}">
                  Rp ${item.total_amount.toLocaleString('id-ID')}
                </span>
              </div>
            `);
          });
          $('#loadMore').show();
        } else {
          $('#transactionList').html('<p>Tidak ada transaksi pada bulan ini.</p>');
          $('#loadMore').hide();
        }
        offset = response.data.length;
      }
    });
  });
</script>

</body>
</html>
