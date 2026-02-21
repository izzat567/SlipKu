<?php
// includes/db_functions.php - FIXED VERSION

function connectDB() {
    $host = 'localhost';
    $username = 'root';
    $password = 'danialdev';
    $database = 'slipku_db';
    $conn = mysqli_connect($host, $username, $password, $database);
    if (!$conn) die("Connection failed: " . mysqli_connect_error());
    mysqli_set_charset($conn, 'utf8mb4');
    return $conn;
}

// Get global $conn (from connect.php) - jangan buat connection baru
function getConn() {
    global $conn;
    return $conn;
}

function checkGuruLogin() {
    if (!isset($_SESSION['guru_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: ../login-guru.php');
        exit();
    }
}

function getGuruById($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    // DB baru: column 'id', status varchar 'aktif'
    $stmt = $conn->prepare("SELECT id, nama, email, no_telefon FROM guru WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $guru = $result->fetch_assoc();
    $stmt->close();
    return $guru ?: false;
}

function getGuruInfo($guru_id) {
    return getGuruById($guru_id);
}

// Get subjects taught by guru
function getSubjectsByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    // DB baru: pengajar.id_guru, matapelajaran.status = 'aktif'
    $sql = "SELECT DISTINCT m.id, m.nama, m.kod, m.tahun
            FROM matapelajaran m
            JOIN pengajar p ON m.id = p.id_matapelajaran
            WHERE p.id_guru = ? AND p.status = 'aktif' AND m.status = 'aktif'
            ORDER BY m.nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // Fallback: semua subjek aktif
        return getAllSubjects();
    }
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subjects = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    if (empty($subjects)) return getAllSubjects();
    return $subjects;
}
    return $subjects;
}

function getAllSubjects() {
    global $conn;
    $sql = "SELECT id, nama, kod, tahun FROM matapelajaran WHERE status = 'aktif' ORDER BY nama ASC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getSubjectById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT * FROM matapelajaran WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// Get all exams
function getAllExams() {
    global $conn;
    $sql = "SELECT * FROM peperiksaan WHERE status = 'aktif' ORDER BY tarikh_mula DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Get exams by guru (via matapelajaran yang diajar)
function getExamsByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    // Cuba cari peperiksaan berkaitan subjek guru ini
    $sql = "SELECT DISTINCT p.* FROM peperiksaan p
            JOIN pengajar pj ON p.id_matapelajaran = pj.id_matapelajaran
            WHERE pj.id_guru = ? AND pj.status = 'aktif' AND p.status = 'aktif'
            ORDER BY p.tarikh_mula DESC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exams = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        if (!empty($exams)) return $exams;
    }
    // Fallback: return semua peperiksaan aktif (jika id_matapelajaran NULL atau tiada match)
    return getAllExams();
}

function getExamById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT * FROM peperiksaan WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// Get classes by guru
function getKelasByGuru($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    
    // Kaedah 1: dari table pengajar
    $sql = "SELECT DISTINCT k.id, k.nama, k.tahun FROM kelas k
            JOIN pengajar p ON k.id = p.id_kelas
            WHERE p.id_guru = ? AND p.status = 'aktif' AND k.status = 'aktif'
            ORDER BY k.tahun DESC, k.nama ASC";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $classes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        if (!empty($classes)) return $classes;
    }
    
    // Kaedah 2: dari kelas.id_guru terus
    $sql2 = "SELECT id, nama, tahun FROM kelas WHERE id_guru = ? AND status = 'aktif' ORDER BY tahun DESC, nama ASC";
    $stmt2 = $conn->prepare($sql2);
    if ($stmt2) {
        $stmt2->bind_param("i", $guru_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $classes2 = $result2->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();
        if (!empty($classes2)) return $classes2;
    }
    
    return [];
}

function getAllKelas() {
    global $conn;
    $sql = "SELECT * FROM kelas WHERE status = 'aktif' ORDER BY tahun DESC, nama ASC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getKelasById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT * FROM kelas WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

// Get students by class ID
function getStudentsByClass($kelas_id) {
    global $conn;
    $kelas_id = intval($kelas_id);
    // pelajar.status boleh jadi 'aktif' atau '1' - cari kedua-dua
    $sql = "SELECT * FROM pelajar WHERE id_kelas = ? AND (status = 'aktif' OR status = '1') ORDER BY nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param("i", $kelas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $students;
}

// Get students by class name
function getStudentsByClassName($class_name) {
    global $conn;
    $sql = "SELECT p.* FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            WHERE k.nama = ? AND (p.status = 'aktif' OR p.status = '1')
            ORDER BY p.nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param("s", $class_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $students;
}

// Get students by guru
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $status = '') {
    global $conn;
    $guru_id = intval($guru_id);
    
    // Dapatkan senarai kelas_id untuk guru ini (dari pengajar DAN kelas.id_guru)
    $kelas_ids = [];
    
    // Dari pengajar
    $stmt_k = $conn->prepare("SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif'");
    if ($stmt_k) {
        $stmt_k->bind_param("i", $guru_id);
        $stmt_k->execute();
        $r = $stmt_k->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_k->close();
        foreach ($r as $row) if ($row['id_kelas']) $kelas_ids[] = $row['id_kelas'];
    }
    
    // Dari kelas.id_guru
    $stmt_k2 = $conn->prepare("SELECT id FROM kelas WHERE id_guru = ? AND status = 'aktif'");
    if ($stmt_k2) {
        $stmt_k2->bind_param("i", $guru_id);
        $stmt_k2->execute();
        $r2 = $stmt_k2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_k2->close();
        foreach ($r2 as $row) if ($row['id']) $kelas_ids[] = $row['id'];
    }
    
    $kelas_ids = array_unique(array_filter($kelas_ids));
    
    if (empty($kelas_ids)) return [];
    
    $placeholders = implode(',', array_fill(0, count($kelas_ids), '?'));
    $sql = "SELECT DISTINCT p.*, k.nama as kelas_nama, k.tahun
            FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            WHERE p.id_kelas IN ($placeholders)
            AND (p.status = 'aktif' OR p.status = '1')";
    
    $params = $kelas_ids;
    $types = str_repeat("i", count($kelas_ids));
    
    if (!empty($search)) {
        $search_like = "%$search%";
        $sql .= " AND (p.nama LIKE ? OR p.no_kp LIKE ?)";
        $params[] = $search_like;
        $params[] = $search_like;
        $types .= "ss";
    }
    if (!empty($kelas)) {
        $sql .= " AND k.nama = ?";
        $params[] = $kelas;
        $types .= "s";
    }
    
    $sql .= " ORDER BY p.nama ASC";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $students;
}

function getPelajarById($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("SELECT * FROM pelajar WHERE id = ? LIMIT 1");
    if (!$stmt) return false;
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

function tambahPelajar($data) {
    global $conn;
    $nama = $data['nama'];
    $no_ic = $data['no_ic'];
    $jantina = $data['jantina'];
    $id_kelas = !empty($data['id_kelas']) ? intval($data['id_kelas']) : null;
    $status = $data['status'] ?? 'aktif';
    
    $stmt = $conn->prepare("INSERT INTO pelajar (nama, no_kp, jantina, id_kelas, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama, $no_ic, $jantina, $id_kelas, $status);
    return $stmt->execute();
}

function kemaskiniPelajar($id, $data) {
    global $conn;
    $id = intval($id);
    $nama = $data['nama'];
    $no_ic = $data['no_ic'];
    $jantina = $data['jantina'];
    $id_kelas = !empty($data['id_kelas']) ? intval($data['id_kelas']) : null;
    $status = $data['status'] ?? 'aktif';
    
    $stmt = $conn->prepare("UPDATE pelajar SET nama=?, no_kp=?, jantina=?, id_kelas=?, status=? WHERE id=?");
    $stmt->bind_param("sssssl", $nama, $no_ic, $jantina, $id_kelas, $status, $id);
    return $stmt->execute();
}

function padamPelajar($id) {
    global $conn;
    $id = intval($id);
    $stmt = $conn->prepare("DELETE FROM pelajar WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// MARKAH FUNCTIONS - uses id_perperiksaan (correct DB column name)
function addMark($data) {
    global $conn;
    $id_pelajar = intval($data['id_pelajar']);
    // Sokong kedua-dua nama field
    $id_perperiksaan = intval($data['id_perperiksaan'] ?? $data['id_peperiksaan'] ?? 0);
    $markah = intval($data['markah']);
    $gred = !empty($data['gred']) ? $data['gred'] : calculateGrade($markah);
    $catatan = $data['catatan'] ?? '';
    $today = date('Y-m-d');
    
    if (!$id_pelajar || !$id_perperiksaan) {
        return ['success' => false, 'message' => 'ID pelajar atau peperiksaan tidak sah'];
    }
    
    // Check if exists
    $check = $conn->prepare("SELECT id FROM markah WHERE id_pelajar=? AND id_perperiksaan=?");
    if (!$check) return ['success' => false, 'message' => 'Ralat prepare: ' . $conn->error];
    $check->bind_param("ii", $id_pelajar, $id_perperiksaan);
    $check->execute();
    $exists = $check->get_result()->num_rows > 0;
    $check->close();
    
    if ($exists) {
        $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, catatan=?, tarikh_kemaskini=?, status='aktif' WHERE id_pelajar=? AND id_perperiksaan=?");
        if (!$stmt) return ['success' => false, 'message' => 'Ralat prepare update: ' . $conn->error];
        $stmt->bind_param("isssii", $markah, $gred, $catatan, $today, $id_pelajar, $id_perperiksaan);
    } else {
        // 7 placeholder + 'aktif' hardcoded = betul
        $stmt = $conn->prepare("INSERT INTO markah (id_pelajar, id_perperiksaan, markah, gred, catatan, tarikh_cipta, tarikh_kemaskini, status) VALUES (?,?,?,?,?,?,?,'aktif')");
        if (!$stmt) return ['success' => false, 'message' => 'Ralat prepare insert: ' . $conn->error];
        $stmt->bind_param("iiissss", $id_pelajar, $id_perperiksaan, $markah, $gred, $catatan, $today, $today);
    }
    
    if ($stmt->execute()) {
        $stmt->close();
        return ['success' => true, 'message' => 'Markah berjaya disimpan'];
    }
    $err = $conn->error;
    $stmt->close();
    return ['success' => false, 'message' => 'Ralat: ' . $err];
}

function addMultipleMarks($marks_data) {
    $success_count = 0;
    $errors = [];
    foreach ($marks_data as $data) {
        $result = addMark($data);
        if ($result['success']) $success_count++;
        else $errors[] = $result['message'];
    }
    return [
        'success' => $success_count > 0,
        'message' => "$success_count markah berjaya disimpan",
        'errors' => $errors
    ];
}

function getMarksByExam($exam_id) {
    global $conn;
    $exam_id = intval($exam_id);
    $sql = "SELECT m.*, p.nama as nama_pelajar, p.no_kp 
            FROM markah m
            JOIN pelajar p ON m.id_pelajar = p.id
            WHERE m.id_perperiksaan = ? AND m.status = 'aktif'
            ORDER BY p.nama ASC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) return [];
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $marks = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $marks;
}

function calculateGrade($mark, $full_marks = 100) {
    if ($mark === null || $mark === '') return '';
    $percentage = ($full_marks > 0) ? ($mark / $full_marks) * 100 : 0;
    if ($percentage >= 90) return 'A+';
    if ($percentage >= 80) return 'A';
    if ($percentage >= 70) return 'B';
    if ($percentage >= 60) return 'C';
    if ($percentage >= 50) return 'D';
    if ($percentage >= 40) return 'E';
    return 'F';
}

function getStatistikPelajar($guru_id) {
    global $conn;
    $guru_id = intval($guru_id);
    
    // Kira pelajar dari kelas yang diajar guru (via pengajar DAN kelas.id_guru)
    $total = 0;
    
    $sql = "SELECT COUNT(DISTINCT p.id) as total FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            WHERE k.status = 'aktif' AND (p.status = 'aktif' OR p.status = '1')
            AND (
                k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                OR k.id_guru = ?
            )";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $guru_id, $guru_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $total = $row['total'] ?? 0;
        $stmt->close();
    }
    return ['total_pelajar' => $total, 'pelajar_aktif' => $total];
}

function checkStudentExists($no_ic, $exclude_id = null) {
    global $conn;
    $sql = "SELECT id FROM pelajar WHERE no_kp = ?";
    if ($exclude_id) $sql .= " AND id != " . intval($exclude_id);
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $no_ic);
    $stmt->execute();
    $count = $stmt->get_result()->num_rows;
    $stmt->close();
    return $count > 0;
}

function ensureDemoGuruExists() {
    global $conn;
    // Demo guru already in DB, skip
}
ensureDemoGuruExists();
?>
