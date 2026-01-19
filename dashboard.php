<?php
session_start();
$password = '172323';

if (!isset($_SESSION['admin_login'])) {
    if (isset($_POST['pass']) && $_POST['pass'] === $password) {
        $_SESSION['admin_login'] = true;
    } else {
        echo '<!DOCTYPE html><html><head><title>Login Admin</title>
              <script src="https://cdn.tailwindcss.com"></script></head>
              <body class="bg-gradient-to-br from-indigo-600 to-purple-700 min-h-screen flex items-center justify-center">
              <form method="post" class="bg-white p-12 rounded-3xl shadow-2xl text-center">
                <h2 class="text-4xl font-bold mb-8 text-indigo-600">Login Admin</h2>
                <input type="password" name="pass" placeholder="Password" required 
                       class="w-full px-6 py-4 text-xl border-2 border-indigo-300 rounded-xl focus:outline-none focus:border-indigo-600">
                <button type="submit" class="mt-8 w-full bg-indigo-600 text-white py-4 rounded-xl text-xl font-bold hover:bg-indigo-700 transition">
                  Masuk Dashboard
                </button>
              </form></body></html>';
        exit;
    }
}
if (isset($_GET['logout'])) { session_destroy(); header('Location: dashboard.php'); exit; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FIKRI PRODUCTION - Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto p-8">
    <div class="flex justify-between items-center mb-10">
      <h1 class="text-5xl font-bold text-indigo-600">Dashboard Admin</h1>
      <a href="?logout=1" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">Logout</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <a href="list.php" class="bg-blue-600 text-white p-10 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition text-center">
        <div class="text-6xl mb-4">Print Dokumen</div>
        <p class="text-xl">Lihat & Kelola Order</p>
      </a>
