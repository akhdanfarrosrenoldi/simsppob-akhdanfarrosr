<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - SIM PPOB</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/style.css">
</head>
<body>

<div class="container mt-5">
    <!-- Logout Button -->
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a> <!-- Ubah menjadi link GET -->
        </div>
    </div>

    <!-- Profile Section -->
    <div class="row mt-3">
        <div class="col-md-4">
            <img src="<?= $profile['profile_image'] ?>" alt="Profile Image" class="img-fluid rounded-circle" style="width: 150px;">
        </div>
        <div class="col-md-8">
            <h2>Welcome, <?= $profile['first_name'] ?> <?= $profile['last_name'] ?></h2>
            <p>Email: <?= $profile['email'] ?></p>
            <p><strong>Balance:</strong> Rp <?= number_format($balance['balance'], 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Service Section -->
    <h3 class="mt-5">Available Services</h3>
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
    <h3 class="mt-5">Our Banners</h3>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

</body>
</html>
