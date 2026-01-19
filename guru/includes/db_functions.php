<?php
// includes/db_functions.php
require_once __DIR__ .'/../../config/connect.php';

class DBFunctions {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Get all subjects
    public function getSubjects() {
        $sql = "SELECT * FROM matapelajaran WHERE status = 1 ORDER BY nama";
        $result = $this->conn->query($sql);
        
        $subjects = [];
        while ($row = $result->fetch_assoc()) {
            $subjects[] = $row;
        }
        
        return $subjects;
    }
    
    // Get all classes
    public function getClasses() {
        $sql = "SELECT * FROM kelas WHERE status = 1 ORDER BY tahun, nama";
        $result = $this->conn->query($sql);
        
        $classes = [];
        while ($row = $result->fetch_assoc()) {
            $classes[] = $row;
        }
        
        return $classes;
    }
    
    // Get students by class
    public function getStudentsByClass($kelas_nama) {
        $sql = "SELECT * FROM pelajar WHERE id_kelas LIKE ? AND status = 1 ORDER BY nama";
        $stmt = executeQuery($this->conn, $sql, ["%$kelas_nama%"]);
        
        $students = [];
        if ($stmt) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }
        
        return $students;
    }
    
    // Get all exams
    public function getExams() {
        $sql = "SELECT * FROM peperiksaan WHERE status = 1 ORDER BY tarikh_mula DESC";
        $result = $this->conn->query($sql);
        
        $exams = [];
        while ($row = $result->fetch_assoc()) {
            $exams[] = $row;
        }
        
        return $exams;
    }
    
    // Get exam by ID
    public function getExamById($id) {
        $sql = "SELECT * FROM peperiksaan WHERE id = ? AND status = 1";
        $stmt = executeQuery($this->conn, $sql, [$id]);
        
        if ($stmt) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Add marks
    public function addMarks($data) {
        $sql = "INSERT INTO markah 
                (id_pelajar, id_perperiksaan, markah, gred, catatan, tarikh_cipta, tarikh_kemaskini, status) 
                VALUES (?, ?, ?, ?, ?, CURDATE(), CURDATE(), 1)";
        
        $stmt = executeQuery($this->conn, $sql, [
            $data['id_pelajar'],
            $data['id_peperiksaan'],
            $data['markah'],
            $data['gred'],
            $data['catatan'] ?? ''
        ]);
        
        if ($stmt && $stmt->affected_rows > 0) {
            return [
                'success' => true,
                'message' => 'Markah berjaya ditambah!',
                'id' => $stmt->insert_id
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal menambah markah!'
        ];
    }
    
    // Add multiple marks (bulk)
    public function addMultipleMarks($marksData) {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        foreach ($marksData as $index => $data) {
            try {
                $result = $this->addMarks($data);
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $errors[] = "Baris " . ($index + 1) . ": " . $result['message'];
                }
            } catch (Exception $e) {
                $errorCount++;
                $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        return [
            'success' => $errorCount === 0,
            'message' => "Berjaya: $successCount, Gagal: $errorCount",
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'errors' => $errors
        ];
    }
    
    // Get student by ID
    public function getStudentById($id) {
        $sql = "SELECT * FROM pelajar WHERE id = ? AND status = 1";
        $stmt = executeQuery($this->conn, $sql, [$id]);
        
        if ($stmt) {
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    // Check if marks already exist for student and exam
    public function marksExist($pelajar_id, $peperiksaan_id) {
        $sql = "SELECT COUNT(*) as count FROM markah 
                WHERE id_pelajar = ? AND id_perperiksaan = ? AND status = 1";
        $stmt = executeQuery($this->conn, $sql, [$pelajar_id, $peperiksaan_id]);
        
        if ($stmt) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        
        return false;
    }
    
    // Calculate grade based on marks
    public function calculateGrade($markah, $markah_penuh = 100) {
        if ($markah === null || $markah === '') {
            return '';
        }
        
        $percentage = ($markah / $markah_penuh) * 100;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        if ($percentage >= 40) return 'E';
        return 'T';
    }
}
?>