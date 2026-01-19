<?php
session_start();
$password = '172323';  // <<< GANTI KALAU MAU UBAH PASSWORD

// === LOGIN ===
if (!isset($_SESSION['login'])) {
    if (!isset($_POST['pass']) || $_POST['pass'] !== $password) {
        echo '<!DOCTYPE html><html><head><title>Login Admin</title><script src="https://cdn.tailwindcss.com"></script></head>
              <body class="bg-gray-800 flex items-center justify-center min-h-screen">
              <form method="post" class="bg-white p-10 rounded-xl shadow-2xl">
              <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Login Admin Print Order</h2>
              <input type="password" name="pass" class="w-full px-4 py-3 border rounded-lg mb-4" placeholder="Password" required>
              <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700">Login</button>
              </form></body></html>';
        if (isset($_POST['pass']) && $_POST['pass'] !== $password) echo '<p class="text-red-600 text-center mt-4 font-bold">Password Salah Bro!</p>';
        exit;
    }
    if ($_POST['pass'] === $password) $_SESSION['login'] = true;
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: list.php'); exit; }

require '../config.php';

// === ORDER SELESAI -> PINDAH KE RIWAYAT ===
if (isset($_GET['selesai'])) {
    $id = (int)$_GET['selesai'];
    $r = $conn->query("SELECT * FROM orders WHERE id = $id")->fetch_assoc();
    if ($r) {
        $conn->query("INSERT INTO riwayat 
            (order_id, nama, email, jumlah_lembar, warna, ukuran_kertas, pembayaran, deskripsi, original_name, harga_total)
            VALUES 
            ({$r['id']}, '{$r['nama']}', '{$r['email']}', {$r['jumlah_lembar']}, '{$r['warna']}', '{$r['ukuran_kertas']}', '{$r['pembayaran']}', '{$r['deskripsi']}', '{$r['original_name']}', {$r['harga_total']})");
        
        $conn->query("DELETE FROM orders WHERE id = $id");
        echo "<script>alert('Order selesai & dipindah ke riwayat!'); window.location='list.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Order Aktif</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="container mx-auto p-8">
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-4xl font-bold text-indigo-600">Order Aktif (Belum Selesai)</h1>
    <div>
      <a href="riwayat.php" class="bg-green-600 text-white px-6 py-3 rounded-lg mr-3 hover:bg-green-700">Lihat Riwayat</a>
      <a href="?logout=1" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">Logout</a>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full table-auto">
      <thead class="bg-indigo-600 text-white">
        <tr>
          <th class="p-4">No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Ukuran</th>
          <th>Jenis</th>
          <th>Lembar</th>
          <th>Total Harga</th>
          <th>Pembayaran</th>
          <th>File</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
        $no = 1;
        while ($r = $res->fetch_assoc()): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 text-center"><?=$no++?></td>
            <td><?=htmlspecialchars($r['nama'])?></td>
            <td><?=htmlspecialchars($r['email'])?></td>
            <td><span class="font-bold"><?=$r['ukuran_kertas']?></span></td>
            <td><?=$r['warna']?></td>
            <td><?=$r['jumlah_lembar']?></td>
            <td class="font-bold text-green-600">Rp <?=number_format($r['harga_total'])?></td>
            <td><span class="px-3 py-1 rounded text-white <?=($r['pembayaran']=='Tunai di Tempat')?'bg-green-600':'bg-blue-600'?>"><?=$r['pembayaran']?></span></td>
            <td><a href="../uploads/<?=$r['filename']?>" target="_blank" class="text-indigo-600 font-bold underline">Download</a></td>
            <td>
              <a href="?selesai=<?=$r['id']?>" onclick="return confirm('Yakin order ini sudah selesai?')" 
                 class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700">Selesai ✓</a>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if ($res->num_rows == 0): ?>
          <tr><td colspan="10" class="text-center py-10 text-gray-500 text-xl">Belum ada order aktif</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
