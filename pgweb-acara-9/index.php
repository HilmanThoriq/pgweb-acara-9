<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb_acara8";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM data_kecamatan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Jumlah Penduduk - Peta dan Tabel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            background-color: #f5f5f5;
            padding-top: 60px; /* Memberikan ruang untuk fixed navbar */
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .nav-link {
            font-weight: 500;
        }
        #map {
            height: 500px;
            width: 100%;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .table-container {
            animation: fadeIn 0.6s ease;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .btn-back {
            background-color: #4a90e2;
            color: white;
            border: none;
            font-size: 1rem;
            padding: 8px 20px;
            border-radius: 6px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        .btn-back:hover {
            background-color: #357ABD;
            color: #fff;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-globe2 me-2"></i>
                WebGIS Sleman
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#table-data">
                            <i class="bi bi-info-circle"></i> Tabel Data 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inputData.php">
                            <i class="bi bi-plus-circle"></i> Tambah Data
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Map Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="display-6 mb-4 text-center">Peta Jumlah Penduduk</h2>
                <div id="map"></div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row">
            <div class="col-12">
                <h4 class="display-6 mb-4 text-center" id="table-data">Data Jumlah Penduduk</h4> 
                <div class="card shadow-sm table-container">
                    <div class="card-body">
                        <div class="table-responsive">
                            <?php
                            if ($result->num_rows > 0) {
                                echo "<table class='table table-hover table-striped align-middle mb-0'>
                                    <thead class='table-light'>
                                        <tr>
                                            <th>Kecamatan</th>
                                            <th>Longitude</th>
                                            <th>Latitude</th>
                                            <th>Luas</th>
                                            <th class='text-center'>Jumlah Penduduk</th>
                                            <th class='text-center'>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $row["kecamatan"] . "</td>
                                        <td>" . $row["longitude"] . "</td>
                                        <td>" . $row["latitude"] . "</td>
                                        <td>" . $row["luas"] . "</td>
                                        <td class='text-center'>" . number_format($row["jml_penduduk"]) . "</td>
                                        <td class='text-center'>
                                            <button type='button' 
                                                class='btn btn-outline-primary btn-sm' 
                                                data-bs-toggle='modal' 
                                                data-bs-target='#editModal' 
                                                onclick='openEditModal(\"" . addslashes($row["kecamatan"]) . "\", \"" . $row["longitude"] . "\", \"" . $row["latitude"] . "\", \"" . $row["luas"] . "\", \"" . $row["jml_penduduk"] . "\")'>
                                                <i class='bi bi-pencil'></i> Edit
                                            </button>
                                            <button type='button' 
                                                class='btn btn-outline-danger btn-sm' 
                                                onclick='confirmDelete(\"" . urlencode($row["kecamatan"]) . "\")'>
                                                <i class='bi bi-trash'></i> Hapus
                                            </button>
                                        </td>
                                    </tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<div class='alert alert-info'>Tidak ada data yang ditemukan</div>";
                            }
                            ?>
                        </div>
                        <div class="mt-3 d-flex justify-content-end gap-2">
                            <a href="inputData.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="editData.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data Kecamatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editKecamatan" name="kecamatan">
                        <div class="mb-3">
                            <label for="editLongitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="editLongitude" name="longitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLatitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="editLatitude" name="latitude" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLuas" class="form-label">Luas</label>
                            <input type="text" class="form-control" id="editLuas" name="luas" required>
                        </div>
                        <div class="mb-3">
                            <label for="editJmlPenduduk" class="form-label">Jumlah Penduduk</label>
                            <input type="text" class="form-control" id="editJmlPenduduk" name="jml_penduduk" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Map & Bootstrap JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Leaflet map initialization
        var map = L.map('map').setView([-7.757, 110.378], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Function for delete confirmation
        function confirmDelete(kecamatan) {
            if (confirm('Apakah Anda yakin ingin menghapus data kecamatan ' + decodeURIComponent(kecamatan) + '?')) {
                window.location.href = 'deleteData.php?kecamatan=' + kecamatan;
            }
        }

        // Function to open edit modal with current data
        function openEditModal(kecamatan, longitude, latitude, luas, jmlPenduduk) {
            document.getElementById('editKecamatan').value = kecamatan;
            document.getElementById('editLongitude').value = longitude;
            document.getElementById('editLatitude').value = latitude;
            document.getElementById('editLuas').value = luas;
            document.getElementById('editJmlPenduduk').value = jmlPenduduk;
        }
    </script>
</body>
</html>
