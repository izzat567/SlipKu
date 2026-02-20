<?php
// includes/db_functions.php

// ============================================
// DATABASE CONNECTION
// ============================================
function connectDB() {
    $host = 'localhost';
    $username = 'root';
    $password = 'danialdev';
    $database = 'slipku_db';
    
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}

$conn = connectDB();

// ============================================
// GURU AUTHENTICATION & FUNCTIONS
// ============================================

/**
 * Authenticate guru login
 */
function authenticateGuru($email, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT `id`, `nama`, `email`, `password`, `status` FROM `guru` WHERE `email` = ?");
    
    if (!$stmt) {
        error_log("MySQL Prepare Error: " . $conn->error);
        return false;
    }
    
    $stmt->bind_param("s", $email);
    
    if (!$stmt->execute()) {
        error_log("MySQL Execute Error: " . $stmt->error);
        return false;
    }
    
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        error_log("User found: " . print_r($row, true));
        
        if ($password === $row['password']) {
            if ($row['status'] == 'aktif' || $row['status'] == '1') {
                return [
                    'guru_id' => $row['id'],
                    'guru_nama' => $row['nama'],
                    'guru_email' => $row['email'],
                    'guru_role' => 'guru',
                    'status' => $row['status']
                ];
            } else {
                error_log("User status not active: " . $row['status']);
            }
        } else {
            error_log("Password mismatch for: " . $email);
        }
    } else {
        error_log("No user found with email: " . $email);
    }
    
    return false;
}

/**
 * Check if guru is logged in
 */
function checkGuruLogin() {
    if (!isset($_SESSION['guru_id'])) {
        header('Location: login-guru.php');
        exit();
    }
}

/**
 * Get guru by ID
 */
function getGuruById($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    $sql = "SELECT * FROM guru WHERE id = '$guru_id' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $guru = mysqli_fetch_assoc($result);
        
        return [
            'id' => $guru['id'],
            'nama' => $guru['nama'],
            'email' => $guru['email'],
            'no_telefon' => $guru['no_telefon'] ?? ''
        ];
    }
    
    return false;
}

/**
 * Get guru info by ID (alias for getGuruById)
 */
function getGuruInfo($guru_id) {
    return getGuruById($guru_id);
}

// ============================================
// SUBJEK (SUBJECT) FUNCTIONS
// ============================================

/**
 * Get all subjects
 */
function getAllSubjects() {
    global $conn;
    
    $sql = "SELECT * FROM matapelajaran WHERE status = 1 ORDER BY nama ASC";
    $result = mysqli_query($conn, $sql);
    $subjects = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row;
        }
    }
    
    return $subjects;
}

/**
 * Get subjects by guru
 */
function getSubjectsByGuru($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    $sql = "SELECT m.* FROM matapelajaran m
            JOIN pengajar p ON m.id = p.id_matapelajaran
            WHERE p.id_guru = '$guru_id' AND m.status = 1
            ORDER BY m.nama ASC";
    
    $result = mysqli_query($conn, $sql);
    $subjects = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row;
        }
    }
    
    return $subjects;
}

/**
 * Get subject by ID
 */
function getSubjectById($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM matapelajaran WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// ============================================
// PEPERIKSAAN (EXAM) FUNCTIONS
// ============================================

/**
 * Get all exams - VERSION DENGAN LEFT JOIN
 */
function getAllExams() {
    global $conn;
    
    $sql = "SELECT peperiksaan.*, 
                   COALESCE(matapelajaran.nama, 'Tiada Subjek') as nama_matapelajaran, 
                   COALESCE(kelas.nama, 'Tiada Kelas') as nama_kelas 
            FROM peperiksaan 
            LEFT JOIN matapelajaran ON peperiksaan.id_matapelajaran = matapelajaran.id
            LEFT JOIN kelas ON peperiksaan.id_kelas = kelas.id
            WHERE peperiksaan.status = 1 OR peperiksaan.status = 'aktif'
            ORDER BY peperiksaan.tarikh_mula DESC";
    
    $result = mysqli_query($conn, $sql);
    $exams = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $exams[] = $row;
        }
    }
    
    return $exams;
}

/**
 * Get exams by guru - VERSION YANG DAH DIBETULKAN UNTUK STRUKTUR DB ANDA
 */
function getExamsByGuru($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    // Version ringkas - ambil semua peperiksaan tanpa filter guru dulu
    $sql = "SELECT peperiksaan.*, 
                   COALESCE(matapelajaran.nama, 'Matematik') as nama_matapelajaran, 
                   COALESCE(kelas.nama, '1 ALPHA') as nama_kelas 
            FROM peperiksaan 
            LEFT JOIN matapelajaran ON peperiksaan.id_matapelajaran = matapelajaran.id
            LEFT JOIN kelas ON peperiksaan.id_kelas = kelas.id
            WHERE peperiksaan.status = 1 OR peperiksaan.status = 'aktif'
            ORDER BY peperiksaan.tarikh_mula DESC";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        error_log("SQL Error in getExamsByGuru: " . mysqli_error($conn));
        return [];
    }
    
    $exams = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $exams[] = $row;
        }
    }
    
    return $exams;
}

/**
 * Get exam by ID
 */
function getExamById($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT peperiksaan.*, 
                   COALESCE(matapelajaran.nama, 'Tiada Subjek') as nama_matapelajaran, 
                   COALESCE(kelas.nama, 'Tiada Kelas') as nama_kelas 
            FROM peperiksaan 
            LEFT JOIN matapelajaran ON peperiksaan.id_matapelajaran = matapelajaran.id
            LEFT JOIN kelas ON peperiksaan.id_kelas = kelas.id
            WHERE peperiksaan.id = '$id' LIMIT 1";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// ============================================
// MARKAH (MARKS) FUNCTIONS
// ============================================

/**
 * Add single mark
 */
function addMark($data) {
    global $conn;
    
    $id_pelajar = mysqli_real_escape_string($conn, $data['id_pelajar']);
    $id_peperiksaan = mysqli_real_escape_string($conn, $data['id_peperiksaan']);
    $markah = mysqli_real_escape_string($conn, $data['markah']);
    $gred = mysqli_real_escape_string($conn, $data['gred'] ?? '');
    $catatan = mysqli_real_escape_string($conn, $data['catatan'] ?? '');
    
    // Check if mark already exists
    $check_sql = "SELECT id FROM markah WHERE id_pelajar = '$id_pelajar' AND id_peperiksaan = '$id_peperiksaan'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // Update existing mark
        $sql = "UPDATE markah 
                SET markah = '$markah', gred = '$gred', catatan = '$catatan', updated_at = NOW() 
                WHERE id_pelajar = '$id_pelajar' AND id_peperiksaan = '$id_peperiksaan'";
    } else {
        // Insert new mark
        $sql = "INSERT INTO markah (id_pelajar, id_peperiksaan, markah, gred, catatan, status) 
                VALUES ('$id_pelajar', '$id_peperiksaan', '$markah', '$gred', '$catatan', 1)";
    }
    
    if (mysqli_query($conn, $sql)) {
        return ['success' => true, 'message' => 'Markah berjaya disimpan'];
    } else {
        return ['success' => false, 'message' => 'Ralat: ' . mysqli_error($conn)];
    }
}

/**
 * Add multiple marks
 */
function addMultipleMarks($marks_data) {
    global $conn;
    
    $success_count = 0;
    $errors = [];
    
    foreach ($marks_data as $data) {
        $result = addMark($data);
        if ($result['success']) {
            $success_count++;
        } else {
            $errors[] = $result['message'];
        }
    }
    
    if ($success_count > 0) {
        return [
            'success' => true, 
            'message' => "$success_count markah berjaya disimpan",
            'errors' => $errors
        ];
    } else {
        return [
            'success' => false, 
            'message' => 'Tiada markah berjaya disimpan',
            'errors' => $errors
        ];
    }
}

/**
 * Get marks by exam
 */
function getMarksByExam($exam_id) {
    global $conn;
    
    $exam_id = mysqli_real_escape_string($conn, $exam_id);
    
    $sql = "SELECT m.*, p.nama as nama_pelajar, p.no_kp 
            FROM markah m
            JOIN pelajar p ON m.id_pelajar = p.id
            WHERE m.id_peperiksaan = '$exam_id' AND m.status = 1
            ORDER BY p.nama ASC";
    
    $result = mysqli_query($conn, $sql);
    $marks = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $marks[] = $row;
        }
    }
    
    return $marks;
}

/**
 * Get students by class
 */
function getStudentsByClass($class_name) {
    global $conn;
    
    $class_name = mysqli_real_escape_string($conn, $class_name);
    
    $sql = "SELECT p.* FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            WHERE k.nama = '$class_name' AND p.status = 1
            ORDER BY p.nama ASC";
    
    $result = mysqli_query($conn, $sql);
    $students = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    }
    
    return $students;
}

/**
 * Calculate grade based on marks
 */
function calculateGrade($mark, $full_marks = 100) {
    if ($mark === null || $mark === '') return '';
    
    $percentage = ($mark / $full_marks) * 100;
    
    if ($percentage >= 80) return 'A';
    if ($percentage >= 70) return 'B';
    if ($percentage >= 60) return 'C';
    if ($percentage >= 50) return 'D';
    if ($percentage >= 40) return 'E';
    return 'F';
}

// ============================================
// PELAJAR (STUDENT) FUNCTIONS
// ============================================

/**
 * Get students by guru with filters
 */
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    $sql = "SELECT 
                p.*, 
                k.nama as kelas_nama,
                k.tahun
            FROM pelajar p
            JOIN kelas k ON p.id_kelas = k.id
            JOIN pengajar pj ON k.id = pj.id_kelas
            WHERE pj.id_guru = '$guru_id'";

    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " AND (p.nama LIKE '%$search%' OR p.no_kp LIKE '%$search%')";
    }

    if (!empty($kelas)) {
        $kelas = mysqli_real_escape_string($conn, $kelas);
        $sql .= " AND k.nama = '$kelas'";
    }

    if (!empty($tahun)) {
        $tahun = mysqli_real_escape_string($conn, $tahun);
        $sql .= " AND k.tahun = '$tahun'";
    }

    if (!empty($status)) {
        $status_value = 1;
        if ($status === 'active') $status_value = 1;
        elseif ($status === 'inactive') $status_value = 0;
        elseif ($status === 'graduated') $status_value = 2;
        
        $sql .= " AND p.status = '$status_value'";
    }
    
    $sql .= " ORDER BY p.nama ASC";
    
    $result = mysqli_query($conn, $sql);
    $students = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
    }
    
    return $students;
}

/**
 * Check if student exists by IC number
 */
function checkStudentExists($no_ic, $exclude_id = null) {
    global $conn;
    
    $no_ic = mysqli_real_escape_string($conn, $no_ic);
    $sql = "SELECT id FROM pelajar WHERE no_kp = '$no_ic'";
    
    if ($exclude_id) {
        $exclude_id = mysqli_real_escape_string($conn, $exclude_id);
        $sql .= " AND id != '$exclude_id'";
    }
    
    $result = mysqli_query($conn, $sql);
    return ($result && mysqli_num_rows($result) > 0);
}

/**
 * Add new student
 */
function tambahPelajar($data) {
    global $conn;
    
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_ic = mysqli_real_escape_string($conn, $data['no_ic']);
    $jantina = mysqli_real_escape_string($conn, $data['jantina']);
    $id_kelas = isset($data['id_kelas']) ? mysqli_real_escape_string($conn, $data['id_kelas']) : null;
    $status = isset($data['status']) ? $data['status'] : 'active';

    $status_value = ($status === 'active') ? 1 : (($status === 'inactive') ? 0 : 2);
    
    $sql = "INSERT INTO pelajar (nama, no_kp, jantina, id_kelas, status) 
            VALUES ('$nama', '$no_ic', '$jantina', " . ($id_kelas ? "'$id_kelas'" : "NULL") . ", '$status_value')";
    
    return mysqli_query($conn, $sql);
}

/**
 * Update student
 */
function kemaskiniPelajar($id, $data) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_ic = mysqli_real_escape_string($conn, $data['no_ic']);
    $jantina = mysqli_real_escape_string($conn, $data['jantina']);
    $id_kelas = isset($data['id_kelas']) ? mysqli_real_escape_string($conn, $data['id_kelas']) : null;
    $status = isset($data['status']) ? $data['status'] : 'active';

    $status_value = ($status === 'active') ? 1 : (($status === 'inactive') ? 0 : 2);
    
    $sql = "UPDATE pelajar 
            SET nama = '$nama', 
                no_kp = '$no_ic', 
                jantina = '$jantina', 
                id_kelas = " . ($id_kelas ? "'$id_kelas'" : "NULL") . ",
                status = '$status_value'
            WHERE id = '$id'";
    
    return mysqli_query($conn, $sql);
}

/**
 * Delete student
 */
function padamPelajar($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM pelajar WHERE id = '$id'";
    
    return mysqli_query($conn, $sql);
}

/**
 * Get student by ID
 */
function getPelajarById($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM pelajar WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// ============================================
// KELAS (CLASS) FUNCTIONS
// ============================================

/**
 * Get all classes
 */
function getAllKelas() {
    global $conn;
    
    $sql = "SELECT * FROM kelas WHERE status = 1 ORDER BY tahun DESC, nama ASC";
    $result = mysqli_query($conn, $sql);
    $classes = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $classes[] = $row;
        }
    }
    
    return $classes;
}

/**
 * Get classes by guru
 */
function getKelasByGuru($guru_id) {
    global $conn;

    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    $sql = "SELECT k.* FROM kelas k
            JOIN pengajar p ON k.id = p.id_kelas
            WHERE p.id_guru = '$guru_id' AND k.status = 1
            ORDER BY k.tahun DESC, k.nama ASC";
    
    $result = mysqli_query($conn, $sql);
    $classes = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $classes[] = $row;
        }
    }
    
    return $classes;
}

/**
 * Get class by ID
 */
function getKelasById($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM kelas WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// ============================================
// STATISTICS & REPORTS
// ============================================

/**
 * Get student statistics
 */
function getStatistikPelajar($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    // Total students dalam kelas yang diajar oleh guru ini
    $sql_total = "SELECT COUNT(DISTINCT p.id) as total 
                  FROM pelajar p
                  JOIN kelas k ON p.id_kelas = k.id
                  JOIN pengajar pj ON k.id = pj.id_kelas
                  WHERE pj.id_guru = '$guru_id'";
    
    $result_total = mysqli_query($conn, $sql_total);
    $total = 0;
    
    if ($result_total) {
        $row = mysqli_fetch_assoc($result_total);
        $total = $row['total'] ?? 0;
    }
    
    // Active students
    $sql_active = "SELECT COUNT(DISTINCT p.id) as active 
                   FROM pelajar p
                   JOIN kelas k ON p.id_kelas = k.id
                   JOIN pengajar pj ON k.id = pj.id_kelas
                   WHERE pj.id_guru = '$guru_id' AND p.status = 1";
    
    $result_active = mysqli_query($conn, $sql_active);
    $active = 0;
    
    if ($result_active) {
        $row = mysqli_fetch_assoc($result_active);
        $active = $row['active'] ?? 0;
    }
    
    return [
        'total_pelajar' => $total,
        'pelajar_aktif' => $active,
        'prestasi_purata' => 78.5,
        'kadar_kehadiran' => 92.3
    ];
}

/**
 * Bulk update students
 */
function bulkUpdateStudents($student_ids, $data) {
    global $conn;
    
    if (empty($student_ids)) return false;
    
    $ids = array_map(function($id) use ($conn) {
        return mysqli_real_escape_string($conn, $id);
    }, $student_ids);
    
    $ids_str = "'" . implode("','", $ids) . "'";

    $updates = [];
    foreach ($data as $key => $value) {
        $key = mysqli_real_escape_string($conn, $key);
        $value = mysqli_real_escape_string($conn, $value);
        
        if ($key === 'status') {
            $value = ($value === 'active') ? 1 : (($value === 'inactive') ? 0 : 2);
        }
        
        $updates[] = "$key = '$value'";
    }
    
    if (empty($updates)) return false;
    
    $updates_str = implode(', ', $updates);
    $sql = "UPDATE pelajar SET $updates_str WHERE id IN ($ids_str)";
    
    return mysqli_query($conn, $sql);
}

/**
 * Get student performance (demo data)
 */
function getStudentPerformance($student_id) {
    return [
        'average' => rand(60, 95),
        'subject_scores' => [
            ['subject' => 'Matematik', 'score' => rand(60, 95)],
            ['subject' => 'Sains', 'score' => rand(60, 95)],
            ['subject' => 'Bahasa Melayu', 'score' => rand(60, 95)]
        ]
    ];
}

/**
 * Get student attendance (demo data)
 */
function getStudentAttendance($student_id) {
    return [
        'percentage' => rand(85, 100),
        'present' => rand(15, 20),
        'absent' => rand(0, 5),
        'total' => 20
    ];
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Close database connection
 */
function closeDB($conn) {
    mysqli_close($conn);
}

/**
 * Ensure demo guru exists (for demo purposes)
 */
function ensureDemoGuruExists() {
    global $conn;
    
    $check_sql = "SELECT * FROM guru WHERE email = 'guru@demo.com'";
    $result = mysqli_query($conn, $check_sql);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        $insert_sql = "INSERT INTO guru (nama, email, no_telefon, password, status) 
                      VALUES ('Cikgu Demo', 'guru@demo.com', '012-3456789', 'demo123', 1)";
        mysqli_query($conn, $insert_sql);
    }
}

// Ensure demo guru exists when this file is loaded
ensureDemoGuruExists();

?>