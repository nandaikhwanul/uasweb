-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 11:16 PM
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
-- Database: `berita`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `kategori` enum('Technology','Lifestyle') NOT NULL,
  `author` varchar(100) NOT NULL,
  `tanggal_publikasi` date NOT NULL,
  `images` varchar(255) NOT NULL,
  `view` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `judul`, `isi`, `kategori`, `author`, `tanggal_publikasi`, `images`, `view`) VALUES
(1, 'Manfaat Olahraga bagi Kesehatan Tubuh', 'Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran.', 'Lifestyle', 'Frank', '2024-10-03', 'assets/images/1.jpg', 56),
(2, 'Panduan Lengkap Memilih Laptop untuk Pelajar', 'Memilih laptop yang tepat sangat penting bagi pelajar.', 'Technology', 'Charlie', '2024-10-23', 'assets/images/2.jpg', 23),
(3, 'Manfaat Olahraga bagi Kesehatan Tubuh', 'Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran.', 'Lifestyle', 'David', '2024-10-23', 'assets/images/3.jpg', 75),
(4, 'Inovasi Terbaru dalam Teknologi Smartphone', 'Teknologi smartphone terus berkembang dengan pesat.', 'Technology', 'Charlie', '2024-10-19', 'assets/images/4.jpg', 456),
(5, 'Membangun Rutinitas Pagi yang Efektif', 'Rutinitas pagi yang baik dapat meningkatkan produktivitas Anda sepanjang hari.', 'Lifestyle', 'Alice', '2024-10-07', 'assets/images/5.jpg', 73),
(6, 'Mengapa Kecerdasan Buatan Penting untuk Bisnis?', 'Kecerdasan buatan (AI) semakin menjadi bagian penting dalam strategi bisnis.', 'Technology', 'Grace', '2024-10-18', 'assets/images/6.jpg', 81),
(7, 'Tips Sehat untuk Makanan Ringan', 'Makanan ringan tidak selalu harus tidak sehat.', 'Lifestyle', 'Hannah', '2024-10-14', 'assets/images/7.jpg', 34),
(8, 'Internet of Things: Mengubah Cara Kita Hidup', 'Internet of Things (IoT) semakin mengubah cara kita berinteraksi dengan dunia sekitar.', 'Technology', 'Bob', '2024-10-18', 'assets/images/8.jpg', 32),
(9, 'Tips Sehat untuk Makanan Ringan', 'Makanan ringan tidak selalu harus tidak sehat.', 'Lifestyle', 'Bob', '2024-10-16', 'assets/images/9.jpg', 81),
(10, 'Panduan Lengkap Memilih Laptop untuk Pelajar', 'Memilih laptop yang tepat sangat penting bagi pelajar.', 'Technology', 'Eve', '2024-10-05', 'assets/images/10.jpg', 43),
(11, 'Manfaat Olahraga bagi Kesehatan Tubuh', 'Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran.', 'Lifestyle', 'Grace', '2024-10-28', 'assets/images/11.jpg', 51),
(12, 'Mengapa Kecerdasan Buatan Penting untuk Bisnis?', 'Kecerdasan buatan (AI) semakin menjadi bagian penting dalam strategi bisnis.', 'Technology', 'Frank', '2024-10-11', 'assets/images/12.jpg', 943),
(13, 'Membangun Rutinitas Pagi yang Efektif', 'Rutinitas pagi yang baik dapat meningkatkan produktivitas Anda sepanjang hari.', 'Lifestyle', 'Charlie', '2024-10-07', 'assets/images/13.jpg', 23),
(14, 'Panduan Lengkap Memilih Laptop untuk Pelajar', 'Memilih laptop yang tepat sangat penting bagi pelajar.', 'Technology', 'Alice', '2024-10-13', 'assets/images/14.jpg', 97),
(15, 'Manfaat Olahraga bagi Kesehatan Tubuh', 'Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran.', 'Lifestyle', 'Alice', '2024-10-08', 'assets/images/15.jpg', 74),
(16, 'Teknologi Blockchain dan Masa Depan Keamanan Data', 'Teknologi blockchain menawarkan solusi inovatif untuk masalah keamanan data.', 'Technology', 'Alice', '2024-10-10', 'assets/images/16.jpg', 23),
(17, 'Manfaat Olahraga bagi Kesehatan Tubuh', 'Olahraga adalah kunci untuk menjaga kesehatan tubuh dan pikiran.', 'Lifestyle', 'Eve', '2024-10-13', 'assets/images/17.jpg', 0),
(18, 'Internet of Things: Mengubah Cara Kita Hidup', 'Internet of Things (IoT) semakin mengubah cara kita berinteraksi dengan dunia sekitar.', 'Technology', 'Bob', '2024-10-03', 'assets/images/18.jpg', 0),
(19, 'Menjaga Kesehatan Mental di Era Digital', 'Dalam dunia yang semakin terhubung melalui teknologi, menjaga kesehatan mental menjadi lebih penting dari sebelumnya.', 'Lifestyle', 'Isaac', '2024-10-11', 'assets/images/19.jpg', 0),
(20, 'Mengapa Kecerdasan Buatan Penting untuk Bisnis?', 'Kecerdasan buatan (AI) semakin menjadi bagian penting dalam strategi bisnis.', 'Technology', 'Jack', '2024-10-12', 'assets/images/20.jpg', 90);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
