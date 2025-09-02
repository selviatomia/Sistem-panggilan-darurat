<?php
session_start();
include 'db.php';

// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ID dikirimkan via GET
if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = intval($_GET['id']); // amankan ID dari URL

// Ambil data tim SAR berdasarkan ID
$data = mysqli_query($conn, "SELECT * FROM tim_sar WHERE id = '$id'");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    echo "Data tidak ditemukan!";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $Nama_tim = $_POST['Nama_tim'];
    $Jumlah_Anggota = $_POST['Jumlah_Anggota'];
    $Status = $_POST['Status'];
    $Tugas = $_POST['Tugas'];

    // Query update
    $query = "UPDATE tim_sar SET 
        Nama_tim = '$Nama_tim',
        Jumlah_Anggota = '$Jumlah_Anggota',
        Status = '$Status',
        Tugas = '$Tugas'
        WHERE id = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: tim_sar.php?status=edited");
        exit;
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tim SAR</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <div class="col-md-10 p-4">
        <h3>Edit Tim SAR</h3>
        <form method="POST">
            <div class="form-group">
                <label>Nama Tim</label>
                <input type="text" name="Nama_tim" class="form-control" 
                       value="<?= $row['Nama_tim']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah Anggota</label>
                <input type="number" name="Jumlah_Anggota" class="form-control" 
                       value="<?= $row['Jumlah_Anggota']; ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select class="form-control" name="Status" required>
                    <option value="">-- Pilih --</option>
                    <option value="Aktif" <?= $row['Status'] == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Bertugas" <?= $row['Status'] == 'Bertugas' ? 'selected' : ''; ?>>Bertugas</option>
                    <option value="Istirahat" <?= $row['Status'] == 'Istirahat' ? 'selected' : ''; ?>>Istirahat</option>
                </select>
            </div>
            <div class="form-group">
                <label>Hari Bertugas</label>
                <select class="form-control" name="Tugas" required>
                    <option value="">-- Pilih Hari --</option>
                    <option value="Senin" <?= $row['Tugas'] == 'Senin' ? 'selected' : ''; ?>>Senin</option>
                    <option value="Selasa" <?= $row['Tugas'] == 'Selasa' ? 'selected' : ''; ?>>Selasa</option>
                    <option value="Rabu" <?= $row['Tugas'] == 'Rabu' ? 'selected' : ''; ?>>Rabu</option>
                    <option value="Kamis" <?= $row['Tugas'] == 'Kamis' ? 'selected' : ''; ?>>Kamis</option>
                    <option value="Jumat" <?= $row['Tugas'] == 'Jumat' ? 'selected' : ''; ?>>Jumat</option>
                    <option value="Sabtu" <?= $row['Tugas'] == 'Sabtu' ? 'selected' : ''; ?>>Sabtu</option>
                    <option value="Minggu" <?= $row['Tugas'] == 'Minggu' ? 'selected' : ''; ?>>Minggu</option>
                </select>
            </div>

            <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            <a href="tim_sar.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
</body>
</html>
