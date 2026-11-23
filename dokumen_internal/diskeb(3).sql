-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jul 2024 pada 11.50
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diskeb`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `gambar_judul` varchar(255) NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `gambar_judul`, `kegiatan`, `tanggal`) VALUES
(9, 'sattar', 'agenda1708481879_tb_gambar-gambar.jpeg', '<p align=\"center\">gadhsjgdsjag</p><p align=\"left\"><img src=\"../asset/img/0aa1883c6411f7873cb83dacb17b0afc.png\" data-filename=\"0aa1883c6411f7873cb83dacb17b0afc.png\" style=\"width: 468.833px; height: 283.857px;\"><img style=\"width: 50%;\" src=\"../asset/img/b3e', '2024-02-27'),
(10, 'ela', '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `agenda_b`
--

CREATE TABLE `agenda_b` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `file_pdf` varchar(255) NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `agenda_b`
--

INSERT INTO `agenda_b` (`id`, `judul`, `gambar`, `file_pdf`, `tanggal`) VALUES
(5, 'FESTIFAL ELA - ELA', 'agenda1708487070_pemasangan spanduk dan promosi.jpg', 'agenda1708487070_FESTIVAL ELA - ELA  TAHJUN 233.pdf', '2023-12-19'),
(6, 'DOKUMENTASI MUHIBAH BUDAYA JALUR REMPAH TAHUN  2022', 'agenda1708488288_12.jpg', 'agenda1708488288_DOKUMENTASI MUHIBAH BUDAYA JALUR REMPAH TAHUN  2022.pdf', '2022-03-03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `berita`
--

INSERT INTO `berita` (`id`, `file`) VALUES
(1, 'TL Pengisian Kuesioner24.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kepala`
--

CREATE TABLE `kepala` (
  `id` int(11) NOT NULL,
  `jenis_kepala` text NOT NULL,
  `foto` text NOT NULL,
  `nama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kepala`
--

INSERT INTO `kepala` (`id`, `jenis_kepala`, `foto`, `nama`) VALUES
(1, 'KEPALA DINAS', 'kepala1709108904_saa.jpg', 'DR. MUSLIM GANI ,S.Pd.M.Pd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nama_kepala`
--

CREATE TABLE `nama_kepala` (
  `id_kepala` int(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `mulai` varchar(200) NOT NULL,
  `selesai` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nama_kepala`
--

INSERT INTO `nama_kepala` (`id_kepala`, `nama`, `mulai`, `selesai`) VALUES
(1, 'safff', '3253', '6465'),
(2, 'safa', '3254', '65756');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pejabat_struktural`
--

CREATE TABLE `pejabat_struktural` (
  `id` int(11) NOT NULL,
  `nama_pejabat` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pejabat_struktural`
--

INSERT INTO `pejabat_struktural` (`id`, `nama_pejabat`, `jabatan`) VALUES
(1, 'star', 'bintang'),
(2, 'Drs. H. Ngatmin, MA', 'Kepala Sub Bagian Tata Usaha');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sejarah`
--

CREATE TABLE `sejarah` (
  `id_sejarah` int(11) NOT NULL,
  `sejarah` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sejarah`
--

INSERT INTO `sejarah` (`id_sejarah`, `sejarah`) VALUES
(1, 'Sebelum tahun 1980, Kementerian Agama Kab. Magelang (waktu itu bernama Departemen Agama – DEPAG), untuk aktivitas sehari-hari menempati kompleks Masjid Agung Kota Magelang yang terletak di Alun-alun Kota Magelang.\r\n\r\nUntuk menjalankan aktifitas sehari-hari, dengan jumlah pegawai waktu itu mencapai sekitar 6.800 pegawai, Bagian Sekretariat (Urusan Umum dan Keuangan), berkantor di sebuah ruangan di komplek Masjid Agung. Ruangan itu sekarang menjadi garasi Ambulan Masjid Agung.\r\n\r\nSementara itu, Bagian Kepegawaian menyewa sebuah rumah milik Mbok Kemi, warga kampung di sekitar Masjid Agung. Seksi Urais menyewa sebuah rumah di sebelah Toko Buku Cahaya. Seksi Pendidikan menyewa rumah di Jalan Diponegoro (sekarang bangunan itu berdiri Pom Bensin).\r\n\r\nBUKP (Badan Usaha Kesejahteraan Pegawai), sebagai embrio Koperasi KOKARDA sekarang, berkantor di sebelah selatan Alun-alun Magelang. Baru pada tahun sekitar 1984, pindah di Menowosari, Magelang.\r\n\r\nTahun 1980, dibangun kantor di Mertoyudan (sekarang Jalan Bambang Sugeng) dan mulai tahun 1981, Depag Kab. Magelang yang semula berkantor di kompleks Masjid Agung Magelang pindah ke Mertoyudan sampai sekarang.\r\n\r\nSeiring dengan dinamika organisasi, berikut nama-nama Kepala Kantor sejak tahun 1974 sd. sekarang.\r\n\r\n12345'),
(2, 'lorem'),
(8, 'lorem');

-- --------------------------------------------------------

--
-- Struktur dari tabel `visimisi`
--

CREATE TABLE `visimisi` (
  `id_visimis` int(11) NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `visimisi`
--

INSERT INTO `visimisi` (`id_visimis`, `visi`, `misi`) VALUES
(1, 'Mewujudkan Kota Ternate Yang Mandiri Dan Berkeadilan.', 'Menumbuh Kembangkan Kelembagaan Sosial Dalam Bingkai Kearifan Lokal \r\n(Adat Seatorang).');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `agenda_b`
--
ALTER TABLE `agenda_b`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kepala`
--
ALTER TABLE `kepala`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nama_kepala`
--
ALTER TABLE `nama_kepala`
  ADD PRIMARY KEY (`id_kepala`);

--
-- Indeks untuk tabel `pejabat_struktural`
--
ALTER TABLE `pejabat_struktural`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sejarah`
--
ALTER TABLE `sejarah`
  ADD PRIMARY KEY (`id_sejarah`);

--
-- Indeks untuk tabel `visimisi`
--
ALTER TABLE `visimisi`
  ADD PRIMARY KEY (`id_visimis`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `agenda_b`
--
ALTER TABLE `agenda_b`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `kepala`
--
ALTER TABLE `kepala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `nama_kepala`
--
ALTER TABLE `nama_kepala`
  MODIFY `id_kepala` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pejabat_struktural`
--
ALTER TABLE `pejabat_struktural`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sejarah`
--
ALTER TABLE `sejarah`
  MODIFY `id_sejarah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `visimisi`
--
ALTER TABLE `visimisi`
  MODIFY `id_visimis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
