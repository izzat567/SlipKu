-- ============================================================
-- DATA REAL SLIPKU - SEKOLAH KEBANGSAAN RANTAU PANJANG
-- Sesi Akademik 2026
-- Jalankan fail ini dalam phpMyAdmin atau MySQL CLI
-- ============================================================

SET NAMES utf8mb4;
SET SQL_MODE = '';

-- ============================================================
-- 1. BERSIHKAN DATA LAMA (jika perlu rerun)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `markah`;
TRUNCATE TABLE `guru_kelas`;
DELETE FROM `kelas`;
DELETE FROM `pelajar`;
DELETE FROM `guru`;
DELETE FROM `peperiksaan`;
SET FOREIGN_KEY_CHECKS = 1;

ALTER TABLE `kelas`       AUTO_INCREMENT = 1;
ALTER TABLE `pelajar`     AUTO_INCREMENT = 1;
ALTER TABLE `guru`        AUTO_INCREMENT = 1;
ALTER TABLE `peperiksaan` AUTO_INCREMENT = 1;

-- ============================================================
-- 2. TABLE KELAS (Darjah 1-6, 3 Kelas Setiap Darjah)
-- ============================================================
INSERT INTO `kelas` (`id`, `nama`, `tahun`, `status`) VALUES
-- Darjah 1
(1,  '1 ALPHA',  1, 1),
(2,  '1 BETA',   1, 1),
(3,  '1 GAMMA',  1, 1),
-- Darjah 2
(4,  '2 ALPHA',  2, 1),
(5,  '2 BETA',   2, 1),
(6,  '2 GAMMA',  2, 1),
-- Darjah 3
(7,  '3 ALPHA',  3, 1),
(8,  '3 BETA',   3, 1),
(9,  '3 GAMMA',  3, 1),
-- Darjah 4
(10, '4 ALPHA',  4, 1),
(11, '4 BETA',   4, 1),
(12, '4 GAMMA',  4, 1),
-- Darjah 5
(13, '5 ALPHA',  5, 1),
(14, '5 BETA',   5, 1),
(15, '5 GAMMA',  5, 1),
-- Darjah 6
(16, '6 ALPHA',  6, 1),
(17, '6 BETA',   6, 1),
(18, '6 GAMMA',  6, 1);

-- ============================================================
-- 3. TABLE GURU (6 Guru)
-- Password: 'password123' (bcrypt hash)
-- ============================================================
INSERT INTO `guru` (`id_guru`, `nama`, `email`, `no_telefon`, `password`, `status`) VALUES
(1, 'AHMAD RIZAL BIN HASSAN',    'ahmad.rizal@skrp.edu.my',    '0123456789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(2, 'SITI AMINAH BINTI YUSOF',   'siti.aminah@skrp.edu.my',    '0134567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(3, 'MOHD FADZLI BIN ZAKARIA',   'mohd.fadzli@skrp.edu.my',    '0145678901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(4, 'NOR HASLINDA BINTI KAMAL',  'nor.haslinda@skrp.edu.my',   '0156789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(5, 'ZULKIFLI BIN IBRAHIM',      'zulkifli@skrp.edu.my',       '0167890123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(6, 'FARIDAH BINTI OTHMAN',      'faridah@skrp.edu.my',        '0178901234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- ============================================================
-- 4. TABLE GURU_KELAS (Penugasan Guru ke Kelas)
--    Guru 1 (Ahmad Rizal): Darjah 1 ALPHA, 2 ALPHA, 3 ALPHA
--    Guru 2 (Siti Aminah): Darjah 1 BETA,  2 BETA,  3 BETA
--    Guru 3 (Mohd Fadzli): Darjah 1 GAMMA, 2 GAMMA, 3 GAMMA
--    Guru 4 (Nor Haslinda): Darjah 4 ALPHA, 5 ALPHA, 6 ALPHA
--    Guru 5 (Zulkifli): Darjah 4 BETA, 5 BETA, 6 BETA
--    Guru 6 (Faridah): Darjah 4 GAMMA, 5 GAMMA, 6 GAMMA
-- ============================================================
INSERT INTO `guru_kelas` (`guru_id`, `kelas_id`, `tahun`, `status`) VALUES
-- Guru 1
(1, 1,  2026, 1), (1, 4,  2026, 1), (1, 7,  2026, 1),
-- Guru 2
(2, 2,  2026, 1), (2, 5,  2026, 1), (2, 8,  2026, 1),
-- Guru 3
(3, 3,  2026, 1), (3, 6,  2026, 1), (3, 9,  2026, 1),
-- Guru 4
(4, 10, 2026, 1), (4, 13, 2026, 1), (4, 16, 2026, 1),
-- Guru 5
(5, 11, 2026, 1), (5, 14, 2026, 1), (5, 17, 2026, 1),
-- Guru 6
(6, 12, 2026, 1), (6, 15, 2026, 1), (6, 18, 2026, 1);

-- ============================================================
-- 5. PELAJAR (10 pelajar setiap kelas, 18 kelas = 180 pelajar)
-- ============================================================

-- KELAS 1 ALPHA (id_kelas=1)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(1,'ALI BIN AHMAD','170101-11-0001','L',1),(1,'SITI BINTI MOHD','170202-12-0002','P',1),
(1,'AMIR BIN KAMAL','170303-13-0003','L',1),(1,'NURUL BINTI ZAINAL','170404-14-0004','P',1),
(1,'KUMAR A/L RAJ','170505-15-0005','L',1),(1,'MEI LING','170606-16-0006','P',1),
(1,'HAFIZ BIN ISMAIL','170707-17-0007','L',1),(1,'HIDAYAH BINTI ALI','170808-18-0008','P',1),
(1,'RAJESH A/L KUMAR','170909-19-0009','L',1),(1,'NURUL AIN BINTI HASSAN','171010-11-0010','P',1);

-- KELAS 1 BETA (id_kelas=2)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(2,'NUR SYAFIQAH BINTI AZMI','170115-12-0011','P',1),(2,'MUHAMMAD ADAM BIN YUSOF','170220-13-0012','L',1),
(2,'AISYAH BINTI ROSLI','170325-14-0013','P',1),(2,'AHMAD DANIAL BIN HAMZAH','170430-15-0014','L',1),
(2,'SITI HAJAR BINTI KAMARUDDIN','170505-16-0015','P',1),(2,'VISHNU A/L GANESAN','170610-17-0016','L',1),
(2,'NURUL SYUHADA BINTI AZIZ','170715-18-0017','P',1),(2,'MUHAMMAD IRFAN BIN ZULKIFLI','170820-19-0018','L',1),
(2,'FARAH DIYANA BINTI FAUZI','170905-11-0019','P',1),(2,'KAVIN A/L RAJENDRAN','171010-12-0020','L',1);

-- KELAS 1 GAMMA (id_kelas=3)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(3,'NURUL FATIN BINTI ZAINI','170120-13-0021','P',1),(3,'MUHAMMAD HAZIM BIN ZAKARIA','170225-14-0022','L',1),
(3,'SITI AISYAH BINTI ABDULLAH','170320-15-0023','P',1),(3,'AHMAD FARIS BIN KAMAL','170425-16-0024','L',1),
(3,'NUR IZZATI BINTI ISMAIL','170530-17-0025','P',1),(3,'DINESH A/L KUMAR','170610-18-0026','L',1),
(3,'NURUL ATHIRAH BINTI MOHD','170715-19-0027','P',1),(3,'MUHAMMAD AZRI BIN ROSLI','170820-11-0028','L',1),
(3,'SITI NADIAH BINTI YAHYA','170925-12-0029','P',1),(3,'GOPAL A/L RAMASAMY','171010-13-0030','L',1);

-- KELAS 2 ALPHA (id_kelas=4)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(4,'MOHD NAZRI BIN SULAIMAN','160101-11-0031','L',1),(4,'PUTRI BALQIS BINTI RAMLI','160202-12-0032','P',1),
(4,'FARHAN BIN SHAHRIL','160303-13-0033','L',1),(4,'NUR INSYIRAH BINTI ZULKIFLI','160404-14-0034','P',1),
(4,'HARISH A/L VIJAYAN','160505-15-0035','L',1),(4,'YAP WEN QI','160606-16-0036','P',1),
(4,'IZZATUL IMAN BINTI ARIFFIN','160707-17-0037','P',1),(4,'SYAZWAN BIN NORDIN','160808-18-0038','L',1),
(4,'LAVANYA A/P SURESH','160909-19-0039','P',1),(4,'DANISH AIMAN BIN AZLAN','161010-11-0040','L',1);

-- KELAS 2 BETA (id_kelas=5)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(5,'AMIRAH BINTI HARUN','160115-12-0041','P',1),(5,'AZLAN SHAH BIN OTHMAN','160220-13-0042','L',1),
(5,'SITI NORZAHARA BINTI YAHYA','160325-14-0043','P',1),(5,'MOHAMAD IZZWAN BIN SALLEH','160430-15-0044','L',1),
(5,'PRIYA A/P KRISHNAN','160515-16-0045','P',1),(5,'LING MING HUI','160620-17-0046','P',1),
(5,'HAZIQ FIKRI BIN HALIM','160725-18-0047','L',1),(5,'NUR ZULAIKHA BINTI BAKAR','160830-19-0048','P',1),
(5,'FAIZUL ANWAR BIN ADNAN','160925-11-0049','L',1),(5,'NURHANIS BINTI AZIZ','161030-12-0050','P',1);

-- KELAS 2 GAMMA (id_kelas=6)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(6,'ZAFRAN BIN ZUHAIRI','160120-13-0051','L',1),(6,'NABILAH BINTI NOR','160225-14-0052','P',1),
(6,'ARIF HAKIMI BIN RASHID','160320-15-0053','L',1),(6,'SITI HUMAIRAH BINTI ROSLAN','160425-16-0054','P',1),
(6,'TANESH A/L SUBRAMANIAM','160530-17-0055','L',1),(6,'TEE SZE YI','160610-18-0056','P',1),
(6,'IMAN HUSNA BINTI IDRIS','160715-19-0057','P',1),(6,'MUKHRIZ BIN MANSOR','160820-11-0058','L',1),
(6,'SANGEETHA A/P RAJAN','160925-12-0059','P',1),(6,'NAIM SYAHMI BIN ZAHARI','161010-13-0060','L',1);

-- KELAS 3 ALPHA (id_kelas=7)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(7,'RIDHWAN BIN ROSLI','150101-11-0061','L',1),(7,'IRDINA BINTI JAMALUDIN','150202-12-0062','P',1),
(7,'LUTFI HAKIM BIN ZAINOL','150303-13-0063','L',1),(7,'SITI AQILAH BINTI MANSOR','150404-14-0064','P',1),
(7,'SURYEN A/L KRISHNA','150505-15-0065','L',1),(7,'ANGELINE CHEW','150606-16-0066','P',1),
(7,'HAZWANI BINTI HALIM','150707-17-0067','P',1),(7,'AFIQ DANISH BIN ARIF','150808-18-0068','L',1),
(7,'RUBINI A/P RAMU','150909-19-0069','P',1),(7,'HAIKAL BIN HAMZAH','151010-11-0070','L',1);

-- KELAS 3 BETA (id_kelas=8)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(8,'SOFEA BINTI AZMAN','150115-12-0071','P',1),(8,'AQIF SYAMIL BIN AZRUL','150220-13-0072','L',1),
(8,'HANIS SYAHEERA BINTI RAZIF','150325-14-0073','P',1),(8,'DANISH HARRAZ BIN IKHWAN','150430-15-0074','L',1),
(8,'NITHYA A/P BALAKRISHNAN','150515-16-0075','P',1),(8,'LEE JIA XIN','150620-17-0076','P',1),
(8,'ALIFF AIMAN BIN SHAMSUL','150725-18-0077','L',1),(8,'ADIBAH BINTI ZULKARNAIN','150830-19-0078','P',1),
(8,'FARUQ BIN FADZIL','150925-11-0079','L',1),(8,'MAISARAH BINTI MOKHTAR','151030-12-0080','P',1);

-- KELAS 3 GAMMA (id_kelas=9)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(9,'IZZUDDIN BIN SAHARUDDIN','150120-13-0081','L',1),(9,'AFIQAH BINTI ZAILANI','150225-14-0082','P',1),
(9,'MIKHAIL BIN MUSTAFA','150320-15-0083','L',1),(9,'NADHIRAH BINTI RASHIDI','150425-16-0084','P',1),
(9,'MUGESH A/L MURUGESAN','150530-17-0085','L',1),(9,'CHUA YAN YI','150610-18-0086','P',1),
(9,'FARA AMEERA BINTI AZHAR','150715-19-0087','P',1),(9,'LUQMANUL HAKIM BIN LATIF','150820-11-0088','L',1),
(9,'KIRTHIKA A/P ANAND','150925-12-0089','P',1),(9,'FADZLAN BIN FARIS','151010-13-0090','L',1);

-- KELAS 4 ALPHA (id_kelas=10)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(10,'AIMAN HAKIMI BIN ANUAR','140101-11-0091','L',1),(10,'SYAHIRAH BINTI SAPUAN','140202-12-0092','P',1),
(10,'AIDIL FITRI BIN RAMLAN','140303-13-0093','L',1),(10,'SYAZWANI BINTI SHAMSUDIN','140404-14-0094','P',1),
(10,'SURESH A/L PERUMAL','140505-15-0095','L',1),(10,'OOI LI YING','140606-16-0096','P',1),
(10,'WARDAH BINTI WAHAB','140707-17-0097','P',1),(10,'FARIQ NAUFAL BIN FAKRUDIN','140808-18-0098','L',1),
(10,'AMEESHA A/P DAVID','140909-19-0099','P',1),(10,'ZHARIFF IMAN BIN ZAIDI','141010-11-0100','L',1);

-- KELAS 4 BETA (id_kelas=11)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(11,'NAZIRUL IMAN BIN NASIRUDDIN','140115-12-0101','L',1),(11,'AFRINA BINTI AMINUDDIN','140220-13-0102','P',1),
(11,'HADIF HAFIY BIN HASRUL','140325-14-0103','L',1),(11,'TASNIM BINTI TALIB','140430-15-0104','P',1),
(11,'THARMAN A/L THIRUMAN','140515-16-0105','L',1),(11,'WONG QI LING','140620-17-0106','P',1),
(11,'UMAIRAH BINTI UTHMAN','140725-18-0107','P',1),(11,'QUSYAIRI BIN QABIL','140830-19-0108','L',1),
(11,'ARULNITHII A/P ARULNIDHI','140925-11-0109','P',1),(11,'ZIKRI HANIF BIN ZAMRI','141030-12-0110','L',1);

-- KELAS 4 GAMMA (id_kelas=12)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(12,'QASEH INSYIRAH BINTI QADRI','140120-13-0111','P',1),(12,'FADHLI ASYRAF BIN FUAD','140225-14-0112','L',1),
(12,'NABILAH HUSNA BINTI NASIR','140320-15-0113','P',1),(12,'ERAAS AIMAN BIN AHMAD','140425-16-0114','L',1),
(12,'MALARVILI A/P MANIAM','140530-17-0115','P',1),(12,'TAN JUN WEI','140610-18-0116','L',1),
(12,'HANA MAISARA BINTI HANIF','140715-19-0117','P',1),(12,'NASRULLAH BIN NASRI','140820-11-0118','L',1),
(12,'SHANKARI A/P SELVAM','140925-12-0119','P',1),(12,'ARIFF SUFFIAN BIN ARUZMI','141010-13-0120','L',1);

-- KELAS 5 ALPHA (id_kelas=13)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(13,'MUAZ IZZUDDIN BIN MUKHTAR','130101-11-0121','L',1),(13,'HANA INSYIRAH BINTI HARUN','130202-12-0122','P',1),
(13,'SYAFIQ AMMAR BIN SHUIB','130303-13-0123','L',1),(13,'INTAN SYAZWANI BINTI IDRUS','130404-14-0124','P',1),
(13,'PRADEEP A/L PILLAI','130505-15-0125','L',1),(13,'GOH XIAN YING','130606-16-0126','P',1),
(13,'FARHANA BINTI FADILAH','130707-17-0127','P',1),(13,'HAZWAN BIN HAFIZI','130808-18-0128','L',1),
(13,'REVATHI A/P RAGAVENDRAN','130909-19-0129','P',1),(13,'HAZRIL NIZAM BIN HAMDAN','131010-11-0130','L',1);

-- KELAS 5 BETA (id_kelas=14)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(14,'NABILAH NAZURAH BINTI NAZRI','130115-12-0131','P',1),(14,'IZZAT HAFIY BIN IZHAM','130220-13-0132','L',1),
(14,'KHADIJAH BINTI KHAIRUL','130325-14-0133','P',1),(14,'SAFWAN HAKIMI BIN SAMSURI','130430-15-0134','L',1),
(14,'VIMALAN A/L VIMALRAJ','130515-16-0135','L',1),(14,'LIOW SHU WEN','130620-17-0136','P',1),
(14,'NADIA IRDINA BINTI NAZRIN','130725-18-0137','P',1),(14,'FAWWAZ BIN FAIRUZ','130830-19-0138','L',1),
(14,'SASHIKALA A/P SANTHANAM','130925-11-0139','P',1),(14,'FADZRUL AMIN BIN FAUZAN','131030-12-0140','L',1);

-- KELAS 5 GAMMA (id_kelas=15)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(15,'ZARA IZZATI BINTI ZAHIR','130120-13-0141','P',1),(15,'IZZUL HAKIM BIN IZZUDIN','130225-14-0142','L',1),
(15,'SULASTRI BINTI SULAIMAN','130320-15-0143','P',1),(15,'AMIRUL ASHRAF BIN ASRI','130425-16-0144','L',1),
(15,'NALINI A/P NAVARATNAM','130530-17-0145','P',1),(15,'CHAN WEI XIAN','130610-18-0146','L',1),
(15,'MARYAM BINTI MAZLAN','130715-19-0147','P',1),(15,'AFNAN HAKIM BIN AFFANDI','130820-11-0148','L',1),
(15,'HEMAVATHI A/P HARIKRISHNAN','130925-12-0149','P',1),(15,'ZAKWAN IMRAN BIN ZAINUDDIN','131010-13-0150','L',1);

-- KELAS 6 ALPHA (id_kelas=16)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(16,'ARIFF AMSYAR BIN AZMAN','120101-11-0151','L',1),(16,'IRDINA WARDAH BINTI IBRAHIM','120202-12-0152','P',1),
(16,'IMRAN HAKIMI BIN IRWAN','120303-13-0153','L',1),(16,'NUR ADILAH BINTI ADNAN','120404-14-0154','P',1),
(16,'SUGUMAR A/L SUPPAIAH','120505-15-0155','L',1),(16,'LIM YAN CHI','120606-16-0156','P',1),
(16,'KHAIRUN NISA BINTI KHAIRUL','120707-17-0157','P',1),(16,'HAZIQ FARHAN BIN HALIL','120808-18-0158','L',1),
(16,'MATHANGI A/P MUTHUKRISHNAN','120909-19-0159','P',1),(16,'ISYRAF SUFFIAN BIN ISMAIL','121010-11-0160','L',1);

-- KELAS 6 BETA (id_kelas=17)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(17,'SITI MAISARAH BINTI SALIM','120115-12-0161','P',1),(17,'MOHD AMSYAR BIN MOHD NOOR','120220-13-0162','L',1),
(17,'HANIS HUSNA BINTI HAMID','120325-14-0163','P',1),(17,'FAHMI RADZWAN BIN FAKHRUL','120430-15-0164','L',1),
(17,'JANANI A/P JAYAKUMAR','120515-16-0165','P',1),(17,'NG ZI WEI','120620-17-0166','L',1),
(17,'BASYIRAH BINTI BAHAROM','120725-18-0167','P',1),(17,'HAFIZUDDIN BIN HAPIZ','120830-19-0168','L',1),
(17,'YOGESWARI A/P YOHANRAJ','120925-11-0169','P',1),(17,'AQEEL IKHWAN BIN AKMAL','121030-12-0170','L',1);

-- KELAS 6 GAMMA (id_kelas=18)
INSERT INTO `pelajar` (`id_kelas`, `nama`, `no_kp`, `jantina`, `status`) VALUES
(18,'NURUL IZZAH BINTI NAZRI','120120-13-0171','P',1),(18,'AIZAT AQMAL BIN AZIZAN','120225-14-0172','L',1),
(18,'SITI ZULAIKHA BINTI ZAHARI','120320-15-0173','P',1),(18,'IZZWAN HAQIM BIN ISHAK','120425-16-0174','L',1),
(18,'THILAGAM A/P THIRUMALAI','120530-17-0175','P',1),(18,'CHEW JIA HONG','120610-18-0176','L',1),
(18,'AINA SOFEA BINTI ADLI','120715-19-0177','P',1),(18,'ZULHILMI BIN ZULKIFLI','120820-11-0178','L',1),
(18,'SARANYA A/P SARAVANAN','120925-12-0179','P',1),(18,'IZZUL FIKRI BIN IZWAN','121010-13-0180','L',1);

-- ============================================================
-- 6. PEPERIKSAAN
-- ============================================================
INSERT INTO `peperiksaan` (`id`, `id_matapelajaran`, `tahun_akademik`, `nama_peperiksaan`, `tarikh_mula`, `tarikh_tamat`, `jenis`, `status`) VALUES
(1, 1, '2026', 'Ujian Bulanan 1 - Matematik 2026',    '2026-02-10', '2026-02-12', 'ujian',       1),
(2, 2, '2026', 'Ujian Bulanan 1 - Bahasa Melayu 2026','2026-02-13', '2026-02-14', 'ujian',       1),
(3, 3, '2026', 'Ujian Bulanan 1 - Bahasa Inggeris 2026','2026-02-15', '2026-02-15', 'ujian',     1),
(4, 1, '2026', 'Peperiksaan Pertengahan Tahun - Matematik 2026', '2026-05-04', '2026-05-06', 'pertengahan', 1),
(5, 2, '2026', 'Peperiksaan Pertengahan Tahun - BM 2026',        '2026-05-07', '2026-05-08', 'pertengahan', 1),
(6, 4, '2026', 'Peperiksaan Pertengahan Tahun - Sains 2026',     '2026-05-09', '2026-05-10', 'pertengahan', 1),
(7, 1, '2026', 'Peperiksaan Akhir Tahun - Matematik 2026',       '2026-10-05', '2026-10-07', 'akhir', 1),
(8, 2, '2026', 'Peperiksaan Akhir Tahun - BM 2026',              '2026-10-08', '2026-10-09', 'akhir', 1);

-- ============================================================
-- 7. PENGAJAR (hubungan guru-subjek, diperlukan oleh db_functions.php)
--    Jika table pengajar tidak wujud, create dulu
-- ============================================================
CREATE TABLE IF NOT EXISTS `pengajar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_guru` int(11) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `id_matapelajaran` int(11) NOT NULL,
  `tahun_akademik` varchar(10) DEFAULT '2026',
  `status` varchar(10) DEFAULT 'aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Assign guru ke subjek (berdasarkan kelas yang diajar)
-- Guru 1 (Ahmad Rizal) - Mengajar Matematik & BM untuk kelas 1A, 2A, 3A
INSERT IGNORE INTO `pengajar` (`id_guru`, `id_kelas`, `id_matapelajaran`, `tahun_akademik`, `status`) VALUES
(1,1,1,'2026','aktif'),(1,1,2,'2026','aktif'),(1,4,1,'2026','aktif'),(1,4,2,'2026','aktif'),
(1,7,1,'2026','aktif'),(1,7,2,'2026','aktif'),
-- Guru 2 (Siti Aminah) - Matematik & BM untuk 1B, 2B, 3B
(2,2,1,'2026','aktif'),(2,2,2,'2026','aktif'),(2,5,1,'2026','aktif'),(2,5,2,'2026','aktif'),
(2,8,1,'2026','aktif'),(2,8,2,'2026','aktif'),
-- Guru 3 (Mohd Fadzli) - Matematik & BM untuk 1G, 2G, 3G
(3,3,1,'2026','aktif'),(3,3,2,'2026','aktif'),(3,6,1,'2026','aktif'),(3,6,2,'2026','aktif'),
(3,9,1,'2026','aktif'),(3,9,2,'2026','aktif'),
-- Guru 4 (Nor Haslinda) - Sains & Matematik untuk 4A, 5A, 6A
(4,10,1,'2026','aktif'),(4,10,4,'2026','aktif'),(4,13,1,'2026','aktif'),(4,13,4,'2026','aktif'),
(4,16,1,'2026','aktif'),(4,16,4,'2026','aktif'),
-- Guru 5 (Zulkifli) - Sains & Sejarah untuk 4B, 5B, 6B
(5,11,4,'2026','aktif'),(5,11,6,'2026','aktif'),(5,14,4,'2026','aktif'),(5,14,6,'2026','aktif'),
(5,17,4,'2026','aktif'),(5,17,6,'2026','aktif'),
-- Guru 6 (Faridah) - BM & BI untuk 4G, 5G, 6G
(6,12,2,'2026','aktif'),(6,12,3,'2026','aktif'),(6,15,2,'2026','aktif'),(6,15,3,'2026','aktif'),
(6,18,2,'2026','aktif'),(6,18,3,'2026','aktif');

-- ============================================================
-- 8. MARKAH SAMPEL (untuk pelajar kelas 1 ALPHA, ujian 1)
-- ============================================================
INSERT INTO `markah` (`id_pelajar`, `id_perperiksaan`, `markah`, `gred`, `catatan`, `tarikh_cipta`, `tarikh_kemaskini`, `status`)
SELECT 
    p.id,
    1 AS id_perperiksaan,
    FLOOR(50 + RAND() * 50) AS markah,
    CASE 
        WHEN FLOOR(50 + RAND() * 50) >= 80 THEN 'A'
        WHEN FLOOR(50 + RAND() * 50) >= 70 THEN 'B'
        WHEN FLOOR(50 + RAND() * 50) >= 60 THEN 'C'
        WHEN FLOOR(50 + RAND() * 50) >= 50 THEN 'D'
        ELSE 'E'
    END AS gred,
    NULL,
    '2026-02-12',
    '2026-02-12',
    1
FROM pelajar p
WHERE p.id_kelas = 1;

-- ============================================================
-- SELESAI
-- Login dengan email: ahmad.rizal@skrp.edu.my
-- Password: password123  (hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)
-- ============================================================
