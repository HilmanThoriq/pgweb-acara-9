<?php
// Ambil kecamatan dari parameter URL
$kecamatan = $_GET['kecamatan'];

// Konfigurasi koneksi database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb_acara8";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query untuk menghapus data berdasarkan kecamatan, dengan nilai kecamatan dibungkus tanda kutip
$sql = "DELETE FROM data_kecamatan WHERE kecamatan = '$kecamatan'";

if ($conn->query($sql) === TRUE) {
  header('Location: index.php?statusDelete=success');
} else {
  header('Location: index.php?statusDelete=error');
}

// Tutup koneksi
$conn->close();
?>
