<?php 
session_start(); 
if (!isset($_SESSION['username'])) {     
    header("Location: login.php");     
    exit; 
} 
include 'db.php';  

// Hitung jumlah status 0 
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang WHERE Status = 0"); 
$row = mysqli_fetch_assoc($result); 
$totalBelum = $row['total'];  

// Proses tambah data 
if (isset($_POST['simpan'])) {     
    $Nama = $_POST['Nama'];     
    $Jenis_Kelamin = $_POST['Jenis_Kelamin'];     
    $Umur = $_POST['Umur'];     
    $Terakhir_terlihat = $_POST['Terakhir_terlihat'];     
    $Lokasi = $_POST['Lokasi_Terakhir_Terlihat'];     
    $Deskripsi = $_POST['Deskripsi'];     
    $Nama_Pelapor = $_POST['Nama_Pelapor'];     
    $No_Pelapor = $_POST['No_Pelapor'];     
    $Hubungan = $_POST['Hubungan_dengan_Korban'];     
    $Status = $_POST['status'];     
    $Aksi = $_POST['aksi'];      

    $foto_name = $_FILES['Foto']['name'];     
    $tmp = $_FILES['Foto']['tmp_name'];     
    $upload_path = "uploads/";     
    if (!file_exists($upload_path)) {         
        mkdir($upload_path, 0777, true);     
    }     
    move_uploaded_file($tmp, $upload_path . $foto_name);      

    mysqli_query($conn, "INSERT INTO laporan_orang_hilang          
        (Nama, Jenis_Kelamin, Umur, Terakhir_terlihat, Lokasi_Terakhir_Terlihat, Deskripsi, Nama_Pelapor, No_Pelapor, Hubungan_dengan_Korban, Foto, status, aksi)          
        VALUES ('$Nama', '$Jenis_Kelamin', '$Umur', '$Terakhir_terlihat', '$Lokasi', '$Deskripsi', '$Nama_Pelapor', '$No_Pelapor', '$Hubungan', '$foto_name', '$Status', '$Aksi')");      

    header("Location: informasi_orang_hilang.php?status=added");     
    exit; 
}   

// Ambil nilai limit dan pencarian 
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; 
if (!in_array($limit, [5, 10, 50])) $limit = 10;  

$keyword = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';  

// Query data 
if ($keyword) {     
    $data = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang          
        WHERE Nama LIKE '%$keyword%'          
        OR Lokasi_Terakhir_Terlihat LIKE '%$keyword%'          
        ORDER BY id DESC         
        LIMIT $limit"); 
} else {     
    $data = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang ORDER BY id DESC LIMIT $limit"); 
} 
?>  

<!doctype html> 
<html lang="id"> 
<head>     
    <meta charset="utf-8">     
    <title>Tim SAR | Sistem SAR Ambon</title>     
    <meta name="viewport" content="width=device-width, initial-scale=1">      

    <!-- Bootstrap & Icons -->     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">     
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>      

    <style>         
        body { font-family: 'Segoe UI', sans-serif; background: #f7f9fc; }         
        .sidebar { position: fixed; top: 0; left: 0; width: 240px; height: 100vh; background: linear-gradient(180deg, #212529, #343a40); color: #fff; display: flex; flex-direction: column; padding-top: 20px; }         
        .sidebar img { width: 80px; margin: 0 auto 10px; }         
        .sidebar .title { text-align: center; font-weight: bold; margin-bottom: 30px; font-size: 14px; color: #ffc107; }         
        .sidebar a { color: #adb5bd; display: block; padding: 12px 20px; font-size: 14px; border-radius: 8px; margin: 4px 12px; transition: all 0.3s; }         
        .sidebar a:hover, .sidebar a.active { background: #495057; color: #fff; text-decoration: none; }         
        .sidebar hr { border-top: 1px solid #495057; margin: 10px 20px; }         
        .content { margin-left: 240px; padding: 25px; }         
        .card { border: none; border-radius: 12px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }         
        .btn { border-radius: 8px; }         
        table { background: #fff; border-radius: 8px; overflow: hidden; }         
        thead { background: #ffc107; color: #212529; }     
    </style> 
</head>  

<body> 
<!-- Sidebar --> 
<div class="sidebar">     
    <img src="logooo.png" alt="Logo SAR">     
    <div class="title">KANTOR SAR AMBON</div>          
    <a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>          
    <a href="informasi_orang_hilang.php" class="active">         
        <i class="fas fa-user-secret"></i> Informasi Orang Hilang         
        <?php if ($totalBelum > 0): ?>             
            <span style="background:red; color:white; padding:2px 6px; border-radius:12px; font-size:12px; margin-left:5px;">                 
                <?= $totalBelum ?>             
            </span>         
        <?php endif; ?>     
    </a>          
    <a href="tim_sar.php"><i class="fas fa-users"></i> Tim SAR</a>     
    <hr>     
    <a href="index.php"><i class="fas fa-home"></i> Home</a>     
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
</div>    

<!-- Content --> 
<div class="content">     
    <h3 class="mb-3"><i class="fas fa-users"></i> Informasi Orang Hilang</h3>      
    <hr> 

    <!-- Tombol Export PDF dengan SweetAlert --> 
    <a href="javascript:void(0)" onclick="konfirmasiExport('<?= htmlspecialchars($keyword) ?>')" class="btn btn-danger mb-3">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>

    <!-- Baris pencarian + limit -->         
    <div class="d-flex justify-content-between mb-3">             
        <!-- Pencarian kiri -->             
        <form method="GET" class="form-inline">                 
            <input type="text" name="search" class="form-control mr-2" placeholder="Cari nama, atau lokasi, ..." value="<?= htmlspecialchars($keyword) ?>">                 
            <input type="hidden" name="limit" value="<?= $limit ?>">                 
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>                 
            <a href="informasi_orang_hilang.php" class="btn btn-secondary ml-2">Reset</a>             
        </form>              

        <!-- Limit  -->             
        <form method="GET" class="form-inline">                 
            <label for="limitSelect" class="mr-2 mb-0 align-self-center">Tampilkan:</label>                 
            <select name="limit" id="limitSelect" class="form-control" onchange="this.form.submit()">                     
                <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>                     
                <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>                     
                <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>                 
            </select>                 
            <input type="hidden" name="search" value="<?= htmlspecialchars($keyword) ?>">             
        </form>         
    </div>          

    <?php         
    // Hitung total data yang sesuai filter pencarian         
    if ($keyword) {             
        $total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang                  
            WHERE Nama LIKE '%$keyword%' OR Lokasi_Terakhir_Terlihat LIKE '%$keyword%'");         
    } else {             
        $total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan_orang_hilang");         
    }         
    $total_data = mysqli_fetch_assoc($total_result)['total'];         
    ?>         
    <div class="mb-3">             
        <strong>Total Data: <?= $total_data ?></strong>         
    </div>          

    <table class="table table-bordered table-striped">     
        <thead>         
            <tr class="text-center">             
                <th>No</th>             
                <th>Nama</th>             
                <th>Jenis Kelamin</th>             
                <th>Terakhir Terlihat</th>             
                <th>Lokasi</th>             
                <th>Foto</th>             
                <th>Status</th>             
                <th>Aksi</th>         
            </tr>     
        </thead>     
        <tbody>         
            <?php         
            $no = 1;         
            while ($row = mysqli_fetch_array($data)) {         
            ?>         
            <tr class="text-center <?php if($row['Status']==0) echo 'table-danger'; ?>">     
                <td><?= $no++ ?></td>     
                <td><?= htmlspecialchars($row['Nama']) ?></td>     
                <td><?= htmlspecialchars($row['Jenis_Kelamin']) ?></td>     
                <td><?= htmlspecialchars($row['Terakhir_terlihat']) ?></td>     
                <td><?= htmlspecialchars($row['Lokasi_Terakhir_Terlihat']) ?></td>     
                <td><img src="uploads/<?= htmlspecialchars($row['Foto']) ?>" width="100" alt="Foto <?= htmlspecialchars($row['Nama']) ?>"></td>     
                <td>         
                    <?php if ($row['Status'] == 1): ?>             
                        <span class="badge bg-success text-light">Aktif</span>         
                    <?php elseif ($row['Status'] == 0): ?>             
                        <span class="badge bg-secondary text-light">Tidak Aktif</span>         
                    <?php else: ?>             
                        <span class="badge bg-info text-light">Selesai</span>         
                    <?php endif; ?>     
                </td>      

                <td>     
                    <a href="detail_orang_hilang.php?id=<?= $row['id'] ?>&from=informasi" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>      

                    <?php if ($row['Status'] == 0): ?>         
                        <button class="btn btn-light btn-sm" onclick="konfirmasi(<?= $row['id'] ?>)" title="Konfirmasi"><i class="fas fa-check"></i></button>         
                        <a href="edit_orang_hilang.php?id=<?= $row['id'] ?>" class="btn btn-light btn-sm" title="Edit"><i class="fas fa-edit"></i></a>         
                        <button class="btn btn-light btn-sm" onclick="konfirmasiHapus(<?= $row['id'] ?>)" title="Hapus"><i class="fas fa-trash-alt"></i></button>      
                    <?php elseif ($row['Status'] == 1): ?>         
                        <button class="btn btn-success btn-sm" onclick="ditemukan(<?= $row['id'] ?>)" title="Ditemukan"><i class="fas fa-user-check"></i> Ditemukan</button>      
                    <?php elseif ($row['Status'] == 2): ?>         
                        <button class="btn btn-primary btn-sm" onclick="ditemukan2(<?= $row['id'] ?>)" title="Hapus di Display"><i class="fas fa-user-check"></i> Hapus di Display</button>       
                    <?php endif; ?> 
                </td> 
            </tr>              
            <?php } ?>     
        </tbody> 
    </table>      
</div> 

<!-- JS Bootstrap --> 
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>  

<!-- SweetAlert Confirm Hapus --> 
<script> 
function konfirmasiHapus(id) {     
    Swal.fire({         
        title: 'Yakin ingin menghapus?',         
        text: "Data tidak bisa dikembalikan!",         
        icon: 'warning',         
        showCancelButton: true,         
        confirmButtonColor: '#d33',         
        cancelButtonColor: '#aaa',         
        confirmButtonText: 'Ya, hapus!',         
        cancelButtonText: 'Batal'     
    }).then((result) => {         
        if (result.isConfirmed) {             
            window.location.href = "hapus_orang_hilang.php?id=" + id;         
        }     
    }); 
} 
</script>  

<!-- SweetAlert Confirm Konfirmasi --> 
<script> 
function konfirmasi(id) {     
    Swal.fire({         
        title: 'Yakin ingin menerima?',         
        text: "Data Orang Hilang akan di Publish!",         
        icon: 'success',         
        showCancelButton: true,         
        confirmButtonColor: '#28a745',         
        cancelButtonColor: '#aaa',         
        confirmButtonText: 'Ya, Terima!',         
        cancelButtonText: 'Batal'     
    }).then((result) => {         
        if (result.isConfirmed) {             
            window.location.href = "konfir_orang_hilang.php?id=" + id;         
        }     
    }); 
} 
</script>  

<!-- SweetAlert Confirm Ditemukan --> 
<script> 
function ditemukan(id) {     
    Swal.fire({         
        title: 'Orang ini sudah ditemukan?',         
        icon: 'question',         
        showCancelButton: true,         
        confirmButtonColor: '#28a745',         
        cancelButtonColor: '#d33',         
        confirmButtonText: '<i class="fas fa-check"></i> Ya',         
        cancelButtonText: '<i class="fas fa-times"></i> Tidak'     
    }).then((result) => {         
        if (result.isConfirmed) {             
            window.location.href = "update_status.php?id=" + id + "&status=2";         
        }     
    }) 
} 
</script>  

<script> 
function ditemukan2(id) {     
    Swal.fire({         
        title: 'Orang ini sudah ditemukan, Hapus dari Display?',         
        icon: 'question',         
        showCancelButton: true,         
        confirmButtonColor: '#a78528ff',         
        cancelButtonColor: '#d33',         
        confirmButtonText: '<i class="fas fa-check"></i> Ya',         
        cancelButtonText: '<i class="fas fa-times"></i> Tidak'     
    }).then((result) => {         
        if (result.isConfirmed) {             
            window.location.href = "update_status3.php?id=" + id + "&status=3";         
        }     
    }) 
} 
</script>  

<!-- SweetAlert Confirm Export PDF --> 
<script>
function konfirmasiExport(keyword) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data akan dicetak ke PDF!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Cetak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "export_pdf.php?search=" + encodeURIComponent(keyword);
        }
    });
}
</script>

<!-- SweetAlert Status Notif --> 
<?php if (isset($_GET['status'])): ?> 
<script> 
document.addEventListener("DOMContentLoaded", function () {     
    const status = "<?= $_GET['status'] ?>";     
    if (status === "added") {         
        Swal.fire("Berhasil!", "Data berhasil ditambahkan.", "success");     
    } else if (status === "edited") {         
        Swal.fire("Berhasil!", "Data berhasil diedit.", "success");     
    } else if (status === "deleted") {         
        Swal.fire("Berhasil!", "Data berhasil dihapus.", "success");     
    } 
}); 
</script> 
<?php endif; ?>  

</body> 
</html>
