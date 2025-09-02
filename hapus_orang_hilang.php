<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

// Cek apakah data ada
$result = mysqli_query($conn, "SELECT Foto FROM laporan_orang_hilang WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if ($data) {
    // Hapus file foto jika ada
    if (!empty($data['Foto']) && file_exists("uploads/" . $data['Foto'])) {
        unlink("uploads/" . $data['Foto']);
    }

    // Hapus data dari database
    mysqli_query($conn, "DELETE FROM laporan_orang_hilang WHERE id = $id");
}

$redirect = $from === 'admin' ? 'admin.php' : 'informasi_orang_hilang.php';
header("Location: $redirect?status=deleted");
exit;
