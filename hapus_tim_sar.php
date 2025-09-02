<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Validasi ID dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Cek apakah data dengan ID tersebut ada
    $cek = mysqli_query($conn, "SELECT * FROM tim_sar WHERE id = $id");
    if (mysqli_num_rows($cek) > 0) {

        // Hapus data
        mysqli_query($conn, "DELETE FROM tim_sar WHERE id = $id");
        header("Location: tim_sar.php?status=deleted");
        exit;
    } else {

        // Jika data tidak ditemukan
        header("Location: tim_sar.php?status=notfound");
        exit;
    }
} else {
    
    // Jika ID tidak valid
    header("Location: tim_sar.php?status=invalid");
    exit;
}
