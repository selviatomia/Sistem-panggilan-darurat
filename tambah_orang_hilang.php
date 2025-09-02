<?php
include 'db.php';

if (isset($_POST['simpan'])) {
    $Nama = $_POST['Nama'];
    $Jenis_Kelamin = $_POST['Jenis_Kelamin'];
    $Umur = $_POST['Umur'];
    $Terakhir_terlihat = $_POST['Terakhir_terlihat'];
    $Lokasi_Terakhir_Terlihat = $_POST['Lokasi_Terakhir_Terlihat'];
    $Deskripsi = $_POST['Deskripsi'];
    $Nama_Pelapor = $_POST['Nama_Pelapor'];
    $No_Pelapor = $_POST['No_Pelapor'];
    $Hubungan_dengan_Korban = $_POST['Hubungan_dengan_Korban'];
    $Status = '0'; // Otomatis default

    // Upload foto
    $fotoName = $_FILES['Foto']['name'];
    $fotoTmp = $_FILES['Foto']['tmp_name'];
    $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fotoExt, $allowed)) {
        $newFotoName = uniqid() . '.' . $fotoExt;
        $uploadDir = 'uploads/';
        if (move_uploaded_file($fotoTmp, $uploadDir . $newFotoName)) {
            $sql = "INSERT INTO laporan_orang_hilang 
                (Nama, Jenis_Kelamin, Umur, Terakhir_terlihat, Lokasi_Terakhir_Terlihat, Deskripsi, Nama_Pelapor, No_Pelapor, Hubungan_dengan_Korban, Foto, Status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssissssssss", 
                $Nama, $Jenis_Kelamin, $Umur, $Terakhir_terlihat, $Lokasi_Terakhir_Terlihat, $Deskripsi, $Nama_Pelapor, $No_Pelapor, $Hubungan_dengan_Korban, $newFotoName, $Status);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                header("Location: index.php?status=sukses");
                exit;
            } else {
                echo "Gagal menyimpan data.";
            }
        } else {
            echo "Gagal upload foto.";
        }
    } else {
        echo "Format foto tidak diperbolehkan.";
    }
} else {
    echo "Akses langsung tidak diperbolehkan.";
}

?>
