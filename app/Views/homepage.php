<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage - SIM PPOB</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h1>Selamat datang, <?= esc($user_name) ?>!</h1>
    <p>Ini adalah halaman utama setelah login.</p>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
  </div>
</body>
</html>
