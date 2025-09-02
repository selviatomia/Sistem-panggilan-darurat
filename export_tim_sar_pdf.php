<?php
session_start();
require 'db.php';
require('fpdf/fpdf.php'); // pastikan path sesuai

class PDF extends FPDF {
    function Header() {
        // Logo
        $this->Image('logooo.png', 10, 6, 20);

        // Nama Kantor
        $this->SetFont('Arial','B',12);
        $this->Cell(0,5,'BADAN NASIONAL PENCARIAN DAN PERTOLONGAN',0,1,'C');
        $this->Cell(0,5,'KANTOR PENCARIAN DAN PERTOLONGAN AMBON',0,1,'C');

        // Alamat
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'Jl. Dr.J. Leimena, Hative Besar, Kec. Tlk. Ambon, Kota Ambon, Maluku',0,1,'C');
        $this->Cell(0,5,'Telp: (0911) 323774 | Email: sar.ambon@basarnas.go.id',0,1,'C');
        $this->Ln(5);

        // Garis pembatas
        $this->Line(10, 30, 200, 30);
        $this->Ln(12);
    }

    function Footer() {
        // Posisi 1,5 cm dari bawah
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);

        // Nomor halaman
        $this->Cell(0,5,'Halaman '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Judul laporan
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Laporan Data Tim SAR',0,1,'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial','B',11);
$pdf->SetFillColor(255,204,0);
$pdf->Cell(10,10,'No',1,0,'C',true);
$pdf->Cell(50,10,'Nama Tim',1,0,'C',true);
$pdf->Cell(35,10,'Jumlah Anggota',1,0,'C',true);
$pdf->Cell(40,10,'Status',1,0,'C',true);
$pdf->Cell(55,10,'Tugas',1,1,'C',true);

// Isi tabel
$pdf->SetFont('Arial','',11);
$no = 1;
$result = mysqli_query($conn, "SELECT * FROM tim_sar ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10,10,$no++,1,0,'C');
    $pdf->Cell(50,10,$row['Nama_tim'],1,0);
    $pdf->Cell(35,10,$row['Jumlah_Anggota'],1,0,'C');
    $pdf->Cell(40,10,$row['Status'],1,0,'C');
    $pdf->Cell(55,10,$row['Tugas'],1,1,'C');
}

$pdf->Output('I','laporan_tim_sar.pdf');
