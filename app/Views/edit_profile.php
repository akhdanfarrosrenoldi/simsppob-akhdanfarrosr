<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMS PPOB - Akhdan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
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

<!-- Modal Notifikasi -->
<div id="notificationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div id="modalContent" class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md text-center transform transition-all scale-95 opacity-0">
    <div id="modalIcon" class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-green-500">
      <svg id="iconSvg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path id="iconPath" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="" />
      </svg>
    </div>
    <h2 id="notificationTitle" class="text-2xl font-bold mb-2"></h2>
    <p id="notificationMessage" class="text-gray-600 mb-6"></p>
    <button onclick="closeModal()" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">OK</button>
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

  if (file.size > 100 * 1024) {
    showModal('Gagal', 'Ukuran file maksimal 100 KB.', false);
    return;
  }

  const formData = new FormData();
  formData.append('profile_image', file);

  fetch('<?= base_url('profile/image'); ?>', {
    method: 'POST',
    headers: { 'X-HTTP-Method-Override': 'PUT' },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 0) {
      document.getElementById('profileImage').src = data.data.profile_image;
      showModal('Berhasil', 'Foto profil berhasil diperbarui!', true);
    } else {
      showModal('Gagal', data.message || 'Gagal update foto.', false);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showModal('Error', 'Gagal upload foto.', false);
  });
}

document.getElementById('editProfileForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const firstName = document.getElementById('first_name').value.trim();
  const lastName = document.getElementById('last_name').value.trim();

  if (!firstName || !lastName) {
    showModal('Gagal', 'Nama depan dan belakang harus diisi.', false);
    return;
  }

  fetch('<?= base_url('profile/update'); ?>', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ first_name: firstName, last_name: lastName })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 0) {
      showModal('Berhasil', 'Profil berhasil diperbarui!', true);
      setTimeout(() => window.location.href = "<?= base_url('profile'); ?>", 1500);
    } else {
      showModal('Gagal', data.message || 'Gagal update profil.', false);
    }
  })
  .catch(error => {
    console.error('Error:', error);
    showModal('Error', 'Terjadi kesalahan saat update.', false);
  });
});

function showModal(title, message, success) {
  document.getElementById('notificationTitle').innerText = title;
  document.getElementById('notificationMessage').innerText = message;

  const iconBg = document.getElementById('modalIcon');
  const iconPath = document.getElementById('iconPath');

  if (success) {
    iconBg.className = 'w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-green-500';
    iconPath.setAttribute('d', 'M5 13l4 4L19 7');
  } else {
    iconBg.className = 'w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-full bg-red-500';
    iconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
  }

  const modal = document.getElementById('notificationModal');
  const content = document.getElementById('modalContent');
  modal.classList.remove('hidden');
  setTimeout(() => {
    content.classList.remove('scale-95', 'opacity-0');
    content.classList.add('scale-100', 'opacity-100');
  }, 50);
}

function closeModal() {
  const modal = document.getElementById('notificationModal');
  const content = document.getElementById('modalContent');
  content.classList.add('scale-95', 'opacity-0');
  content.classList.remove('scale-100', 'opacity-100');
  setTimeout(() => {
    modal.classList.add('hidden');
  }, 200);
}
</script>

</body>
</html>
