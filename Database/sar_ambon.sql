-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Agu 2025 pada 06.07
-- Versi server: 10.4.22-MariaDB
-- Versi PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sar_ambon`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `id` int(11) NOT NULL,
  `id_orang_hilang` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `isi` text NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_orang_hilang`
--

CREATE TABLE `laporan_orang_hilang` (
  `id` int(50) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Jenis_Kelamin` varchar(100) NOT NULL,
  `Umur` varchar(50) NOT NULL,
  `Terakhir_terlihat` datetime NOT NULL,
  `Lokasi_Terakhir_Terlihat` varchar(100) NOT NULL,
  `Deskripsi` varchar(255) NOT NULL,
  `Nama_Pelapor` varchar(100) NOT NULL,
  `No_Pelapor` varchar(50) NOT NULL,
  `Hubungan_dengan_Korban` varchar(100) NOT NULL,
  `Foto` varchar(255) NOT NULL,
  `Status` int(2) NOT NULL DEFAULT 0,
  `Tim` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_orang_hilang`
--

INSERT INTO `laporan_orang_hilang` (`id`, `Nama`, `Jenis_Kelamin`, `Umur`, `Terakhir_terlihat`, `Lokasi_Terakhir_Terlihat`, `Deskripsi`, `Nama_Pelapor`, `No_Pelapor`, `Hubungan_dengan_Korban`, `Foto`, `Status`, `Tim`) VALUES
(1, 'Luffy', 'Laki-laki', '7', '2025-08-20 14:48:00', 'Waiheru', 'rambut pendek hitam, tinggi 135 cm, pakai baju merah', 'Nami', '081133445566', 'Anak', '68a6b3b3839f2.png', 0, NULL),
(2, 'Trafa', 'Laki-laki', '15', '2025-08-19 16:29:00', 'Wayame', 'Menggunakan celana jeans pendek hitam, comma hair, sendal swallow', 'Robin', '081133445577', 'Istri', '68a6bcff035ca.png', 3, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tim_sar`
--

CREATE TABLE `tim_sar` (
  `id` int(10) NOT NULL,
  `Nama_tim` varchar(255) NOT NULL,
  `Jumlah_Anggota` varchar(100) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT '0',
  `Tugas` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tim_sar`
--

INSERT INTO `tim_sar` (`id`, `Nama_tim`, `Jumlah_Anggota`, `Status`, `Tugas`) VALUES
(1, 'Leon', '7', 'Aktif', 'Kamis'),
(2, 'Tiger', '5', 'Aktif', 'Kamis'),
(3, 'Owl', '5', 'Aktif', 'Rabu'),
(4, 'Falcon', '6', 'Aktif', 'Rabu'),
(5, 'Eagle', '6', 'Aktif', 'Selasa'),
(6, 'Bravo', '6', 'Aktif', 'Selasa'),
(7, 'Alpha', '7', 'Aktif', 'Senin'),
(8, 'Delta', '7', 'Aktif', 'Senin'),
(9, 'Golf', '5', 'Aktif', 'Jumat'),
(10, 'Hotel', '6', 'Aktif', 'Jumat'),
(11, 'Sierra', '5', 'Aktif', 'Sabtu'),
(12, 'Mike', '7', 'Aktif', 'Sabtu'),
(13, 'Echo', '5', 'Aktif', 'Minggu'),
(14, 'Charlie', '6', 'Aktif', 'Minggu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `Username`, `Password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_orang_hilang` (`id_orang_hilang`);

--
-- Indeks untuk tabel `laporan_orang_hilang`
--
ALTER TABLE `laporan_orang_hilang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tim_sar`
--
ALTER TABLE `tim_sar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `laporan_orang_hilang`
--
ALTER TABLE `laporan_orang_hilang`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tim_sar`
--
ALTER TABLE `tim_sar`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`id_orang_hilang`) REFERENCES `laporan_orang_hilang` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
