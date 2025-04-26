<!doctype html>
<html lang="id">
  <head>
    <title>SIM PPOB Homepage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
  </head>
  <body>
    <section class="ftco-section">
      <!-- Full-width Navbar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-full">
        <!-- Logo SIM PPOB and Local Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url() ?>">
          <img src="<?= base_url(); ?>/assets/images/Logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">
          SIM PPOB
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a href="#" class="nav-link">Top Up</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Transaction</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Akun</a></li>
            <li class="nav-item"><a href="<?= base_url('logout'); ?>" class="nav-link">Log Out</a></li> <!-- Log Out Link -->
          </ul>
        </div>
      </nav>

      <div class="container">
        <!-- Profile Section -->
        <div class="row mt-5">
          <div class="col-md-4">
            <img src="<?= $profile['profile_image'] ?>" alt="Profile Image" class="img-fluid rounded-circle" style="width: 150px;">
          </div>
          <div class="col-md-8">
            <h2>Selamat datang, <?= $profile['first_name'] ?> <?= $profile['last_name'] ?></h2>
            <p>Email: <?= $profile['email'] ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <p><strong>Balance:</strong> Rp <span id="balance"><?= number_format($balance['balance'], 0, ',', '.') ?></span></p>
              <button class="btn btn-outline-secondary" id="toggleBalance">Lihat Saldo</button>
            </div>
          </div>
        </div>

        <!-- Services Section -->
        <h3 class="mt-5">Layanan yang Tersedia</h3>
        <div class="row">
          <?php foreach ($services as $service): ?>
            <div class="col-md-4 mb-4">
              <div class="card">
                <img src="<?= $service['service_icon'] ?>" class="card-img-top" alt="<?= $service['service_name'] ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= $service['service_name'] ?></h5>
                  <p class="card-text">Tariff: Rp <?= number_format($service['service_tariff'], 0, ',', '.') ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Banner Section -->
        <h3 class="mt-5">Temukan Promo Menarik</h3>
        <div id="bannerCarousel" class="carousel slide" data-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($banners as $index => $banner): ?>
              <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                <img src="<?= $banner['banner_image'] ?>" class="d-block w-100" alt="<?= $banner['banner_name'] ?>">
                <div class="carousel-caption d-none d-md-block">
                  <h5><?= $banner['banner_name'] ?></h5>
                  <p><?= $banner['description'] ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </section>

    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <script>
      // Toggle Balance Visibility
      document.getElementById('toggleBalance').addEventListener('click', function() {
        var balanceElement = document.getElementById('balance');
        var isHidden = balanceElement.style.display === 'none';
        balanceElement.style.display = isHidden ? 'inline' : 'none';
        this.textContent = isHidden ? 'Sembunyikan Saldo' : 'Lihat Saldo';
      });
    </script>
  </body>
</html>
