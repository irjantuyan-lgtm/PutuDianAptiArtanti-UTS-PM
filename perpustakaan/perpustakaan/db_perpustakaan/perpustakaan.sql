-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 05, 2026 at 11:25 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_anggota`
--

CREATE TABLE `tb_anggota` (
  `id_anggota` int NOT NULL,
  `nama_anggota` varchar(100) NOT NULL,
  `alamat` text,
  `nomor_telepon` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_anggota`
--

INSERT INTO `tb_anggota` (`id_anggota`, `nama_anggota`, `alamat`, `nomor_telepon`) VALUES
(1, 'Dian', 'Bukit Batu', '089892035647'),
(2, 'Andre', 'Gianyar', '087869987345'),
(3, 'Krisna', 'Denpasar', '081335267456'),
(4, 'Meylan', 'Bangli', '087861335467'),
(5, 'Dwi Kristina', 'Gianyar', '082345678345'),
(6, 'Rizky', 'Denpasar', '089809876435'),
(7, 'Dira Putri', 'Bangli', '087675893465'),
(8, 'Satya', 'Denpasar', '082345678394'),
(9, 'Sinta', 'Gianyar', '087647834213'),
(10, 'Dinda', 'Gianyar', '082374890172'),
(11, 'Randi', 'Bangli', '089786985736');

-- --------------------------------------------------------

--
-- Table structure for table `tb_buku`
--

CREATE TABLE `tb_buku` (
  `id_buku` int NOT NULL,
  `judul_buku` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_buku`
--

INSERT INTO `tb_buku` (`id_buku`, `judul_buku`, `penulis`, `penerbit`, `tahun_terbit`, `stok`) VALUES
(2, 'Pemrograman', 'Abdul Kadir', 'Andi', '2010', 11),
(3, '100 Hari menjadi Programmer', 'Hima Jaya', 'Lingga', '2019', 4),
(4, 'Dasar Dasar C++', 'Dea Lestari', 'Gramedia Pustaka', '2015', 12),
(5, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', '2005', 3),
(6, 'Bumi', 'Tere Liye', 'Gramedia Pustaka', '2014', 4),
(7, 'Hujan', 'Tere Liye', 'Gramedia Pustaka', '2012', 6),
(8, 'Rekayasa Perangkat Lunak', 'Roger S.Pressman', 'Raka', '2011', 1),
(9, 'Psikologi Kepribadian', 'Alwisol', 'Republika', '2009', 6),
(10, 'Mariposa', 'Luluk HF', 'Coconut Books', '2008', 8),
(11, 'Dilan 1990', 'Pidi Baiq', 'Pastel Books', '2014', 7),
(12, 'Angkasa', 'Nindya Putri', 'Gramedia Pustaka', '2020', 11),
(13, 'Dasar HTML', 'Raya Putri', 'Andi', '2019', 10);

-- --------------------------------------------------------

--
-- Table structure for table `tb_peminjaman`
--

CREATE TABLE `tb_peminjaman` (
  `id_peminjaman` int NOT NULL,
  `id_anggota` int NOT NULL,
  `id_buku` int NOT NULL,
  `id_petugas` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `status_peminjaman` enum('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_peminjaman`
--

INSERT INTO `tb_peminjaman` (`id_peminjaman`, `id_anggota`, `id_buku`, `id_petugas`, `tanggal_pinjam`, `tanggal_jatuh_tempo`, `jumlah`, `status_peminjaman`) VALUES
(2, 1, 2, 1, '2026-11-03', '2026-11-10', 1, 'Dikembalikan'),
(3, 2, 2, 1, '2026-11-04', '2026-11-12', 1, 'Dipinjam'),
(5, 9, 12, 1, '2026-11-05', '2026-11-12', 1, 'Dikembalikan'),
(6, 3, 9, 1, '2026-11-04', '2026-11-11', 2, 'Dipinjam'),
(7, 4, 5, 1, '2026-11-05', '2026-11-12', 1, 'Dipinjam'),
(8, 5, 7, 1, '2026-11-07', '2026-11-14', 2, 'Dikembalikan'),
(9, 6, 3, 1, '2026-11-06', '2026-11-13', 1, 'Dipinjam'),
(10, 7, 10, 1, '2026-11-06', '2026-11-13', 2, 'Dipinjam'),
(11, 8, 8, 1, '2026-11-08', '2026-11-15', 2, 'Dipinjam'),
(12, 9, 5, 1, '2026-11-10', '2026-11-17', 1, 'Dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengembalian`
--

CREATE TABLE `tb_pengembalian` (
  `id_pengembalian` int NOT NULL,
  `id_peminjaman` int NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `denda` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pengembalian`
--

INSERT INTO `tb_pengembalian` (`id_pengembalian`, `id_peminjaman`, `tanggal_kembali`, `denda`) VALUES
(5, 8, '2026-11-10', 0.00),
(6, 5, '2026-11-11', 0.00),
(7, 2, '2026-11-06', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` int NOT NULL,
  `nama_petugas` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `nama_petugas`, `email`, `username`, `password`) VALUES
(1, 'Admin Perpustakaan', 'adminperpustakaan@gmail.com', 'admin', '$2y$10$G8l.QBTSDMDuwM9pbs0mqODVJvQuLIXMuA2Xl2Exfdz6QqIV77Aoy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `tb_buku`
--
ALTER TABLE `tb_buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_anggota` (`id_anggota`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_petugas` (`id_petugas`);

--
-- Indexes for table `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD UNIQUE KEY `id_peminjaman` (`id_peminjaman`);

--
-- Indexes for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_anggota`
--
ALTER TABLE `tb_anggota`
  MODIFY `id_anggota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_buku`
--
ALTER TABLE `tb_buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  MODIFY `id_peminjaman` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  MODIFY `id_pengembalian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  MODIFY `id_petugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_peminjaman`
--
ALTER TABLE `tb_peminjaman`
  ADD CONSTRAINT `tb_peminjaman_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `tb_anggota` (`id_anggota`),
  ADD CONSTRAINT `tb_peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `tb_buku` (`id_buku`),
  ADD CONSTRAINT `tb_peminjaman_ibfk_3` FOREIGN KEY (`id_petugas`) REFERENCES `tb_petugas` (`id_petugas`);

--
-- Constraints for table `tb_pengembalian`
--
ALTER TABLE `tb_pengembalian`
  ADD CONSTRAINT `tb_pengembalian_ibfk_1` FOREIGN KEY (`id_peminjaman`) REFERENCES `tb_peminjaman` (`id_peminjaman`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
