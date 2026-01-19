<?php
// includes/db_functions.php
require_once __DIR__ . '/../../config/connect.php';

// ========== GURU FUNCTIONS ==========
function getGuruInfo($guruId = null) {
    global $conn;
    
    if ($guruId === null && isset($_SESSION['guru_id'])) {
        $guruId = $_SESSION['guru_id'];
    }
    
    if ($guruId) {
        $sql = "SELECT * FROM guru WHERE id_guru = ? AND status = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $guruId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
    }
    
    // Return default untuk testing
    return [
        'id_guru' => 1,
        'nama' => 'Cikgu Ahmad',
        'email' => 'ahmad@sekolah.edu.my',
        'no_telefon' => '012-3456789',
        'role' => 'guru'
    ];
}

// ========== SUBJECT FUNCTIONS ==========
function getSubjects() {
    global $conn;
    
    $result = $conn->query("SELECT * FROM matapelajaran WHERE status = 1 ORDER BY nama");
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
    
    return $subjects;
}

// ========== CLASS FUNCTIONS ==========
function getClasses() {
    global $conn;
    
    $result = $conn->query("SELECT * FROM kelas WHERE status = 1 ORDER BY tahun, nama");
    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
    
    return $classes;
}

// ========== STUDENT FUNCTIONS ==========
function getStudentsByClass($kelas_nama) {
    global $conn;
    
    $sql = "SELECT * FROM pelajar WHERE id_kelas LIKE ? AND status = 1 ORDER BY nama";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", "%$kelas_nama%");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    
    return $students;
}

// ========== CALCULATE GRADE ==========
function calculateGrade($markah, $markah_penuh = 100) {
    if ($markah === null || $markah === '') {
        return '';
    }
    
    $percentage = ($markah / $markah_penuh) * 100;
    
    if ($percentage >= 80) return 'A';
    if ($percentage >= 70) return 'B';
    if ($percentage >= 60) return 'C';
    if ($percentage >= 50) return 'D';
    if ($percentage >= 40) return 'E';
    return 'F';
}
?>