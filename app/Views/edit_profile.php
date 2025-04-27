<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMS PPOB - Edit Profile</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
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

<!-- Content -->
<div class="container mx-auto px-4 py-10">
  <div class="flex flex-col md:flex-row gap-8">
    <div class="flex flex-col items-center w-full md:w-1/2">
      <!-- Foto Profil -->
      <img id="profileImage"
           src="<?= !empty($profile['profile_image']) ? $profile['profile_image'] : base_url('assets/images/default-profile.png') ?>" 
           onerror="this.onerror=null;this.src='<?= base_url('assets/images/default-profile.png') ?>';" 
           alt="Profile Image"
           class="w-36 h-36 rounded-full object-cover cursor-pointer mb-4"
           onclick="uploadImage()">
      
      <!-- Input File Upload -->
      <input type="file" id="fileInput" class="hidden" accept="image/*" onchange="updateProfileImage(event)">

      <div class="text-center">
        <h3 class="text-lg font-semibold"><?= esc($profile['first_name']) ?> <?= esc($profile['last_name']) ?></h3>
      </div>
    </div>

    <div class="w-full md:w-1/2">
      <!-- Form Edit Profile -->
      <form id="editProfileForm" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Email</label>
          <input type="email" value="<?= esc($profile['email']) ?>" class="w-full p-2 border rounded bg-gray-100" disabled>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">Nama Depan</label>
          <input type="text" id="first_name" value="<?= esc($profile['first_name']) ?>" class="w-full p-2 border rounded">
        </div>
        <div class="mb-6">
          <label class="block text-gray-700 mb-2">Nama Belakang</label>
          <input type="text" id="last_name" value="<?= esc($profile['last_name']) ?>" class="w-full p-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700">Update Profile</button>
      </form>
    </div>
  </div>
</div>

<!-- Script -->
<script>
function uploadImage() {
  document.getElementById('fileInput').click();
}

function updateProfileImage(event) {
  const file = event.target.files[0];
  if (!file) return;

  if (file.size > 100 * 1024) { // maksimal 100KB
    alert('Ukuran file maksimal 100 KB.');
    return;
  }

  const formData = new FormData();
  formData.append('profile_image', file);

  fetch('<?= base_url('profile/image'); ?>', {
    method: 'POST',
    headers: {
      'X-HTTP-Method-Override': 'PUT'
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 0) {
      document.getElementById('profileImage').src = data.data.profile_image;
      alert('Foto profil berhasil diperbarui!');
    } else {
      alert(data.message || 'Gagal update foto.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Gagal upload foto.');
  });
}

// Edit Nama Depan / Belakang
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const firstName = document.getElementById('first_name').value.trim();
  const lastName = document.getElementById('last_name').value.trim();

  if (!firstName || !lastName) {
    alert('Nama depan dan belakang harus diisi.');
    return;
  }

  fetch('<?= base_url('profile/update'); ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      first_name: firstName,
      last_name: lastName
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 0) {
      alert('Profil berhasil diperbarui!');
      window.location.href = "<?= base_url('profile'); ?>";
    } else {
      alert(data.message || 'Gagal update profil.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Terjadi kesalahan saat update.');
  });
});
</script>

</body>
</html>
