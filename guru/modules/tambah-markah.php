<?php
// Start session and check login


// Include database functions
require_once('../../config/connect.php');
require_once '/../includes/db_functions.php';

// Initialize database functions
$db = new DBFunctions($conn);
$guru_info = getGuruInfo();

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

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Markah - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --black: #000000;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 20px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .menu-toggle:hover {
            background: var(--primary-light);
        }

        /* Header Styles */
        .header {
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 30px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 22px;
        }

        .logo-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .logo-text p {
            font-size: 12px;
            color: var(--medium-gray);
            font-weight: 500;
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            left: 0;
            top: 85px;
            bottom: 0;
            width: 260px;
            padding: 30px 0;
            overflow-y: auto;
            z-index: 900;
            transition: var(--transition);
            transform: translateX(0);
        }

        .sidebar.sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-section {
            margin-bottom: 30px;
            padding: 0 25px;
        }

        .sidebar-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--medium-gray);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: var(--medium-gray);
            text-decoration: none;
            border-radius: 12px;
            margin: 5px 0;
            transition: var(--transition);
            position: relative;
        }

        .sidebar-item:hover {
            background: var(--light-gray);
            color: var(--primary);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .sidebar-item.active i {
            color: white;
        }

        .sidebar-item i {
            width: 20px;
            font-size: 16px;
            color: var(--medium-gray);
        }

        .badge {
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
            min-width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 85px;
            padding: 30px;
            transition: var(--transition);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-title h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .page-title p {
            color: var(--medium-gray);
            font-size: 16px;
        }

        .page-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--dark-gray);
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #0da271;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger);
        }

        .form-input, .form-select, .form-date, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-date:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Selection Section */
        .selection-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .selection-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .selection-title i {
            color: var(--primary);
        }

        /* Marks Table */
        .marks-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .marks-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .marks-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .marks-title i {
            color: var(--success);
        }

        .marks-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
        }

        .marks-table th {
            background: var(--light-gray);
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
        }

        .marks-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .marks-table tr:hover td {
            background: var(--primary-light);
        }

        /* Student Row */
        .student-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .student-info h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-info p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Mark Input */
        .mark-input-container {
            position: relative;
        }

        .mark-input {
            width: 100px;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            transition: var(--transition);
        }

        .mark-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .mark-input.out-of-range {
            border-color: var(--danger);
            background-color: rgba(239, 68, 68, 0.05);
        }

        .mark-input.excellent {
            border-color: var(--success);
            background-color: rgba(16, 185, 129, 0.05);
        }

        .mark-input.good {
            border-color: var(--info);
            background-color: rgba(59, 130, 246, 0.05);
        }

        .mark-input.average {
            border-color: var(--warning);
            background-color: rgba(245, 158, 11, 0.05);
        }

        /* Grade Badge */
        .grade-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 60px;
        }

        .grade-a {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .grade-b {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .grade-c {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .grade-d {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .grade-e {
            background: rgba(107, 114, 128, 0.1);
            color: var(--medium-gray);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Summary Section */
        .summary-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-title i {
            color: var(--primary);
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .summary-stat {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 auto 15px;
        }

        .stat-icon.average {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .stat-icon.highest {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
        }

        .stat-icon.lowest {
            background: linear-gradient(135deg, var(--danger), #f87171);
            color: white;
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, var(--info), #60a5fa);
            color: white;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--medium-gray);
        }

        /* Grade Distribution */
        .grade-distribution {
            margin-top: 30px;
        }

        .grade-distribution-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 15px;
        }

        .grade-bars {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .grade-bar {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .grade-label {
            width: 40px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .grade-bar-fill {
            flex: 1;
            height: 30px;
            background: var(--light-gray);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .grade-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        .grade-count {
            width: 40px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
            text-align: right;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 25px;
            border-bottom: 2px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-gray);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            padding: 5px;
            border-radius: 8px;
        }

        .modal-close:hover {
            background: var(--light-gray);
            color: var(--danger);
        }

        .modal-body {
            padding: 25px;
        }

        /* File Upload */
        .file-upload {
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            margin-bottom: 20px;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload i {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .file-upload p {
            color: var(--medium-gray);
            margin-bottom: 10px;
        }

        .file-upload span {
            font-size: 13px;
            color: var(--medium-gray);
        }

        /* Radio Group */
        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        /* Progress Bar */
        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-value {
            font-size: 12px;
            color: var(--medium-gray);
            text-align: right;
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn.primary {
            background: var(--primary);
            color: white;
        }

        .action-btn.primary:hover {
            background: var(--primary-dark);
        }

        .action-btn.success {
            background: var(--success);
            color: white;
        }

        .action-btn.success:hover {
            background: #0da271;
        }

        .action-btn.info {
            background: var(--info);
            color: white;
        }

        .action-btn.info:hover {
            background: #2563eb;
        }

        .action-btn.warning {
            background: var(--warning);
            color: white;
        }

        .action-btn.warning:hover {
            background: #d97706;
        }

        .action-btn.danger {
            background: var(--danger);
            color: white;
        }

        .action-btn.danger:hover {
            background: #dc2626;
        }

        /* Top Navigation for Mobile */
        .top-nav {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--medium-gray);
            text-decoration: none;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 12px;
            transition: var(--transition);
            position: relative;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            border-radius: 12px;
            background: var(--light-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-profile:hover {
            background: var(--primary-light);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .user-info p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 899;
            display: none;
            backdrop-filter: blur(3px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.sidebar-active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .top-nav {
                display: none;
            }
            
            .user-profile .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 20px;
            }
            
            .header-container {
                padding: 15px 0;
            }
            
            .main-content {
                padding: 20px;
                margin-top: 75px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .summary-stats {
                grid-template-columns: 1fr 1fr;
            }
            
            .marks-table {
                min-width: 1200px;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .logo-text h1 {
                font-size: 20px;
            }
            
            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 13px;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .summary-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
  <div class="modal" id="bulkUploadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Muat Naik Markah Secara Pukal</h3>
                <button class="modal-close" onclick="closeBulkUploadModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="bulkUploadForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_bulk">
                    <input type="hidden" name="marks_data" id="marksDataInput">
                    
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="uploadExcel" name="uploadType" value="excel" checked>
                            <label for="uploadExcel">Muat naik fail Excel</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="uploadCSV" name="uploadType" value="csv">
                            <label for="uploadCSV">Muat naik fail CSV</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="manualEntry" name="uploadType" value="manual">
                            <label for="manualEntry">Masukan Manual</label>
                        </div>
                    </div>
                    
                    <div id="fileUploadSection">
                        <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                            <i class="fas fa-file-upload"></i>
                            <p><strong>Klik untuk muat naik fail</strong></p>
                            <p>Format yang disokong: .xlsx, .xls, .csv</p>
                            <span>Saiz maksimum: 10MB</span>
                        </div>
                        <input type="file" id="fileInput" name="marks_file" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileUpload()">
                    </div>
                    
                    <div id="manualEntrySection" style="display: none;">
                        <textarea id="manualData" rows="10" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px;" placeholder="Masukkan data dalam format CSV:
No_Kad_Pengenalan,Nama,Markah
010101-14-0001,Ahmad bin Abdullah,85
010101-14-0002,Siti Nurhaliza binti Kamal,92"></textarea>
                    </div>
                    
                    <div id="uploadProgress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" id="uploadProgressFill" style="width: 0%"></div>
                        </div>
                        <div class="progress-value" id="uploadProgressText">0%</div>
                    </div>
                    
                    <div style="background: var(--primary-light); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                        <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--primary);">
                            <i class="fas fa-info-circle"></i> Format Fail yang Disyorkan
                        </h4>
                        <p style="font-size: 13px; color: var(--medium-gray); line-height: 1.5;">
                            Fail Excel/CSV anda perlu mengandungi kolum berikut:<br>
                            <strong>no_kp, nama, markah</strong> atau <strong>id_pelajar, markah</strong>
                        </p>
                    </div>
                    
                    <div style="text-align: center;">
                        <button type="button" class="btn btn-primary" onclick="processBulkUpload()" id="processBtn">
                            <i class="fas fa-upload"></i>
                            Mula Muat Naik
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Confirmation -->
      <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="confirmationTitle">Simpan Markah</h3>
                <button class="modal-close" onclick="closeConfirmationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 25px;">
                    <i class="fas fa-question-circle" style="font-size: 48px; color: var(--warning); margin-bottom: 15px;"></i>
                    <h4 id="confirmationMessage" style="font-size: 18px; color: var(--dark-gray); margin-bottom: 10px;">Adakah anda pasti ingin menyimpan semua markah?</h4>
                    <p id="confirmationDetails" style="color: var(--medium-gray); font-size: 14px;">Tindakan ini tidak boleh dibatalkan.</p>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="closeConfirmationModal()">
                        Batal
                    </button>
                    <button class="btn btn-primary" id="confirmActionBtn">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Success -->
    <div class="modal" id="successModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Berjaya!</h3>
                <button class="modal-close" onclick="closeSuccessModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 25px;">
                    <i class="fas fa-check-circle" style="font-size: 48px; color: var(--success); margin-bottom: 15px;"></i>
                    <h4 id="successMessage" style="font-size: 18px; color: var(--dark-gray); margin-bottom: 10px;">Markah Berjaya Disimpan!</h4>
                    <p id="successDetails" style="color: var(--medium-gray); font-size: 14px;">Semua markah telah berjaya disimpan ke dalam sistem.</p>
                </div>
                
                <div style="text-align: center;">
                    <button class="btn btn-primary" onclick="closeSuccessModal()">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo -->
             <a href="dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Tambah Markah</p>
                </div>
            </a>


            <!-- User Profile -->
            <?php if ($guru_info): ?>
            <div class="user-profile" id="userProfile">
                <div class="user-avatar"><?php echo substr($guru_info['nama'], 0, 2); ?></div>
                <div class="user-info">
                    <h4><?php echo htmlspecialchars($guru_info['nama']); ?></h4>
                    <p>Admin Guru Tahun 6</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Sidebar -->
       <?php
    require_once '../includes/session.php';
    require_once '../includes/functions.php';
    SessionManager::requireGuruLogin();

    $functions = new GuruFunctions();
    $pelajar_list = $functions->getAllPelajar();
    ?>

    <?php include '../includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Tambah Markah ✏️</h2>
                <p>Masukkan dan simpan markah peperiksaan atau penilaian pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaHalaman()">
                    <i class="fas fa-redo"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="simpanSemuaMarkah()">
                    <i class="fas fa-save"></i>
                    Simpan Semua
                </button>
            </div>
        </div>

         <!-- Show messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert-message alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert-message alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

         <!-- Selection Section -->
        <div class="selection-section">
            <div class="selection-title">
                <i class="fas fa-filter"></i>
                Pilih Penilaian
            </div>
            
            <form method="GET" action="" id="filterForm">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Subjek</label>
                        <select class="form-select" name="subject" id="subjectSelect" onchange="loadStudents()" required>
                            <option value="">Pilih Subjek</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?php echo $subject['id']; ?>" 
                                    <?php echo ($selected_subject == $subject['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($subject['nama']) . ' (' . htmlspecialchars($subject['kod']) . ')'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Kelas</label>
                        <select class="form-select" name="class" id="classSelect" onchange="loadStudents()" required>
                            <option value="">Pilih Kelas</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?php echo $class['nama']; ?>"
                                    <?php echo ($selected_class == $class['nama']) ? 'selected' : ''; ?>>
                                    Tahun <?php echo $class['tahun']; ?> <?php echo htmlspecialchars($class['nama']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Jenis Peperiksaan</label>
                        <select class="form-select" name="exam" id="examSelect" required>
                            <option value="">Pilih Peperiksaan</option>
                            <?php foreach ($exams as $exam): ?>
                                <option value="<?php echo $exam['id']; ?>"
                                    <?php echo ($selected_exam == $exam['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($exam['nama_peperiksaan']); ?>
                                    (<?php echo date('d/m/Y', strtotime($exam['tarikh_mula'])); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Tarikh Penilaian</label>
                        <input type="date" class="form-date" name="assessment_date" id="assessmentDate" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Markah Penuh</label>
                        <input type="number" class="form-input" name="markah_penuh" id="fullMarks" 
                               value="<?php echo $markah_penuh; ?>" min="1" max="200" onchange="updateMarkingScheme()" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Markah Lulus</label>
                        <input type="number" class="form-input" name="markah_lulus" id="passingMarks" 
                               value="<?php echo $markah_lulus; ?>" min="0" max="100" onchange="updateMarkingScheme()" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-textarea" name="notes" id="assessmentNotes" 
                              placeholder="Catatan mengenai penilaian ini..." rows="2"></textarea>
                </div>
                
                <div style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Muatkan Pelajar
                    </button>
                </div>
            </form>
        </div>

        <!-- Marks Entry Section -->
        <?php if (!empty($students)): ?>
        <div class="marks-container" id="marksContainer">
            <div class="marks-header">
                <div class="marks-title">
                    <i class="fas fa-edit"></i>
                    <span>Masukkan Markah Pelajar</span>
                </div>
                <div class="marks-actions">
                    <button class="action-btn info" onclick="openBulkUploadModal()">
                        <i class="fas fa-upload"></i>
                        Muat Naik Pukal
                    </button>
                    <button class="action-btn success" onclick="hitungPurataAuto()">
                        <i class="fas fa-calculator"></i>
                        Kira Automatik
                    </button>
                    <button class="action-btn danger" onclick="kosongkanSemua()">
                        <i class="fas fa-eraser"></i>
                        Kosongkan Semua
                    </button>
                </div>
            </div>
            
            <div style="overflow-x: auto;">
                <form id="marksForm" method="POST" action="">
                    <input type="hidden" name="action" value="add_single">
                    <input type="hidden" name="id_peperiksaan" id="hiddenExamId" value="<?php echo $selected_exam; ?>">
                    
                    <table class="marks-table" id="marksTable">
                        <thead>
                            <tr>
                                <th>BIL</th>
                                <th>PELAJAR</th>
                                <th>NO. KAD PENGENALAN</th>
                                <th>MARKAH (0-<span id="fullMarksDisplay"><?php echo $markah_penuh; ?></span>)</th>
                                <th>GRED</th>
                                <th>STATUS</th>
                                <th>CATATAN</th>
                                <th>TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody id="marksTableBody">
                            <?php foreach ($students as $index => $student): ?>
                                <?php
                                $initials = '';
                                $names = explode(' ', $student['nama']);
                                if (count($names) >= 2) {
                                    $initials = $names[0][0] . $names[count($names)-1][0];
                                } else {
                                    $initials = substr($student['nama'], 0, 2);
                                }
                                ?>
                                <tr data-student-id="<?php echo $student['id']; ?>">
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="student-row">
                                            <div class="student-avatar"><?php echo strtoupper($initials); ?></div>
                                            <div class="student-info">
                                                <h4><?php echo htmlspecialchars($student['nama']); ?></h4>
                                                <p>ID: <?php echo htmlspecialchars($student['id_kelas']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['no_kp']); ?></td>
                                    <td>
                                        <div class="mark-input-container">
                                            <input type="number" 
                                                   class="mark-input" 
                                                   name="markah[<?php echo $student['id']; ?>]"
                                                   id="mark-<?php echo $student['id']; ?>"
                                                   min="0" 
                                                   max="<?php echo $markah_penuh; ?>"
                                                   placeholder="0-<?php echo $markah_penuh; ?>"
                                                   oninput="updateMark('<?php echo $student['id']; ?>', this.value)"
                                                   onblur="validateMark('<?php echo $student['id']; ?>')">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="grade-badge" id="grade-<?php echo $student['id']; ?>">
                                            -
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-warning" id="status-<?php echo $student['id']; ?>">
                                            BELUM DIISI
                                        </span>
                                    </td>
                                    <td>
                                        <input type="text" 
                                               class="form-input" 
                                               style="font-size: 13px; padding: 8px 12px;"
                                               name="catatan[<?php echo $student['id']; ?>]"
                                               id="notes-<?php echo $student['id']; ?>"
                                                 placeholder="Catatan...">
                                    </td>
                                    <td>
                                        <button type="button" class="action-btn primary" 
                                                onclick="simpanMarkahIndividu('<?php echo $student['id']; ?>')">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div style="text-align: right; margin-top: 20px; font-size: 13px; color: var(--medium-gray);">
                <span id="marksStatus"><?php echo count($students); ?> pelajar ditemui. Tiada markah dimasukkan.</span>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section" id="summarySection">
            <div class="summary-title">
                <i class="fas fa-chart-bar"></i>
                Ringkasan Markah
            </div>
            
            <div class="summary-stats">
                <div class="summary-stat">
                    <div class="stat-icon average">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-value" id="averageMarks">0.0</div>
                    <div class="stat-label">Purata Markah</div>
                </div>
                
                <div class="summary-stat">
                    <div class="stat-icon highest">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-value" id="highestMarks">0</div>
                    <div class="stat-label">Markah Tertinggi</div>
                </div>
                
                <div class="summary-stat">
                    <div class="stat-icon lowest">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-value" id="lowestMarks">0</div>
                    <div class="stat-label">Markah Terendah</div>
                </div>
                
                <div class="summary-stat">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value" id="studentsCompleted">0/<?php echo count($students); ?></div>
                    <div class="stat-label">Pelajar Selesai</div>
                </div>
            </div>
            
            <div class="grade-distribution">
                <div class="grade-distribution-title">Taburan Gred</div>
                <div class="grade-bars" id="gradeBars">
                    <!-- Grade distribution bars will be loaded dynamically -->
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const bulkUploadModal = document.getElementById('bulkUploadModal');
        const confirmationModal = document.getElementById('confirmationModal');
        const successModal = document.getElementById('successModal');
        const marksContainer = document.getElementById('marksContainer');
        const summarySection = document.getElementById('summarySection');
        const marksTableBody = document.getElementById('marksTableBody');
        const gradeBars = document.getElementById('gradeBars');

        // Current state
        let currentStudents = [];
        let currentMarks = {};
        let gradeDistribution = { A: 0, B: 0, C: 0, D: 0, E: 0, F: 0 };

        // Sample data for students
        const sampleStudents = {
            '6A': [
                { id: 'STU001', name: 'Ahmad bin Ali', ic: '120101-01-1234', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU002', name: 'Siti binti Abu', ic: '120202-02-5678', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU003', name: 'Muhammad bin Hassan', ic: '120303-03-9012', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU004', name: 'Aisyah binti Musa', ic: '120404-04-3456', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU005', name: 'Ali bin Abdullah', ic: '120505-05-7890', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU006', name: 'Fatimah binti Omar', ic: '120606-06-2345', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU007', name: 'Hassan bin Ismail', ic: '120707-07-6789', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU008', name: 'Zainab binti Yusuf', ic: '120808-08-1234', marks: null, grade: '', status: 'pending', notes: '' }
            ],
            '6B': [
                { id: 'STU009', name: 'Ibrahim bin Adam', ic: '120909-09-5678', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU010', name: 'Mariam binti Noah', ic: '121010-10-9012', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU011', name: 'Yusuf bin Idris', ic: '121111-11-3456', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU012', name: 'Sarah binti Harun', ic: '121212-12-7890', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU013', name: 'Ismail bin Lut', ic: '130101-01-2345', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU014', name: 'Hajar binti Yakub', ic: '130202-02-6789', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU015', name: 'Isa bin Daud', ic: '130303-03-1234', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU016', name: 'Maryam binti Sulaiman', ic: '130404-04-5678', marks: null, grade: '', status: 'pending', notes: '' }
            ],
            '5A': [
                { id: 'STU017', name: 'Musa bin Zakaria', ic: '130505-05-9012', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU018', name: 'Khadijah binti Yahya', ic: '130606-06-3456', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU019', name: 'Harun bin Yunus', ic: '130707-07-7890', marks: null, grade: '', status: 'pending', notes: '' },
                { id: 'STU020', name: 'Asiyah binti Ilyas', ic: '130808-08-2345', marks: null, grade: '', status: 'pending', notes: '' }
            ]
        };

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Set default values
            document.getElementById('subjectSelect').value = 'MAT601';
            document.getElementById('classSelect').value = '6A';
            document.getElementById('assessmentType').value = 'quiz1';
            
            // Load initial data
            loadStudents();
        }

        // Load students based on selection
        function loadStudents() {
            const subject = document.getElementById('subjectSelect').value;
            const classSelect = document.getElementById('classSelect').value;
            
            if (!subject || !classSelect) {
                marksContainer.style.display = 'none';
                summarySection.style.display = 'none';
                return;
            }
            
            currentStudents = sampleStudents[classSelect] || [];
            currentMarks = {};
            
            // Reset grade distribution
            gradeDistribution = { A: 0, B: 0, C: 0, D: 0, E: 0, F: 0 };
            
            // Show marks container
            marksContainer.style.display = 'block';
            summarySection.style.display = 'block';
            
            // Load students into table
            loadStudentTable();
            
            // Update summary
            updateSummary();
        }

        // Load student table
        function loadStudentTable() {
            marksTableBody.innerHTML = currentStudents.map((student, index) => {
                // Get student initials for avatar
                const names = student.name.split(' ');
                const initials = names.length >= 2 
                    ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                    : names[0].substring(0, 2);
                
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <h4>${student.name}</h4>
                                    <p>ID: ${student.id}</p>
                                </div>
                            </div>
                        </td>
                        <td>${student.ic}</td>
                        <td>
                            <div class="mark-input-container">
                                <input type="number" 
                                       class="mark-input" 
                                       id="mark-${student.id}"
                                       min="0" 
                                       max="${document.getElementById('fullMarks').value}"
                                       value="${student.marks !== null ? student.marks : ''}"
                                       oninput="updateMark('${student.id}', this.value)"
                                       onblur="validateMark('${student.id}')"
                                       placeholder="0-${document.getElementById('fullMarks').value}">
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${student.grade.toLowerCase()}" id="grade-${student.id}">
                                ${student.grade || '-'}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge ${getStatusClass(student.status)}" id="status-${student.id}">
                                ${getStatusText(student.status)}
                            </span>
                        </td>
                        <td>
                            <input type="text" 
                                   class="form-input" 
                                   style="font-size: 13px; padding: 8px 12px;"
                                   id="notes-${student.id}"
                                   value="${student.notes}"
                                   placeholder="Catatan..."
                                   onchange="updateNotes('${student.id}', this.value)">
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Update full marks display
            document.getElementById('fullMarksDisplay').textContent = document.getElementById('fullMarks').value;
        }

        // Update mark for a student
        function updateMark(studentId, mark) {
            const student = currentStudents.find(s => s.id === studentId);
            if (!student) return;
            
            // Parse mark value
            const markValue = mark === '' ? null : parseInt(mark);
            
            // Update student data
            student.marks = markValue;
            
            // Update current marks object
            if (markValue !== null) {
                currentMarks[studentId] = markValue;
            } else {
                delete currentMarks[studentId];
            }
            
            // Update grade and status
            updateStudentGradeAndStatus(studentId);
            
            // Update summary
            updateSummary();
            
            // Update marks status
            updateMarksStatus();
        }

        // Validate mark input
        function validateMark(studentId) {
            const input = document.getElementById(`mark-${studentId}`);
            const fullMarks = parseInt(document.getElementById('fullMarks').value);
            const markValue = input.value === '' ? null : parseInt(input.value);
            
            if (markValue !== null) {
                if (markValue < 0 || markValue > fullMarks) {
                    input.classList.add('out-of-range');
                    showNotification(`Markah mesti antara 0 dan ${fullMarks}`, 'warning');
                } else {
                    input.classList.remove('out-of-range');
                    
                    // Add visual feedback based on mark
                    const percentage = (markValue / fullMarks) * 100;
                    input.classList.remove('excellent', 'good', 'average');
                    
                    if (percentage >= 80) {
                        input.classList.add('excellent');
                    } else if (percentage >= 60) {
                        input.classList.add('good');
                    } else if (percentage >= 40) {
                        input.classList.add('average');
                    }
                }
            } else {
                input.classList.remove('out-of-range', 'excellent', 'good', 'average');
            }
        }

        // Update student grade and status
        function updateStudentGradeAndStatus(studentId) {
            const student = currentStudents.find(s => s.id === studentId);
            if (!student) return;
            
            const fullMarks = parseInt(document.getElementById('fullMarks').value);
            const passingMarks = parseInt(document.getElementById('passingMarks').value);
            
            // Calculate grade
            let grade = 'F';
            let status = 'pending';
            
            if (student.marks !== null) {
                const percentage = (student.marks / fullMarks) * 100;
                
                if (percentage >= 80) {
                    grade = 'A';
                } else if (percentage >= 70) {
                    grade = 'B';
                } else if (percentage >= 60) {
                    grade = 'C';
                } else if (percentage >= 50) {
                    grade = 'D';
                } else if (percentage >= 40) {
                    grade = 'E';
                }
                
                // Update status
                status = student.marks >= passingMarks ? 'passed' : 'failed';
                
                // Update grade distribution
                updateGradeDistribution();
            } else {
                status = 'pending';
            }
            
            // Update student data
            student.grade = grade;
            student.status = status;
            
            // Update UI
            const gradeBadge = document.getElementById(`grade-${studentId}`);
            const statusBadge = document.getElementById(`status-${studentId}`);
            
            gradeBadge.textContent = student.marks !== null ? grade : '-';
            gradeBadge.className = `grade-badge grade-${grade.toLowerCase()}`;
            
            statusBadge.textContent = getStatusText(status);
            statusBadge.className = `status-badge ${getStatusClass(status)}`;
        }

        // Update grade distribution
        function updateGradeDistribution() {
            // Reset distribution
            gradeDistribution = { A: 0, B: 0, C: 0, D: 0, E: 0, F: 0 };
            
            // Count grades
            currentStudents.forEach(student => {
                if (student.grade && student.marks !== null) {
                    gradeDistribution[student.grade] = (gradeDistribution[student.grade] || 0) + 1;
                }
            });
            
            // Update grade distribution display
            updateGradeDistributionDisplay();
        }

        // Update grade distribution display
        function updateGradeDistributionDisplay() {
            const totalStudents = currentStudents.length;
            const totalWithMarks = Object.values(currentMarks).length;
            
            gradeBars.innerHTML = '';
            
            ['A', 'B', 'C', 'D', 'E', 'F'].forEach(grade => {
                const count = gradeDistribution[grade] || 0;
                const percentage = totalWithMarks > 0 ? (count / totalWithMarks) * 100 : 0;
                
                const gradeColor = {
                    'A': '#10b981',
                    'B': '#3b82f6',
                    'C': '#f59e0b',
                    'D': '#ef4444',
                    'E': '#8b5cf6',
                    'F': '#6b7280'
                }[grade];
                
                gradeBars.innerHTML += `
                    <div class="grade-bar">
                        <div class="grade-label">Gred ${grade}</div>
                        <div class="grade-bar-fill">
                            <div class="grade-fill" id="grade-fill-${grade}" 
                                 style="width: ${percentage}%; background: ${gradeColor};">
                            </div>
                        </div>
                        <div class="grade-count">${count}</div>
                    </div>
                `;
            });
        }

        // Update summary statistics
        function updateSummary() {
            const marks = Object.values(currentMarks);
            const totalStudents = currentStudents.length;
            const totalWithMarks = marks.length;
            
            // Calculate statistics
            let average = 0;
            let highest = 0;
            let lowest = 100;
            
            if (marks.length > 0) {
                const sum = marks.reduce((a, b) => a + b, 0);
                average = sum / marks.length;
                highest = Math.max(...marks);
                lowest = Math.min(...marks);
            }
            
            // Update UI
            document.getElementById('averageMarks').textContent = average.toFixed(1);
            document.getElementById('highestMarks').textContent = highest;
            document.getElementById('lowestMarks').textContent = lowest > 99 ? 0 : lowest;
            document.getElementById('studentsCompleted').textContent = `${totalWithMarks}/${totalStudents}`;
        }

        // Update marks status
        function updateMarksStatus() {
            const totalStudents = currentStudents.length;
            const totalWithMarks = Object.values(currentMarks).length;
            
            let statusText = '';
            let statusColor = 'var(--medium-gray)';
            
            if (totalWithMarks === 0) {
                statusText = 'Tiada markah dimasukkan';
                statusColor = 'var(--danger)';
            } else if (totalWithMarks < totalStudents) {
                statusText = `${totalWithMarks} daripada ${totalStudents} pelajar telah dimasukkan`;
                statusColor = 'var(--warning)';
            } else {
                statusText = `Semua ${totalStudents} pelajar telah dimasukkan`;
                statusColor = 'var(--success)';
            }
            
            document.getElementById('marksStatus').textContent = statusText;
            document.getElementById('marksStatus').style.color = statusColor;
        }

        // Update notes for a student
        function updateNotes(studentId, notes) {
            const student = currentStudents.find(s => s.id === studentId);
            if (student) {
                student.notes = notes;
            }
        }

        // Calculate grades automatically
        function hitungPurataAuto() {
            if (!confirm('Adakah anda ingin mengira markah secara automatik untuk pelajar yang tiada markah?')) {
                return;
            }
            
            const fullMarks = parseInt(document.getElementById('fullMarks').value);
            
            currentStudents.forEach(student => {
                if (student.marks === null) {
                    // Generate random mark between 40 and 95
                    const randomMark = Math.floor(Math.random() * 56) + 40;
                    
                    // Update mark
                    const input = document.getElementById(`mark-${student.id}`);
                    input.value = randomMark;
                    
                    // Update student data
                    updateMark(student.id, randomMark);
                    validateMark(student.id);
                }
            });
            
            showNotification('Markah telah dikira secara automatik', 'success');
        }

        // Clear all marks
        function kosongkanSemua() {
            if (!confirm('Adakah anda pasti ingin mengosongkan semua markah?')) {
                return;
            }
            
            currentStudents.forEach(student => {
                student.marks = null;
                student.grade = '';
                student.status = 'pending';
                
                const input = document.getElementById(`mark-${student.id}`);
                if (input) {
                    input.value = '';
                    input.classList.remove('out-of-range', 'excellent', 'good', 'average');
                }
                
                updateStudentGradeAndStatus(student.id);
            });
            
            currentMarks = {};
            updateSummary();
            updateMarksStatus();
            
            showNotification('Semua markah telah dikosongkan', 'info');
        }

        // Save all marks
        function simpanSemuaMarkah() {
            const totalWithMarks = Object.values(currentMarks).length;
            const totalStudents = currentStudents.length;
            
            if (totalWithMarks === 0) {
                showNotification('Tiada markah untuk disimpan', 'warning');
                return;
            }
            
            // Show confirmation modal
            document.getElementById('confirmationTitle').textContent = 'Simpan Markah';
            document.getElementById('confirmationMessage').textContent = `Adakah anda pasti ingin menyimpan markah untuk ${totalWithMarks} pelajar?`;
            document.getElementById('confirmationDetails').textContent = 'Markah akan disimpan ke dalam sistem dan tidak boleh diubah melainkan melalui kemaskini markah.';
            
            document.getElementById('confirmActionBtn').onclick = function() {
                saveMarksToSystem();
                closeConfirmationModal();
            };
            
            confirmationModal.classList.add('active');
        }

        // Save marks to system (simulated)
        function saveMarksToSystem() {
            // Show loading
            showNotification('Menyimpan markah...', 'info');
            
            // Simulate API call delay
            setTimeout(() => {
                // Show success modal
                document.getElementById('successMessage').textContent = 'Markah Berjaya Disimpan!';
                document.getElementById('successDetails').textContent = `Markah untuk ${Object.values(currentMarks).length} pelajar telah berjaya disimpan ke dalam sistem.`;
                
                successModal.classList.add('active');
                
                // Log saved data (for demo)
                console.log('Marks saved:', {
                    subject: document.getElementById('subjectSelect').value,
                    class: document.getElementById('classSelect').value,
                    assessmentType: document.getElementById('assessmentType').value,
                    assessmentDate: document.getElementById('assessmentDate').value,
                    fullMarks: document.getElementById('fullMarks').value,
                    passingMarks: document.getElementById('passingMarks').value,
                    marks: currentMarks,
                    students: currentStudents
                });
            }, 1500);
        }

        // Update marking scheme
        function updateMarkingScheme() {
            const fullMarks = document.getElementById('fullMarks').value;
            document.getElementById('fullMarksDisplay').textContent = fullMarks;
            
            // Update max attribute for all mark inputs
            currentStudents.forEach(student => {
                const input = document.getElementById(`mark-${student.id}`);
                if (input) {
                    input.max = fullMarks;
                    input.placeholder = `0-${fullMarks}`;
                    
                    // Revalidate current value
                    validateMark(student.id);
                }
            });
        }

        // Open bulk upload modal
        function openBulkUploadModal() {
            bulkUploadModal.classList.add('active');
        }

        // Close bulk upload modal
        function closeBulkUploadModal() {
            bulkUploadModal.classList.remove('active');
        }

        // Handle file upload
        function handleFileUpload() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            
            if (!file) return;
            
            // Check file type
            const validTypes = ['.xlsx', '.xls', '.csv'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!validTypes.includes(fileExtension)) {
                showNotification('Sila pilih fail Excel atau CSV sahaja', 'error');
                fileInput.value = '';
                return;
            }
            
            // Check file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                showNotification('Fail terlalu besar. Saiz maksimum 10MB', 'error');
                fileInput.value = '';
                return;
            }
            
            showNotification(`Fail "${file.name}" dipilih`, 'info');
        }

        // Simulate bulk upload
        function simulateBulkUpload() {
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadProgressFill = document.getElementById('uploadProgressFill');
            const uploadProgressText = document.getElementById('uploadProgressText');
            
            // Show progress bar
            uploadProgress.style.display = 'block';
            
            // Simulate upload progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                uploadProgressFill.style.width = `${progress}%`;
                uploadProgressText.textContent = `${progress}%`;
                
                if (progress >= 100) {
                    clearInterval(interval);
                    
                    // Simulate processing
                    setTimeout(() => {
                        // Simulate successful upload
                        showNotification('Fail berjaya dimuat naik dan diproses', 'success');
                        
                        // Simulate data loading from file
                        setTimeout(() => {
                            // Load sample data from "uploaded file"
                            const sampleData = {
                                'STU001': 85,
                                'STU002': 72,
                                'STU003': 90,
                                'STU004': 68,
                                'STU005': 79,
                                'STU006': 55,
                                'STU007': 92,
                                'STU008': 61
                            };
                            
                            // Apply the marks
                            Object.keys(sampleData).forEach(studentId => {
                                const input = document.getElementById(`mark-${studentId}`);
                                if (input) {
                                    input.value = sampleData[studentId];
                                    updateMark(studentId, sampleData[studentId]);
                                    validateMark(studentId);
                                }
                            });
                            
                            closeBulkUploadModal();
                            showNotification('Markah dari fail berjaya dimuatkan', 'success');
                        }, 500);
                    }, 500);
                }
            }, 100);
        }

        // Close confirmation modal
        function closeConfirmationModal() {
            confirmationModal.classList.remove('active');
        }

        // Close success modal
        function closeSuccessModal() {
            successModal.classList.remove('active');
        }

        // Reload page
        function muatSemulaHalaman() {
            if (confirm('Adakah anda pasti ingin memuat semula halaman? Semua perubahan yang belum disimpan akan hilang.')) {
                location.reload();
            }
        }

        // Get status class
        function getStatusClass(status) {
            switch (status) {
                case 'passed': return 'status-success';
                case 'failed': return 'status-danger';
                default: return 'status-warning';
            }
        }

        // Get status text
        function getStatusText(status) {
            switch (status) {
                case 'passed': return 'LULUS';
                case 'failed': return 'GAGAL';
                default: return 'BELUM DIISI';
            }
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 30px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : type === 'warning' ? 'var(--warning)' : 'var(--info)'};
                color: white;
                padding: 15px 25px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Setup event listeners
        function setupEventListeners() {
            // Toggle sidebar
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
            
            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    closeBulkUploadModal();
                    closeConfirmationModal();
                    closeSuccessModal();
                }
            });
        }

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            sidebarOverlay.classList.toggle('active');
            mainContent.classList.toggle('full-width');
            document.body.style.overflow = sidebar.classList.contains('sidebar-active') ? 'hidden' : '';
        }

        // Close Sidebar on Mobile
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('sidebar-active');
                sidebarOverlay.classList.remove('active');
                mainContent.classList.remove('full-width');
                document.body.style.overflow = '';
            }
        }

        // Add CSS for notification animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    </script>
</body>
</html>