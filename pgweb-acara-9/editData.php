<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb_acara8";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah data telah dikirim melalui form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form edit
    $kecamatan = $_POST["kecamatan"];
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $luas = $_POST["luas"];
    $jml_penduduk = $_POST["jml_penduduk"];

    // Query untuk mengupdate data
    $sql = "UPDATE data_kecamatan SET 
                longitude = ?, 
                latitude = ?, 
                luas = ?, 
                jml_penduduk = ? 
            WHERE kecamatan = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $longitude, $latitude, $luas, $jml_penduduk, $kecamatan);

    if ($stmt->execute()) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data. Silakan coba lagi.');
                window.location.href = 'index.php';
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
