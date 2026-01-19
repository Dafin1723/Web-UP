kk<?php
$host = 'db';                  // nama service MySQL di docker-compose
$user = 'fikri';
$pass = 'fikri123';
$db   = 'print_order';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
