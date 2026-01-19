<?php
// Start session and check login
session_start();

// Include database functions
require_once('../../config/connect.php');
require_once '../includes/db_functions.php';

// Initialize DBFunctions class jika menggunakan class approach
$db = new DBFunctions($conn);

// Get guru info
$guru_info = $db->getGuruInfo();  // Pastikan method ini wujud dalam class

// Get data for dropdowns
$subjects = $db->getSubjects();
$classes = $db->getClasses();
$exams = $db->getExams();

// Initialize variables
$students = [];
$selected_subject = '';
$selected_class = '';
$selected_exam = '';
$markah_penuh = 100;
$markah_lulus = 40;

// Handle form submission for individual marks
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_single') {
        // Add single mark
        $data = [
            'id_pelajar' => $_POST['id_pelajar'],
            'id_peperiksaan' => $_POST['id_peperiksaan'],
            'markah' => $_POST['markah'],
            'gred' => $_POST['gred'],
            'catatan' => $_POST['catatan'] ?? ''
        ];
        
        $result = $db->addMarks($data);
        
        if ($result['success']) {
            $success_message = $result['message'];
        } else {
            $error_message = $result['message'];
        }
    }
    elseif ($_POST['action'] === 'add_bulk') {
        // Add multiple marks
        $marksData = json_decode($_POST['marks_data'], true);
        if ($marksData) {
            $result = $db->addMultipleMarks($marksData);
            
            if ($result['success']) {
                $success_message = "Semua markah berjaya disimpan!";
            } else {
                $error_message = $result['message'];
                if (!empty($result['errors'])) {
                    $error_message .= "<br>" . implode("<br>", array_slice($result['errors'], 0, 5));
                    if (count($result['errors']) > 5) {
                        $error_message .= "<br>... dan " . (count($result['errors']) - 5) . " lagi";
                    }
                }
            }
        }
    }
}

// Handle AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    if ($_GET['ajax'] === 'get_students') {
        $class = $_GET['class'] ?? '';
        $students = $db->getStudentsByClass($class);
        
        echo json_encode([
            'success' => true,
            'students' => $students
        ]);
        exit();
    }
    
    if ($_GET['ajax'] === 'calculate_grade') {
        $markah = $_GET['markah'] ?? 0;
        $markah_penuh = $_GET['markah_penuh'] ?? 100;
        $grade = $db->calculateGrade($markah, $markah_penuh);
        
        echo json_encode([
            'success' => true,
            'grade' => $grade
        ]);
        exit();
    }
}

// Get selected values from form
if (isset($_GET['subject'])) $selected_subject = $_GET['subject'];
if (isset($_GET['class'])) $selected_class = $_GET['class'];
if (isset($_GET['exam'])) $selected_exam = $_GET['exam'];
if (isset($_GET['markah_penuh'])) $markah_penuh = $_GET['markah_penuh'];
if (isset($_GET['markah_lulus'])) $markah_lulus = $_GET['markah_lulus'];

// If class is selected, get students
if ($selected_class) {
    $students = $db->getStudentsByClass($selected_class);
}
?>