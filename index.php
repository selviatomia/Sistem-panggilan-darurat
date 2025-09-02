<?php 
include 'db.php';  

$search = isset($_GET['search']) ? trim($_GET['search']) : '';  

if ($search !== '') {     
    $search_safe = mysqli_real_escape_string($conn, $search);     
    $laporan_orang_hilang = mysqli_query(
        $conn,
        "SELECT * FROM laporan_orang_hilang 
         WHERE Status IN (1,2) AND Nama LIKE '%$search_safe%' 
         ORDER BY id DESC"
    ); 
} else {     
    $laporan_orang_hilang = mysqli_query(
        $conn,
        "SELECT * FROM laporan_orang_hilang 
         WHERE Status IN (1,2) 
         ORDER BY id DESC"
    ); 
} 
?>  

<!doctype html> 
<html lang="id"> 
<head>   
  <meta charset="utf-8">   
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">   
  <title>Sistem Informasi Panggilan Darurat SAR Ambon</title>    

  <!-- Bootstrap & Swiper -->   
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">   
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>   
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">   
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    

  <style>     
    body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }     
    .navbar { background: linear-gradient(90deg, #f39c12, #e67e22); box-shadow: 0 4px 10px rgba(0,0,0,0.15); }     
    .navbar-brand { font-size: 1.1rem; letter-spacing: 1px; }     
    .navbar .nav-link { transition: 0.3s; }     
    .navbar .nav-link:hover { text-decoration: underline; }     
    h4 { font-weight: bold; color: #2c3e50; text-transform: uppercase; letter-spacing: 1px; }     
    .swiper { padding-bottom: 60px; }     
    .swiper-slide { display: flex; justify-content: center; }     
    .card { width: 260px; padding: 15px; text-align: center; border-radius: 15px; background: #fff; border: none; box-shadow: 0 6px 18px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; }     
    .card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }     
    .card img { width: 100%; height: 200px; object-fit: cover; border-radius: 12px; }     
    .badge { font-size: 0.8rem; font-weight: 600; border-radius: 12px; }     
    .form-inline input { border-radius: 25px; border: 1px solid #ccc; padding-left: 15px; }     
    .form-inline .btn { border-radius: 25px; }     
    .btn-primary { background: linear-gradient(90deg, #3498db, #2980b9); border: none; border-radius: 25px; transition: 0.3s; }     
    .btn-primary:hover { background: linear-gradient(90deg, #2980b9, #1f618d); }     
    .btn-warning, .btn-success, .btn-secondary, .btn-info { border-radius: 25px; }     
    .modal-content { border-radius: 15px; box-shadow: 0 6px 18px rgba(0,0,0,0.2); }     
    .modal-header { border-top-left-radius: 15px; border-top-right-radius: 15px; }     
    .form-control { border-radius: 10px; }     
    hr { border-top: 2px solid #ddd; width: 60%; }   
  </style> 
</head> 
<body>       

<!-- Navbar --> 
<nav class="navbar navbar-expand-lg navbar-light bg-warning fixed-top">   
  <div class="container">     
    <a class="navbar-brand font-weight-bold text-white d-flex align-items-center" href="#">       
      <img src="logooo.png" alt="Logo" width="40" height="40" class="mr-2 rounded-circle">       
      SISTEM INFORMASI PANGGILAN DARURAT SAR AMBON     
    </a>     
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">       
      <span class="navbar-toggler-icon"></span>     
    </button>     
    <div class="collapse navbar-collapse ml-auto" id="navbarNav">       
      <ul class="navbar-nav ms-auto">         
        <li class="nav-item"><a class="nav-link text-white" href="index.php"><i class="fas fa-home"></i> Home</a></li>         
        <li class="nav-item"><a class="nav-link text-white" href="tel:0911323774"><i class="fas fa-phone-alt"></i> Telepon Darurat</a></li>         
        <li class="nav-item"><a class="nav-link text-white" href="profil.php"><i class="fas fa-user"></i> Profil</a></li>         
        <li class="nav-item"><a class="nav-link text-white" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>       
      </ul>     
    </div>   
  </div> 
</nav>  

<div class="container mt-5 pt-5">   
  <h4 class="text-center mt-4">Data Orang Hilang Terbaru</h4>   
  <hr>    

  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">     
    <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalTambah">
      <i class="fas fa-plus"></i> Tambah Data
    </button>      

    <form method="GET" class="form-inline mb-2" style="max-width: 380px;">       
      <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Cari Nama..." value="<?= htmlspecialchars($search) ?>" style="height: 38px; flex:1;">       
      <button type="submit" class="btn btn-primary mr-2" style="height: 38px;"><i class="fas fa-search"></i></button>       
      <a href="index.php" class="btn btn-secondary" style="height: 38px;">Reset</a>     
    </form>   
  </div>    

  <!-- Swiper Carousel -->   
  <?php if (mysqli_num_rows($laporan_orang_hilang) > 0): ?>   
  <div class="swiper mySwiper">     
    <div class="swiper-wrapper">       
      <?php while ($row = mysqli_fetch_assoc($laporan_orang_hilang)) { ?>         
      <div class="swiper-slide">           
        <div class="card">             
          <img src="uploads/<?= htmlspecialchars($row['Foto']) ?>" alt="<?= htmlspecialchars($row['Nama']) ?>">             
          <h5 class="mt-3 font-weight-bold"><?= htmlspecialchars($row['Nama']) ?></h5>             
          <p class="mb-1 text-muted">Umur: <?= $row['Umur'] ?></p>              

          <!-- Status -->             
          <?php               
            $status = (int)($row['Status'] ?? 0);               
            $statusText = ' ';               
            $badgeClass = 'badge-secondary';                

            if ($status === 0) {                   
              $statusText = 'Hilang';                   
              $badgeClass = 'badge-danger';               
            } elseif ($status === 1) {                   
              $statusText = 'Dalam Pencarian';                   
              $badgeClass = 'badge-warning';               
            } elseif ($status === 2) {                   
              $statusText = 'Ditemukan';                   
              $badgeClass = 'badge-success';               
            }             
          ?>             

          <?php 
            $idTim = $row['Tim']; 
            $qTim  = mysqli_query($conn, "SELECT Nama_tim FROM tim_sar WHERE id = '$idTim' LIMIT 1"); 
            $tim   = mysqli_fetch_assoc($qTim); 
            $namaTim = $tim ? $tim['Nama_tim'] : '-'; 
          ?>                

          <div class="mb-2">               
            <span class="badge <?= $badgeClass ?> p-2"><?= $statusText ?> Tim <?= htmlspecialchars($namaTim) ?></span>             
          </div>              

          <a href="detail.php?id=<?= $row['id'] ?>&from=index" class="btn btn-primary btn-sm mt-2">
            <i class="fas fa-info-circle"></i> Detail
          </a>             
          <a href="komentar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mt-2">
            <i class="fas fa-comment"></i> Komentar
          </a>             

          <!-- Tombol Bagikan dengan Dropdown -->
          <?php 
            $shareUrl = urlencode("http://yourdomain.com/detail.php?id=" . $row['id']); 
            $shareText = urlencode("Laporan Orang Hilang: " . $row['Nama'] . " - Mohon bantu sebarkan informasi.");
          ?>
          <div class="dropdown mt-2">
            <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownShare-<?= $row['id'] ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-share-alt"></i> Bagikan
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownShare-<?= $row['id'] ?>">
              <a class="dropdown-item text-success" href="https://wa.me/?text=<?= $shareText ?>%20<?= $shareUrl ?>" target="_blank">
                <i class="fab fa-whatsapp"></i> WhatsApp
              </a>
              <a class="dropdown-item text-primary" href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank">
                <i class="fab fa-facebook"></i> Facebook
              </a>
              <a class="dropdown-item text-danger" href="https://www.instagram.com/?url=<?= $shareUrl ?>" target="_blank">
                <i class="fab fa-instagram"></i> Instagram
              </a>
            </div>
          </div>

        </div>         
      </div>       
      <?php } ?>     
    </div>     
    <div class="swiper-button-next"></div>     
    <div class="swiper-button-prev"></div>     
    <div class="swiper-pagination"></div>   
  </div>   
  <?php else: ?> 
  <div style="display:flex; justify-content:center; align-items:center; margin:30px 0;">     
    <div style="background: #fff7e6; padding: 25px 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 450px; width: 100%;">         
      <i class="fas fa-info-circle" style="font-size: 36px; color: #ff8c42; margin-bottom: 12px;"></i>         
      <h4 style="margin-bottom: 8px; color: #333; font-weight: 600;">Belum Ada Data</h4>         
      <p style="color: #555; font-size: 14px; line-height: 1.5;">Saat ini belum ada laporan orang hilang yang diterima. Semua data akan ditampilkan di sini ketika tersedia.</p>     
    </div> 
  </div> 
  <?php endif; ?>  

</div>  

<!-- Modal Tambah --> 
<div class="modal fade" id="modalTambah">   
  <div class="modal-dialog modal-lg">     
    <div class="modal-content">       
      <form method="POST" enctype="multipart/form-data" action="tambah_orang_hilang.php" id="formOrangHilang">         
        <div class="modal-header bg-info text-white">           
          <h5 class="modal-title">Tambah Informasi Orang Hilang</h5>           
          <button type="button" class="close" data-dismiss="modal">&times;</button>         
        </div>         
        <div class="modal-body">           
          <div class="form-group"><label>Nama</label><input type="text" class="form-control" name="Nama"></div>           
          <div class="form-group"><label>Jenis Kelamin</label>                         
            <select class="form-control" name="Jenis_Kelamin">                           
              <option value="">-- Pilih --</option>                           
              <option value="Laki-laki">Laki-laki</option>                           
              <option value="Perempuan">Perempuan</option>                         
            </select>
          </div>           
          <div class="form-group"><label>Umur</label><input type="number" class="form-control" name="Umur"></div>           
          <div class="form-group"><label>Terakhir Terlihat</label><input type="datetime-local" class="form-control" name="Terakhir_terlihat"></div>           
          <div class="form-group"><label>Lokasi Terakhir Terlihat</label><input type="text" class="form-control" name="Lokasi_Terakhir_Terlihat"></div>           
          <div class="form-group"><label>Deskripsi</label><textarea class="form-control" name="Deskripsi" rows="3"></textarea></div>           
          <div class="form-group"><label>Nama Pelapor</label><input type="text" class="form-control" name="Nama_Pelapor"></div>           
          <div class="form-group"><label>No. Pelapor</label><input type="text" class="form-control" name="No_Pelapor"></div>           
          <div class="form-group"><label>Hubungan dengan Korban</label>                         
            <select class="form-control" name="Hubungan_dengan_Korban">                             
              <option value="">-- Pilih --</option>                             
              <option value="Keluarga">Keluarga</option>                             
              <option value="Teman">Teman</option>                             
              <option value="Kerabat">Kerabat</option>                             
              <option value="Lainnya">Lainnya</option>                         
            </select>                     
          </div>           
          <div class="form-group"><label>Foto Korban</label><input type="file" class="form-control" name="Foto" accept="image/*"></div>         
        </div>         
        <div class="modal-footer">           
          <button type="submit" name="simpan" class="btn btn-success">Simpan</button>           
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>         
        </div>       
      </form>     
    </div>   
  </div> 
</div>  

<script> 
document.getElementById("formOrangHilang").addEventListener("submit", function(e) {     
  const form = this;     
  const fields = ["Nama","Jenis_Kelamin","Umur","Terakhir_terlihat","Lokasi_Terakhir_Terlihat","Deskripsi","Nama_Pelapor","No_Pelapor","Hubungan_dengan_Korban","Foto"];     
  let kosong = false;      

  fields.forEach(f => {         
    const el = form[f];         
    if (!el || !el.value.trim()) {             
      kosong = true;         
    }     
  });      

  if (kosong) {         
    e.preventDefault();         
    Swal.fire({             
      icon: "error",             
      title: "Oops...",             
      text: "Semua Kolom Wajib Diisi!"         
    });     
  } 
}); 
</script>  

<!-- Scripts --> 
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script> 
<script>   
document.addEventListener("DOMContentLoaded", function () {     
  var swiper = new Swiper(".mySwiper", {       
    loop: true,       
    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },       
    pagination: { el: ".swiper-pagination", clickable: true },       
    slidesPerView: 1,       
    spaceBetween: 15,       
    breakpoints: { 768: { slidesPerView: 2 }, 992: { slidesPerView: 3 } }     
  });   
}); 
</script>  

<!-- SweetAlert Notification --> 
<?php if (isset($_GET['status']) && $_GET['status'] == 'sukses') : ?> 
<script>   
Swal.fire({     
  icon: 'success',     
  title: 'Berhasil!',     
  text: 'Data orang hilang berhasil disimpan.',     
  confirmButtonColor: '#3085d6'   
}); 
</script> 
<?php endif; ?> 

</body> 
</html>
