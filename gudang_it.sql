-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2024 at 09:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudang_it`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `idKategori` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `stok` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `idKategori`, `kode`, `nama`, `stok`) VALUES
(37, 1, 'B001', 'Barang 1', '10'),
(38, 1, 'B002', 'Barang 2', '10'),
(39, 3, 'B003', 'Barang 3', '10'),
(40, 3, 'B004', 'Barang 4', '10'),
(41, 5, 'PCB', 'Percobaan', '10'),
(42, 1, 'B005', 'Barang 5', '10'),
(43, 1, 'B006', 'Barang 6', '10'),
(44, 3, 'B007', 'Barang 7', '10'),
(45, 3, 'B008', 'Barang 8', '10');

-- --------------------------------------------------------

--
-- Table structure for table `inventaris`
--

CREATE TABLE `inventaris` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `stok` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventaris`
--

INSERT INTO `inventaris` (`id`, `nama`, `stok`) VALUES
(1, 'HDMI', '10'),
(2, 'Laptop Asus Putih', '10'),
(3, 'Proyektor', '10'),
(4, 'USB LAN', '10'),
(5, 'Mouse', '10'),
(6, 'Keyboard', '10');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `keterangan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `keterangan`) VALUES
(1, 'Rutinan', 'Barang Wajib Mingguann'),
(3, 'Bulanan', 'Dilakukan Setiap Bulan'),
(4, 'Tahunan', 'Dilakukan Setiap Tahun');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `petugas` varchar(255) NOT NULL,
  `tempat` varchar(255) NOT NULL,
  `kegiatan` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `waktu`, `petugas`, `tempat`, `kegiatan`, `foto`) VALUES
(1, '2024-11-29 01:29:00', 'Marc Klok', 'Manajemen 5', 'Printer Rusak', '1732843740_berhasil.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `keluar`
--

CREATE TABLE `keluar` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idPetugas` int(11) NOT NULL,
  `idBarang` int(11) NOT NULL,
  `jumlah` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluar`
--

INSERT INTO `keluar` (`id`, `waktu`, `idPetugas`, `idBarang`, `jumlah`) VALUES
(1, '2024-10-22 00:24:13', 3, 5, '5'),
(2, '2024-10-22 00:25:55', 3, 5, '5'),
(3, '2024-11-28 13:17:41', 7, 8, '10'),
(4, '2024-11-30 04:09:56', 3, 35, '3'),
(5, '2024-11-01 04:52:41', 7, 38, '5');

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idPetugas` int(11) NOT NULL,
  `idBarang` int(11) NOT NULL,
  `jumlah` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`id`, `waktu`, `idPetugas`, `idBarang`, `jumlah`) VALUES
(4, '2024-10-21 11:13:05', 3, 4, '5'),
(5, '2024-10-21 11:14:21', 3, 4, '5'),
(6, '2024-10-22 00:23:33', 3, 5, '2'),
(7, '2024-11-28 06:37:10', 6, 8, '5'),
(8, '2024-11-28 07:06:02', 7, 5, '5'),
(9, '2024-11-01 03:45:09', 7, 38, '5');

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id`, `nama`) VALUES
(7, 'Bisma'),
(8, 'Lana'),
(9, 'Diki'),
(10, 'Eza'),
(11, 'Bayu'),
(12, 'Yoga');

-- --------------------------------------------------------

--
-- Table structure for table `pinjam`
--

CREATE TABLE `pinjam` (
  `id` int(11) NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nama` varchar(100) NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `keperluan` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `idInventaris` int(11) NOT NULL,
  `idPetugas` int(11) NOT NULL,
  `status` enum('dipinjam','selesai') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pinjam`
--

INSERT INTO `pinjam` (`id`, `waktu`, `nama`, `tempat`, `keperluan`, `jumlah`, `idInventaris`, `idPetugas`, `status`) VALUES
(42, '2024-10-21 14:31:30', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 1, 3, 'selesai'),
(43, '2024-11-09 04:14:04', 'Rhmd Ari', 'Manajemen 5 Lt3', 'Rapat', 10, 1, 3, 'selesai'),
(44, '2024-11-09 04:14:13', 'Rhmd Ari', 'Manajemen 5 Lt3', 'Rapat', 5, 2, 3, 'selesai'),
(45, '2024-11-21 10:15:45', 'Arei', 'Manajemen 5', 'Rapat', 1, 1, 3, 'selesai'),
(46, '2024-11-21 12:02:09', 'Ari', 'Manajemen 5', 'Rapat', 5, 1, 3, 'selesai'),
(47, '2024-11-21 12:02:05', 'Ari', 'Manajemen 5', 'Rapat', 5, 2, 3, 'selesai'),
(48, '2024-11-21 12:02:00', 'Ari', 'Manajemen 5', 'Rapat', 5, 3, 3, 'selesai'),
(49, '2024-11-28 06:36:47', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 1, 7, 'selesai'),
(50, '2024-11-29 05:28:04', 'Bisma', 'Manajemen 5', 'Rapat', 6, 1, 7, 'selesai'),
(51, '2024-11-29 05:28:15', 'Bisma', 'Manajemen 5', 'Rapat', 6, 2, 7, 'selesai'),
(52, '2024-11-29 05:28:54', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 1, 8, 'selesai'),
(53, '2024-11-29 05:28:47', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 2, 8, 'selesai'),
(54, '2024-11-29 05:28:56', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 3, 8, 'selesai'),
(55, '2024-11-29 05:28:51', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 4, 8, 'selesai'),
(56, '2024-11-29 05:29:01', 'Hamdani', 'Manajemen 5', 'Rapat', 5, 5, 8, 'selesai'),
(57, '2024-11-29 05:28:37', 'ham', 'Manajemen 5', 'Rapat', 3, 1, 8, 'selesai'),
(58, '2024-11-29 05:28:24', 'ham', 'Manajemen 5', 'Rapat', 3, 2, 8, 'selesai'),
(59, '2024-11-29 05:28:41', 'ham', 'Manajemen 5', 'Rapat', 3, 3, 8, 'selesai'),
(60, '2024-11-29 05:28:32', 'ham', 'Manajemen 5', 'Rapat', 3, 4, 8, 'selesai'),
(61, '2024-11-29 05:28:44', 'ham', 'Manajemen 5', 'Rapat', 3, 5, 8, 'selesai'),
(62, '2024-11-29 07:03:31', 'Muhamad', 'Manajemen 3', 'Rapat', 5, 1, 7, 'selesai'),
(63, '2024-11-29 07:03:20', 'Muhamad', 'Manajemen 3', 'Rapat', 5, 2, 7, 'selesai'),
(64, '2024-11-29 07:03:35', 'Muhamad', 'Manajemen 3', 'Rapat', 5, 3, 7, 'selesai'),
(65, '2024-11-29 07:03:25', 'Muhamad', 'Manajemen 3', 'Rapat', 5, 4, 7, 'selesai'),
(66, '2024-11-29 07:03:13', 'Muhamad', 'Manajemen 3', 'Rapat', 5, 5, 7, 'selesai'),
(67, '2024-11-29 07:08:06', 'Pram', 'Manajemen 3', 'Rapat', 5, 1, 8, 'selesai'),
(68, '2024-11-29 07:08:06', 'Pram', 'Manajemen 3', 'Rapat', 5, 2, 8, 'selesai'),
(69, '2024-11-29 07:08:06', 'Pram', 'Manajemen 3', 'Rapat', 5, 3, 8, 'selesai'),
(70, '2024-11-29 07:08:06', 'Pram', 'Manajemen 3', 'Rapat', 5, 4, 8, 'selesai'),
(71, '2024-11-29 07:08:06', 'Pram', 'Manajemen 3', 'Rapat', 5, 5, 8, 'selesai'),
(72, '2024-11-29 07:09:07', 'Dira', 'Manajemen 1', 'Rapat', 10, 1, 6, 'selesai'),
(73, '2024-11-29 07:09:07', 'Dira', 'Manajemen 1', 'Rapat', 10, 2, 6, 'selesai'),
(74, '2024-11-29 07:09:07', 'Dira', 'Manajemen 1', 'Rapat', 10, 3, 6, 'selesai'),
(75, '2024-11-29 07:09:07', 'Dira', 'Manajemen 1', 'Rapat', 10, 4, 6, 'selesai'),
(76, '2024-11-29 07:09:07', 'Dira', 'Manajemen 1', 'Rapat', 10, 5, 6, 'selesai'),
(77, '2024-11-29 07:11:44', 'Nadira', 'Manajemen 1', 'Rapat', 10, 1, 8, 'selesai'),
(78, '2024-11-29 07:11:44', 'Nadira', 'Manajemen 1', 'Rapat', 10, 2, 8, 'selesai'),
(79, '2024-11-29 07:11:44', 'Nadira', 'Manajemen 1', 'Rapat', 10, 3, 8, 'selesai'),
(80, '2024-11-29 07:11:44', 'Nadira', 'Manajemen 1', 'Rapat', 10, 4, 8, 'selesai'),
(81, '2024-11-29 07:11:44', 'Nadira', 'Manajemen 1', 'Rapat', 10, 5, 8, 'selesai'),
(82, '2024-11-30 04:36:44', 'Last', 'Manajemen 5', ' Dira', 10, 1, 6, 'selesai'),
(83, '2024-11-30 04:36:44', 'Last', 'Manajemen 5', ' Dira', 10, 2, 6, 'selesai'),
(84, '2024-11-30 04:36:44', 'Last', 'Manajemen 5', ' Dira', 10, 3, 6, 'selesai'),
(85, '2024-11-30 04:36:44', 'Last', 'Manajemen 5', ' Dira', 10, 4, 6, 'selesai'),
(86, '2024-11-30 04:36:44', 'Last', 'Manajemen 5', ' Dira', 10, 5, 6, 'selesai'),
(87, '2024-11-30 05:18:36', 'Nadhira', 'Manajemen 54', 'Rapat', 10, 1, 6, 'selesai'),
(88, '2024-11-30 05:18:36', 'Nadhira', 'Manajemen 54', 'Rapat', 10, 2, 6, 'selesai'),
(89, '2024-11-30 05:18:36', 'Nadhira', 'Manajemen 54', 'Rapat', 10, 3, 6, 'selesai'),
(90, '2024-11-30 05:18:36', 'Nadhira', 'Manajemen 54', 'Rapat', 10, 4, 6, 'selesai'),
(91, '2024-11-30 05:18:36', 'Nadhira', 'Manajemen 54', 'Rapat', 10, 5, 6, 'selesai'),
(92, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 1, 11, 'selesai'),
(93, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 2, 11, 'selesai'),
(94, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 3, 11, 'selesai'),
(95, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 4, 11, 'selesai'),
(96, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 5, 11, 'selesai'),
(97, '2024-11-30 14:07:53', 'NdhiraAzza', 'Diklat', 'Rapat Orientasi', 10, 6, 11, 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(250) NOT NULL,
  `level` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `level`) VALUES
(1, 'admin', '$2y$10$y19rk/GiEyLupjo.0Yf20uvPP0VSs86RJAuUkx1zrC/u9KxiQXfye', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventaris`
--
ALTER TABLE `inventaris`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keluar`
--
ALTER TABLE `keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pinjam`
--
ALTER TABLE `pinjam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `inventaris`
--
ALTER TABLE `inventaris`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `keluar`
--
ALTER TABLE `keluar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pinjam`
--
ALTER TABLE `pinjam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
