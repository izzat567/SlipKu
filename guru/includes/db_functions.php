<?php
// includes/db_functions.php
require_once __DIR__ . '/../../config/connect.php';

class DBFunctions {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // ========== GURU FUNCTIONS ==========
    public function getGuruInfo($guruId = null) {
        // Untuk testing sahaja
        return [
            'id_guru' => 1,
            'nama' => 'Cikgu Ahmad',
            'email' => 'ahmad@sekolah.edu.my',
            'no_telefon' => '012-3456789',
            'role' => 'guru'
        ];
    }
    
    // ========== SUBJECT FUNCTIONS ==========
    public function getSubjects() {
        $sql = "SELECT * FROM matapelajaran WHERE status = 1 ORDER BY nama";
        $result = $this->conn->query($sql);
        
        $subjects = [];
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
        
        return $subjects;
    }
    
    // ========== CLASS FUNCTIONS ==========
    public function getClasses() {
        $sql = "SELECT * FROM kelas WHERE status = 1 ORDER BY tahun, nama";
        $result = $this->conn->query($sql);
        
        $classes = [];
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row;
        }
        
        return $classes;
    }
    
    // ========== STUDENT FUNCTIONS ==========
    public function getStudentsByClass($kelas_nama) {
        $sql = "SELECT * FROM pelajar WHERE id_kelas LIKE ? AND status = 1 ORDER BY nama";
        $stmt = $this->conn->prepare($sql);
        $searchClass = "%$kelas_nama%";
        $stmt->bind_param("s", $searchClass);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        
        return $students;
    }
    
    // ========== EXAM FUNCTIONS ==========
    public function getExams() {
        $sql = "SELECT * FROM peperiksaan WHERE status = 1 ORDER BY tarikh_mula DESC";
        $result = $this->conn->query($sql);
        
        $exams = [];
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        
        return $exams;
    }
    
    // ========== MARK FUNCTIONS ==========
    public function addMarks($data) {
        // Untuk testing sahaja
        return [
            'success' => true,
            'message' => 'Markah berjaya disimpan!',
            'id' => rand(1, 1000)
        ];
    }
    
    public function addMultipleMarks($marksData) {
        // Untuk testing sahaja
        return [
            'success' => true,
            'message' => "Berjaya: " . count($marksData) . ", Gagal: 0",
            'success_count' => count($marksData),
            'error_count' => 0,
            'errors' => []
        ];
    }
    
    // ========== CALCULATE GRADE ==========
    public function calculateGrade($markah, $markah_penuh = 100) {
        if ($markah === null || $markah === '' || $markah == 0) {
            return '-';
        }
        
        $percentage = ($markah / $markah_penuh) * 100;
        
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        if ($percentage >= 40) return 'E';
        return 'F';
    }
}
?>