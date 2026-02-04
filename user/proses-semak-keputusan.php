<?php
// proses_semak_keputusan.php
session_start();

// Clear session lama
unset($_SESSION['result_data']);
unset($_SESSION['error']);

// Validasi request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Akses tidak sah!";
    header("Location: form_student_gred.php");
    exit();
}

// MUATKAN CONFIG DATABASE
$config_path = __DIR__ . '/config/database.php';
if (!file_exists($config_path)) {
    $_SESSION['error'] = "Sistem config tidak dijumpai!";
    header("Location: form_student_gred.php");
    exit();
}

require_once $config_path;

// Pastikan $conn wujud
if (!isset($conn) || !$conn) {
    $_SESSION['error'] = "Sambungan database gagal! Sila hubungi pentadbir.";
    header("Location: form_student_gred.php");
    exit();
}

// Debug: Semak sambungan
if (mysqli_connect_errno()) {
    $_SESSION['error'] = "Sambungan database gagal: " . mysqli_connect_error();
    header("Location: form_student_gred.php");
    exit();
}

// Ambil dan sanitize data
$nama = mysqli_real_escape_string($conn, trim($_POST['nama'] ?? ''));
$no_kp = mysqli_real_escape_string($conn, trim($_POST['no_kp'] ?? ''));
$kelas_form = mysqli_real_escape_string($conn, $_POST['kelas'] ?? '');
$id_kelas_form = mysqli_real_escape_string($conn, $_POST['id_kelas'] ?? '');
$jenis_peperiksaan = mysqli_real_escape_string($conn, $_POST['examType'] ?? '');

// Validasi input wajib
if (empty($nama) || empty($no_kp) || empty($kelas_form) || empty($id_kelas_form) || empty($jenis_peperiksaan)) {
    $_SESSION['error'] = "Sila isi semua maklumat yang diperlukan!";
    header("Location: form_student_gred.php");
    exit();
}

// Format IC number (buang dash)
$no_kp_clean = str_replace('-', '', $no_kp);

// Debug log untuk semakan
error_log("Semakan keputusan: Nama=$nama, IC=$no_kp_clean, Kelas=$kelas_form, Tahun=$id_kelas_form, Exam=$jenis_peperiksaan");

// 1. SEMAK PELAJAR DALAM DATABASE
$sql_pelajar = "SELECT p.*, k.nama as nama_kelas, k.tahun 
                FROM pelajar p 
                LEFT JOIN kelas k ON p.id_kelas = k.nama
                WHERE p.nama = '$nama' 
                AND REPLACE(p.no_kp, '-', '') = '$no_kp_clean'
                AND p.status = 1
                LIMIT 1";

$result_pelajar = mysqli_query($conn, $sql_pelajar);

if (!$result_pelajar) {
    $_SESSION['error'] = "Ralat sistem: " . mysqli_error($conn);
    header("Location: form_student_gred.php");
    exit();
}

if (mysqli_num_rows($result_pelajar) == 0) {
    $_SESSION['error'] = "Rekod pelajar tidak dijumpai! Sila pastikan maklumat anda betul.";
    header("Location: form_student_gred.php?error=1");
    exit();
}

$pelajar = mysqli_fetch_assoc($result_pelajar);

// 2. SEMAK KELAS PELAJAR (tambahan validasi)
if ($pelajar['nama_kelas'] != $kelas_form || $pelajar['tahun'] != $id_kelas_form) {
    $_SESSION['error'] = "Maklumat kelas tidak sepadan dengan rekod pelajar!";
    header("Location: form_student_gred.php?error=2");
    exit();
}

// 3. DAPATKAN ID PEPERIKSAAN BERDASARKAN JENIS
$sql_peperiksaan = "SELECT id, nama_peperiksaan 
                    FROM peperiksaan 
                    WHERE jenis = '$jenis_peperiksaan' 
                    AND tahun_akademik LIKE '%2024%'
                    AND status = 1
                    LIMIT 1";

$result_peperiksaan = mysqli_query($conn, $sql_peperiksaan);

if (!$result_peperiksaan) {
    $_SESSION['error'] = "Ralat sistem: " . mysqli_error($conn);
    header("Location: form_student_gred.php");
    exit();
}

if (mysqli_num_rows($result_peperiksaan) == 0) {
    $_SESSION['error'] = "Peperiksaan yang dipilih tidak dijumpai!";
    header("Location: form_student_gred.php?error=3");
    exit();
}

$peperiksaan = mysqli_fetch_assoc($result_peperiksaan);
$id_peperiksaan = $peperiksaan['id'];

// 4. SEMAK MARKAH PELAJAR UNTUK PEPERIKSAAN INI
// Untuk testing, kita akan cipta data dummy jika tiada data dalam table markah
// Dalam production, guna data sebenar

$sql_check_markah = "SELECT COUNT(*) as total FROM markah WHERE id_perperiksaan = $id_peperiksaan LIMIT 1";
$result_check = mysqli_query($conn, $sql_check_markah);
$row_check = mysqli_fetch_assoc($result_check);

if ($row_check['total'] == 0) {
    // Jika tiada data markah dalam sistem, buat data dummy untuk testing
    createDummyMarks($conn, $pelajar['id'], $id_peperiksaan);
}

// Sekarang semak markah pelajar
$sql_markah = "SELECT m.*, mp.kod, mp.nama as nama_matapelajaran
               FROM markah m
               INNER JOIN matapelajaran mp ON m.id_matapelajaran = mp.id
               WHERE m.id_pelajar = {$pelajar['id']}
               AND m.id_perperiksaan = $id_peperiksaan
               AND m.status = 1
               AND mp.status = 1
               ORDER BY mp.kod";

$result_markah = mysqli_query($conn, $sql_markah);

if (!$result_markah) {
    $_SESSION['error'] = "Ralat sistem: " . mysqli_error($conn);
    header("Location: form_student_gred.php");
    exit();
}

if (mysqli_num_rows($result_markah) == 0) {
    $_SESSION['error'] = "Keputusan peperiksaan belum dikeluarkan untuk pelajar ini!";
    header("Location: form_student_gred.php?error=4");
    exit();
}

// 5. KUMPUL DATA KEPUTUSAN
$subjects = [];
$total_gpa = 0;
$total_subjects = mysqli_num_rows($result_markah);

// Sistem gred Malaysia
$grade_points = [
    'A+' => 4.0, 'A' => 4.0, 'A-' => 3.67,
    'B+' => 3.33, 'B' => 3.0, 'B-' => 2.67,
    'C+' => 2.33, 'C' => 2.0, 'C-' => 1.67,
    'D' => 1.0, 'E' => 0.5, 'F' => 0.0
];

while ($row = mysqli_fetch_assoc($result_markah)) {
    $gred = $row['gred'] ?? 'F';
    $point = $grade_points[$gred] ?? 0;
    $total_gpa += $point;
    
    $subjects[] = [
        'matapelajaran' => [
            'nama' => $row['nama_matapelajaran'],
            'kod' => $row['kod']
        ],
        'markah' => [
            'markah' => $row['markah'],
            'gred' => $gred,
            'catatan' => $row['catatan'] ?? 'Tiada catatan',
            'point' => $point
        ]
    ];
}

// 6. HITUNG STATISTIK
$average_gpa = $total_subjects > 0 ? $total_gpa / $total_subjects : 0;
$average_grade = calculateAverageGrade($average_gpa);

// Dapatkan kedudukan dalam kelas
$rank = getClassRank($conn, $pelajar['id'], $id_peperiksaan, $pelajar['id_kelas']);

// 7. SET DATA UNTUK SESSION
$_SESSION['result_data'] = [
    'student' => [
        'id' => $pelajar['id'],
        'nama' => $pelajar['nama'],
        'no_kp' => $pelajar['no_kp'],
        'nama_kelas' => $pelajar['id_kelas'], // Gunakan id_kelas dari table pelajar
        'id_kelas' => $pelajar['tahun'],
        'jantina' => $pelajar['jantina']
    ],
    'exam' => [
        'id' => $peperiksaan['id'],
        'nama_peperiksaan' => $peperiksaan['nama_peperiksaan'],
        'jenis' => $jenis_peperiksaan
    ],
    'subjects' => $subjects,
    'summary' => [
        'total_subjects' => $total_subjects,
        'average_grade' => $average_grade,
        'gpa' => round($average_gpa, 2),
        'total_points' => $total_gpa,
        'rank' => $rank
    ]
];

// 8. REDIRECT KE HALAMAN SLIP
header("Location: result_gred.php");
exit();

// ============ FUNGSI BANTU ============

function calculateAverageGrade($gpa) {
    if ($gpa >= 3.67) return 'A';
    if ($gpa >= 3.33) return 'A-';
    if ($gpa >= 3.00) return 'B+';
    if ($gpa >= 2.67) return 'B';
    if ($gpa >= 2.33) return 'B-';
    if ($gpa >= 2.00) return 'C+';
    if ($gpa >= 1.67) return 'C';
    if ($gpa >= 1.00) return 'C-';
    if ($gpa >= 0.50) return 'D';
    return 'F';
}

function getClassRank($conn, $id_pelajar, $id_peperiksaan, $kelas) {
    // Untuk testing, return dummy rank
    // Dalam production, implementasikan logik sebenar
    $dummy_ranks = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5];
    
    if (isset($dummy_ranks[$id_pelajar])) {
        return $dummy_ranks[$id_pelajar];
    }
    
    return 'N/A';
}

function createDummyMarks($conn, $id_pelajar, $id_peperiksaan) {
    // Data mata pelajaran untuk dummy
    $dummy_subjects = [
        ['id' => 1, 'markah' => 85, 'gred' => 'A', 'catatan' => 'Cemerlang'],
        ['id' => 2, 'markah' => 78, 'gred' => 'B+', 'catatan' => 'Baik'],
        ['id' => 3, 'markah' => 92, 'gred' => 'A+', 'catatan' => 'Sangat Cemerlang'],
        ['id' => 4, 'markah' => 65, 'gred' => 'C+', 'catatan' => 'Memuaskan'],
        ['id' => 5, 'markah' => 88, 'gred' => 'A', 'catatan' => 'Cemerlang'],
        ['id' => 6, 'markah' => 72, 'gred' => 'B-', 'catatan' => 'Baik'],
        ['id' => 7, 'markah' => 95, 'gred' => 'A+', 'catatan' => 'Sangat Cemerlang'],
        ['id' => 8, 'markah' => 55, 'gred' => 'D', 'catatan' => 'Perlu usaha lagi']
    ];
    
    foreach ($dummy_subjects as $subject) {
        $sql = "INSERT INTO markah (id_pelajar, id_perperiksaan, id_matapelajaran, markah, gred, catatan, tarikh_cipta, tarikh_kemaskini, status) 
                VALUES ($id_pelajar, $id_peperiksaan, {$subject['id']}, {$subject['markah']}, '{$subject['gred']}', '{$subject['catatan']}', CURDATE(), CURDATE(), 1)";
        mysqli_query($conn, $sql);
    }
}
?>