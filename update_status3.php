<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : 3;

if ($id > 0) {
    mysqli_query($conn, "UPDATE laporan_orang_hilang SET status = $status WHERE id = $id");
}

header("Location: informasi_orang_hilang.php?status=updated");
exit;
