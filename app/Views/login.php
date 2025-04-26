<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SIMS PPOB - Login</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/login.css">
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="brand-wrapper">
            <img src="<?= base_url(); ?>/assets/images/Logo.png" alt="logo" class="logo">
            <span class="logo-text" style="font-size: 24px; font-weight: bold;">SIMS PPOB</span>
          </div>
          <div class="login-wrapper my-auto">
            <h1 class="login-title">Masuk atau buat akun untuk memulai</h1>

            <!-- Menampilkan pesan error jika ada -->
            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post">
              <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukan email anda" required value="<?= old('email') ?>">
              </div>
              <div class="form-group mb-4 position-relative">
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukan password anda" required>
                <div class="input-group-append position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                  <button class="btn btn-light" type="button" id="togglePassword">
                    <i class="mdi mdi-eye"></i>
                  </button>
                </div>
              </div>
              <input name="login" id="login" class="btn btn-block login-btn" type="submit" value="Masuk">
            </form>

            <p class="login-wrapper-footer-text">Belum punya akun? Registrasi <a href="<?= base_url('registration') ?>" class="text-reset">disini</a></p>
          </div>
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="<?= base_url(); ?>/assets/images/Illustrasi Login.png" alt="login image" class="login-img">
        </div>
      </div>
    </div>
  </main>

  <!-- Script -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <script>
    // Toggle password visibility
    document.getElementById("togglePassword").addEventListener("click", function () {
      const passwordField = document.getElementById("password");
      const passwordIcon = document.querySelector("#togglePassword i");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordIcon.classList.remove("mdi-eye");
        passwordIcon.classList.add("mdi-eye-off");
      } else {
        passwordField.type = "password";
        passwordIcon.classList.remove("mdi-eye-off");
        passwordIcon.classList.add("mdi-eye");
      }
    });
  </script>
</body>
</html>
