k<?php
session_start();
$password = '172323'; // ganti kalau mau

// Login
if (!isset($_SESSION['admin_login'])) {
    if (isset($_POST['pass']) && $_POST['pass'] === $password) {
        $_SESSION['admin_login'] = true;
    } else {
        echo '<!DOCTYPE html><html><head><title>Login Admin</title><script src="https://cdn.tailwindcss.com"></script></head>
              <body class="bg-gray-900 flex items-center justify-center min-h-screen">
              <form method="post" class="bg-white p-10 rounded-2xl shadow-2xl">
                <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Login Catatan Warung</h2>
                <input type="password" name="pass" placeholder="Password" required class="w-full px-6 py-4 border-2 rounded-xl text-lg">
                <button type="submit" class="mt-6 w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-xl hover:bg-indigo-700">Login</button>
              </form></body></html>';
        if (isset($_POST['pass'])) echo '<p class="text-red-600 text-center mt-4 font-bold">Password salah!</p>';
        exit;
    }
}

require '../config.php';

// Pastikan tabel keuangan ada (kalau belum otomatis dibuat)
$conn->query("CREATE TABLE IF NOT EXISTS keuangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    pemasukan INT DEFAULT 0,
    pengeluaran INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// Tambah catatan
if (isset($_POST['tambah'])) {
    $t = $_POST['tanggal'];
    $k = $conn->real_escape_string($_POST['keterangan']);
    $m = (int)$_POST['masuk'];
    $k = (int)$_POST['keluar'];
    $conn->query("INSERT INTO keuangan (tanggal,keterangan,pemasukan,pengeluaran) VALUES ('$t','$k',$m,$k)");
}

// Hapus
if (isset($_GET['hapus'])) {
    $conn->query("DELETE FROM keuangan WHERE id = ".(int)$_GET['hapus']);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Catatan Warung</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="container mx-auto p-8 max-w-6xl">
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-4xl font-bold text-green-600">📊 Catatan Warung</h1>
    <div>
      <a href="list.php" class="bg-indigo-600 text-white px-6 py-3 rounded-lg mr-3">📋 Order Print</a>
      <a href="?logout=1" class="bg-red-600 text-white px-6 py-3 rounded-lg">Logout</a>
    </div>
  </div>

  <?php if(isset($_GET['logout'])) { session_destroy(); header('Location: keuangan.php'); } ?>

  <!-- Form Input -->
  <div class="bg-white p-6 rounded-xl shadow mb-8">
    <form method="post" class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <input type="date" name="tanggal" value="<?=date('Y-m-d')?>" required class="border p-3 rounded-lg">
      <input type="text" name="keterangan" placeholder="Keterangan (misal: Beli kertas)" required class="border p-3 rounded-lg">
      <input type="number" name="masuk" placeholder="Pemasukan" value="0" class="border p-3 rounded-lg text-green-600 font-bold">
      <input type="number" name="keluar" placeholder="Pengeluaran" value="0" class="border p-3 rounded-lg text-red-600 font-bold">
      <button type="submit" name="tambah" class="bg-green-600 text-white p-3 rounded-lg font-bold hover:bg-green-700">+ Tambah</button>
    </form>
  </div>

  <!-- Tabel -->
  <div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full">
      <thead class="bg-green-600 text-white">
        <tr>
          <th class="p-4">Tanggal</th>
          <th class="p-4">Keterangan</th>
          <th class="p-4">Pemasukan</th>
          <th class="p-4">Pengeluaran</th>
          <th class="p-4">Saldo</th>
          <th class="p-4">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $saldo = 0;
        $res = $conn->query("SELECT * FROM keuangan ORDER BY tanggal DESC, id DESC");
        while($r = $res->fetch_assoc()):
          $saldo += $r['pemasukan'] - $r['pengeluaran'];
        ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-4 text-center"><?=date('d-m-Y', strtotime($r['tanggal']))?></td>
            <td class="p-4"><?=htmlspecialchars($r['keterangan'])?></td>
            <td class="p-4 text-green-600 font-bold">+Rp <?=number_format($r['pemasukan'])?></td>
            <td class="p-4 text-red-600 font-bold">-Rp <?=number_format($r['pengeluaran'])?></td>
            <td class="p-4 font-bold text-xl">Rp <?=number_format($saldo)?></td>
            <td class="p-4 text-center">
              <a href="?hapus=<?=$r['id']?>" onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:underline">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if($res->num_rows == 0): ?>
          <tr><td colspan="6" class="text-center py-10 text-gray-500 text-xl">Belum ada catatan keuangan</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
