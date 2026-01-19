<!DOCTYPE html>
<html class="dark">
<head><title>Pembayaran Order Print</title><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
<div class="bg-gray-800 p-10 rounded-2xl shadow-2xl text-center">
<?php
$amount = $_GET['amount'] ?? 0;
$via = $_GET['via'] ?? 'Unknown';
echo "<h1 class=\"text-4xl font-bold text-indigo-400 mb-6\">Scan $via</h1>";
echo "<p class=\"text-2xl mb-8\">Total: Rp " . number_format($amount,0,',','.') . "</p>";
echo "<img src=\"https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" . urlencode($_SERVER['REQUEST_URI']) . "\" class=\"mx-auto\">";
?>
<p class="mt-8 text-green-400">Terima kasih udah order bro! 😎</p>
</div>
</body>
</html>
