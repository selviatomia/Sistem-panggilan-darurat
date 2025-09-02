<?php
require('fpdf/fpdf.php');
include 'db.php';

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
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);

        // Nomor halaman
        $this->Cell(0,5,'Halaman '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function Row($data, $widths, $aligns) {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = 6 * $nb;
        $this->CheckPageBreak($h);
        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 6, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h) {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

$pdf = new PDF();
$pdf->AliasNbPages(); // aktifkan total halaman
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Daftar Orang Hilang SAR Ambon', 0, 1, 'C');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(255, 204, 0);
$header = ['No', 'Nama', 'Jenis Kelamin', 'Terakhir Terlihat', 'Lokasi', 'Deskripsi', 'Status'];
$w = [10, 25, 25, 35, 25, 50, 20];

for ($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
}
$pdf->Ln();

// Data tabel
$pdf->SetFont('Arial', '', 10);
$result = mysqli_query($conn, "SELECT * FROM laporan_orang_hilang ORDER BY id ASC");
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    switch ($row['Status']) {
        case 0: $status_text = "Belum Verifikasi"; break;
        case 1: $status_text = "Dalam Proses Pencarian"; break;
        case 2: $status_text = "Ditemukan"; break;
        case 3: $status_text = "Selesai"; break;
        default: $status_text = "-";
    }

    $pdf->Row([
        $no++,
        $row['Nama'],
        $row['Jenis_Kelamin'],
        $row['Terakhir_terlihat'],
        $row['Lokasi_Terakhir_Terlihat'],
        $row['Deskripsi'],
        $status_text
    ], $w, ['C','L','C','C','C','L','C']);
}

$pdf->Output('I', 'Data_Orang_Hilang.pdf');
