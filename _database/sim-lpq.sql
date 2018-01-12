-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2017 at 10:20 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sim-lpq-0`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(2) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `foto_profil` varchar(32) NOT NULL DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `foto_profil`) VALUES
(1, 'admin', '456b7016a916a4b178dd72b947c152b7', 'default.png');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(5) UNSIGNED NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` char(32) NOT NULL,
  `nama_lengkap` varchar(32) NOT NULL,
  `jenis_kelamin` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `status` tinyint(3) UNSIGNED NOT NULL,
  `id_status` varchar(32) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `nomor_hp` varchar(13) NOT NULL,
  `nomor_wa` varchar(13) DEFAULT NULL,
  `email` varchar(32) NOT NULL,
  `alamat` text NOT NULL,
  `foto_profil` varchar(32) NOT NULL DEFAULT 'default.png',
  `waktu_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mentoring` tinyint(1) NOT NULL DEFAULT '1',
  `nama_murobbi` varchar(32) DEFAULT NULL,
  `nomor_murobbi` varchar(13) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `instruktur`
--

CREATE TABLE `instruktur` (
  `id_instruktur` int(5) UNSIGNED NOT NULL,
  `id_anggota` int(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `jadwal_view`
--
CREATE TABLE `jadwal_view` (
`program` tinyint(3) unsigned
,`jenis_kelamin` tinyint(1) unsigned
,`jenjang` tinyint(3) unsigned
,`nama_lengkap` varchar(32)
,`nomor_hp` varchar(13)
,`id_kelompok` int(3) unsigned
,`hari` tinyint(1) unsigned
,`waktu` time
);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok`
--

CREATE TABLE `kelompok` (
  `id_kelompok` int(3) UNSIGNED NOT NULL,
  `id_instruktur` int(5) UNSIGNED NOT NULL,
  `program` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `jenjang` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `hari` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `waktu` time NOT NULL,
  `kuota` tinyint(2) UNSIGNED NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `kelompok_view`
--
CREATE TABLE `kelompok_view` (
`jk` tinyint(1) unsigned
,`pr` tinyint(3) unsigned
,`j` tinyint(3) unsigned
,`h` tinyint(1) unsigned
,`w` time
,`jml_kuota` decimal(25,0)
,`sisa` decimal(26,0)
,`jml_kelompok` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `penjadwalan_santri`
--

CREATE TABLE `penjadwalan_santri` (
  `id_santri` int(5) UNSIGNED NOT NULL,
  `program` tinyint(1) UNSIGNED NOT NULL,
  `hari` tinyint(1) UNSIGNED NOT NULL,
  `waktu` time NOT NULL,
  `id_kelompok` int(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `penjadwalan_santri_view`
--
CREATE TABLE `penjadwalan_santri_view` (
`jk` tinyint(1) unsigned
,`pr` tinyint(3) unsigned
,`j` tinyint(3) unsigned
,`h` tinyint(1) unsigned
,`w` time
,`jml_kuota` decimal(25,0)
,`dipilih` bigint(21)
,`sisa` decimal(26,0)
,`jml_kelompok` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan_instruktur`
--

CREATE TABLE `pertanyaan_instruktur` (
  `id_instruktur` int(5) UNSIGNED NOT NULL,
  `program` tinyint(1) NOT NULL,
  `jawaban1` text,
  `jawaban2` text,
  `jawaban3` text,
  `jawaban4` text,
  `jawaban5` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pertanyaan_santri`
--

CREATE TABLE `pertanyaan_santri` (
  `id_santri` int(5) UNSIGNED NOT NULL,
  `program` tinyint(1) UNSIGNED NOT NULL,
  `jawaban1` text,
  `jawaban2` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `plot_view`
--
CREATE TABLE `plot_view` (
`jk` tinyint(1) unsigned
,`pr` tinyint(1) unsigned
,`j` tinyint(1) unsigned
,`h` tinyint(1) unsigned
,`w` time
,`nama_lengkap` varchar(32)
,`id_status` varchar(32)
,`nomor_hp` varchar(13)
,`idk` int(5) unsigned
,`nama_instruktur` varchar(32)
,`nomor_instruktur` varchar(13)
);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `id_anggota` int(5) UNSIGNED NOT NULL,
  `program` tinyint(1) UNSIGNED NOT NULL,
  `keanggotaan` tinyint(1) UNSIGNED NOT NULL,
  `jenjang` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `santri`
--

CREATE TABLE `santri` (
  `id_santri` int(5) UNSIGNED NOT NULL,
  `id_anggota` int(5) UNSIGNED NOT NULL,
  `placement_test` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sistem`
--

CREATE TABLE `sistem` (
  `id_pengaturan` int(1) UNSIGNED NOT NULL,
  `nama_pengaturan` varchar(32) NOT NULL,
  `isi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sistem`
--

INSERT INTO `sistem` (`id_pengaturan`, `nama_pengaturan`, `isi`) VALUES
(1, 'pendaftaran_santri', '0'),
(2, 'pendaftaran_instruktur', '0'),
(3, 'pengumuman_santri', '<big>\r\n<strong>Timeline</strong><br />\r\nPendaftaran Santri: 14 Agustus s.d. 4 September 2016<br />\r\nPlacement Test / Registrasi Ulang: 10â€“11 September 2016<br />\r\nPembayaran Buku & SPP: 10â€“18 September 2016<br />\r\nPenjadwalan Santri: 16 September 2016 09.30 s.d. 17 September 2016 23.59<br />\r\n<s>Temu Perdana Santri: 18 September 2016*</s><br />\r\nCP: 0877-6484-6361 (Luqni), 0852-2595-2060 (Annis)\r\n</big>'),
(4, 'pengumuman_instruktur', '<big>\r\n<strong>Timeline</strong><br />\r\nPendaftaran instruktur Tahsin: 9â€“29 Agustus 2016<br />\r\nTes instruktur Tahsin: 3â€“4 September 2016<br />\r\nPenjadwalan instruktur Tahsin: 9â€“12 September 2016*<br />\r\nCP: 0856-4240-7153 (Rosyid) / 0851-0194-0781 (Ghania)<br /><br />\r\nPendaftaran mu`alim Bahasa Arab di <a href="http://bit.ly/or_mualim" style="text-decoration: none;">bit.ly/or_mualim</a>\r\n</big>'),
(5, 'penjadwalan_santri', '0'),
(6, 'penjadwalan_instruktur', '0');

-- --------------------------------------------------------

--
-- Structure for view `jadwal_view`
--
DROP TABLE IF EXISTS `jadwal_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sim-lpq`@`localhost` SQL SECURITY DEFINER VIEW `jadwal_view`  AS  select `k`.`program` AS `program`,`a`.`jenis_kelamin` AS `jenis_kelamin`,`k`.`jenjang` AS `jenjang`,`a`.`nama_lengkap` AS `nama_lengkap`,`a`.`nomor_hp` AS `nomor_hp`,`k`.`id_kelompok` AS `id_kelompok`,`k`.`hari` AS `hari`,`k`.`waktu` AS `waktu` from ((`kelompok` `k` join `instruktur` `i`) join `anggota` `a`) where ((`k`.`id_instruktur` = `i`.`id_instruktur`) and (`i`.`id_anggota` = `a`.`id_anggota`)) order by `a`.`nama_lengkap` ;

-- --------------------------------------------------------

--
-- Structure for view `kelompok_view`
--
DROP TABLE IF EXISTS `kelompok_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sim-lpq`@`localhost` SQL SECURITY DEFINER VIEW `kelompok_view`  AS  select `a`.`jenis_kelamin` AS `jk`,`k`.`program` AS `pr`,`k`.`jenjang` AS `j`,`k`.`hari` AS `h`,`k`.`waktu` AS `w`,sum(`k`.`kuota`) AS `jml_kuota`,(sum(`k`.`kuota`) - (select count(`psx`.`id_santri`) from (((`penjadwalan_santri` `psx` join `santri` `sx`) join `anggota` `ax`) join `program` `px`) where ((`psx`.`id_santri` = `sx`.`id_santri`) and (`sx`.`id_anggota` = `ax`.`id_anggota`) and (`ax`.`id_anggota` = `px`.`id_anggota`) and (`px`.`program` = `psx`.`program`) and (`ax`.`jenis_kelamin` = `a`.`jenis_kelamin`) and (`psx`.`program` = `k`.`program`) and (`px`.`jenjang` = `k`.`jenjang`) and (`psx`.`hari` = `k`.`hari`) and (`psx`.`waktu` = `k`.`waktu`)))) AS `sisa`,count(`k`.`id_kelompok`) AS `jml_kelompok` from ((`kelompok` `k` join `instruktur` `i`) join `anggota` `a`) where ((`a`.`id_anggota` = `i`.`id_anggota`) and (`i`.`id_instruktur` = `k`.`id_instruktur`)) group by `a`.`jenis_kelamin`,`k`.`program`,`k`.`jenjang`,`k`.`hari`,`k`.`waktu` ;

-- --------------------------------------------------------

--
-- Structure for view `penjadwalan_santri_view`
--
DROP TABLE IF EXISTS `penjadwalan_santri_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sim-lpq`@`localhost` SQL SECURITY DEFINER VIEW `penjadwalan_santri_view`  AS  select `a`.`jenis_kelamin` AS `jk`,`k`.`program` AS `pr`,`k`.`jenjang` AS `j`,`k`.`hari` AS `h`,`k`.`waktu` AS `w`,sum(`k`.`kuota`) AS `jml_kuota`,(select count(`jsy`.`id_santri`) from (((`penjadwalan_santri` `jsy` join `santri` `sy`) join `anggota` `ay`) join `program` `py`) where ((`ay`.`id_anggota` = `sy`.`id_anggota`) and (`ay`.`id_anggota` = `py`.`id_anggota`) and (`sy`.`id_santri` = `jsy`.`id_santri`) and (`py`.`program` = `jsy`.`program`) and (`ay`.`jenis_kelamin` = `a`.`jenis_kelamin`) and (`jsy`.`program` = `k`.`program`) and (`py`.`jenjang` = `k`.`jenjang`) and (`jsy`.`hari` = `k`.`hari`) and (`jsy`.`waktu` = `k`.`waktu`))) AS `dipilih`,(sum(`k`.`kuota`) - (select count(`jsy`.`id_santri`) from (((`penjadwalan_santri` `jsy` join `santri` `sy`) join `anggota` `ay`) join `program` `py`) where ((`ay`.`id_anggota` = `sy`.`id_anggota`) and (`ay`.`id_anggota` = `py`.`id_anggota`) and (`sy`.`id_santri` = `jsy`.`id_santri`) and (`py`.`program` = `jsy`.`program`) and (`ay`.`jenis_kelamin` = `a`.`jenis_kelamin`) and (`jsy`.`program` = `k`.`program`) and (`py`.`jenjang` = `k`.`jenjang`) and (`jsy`.`hari` = `k`.`hari`) and (`jsy`.`waktu` = `k`.`waktu`)))) AS `sisa`,(select count(`kx`.`id_kelompok`) from ((`kelompok` `kx` join `instruktur` `ix`) join `anggota` `ax`) where ((`ax`.`id_anggota` = `ix`.`id_anggota`) and (`ix`.`id_instruktur` = `kx`.`id_instruktur`) and (`ax`.`jenis_kelamin` = `a`.`jenis_kelamin`) and (`kx`.`program` = `k`.`program`) and (`kx`.`jenjang` = `k`.`jenjang`) and (`kx`.`hari` = `k`.`hari`) and (`kx`.`waktu` = `k`.`waktu`))) AS `jml_kelompok` from ((`kelompok` `k` join `instruktur` `i`) join `anggota` `a`) where ((`a`.`id_anggota` = `i`.`id_anggota`) and (`i`.`id_instruktur` = `k`.`id_instruktur`)) group by `a`.`jenis_kelamin`,`k`.`program`,`k`.`jenjang`,`k`.`hari`,`k`.`waktu` ;

-- --------------------------------------------------------

--
-- Structure for view `plot_view`
--
DROP TABLE IF EXISTS `plot_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sim-lpq`@`localhost` SQL SECURITY DEFINER VIEW `plot_view`  AS  select `a`.`jenis_kelamin` AS `jk`,`ps`.`program` AS `pr`,`p`.`jenjang` AS `j`,`ps`.`hari` AS `h`,`ps`.`waktu` AS `w`,`a`.`nama_lengkap` AS `nama_lengkap`,`a`.`id_status` AS `id_status`,`a`.`nomor_hp` AS `nomor_hp`,`ps`.`id_kelompok` AS `idk`,(select `ay`.`nama_lengkap` from ((`anggota` `ay` join `instruktur` `iy`) join `kelompok` `ky`) where ((`ky`.`id_instruktur` = `iy`.`id_instruktur`) and (`iy`.`id_anggota` = `ay`.`id_anggota`) and (`ky`.`id_kelompok` = `idk`))) AS `nama_instruktur`,(select `ax`.`nomor_hp` from ((`anggota` `ax` join `instruktur` `ix`) join `kelompok` `kx`) where ((`kx`.`id_instruktur` = `ix`.`id_instruktur`) and (`ix`.`id_anggota` = `ax`.`id_anggota`) and (`kx`.`id_kelompok` = `idk`))) AS `nomor_instruktur` from (((`penjadwalan_santri` `ps` join `santri` `s`) join `anggota` `a`) join `program` `p`) where ((`ps`.`id_santri` = `s`.`id_santri`) and (`s`.`id_anggota` = `a`.`id_anggota`) and (`a`.`id_anggota` = `p`.`id_anggota`) and (`ps`.`program` = `p`.`program`)) order by `a`.`jenis_kelamin`,`ps`.`program`,`p`.`jenjang`,`ps`.`hari`,`ps`.`waktu`,`ps`.`id_kelompok`,`a`.`nama_lengkap` limit 0,1000 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `id_status` (`id_status`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nomor_hp` (`nomor_hp`);

--
-- Indexes for table `instruktur`
--
ALTER TABLE `instruktur`
  ADD PRIMARY KEY (`id_instruktur`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD PRIMARY KEY (`id_kelompok`),
  ADD KEY `id_instruktur` (`id_instruktur`);

--
-- Indexes for table `penjadwalan_santri`
--
ALTER TABLE `penjadwalan_santri`
  ADD PRIMARY KEY (`id_santri`,`program`),
  ADD KEY `id_kelompok` (`id_kelompok`);

--
-- Indexes for table `pertanyaan_instruktur`
--
ALTER TABLE `pertanyaan_instruktur`
  ADD PRIMARY KEY (`id_instruktur`,`program`);

--
-- Indexes for table `pertanyaan_santri`
--
ALTER TABLE `pertanyaan_santri`
  ADD PRIMARY KEY (`id_santri`,`program`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id_anggota`,`program`);

--
-- Indexes for table `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id_santri`),
  ADD UNIQUE KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `sistem`
--
ALTER TABLE `sistem`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `instruktur`
--
ALTER TABLE `instruktur`
  MODIFY `id_instruktur` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kelompok`
--
ALTER TABLE `kelompok`
  MODIFY `id_kelompok` int(3) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `santri`
--
ALTER TABLE `santri`
  MODIFY `id_santri` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sistem`
--
ALTER TABLE `sistem`
  MODIFY `id_pengaturan` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `instruktur`
--
ALTER TABLE `instruktur`
  ADD CONSTRAINT `instruktur_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kelompok`
--
ALTER TABLE `kelompok`
  ADD CONSTRAINT `kelompok_ibfk_1` FOREIGN KEY (`id_instruktur`) REFERENCES `instruktur` (`id_instruktur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penjadwalan_santri`
--
ALTER TABLE `penjadwalan_santri`
  ADD CONSTRAINT `penjadwalan_santri_ibfk_1` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjadwalan_santri_ibfk_2` FOREIGN KEY (`id_kelompok`) REFERENCES `kelompok` (`id_kelompok`);

--
-- Constraints for table `pertanyaan_instruktur`
--
ALTER TABLE `pertanyaan_instruktur`
  ADD CONSTRAINT `pertanyaan_instruktur_ibfk_1` FOREIGN KEY (`id_instruktur`) REFERENCES `instruktur` (`id_instruktur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pertanyaan_santri`
--
ALTER TABLE `pertanyaan_santri`
  ADD CONSTRAINT `pertanyaan_santri_ibfk_1` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `santri`
--
ALTER TABLE `santri`
  ADD CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
