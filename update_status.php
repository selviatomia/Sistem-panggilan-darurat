<?php
include 'db.php';

$id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$status = isset($_GET['status']) ? (int)$_GET['status'] : 2;

if ($id > 0) {
    if ($status == 2) {
        // Ambil tim dari laporan
        $q    = mysqli_query($conn, "SELECT Tim FROM laporan_orang_hilang WHERE id = $id");
        $row  = mysqli_fetch_assoc($q);
        $timId = $row['Tim'];

        // Update laporan: status 3 + tim null
        mysqli_query($conn, "UPDATE laporan_orang_hilang 
                             SET status = 2 
                             WHERE id = $id");

        // Kembalikan tim SAR ke status 'Aktif' jika ada
        if (!empty($timId)) {
            mysqli_query($conn, "UPDATE tim_sar 
                                 SET Status = 'Aktif' 
                                 WHERE id = $timId");
        }
    } else {
        // Update biasa
        mysqli_query($conn, "UPDATE laporan_orang_hilang SET status = $status WHERE id = $id");
    }
}

header("Location: informasi_orang_hilang.php?status=updated");
exit;
