<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS PPOB - Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

<!-- Navbar -->
<nav class="bg-white shadow-sm">
    <div class="container mx-auto flex justify-between items-center px-6 py-4">
        <div class="flex items-center space-x-2">
            <img src="<?= base_url('assets/images/Logo.png') ?>" alt="Logo" class="w-8 h-8">
            <span class="font-bold text-lg">SIMS PPOB</span>
        </div>
        <div class="flex space-x-8 font-semibold">
            <a href="<?= base_url('topup') ?>" class="hover:text-red-500">Top Up</a>
            <a href="<?= base_url('history') ?>" class="hover:text-red-500">Transaction</a>
            <a href="<?= base_url('profile') ?>" class="hover:text-red-500">Akun</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto px-6 py-10">

    <!-- Profile and Balance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
        <div class="flex flex-col items-center md:items-start">
            <img src="<?= !empty($profile['profile_image']) ? $profile['profile_image'] : base_url('assets/images/default-profile.png') ?>" alt="Profile Image" class="w-24 h-24 rounded-full mb-4 object-cover">
            <h2 class="text-gray-600 text-lg">Selamat datang,</h2>
            <h1 class="text-2xl font-bold"><?= esc($profile['first_name']) ?> <?= esc($profile['last_name']) ?></h1>
        </div>

        <div class="bg-red-500 text-white rounded-2xl p-6 flex flex-col items-start justify-center">
            <h4 class="text-lg">Saldo anda</h4>
            <p class="text-3xl mt-2 font-bold">Rp <span id="balance">••••••••</span></p>
            <button id="toggleBalance" class="flex items-center mt-4 text-white font-semibold hover:underline">
                Lihat Saldo
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2C5 2 1.73 6.11 1 10c.73 3.89 4 8 9 8s8.27-4.11 9-8c-.73-3.89-4-8-9-8zM10 16c-2.5 0-4.71-1.28-6-3.22C5.29 10.28 7.5 9 10 9s4.71 1.28 6 3.22C14.71 14.72 12.5 16 10 16z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Services -->
    <div class="mt-12">
        <div class="flex space-x-6 overflow-x-auto py-4">
            <?php foreach ($services as $service): ?>
                <div class="flex-shrink-0 text-center">
                    <a href="<?= base_url('pembayaran/'.$service['service_code']); ?>" class="block hover:scale-110 transition">
                        <img src="<?= $service['service_icon'] ?>" alt="<?= $service['service_name'] ?>" class="w-12 h-12 mx-auto mb-2">
                        <div class="text-xs font-semibold text-gray-700"><?= esc($service['service_name']) ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Banners -->
    <div class="mt-14">
        <h3 class="text-xl font-bold mb-6">Temukan promo menarik</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($banners as $banner): ?>
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <img src="<?= $banner['banner_image'] ?>" alt="<?= $banner['banner_name'] ?>" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h5 class="text-md font-bold"><?= esc($banner['banner_name']) ?></h5>
                        <p class="text-sm text-gray-600"><?= esc($banner['description']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</div>

<!-- Script Toggle Balance -->
<script>
    const balance = document.getElementById('balance');
    const toggleButton = document.getElementById('toggleBalance');

    toggleButton.addEventListener('click', function () {
        if (balance.innerText.includes('•')) {
            balance.innerText = '<?= number_format($balance['balance'] ?? 0, 0, ',', '.') ?>';
            this.innerText = 'Sembunyikan Saldo';
        } else {
            balance.innerText = '••••••••';
            this.innerHTML = 'Lihat Saldo <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 inline" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2C5 2 1.73 6.11 1 10c.73 3.89 4 8 9 8s8.27-4.11 9-8c-.73-3.89-4-8-9-8zM10 16c-2.5 0-4.71-1.28-6-3.22C5.29 10.28 7.5 9 10 9s4.71 1.28 6 3.22C14.71 14.72 12.5 16 10 16z"/></svg>';
        }
    });
</script>

</body>
</html>
