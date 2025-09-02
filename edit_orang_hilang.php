<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$from = isset($_GET['from']) ? $_GET['from'] : '';

$result = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

if (isset($_POST['update'])) {
    $Nama = $_POST['Nama'];
    $Jenis_Kelamin = $_POST['Jenis_Kelamin'];
    $Umur = $_POST['Umur'];
    $Terakhir_terlihat = $_POST['Terakhir_terlihat'];
    $Lokasi_Terakhir_Terlihat = $_POST['Lokasi_Terakhir_Terlihat'];
    $Deskripsi = $_POST['Deskripsi'];
    $Nama_Pelapor = $_POST['Nama_Pelapor'];
    $No_Pelapor = $_POST['No_Pelapor'];
    $Hubungan = $_POST['Hubungan_dengan_Korban'];

    // Proses upload foto jika ada
    if (isset($_FILES['Foto']) && $_FILES['Foto']['error'] === UPLOAD_ERR_OK) {
        $foto_name = $_FILES['Foto']['name'];
        $tmp = $_FILES['Foto']['tmp_name'];
        $upload_path = "uploads/";

        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        move_uploaded_file($tmp, $upload_path . $foto_name);
    } else {
        $foto_name = $data['Foto']; // Gunakan foto lama jika tidak ada upload baru
    }

    // Update data ke database
    mysqli_query($conn, "UPDATE laporan_orang_hilang SET 
        Nama = '$Nama',
        Jenis_Kelamin = '$Jenis_Kelamin',
        Umur = '$Umur',
        Terakhir_terlihat = '$Terakhir_terlihat',
        Lokasi_Terakhir_Terlihat = '$Lokasi_Terakhir_Terlihat',
        Deskripsi = '$Deskripsi',
        Nama_Pelapor = '$Nama_Pelapor',
        No_Pelapor = '$No_Pelapor',
        Hubungan_dengan_Korban = '$Hubungan',
        Foto = '$foto_name'
        WHERE id = $id
    ");

    $redirect = $from === 'admin' ? 'admin.php' : 'informasi_orang_hilang.php';
    header("Location: $redirect?status=edited");
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Orang Hilang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
    <div class="container">
        <h3>Edit Data Orang Hilang</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="Nama" class="form-control" value="<?= htmlspecialchars($data['Nama']) ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="Jenis_Kelamin" class="form-control" required>
                    <option value="Laki-laki" <?= $data['Jenis_Kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $data['Jenis_Kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Umur</label>
                <input type="number" name="Umur" class="form-control" value="<?= $data['Umur'] ?>" required>
            </div>
            <div class="form-group">
                <label>Terakhir Terlihat</label>
                <input type="datetime-local" name="Terakhir_terlihat" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($data['Terakhir_terlihat'])) ?>" required>
            </div>
            <div class="form-group">
                <label>Lokasi Terakhir Terlihat</label>
                <input type="text" name="Lokasi_Terakhir_Terlihat" class="form-control" value="<?= htmlspecialchars($data['Lokasi_Terakhir_Terlihat']) ?>" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="Deskripsi" class="form-control" required><?= htmlspecialchars($data['Deskripsi']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Nama Pelapor</label>
                <input type="text" name="Nama_Pelapor" class="form-control" value="<?= htmlspecialchars($data['Nama_Pelapor']) ?>" required>
            </div>
            <div class="form-group">
                <label>No Pelapor</label>
                <input type="text" name="No_Pelapor" class="form-control" value="<?= htmlspecialchars($data['No_Pelapor']) ?>" required>
            </div>
            <div class="form-group">
               <label>Hubungan Dengan Korban</label>
                <select name="Hubungan_dengan_Korban" class="form-control" required>
                    <option value="Keluarga" <?= $data['Hubungan_dengan_Korban'] == 'Keluarga' ? 'selected' : '' ?>>Keluarga</option>
                    <option value="Teman" <?= $data['Hubungan_dengan_Korban'] == 'Teman' ? 'selected' : '' ?>>Teman</option>
                    <option value="Teman" <?= $data['Hubungan_dengan_Korban'] == 'Kerabat' ? 'selected' : '' ?>>Kerabat</option>
                    <option value="Teman" <?= $data['Hubungan_dengan_Korban'] == 'Lainnnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label>Foto Korban</label><br>
                <?php if (!empty($data['Foto'])): ?>
                    <img src="uploads/<?= $data['Foto'] ?>" alt="Foto Korban" style="max-width: 150px; display:block; margin-bottom:10px;">
                <?php endif; ?>
                <input type="file" class="form-control" name="Foto" accept="image/*">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
            </div>
            <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
            <a href="<?= $from === 'admin' ? 'admin.php' : 'informasi_orang_hilang.php' ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
