<?php
include 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data orang hilang
$result = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang WHERE id = $id");
$data = mysqli_fetch_assoc($result);
if (!$data) {
    echo "Data tidak ditemukan."; 
    exit;
}

// Hapus komentar jika ada parameter hapus
if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM komentar WHERE id = $hapus_id");
    header("Location: komentar.php?id=$id&status=hapus");
    exit;
}

// Simpan komentar baru
if (isset($_POST['submit_komentar'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    mysqli_query($conn, "INSERT INTO komentar (id_orang_hilang, nama, isi, tanggal) 
                         VALUES ($id, '$nama', '$isi', NOW())");
    header("Location: komentar.php?id=$id&status=sukses");
    exit;
}

// Simpan hasil edit komentar
if (isset($_POST['update_komentar'])) {
    $edit_id = (int)$_POST['edit_id'];
    $isi_baru = mysqli_real_escape_string($conn, $_POST['isi_baru']);
    mysqli_query($conn, "UPDATE komentar SET isi = '$isi_baru' WHERE id = $edit_id");
    header("Location: komentar.php?id=$id&status=update");
    exit;
}

// Ambil semua komentar
$komentar = mysqli_query($conn, "SELECT * FROM komentar WHERE id_orang_hilang = $id ORDER BY tanggal DESC");
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Komentar untuk <?= htmlspecialchars($data['Nama']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .comment-box {
      background-color: #fff;
      border-radius: 10px;
      padding: 15px 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      position: relative;
    }
    .comment-header { font-size: 1.25rem; font-weight: bold; color: #333; }
    .comment-meta { font-size: 0.9rem; color: #888; }
    .comment-text { margin-top: 5px; color: #555; }
    .comment-actions { position: absolute; top: 10px; right: 15px; }
    .section-title {
      border-left: 5px solid #007bff;
      padding-left: 10px;
      font-weight: bold;
      color: #007bff;
      margin-bottom: 20px;
    }
  </style>
</head>
<body class="container my-5">

<h3 class="section-title">Komentar untuk: <?= htmlspecialchars($data['Nama']) ?></h3>

<!-- Form Komentar -->
<form method="POST" class="mb-5">
  <div class="form-group">
    <label><strong>Nama Anda</strong></label>
    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
  </div>
  <div class="form-group">
    <label><strong>Isi Komentar</strong></label>
    <textarea name="isi" class="form-control" rows="4" placeholder="Tulis komentar di sini..." required></textarea>
  </div>
  <button type="submit" name="submit_komentar" class="btn btn-primary">Kirim Komentar</button>
  <a href="index.php" class="btn btn-secondary ml-2">Kembali</a>
</form>

<!-- Daftar Komentar -->
<h5 class="section-title">Komentar Sebelumnya</h5>
<?php if ($komentar && mysqli_num_rows($komentar) > 0): ?>
  <?php while ($row = mysqli_fetch_assoc($komentar)) : ?>
    <div class="comment-box mb-3">
      <div class="comment-actions">
        <a href="?id=<?= $id ?>&hapus=<?= $row['id'] ?>" 
           onclick="return confirm('Yakin ingin menghapus komentar ini?')" 
           class="btn btn-danger btn-sm">Hapus</a>
        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editModal<?= $row['id'] ?>">Edit</button>
      </div>
      <div class="comment-header"><?= htmlspecialchars($row['nama']) ?></div>
      <div class="comment-meta"><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></div>
      <div class="comment-text"><?= nl2br(htmlspecialchars($row['isi'])) ?></div>
    </div>

    <!-- Modal Edit Komentar -->
    <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <form method="POST">
          <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
          <div class="modal-content">
            <div class="modal-header bg-warning">
              <h5 class="modal-title">Edit Komentar</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Isi Komentar</label>
                <textarea name="isi_baru" class="form-control" rows="4" required><?= htmlspecialchars($row['isi']) ?></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="update_komentar" class="btn btn-primary">Simpan Perubahan</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p class="text-muted">Belum ada komentar.</p>
<?php endif; ?>

<!-- Script Bootstrap & SweetAlert -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($_GET['status'])): ?>
<script>
<?php if ($_GET['status'] == 'sukses'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Komentar berhasil disimpan',
      showConfirmButton: false,
      timer: 2000
    })
<?php elseif ($_GET['status'] == 'hapus'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Komentar berhasil dihapus',
      showConfirmButton: false,
      timer: 2000
    })
<?php elseif ($_GET['status'] == 'update'): ?>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: 'Komentar berhasil diperbarui',
      showConfirmButton: false,
      timer: 2000
    })
<?php endif; ?>

// hapus parameter status dari URL setelah alert
if (window.history.replaceState) {
    const url = new URL(window.location);
    url.searchParams.delete('status');
    window.history.replaceState({}, document.title, url);
}
</script>
<?php endif; ?>

</body>
</html>
