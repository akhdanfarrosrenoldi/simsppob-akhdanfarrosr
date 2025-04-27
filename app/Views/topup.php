<!doctype html>
<html lang="id">
<head>
  <title>SIMS PPOB Top Up</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,700" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
  <style>
    .topup-button { width: 100%; margin-bottom: 10px; }
    .balance-card {
      background-image: url('<?= base_url('assets/images/Background Saldo.png'); ?>');
      background-size: cover;
      background-position: center;
      color: white;
    }
    .form-topup {
      margin-top: 30px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 10px;
    }

     /* Navbar style */
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
      /* Service Row */
      .services-row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
      }
      .service-card {
        flex: 0 0 auto;
        width: 100px;
        margin-right: 10px;
        text-align: center;
      }
      .service-card img {
        width: 50px;
        height: 50px;
        object-fit: contain;
      }
      /* Card Style */
      .card {
        max-height: 350px;
        overflow: hidden;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
      }
      .card-body {
        padding: 20px;
      }
      .carousel-item img {
        height: 200px;
        object-fit: cover;
      }

      /* Flexbox for equal card height */
      .row-equal .col-md-6 {
        display: flex;
        justify-content: space-between;
        flex-direction: column;
      }
      .row-equal .card {
        flex: 1;
      }
  </style>
</head>

<body>
  <section class="ftco-section">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="<?= session()->get('isLoggedIn') ? base_url('home') : base_url('registration') ?>">
          <img src="<?= base_url(); ?>/assets/images/Logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">
          SIMS PPOB
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a href="<?= base_url('topup') ?>" class="nav-link">Top Up</a></li>
            <li class="nav-item"><a href="<?= base_url('history') ?>" class="nav-link">Transaction</a></li>
            <li class="nav-item"><a href="<?= base_url('profile') ?>" class="nav-link">Akun</a></li>
            <li class="nav-item"><a href="<?= base_url('logout') ?>" class="nav-link">Log Out</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container mt-5">
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card p-4">
            <div class="text-left">
              <img src="<?= $profile['profile_image'] ?>" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="width: 100px;">
              <h4>Selamat datang, <?= esc($profile['first_name']) ?> <?= esc($profile['last_name']) ?></h4>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card balance-card p-4">
            <h4>Saldo Anda</h4>
            <p class="h3" id="balance"><?= number_format($balance['balance'], 0, ',', '.') ?></p>
            <button class="btn btn-light mt-3" id="toggleBalance">Lihat Saldo</button>
          </div>
        </div>
      </div>

      <div class="form-topup">
        <h5>Top Up Saldo</h5>
        <div class="form-group">
          <input type="number" class="form-control" id="nominal" name="nominal" placeholder="Masukkan nominal Top Up (Minimal 10.000, Maksimal 1.000.000)" min="10000" max="1000000" required>
        </div>

        <div class="row text-center">
          <?php
            $presetNominal = [10000, 20000, 50000, 100000, 250000, 500000];
            foreach ($presetNominal as $value):
          ?>
            <div class="col-6 col-md-4 mb-2">
              <button type="button" class="btn btn-outline-primary topup-button" data-value="<?= $value ?>">Rp <?= number_format($value, 0, ',', '.') ?></button>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-primary btn-block" id="btnSubmit" disabled>Top Up</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal for confirmation -->
  <div class="modal" tabindex="-1" role="dialog" id="confirmModal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Top Up</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin melakukan top up sebesar <span id="confirmAmount"></span>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
          <button type="button" class="btn btn-primary" id="confirmTopUp">Ya, Top Up</button>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

  <script>
  // Toggle Saldo
  const balanceElement = document.getElementById('balance');
  balanceElement.style.filter = 'blur(5px)';
  document.getElementById('toggleBalance').addEventListener('click', function () {
    const isBlurred = balanceElement.style.filter === 'blur(5px)';
    balanceElement.style.filter = isBlurred ? 'none' : 'blur(5px)';
    this.textContent = isBlurred ? 'Sembunyikan Saldo' : 'Lihat Saldo';
  });

  // Enable submit button
  function enableSubmitButton() {
    const nominal = document.getElementById('nominal').value;
    document.getElementById('btnSubmit').disabled = !(nominal >= 10000 && nominal <= 1000000);
  }

  document.getElementById('nominal').addEventListener('input', enableSubmitButton);

  document.querySelectorAll('.topup-button').forEach(button => {
    button.addEventListener('click', function () {
      document.getElementById('nominal').value = this.dataset.value;
      enableSubmitButton();
    });
  });

  // Show confirmation modal
  document.getElementById('btnSubmit').addEventListener('click', function () {
    const nominal = parseInt(document.getElementById('nominal').value);
    if (nominal >= 10000 && nominal <= 1000000) {
      document.getElementById('confirmAmount').textContent = 'Rp ' + nominal.toLocaleString('id-ID');
      $('#confirmModal').modal('show');
    }
  });

  // Confirm top-up
  document.getElementById('confirmTopUp').addEventListener('click', function () {
    const nominal = parseInt(document.getElementById('nominal').value);
    $.ajax({
      url: '<?= base_url('topup/topUp') ?>',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({
        top_up_amount: nominal
      }),
      success: function (response) {
        console.log(response);  // Log response untuk debug
        if (response.status === 0) {
          alert('Top Up Berhasil! Saldo Baru: Rp ' + response.data.balance.toLocaleString('id-ID'));
          location.reload();  // Reload halaman untuk memperbarui saldo
        } else if (response.status === 102) {
          alert('Error: ' + response.message);
        } else if (response.status === 108) {
          alert('Session habis, silakan login kembali.');
          window.location.href = "<?= base_url('login') ?>";
        } else {
          alert('Gagal: ' + response.message);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error); // Log error untuk debugging
        alert('Terjadi kesalahan pada server.');
      }
    });
    $('#confirmModal').modal('hide');
  });

  </script>

</body>
</html>
