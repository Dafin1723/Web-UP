<?php
session_start();
$password = '172323';  // password kamu

// === CEK LOGIN ===
if (!isset($_SESSION['login'])) {
    if (!isset($_POST['pass']) || $_POST['pass'] !== $password) {
        echo '<!DOCTYPE html><html><head><title>Login Admin</title><script src="https://cdn.tailwindcss.com"></script></head>
              <body class="bg-gray-800 flex items-center justify-center min-h-screen">
              <form method="post" class="bg-white p-10 rounded-xl shadow-2xl">
              <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Login Admin</h2>
              <input type="password" name="pass" class="w-full px-4 py-3 border rounded-lg mb-4" placeholder="Password" required>
              <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold">Login</button>
              </form></body></html>';
        if (isset($_POST['pass']) && $_POST['pass'] !== $password) echo '<p class="text-red-600 text-center mt-4 font-bold">Password Salah!</p>';
        exit;
    }
    if ($_POST['pass'] === $password) $_SESSION['login'] = true;
}

require '../config.php';

// Ambil filter bulan & tahun (default bulan ini)
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$filter = "$tahun-$bulan";  // format YYYY-MM
?>
<!DOCTYPE html>
<html>
<head>
  <title>Riwayat Order - Filter Bulan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="container mx-auto p-8">
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-4xl font-bold text-green-600">Riwayat Order Selesai</h1>
    <div>
      <a href="list.php" class="bg-indigo-600 text-white px-6 py-3 rounded-lg mr-3">← Order Aktif</a>
      <a href="list.php?logout=1" class="bg-red-600 text-white px-6 py-3 rounded-lg">Logout</a>
    </div>
  </div>

  <!-- FILTER BULAN & TAHUN -->
  <div class="bg-white p-6 rounded-xl shadow mb-6 flex flex-wrap gap-4 items-end">
    <div>
      <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Bulan</label>
      <select onchange="window.location='?bulan='+this.value+'&tahun=<?=$tahun?>'" class="px-4 py-3 border rounded-lg">
        <?php for($m=1;$m<=12;$m++): ?>
          <option value="<?=sprintf('%02d',$m)?>" <?=($m==$bulan)?'selected':''?>><?=date('F', mktime(0,0,0,$m,1))?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div>
      <label class="block text-sm font-bold text-gray-700 mb-2">Tahun</label>
      <select onchange="window.location='?tahun='+this.value+'&bulan=<?=$bulan?>'" class="px-4 py-3 border rounded-lg">
        <?php for($y=date('Y'); $y>=2023; $y--): ?>
          <option value="<?=$y?>" <?=($y==$tahun)?'selected':''?>><?=$y?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="text-2xl font-bold text-green-700">
      <?=date('F Y', strtotime($filter))?>
    </div>
  </div>

  <?php
  // Hitung total bulan ini
  $sql_total = "SELECT SUM(harga_total) as tot FROM riwayat WHERE DATE_FORMAT(selesai_time, '%Y-%m') = '$filter'";
  $total_bulan = $conn->query($sql_total)->fetch_assoc()['tot'] ?? 0;

  // Hitung total semua waktu
  $total_all = $conn->query("SELECT SUM(harga_total) as tot FROM riwayat")->fetch_assoc()['tot'] ?? 0;
  ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-green-100 p-6 rounded-xl text-center">
      <p class="text-lg text-gray-700">Pendapatan Bulan Ini</p>
      <p class="text-4xl font-bold text-green-800">Rp <?=number_format($total_bulan)?></p>
    </div>
    <div class="bg-blue-100 p-6 rounded-xl text-center">
      <p class="text-lg text-gray-700">Total Pendapatan Semua Waktu</p>
      <p class="text-4xl font-bold text-blue-800">Rp <?=number_format($total_all)?></p>
    </div>
  </div>

  <!-- TABEL RIWAYAT BULAN INI -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full table-auto">
      <thead class="bg-green-600 text-white">
        <tr>
          <th class="p-4">No</th>
          <th>Tanggal</th>
          <th>Nama</th>
          <th>Lembar</th>
          <th>Jenis</th>
          <th>Harga</th>
          <th>Pembayaran</th>
          <th>deskripsi</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $sql = "SELECT * FROM riwayat WHERE DATE_FORMAT(selesai_time, '%Y-%m') = '$filter' ORDER BY selesai_time DESC";
      $res = $conn->query($sql);
      $no = 1;
      while($r = $res->fetch_assoc()): ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="p-4 text-center"><?=$no++?></td>
          <td><?=date('d-m-Y H:i', strtotime($r['selesai_time']))?></td>
          <td><?=htmlspecialchars($r['nama'])?></td>
          <td><?=$r['jumlah_lembar']?></td>
          <td><?=$r['warna']?></td>
          <td>Rp <?=number_format($r['harga_total'])?></td>
          <td><span class="px-3 py-1 rounded text-white <?=$r['pembayaran']=='Tunai di Tempat'?'bg-green-600':'bg-blue-600'?>"><?=$r['pembayaran']?></span></td>
        </tr>
      <?php endwhile; ?>
      <?php if($res->num_rows == 0): ?>
        <tr><td colspan="7" class="text-center py-10 text-gray-500 text-xl">Belum ada order selesai di bulan ini</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
