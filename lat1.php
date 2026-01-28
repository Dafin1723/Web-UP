<?php
echo "halo PHP <br>";

$page = $_GET['page'] ?? 'kosong';
echo "output dari hasil get di atas : " . $page;
echo "<br><br>";

$nama = $_GET['nama'] ?? 'anonim';
echo "Halo " . $nama;
