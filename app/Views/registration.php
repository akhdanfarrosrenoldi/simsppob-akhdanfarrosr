<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>SIM PPOB - Registrasi</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/login.css">
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="brand-wrapper">
            <img src="<?= base_url(); ?>/assets/images/Logo.png" class="logo" alt="logo">
            <span class="logo-text" style="font-size: 24px; font-weight: bold;">SIMS PPOB</span>
          </div>
          <div class="login-wrapper my-auto">
            <h1 class="login-title">Lengkapi data untuk membuat akun</h1>

            <!-- Notifikasi Error Bootstrap -->
            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error_confirm_password')): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error_confirm_password') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            <?php endif; ?>

            <form action="<?= base_url('registration/register') ?>" method="post">
              <div class="form-group">
                <input type="email" name="email" value="<?= old('email') ?>" class="form-control" placeholder="Masukan email anda" required>
              </div>
              <div class="form-group">
                <input type="text" name="first_name" value="<?= old('first_name') ?>" class="form-control" placeholder="Nama depan" required>
              </div>
              <div class="form-group">
                <input type="text" name="last_name" value="<?= old('last_name') ?>" class="form-control" placeholder="Nama belakang" required>
              </div>
              <!-- Password Field -->
              <div class="form-group mb-4 position-relative">
                <input type="password" name="password" class="form-control" placeholder="Buat password" id="password" required>
                <div class="input-group-append position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                  <button class="btn btn-light" type="button" id="togglePasswordRegister">
                    <i class="mdi mdi-eye"></i>
                  </button>
                </div>
              </div>
              <!-- Confirm Password Field -->
              <div class="form-group mb-4 position-relative">
                <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi password" id="confirm_password" required>
                <div class="input-group-append position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%);">
                  <button class="btn btn-light" type="button" id="toggleConfirmPassword">
                    <i class="mdi mdi-eye"></i>
                  </button>
                </div>
              </div>
              <button type="submit" class="btn btn-block login-btn">Registrasi</button>
            </form>

            <p class="login-wrapper-footer-text">Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-reset">Login disini</a></p>
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
    $(document).ready(function() {
      // Toggle password visibility for the password field
      document.getElementById("togglePasswordRegister").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const passwordIcon = document.querySelector("#togglePasswordRegister i");
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

      // Toggle password visibility for the confirm password field
      document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
        const passwordField = document.getElementById("confirm_password");
        const passwordIcon = document.querySelector("#toggleConfirmPassword i");
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

      // Cek jika ada alert di halaman
      if ($('.alert').length) {
        setTimeout(function() {
          $('.alert').fadeOut('slow');
        }, 4000);  // Alert hilang setelah 4 detik
      }
    });
  </script>
</body>
</html>
