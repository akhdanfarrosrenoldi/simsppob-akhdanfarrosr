<!doctype html>
<html lang="id">
  <head>
    <title>SIMS PPOB Homepage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
    <style>
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
              <li class="nav-item"><a href="#" class="nav-link">Transaction</a></li>
              <li class="nav-item"><a href="#" class="nav-link">Akun</a></li>
              <li class="nav-item"><a href="<?= base_url('logout'); ?>" class="nav-link">Log Out</a></li>
            </ul>
          </div>
        </div>
      </nav>

      <div class="container mt-5">
        <div class="row row-equal">
          <!-- Kiri: Profil -->
          <div class="col-md-6 mb-4">
            <div class="card p-4">
              <div class="text-left">
              <img src="<?= $profile['profile_image'] ?>" alt="Profile Image" class="img-fluid rounded-circle mb-3" style="width: 100px;">
              <h4>Selamat datang, <?= $profile['first_name'] ?> <?= $profile['last_name'] ?></h4>
              </div>
            </div>
          </div>

          <!-- Kanan: Saldo -->
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

        <!-- Services -->
        <div class="services-row mt-4">
          <?php foreach ($services as $service): ?>
            <div class="service-card">
              <img src="<?= $service['service_icon'] ?>" alt="<?= $service['service_name'] ?>">
              <h6 class="mt-2"><?= $service['service_name'] ?></h6>
              <!-- Harga tidak ditampilkan -->
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Banner -->
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

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

    <script>
      // Saldo blur on load
      const balance = document.getElementById('balance');
      balance.style.filter = 'blur(5px)';

      // Toggle blur
      document.getElementById('toggleBalance').addEventListener('click', function () {
        const isBlurred = balance.style.filter === 'blur(5px)';
        balance.style.filter = isBlurred ? 'none' : 'blur(5px)';
        this.textContent = isBlurred ? 'Sembunyikan Saldo' : 'Lihat Saldo';
      });
    </script>
  </body>
</html>
