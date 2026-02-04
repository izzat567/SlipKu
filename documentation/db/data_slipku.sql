-- ============================================
-- DATABASE SEKOLAH KEBANGSAAN RANTAU PANJANG
-- SESI AKADEMIK 2025/2026
-- ============================================

-- 1. TABEL KELAS (Darjah 1-6, 3 Kelas Setiap Darjah)
INSERT INTO kelas (id, nama, tahun, status) VALUES
-- Darjah 1
(1, '1 ALPHA', 2026, 'aktif'),
(2, '1 BETA', 2026, 'aktif'),
(3, '1 GAMMA', 2026, 'aktif'),

-- Darjah 2
(4, '2 ALPHA', 2026, 'aktif'),
(5, '2 BETA', 2026, 'aktif'),
(6, '2 GAMMA', 2026, 'aktif'),

-- Darjah 3
(7, '3 ALPHA', 2026, 'aktif'),
(8, '3 BETA', 2026, 'aktif'),
(9, '3 GAMMA', 2026, 'aktif'),

-- Darjah 4
(10, '4 ALPHA', 2026, 'aktif'),
(11, '4 BETA', 2026, 'aktif'),
(12, '4 GAMMA', 2026, 'aktif'),

-- Darjah 5
(13, '5 ALPHA', 2026, 'aktif'),
(14, '5 BETA', 2026, 'aktif'),
(15, '5 GAMMA', 2026, 'aktif'),

-- Darjah 6
(16, '6 ALPHA', 2026, 'aktif'),
(17, '6 BETA', 2026, 'aktif'),
(18, '6 GAMMA', 2026, 'aktif');

-- ============================================

-- 2. TABEL PELAJAR (15 Pelajar Setiap Kelas)
INSERT INTO pelajar (id, id_kelas, nama, no_kp, jantina, status) VALUES
-- KELAS 1 ALPHA (ID: 1) - 15 Pelajar
(101, 1, 'ALI BIN AHMAD', '170101-01-1234', 'L', 'aktif'),
(102, 1, 'SITI BINTI MOHD', '170215-02-5678', 'P', 'aktif'),
(103, 1, 'AMIR BIN KAMAL', '170330-01-9012', 'L', 'aktif'),
(104, 1, 'FATIMAH BINTI ZAINAL', '170415-02-3456', 'P', 'aktif'),
(105, 1, 'KUMAR A/L RAJ', '170520-01-7890', 'L', 'aktif'),
(106, 1, 'MEI LING', '170610-02-1122', 'P', 'aktif'),
(107, 1, 'HAFIZ BIN ISMAIL', '170715-01-3344', 'L', 'aktif'),
(108, 1, 'NOR HIDAYAH BINTI ALI', '170820-02-5566', 'P', 'aktif'),
(109, 1, 'RAJESH A/L KUMAR', '170925-01-7788', 'L', 'aktif'),
(110, 1, 'NURUL AIN BINTI HASSAN', '171030-02-9900', 'P', 'aktif'),
(111, 1, 'MOHD FAQIH BIN ZAINI', '171105-01-1122', 'L', 'aktif'),
(112, 1, 'SITI SARAH BINTI RAHMAN', '171210-02-3344', 'P', 'aktif'),
(113, 1, 'AHMAD SYAHMI BIN ZAKARIA', '171225-01-5566', 'L', 'aktif'),
(114, 1, 'NORAINI BINTI SULAIMAN', '171230-02-7788', 'P', 'aktif'),
(115, 1, 'WONG CHEN LOONG', '171231-01-9900', 'L', 'aktif'),

-- KELAS 1 BETA (ID: 2) - 15 Pelajar
(116, 2, 'NUR SYAFIQAH BINTI AZMI', '170115-02-1235', 'P', 'aktif'),
(117, 2, 'MUHAMMAD ADAM BIN YUSOF', '170230-01-5679', 'L', 'aktif'),
(118, 2, 'AISYAH BINTI ROSLI', '170315-02-9013', 'P', 'aktif'),
(119, 2, 'AHMAD DANIAL BIN HAMZAH', '170420-01-3457', 'L', 'aktif'),
(120, 2, 'SITI HAJAR BINTI KAMARUDDIN', '170525-02-7891', 'P', 'aktif'),
(121, 2, 'VISHNU A/L GANESAN', '170630-01-1123', 'L', 'aktif'),
(122, 2, 'NURUL SYUHADA BINTI AZIZ', '170715-02-3345', 'P', 'aktif'),
(123, 2, 'MUHAMMAD IRFAN BIN ZULKIFLI', '170820-01-5567', 'L', 'aktif'),
(124, 2, 'FARAH DIYANA BINTI FAUZI', '170925-02-7789', 'P', 'aktif'),
(125, 2, 'KAVIN A/L RAJENDRAN', '171030-01-9901', 'L', 'aktif'),
(126, 2, 'NUR ATIKAH BINTI SHAARI', '171105-02-1123', 'P', 'aktif'),
(127, 2, 'MUHAMMAD AMIRUL BIN AZMAN', '171210-01-3345', 'L', 'aktif'),
(128, 2, 'SITI NABILAH BINTI ROSLAN', '171225-02-5567', 'P', 'aktif'),
(129, 2, 'AHMAD ZAKWAN BIN ZAINAL', '171230-01-7789', 'L', 'aktif'),
(130, 2, 'NUR AMIRA BINTI ISMAIL', '171231-02-9901', 'P', 'aktif'),

-- KELAS 1 GAMMA (ID: 3) - 15 Pelajar
(131, 3, 'NURUL FATIN BINTI ZAINI', '170120-02-1237', 'P', 'aktif'),
(132, 3, 'MUHAMMAD HAZIM BIN ZAKARIA', '170225-01-5681', 'L', 'aktif'),
(133, 3, 'SITI AISYAH BINTI ABDULLAH', '170320-02-9015', 'P', 'aktif'),
(134, 3, 'AHMAD FARIS BIN KAMAL', '170425-01-3458', 'L', 'aktif'),
(135, 3, 'NUR IZZATI BINTI ISMAIL', '170530-02-7892', 'P', 'aktif'),
(136, 3, 'DINESH A/L KUMAR', '170630-01-1124', 'L', 'aktif'),
(137, 3, 'NURUL ATHIRAH BINTI MOHD', '170715-02-3346', 'P', 'aktif'),
(138, 3, 'MUHAMMAD AZRI BIN ROSLI', '170820-01-5568', 'L', 'aktif'),
(139, 3, 'SITI NADIAH BINTI YAHYA', '170925-02-7790', 'P', 'aktif'),
(140, 3, 'GOPAL A/L RAMASAMY', '171030-01-9902', 'L', 'aktif'),
(141, 3, 'NUR SYAHIRA BINTI ZAINAL', '171105-02-1124', 'P', 'aktif'),
(142, 3, 'MUHAMMAD AMIN BIN HASSAN', '171210-01-3346', 'L', 'aktif'),
(143, 3, 'NURUL HIDAYAH BINTI AZIZ', '171225-02-5568', 'P', 'aktif'),
(144, 3, 'AHMAD SYAFIQ BIN ZULKIFLI', '171230-01-7790', 'L', 'aktif'),
(145, 3, 'SITI HAJAR BINTI RAHIM', '171231-02-9902', 'P', 'aktif');

-- ============================================

-- 3. TABEL ADMIN
INSERT INTO admin (id, email, nama, katalaluan, tarikh_cipta, status) VALUES
(201, 'admin@skrp.edu.my', 'ENCIK ROSLI BIN AHMAD', '$2y$10$skrp2026abc123', '2025-01-01', 'aktif'),
(202, 'pentadbiran@skrp.edu.my', 'PUAN ZURAIDAH BINTI YUSOF', '$2y$10$admin2026def456', '2025-02-01', 'aktif');

-- ============================================

-- 4. TABEL GURU
INSERT INTO guru (id, nama, email, no_telefon, password, status) VALUES
(301, 'CIK AMINAH BINTI ABDULLAH', 'aminah@skrp.edu.my', '013-4567890', '$2y$10$guru2026Aminah', 'aktif'),
(302, 'ENCIK RAHIM BIN MAT', 'rahim@skrp.edu.my', '014-5678901', '$2y$10$guru2026Rahim', 'aktif'),
(303, 'PUAN NOR HIDAYAH BINTI OTHMAN', 'norhidayah@skrp.edu.my', '019-8765432', '$2y$10$guru2026Norhidayah', 'aktif'),
(304, 'ENCIK MANIAM A/L KUMAR', 'maniam@skrp.edu.my', '016-5432109', '$2y$10$guru2026Maniam', 'aktif'),
(305, 'PUAN SARAH BINTI ISMAIL', 'sarah@skrp.edu.my', '017-6543210', '$2y$10$guru2026Sarah', 'aktif'),
(306, 'ENCIK AZIZ BIN HASSAN', 'aziz@skrp.edu.my', '018-7654321', '$2y$10$guru2026Aziz', 'aktif');

-- ============================================

-- 5. TABEL MATAPELAJARAN (Sesuai Permintaan)
INSERT INTO matapelajaran (id, nod, nama, tahun, status) VALUES
(401, 'BM', 'BAHASA MELAYU', 2026, 'aktif'),
(402, 'BI', 'BAHASA INGGERIS', 2026, 'aktif'),
(403, 'MAT', 'MATEMATIK', 2026, 'aktif'),
(404, 'SNS', 'SAINS', 2026, 'aktif'),
(405, 'SJH', 'SEJARAH', 2026, 'aktif'),
(406, 'PI', 'PENDIDIKAN ISLAM', 2026, 'aktif'),
(407, 'PSV', 'PENDIDIKAN SENI VISUAL', 2026, 'aktif'),
(408, 'PM', 'PENDIDIKAN MUZIK', 2026, 'aktif'),
(409, 'BA', 'BAHASA ARAB', 2026, 'aktif'),
(410, 'PJK', 'PENDIDIKAN JASMANI DAN PENDIDIKAN KESIHATAN', 2026, 'aktif'),
(411, 'RBT', 'REKA BENTUK DAN TEKNOLOGI', 2026, 'aktif'),
(412, 'TMK', 'TEKNOLOGI MAKLUMAT DAN KOMUNIKASI', 2026, 'aktif');

-- ============================================

-- 6. TABEL PENGAJAR
INSERT INTO pengajar (id, id_kelas, id_guru, id_matapelajaran, tahun_akademik, status) VALUES
-- Kelas 1 ALPHA
(501, 1, 301, 401, '2025/2026', 'aktif'),  -- Bahasa Melayu
(502, 1, 301, 402, '2025/2026', 'aktif'),  -- Bahasa Inggeris
(503, 1, 302, 403, '2025/2026', 'aktif'),  -- Matematik
(504, 1, 303, 404, '2025/2026', 'aktif'),  -- Sains
(505, 1, 304, 410, '2025/2026', 'aktif'),  -- PJK
(506, 1, 305, 407, '2025/2026', 'aktif'),  -- PSV
(507, 1, 306, 408, '2025/2026', 'aktif'),  -- PM

-- Kelas 1 BETA
(508, 2, 301, 401, '2025/2026', 'aktif'),
(509, 2, 301, 402, '2025/2026', 'aktif'),
(510, 2, 302, 403, '2025/2026', 'aktif'),
(511, 2, 303, 404, '2025/2026', 'aktif'),
(512, 2, 304, 410, '2025/2026', 'aktif'),

-- Kelas 1 GAMMA
(513, 3, 301, 401, '2025/2026', 'aktif'),
(514, 3, 301, 402, '2025/2026', 'aktif'),
(515, 3, 302, 403, '2025/2026', 'aktif'),
(516, 3, 303, 404, '2025/2026', 'aktif'),
(517, 3, 304, 410, '2025/2026', 'aktif'),

-- Kelas 2 ALPHA
(518, 4, 301, 401, '2025/2026', 'aktif'),
(519, 4, 301, 402, '2025/2026', 'aktif'),
(520, 4, 302, 403, '2025/2026', 'aktif'),
(521, 4, 303, 404, '2025/2026', 'aktif'),
(522, 4, 304, 410, '2025/2026', 'aktif');

-- ============================================

-- 7. TABEL PEPERIKSAAN (SATU PEPERIKSAAN UTAMA untuk semua mata pelajaran)
INSERT INTO peperiksaan (id, id_matapelajaran, tahun_akademik, nama_peperiksaan, tarikh_mula, tarikh_tamat, tarikh_cipta, jenis, status) VALUES
-- PEPERIKSAAN AKHIR TAHUN 2026 (semua mata pelajaran dalam satu peperiksaan)
(601, NULL, '2025/2026', 'PEPERIKSAAN AKHIR TAHUN 2026', '2026-10-20', '2026-10-28', '2026-06-01', 'bertulis', 'aktif');

-- ============================================

-- 8. TABEL MARKAH (untuk Peperiksaan Akhir Tahun 2026)
INSERT INTO markah (id, id_pelajar, id_peperiksaan, markah, gred, kod, catatan, nama, tarikh_cipta, tahun, tarikh_kemaskini, status) VALUES
-- Pelajar 101 (Ali) - Peperiksaan Akhir Tahun
(701, 101, 601, 88, 'A', 'BM-101', 'Cemerlang', 'ALI BIN AHMAD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(702, 101, 601, 85, 'A', 'BI-101', 'Cemerlang', 'ALI BIN AHMAD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(703, 101, 601, 92, 'A+', 'MAT-101', 'Cemerlang Tertinggi', 'ALI BIN AHMAD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(704, 101, 601, 80, 'A-', 'SNS-101', 'Baik', 'ALI BIN AHMAD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(705, 101, 601, 78, 'B+', 'PJK-101', 'Memuaskan', 'ALI BIN AHMAD', '2026-10-29', 2026, '2026-10-29', 'aktif'),

-- Pelajar 102 (Siti) - Peperiksaan Akhir Tahun
(706, 102, 601, 76, 'B', 'BM-102', 'Memuaskan', 'SITI BINTI MOHD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(707, 102, 601, 82, 'A-', 'BI-102', 'Baik', 'SITI BINTI MOHD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(708, 102, 601, 88, 'A', 'MAT-102', 'Cemerlang', 'SITI BINTI MOHD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(709, 102, 601, 90, 'A', 'SNS-102', 'Cemerlang', 'SITI BINTI MOHD', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(710, 102, 601, 75, 'B', 'PJK-102', 'Memuaskan', 'SITI BINTI MOHD', '2026-10-29', 2026, '2026-10-29', 'aktif'),

-- Pelajar 103 (Amir) - Peperiksaan Akhir Tahun
(711, 103, 601, 95, 'A+', 'BM-103', 'Cemerlang Tertinggi', 'AMIR BIN KAMAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(712, 103, 601, 92, 'A+', 'BI-103', 'Cemerlang Tertinggi', 'AMIR BIN KAMAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(713, 103, 601, 98, 'A+', 'MAT-103', 'Cemerlang Tertinggi', 'AMIR BIN KAMAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(714, 103, 601, 94, 'A+', 'SNS-103', 'Cemerlang Tertinggi', 'AMIR BIN KAMAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(715, 103, 601, 89, 'A', 'PJK-103', 'Cemerlang', 'AMIR BIN KAMAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),

-- Pelajar 104 (Fatimah) - Peperiksaan Akhir Tahun
(716, 104, 601, 82, 'A-', 'BM-104', 'Baik', 'FATIMAH BINTI ZAINAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(717, 104, 601, 79, 'B+', 'BI-104', 'Memuaskan', 'FATIMAH BINTI ZAINAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(718, 104, 601, 85, 'A', 'MAT-104', 'Cemerlang', 'FATIMAH BINTI ZAINAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(719, 104, 601, 80, 'A-', 'SNS-104', 'Baik', 'FATIMAH BINTI ZAINAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(720, 104, 601, 76, 'B', 'PJK-104', 'Memuaskan', 'FATIMAH BINTI ZAINAL', '2026-10-29', 2026, '2026-10-29', 'aktif'),

-- Pelajar 105 (Kumar) - Peperiksaan Akhir Tahun
(721, 105, 601, 70, 'B-', 'BM-105', 'Memuaskan', 'KUMAR A/L RAJ', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(722, 105, 601, 68, 'C+', 'BI-105', 'Memuaskan', 'KUMAR A/L RAJ', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(723, 105, 601, 75, 'B', 'MAT-105', 'Memuaskan', 'KUMAR A/L RAJ', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(724, 105, 601, 72, 'B-', 'SNS-105', 'Memuaskan', 'KUMAR A/L RAJ', '2026-10-29', 2026, '2026-10-29', 'aktif'),
(725, 105, 601, 80, 'A-', 'PJK-105', 'Baik', 'KUMAR A/L RAJ', '2026-10-29', 2026, '2026-10-29', 'aktif');

-- ============================================
-- AKHIR SQL INSERT STATEMENTS
-- ============================================