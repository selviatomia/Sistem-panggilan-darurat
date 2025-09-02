<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Hitung jumlah status 0
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang WHERE Status = 0");
$row = mysqli_fetch_assoc($result);
$totalBelum = $row['total'];

// Tambah Data
if (isset($_POST['simpan'])) {
    $Nama_tim = $_POST['Nama_tim'];
    $Jumlah_Anggota = $_POST['Jumlah_Anggota'];
    $Status = $_POST['Status'];
    $Tugas = $_POST['Tugas'];

    mysqli_query($conn, "INSERT INTO tim_sar (Nama_tim, Jumlah_Anggota, Status, Tugas)
        VALUES ('$Nama_tim', '$Jumlah_Anggota', '$Status', '$Tugas')");

    header("Location: tim_sar.php?status=added");
    exit;
}

// Pencarian dan limit
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
if (!in_array($limit, [5, 10, 50])) $limit = 10;

// Ambil data dan total data sesuai filter
if ($search !== '') {
    $data = mysqli_query($conn, "SELECT * FROM tim_sar WHERE Nama_tim LIKE '%$search%' ORDER BY id DESC LIMIT $limit");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tim_sar WHERE Nama_tim LIKE '%$search%'");
} else {
    $data = mysqli_query($conn, "SELECT * FROM tim_sar ORDER BY id DESC LIMIT $limit");
    $total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tim_sar");
}
$total_result = mysqli_fetch_assoc($total_query);
$total_data = $total_result['total'];
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tim SAR | Sistem SAR Ambon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background: linear-gradient(180deg, #212529, #343a40);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }
        .sidebar img {
            width: 80px;
            margin: 0 auto 10px;
        }
        .sidebar .title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 14px;
            color: #ffc107;
        }
        .sidebar a {
            color: #adb5bd;
            display: block;
            padding: 12px 20px;
            font-size: 14px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: all 0.3s;
        }
        .sidebar a:hover, 
        .sidebar a.active {
            background: #495057;
            color: #fff;
            text-decoration: none;
        }
        .sidebar hr {
            border-top: 1px solid #495057;
            margin: 10px 20px;
        }
        /* Content */
        .content {
            margin-left: 240px;
            padding: 25px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .btn {
            border-radius: 8px;
        }
        table {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background: #ffc107;
            color: #212529;
        }
    </style>
</head>

<body>
<!-- Sidebar -->
<div class="sidebar">
    <img src="logooo.png" alt="Logo SAR">
    <div class="title">KANTOR SAR AMBON</div>
    <a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="informasi_orang_hilang.php">
        <i class="fas fa-user-secret"></i> Informasi Orang Hilang
        <?php if ($totalBelum > 0): ?>
            <span style="background:red; color:white; padding:2px 6px; border-radius:12px; font-size:12px; margin-left:5px;">
                <?= $totalBelum ?>
            </span>
        <?php endif; ?></a>
    <a href="tim_sar.php" class="active"><i class="fas fa-users"></i> Tim SAR</a>
    <hr>
    <a href="index.php"><i class="fas fa-home"></i> Home</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h3 class="mb-3"><i class="fas fa-users"></i> Tim SAR</h3>
    <hr>

    <div class="d-flex justify-content-between mb-3">
        <!-- Search -->
        <form method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="Cari Nama Tim..." value="<?= htmlspecialchars($search) ?>">
            <input type="hidden" name="limit" value="<?= $limit ?>">
            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search"></i></button>
            <a href="tim_sar.php" class="btn btn-secondary">Reset</a>
        </form>

        <!-- Limit -->
        <form method="GET" class="form-inline">
            <label class="mr-2 mb-0">Tampilkan:</label>
            <select name="limit" class="form-control" onchange="this.form.submit()">
                <option value="5" <?= $limit == 5 ? 'selected' : '' ?>>5</option>
                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
            </select>
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        </form>
    </div>

    <div class="mb-3">
        <strong>Total Data: <?= $total_data ?></strong>
    </div>

    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Data</button> &nbsp; 
    
    <!-- Export PDF pakai SweetAlert konfirmasi -->
    <button class="btn btn-danger mb-3" onclick="konfirmasiExport()">
        <i class="fas fa-file-pdf"></i> Export PDF
    </button>

    <div class="card p-3">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Tim</th>
                    <th>Jumlah Anggota</th>
                    <th>Status</th>
                    <th>Tugas (Hari Kerja)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['Nama_tim']) ?></td>
                    <td><?= $row['Jumlah_Anggota'] ?></td>
                    <td><span class="badge badge-<?= $row['Status']=='Aktif'?'success':($row['Status']=='Bertugas'?'warning':'secondary') ?>">
                        <?= $row['Status'] ?></span></td>
                    <td><?= htmlspecialchars($row['Tugas']) ?></td>
                    <td class="text-center">
                        <a href="edit_tim_sar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="formTimSAR">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Tambah Data Tim SAR</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label>Nama Tim</label><input type="text" class="form-control" name="Nama_tim"></div>
                    <div class="form-group"><label>Jumlah Anggota</label><input type="number" class="form-control" name="Jumlah_Anggota"></div>
                    <div class="form-group"><label>Status</label>
                        <select class="form-control" name="Status">
                            <option value="">-- Pilih --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Bertugas">Bertugas</option>
                            <option value="Istirahat">Istirahat</option>
                        </select>
                    </div>
                    <div class="form-group"><label>Tugas (Hari Kerja)</label>
                        <select class="form-control" name="Tugas">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Bootstrap + SweetAlert -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus_tim_sar.php?id=' + id;
        }
    });
}

// Konfirmasi Export PDF
function konfirmasiExport() {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data akan dicetak ke PDF!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Cetak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'export_tim_sar_pdf.php';
        }
    });
}

// Validasi form dengan SweetAlert
document.getElementById("formTimSAR").addEventListener("submit", function(e) {
    const namaTim = this.Nama_tim.value.trim();
    const jumlah = this.Jumlah_Anggota.value.trim();
    const status = this.Status.value.trim();
    const tugas = this.Tugas.value.trim();

    if (!namaTim || !jumlah || !status || !tugas) {
        e.preventDefault();
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Semua Kolom Wajib Diisi!"
        });
    }
});
</script>

<!-- SweetAlert Status -->
<?php if (isset($_GET['status'])): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const status = "<?= $_GET['status'] ?>";
    if (status === "added") {
        Swal.fire("Berhasil!", "Data berhasil ditambahkan.", "success");
    } else if (status === "edited") {
        Swal.fire("Berhasil!", "Data berhasil diedit.", "success");
    } else if (status === "deleted") {
        Swal.fire("Berhasil!", "Data berhasil dihapus.", "success");
    }
});
</script>
<?php endif; ?>
</body>
</html>
