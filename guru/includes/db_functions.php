<?php
// includes/db_functions.php

// Database connection
function connectDB() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'slipku_db';
    
    $conn = mysqli_connect($host, $username, $password, $database);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}

$conn = connectDB();

// Authentication function for guru - updated for actual table structure
function authenticateGuru($email, $password) {
    global $conn;
    
    $email = mysqli_real_escape_string($conn, $email);
    
    // Check in guru table - updated column names
    $sql = "SELECT * FROM guru WHERE email = '$email' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $guru = mysqli_fetch_assoc($result);
        
        // Check password (assuming plain text for demo)
        if ($password === $guru['password']) {
            // Return with correct column names
            return [
                'id' => $guru['id_guru'],
                'nama' => $guru['nama'],
                'email' => $guru['email'],
                'no_telefon' => $guru['no_telefon'],
                'role' => 'teacher'
            ];
        }
    }
    
    return false;
}

// Check if guru is logged in
function checkGuruLogin() {
    if (!isset($_SESSION['guru_id'])) {
        header('Location: login-guru.php');
        exit();
    }
}

// Get guru by ID - updated for actual table structure
function getGuruById($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    $sql = "SELECT * FROM guru WHERE id_guru = '$guru_id' AND status = 1 LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $guru = mysqli_fetch_assoc($result);
        return [
            'id' => $guru['id_guru'],
            'nama' => $guru['nama'],
            'email' => $guru['email'],
            'no_telefon' => $guru['no_telefon']
        ];
    }
    
    return false;
}

// Get students by guru
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    global $conn;
    
    $sql = "SELECT 
                p.*, 
                k.nama as kelas_nama,
                k.tahun
            FROM pelajar p
            LEFT JOIN kelas k ON p.id_kelas = k.id
            WHERE 1=1";
    
    // Add search filter
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $sql .= " AND (p.nama LIKE '%$search%' OR p.no_kp LIKE '%$search%')";
    }
    
    // Add kelas filter
    if (!empty($kelas)) {
        $kelas = mysqli_real_escape_string($conn, $kelas);
        $sql .= " AND k.nama = '$kelas'";
    }
    
    // Add tahun filter
    if (!empty($tahun)) {
        $tahun = mysqli_real_escape_string($conn, $tahun);
        $sql .= " AND k.tahun = '$tahun'";
    }
    
    // Add status filter
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

// Function to check if student exists by IC
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

// Function to add student
function tambahPelajar($data) {
    global $conn;
    
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_ic = mysqli_real_escape_string($conn, $data['no_ic']);
    $jantina = mysqli_real_escape_string($conn, $data['jantina']);
    $status = isset($data['status']) ? $data['status'] : 'active';
    
    // Convert status
    $status_value = ($status === 'active') ? 1 : 
                   (($status === 'inactive') ? 0 : 2);
    
    // Generate student ID based on latest ID
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

// Function to update student
function kemaskiniPelajar($id, $data) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $no_ic = mysqli_real_escape_string($conn, $data['no_ic']);
    $jantina = mysqli_real_escape_string($conn, $data['jantina']);
    $status = isset($data['status']) ? $data['status'] : 'active';
    
    // Convert status
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

// Function to delete student
function padamPelajar($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM pelajar WHERE id = '$id'";
    
    return mysqli_query($conn, $sql);
}

// Get all classes
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

// Get classes by guru
function getKelasByGuru($guru_id) {
    global $conn;
    
    // Since there's no direct link, get all active classes
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

// Get student by ID
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

// Get student statistics
function getStatistikPelajar($guru_id) {
    global $conn;
    
    // Get total students
    $sql_total = "SELECT COUNT(*) as total FROM pelajar";
    $result_total = mysqli_query($conn, $sql_total);
    $total = 0;
    
    if ($result_total && mysqli_num_rows($result_total) > 0) {
        $row = mysqli_fetch_assoc($result_total);
        $total = $row['total'];
    }
    
    // Get active students
    $sql_active = "SELECT COUNT(*) as active FROM pelajar WHERE status = 1";
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

// Bulk update students
function bulkUpdateStudents($student_ids, $data) {
    global $conn;
    
    if (empty($student_ids)) return false;
    
    $ids = array_map(function($id) use ($conn) {
        return mysqli_real_escape_string($conn, $id);
    }, $student_ids);
    
    $ids_str = "'" . implode("','", $ids) . "'";
    
    // Build update query
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

// Get student performance
function getStudentPerformance($student_id) {
    global $conn;
    
    // Dummy data for demo
    return [
        'average' => rand(60, 95),
        'subject_scores' => [
            ['subject' => 'Matematik', 'score' => rand(60, 95)],
            ['subject' => 'Sains', 'score' => rand(60, 95)],
            ['subject' => 'Bahasa Melayu', 'score' => rand(60, 95)]
        ]
    ];
}

// Get student attendance
function getStudentAttendance($student_id) {
    // Dummy data for demo
    return [
        'percentage' => rand(85, 100),
        'present' => rand(15, 20),
        'absent' => rand(0, 5),
        'total' => 20
    ];
}

// Get kelas by ID
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

// Close database connection
function closeDB($conn) {
    mysqli_close($conn);
}

// Add default guru if not exists (for demo)
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

// Call this function to ensure demo guru exists
ensureDemoGuruExists();
?>