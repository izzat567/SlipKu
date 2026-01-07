-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 07, 2026 at 02:34 AM
-- Server version: 12.0.2-MariaDB
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `slipku_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `katalaluan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `nama`, `katalaluan`, `created_at`, `status`) VALUES
(1, 'admin@slipku.edu.my', 'Cikgu Admin', 'admin123', '2025-12-16 17:26:40', 1),
(2, 'siti@slipku.edu.my', 'Cikgu Siti Aisyah', '$2y$10$YourHashedPasswordHere', '2026-01-06 06:47:45', 1),
(3, 'ali@slipku.edu.my', 'Cikgu Ali Asri', '$2y$10$YourHashedPasswordHere', '2026-01-06 06:47:45', 1),
(4, 'ahmad@slipku.edu.my', 'Cikgu Ahmad Fahmi', '$2y$10$YourHashedPasswordHere', '2026-01-06 06:47:45', 1),
(5, 'rosnah@slipku.edu.my', 'Cikgu Rosnah Ismail', '$2y$10$YourHashedPasswordHere', '2026-01-06 06:47:45', 1),
(6, 'lim@slipku.edu.my', 'Cikgu Lim Mei Ling', '$2y$10$YourHashedPasswordHere', '2026-01-06 06:47:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_telefon` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama` varchar(10) NOT NULL,
  `tahun` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama`, `tahun`, `status`) VALUES
(1, '1A', 1, 1),
(2, '1B', 1, 1),
(3, '2A', 2, 1),
(4, '2B', 2, 1),
(5, '3A', 3, 1),
(6, '3B', 3, 1),
(7, '4A', 4, 1),
(8, '4B', 4, 1),
(9, '5A', 5, 1),
(10, '5B', 5, 1),
(11, '6A', 6, 1),
(12, '6B', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `markah`
--

CREATE TABLE `markah` (
  `id` int(11) NOT NULL,
  `id_pelajar` int(11) NOT NULL,
  `id_perperiksaan` int(11) NOT NULL,
  `markah` int(11) NOT NULL,
  `gred` varchar(2) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `tarikh_cipta` date NOT NULL,
  `tarikh_kemaskini` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matapelajaran`
--

CREATE TABLE `matapelajaran` (
  `id` int(11) NOT NULL,
  `kod` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tahun` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matapelajaran`
--

INSERT INTO `matapelajaran` (`id`, `kod`, `nama`, `tahun`, `status`) VALUES
(1, 'MAT01', 'Matematik', '1-6', 1),
(2, 'BM01', 'Bahasa Melayu', '1-6', 1),
(3, 'BI01', 'Bahasa Inggeris', '1-6', 1),
(4, 'SAINS01', 'Sains', '1-6', 1),
(5, 'PISLAM01', 'Pendidikan Islam', '1-6', 1),
(6, 'SEJ01', 'Sejarah', '4-6', 1),
(7, 'PJK01', 'Pendidikan Jasmani dan Kesihatan', '1-6', 1),
(8, 'TMK01', 'Teknologi Maklumat dan Komunikasi', '4-6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pelajar`
--

CREATE TABLE `pelajar` (
  `id` int(11) NOT NULL,
  `id_kelas` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_kp` varchar(14) NOT NULL,
  `jantina` enum('L','P') NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelajar`
--

INSERT INTO `pelajar` (`id`, `id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(1, 'S0012024', 'Ahmad bin Abdullah', '010101-14-0001', 'L', 1),
(2, 'S0022024', 'Siti Nurhaliza binti Kamal', '010101-14-0002', 'P', 1),
(3, 'S0032024', 'Muhammad Ali bin Ahmad', '010101-14-0003', 'L', 1),
(4, 'S0042024', 'Nurul Aini binti Mohd', '010101-14-0004', 'P', 1),
(5, 'S0052024', 'Amirul Hakim bin Zamri', '010101-14-0005', 'L', 1),
(6, 'S0062024', 'Nur Fatimah binti Azman', '010101-14-0006', 'P', 1),
(7, 'S0072024', 'Hafiz bin Rosli', '010101-14-0007', 'L', 1),
(8, 'S0082024', 'Nur Syafiqah binti Rahim', '010101-14-0008', 'P', 1),
(9, 'S0092024', 'Amir bin Farid', '010101-14-0009', 'L', 1),
(10, 'S0102024', 'Siti Khadijah binti Kamarul', '010101-14-0010', 'P', 1),
(11, 'S0112024', 'Muhammad Hariz bin Azlan', '010101-14-0011', 'L', 1),
(12, 'S0122024', 'Nur Ain binti Zamri', '010101-14-0012', 'P', 1),
(13, 'S0132024', 'Ahmad Fahmi bin Ridzuan', '010101-14-0013', 'L', 1),
(14, 'S0142024', 'Nurul Iman binti Faizal', '010101-14-0014', 'P', 1),
(15, 'S0152024', 'Amir Syahmi bin Razak', '010101-14-0015', 'L', 1),
(16, 'S0162024', 'Siti Sarah binti Halim', '010101-14-0016', 'P', 1),
(17, 'S0172024', 'Muhammad Akmal bin Shahrol', '010101-14-0017', 'L', 1),
(18, 'S0182024', 'Nur Athirah binti Nazri', '010101-14-0018', 'P', 1),
(19, 'S0192024', 'Ahmad Shukri bin Jamal', '010101-14-0019', 'L', 1),
(20, 'S0202024', 'Nur Farhana binti Zainal', '010101-14-0020', 'P', 1),
(21, 'S0212024', 'Amir Hafiz bin Roslan', '010101-14-0021', 'L', 1),
(22, 'S0222024', 'Siti Mariam binti Aziz', '010101-14-0022', 'P', 1),
(23, 'S0232024', 'Muhammad Firdaus bin Kamal', '010101-14-0023', 'L', 1),
(24, 'S0242024', 'Nur Qistina binti Ridzuan', '010101-14-0024', 'P', 1),
(25, 'S0252024', 'Ahmad Asri bin Shafie', '010101-14-0025', 'L', 1),
(26, 'S0262024', 'Nur Syahira binti Faizal', '010101-14-0026', 'P', 1),
(27, 'S0272024', 'Amir Irfan bin Razak', '010101-14-0027', 'L', 1),
(28, 'S0282024', 'Siti Aisyah binti Halim', '010101-14-0028', 'P', 1),
(29, 'S0292024', 'Muhammad Danish bin Shahrol', '010101-14-0029', 'L', 1),
(30, 'S0302024', 'Nur Azmina binti Nazri', '010101-14-0030', 'P', 1),
(31, 'S0312024', 'Ahmad Syahmi bin Jamal', '010101-14-0031', 'L', 1),
(32, 'S0322024', 'Nur Fatin binti Zainal', '010101-14-0032', 'P', 1),
(33, 'S0332024', 'Amir Aiman bin Roslan', '010101-14-0033', 'L', 1),
(34, 'S0342024', 'Siti Hajar binti Aziz', '010101-14-0034', 'P', 1),
(35, 'S0352024', 'Muhammad Adam bin Kamal', '010101-14-0035', 'L', 1),
(36, 'S0362024', 'Nur Irdina binti Ridzuan', '010101-14-0036', 'P', 1),
(37, 'S0372024', 'Ahmad Zikri bin Shafie', '010101-14-0037', 'L', 1),
(38, 'S0382024', 'Muhammad Irfan bin Ismail', '010101-14-0038', 'L', 1),
(39, 'S0392024', 'Nur Anis binti Rahman', '010101-14-0039', 'P', 1),
(40, 'S0402024', 'Ahmad Faris bin Zainal', '010101-14-0040', 'L', 1);

-- --------------------------------------------------------

--
-- Table structure for table `peperiksaan`
--

CREATE TABLE `peperiksaan` (
  `id` int(11) NOT NULL,
  `id_matapelajaran` int(11) NOT NULL,
  `tahun_akademik` varchar(50) NOT NULL,
  `nama_peperiksaan` varchar(100) NOT NULL,
  `tarikh_mula` date DEFAULT NULL,
  `tarikh_tamat` date DEFAULT NULL,
  `tarikh_cipta` timestamp NOT NULL DEFAULT current_timestamp(),
  `jenis` enum('pertengahan','akhir','percubaan','ujian') NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peperiksaan`
--

INSERT INTO `peperiksaan` (`id`, `id_matapelajaran`, `tahun_akademik`, `nama_peperiksaan`, `tarikh_mula`, `tarikh_tamat`, `tarikh_cipta`, `jenis`, `status`) VALUES
(1, 0, '', 'Peperiksaan Pertengahan Tahun 2024', '2024-04-01', '2024-04-12', '2026-01-06 06:47:45', 'pertengahan', 1),
(2, 0, '', 'Peperiksaan Akhir Tahun 2024', '2024-10-01', '2024-10-15', '2026-01-06 06:47:45', 'akhir', 1),
(3, 0, '', 'Ujian Bulanan Mac 2024', '2024-03-15', '2024-03-20', '2026-01-06 06:47:45', 'ujian', 1),
(4, 0, '', 'Percubaan UPSR 2024', '2024-08-01', '2024-08-05', '2026-01-06 06:47:45', 'percubaan', 1),
(5, 0, '', 'Peperiksaan Pertengahan Tahun 2025', '2025-04-01', '2025-04-12', '2026-01-06 06:47:45', 'pertengahan', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_kelas` (`tahun`,`nama`);

--
-- Indexes for table `matapelajaran`
--
ALTER TABLE `matapelajaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kod_subjek` (`kod`);

--
-- Indexes for table `pelajar`
--
ALTER TABLE `pelajar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pelajar_id` (`id_kelas`),
  ADD UNIQUE KEY `nokp` (`no_kp`);

--
-- Indexes for table `peperiksaan`
--
ALTER TABLE `peperiksaan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `matapelajaran`
--
ALTER TABLE `matapelajaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pelajar`
--
ALTER TABLE `pelajar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `peperiksaan`
--
ALTER TABLE `peperiksaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
