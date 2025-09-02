<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $laporan_id = intval($_POST['laporan_id']);
    $nama_pengirim = mysqli_real_escape_string($conn, $_POST['nama_pengirim']);
    $isi_komentar = mysqli_real_escape_string($conn, $_POST['isi_komentar']);

    $query = "INSERT INTO komentar_orang_hilang (laporan_id, nama_pengirim, isi_komentar) VALUES ('$laporan_id', '$nama_pengirim', '$isi_komentar')";
    mysqli_query($conn, $query);
}

header("Location: index.php");
exit;
