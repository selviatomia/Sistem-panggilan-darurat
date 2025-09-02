<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'admin') {
    header("Location: informasi_orang_hilang.php?status=unauthorized");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    if (in_array($status, ['Proses', 'Selesai'])) {
        mysqli_query($conn, "UPDATE laporan_orang_hilang SET status='$status' WHERE id=$id");
        header("Location: informasi_orang_hilang.php?status=updated");
    } else {
        header("Location: informasi_orang_hilang.php?status=invalid");
    }
}
?>
