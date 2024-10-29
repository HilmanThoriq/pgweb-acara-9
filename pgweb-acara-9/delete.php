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
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'index.php';
          </script>";
} else {
    echo "<script>
            alert('Data gagal dihapus: " . $conn->error . "');
            window.location.href = 'index.php';
          </script>";
}

// Tutup koneksi
$conn->close();
?>
