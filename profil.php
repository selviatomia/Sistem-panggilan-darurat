<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Panggilan Darurat SAR</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      .navbar {
        
        background: linear-gradient(90deg, #f39c12, #e67e22);
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      }
      .navbar-brand {
      font-size: 1.1rem;
      letter-spacing: 1px;
    }
    .navbar .nav-link {
      transition: 0.3s;
    }
    .navbar .nav-link:hover {
      text-decoration: underline;
    }

      .hero {
        height: 100vh;
        background: url('fotoprofil.jpg') center/cover no-repeat;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
      }
      .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5); /* overlay gelap */
      }
      .hero-content {
        position: relative;
        z-index: 2;
      }
      .hero h1 {
        font-size: 3rem;
        font-weight: bold;
      }
      .hero p {
        font-size: 1.2rem;
        margin-top: 15px;
      }
      .section {
        padding: 60px 20px;
      }
      .social-icons i {
        transition: transform 0.3s ease;
      }
      .social-icons i:hover {
        transform: scale(1.2);
      }
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
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse ml-auto" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">
            <i class="fas fa-home"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="tel:0911323774">
            <i class="fas fa-phone-alt"></i> Telepon Darurat
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="profil.php">
            <i class="fas fa-user"></i> Profil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="login.php">
            <i class="fas fa-sign-in-alt"></i> Login
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Kantor Pencarian & Pertolongan Ambon</h1>
        <p>Siap Siaga Menolong di Setiap Kondisi Darurat</p>
        <a href="tel:0911323774" class="btn btn-danger btn-lg mt-3">
          <i class="fas fa-phone-alt"></i> Telepon Darurat
        </a>
      </div>
    </section>

   <!-- Profil -->
<div class="container section py-5">
  <div class="col-md-10 mx-auto">
    <div class="card shadow-lg border-0 rounded-3">
      <div class="card-body p-5 text-center">
        <h3 class="mb-4 text-warning font-weight-bold">
          <i class="fas fa-building mr-2"></i> Profil
        </h3>
        <p class="text-muted mb-3">
          Kantor Pencarian dan Pertolongan (Basarnas) Ambon adalah unit pelaksana teknis di bawah 
          Badan Nasional Pencarian dan Pertolongan (Basarnas) yang memiliki tugas melaksanakan operasi 
          pencarian dan pertolongan kecelakaan pelayaran, penerbangan, dan kondisi darurat lainnya di wilayah Maluku dan sekitarnya.
        </p>

        <p class="text-muted mb-3">
          Basarnas Ambon berkomitmen untuk memberikan pelayanan <span class="font-weight-bold text-dark">cepat, tepat, dan aman</span> 
          dalam setiap kegiatan SAR dengan dukungan personel profesional dan peralatan memadai demi keselamatan masyarakat.
        </p>

        <div class="mt-4">
          <h5 class="mb-2"><i class="fas fa-map-marker-alt text-danger"></i> Alamat</h5>
          <p class="mb-0 font-weight-bold">Jl. Dr. J. Leimena, Ambon, Maluku</p>
        </div>
      </div>
    </div>
  </div>
</div>


   <!-- Sosial Media -->
<section class="sosial-media text-center py-5">
  <div class="container glass-box">
    <h4 class="mb-4 font-weight-bold">
      <i class="fas fa-share-alt mr-2"></i> Ikuti Kami
    </h4>
    <div class="social-icons d-flex justify-content-center flex-wrap">
      <a href="https://www.facebook.com/share/16kAshr61h/?mibextid=wwXIfr" target="_blank" class="social-circle bg-facebook">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://www.instagram.com/kantorsar_ambon?igsh=NmYzNzh3djRuZXhl" target="_blank" class="social-circle bg-instagram">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://youtube.com/@basarnasambonofficial5453?si=74Unyx-n8Z4iVb97" target="_blank" class="social-circle bg-youtube">
        <i class="fab fa-youtube"></i>
      </a>
      <a href="https://www.tiktok.com/@kantorsar_ambon?_t=ZS-8yU7qdWE7Ji&_r=1" target="_blank" class="social-circle bg-tiktok">
        <i class="fab fa-tiktok"></i>
      </a>
    </div>
  </div>
</section>

<style>
  /* efek glassmorphism */
  .glass-box {
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
  }

  .sosial-media h4 {
    font-size: 1.8rem;
    letter-spacing: 1px;
    color: #333;
  }

  .social-circle {
    width: 60px;
    height: 60px;
    margin: 0 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-decoration: none;
  }

  .social-circle:hover {
    transform: translateY(-6px) scale(1.1);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  }

  /* Warna khusus tiap sosmed */
  .bg-facebook { background: #1877f2; }
  .bg-instagram { background: radial-gradient(circle at 30% 30%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
  .bg-youtube { background: #ff0000; }
  .bg-tiktok { background: #000; }
</style>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
  </body>
</html>
