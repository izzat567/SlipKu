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

// Authentication function for guru
function authenticateGuru($email, $password) {
    global $conn;
    
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    
    // Check in guru table
    $sql = "SELECT * FROM guru WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $guru = mysqli_fetch_assoc($result);
        
        // For demo purposes - check plain text password
        // In production, use password_verify() for hashed passwords
        if ($password === $guru['password']) {
            return $guru;
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

// Get guru by ID
function getGuruById($guru_id) {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    $sql = "SELECT * FROM guru WHERE id = '$guru_id' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return false;
}

// Get students by guru
function getPelajarByGuru($guru_id, $search = '', $kelas = '', $tahun = '', $status = '', $prestasi = '') {
    global $conn;
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    $where_clauses = ["p.guru_id = '$guru_id'"];
    
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $where_clauses[] = "(p.nama LIKE '%$search%' OR p.no_kp LIKE '%$search%')";
    }
    
    if (!empty($kelas)) {
        $kelas = mysqli_real_escape_string($conn, $kelas);
        $where_clauses[] = "k.nama = '$kelas'";
    }
    
    if (!empty($tahun)) {
        $tahun = mysqli_real_escape_string($conn, $tahun);
        $where_clauses[] = "k.tahun = '$tahun'";
    }
    
    if (!empty($status)) {
        if ($status === 'active') $status_value = 1;
        elseif ($status === 'inactive') $status_value = 0;
        elseif ($status === 'graduated') $status_value = 2;
        else $status_value = 1;
        
        $where_clauses[] = "p.status = '$status_value'";
    }
    
    $where_sql = implode(' AND ', $where_clauses);
    
    $sql = "SELECT p.*, k.nama as kelas_nama, k.tahun 
            FROM pelajar p
            LEFT JOIN kelas k ON p.kelas_id = k.id
            WHERE $where_sql
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
    
    $sql = "INSERT INTO pelajar (nama, no_kp, jantina, status, created_at) 
            VALUES ('$nama', '$no_ic', '$jantina', '$status_value', NOW())";
    
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
                status = '$status_value',
                updated_at = NOW()
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
    
    $sql = "SELECT * FROM kelas ORDER BY tahun DESC, nama ASC";
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
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    $sql = "SELECT * FROM kelas WHERE guru_id = '$guru_id' ORDER BY tahun DESC, nama ASC";
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
    
    $guru_id = mysqli_real_escape_string($conn, $guru_id);
    
    $sql = "SELECT 
                COUNT(*) as total_pelajar,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pelajar_aktif
            FROM pelajar p
            LEFT JOIN kelas k ON p.kelas_id = k.id
            WHERE k.guru_id = '$guru_id' OR p.guru_id = '$guru_id'";
    
    $result = mysqli_query($conn, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $stats = mysqli_fetch_assoc($result);
        
        // Add dummy data for demo
        $stats['prestasi_purata'] = 78.5;
        $stats['kadar_kehadiran'] = 92.3;
        
        return $stats;
    }
    
    return [
        'total_pelajar' => 0,
        'pelajar_aktif' => 0,
        'prestasi_purata' => 0,
        'kadar_kehadiran' => 0
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
    // Dummy data for demo
    return [
        'average' => 82.5,
        'subject_scores' => [
            ['subject' => 'Matematik', 'score' => 85],
            ['subject' => 'Sains', 'score' => 80],
            ['subject' => 'Bahasa Melayu', 'score' => 82]
        ]
    ];
}

// Get student attendance
function getStudentAttendance($student_id) {
    // Dummy data for demo
    return [
        'percentage' => 95,
        'present' => 19,
        'absent' => 1,
        'total' => 20
    ];
}

// Close database connection (optional)
function closeDB($conn) {
    mysqli_close($conn);
}
?>