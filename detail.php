<?php
include 'db.php'; // pastikan ada koneksi

if (!isset($_GET['id'])) {
    echo "Data tidak ditemukan.";
    exit;
}

$id = intval($_GET['id']); // amankan dari injection
$data = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang WHERE id = $id");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Orang Hilang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        .card-header {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .table th {
            width: 220px;
            background: #f8f9fa;
        }
        .badge {
            font-size: 0.85rem;
            padding: 6px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Detail Informasi Orang Hilang</h2>

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

    <a href="<?= $kembali ?>" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong><i class="fas fa-user"></i> <?= htmlspecialchars($row['Nama']) ?></strong>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Foto -->
                <div class="col-md-4 text-center mb-3">
                    <img src="uploads/<?= htmlspecialchars($row['Foto']) ?>" class="img-fluid rounded shadow-sm" alt="Foto Korban">
                    <div class="mt-3">
                        <?php
                        $status = (int)($row['Status'] ?? 0);
                        $statusText = 'Tidak diketahui';
                        $badgeClass = 'badge-secondary';

                        if ($status === 0) {
                            $statusText = 'Hilang';
                            $badgeClass = 'badge-danger';
                        } elseif ($status === 1) {
                            $statusText = 'Dalam Pencarian';
                            $badgeClass = 'badge-warning';
                        } elseif ($status === 2) {
                            $statusText = 'Ditemukan';
                            $badgeClass = 'badge-success';
                        }
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $statusText ?></span>
                    </div>
                </div>

                <!-- Detail -->
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
                            <td><a href="https://wa.me/62<?= htmlspecialchars($row['No_Pelapor']) ?>" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($row['No_Pelapor']) ?></a></td>
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
