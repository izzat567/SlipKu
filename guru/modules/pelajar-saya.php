<?php
session_start();

// Include database functions
require_once __DIR__ . '/../includes/db_functions.php';

// Check if user is logged in
if (!isset($_SESSION['guru_id'])) {
    header('Location: ../login.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];
$action = $_GET['action'] ?? '';
$student_id = $_GET['id'] ?? '';

// Set default timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch($action) {
        case 'add':
            handleAddStudent();
            break;
        case 'edit':
            handleEditStudent($student_id);
            break;
        case 'delete':
            handleDeleteStudent($student_id);
            break;
        case 'bulk_delete':
            handleBulkDelete();
            break;
        case 'bulk_update':
            handleBulkUpdate();
            break;
        case 'import':
            handleImportStudents();
            break;
    }
}

// Get search and filter parameters
$search = $_GET['search'] ?? '';
$kelas = $_GET['kelas'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$status = $_GET['status'] ?? '';
$prestasi = $_GET['prestasi'] ?? '';

// Get data from database
$pelajar_list = getPelajarByGuru($guru_id, $search, $kelas, $tahun, $status, $prestasi);
$kelas_guru = getKelasByGuru($guru_id);
$statistik = getStatistikPelajar($guru_id);
$all_kelas = getAllKelas();

// If editing, get student data
$student = null;
if ($action === 'edit' && $student_id) {
    $student = getPelajarById($student_id);
}

// Function to handle adding student
function handleAddStudent() {
    // Validate required fields
    $required_fields = ['nama', 'no_ic', 'jantina'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
            return;
        }
    }
    
    // Check if IC already exists
    if (checkStudentExists($_POST['no_ic'])) {
        $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
        return;
    }
    
    $data = [
        'nama' => trim($_POST['nama']),
        'no_ic' => trim($_POST['no_ic']),
        'jantina' => $_POST['jantina']
    ];
    
    // Optional fields
    if (!empty($_POST['status'])) {
        $data['status'] = $_POST['status'];
    }
    
    if (tambahPelajar($data)) {
        $_SESSION['success_message'] = 'Pelajar berjaya ditambah!';
        header('Location: pelajar-saya.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Gagal menambah pelajar. Sila cuba lagi.';
    }
}

// Function to handle editing student
function handleEditStudent($id) {
    if (empty($id)) {
        $_SESSION['error_message'] = 'ID pelajar tidak sah.';
        return;
    }
    
    // Validate required fields
    $required_fields = ['nama', 'no_ic', 'jantina'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
            return;
        }
    }
    
    // Check if IC already exists (excluding current student)
    if (checkStudentExists($_POST['no_ic'], $id)) {
        $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
        return;
    }
    
    $data = [
        'nama' => trim($_POST['nama']),
        'no_ic' => trim($_POST['no_ic']),
        'jantina' => $_POST['jantina']
    ];
    
    // Optional fields
    if (!empty($_POST['status'])) {
        $data['status'] = $_POST['status'];
    }
    
    if (kemaskiniPelajar($id, $data)) {
        $_SESSION['success_message'] = 'Maklumat pelajar berjaya dikemaskini!';
        header('Location: pelajar-saya.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Gagal mengemaskini pelajar. Sila cuba lagi.';
    }
}

// Function to handle deleting student
function handleDeleteStudent($id) {
    if (empty($id)) {
        $_SESSION['error_message'] = 'ID pelajar tidak sah.';
        return;
    }
    
    if (padamPelajar($id)) {
        $_SESSION['success_message'] = 'Pelajar berjaya dipadam!';
    } else {
        $_SESSION['error_message'] = 'Gagal memadam pelajar. Sila cuba lagi.';
    }
    header('Location: pelajar-saya.php');
    exit();
}

// Function to handle bulk delete
function handleBulkDelete() {
    if (isset($_POST['student_ids']) && is_array($_POST['student_ids'])) {
        $success_count = 0;
        foreach ($_POST['student_ids'] as $id) {
            if (padamPelajar($id)) {
                $success_count++;
            }
        }
        
        if ($success_count > 0) {
            $_SESSION['success_message'] = "Berjaya memadam $success_count pelajar!";
        } else {
            $_SESSION['error_message'] = 'Gagal memadam pelajar terpilih.';
        }
    } else {
        $_SESSION['error_message'] = 'Tiada pelajar dipilih.';
    }
    header('Location: pelajar-saya.php');
    exit();
}

// Function to handle bulk update
function handleBulkUpdate() {
    if (isset($_POST['student_ids']) && is_array($_POST['student_ids']) && isset($_POST['bulk_action'])) {
        $student_ids = array_map('intval', $_POST['student_ids']);
        
        if ($_POST['bulk_action'] === 'update_status' && isset($_POST['new_status'])) {
            $update_data = ['status' => $_POST['new_status']];
            if (bulkUpdateStudents($student_ids, $update_data)) {
                $_SESSION['success_message'] = 'Status pelajar terpilih berjaya dikemaskini!';
            } else {
                $_SESSION['error_message'] = 'Gagal mengemaskini status pelajar.';
            }
        }
    } else {
        $_SESSION['error_message'] = 'Tiada pelajar dipilih atau tindakan tidak ditentukan.';
    }
    header('Location: pelajar-saya.php');
    exit();
}

// Function to handle import students
function handleImportStudents() {
    // This would handle CSV/Excel file import
    // For now, just show a message
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] === UPLOAD_ERR_OK) {
        $_SESSION['success_message'] = 'Fail berjaya dimuat naik. Proses import sedang dijalankan.';
    } else {
        $_SESSION['error_message'] = 'Gagal memuat naik fail.';
    }
    header('Location: pelajar-saya.php');
    exit();
}

// Display success/error messages
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Handle AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['ajax']) {
        case 'get_students':
            // Return JSON data for AJAX loading
            $response = [
                'success' => true,
                'students' => $pelajar_list,
                'total' => count($pelajar_list),
                'statistics' => $statistik
            ];
            echo json_encode($response);
            exit;
            
        case 'get_student':
            $student_id = $_GET['student_id'] ?? '';
            $student = getPelajarById($student_id);
            $performance = getStudentPerformance($student_id);
            $attendance = getStudentAttendance($student_id);
            
            echo json_encode([
                'success' => true,
                'student' => $student,
                'performance' => $performance,
                'attendance' => $attendance
            ]);
            exit;
            
        case 'delete_student':
            $student_id = $_GET['student_id'] ?? '';
            if (padamPelajar($student_id)) {
                echo json_encode(['success' => true, 'message' => 'Pelajar berjaya dipadam']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memadam pelajar']);
            }
            exit;
            
        case 'check_ic':
            $no_ic = $_GET['no_ic'] ?? '';
            $exclude_id = $_GET['exclude_id'] ?? null;
            $exists = checkStudentExists($no_ic, $exclude_id);
            echo json_encode(['exists' => $exists]);
            exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelajar Saya - SlipKu</title>
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

        .action-btn.view {
            background: var(--info);
            color: white;
        }

        .action-btn.view:hover {
            background: #2563eb;
        }

        .action-btn.edit {
            background: var(--warning);
            color: white;
        }

        .action-btn.edit:hover {
            background: #d97706;
        }

        .action-btn.delete {
            background: var(--danger);
            color: white;
        }

        .action-btn.delete:hover {
            background: #dc2626;
        }

        .action-btn.add {
            background: var(--success);
            color: white;
        }

        .action-btn.add:hover {
            background: #0da271;
        }

        /* Search and Filter Section */
        .search-filter-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
            font-size: 16px;
        }

        .filter-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
            white-space: nowrap;
        }

        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Class Summary */
        .class-summary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            padding: 25px;
            color: white;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .summary-info h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .summary-info p {
            opacity: 0.9;
        }

        .summary-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .summary-stat {
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Student Table Container */
        .student-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        th {
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

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        tr:hover td {
            background: var(--primary-light);
        }

        /* Student Avatar in Table */
        .student-avatar-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .student-info {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-id {
            font-size: 12px;
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

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-graduated {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        /* Performance Indicators */
        .performance-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .performance-bar {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .performance-fill {
            height: 100%;
            border-radius: 4px;
        }

        .performance-excellent {
            background: var(--success);
            width: 90%;
        }

        .performance-good {
            background: #3b82f6;
            width: 75%;
        }

        .performance-average {
            background: var(--warning);
            width: 60%;
        }

        .performance-poor {
            background: var(--danger);
            width: 40%;
        }

        .performance-value {
            font-weight: 600;
            color: var(--dark-gray);
            min-width: 40px;
            text-align: right;
        }

        /* Action Cells */
        .action-cell {
            display: flex;
            gap: 8px;
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

        .form-input, .form-select, .form-date {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-date:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 2px solid var(--light-gray);
        }

        /* Bulk Actions */
        .bulk-actions {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .bulk-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bulk-select input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .pagination-info {
            font-size: 14px;
            color: var(--medium-gray);
        }

        .pagination-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .page-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: var(--white);
            color: var(--dark-gray);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .page-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--medium-gray);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--primary-light);
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--dark-gray);
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
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
            
            .filter-options {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-select {
                flex: 1;
            }
            
            .class-summary {
                flex-direction: column;
                text-align: center;
            }
            
            .summary-stats {
                justify-content: center;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .pagination {
                flex-direction: column;
                gap: 15px;
                text-align: center;
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
            
            .summary-stat {
                min-width: 80px;
            }
            
            .stat-number {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Modal for Add/Edit Student -->
    <!-- Modal for Add/Edit Student -->
<div class="modal" id="studentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle"><?= isset($student) ? 'Edit Pelajar' : 'Tambah Pelajar Baru' ?></h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="studentForm" method="POST" action="?action=<?= isset($student) ? 'edit&id=' . $student['id'] : 'add' ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Nama Penuh</label>
                        <input type="text" class="form-input" id="studentName" name="nama" 
                               placeholder="Nama penuh pelajar" required 
                               value="<?= isset($student) ? htmlspecialchars($student['nama']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">No. Kad Pengenalan</label>
                        <input type="text" class="form-input" id="studentIC" name="no_ic" 
                               placeholder="Contoh: 030101-14-1234" required 
                               value="<?= isset($student) ? htmlspecialchars($student['no_kp']) : '' ?>"
                               onblur="checkICExists()">
                        <small id="icError" style="color: var(--danger); display: none;">
                            No. KP sudah wujud dalam sistem
                        </small>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">Jantina</label>
                        <select class="form-select" id="studentGender" name="jantina" required>
                            <option value="">Pilih Jantina</option>
                            <option value="male" <?= (isset($student) && $student['jantina'] == 'L') ? 'selected' : '' ?>>Lelaki</option>
                            <option value="female" <?= (isset($student) && $student['jantina'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="studentStatus" name="status">
                            <option value="active" <?= (isset($student) && $student['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= (isset($student) && $student['status'] == 0) ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="graduated" <?= (isset($student) && $student['status'] == 2) ? 'selected' : '' ?>>Tamat</option>
                        </select>
                    </div>
                </div>
                
                <input type="hidden" id="currentStudentId" value="<?= isset($student) ? $student['id'] : '' ?>">
                
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <?= isset($student) ? 'Kemaskini Pelajar' : 'Simpan Pelajar' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add success/error message display -->
<?php if (!empty($success_message)): ?>
<div class="alert alert-success" style="position: fixed; top: 100px; right: 30px; z-index: 10000; padding: 15px 25px; background: var(--success); color: white; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); animation: slideIn 0.3s ease;">
    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
</div>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.animation = 'slideOut 0.3s ease';
        setTimeout(() => document.querySelector('.alert').remove(), 300);
    }, 3000);
</script>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
<div class="alert alert-error" style="position: fixed; top: 100px; right: 30px; z-index: 10000; padding: 15px 25px; background: var(--danger); color: white; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); animation: slideIn 0.3s ease;">
    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error_message) ?>
</div>
<script>
    setTimeout(() => {
        document.querySelector('.alert').style.animation = 'slideOut 0.3s ease';
        setTimeout(() => document.querySelector('.alert').remove(), 300);
    }, 3000);
</script>
<?php endif; ?>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
</style>

    <!-- Modal for Bulk Import -->
    <div class="modal" id="importModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Import Pelajar</h3>
                <button class="modal-close" onclick="closeImportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 32px; margin: 0 auto 15px;">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <h4 style="font-size: 18px; margin-bottom: 10px; color: var(--dark-gray);">Import Data Pelajar</h4>
                    <p style="color: var(--medium-gray); font-size: 14px;">Muat naik fail Excel atau CSV yang mengandungi data pelajar</p>
                </div>
                
                <div style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 40px 20px; text-align: center; margin-bottom: 20px; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--primary-light); margin-bottom: 15px;"></i>
                    <p style="color: var(--medium-gray); margin-bottom: 10px;">Klik untuk muat naik fail atau seret fail ke sini</p>
                    <p style="font-size: 12px; color: var(--medium-gray);">Format yang disokong: .xlsx, .xls, .csv</p>
                </div>
                <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileUpload(this)">
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <h5 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">
                        <i class="fas fa-info-circle" style="color: var(--info);"></i>
                        Panduan Format Data
                    </h5>
                    <ul style="font-size: 13px; color: var(--medium-gray); padding-left: 20px;">
                        <li>Pastikan fail mengandungi kolom: Nama, IC, Kelas, Tahun, Jantina</li>
                        <li>Format tarikh: YYYY-MM-DD</li>
                        <li>Muat turun template untuk format yang betul</li>
                    </ul>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="downloadTemplate()">
                        <i class="fas fa-download"></i>
                        Template
                    </button>
                    <button class="btn btn-primary" onclick="processImport()">
                        <i class="fas fa-upload"></i>
                        Import
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
            <a href="dashboard-admin.html" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Pelajar Saya</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="top-nav">
                <a href="dashboard-admin.html" class="nav-item">
                    <i class="fas fa-home"></i>
                    Utama
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    Pemberitahuan
                    <span class="notification-badge">5</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    Mesej
                    <span class="notification-badge">3</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">GU</div>
                <div class="user-info">
                    <h4>Cikgu Ahmad</h4>
                    <p>Admin Guru Tahun 6</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </header>
   <?php include '../includes/sidebar.php'; ?>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu Utama</div>
            <a href="dashboard-guru.php" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="kelas-saya.php" class="sidebar-item">
                <i class="fas fa-users"></i>
                Kelas Saya
                <span class="badge">3</span>
            </a>
            <a href="pelajar-saya.php" class="sidebar-item active">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <span class="badge">85</span>
            </a>
            <a href="subjek-saya.php" class="sidebar-item">
                <i class="fas fa-book"></i>
                Subjek Saya
                <span class="badge">4</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan & Penilaian</div>
            <a href="tambah-markah.php" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="kemaskini-markah.php" class="sidebar-item">
                <i class="fas fa-edit"></i>
                Kemaskini Markah
            </a>
            <a href="semak-markah.php" class="sidebar-item">
                <i class="fas fa-search"></i>
                Semak Markah
            </a>
            <a href="laporan-prestasi.php" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Pengurusan</div>
            <a href="jadual-ujian.php" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                Jadual Ujian
            </a>
            <a href="tugasan.php" class="sidebar-item">
                <i class="fas fa-tasks"></i>
                Tugasan
                <span class="badge">12</span>
            </a>
            <a href="kehadiran.php" class="sidebar-item">
                <i class="fas fa-clipboard-check"></i>
                Kehadiran
            </a>
            <a href="komunikasi.php" class="sidebar-item">
                <i class="fas fa-comments"></i>
                Komunikasi Ibu Bapa
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Sistem</div>
            <a href="profil.php" class="sidebar-item">
                <i class="fas fa-user-cog"></i>
                Profil Saya
            </a>
            <a href="tetapan.php" class="sidebar-item">
                <i class="fas fa-cog"></i>
                Tetapan
            </a>
            <a href="bantuan-admin.php" class="sidebar-item">
                <i class="fas fa-question-circle"></i>
                Bantuan
            </a>
            <a href="#" class="sidebar-item" style="color: var(--danger);">
                <i class="fas fa-sign-out-alt"></i>
                Log Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pelajar Saya 👨‍🎓</h2>
                <p>Urus dan pantau semua pelajar yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-info" onclick="openImportModal()">
                    <i class="fas fa-file-import"></i>
                    Import
                </button>
                <button class="btn btn-primary" onclick="tambahPelajar()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Pelajar
                </button>
            </div>
        </div>

        <!-- Class Summary -->
        <div class="class-summary">
            <div class="summary-info">
                <h3>Jumlah Keseluruhan Pelajar</h3>
                <p>Semua kelas yang anda kendalikan</p>
            </div>
            <div class="summary-stats">
                <div class="summary-stat">
                    <div class="stat-number" id="totalStudents">85</div>
                    <div class="stat-label">Pelajar</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="activeStudents">82</div>
                    <div class="stat-label">Aktif</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="averagePerformance">78.9%</div>
                    <div class="stat-label">Prestasi Purata</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="attendanceRate">92.3%</div>
                    <div class="stat-label">Kehadiran</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar mengikut nama, ID, atau kelas..." onkeyup="searchStudents()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="filterStudents()">
                        <option value="">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                        <option value="4A">Kelas 4A</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="filterStudents()">
                        <option value="">Semua Tahun</option>
                        <option value="6">Tahun 6</option>
                        <option value="5">Tahun 5</option>
                        <option value="4">Tahun 4</option>
                        <option value="3">Tahun 3</option>
                        <option value="2">Tahun 2</option>
                        <option value="1">Tahun 1</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="filterStudents()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="graduated">Tamat</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Prestasi:</label>
                    <select class="filter-select" id="filterPerformance" onchange="filterStudents()">
                        <option value="">Semua Prestasi</option>
                        <option value="excellent">Cemerlang (≥85%)</option>
                        <option value="good">Baik (70-84%)</option>
                        <option value="average">Sederhana (60-69%)</option>
                        <option value="poor">Lemah (≤59%)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="bulk-select">
                <input type="checkbox" id="selectAllBulk" onchange="toggleAllBulk()">
                <label for="selectAllBulk" style="font-weight: 600; color: var(--dark-gray);">
                    <span id="selectedCount">0</span> pelajar dipilih
                </label>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="action-btn add" onclick="assignClassBulk()">
                    <i class="fas fa-users"></i>
                    Tugaskan Kelas
                </button>
                <button class="action-btn edit" onclick="updateStatusBulk()">
                    <i class="fas fa-edit"></i>
                    Kemaskini Status
                </button>
                <button class="action-btn delete" onclick="deleteStudentsBulk()">
                    <i class="fas fa-trash"></i>
                    Padam
                </button>
            </div>
        </div>

        <!-- Student Table -->
        <div class="student-table-container">
            <table id="studentTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll" onchange="toggleAllSelection()">
                        </th>
                        <th>PELAJAR</th>
                        <th>KELAS</th>
                        <th>TAHUN</th>
                        <th>JANTINA</th>
                        <th>PRESTASI</th>
                        <th>KEHADIRAN</th>
                        <th>STATUS</th>
                        <th style="width: 200px;">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Student rows will be loaded here -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-user-graduate"></i>
                <h3>Tiada Pelajar Ditemui</h3>
                <p>Tiada pelajar yang sepadan dengan carian atau penapis anda.</p>
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Penapis
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info" id="paginationInfo">
                Menunjukkan 1-10 daripada 85 pelajar
            </div>
            <div class="pagination-controls">
                <button class="page-btn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">5</button>
                <button class="page-btn" onclick="changePage('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const studentModal = document.getElementById('studentModal');
        const importModal = document.getElementById('importModal');
        const studentTableBody = document.getElementById('studentTableBody');
        const emptyState = document.getElementById('emptyState');
        const bulkActions = document.getElementById('bulkActions');
        const studentForm = document.getElementById('studentForm');

        // Current state
        let studentsData = [];
        let filteredStudents = [];
        let displayedStudents = [];
        let isEditingStudent = false;
        let currentStudentId = null;
        let selectedStudents = new Set();
        let currentPage = 1;
        const studentsPerPage = 10;

        // Sample data for students
        const sampleStudents = [
            {
                id: 'ST001',
                name: 'Ahmad bin Ali',
                ic: '080101-14-1234',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-01-01',
                phone: '012-345 6789',
                email: 'ahmad.ali@email.com',
                address: 'No. 123, Jalan Merdeka, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Ali bin Abu',
                relationship: 'father',
                medical: 'Tiada',
                performance: 85,
                attendance: 95,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST002',
                name: 'Siti binti Abu',
                ic: '080215-08-5678',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-02-15',
                phone: '013-456 7890',
                email: 'siti.abu@email.com',
                address: 'No. 456, Jalan Damai, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Abu bin Hassan',
                relationship: 'father',
                medical: 'Asma (ringan)',
                performance: 90,
                attendance: 98,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST003',
                name: 'Muhammad bin Hassan',
                ic: '080305-10-9012',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-03-05',
                phone: '014-567 8901',
                email: 'muhammad.hassan@email.com',
                address: 'No. 789, Jalan Sejahtera, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Hassan bin Ismail',
                relationship: 'father',
                medical: 'Tiada',
                performance: 78,
                attendance: 92,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST004',
                name: 'Aisha binti Kamal',
                ic: '080410-06-3456',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-04-10',
                phone: '015-678 9012',
                email: 'aisha.kamal@email.com',
                address: 'No. 101, Jalan Aman, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Kamal bin Yusuf',
                relationship: 'father',
                medical: 'Tiada',
                performance: 92,
                attendance: 96,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST005',
                name: 'Ali bin Omar',
                ic: '080512-12-7890',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-05-12',
                phone: '016-789 0123',
                email: 'ali.omar@email.com',
                address: 'No. 202, Jalan Bahagia, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Omar bin Ahmad',
                relationship: 'father',
                medical: 'Tiada',
                performance: 76,
                attendance: 89,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST006',
                name: 'Fatimah binti Yusuf',
                ic: '080623-08-1234',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-06-23',
                phone: '017-890 1234',
                email: 'fatimah.yusuf@email.com',
                address: 'No. 303, Jalan Makmur, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Yusuf bin Ali',
                relationship: 'father',
                medical: 'Tiada',
                performance: 88,
                attendance: 94,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST007',
                name: 'Ravi a/l Kumar',
                ic: '080701-14-5678',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-07-01',
                phone: '018-901 2345',
                email: 'ravi.kumar@email.com',
                address: 'No. 404, Jalan Ceria, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Kumar a/l Raj',
                relationship: 'father',
                medical: 'Tiada',
                performance: 72,
                attendance: 85,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST008',
                name: 'Priya a/p Raj',
                ic: '080815-06-9012',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-08-15',
                phone: '019-012 3456',
                email: 'priya.raj@email.com',
                address: 'No. 505, Jalan Indah, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Raj a/l Muthu',
                relationship: 'father',
                medical: 'Tiada',
                performance: 85,
                attendance: 90,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST009',
                name: 'Kumar a/l Muthu',
                ic: '080920-10-3456',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-09-20',
                phone: '011-123 4567',
                email: 'kumar.muthu@email.com',
                address: 'No. 606, Jalan Permai, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Muthu a/l Samy',
                relationship: 'father',
                medical: 'Tiada',
                performance: 68,
                attendance: 82,
                status: 'inactive',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST010',
                name: 'Mei Ling',
                ic: '081010-08-7890',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-10-10',
                phone: '012-234 5678',
                email: 'mei.ling@email.com',
                address: 'No. 707, Jalan Seri, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Ling Hock',
                relationship: 'father',
                medical: 'Tiada',
                performance: 90,
                attendance: 96,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST011',
                name: 'Wei Jian',
                ic: '081115-12-1234',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-11-15',
                phone: '013-345 6789',
                email: 'wei.jian@email.com',
                address: 'No. 808, Jalan Damai, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Jian Min',
                relationship: 'father',
                medical: 'Tiada',
                performance: 79,
                attendance: 88,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST012',
                name: 'Sofia binti David',
                ic: '081220-06-5678',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-12-20',
                phone: '014-456 7890',
                email: 'sofia.david@email.com',
                address: 'No. 909, Jalan Bahagia, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'David bin Joseph',
                relationship: 'father',
                medical: 'Tiada',
                performance: 82,
                attendance: 91,
                status: 'graduated',
                createdAt: '2023-01-15'
            }
        ];

     // Update initializePage function
function initializePage() {
    // Load data from server
    loadStudentsFromServer();
    
    // Set up event listeners
    setupEventListeners();
    
    // Initialize search and filter
    initializeFilters();
}

// Initialize filters with real data
function initializeFilters() {
    // Load class options from server
    fetch('?ajax=get_classes')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const classSelect = document.getElementById('filterClass');
            const yearSelect = document.getElementById('filterYear');
            
            // Clear existing options except the first one
            while (classSelect.options.length > 1) classSelect.remove(1);
            while (yearSelect.options.length > 1) yearSelect.remove(1);
            
            // Add class options
            const uniqueClasses = [...new Set(data.classes.map(c => c.nama))];
            uniqueClasses.forEach(className => {
                const option = document.createElement('option');
                option.value = className;
                option.textContent = `Kelas ${className}`;
                classSelect.appendChild(option);
            });
            
            // Add year options
            const uniqueYears = [...new Set(data.classes.map(c => c.tahun))].sort((a, b) => b - a);
            uniqueYears.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = `Tahun ${year}`;
                yearSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error loading filters:', error);
    });
}

// Load students from server
function loadStudentsFromServer() {
    showLoading(true);
    
    // Build query string from filters
    const search = document.getElementById('searchInput').value;
    const kelas = document.getElementById('filterClass').value;
    const tahun = document.getElementById('filterYear').value;
    const status = document.getElementById('filterStatus').value;
    const prestasi = document.getElementById('filterPerformance').value;
    
    let query = '?ajax=get_students';
    if (search) query += `&search=${encodeURIComponent(search)}`;
    if (kelas) query += `&kelas=${encodeURIComponent(kelas)}`;
    if (tahun) query += `&tahun=${encodeURIComponent(tahun)}`;
    if (status) query += `&status=${encodeURIComponent(status)}`;
    if (prestasi) query += `&prestasi=${encodeURIComponent(prestasi)}`;
    
    fetch(query, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            studentsData = data.students.map(student => formatStudentData(student));
            filteredStudents = [...studentsData];
            updateSummary(data.statistics);
            loadStudentTable();
            updatePaginationInfo();
        } else {
            showNotification('Gagal memuatkan data pelajar', 'error');
        }
        showLoading(false);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ralat sistem. Sila cuba lagi.', 'error');
        showLoading(false);
    });
}

// Format student data for frontend
function formatStudentData(dbStudent) {
    // Calculate attendance percentage
    const attendance = dbStudent.attendance_percentage || 
        (dbStudent.total_kehadiran > 0 ? 
            Math.round((dbStudent.jumlah_kehadiran / dbStudent.total_kehadiran) * 100) : 0);
    
    // Get gender text
    const genderText = dbStudent.jantina === 'L' ? 'Lelaki' : 'Perempuan';
    
    // Get status
    let status = 'active';
    let statusText = 'AKTIF';
    if (dbStudent.status === 0) {
        status = 'inactive';
        statusText = 'TIDAK AKTIF';
    } else if (dbStudent.status === 2) {
        status = 'graduated';
        statusText = 'TAMAT';
    }
    
    return {
        id: dbStudent.id.toString(),
        name: dbStudent.nama,
        ic: dbStudent.no_kp || '',
        class: dbStudent.kelas_nama || 'N/A',
        year: dbStudent.tahun ? `Tahun ${dbStudent.tahun}` : 'N/A',
        gender: dbStudent.jantina === 'L' ? 'male' : 'female',
        genderText: genderText,
        performance: parseFloat(dbStudent.prestasi_purata) || 0,
        attendance: attendance,
        status: status,
        statusText: statusText,
        student_id: dbStudent.student_id || dbStudent.id_kelas || '',
        kelas_nama: dbStudent.kelas_nama || '',
        tahun: dbStudent.tahun || ''
    };
}

// Update summary with real data
function updateSummary(statistics) {
    if (statistics) {
        document.getElementById('totalStudents').textContent = statistics.total_pelajar || 0;
        document.getElementById('activeStudents').textContent = statistics.pelajar_aktif || 0;
        document.getElementById('averagePerformance').textContent = (statistics.prestasi_purata || 0).toFixed(1) + '%';
        document.getElementById('attendanceRate').textContent = (statistics.kadar_kehadiran || 0).toFixed(1) + '%';
    }
}

// Update student table with real data
function loadStudentTable() {
    if (filteredStudents.length === 0) {
        studentTableBody.innerHTML = '';
        emptyState.style.display = 'block';
        bulkActions.style.display = 'none';
        return;
    }
    
    emptyState.style.display = 'none';
    
    // Calculate pagination
    const startIndex = (currentPage - 1) * studentsPerPage;
    const endIndex = startIndex + studentsPerPage;
    displayedStudents = filteredStudents.slice(startIndex, endIndex);
    
    studentTableBody.innerHTML = displayedStudents.map(student => {
        // Get initials for avatar
        const initials = student.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        
        // Determine performance class
        let performanceClass = '';
        let performanceWidth = '';
        if (student.performance >= 85) {
            performanceClass = 'performance-excellent';
            performanceWidth = '90%';
        } else if (student.performance >= 70) {
            performanceClass = 'performance-good';
            performanceWidth = '75%';
        } else if (student.performance >= 60) {
            performanceClass = 'performance-average';
            performanceWidth = '60%';
        } else {
            performanceClass = 'performance-poor';
            performanceWidth = '40%';
        }
        
        // Determine status badge
        let statusClass = '';
        let statusText = '';
        switch(student.status) {
            case 'active':
                statusClass = 'status-active';
                statusText = 'AKTIF';
                break;
            case 'inactive':
                statusClass = 'status-inactive';
                statusText = 'TIDAK AKTIF';
                break;
            case 'graduated':
                statusClass = 'status-graduated';
                statusText = 'TAMAT';
                break;
        }
        
        return `
            <tr>
                <td>
                    <input type="checkbox" class="student-checkbox" value="${student.id}" onchange="toggleStudentSelection('${student.id}')">
                </td>
                <td>
                    <div class="student-avatar-cell">
                        <div class="student-avatar">
                            ${initials}
                        </div>
                        <div class="student-info">
                            <div class="student-name">${student.name}</div>
                            <div class="student-id">${student.student_id}</div>
                        </div>
                    </div>
                </td>
                <td>${student.kelas_nama || student.class}</td>
                <td>${student.year}</td>
                <td>${student.genderText}</td>
                <td>
                    <div class="performance-cell">
                        <div class="performance-bar">
                            <div class="performance-fill ${performanceClass}" style="width: ${performanceWidth}"></div>
                        </div>
                        <div class="performance-value">${student.performance.toFixed(1)}%</div>
                    </div>
                </td>
                <td>${student.attendance}%</td>
                <td>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </td>
                <td>
                    <div class="action-cell">
                        <button class="action-btn view" onclick="viewStudent('${student.id}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                        <button class="action-btn edit" onclick="editStudent('${student.id}')">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="action-btn delete" onclick="deleteStudent('${student.id}', '${student.name.replace(/'/g, "\\'")}')">
                            <i class="fas fa-trash"></i>
                            Padam
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    
    // Update bulk actions visibility
    updateBulkActions();
}

// View student details
function viewStudent(studentId) {
    showLoading(true);
    
    fetch(`?ajax=get_student&student_id=${studentId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.student) {
            const student = data.student;
            
            // Calculate age if DOB exists
            let ageInfo = '';
            if (student.tarikh_lahir) {
                const dob = new Date(student.tarikh_lahir);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                ageInfo = `Umur: ${age} tahun\n`;
            }
            
            // Format DOB
            let dobInfo = '';
            if (student.tarikh_lahir) {
                const formattedDOB = new Date(student.tarikh_lahir).toLocaleDateString('ms-MY', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                dobInfo = `Tarikh Lahir: ${formattedDOB}\n`;
            }
            
            // Get gender text
            const genderText = student.jantina === 'L' ? 'Lelaki' : 'Perempuan';
            
            // Get status text
            let statusText = 'Aktif';
            if (student.status === 0) statusText = 'Tidak Aktif';
            else if (student.status === 2) statusText = 'Tamat';
            
            alert(`MAKLUMAT PELAJAR\n\n` +
                  `Nama: ${student.nama}\n` +
                  `No. KP: ${student.no_kp}\n` +
                  `ID Pelajar: ${student.id_kelas}\n` +
                  `${ageInfo}` +
                  `${dobInfo}` +
                  `Kelas: ${student.kelas_nama || 'N/A'}\n` +
                  `Tahun: ${student.tahun ? 'Tahun ' + student.tahun : 'N/A'}\n` +
                  `Jantina: ${genderText}\n` +
                  `Status: ${statusText}\n` +
                  `Prestasi Purata: ${student.prestasi_purata || 0}%\n` +
                  `Kehadiran: ${student.attendance_percentage || 0}%`);
        } else {
            showNotification('Gagal memuatkan maklumat pelajar', 'error');
        }
        showLoading(false);
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ralat sistem. Sila cuba lagi.', 'error');
        showLoading(false);
    });
}

// Search students
function searchStudents() {
    // Reload data from server with search filter
    loadStudentsFromServer();
}

// Filter students
function filterStudents() {
    // Reload data from server with all filters
    loadStudentsFromServer();
}

// Reset filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterClass').value = '';
    document.getElementById('filterYear').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPerformance').value = '';
    
    loadStudentsFromServer();
    showNotification('Semua penapis telah dikembalikan kepada tetapan asal', 'success');
}

// Bulk delete students
function deleteStudentsBulk() {
    if (selectedStudents.size === 0) return;
    
    if (confirm(`Adakah anda pasti ingin memadam ${selectedStudents.size} pelajar terpilih?`)) {
        showLoading(true);
        
        const formData = new FormData();
        formData.append('bulk_action', 'delete');
        Array.from(selectedStudents).forEach(id => {
            formData.append('student_ids[]', id);
        });
        
        fetch('?action=bulk_delete', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Ralat sistem. Sila cuba lagi.', 'error');
            showLoading(false);
        });
    }
}

// Bulk update status
function updateStatusBulk() {
    if (selectedStudents.size === 0) return;
    
    const newStatus = prompt('Masukkan status baru (active/inactive/graduated):');
    if (newStatus && ['active', 'inactive', 'graduated'].includes(newStatus)) {
        showLoading(true);
        
        const formData = new FormData();
        formData.append('bulk_action', 'update_status');
        formData.append('new_status', newStatus);
        Array.from(selectedStudents).forEach(id => {
            formData.append('student_ids[]', id);
        });
        
        fetch('?action=bulk_update', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.redirected) {
                window.location.href = response.url;
            } else {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Ralat sistem. Sila cuba lagi.', 'error');
            showLoading(false);
        });
    }
}

// Add new AJAX endpoint to pelajar-saya.php
// In your PHP code, add this to the AJAX handler:
/*
case 'get_classes':
    echo json_encode([
        'success' => true,
        'classes' => $all_kelas
    ]);
    exit;
*/

// Initialize page when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
});
    </script>
</body>
</html>