<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

// Ambil hari dalam bahasa Indonesia
$hari = date('N'); // 1 (Senin) - 7 (Minggu)
$hariIndo = '';
switch ($hari) {
    case 1: $hariIndo = 'Senin'; break;
    case 2: $hariIndo = 'Selasa'; break;
    case 3: $hariIndo = 'Rabu'; break;
    case 4: $hariIndo = 'Kamis'; break;
    case 5: $hariIndo = 'Jumat'; break;
    case 6: $hariIndo = 'Sabtu'; break;
    case 7: $hariIndo = 'Minggu'; break;
}

// Cari tim SAR yang bertugas hari ini tapi masih Aktif
$qTim = mysqli_query($conn, "SELECT * FROM tim_sar 
                             WHERE Tugas = '$hariIndo' 
                             AND Status = 'Aktif' 
                             LIMIT 1");
$tim  = mysqli_fetch_assoc($qTim);

if ($tim) {
    $timId   = $tim['id'];
    $timNama = $tim['nama']; // pastikan nama kolom = 'nama'

    // Update laporan: set status + tim
    mysqli_query($conn, "UPDATE laporan_orang_hilang 
                         SET Status = 1, Tim = $timId 
                         WHERE id = $id");

    // Update tim_sar: set status Bertugas
    mysqli_query($conn, "UPDATE tim_sar 
                         SET Status = 'Bertugas' 
                         WHERE id = $timId");

    $statusMsg = "assigned";
} else {
    // Jika semua tim sudah Bertugas atau tidak ada tim
    mysqli_query($conn, "UPDATE laporan_orang_hilang 
                         SET Status = 1, Tim = '-' 
                         WHERE id = $id");

    $statusMsg = "no_team";
}

// Redirect ke halaman asal
$redirect = $from === 'admin' ? 'admin.php' : 'informasi_orang_hilang.php';
header("Location: $redirect?status={$statusMsg}");
exit;
