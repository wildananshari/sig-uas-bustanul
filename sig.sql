-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 21, 2022 at 03:27 AM
-- Server version: 10.5.13-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u519137383_peta`
--

-- --------------------------------------------------------

--
-- Table structure for table `kordinat_gis`
--

CREATE TABLE `kordinat_gis` (
  `nomor` int(5) NOT NULL,
  `x` decimal(8,5) NOT NULL,
  `y` decimal(8,5) NOT NULL,
  `nama_tempat` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kordinat_gis`
--

INSERT INTO `kordinat_gis` (`nomor`, `x`, `y`, `nama_tempat`) VALUES
(7, '-8.21961', '114.34965', 'Banyuwangi'),
(10, '-8.72490', '115.17981', 'Pantai Kuta'),
(11, '-8.24272', '114.48629', 'Melaya'),
(12, '-8.64887', '115.19354', 'Denpasar'),
(13, '-8.41121', '115.14273', 'Penebel'),
(14, '-7.70687', '113.97817', 'Situbondo'),
(15, '-8.43838', '115.62063', 'Karangasem'),
(16, '-8.55247', '115.03836', 'Kerambitan'),
(17, '-8.31202', '115.02188', 'Pupuan'),
(18, '-8.00072', '114.40390', 'Wongsorejo'),
(19, '-8.44109', '115.31714', 'Tampak Siring'),
(20, '-8.73440', '115.54648', 'Nusa Penida'),
(21, '-8.14756', '115.11389', 'Sukasada'),
(22, '-8.54296', '115.12350', 'Tabanan'),
(23, '-8.22810', '114.29627', 'Glagah'),
(24, '-8.27839', '114.29455', 'Macan Putih'),
(25, '-8.14756', '114.39549', 'Ketapang'),
(26, '-8.15674', '114.21009', 'Songgon');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kordinat_gis`
--
ALTER TABLE `kordinat_gis`
  ADD PRIMARY KEY (`nomor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kordinat_gis`
--
ALTER TABLE `kordinat_gis`
  MODIFY `nomor` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
