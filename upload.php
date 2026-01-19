<?php
// Aktifin error biar keliatan kalau ada yang salah
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'config.php';

// Kalau koneksi gagal langsung kasih tahu
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Pastikan tabel orders & keuangan ada
$conn->query("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    ukuran_kertas ENUM('A4','Sertifikat','A3+','Stiker F4') DEFAULT 'A4',
    warna ENUM('Hitam Putih','Warna') NOT NULL,
    jumlah_lembar INT NOT NULL,
    pembayaran VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    filename VARCHAR(255),
    original_name VARCHAR(255),
    harga_total INT DEFAULT 0,
    upload_time DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS keuangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    pemasukan INT DEFAULT 0,
    pengeluaran INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_upload'])) {
    $nama       = $conn->real_escape_string($_POST['nama']);
    $email      = $conn->real_escape_string($_POST['email']);
    $ukuran     = $_POST['ukuran_kertas'];
    $warna      = $_POST['warna'];
    $pembayaran = $_POST['pembayaran'];
    $deskripsi  = $conn->real_escape_string($_POST['deskripsi']);
    $file       = $_FILES['file_upload'];
    $ext        = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Default jumlah lembar
    $jumlah = 1;
    if (isset($_POST['jumlah_lembar']) && !empty($_POST['jumlah_lembar'])) {
        $jumlah = (int)$_POST['jumlah_lembar'];
    }

    // Kalau PDF → auto detect pake Imagick
    if ($ext === 'pdf' && extension_loaded('imagick')) {
        try {
            $img = new Imagick();
            $img->pingImage($file['tmp_name']);
            $jumlah = $img->getNumberImages();
            $img->clear();
            $img->destroy();
        } catch (Exception $e) {
            // Kalau gagal, tetap pake manual
        }
    }

    // Hitung harga
    if ($ukuran == 'A4') {
        $harga_per = ($warna == 'Warna') ? 1000 : 500;
    } else {
        $harga_per = match($ukuran) {
            'Sertifikat' => 2000,
            'A3+' => 10000,
            'Stiker F4' => 5000,
            default => 500
        };
    }
    $total = $jumlah * $harga_per;

    // Upload file
    $newname = uniqid('print_') . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], 'uploads/' . $newname)) {
        $sql = "INSERT INTO orders 
                (nama,email,ukuran_kertas,warna,jumlah_lembar,pembayaran,deskripsi,filename,original_name,harga_total) 
                VALUES 
                ('$nama','$email','$ukuran','$warna',$jumlah,'$pembayaran','$deskripsi','$newname','{$file['name']}',$total)";
        
        if ($conn->query($sql)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  <script>
                  Swal.fire({
                    title: 'Order Sukses!',
                    html: 'File: <b>{$file['name']}</b><br>Lembar: <b>$jumlah</b><br>Total: <b>Rp ".number_format($total)."</b>',
                    icon: 'success'
                  }).then(() => location='index.php');
                  </script>";
        } else {
            echo "Error simpan database: " . $conn->error;
        }
    } else {
        echo "Gagal upload file! Pastikan folder uploads ada dan permission 755";
    }
} else {
    echo "Tidak ada file yang diupload atau method salah";
}
?>
