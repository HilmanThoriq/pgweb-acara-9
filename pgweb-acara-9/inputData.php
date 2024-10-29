<?php
// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form POST
    $kecamatan = $_POST['kecamatan'];
    $long = $_POST['long'];
    $lat = $_POST['lat'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jml_penduduk'];

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

    // Query untuk menyimpan data ke tabel data_kecamatan
    $sql = "INSERT INTO data_kecamatan (kecamatan, longitude, latitude, luas, jml_penduduk)
            VALUES ('$kecamatan', $long, $lat, $luas, $jumlah_penduduk)";

    // Menyimpan data dan memeriksa apakah berhasil
    if ($conn->query($sql) === TRUE) {
        $message = "Rekord berhasil ditambahkan!";
        $alertType = "success";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $alertType = "error";
    }

    // Menutup koneksi
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kecamatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                      <div class="card-body">
                        <h3 class="mb-3 text-center">Tambah Data Kecamatan</h5>
                        <form action="inputData.php" method="POST">
                            <div class="mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
                            </div>
                            <div class="mb-3">
                                <label for="long" class="form-label">Longitude</label>
                                <input type="number" step="any" class="form-control" id="long" name="long" required>
                            </div>
                            <div class="mb-3">
                                <label for="lat" class="form-label">Latitude</label>
                                <input type="number" step="any" class="form-control" id="lat" name="lat" required>
                            </div>
                            <div class="mb-3">
                                <label for="luas" class="form-label">Luas</label>
                                <input type="number" step="any" class="form-control" id="luas" name="luas" required>
                            </div>
                            <div class="mb-3">
                                <label for="jml_penduduk" class="form-label">Jumlah Penduduk</label>
                                <input type="number" class="form-control" id="jml_penduduk" name="jml_penduduk" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mt-4 mb-2">Simpan Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($message)): ?>
    <script>
        // Menampilkan SweetAlert berdasarkan status penambahan data
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo $alertType === "success" ? "Berhasil" : "Gagal"; ?>',
            text: '<?php echo $message; ?>',
            confirmButtonText: 'Kembali ke Form'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'inputData.php';
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
