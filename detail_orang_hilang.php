<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if (!isset($_GET['id'])) {
    echo "Data tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang WHERE id = $id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Orang Hilang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Detail Informasi Orang Hilang</h2>
    <?php
    $kembali = 'index.php'; // default
    if (isset($_GET['from'])) {
    if ($_GET['from'] === 'informasi') {
        $kembali = 'informasi_orang_hilang.php';
    } elseif ($_GET['from'] === 'admin') {
        $kembali = 'admin.php';
    }
    }

    ?>
    <a href="<?= $kembali ?>" class="btn btn-secondary mb-3">Kembali</a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong><?= htmlspecialchars($row['Nama']) ?></strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="uploads/<?= $row['Foto'] ?>" class="img-fluid img-thumbnail" alt="Foto Korban">
                </div>
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td><?= htmlspecialchars($row['Nama']) ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td><?= htmlspecialchars($row['Jenis_Kelamin']) ?></td>
                        </tr>
                        <tr>
                            <th>Umur</th>
                            <td><?= htmlspecialchars($row['Umur']) ?> tahun</td>
                        </tr>
                        <tr>
                            <th>Terakhir Terlihat</th>
                            <td><?= htmlspecialchars($row['Terakhir_terlihat']) ?></td>
                        </tr>
                        <tr>
                            <th>Lokasi Terakhir Terlihat</th>
                            <td><?= htmlspecialchars($row['Lokasi_Terakhir_Terlihat']) ?></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td><?= nl2br(htmlspecialchars($row['Deskripsi'])) ?></td>
                        </tr>
                        <tr>
                            <th>Nama Pelapor</th>
                            <td><?= htmlspecialchars($row['Nama_Pelapor']) ?></td>
                        </tr>
                        <tr>
                            <th>No. HP Pelapor</th>
                            <td><?= htmlspecialchars($row['No_Pelapor']) ?></td>
                        </tr>
                        <tr>
                            <th>Hubungan dengan Korban</th>
                            <td><?= htmlspecialchars($row['Hubungan_dengan_Korban']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
