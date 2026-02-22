<?php
// proses-semak-keputusan.php — VERSI MUKTAMAD
// Sesuai dengan struktur DB slipku_db yang sebenar
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

unset($_SESSION['result_data']);
unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: form_student_gred.php");
    exit();
}

// ============================================================
// SAMBUNGAN DATABASE — cari connect.php di mana-mana lokasi
// ============================================================
$paths = [
    __DIR__ . '/../config/connect.php',
    __DIR__ . '/config/connect.php',
    __DIR__ . '/../config/database.php',
    __DIR__ . '/config/database.php',
    dirname(__DIR__) . '/config/connect.php',
];
$loaded = false;
foreach ($paths as $p) {
    if (file_exists($p)) { require_once $p; $loaded = true; break; }
}
if (!$loaded) die("ERROR: Fail config/connect.php tidak dijumpai.");
if (!isset($conn) || !$conn) die("ERROR: Sambungan DB gagal. Semak config/connect.php");

// ============================================================
// AMBIL & BERSIHKAN INPUT
// ============================================================
$nama       = strtoupper(trim($_POST['nama']     ?? ''));
$no_kp      = trim($_POST['no_kp']               ?? '');
$kelas_form = strtoupper(trim($_POST['kelas']    ?? ''));
$jenis_exam = trim($_POST['examType']            ?? '');

if (!$nama || !$no_kp || !$kelas_form || !$jenis_exam) {
    header("Location: form_student_gred.php?error=" . urlencode("Sila isi semua maklumat."));
    exit();
}

$nama_e      = mysqli_real_escape_string($conn, $nama);
$no_kp_clean = mysqli_real_escape_string($conn, str_replace('-', '', $no_kp));
$kelas_e     = mysqli_real_escape_string($conn, $kelas_form);
$jenis_e     = mysqli_real_escape_string($conn, $jenis_exam);

// ============================================================
// STEP 1 — CARI PELAJAR (case-insensitive, IC tanpa dash)
// ============================================================
$sql = "SELECT p.id, p.nama, p.no_kp, p.jantina, p.id_kelas, k.nama AS nama_kelas
        FROM pelajar p
        LEFT JOIN kelas k ON p.id_kelas = k.id
        WHERE UPPER(p.nama) = '$nama_e'
          AND REPLACE(p.no_kp, '-', '') = '$no_kp_clean'
          AND p.status = 'aktif'
        LIMIT 1";

$r = mysqli_query($conn, $sql);
if (!$r || mysqli_num_rows($r) === 0) {
    // Cuba tanpa semak status
    $sql = "SELECT p.id, p.nama, p.no_kp, p.jantina, p.id_kelas, k.nama AS nama_kelas
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE UPPER(p.nama) = '$nama_e'
              AND REPLACE(p.no_kp, '-', '') = '$no_kp_clean'
            LIMIT 1";
    $r = mysqli_query($conn, $sql);
}

if (!$r || mysqli_num_rows($r) === 0) {
    header("Location: form_student_gred.php?error=" . urlencode("Rekod pelajar tidak dijumpai. Semak nama dan no. kad pengenalan."));
    exit();
}
$pelajar    = mysqli_fetch_assoc($r);
$id_pelajar = (int)$pelajar['id'];
$kelas_db   = strtoupper(trim($pelajar['nama_kelas'] ?? ''));

// ============================================================
// STEP 2 — SEMAK KELAS
// ============================================================
if ($kelas_db && $kelas_db !== $kelas_form) {
    header("Location: form_student_gred.php?error=" . urlencode("Kelas tidak sepadan. Kelas pelajar: $kelas_db"));
    exit();
}

// ============================================================
// STEP 3 — CARI PEPERIKSAAN
// Dalam DB: peperiksaan id=1 ada jenis='bertulis', status='aktif'
// Form hantar examType='bertulis'
// ============================================================
$sql = "SELECT id, nama_peperiksaan, jenis, tahun_akademik, tarikh_mula, tarikh_tamat
        FROM peperiksaan
        WHERE jenis = '$jenis_e' AND status = 'aktif'
        ORDER BY id DESC LIMIT 1";
$r = mysqli_query($conn, $sql);

// Jika tak jumpa ikut jenis, ambil yg terbaru dengan nama mengandungi keyword
if (!$r || mysqli_num_rows($r) === 0) {
    $sql = "SELECT id, nama_peperiksaan, jenis, tahun_akademik, tarikh_mula, tarikh_tamat
            FROM peperiksaan WHERE status = 'aktif' ORDER BY id DESC LIMIT 1";
    $r = mysqli_query($conn, $sql);
}
if (!$r || mysqli_num_rows($r) === 0) {
    $sql = "SELECT id, nama_peperiksaan, jenis, tahun_akademik, tarikh_mula, tarikh_tamat
            FROM peperiksaan ORDER BY id DESC LIMIT 1";
    $r = mysqli_query($conn, $sql);
}
if (!$r || mysqli_num_rows($r) === 0) {
    header("Location: form_student_gred.php?error=" . urlencode("Tiada rekod peperiksaan dalam sistem."));
    exit();
}
$peperiksaan    = mysqli_fetch_assoc($r);
$id_peperiksaan = (int)$peperiksaan['id'];

// ============================================================
// STEP 4 — DETECT STRUKTUR TABLE MARKAH & AMBIL MARKAH
// DB lama: tiada id_matapelajaran
// DB baru (markah.sql): ada id_matapelajaran
// ============================================================

// Detect column id_matapelajaran
$col_mp  = mysqli_query($conn, "SHOW COLUMNS FROM markah LIKE 'id_matapelajaran'");
$has_mp  = ($col_mp && mysqli_num_rows($col_mp) > 0);

// Detect nama column peperiksaan (id_perperiksaan atau id_peperiksaan)
$col_pp1 = mysqli_query($conn, "SHOW COLUMNS FROM markah LIKE 'id_perperiksaan'");
$col_pp  = ($col_pp1 && mysqli_num_rows($col_pp1) > 0) ? 'id_perperiksaan' : 'id_peperiksaan';

if ($has_mp) {
    // STRUKTUR BARU — ada id_matapelajaran
    $sql = "SELECT m.markah, m.gred, m.catatan,
                   mp.nama AS nama_mp, mp.kod AS kod_mp
            FROM markah m
            LEFT JOIN matapelajaran mp ON m.id_matapelajaran = mp.id
            WHERE m.id_pelajar = $id_pelajar
              AND m.$col_pp   = $id_peperiksaan
            ORDER BY mp.kod ASC";
} else {
    // STRUKTUR LAMA — tiada id_matapelajaran
    $sql = "SELECT m.markah, m.gred, m.catatan,
                   '' AS nama_mp, '' AS kod_mp
            FROM markah m
            WHERE m.id_pelajar = $id_pelajar
              AND m.$col_pp   = $id_peperiksaan
            ORDER BY m.id ASC";
}

$r = mysqli_query($conn, $sql);
if (!$r) {
    header("Location: form_student_gred.php?error=" . urlencode("Ralat sistem: " . mysqli_error($conn)));
    exit();
}

// Kalau tiada markah untuk peperiksaan ini, cuba semua markah pelajar
if (mysqli_num_rows($r) === 0) {
    if ($has_mp) {
        $sql = "SELECT m.markah, m.gred, m.catatan,
                       mp.nama AS nama_mp, mp.kod AS kod_mp
                FROM markah m
                LEFT JOIN matapelajaran mp ON m.id_matapelajaran = mp.id
                WHERE m.id_pelajar = $id_pelajar
                ORDER BY mp.kod ASC";
    } else {
        $sql = "SELECT m.markah, m.gred, m.catatan,
                       '' AS nama_mp, '' AS kod_mp
                FROM markah m WHERE m.id_pelajar = $id_pelajar
                ORDER BY m.id ASC";
    }
    $r = mysqli_query($conn, $sql);
}

if (!$r || mysqli_num_rows($r) === 0) {
    header("Location: form_student_gred.php?error=" . urlencode("Tiada rekod markah ditemui untuk pelajar ini."));
    exit();
}

// ============================================================
// STEP 5 — SUSUN DATA MARKAH
// ============================================================
$grade_points = [
    'A+'=>4.0,'A'=>4.0,'A-'=>3.67,
    'B+'=>3.33,'B'=>3.0,'B-'=>2.67,
    'C+'=>2.33,'C'=>2.0,'C-'=>1.67,
    'D'=>1.0,'E'=>0.5,'F'=>0.0,
];
$mp_fallback = [
    'BM'=>'BAHASA MELAYU','BI'=>'BAHASA INGGERIS','MAT'=>'MATEMATIK',
    'SNS'=>'SAINS','SJH'=>'SEJARAH','PI'=>'PENDIDIKAN ISLAM',
    'PSV'=>'PENDIDIKAN SENI VISUAL','PM'=>'PENDIDIKAN MUZIK',
    'BA'=>'BAHASA ARAB','PJK'=>'PENDIDIKAN JASMANI DAN KESIHATAN',
    'RBT'=>'REKA BENTUK DAN TEKNOLOGI','TMK'=>'TEKNOLOGI MAKLUMAT KOMUNIKASI',
];

$subjects = []; $total_gpa = 0; $total_markah = 0;
$lulus = 0; $gagal = 0; $idx = 1;

while ($row = mysqli_fetch_assoc($r)) {
    $gred   = strtoupper(trim($row['gred'] ?? 'F'));
    $point  = $grade_points[$gred] ?? 0.0;
    $markah = (int)($row['markah'] ?? 0);
    $total_gpa    += $point;
    $total_markah += $markah;
    if ($point >= 1.0) $lulus++; else $gagal++;

    $kod_mp  = strtoupper(trim($row['kod_mp'] ?? ''));
    $nama_mp = strtoupper(trim($row['nama_mp'] ?? ''));
    if (empty($nama_mp)) $nama_mp = $mp_fallback[$kod_mp] ?? "MATA PELAJARAN $idx";
    if (empty($kod_mp))  $kod_mp  = 'MP'.str_pad($idx,2,'0',STR_PAD_LEFT);

    // Status markah
    if ($markah>=90) $status='Cemerlang';
    elseif ($markah>=80) $status='Kepujian';
    elseif ($markah>=70) $status='Baik';
    elseif ($markah>=60) $status='Memuaskan';
    elseif ($markah>=50) $status='Lulus';
    else $status='Gagal';
    $catatan = !empty($row['catatan']) ? $row['catatan'] : $status;

    $subjects[] = [
        'matapelajaran' => ['nama'=>$nama_mp, 'kod'=>$kod_mp],
        'markah'        => ['markah'=>$markah,'gred'=>$gred,'catatan'=>$catatan,'point'=>$point],
    ];
    $idx++;
}
$total_subjects = count($subjects);
$avg_gpa    = $total_subjects > 0 ? $total_gpa / $total_subjects : 0;
$avg_markah = $total_subjects > 0 ? round($total_markah / $total_subjects, 1) : 0;

// ============================================================
// STEP 6 — KIRA GRED PURATA & KEDUDUKAN
// ============================================================
function calculateAverageGrade(float $gpa): string {
    if ($gpa >= 3.85) return 'A+';
    if ($gpa >= 3.67) return 'A';
    if ($gpa >= 3.33) return 'A-';
    if ($gpa >= 3.00) return 'B+';
    if ($gpa >= 2.67) return 'B';
    if ($gpa >= 2.33) return 'B-';
    if ($gpa >= 2.00) return 'C+';
    if ($gpa >= 1.67) return 'C';
    if ($gpa >= 1.00) return 'D';
    if ($gpa >= 0.50) return 'E';
    return 'F';
}

function getStudentRank($conn, int $sid, int $eid, int $cid, string $col_pp): int|string {
    if (!$cid || !$eid) return 'N/A';
    $sql = "SELECT p.id,
                   AVG(CASE
                     WHEN m.gred='A+' THEN 4.0 WHEN m.gred='A'  THEN 4.0
                     WHEN m.gred='A-' THEN 3.67 WHEN m.gred='B+' THEN 3.33
                     WHEN m.gred='B'  THEN 3.0  WHEN m.gred='B-' THEN 2.67
                     WHEN m.gred='C+' THEN 2.33 WHEN m.gred='C'  THEN 2.0
                     WHEN m.gred='C-' THEN 1.67 WHEN m.gred='D'  THEN 1.0
                     WHEN m.gred='E'  THEN 0.5  ELSE 0
                   END) AS avg_gpa
            FROM pelajar p
            INNER JOIN markah m ON p.id = m.id_pelajar
            WHERE p.id_kelas = $cid AND m.$col_pp = $eid
            GROUP BY p.id ORDER BY avg_gpa DESC";
    $res = mysqli_query($conn, $sql);
    if (!$res) return 'N/A';
    $rank = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        if ((int)$row['id'] === $sid) return $rank;
        $rank++;
    }
    return 'N/A';
}

$average_grade = calculateAverageGrade($avg_gpa);
$rank          = getStudentRank($conn, $id_pelajar, $id_peperiksaan, (int)($pelajar['id_kelas']??0), $col_pp);

// ============================================================
// STEP 7 — SIMPAN SESSION & REDIRECT
// ============================================================
$_SESSION['result_data'] = [
    'student' => [
        'id'         => $pelajar['id'],
        'nama'       => $pelajar['nama'],
        'no_kp'      => $pelajar['no_kp'],
        'nama_kelas' => $kelas_db ?: $kelas_form,
        'id_kelas'   => $pelajar['id_kelas'],
        'jantina'    => $pelajar['jantina'] ?? '',
    ],
    'exam' => [
        'id'               => $peperiksaan['id'],
        'nama_peperiksaan' => $peperiksaan['nama_peperiksaan'],
        'jenis'            => $peperiksaan['jenis'],
        'tahun_akademik'   => $peperiksaan['tahun_akademik'] ?? '2025/2026',
        'tarikh_mula'      => $peperiksaan['tarikh_mula'] ?? '',
        'tarikh_tamat'     => $peperiksaan['tarikh_tamat'] ?? '',
    ],
    'subjects' => $subjects,
    'summary'  => [
        'total_subjects' => $total_subjects,
        'average_grade'  => $average_grade,
        'gpa'            => round($avg_gpa, 2),
        'avg_markah'     => $avg_markah,
        'total_points'   => round($total_gpa, 2),
        'lulus'          => $lulus,
        'gagal'          => $gagal,
        'rank'           => $rank,
    ],
    'generated_at' => date('d/m/Y H:i:s'),
];

mysqli_close($conn);
header("Location: result_gred.php");
exit();
?>