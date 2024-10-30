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
    // Ambil data dari form
    $old_kecamatan = $_POST["old_kecamatan"]; // Nama kecamatan sebelum diubah
    $new_kecamatan = $_POST["kecamatan"]; // Nama kecamatan yang baru
    $longitude = $_POST["longitude"];
    $latitude = $_POST["latitude"];
    $luas = $_POST["luas"];
    $jml_penduduk = $_POST["jml_penduduk"];

    // Query untuk mengupdate data di database
    $sql = "UPDATE data_kecamatan SET 
                kecamatan = ?, 
                longitude = ?, 
                latitude = ?, 
                luas = ?, 
                jml_penduduk = ? 
            WHERE kecamatan = ?";

    // Persiapan statement untuk menghindari SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $new_kecamatan, $longitude, $latitude, $luas, $jml_penduduk, $old_kecamatan);

    // Eksekusi query dan cek apakah berhasil
    if ($stmt->execute()) {
        // Jika berhasil, arahkan kembali ke halaman utama dengan pesan sukses
        header('Location: index.php?status=success');
    } else {
        // Jika gagal, arahkan kembali ke halaman utama dengan pesan error
        header('Location: index.php?status=error');
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
