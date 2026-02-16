<?php
// includes/db_functions.php

// ============================================
// DATABASE CONNECTION
// ============================================
function connectDB() {
    $host = 'localhost';
    $username = 'root';
    $password = 'danialdev';
    $conn = 'slipku_db';
    
    $conn = mysqli_connect($host, $username, $password, $conn);
    
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
        
        // Semak kata laluan (tanpa hash - berdasarkan data anda)
        if ($password === $row['password']) {
            // Semak status
            if ($row['status'] == 'aktif' || $row['status'] == '1') {
                return [
                    'guru_id' => $row['id'],
                    'guru_nama' => $row['nama'], // Betulkan dari 'name' ke 'nama'
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
 * Get guru by ID - VERSION YANG DAH DIBETULKAN
 */
function getGuruById($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    // GUNA id SAHAJA - column yang BETUL dalam table guru
    $sql = "SELECT * FROM guru WHERE id = '$guru_id' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $guru = mysqli_fetch_assoc($result);
        
        return [
            'guru_id' => $guru['id'],
            'guru_nama' => $guru['nama'],
            'guru_email' => $guru['email'],
            'guru_no_telefon' => $guru['no_telefon'] ?? ''
        ];
    }
    
    return false;
}

// ============================================
// PELAJAR (STUDENT) FUNCTIONS
// ============================================

/**
 * Get students by guru with filters
 */
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    global $conn;
    
    $sql = "SELECT 
                p.*, 
                k.nama as kelas_nama,
                k.tahun
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE 1=1";

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
        if ($status === 'active') $status_value = 1;
        elseif ($status === 'inactive') $status_value = 0;
        elseif ($status === 'graduated') $status_value = 2;
        else $status_value = 1;
        
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
    $status = isset($data['status']) ? $data['status'] : 'active';

    $status_value = ($status === 'active') ? 1 : 
                   (($status === 'inactive') ? 0 : 2);
    
    // Generate student ID
    $last_id_sql = "SELECT MAX(id) as max_id FROM pelajar";
    $last_id_result = mysqli_query($conn, $last_id_sql);
    $last_id = 1;
    
    if ($last_id_result && mysqli_num_rows($last_id_result) > 0) {
        $row = mysqli_fetch_assoc($last_id_result);
        $last_id = $row['max_id'] + 1;
    }
    
    $id_kelas = 'S' . str_pad($last_id, 3, '0', STR_PAD_LEFT) . date('Y');
    
    $sql = "INSERT INTO pelajar (nama, no_kp, jantina, status, id_kelas) 
            VALUES ('$nama', '$no_ic', '$jantina', '$status_value', '$id_kelas')";
    
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
    $status = isset($data['status']) ? $data['status'] : 'active';

    $status_value = ($status === 'active') ? 1 : 
                   (($status === 'inactive') ? 0 : 2);
    
    $sql = "UPDATE pelajar 
            SET nama = '$nama', 
                no_kp = '$no_ic', 
                jantina = '$jantina', 
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
 * Get classes by guru - VERSION YANG DAH DIBETULKAN
 */
function getKelasByGuru($guru_id) {
    global $conn;

    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    // Query dengan JOIN ke table pengajar
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
 * Get student statistics - VERSION YANG DAH DIBETULKAN
 */
function getStatistikPelajar($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    // Total students dalam kelas yang diajar oleh guru ini
    $sql_total = "SELECT COUNT(DISTINCT p.id) as total 
                  FROM pelajar p
                  JOIN pendaftaran_kelas pk ON p.id = pk.id_pelajar
                  JOIN kelas k ON pk.id_kelas = k.id
                  JOIN pengajar pj ON k.id = pj.id_kelas
                  WHERE pj.id_guru = '$guru_id' AND p.status = 1";
    
    $result_total = mysqli_query($conn, $sql_total);
    $total = 0;
    
    if ($result_total && mysqli_num_rows($result_total) > 0) {
        $row = mysqli_fetch_assoc($result_total);
        $total = $row['total'];
    }
    
    // Active students
    $sql_active = "SELECT COUNT(DISTINCT p.id) as active 
                   FROM pelajar p
                   JOIN pendaftaran_kelas pk ON p.id = pk.id_pelajar
                   JOIN kelas k ON pk.id_kelas = k.id
                   JOIN pengajar pj ON k.id = pj.id_kelas
                   WHERE pj.id_guru = '$guru_id' AND p.status = 1";
    
    $result_active = mysqli_query($conn, $sql_active);
    $active = 0;
    
    if ($result_active && mysqli_num_rows($result_active) > 0) {
        $row = mysqli_fetch_assoc($result_active);
        $active = $row['active'];
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
            $value = ($value === 'active') ? 1 : 
                    (($value === 'inactive') ? 0 : 2);
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