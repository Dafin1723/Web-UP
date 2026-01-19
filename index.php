<!DOCTYPE html>
<html lang="id">
<head>
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- FAVICON & TASKBAR SESUAI LOGO FIKRI PRODUCTION -->
  <link rel="icon" href="https://files.catbox.moe/0c7i5q.png" type="image/png"> <!-- 32x32 -->
  <link rel="apple-touch-icon" href="https://files.catbox.moe/0c7i5q.png">
  <link rel="shortcut icon" href="https://files.catbox.moe/0c7i5q.png" type="image/png">
  
  <!-- Warna taskbar Chrome Android & Safari iOS -->
  <meta name="theme-color" content="#EA580C"> <!-- orange FIKRI PRODUCTION -->
  <meta name="msapplication-TileColor" content="#EA580C">
  <meta name="msapplication-navbutton-color" content="#EA580C">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title>FIKRI PRODUCTION - Print, Sablon, Merch</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">

  <!-- Header -->
  <div class="bg-white shadow-lg">
    <div class="container mx-auto px-6 py-6 flex justify-between items-center">
      <h1 class="text-4xl font-bold text-indigo-600">Unit Produksi</h1>
      <p class="text-xl text-gray-700">Cetak, Sablon, Merch — Semua Ada!</p>
    </div>
  </div>

  <!-- Hero Dashboard Layanan -->
  <div class="container mx-auto px-6 py-16">
    <h2 class="text-5xl font-bold text-center mb-16 text-gray-800">Pilih Layanan Kamu</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 max-w-6xl mx-auto">

      <!-- Print Dokumen (AKTIF) -->
      <div onclick="openPrint()" class="bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-3xl shadow-2xl p-10 cursor-pointer transform hover:scale-105 transition duration-300">
        <div class="text-8xl mb-6 text-center">🖨️</div>
        <h3 class="text-3xl font-bold text-center mb-4">Print Dokumen</h3>
        <p class="text-center text-lg opacity-90">PDF, Word, Gambar — mulai Rp500(kusus santri SMK IT IHSANUL FIKRI)</p>
        <p class="text-center mt-6 bg-white text-blue-700 px-6 py-3 rounded-full font-bold">KLIK UNTUK ORDER</p>
      </div>

      <!-- Yang lain (Coming Soon) -->
      <div class="bg-gradient-to-br from-pink-500 to-red-600 text-white rounded-3xl shadow-2xl p-10 opacity-80">
        <div class="text-8xl mb-6 text-center">☕</div>
        <h3 class="text-3xl font-bold text-center mb-4">Cetak Mug</h3>
        <p class="text-center text-lg opacity-90">Custom sesuai selara dangan gambara anda senderi</p>
        <p class="text-center mt-6 bg-white/20 px-6 py-3 rounded-full">Segera Hadir</p>
      </div>
      <div class="bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-3xl shadow-2xl p-10 opacity-80">
        <div class="text-8xl mb-6 text-center">👕</div>
        <h3 class="text-3xl font-bold text-center mb-4">Sablon Baju</h3>
        <p class="text-center mt-6 bg-white/20 px-6 py-3 rounded-full">Segera Hadir</p>
      </div>
      <div class="bg-gradient-to-br from-yellow-500 to-orange-600 text-white rounded-3xl shadow-2xl p-10 opacity-80">
        <div class="text-8xl mb-6 text-center">🛍️</div>
        <h3 class="text-3xl font-bold text-center mb-4">Cetak Totebag</h3>
        <p class="text-center mt-6 bg-white/20 px-6 py-3 rounded-full">Segera Hadir</p>
      </div>
      <div class="bg-gradient-to-br from-purple-500 to-indigo-700 text-white rounded-3xl shadow-2xl p-10 opacity-80">
        <div class="text-8xl mb-6 text-center">🔖</div>
        <h3 class="text-3xl font-bold text-center mb-4">Gantungan Kunci</h3>
        <p class="text-center mt-6 bg-white/20 px-6 py-3 rounded-full">Segera Hadir</p>
      </div>
      <div onclick="window.open('https://wa.me/6281215258757')" class="bg-gradient-to-br from-gray-700 to-black text-white rounded-3xl shadow-2xl p-10 cursor-pointer hover:scale-105 transition">
        <div class="text-8xl mb-6 text-center">📞</div>
        <h3 class="text-3xl font-bold text-center mb-4">Butuh Custom?</h3>
        <p class="text-center mt-6 bg-green-500 px-6 py-3 rounded-full font-bold">Chat WA Sekarang</p>
      </div>
    </div>
  </div>

  <!-- Modal Print Order -->
  <div id="printModal" class="fixed inset-0 bg-black bg-opacity-70 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-screen overflow-y-auto">
      <div class="flex justify-between items-center p-6 border-b">
        <h2 class="text-3xl font-bold text-indigo-600">Order Print Dokumen</h2>
        <button onclick="closePrint()" class="text-4xl text-gray-500 hover:text-gray-800">&times;</button>
      </div>

      <!-- FORM YANG BENAR-BENAR KIRIM KE upload.php -->
      <form action="upload.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
        <!-- semua input form_print.php lama taruh di sini -->
        <?php include 'form_print.php'; ?>
      </form>
    </div>
  </div>

  <script>
    function openPrint() {
      document.getElementById('printModal').classList.remove('hidden');
    }
    function closePrint() {
      document.getElementById('printModal').classList.add('hidden');
    }
  </script>
</body>
</html>
