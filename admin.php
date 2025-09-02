<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

// Hitung jumlah status 0 (laporan belum diproses)
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang WHERE Status = 0");
$row = mysqli_fetch_assoc($result);
$totalBelum = $row['total'];

// Hitung total data orang hilang & tim
$total_orang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang"));
$total_dalam_pencarian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang WHERE Status=1"));
$total_ditemukan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang WHERE Status=2 OR Status=3"));
$total_tim = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tim_sar"));

// Tim SAR berdasarkan status
$total_bertugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tim_sar WHERE status='Bertugas'"));
$total_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tim_sar WHERE status='Aktif'"));
$total_istirahat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tim_sar WHERE status='Istirahat'"));

$dalam_pencarian = (int)$total_dalam_pencarian['total'];
$ditemukan = (int)$total_ditemukan['total'];
$total_orang_hilang = (int)$total_orang['total'];
$total_tim_sar = (int)$total_tim['total'];
$tim_bertugas = (int)$total_bertugas['total'];
$tim_aktif = (int)$total_aktif['total'];
$tim_istirahat = (int)$total_istirahat['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard SAR Ambon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
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
        .sidebar img { width: 80px; margin: 0 auto 10px; }
        .sidebar .title { text-align: center; font-weight: bold; margin-bottom: 30px; font-size: 14px; color: #ffc107; }
        .sidebar a { color: #adb5bd; display: block; padding: 12px 20px; font-size: 14px; border-radius: 8px; margin: 4px 12px; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: #fff; text-decoration: none; }
        .sidebar hr { border-top: 1px solid #495057; margin: 10px 20px; }

        /* Content */
        .content { margin-left: 240px; padding: 25px; }
        .card { border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-bottom: 20px; text-align:center; padding: 20px; }
        .card h4 { font-weight: bold; margin-bottom: 10px; }
        .card i { font-size: 36px; margin-bottom: 10px; color: #ffc107; }
        .card-total { font-size: 28px; font-weight: bold; color: #343a40; }
        .card-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .card-row .card { flex: 1 1 200px; }
        .chart-container { width: 100%; max-width: 600px; margin: 20px auto; }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <img src="logooo.png" alt="Logo SAR">
    <div class="title">KANTOR SAR AMBON</div>
    <a href="admin.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="informasi_orang_hilang.php">
    <i class="fas fa-user-secret"></i> Informasi Orang Hilang
        <?php if ($totalBelum > 0): ?>
            <span style="background:red; color:white; padding:2px 6px; border-radius:12px; font-size:12px; margin-left:5px;">
                <?= $totalBelum ?>
            </span>
        <?php endif; ?>
        </a>
    <a href="tim_sar.php"><i class="fas fa-users"></i> Tim SAR</a>
    <hr>
    <a href="index.php"><i class="fas fa-home"></i> Home</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h3 class="mb-3"><i class="fas fa-tachometer-alt"></i> Dashboard</h3>

    <!-- Card Total -->
    <div class="card-row">
        <div class="card bg-white">
            <i class="fas fa-user-secret"></i>
            <h4>Total Orang Hilang</h4>
            <div class="card-total"><?= $total_orang_hilang ?></div>
        </div>
        <div class="card bg-white">
            <i class="fas fa-search"></i>
            <h4>Dalam Pencarian</h4>
            <div class="card-total"><?= $dalam_pencarian ?></div>
        </div>
        <div class="card bg-white">
            <i class="fas fa-check-circle"></i>
            <h4>Sudah Ditemukan</h4>
            <div class="card-total"><?= $ditemukan ?></div>
        </div>
        <div class="card bg-white">
            <i class="fas fa-users"></i>
            <h4>Total Tim SAR</h4>
            <div class="card-total"><?= $total_tim_sar ?></div>
        </div>
    </div>

        <!-- Statistik Orang Hilang & Tim SAR -->
    <div class="row">
        <!-- Grafik Orang Hilang -->
        <div class="col-md-8">
            <div class="card bg-white">
                <h5 class="mb-3"><i class="fas fa-chart-pie"></i> Statistik Orang Hilang</h5>
                <!-- Grafik Orang Hilang -->
<div style="width:100%; max-width:600px; margin:auto;">
    <canvas id="grafikOrangHilang" style="height:300px !important;"></canvas>
</div>
            </div>
        </div>

        <!-- Daftar Tim SAR -->
<div class="col-md-4">
    <div class="card bg-white p-3">
        <h5 class="mb-3"><i class="fas fa-users"></i> Status Tim SAR</h5>
        <ul class="list-group text-left" style="max-height: 360px; overflow-y: auto;">
            <?php
            $timList = mysqli_query($conn, "SELECT Nama_tim, status FROM tim_sar ORDER BY Status");
            $counter = 0;
            $allTim = []; // Simpan semua tim untuk modal
            while ($tim = mysqli_fetch_assoc($timList)) {
                $allTim[] = $tim;
                $counter++;
                $status = $tim['status'];
                $badgeClass = "secondary";
                if ($status == "Aktif") $badgeClass = "success";
                if ($status == "Bertugas") $badgeClass = "primary";
                if ($status == "Istirahat") $badgeClass = "warning";

                // Hanya tampilkan 6 data pertama di box utama
                if ($counter <= 5) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            {$tim['Nama_tim']}
                            <span class='badge badge-$badgeClass'>{$status}</span>
                          </li>";
                }
            }
            ?>
        </ul>

        <?php if(count($allTim) > 6): ?>
            <button type="button" class="btn btn-sm btn-outline-primary mt-2 w-100" data-toggle="modal" data-target="#modalTimSAR">
                Lihat Semua
            </button>
        <?php endif; ?>
    </div>
</div>

    </div>


</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

<!-- Toast Notification -->
<div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 1050;">
  <div id="alertToast" class="toast" data-delay="7000" style="cursor:pointer;">
    <div class="toast-header bg-warning text-dark">
      <i class="fas fa-exclamation-triangle mr-2"></i>
      <strong class="mr-auto">Peringatan</strong>
      <small>Sekarang</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
    </div>
    <div class="toast-body">
      Terdapat <?= $totalBelum ?> laporan orang hilang baru. Klik untuk verifikasi!
    </div>
  </div>
</div>


<!-- Modal Semua Tim SAR -->
<div class="modal fade" id="modalTimSAR" tabindex="-1" role="dialog" aria-labelledby="modalTimSARLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTimSARLabel"><i class="fas fa-users"></i> Semua Tim SAR</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
            <?php foreach($allTim as $tim): 
                $status = $tim['status'];
                $badgeClass = "secondary";
                if ($status == "Aktif") $badgeClass = "success";
                if ($status == "Bertugas") $badgeClass = "primary";
                if ($status == "Istirahat") $badgeClass = "warning";
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $tim['Nama_tim'] ?>
                <span class="badge badge-<?= $badgeClass ?>"><?= $status ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


<!-- Chart.js Script -->
<script>
var ctx = document.getElementById("grafikOrangHilang");
var grafik = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["Dalam Pencarian", "Sudah Ditemukan"],
        datasets: [{
            data: [<?= $dalam_pencarian ?>, <?= $ditemukan ?>],
            backgroundColor: ["#f1c40f", "#27ae60"]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { font: { size: 12 } }
            }
        }
    }
});
</script>
<script>
<?php if ($totalBelum > 0): ?>
    $(document).ready(function(){
        var toastEl = $('#alertToast');
        toastEl.toast('show');

        // klik toast langsung ke halaman verifikasi
        toastEl.click(function() {
            window.location.href = 'informasi_orang_hilang.php';
        });
    });
<?php endif; ?>


</script>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

</body>
</html>
