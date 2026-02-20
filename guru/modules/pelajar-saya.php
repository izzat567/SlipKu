<?php
session_start();
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../config/connect.php';

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login-guru.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'pelajar-saya.php';

// Include database functions
require_once __DIR__ . '/../includes/db_functions.php';

// Get guru info
$guru_info = getGuruById($guru_id);

// Get initials for avatar
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    $parts = explode(' ', $_SESSION['guru_nama']);
    foreach ($parts as $p) {
        if (!empty($p)) {
            $initials .= strtoupper(substr($p, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2);
}

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
$status_filter = $_GET['status'] ?? '';
$status = ($status_filter === 'active') ? 'aktif' : $status_filter;
$prestasi = $_GET['prestasi'] ?? '';

// Get data from database
$pelajar_list = getPelajarByGuru($guru_id, $search, $kelas, $status);
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
    global $conn;
    
    // Validate required fields
    $required_fields = ['nama', 'no_ic', 'jantina', 'id_kelas'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
            header('Location: pelajar-saya.php');
            exit();
        }
    }
    
    // Check if IC already exists
    if (checkStudentExists($_POST['no_ic'])) {
        $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    $data = [
        'nama' => trim($_POST['nama']),
        'no_ic' => trim($_POST['no_ic']),
        'jantina' => $_POST['jantina'],
        'id_kelas' => intval($_POST['id_kelas'])
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
        header('Location: pelajar-saya.php');
        exit();
    }
}

// Function to handle editing student
function handleEditStudent($id) {
    if (empty($id)) {
        $_SESSION['error_message'] = 'ID pelajar tidak sah.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    // Validate required fields
    $required_fields = ['nama', 'no_ic', 'jantina', 'id_kelas'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
            header('Location: pelajar-saya.php');
            exit();
        }
    }
    
    // Check if IC already exists (excluding current student)
    if (checkStudentExists($_POST['no_ic'], $id)) {
        $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    $data = [
        'nama' => trim($_POST['nama']),
        'no_ic' => trim($_POST['no_ic']),
        'jantina' => $_POST['jantina'],
        'id_kelas' => intval($_POST['id_kelas'])
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
        header('Location: pelajar-saya.php');
        exit();
    }
}

// Function to handle deleting student
function handleDeleteStudent($id) {
    if (empty($id)) {
        $_SESSION['error_message'] = 'ID pelajar tidak sah.';
        header('Location: pelajar-saya.php');
        exit();
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
        } elseif ($_POST['bulk_action'] === 'change_class' && isset($_POST['new_kelas'])) {
            $update_data = ['id_kelas' => intval($_POST['new_kelas'])];
            if (bulkUpdateStudents($student_ids, $update_data)) {
                $_SESSION['success_message'] = 'Kelas pelajar terpilih berjaya ditukar!';
            } else {
                $_SESSION['error_message'] = 'Gagal menukar kelas pelajar.';
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
    global $conn;
    
    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = 'Gagal memuat naik fail.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    $file = $_FILES['import_file'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, ['csv', 'xlsx', 'xls'])) {
        $_SESSION['error_message'] = 'Format fail tidak disokong. Sila gunakan CSV atau Excel.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    $success_count = 0;
    $error_count = 0;
    $errors = [];
    
    if ($file_ext === 'csv') {
        // Handle CSV import
        if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++;
                
                // Skip header row
                if ($row == 1) continue;
                
                // Check if row has enough columns
                if (count($data) < 4) {
                    $error_count++;
                    $errors[] = "Baris $row: Data tidak lengkap";
                    continue;
                }
                
                $nama = trim($data[0]);
                $no_kp = trim($data[1]);
                $jantina = trim(strtoupper($data[2]));
                $id_kelas = intval($data[3]);
                
                // Validate gender
                if ($jantina !== 'L' && $jantina !== 'P') {
                    $error_count++;
                    $errors[] = "Baris $row: Jantina mesti 'L' atau 'P'";
                    continue;
                }
                
                // Check if IC already exists
                if (checkStudentExists($no_kp)) {
                    $error_count++;
                    $errors[] = "Baris $row: No. KP '$no_kp' sudah wujud";
                    continue;
                }
                
                $student_data = [
                    'nama' => $nama,
                    'no_ic' => $no_kp,
                    'jantina' => $jantina,
                    'id_kelas' => $id_kelas,
                    'status' => 'aktif'
                ];
                
                if (tambahPelajar($student_data)) {
                    $success_count++;
                } else {
                    $error_count++;
                    $errors[] = "Baris $row: Gagal menyimpan data";
                }
            }
            fclose($handle);
        }
    } else {
        // For Excel files, we'll need PHPExcel or similar library
        // For now, show message
        $_SESSION['error_message'] = 'Sokongan untuk fail Excel akan ditambah kemudian. Sila gunakan fail CSV buat masa ini.';
        header('Location: pelajar-saya.php');
        exit();
    }
    
    if ($success_count > 0) {
        $_SESSION['success_message'] = "Berjaya import $success_count pelajar!";
        if ($error_count > 0) {
            $_SESSION['error_message'] = "$error_count pelajar gagal diimport. " . implode(", ", array_slice($errors, 0, 5));
        }
    } else {
        $_SESSION['error_message'] = 'Tiada pelajar berjaya diimport. ' . implode(", ", array_slice($errors, 0, 5));
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
            
        case 'get_classes':
            echo json_encode([
                'success' => true,
                'classes' => $all_kelas
            ]);
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

        .sidebar.sidebar-active {
            transform: translateX(0);
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

        /* Import Section */
        .import-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: none;
        }

        .import-section.active {
            display: block;
        }

        .import-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .import-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .import-header h3 i {
            color: var(--success);
        }

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
        }

        .performance-good {
            background: #3b82f6;
        }

        .performance-average {
            background: var(--warning);
        }

        .performance-poor {
            background: var(--danger);
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

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-btn.disabled:hover {
            background: var(--white);
            border-color: #e5e7eb;
        }

        .page-ellipsis {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--medium-gray);
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

        /* Alert styles */
        .alert {
            position: fixed;
            top: 100px;
            right: 30px;
            z-index: 10000;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 400px;
        }
        
        .alert-success {
            background: var(--success);
            color: white;
        }
        
        .alert-error {
            background: var(--danger);
            color: white;
        }
        
        .alert-warning {
            background: var(--warning);
            color: white;
        }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Loading overlay */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        #loadingOverlay.active {
            display: flex;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid var(--primary-light);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        /* Student details modal */
        .student-details-modal {
            padding: 20px;
        }
        
        .student-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .student-avatar-lg {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }
        
        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .info-item {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 12px;
        }
        
        .info-label {
            display: block;
            font-size: 12px;
            color: var(--medium-gray);
            margin-bottom: 5px;
        }
        
        .info-value {
            font-weight: 600;
            color: var(--dark-gray);
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
    <!-- Loading Overlay -->
    <div id="loadingOverlay">
        <div style="text-align: center;">
            <div class="loading-spinner"></div>
            <p style="color: var(--primary); font-weight: 600;">Memuatkan...</p>
        </div>
    </div>

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
                                <option value="L" <?= (isset($student) && $student['jantina'] == 'L') ? 'selected' : '' ?>>Lelaki</option>
                                <option value="P" <?= (isset($student) && $student['jantina'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Kelas</label>
                            <select class="form-select" id="studentKelas" name="id_kelas" required>
                                <option value="">Pilih Kelas</option>
                                <?php foreach ($kelas_guru as $kelas): ?>
                                <option value="<?= $kelas['id'] ?>" <?= (isset($student) && $student['id_kelas'] == $kelas['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kelas['nama']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="studentStatus" name="status">
                            <option value="active" <?= (isset($student) && $student['status'] == 1) ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= (isset($student) && $student['status'] == 0) ? 'selected' : '' ?>>Tidak Aktif</option>
                            <option value="graduated" <?= (isset($student) && $student['status'] == 2) ? 'selected' : '' ?>>Tamat</option>
                        </select>
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

    <!-- Modal for Import Students -->
    <div class="modal" id="importModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Import Pelajar dari Fail</h3>
                <button class="modal-close" onclick="closeImportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="import-section active">
                    <div class="import-header">
                        <h3><i class="fas fa-file-upload"></i> Muat Naik Fail</h3>
                        <a href="download_template.php" class="btn btn-secondary btn-sm" style="padding: 8px 15px; font-size: 12px;">
                            <i class="fas fa-download"></i> Muat Turun Template
                        </a>
                    </div>
                    
                    <form id="importForm" method="POST" enctype="multipart/form-data" action="?action=import">
                        <div class="file-upload" onclick="document.getElementById('importFile').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p><strong>Klik untuk pilih fail atau seret ke sini</strong></p>
                            <p>Format yang disokong: .csv</p>
                            <span>Saiz maksimum: 10MB</span>
                        </div>
                        <input type="file" id="importFile" name="import_file" accept=".csv" style="display: none;" onchange="showFileName()" required>
                        
                        <div style="margin: 20px 0; padding: 20px; background: var(--light-gray); border-radius: 12px;">
                            <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--primary);">
                                <i class="fas fa-info-circle"></i> Panduan Import
                            </h4>
                            <p style="font-size: 13px; color: var(--medium-gray); line-height: 1.6;">
                                <strong>Format fail CSV:</strong><br>
                                Nama,No_KP,Jantina,ID_Kelas<br>
                                <strong>Contoh:</strong><br>
                                Ahmad bin Ali,030101-14-1234,L,1<br>
                                Siti binti Abu,030102-02-5678,P,1<br><br>
                                <strong>Nota:</strong>
                                <br>- Jantina: L untuk Lelaki, P untuk Perempuan
                                <br>- ID_Kelas: Rujuk senarai kelas dalam sistem
                            </p>
                        </div>
                        
                        <div style="display: flex; gap: 15px; justify-content: flex-end;">
                            <button type="button" class="btn btn-secondary" onclick="closeImportModal()">Batal</button>
                            <button type="submit" class="btn btn-success" id="importBtn">
                                <i class="fas fa-upload"></i> Mula Import
                            </button>
                        </div>
                    </form>
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
            <a href="../dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Pelajar Saya</p>
                </div>
            </a>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar"><?php echo $initials ?: 'GU'; ?></div>
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?></h4>
                    <p><?php echo isset($_SESSION['guru_role']) ? htmlspecialchars($_SESSION['guru_role']) : 'Guru'; ?></p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pelajar Saya 👨‍🎓</h2>
                <p>Urus dan pantau semua pelajar yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="openImportModal()">
                    <i class="fas fa-upload"></i>
                    Import
                </button>
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
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
                    <div class="stat-number" id="totalStudents">0</div>
                    <div class="stat-label">Pelajar</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="activeStudents">0</div>
                    <div class="stat-label">Aktif</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="averagePerformance">0%</div>
                    <div class="stat-label">Prestasi Purata</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="attendanceRate">0%</div>
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
                        <?php foreach ($kelas_guru as $kelas): ?>
                        <option value="<?= htmlspecialchars($kelas['nama']) ?>"><?= htmlspecialchars($kelas['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="filterStudents()">
                        <option value="">Semua Tahun</option>
                        <?php 
                        $years = [];
                        foreach ($kelas_guru as $kelas) {
                            if (!in_array($kelas['tahun'], $years)) {
                                $years[] = $kelas['tahun'];
                            }
                        }
                        sort($years);
                        foreach ($years as $year): 
                        ?>
                        <option value="<?= $year ?>">Tahun <?= $year ?></option>
                        <?php endforeach; ?>
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

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="bulk-select">
                <span id="selectedCount">0</span> pelajar dipilih
            </div>
            <select class="filter-select" id="bulkAction">
                <option value="">Pilih Tindakan</option>
                <option value="update_status">Kemaskini Status</option>
                <option value="change_class">Tukar Kelas</option>
                <option value="delete">Padam</option>
            </select>
            <select class="filter-select" id="bulkValue" style="display: none;">
                <option value="">Pilih Nilai</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
                <option value="graduated">Tamat</option>
            </select>
            <select class="filter-select" id="bulkClass" style="display: none;">
                <option value="">Pilih Kelas</option>
                <?php foreach ($kelas_guru as $kelas): ?>
                <option value="<?= $kelas['id'] ?>"><?= htmlspecialchars($kelas['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary" onclick="applyBulkAction()">Guna</button>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info" id="paginationInfo">
                Memuatkan data...
            </div>
            <div class="pagination-controls" id="paginationControls">
                <!-- Pagination buttons will be loaded here -->
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
        const studentForm = document.getElementById('studentForm');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        const bulkAction = document.getElementById('bulkAction');
        const bulkValue = document.getElementById('bulkValue');
        const bulkClass = document.getElementById('bulkClass');

        // Current state
        let studentsData = [];
        let filteredStudents = [];
        let displayedStudents = [];
        let currentPage = 1;
        const studentsPerPage = 10;
        let selectedStudents = [];

        // Initialize page
        function initializePage() {
            // Load data from server
            loadStudentsFromServer();
            
            // Setup event listeners
            setupEventListeners();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Toggle sidebar
            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    closeModal();
                    closeImportModal();
                }
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
            
            // Bulk action change
            if (bulkAction) {
                bulkAction.addEventListener('change', function() {
                    const action = this.value;
                    if (bulkValue) bulkValue.style.display = action === 'update_status' ? 'inline-block' : 'none';
                    if (bulkClass) bulkClass.style.display = action === 'change_class' ? 'inline-block' : 'none';
                });
            }
        }

        // Toggle Sidebar
        function toggleSidebar() {
            if (sidebar) {
                sidebar.classList.toggle('sidebar-active');
            }
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('active');
            }
            if (mainContent) {
                mainContent.classList.toggle('full-width');
            }
            document.body.style.overflow = sidebar && sidebar.classList.contains('sidebar-active') ? 'hidden' : '';
        }

        // Close Sidebar on Mobile
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                if (sidebar) sidebar.classList.remove('sidebar-active');
                if (sidebarOverlay) sidebarOverlay.classList.remove('active');
                if (mainContent) mainContent.classList.remove('full-width');
                document.body.style.overflow = '';
            }
        }

        // Load students from server
        function loadStudentsFromServer() {
            showLoading(true);
            
            // Build query string from filters
            const search = document.getElementById('searchInput')?.value || '';
            const kelas = document.getElementById('filterClass')?.value || '';
            const tahun = document.getElementById('filterYear')?.value || '';
            const status = document.getElementById('filterStatus')?.value || '';
            const prestasi = document.getElementById('filterPerformance')?.value || '';
            
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
            
            // Calculate performance (demo data for now)
            const performance = Math.floor(Math.random() * 30) + 60;
            
            return {
                id: dbStudent.id.toString(),
                name: dbStudent.nama,
                ic: dbStudent.no_kp || '',
                class: dbStudent.kelas_nama || 'N/A',
                year: dbStudent.tahun ? `Tahun ${dbStudent.tahun}` : 'N/A',
                gender: dbStudent.jantina === 'L' ? 'male' : 'female',
                genderText: genderText,
                performance: performance,
                status: status,
                statusText: statusText,
                student_id: dbStudent.id_kelas || '',
                kelas_nama: dbStudent.kelas_nama || '',
                tahun: dbStudent.tahun || ''
            };
        }

        // Update summary with real data
        function updateSummary(statistics) {
            if (statistics) {
                const totalStudents = document.getElementById('totalStudents');
                const activeStudents = document.getElementById('activeStudents');
                const averagePerformance = document.getElementById('averagePerformance');
                const attendanceRate = document.getElementById('attendanceRate');
                
                if (totalStudents) totalStudents.textContent = statistics.total_pelajar || 0;
                if (activeStudents) activeStudents.textContent = statistics.pelajar_aktif || 0;
                if (averagePerformance) averagePerformance.textContent = (statistics.prestasi_purata || 0).toFixed(1) + '%';
                if (attendanceRate) attendanceRate.textContent = (statistics.kadar_kehadiran || 0).toFixed(1) + '%';
            }
        }

        // Update student table with real data
        function loadStudentTable() {
            if (filteredStudents.length === 0) {
                studentTableBody.innerHTML = '';
                if (emptyState) emptyState.style.display = 'block';
                return;
            }
            
            if (emptyState) emptyState.style.display = 'none';
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * studentsPerPage;
            const endIndex = startIndex + studentsPerPage;
            displayedStudents = filteredStudents.slice(startIndex, endIndex);
            
            if (studentTableBody) {
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
                    switch(student.status) {
                        case 'active':
                            statusClass = 'status-active';
                            break;
                        case 'inactive':
                            statusClass = 'status-inactive';
                            break;
                        case 'graduated':
                            statusClass = 'status-graduated';
                            break;
                    }
                    
                    const isSelected = selectedStudents.includes(student.id);
                    
                    return `
                        <tr>
                            <td>
                                <input type="checkbox" class="student-checkbox" value="${student.id}" 
                                       ${isSelected ? 'checked' : ''} onchange="toggleStudentSelection('${student.id}')">
                            </td>
                            <td>
                                <div class="student-avatar-cell">
                                    <div class="student-avatar">
                                        ${initials}
                                    </div>
                                    <div class="student-info">
                                        <div class="student-name">${student.name}</div>
                                        <div class="student-id">${student.ic}</div>
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
                            <td>
                                <span class="status-badge ${statusClass}">${student.statusText}</span>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <button class="action-btn view" onclick="viewStudent('${student.id}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn edit" onclick="editStudent('${student.id}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteStudent('${student.id}', '${student.name.replace(/'/g, "\\'")}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                }).join('');
            }
        }

        // Update pagination info
        function updatePaginationInfo() {
            const total = filteredStudents.length;
            const totalPages = Math.ceil(total / studentsPerPage);
            
            if (total === 0) {
                document.getElementById('paginationInfo').innerHTML = 'Tiada pelajar';
                document.getElementById('paginationControls').innerHTML = '';
                return;
            }
            
            const start = (currentPage - 1) * studentsPerPage + 1;
            const end = Math.min(currentPage * studentsPerPage, total);
            
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo) {
                paginationInfo.innerHTML = `Menunjukkan ${start}-${end} daripada ${total} pelajar`;
            }
            
            // Update pagination controls
            const pageControls = document.getElementById('paginationControls');
            if (pageControls) {
                let controls = '';
                
                // Previous button
                controls += `<button class="page-btn ${currentPage === 1 ? 'disabled' : ''}" 
                                   onclick="changePage('prev')" ${currentPage === 1 ? 'disabled' : ''}>
                                <i class="fas fa-chevron-left"></i>
                             </button>`;
                
                // Page numbers
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }
                
                if (startPage > 1) {
                    controls += `<button class="page-btn" onclick="goToPage(1)">1</button>`;
                    if (startPage > 2) {
                        controls += `<span class="page-ellipsis">...</span>`;
                    }
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    controls += `<button class="page-btn ${i === currentPage ? 'active' : ''}" 
                                       onclick="goToPage(${i})">${i}</button>`;
                }
                
                if (endPage < totalPages) {
                    if (endPage < totalPages - 1) {
                        controls += `<span class="page-ellipsis">...</span>`;
                    }
                    controls += `<button class="page-btn" onclick="goToPage(${totalPages})">${totalPages}</button>`;
                }
                
                // Next button
                controls += `<button class="page-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                                   onclick="changePage('next')" ${currentPage === totalPages ? 'disabled' : ''}>
                                <i class="fas fa-chevron-right"></i>
                             </button>`;
                
                pageControls.innerHTML = controls;
            }
        }

        // Change page function
        function changePage(direction) {
            const totalPages = Math.ceil(filteredStudents.length / studentsPerPage);
            
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next' && currentPage < totalPages) {
                currentPage++;
            }
            
            loadStudentTable();
            updatePaginationInfo();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Go to specific page
        function goToPage(pageNumber) {
            currentPage = pageNumber;
            loadStudentTable();
            updatePaginationInfo();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Search students function
        function searchStudents() {
            currentPage = 1;
            filterStudents();
        }

        // Filter students function
        function filterStudents() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const kelasFilter = document.getElementById('filterClass')?.value || '';
            const tahunFilter = document.getElementById('filterYear')?.value || '';
            const statusFilter = document.getElementById('filterStatus')?.value || '';
            const prestasiFilter = document.getElementById('filterPerformance')?.value || '';
            
            filteredStudents = studentsData.filter(student => {
                // Search filter
                let matchesSearch = true;
                if (searchTerm) {
                    matchesSearch = student.name.toLowerCase().includes(searchTerm) ||
                                   (student.ic && student.ic.includes(searchTerm)) ||
                                   student.class.toLowerCase().includes(searchTerm) ||
                                   (student.student_id && student.student_id.toLowerCase().includes(searchTerm));
                }
                
                // Class filter
                let matchesKelas = true;
                if (kelasFilter) {
                    matchesKelas = student.class.toLowerCase().includes(kelasFilter.toLowerCase()) ||
                                  (student.kelas_nama && student.kelas_nama.toLowerCase().includes(kelasFilter.toLowerCase()));
                }
                
                // Year filter
                let matchesTahun = true;
                if (tahunFilter) {
                    matchesTahun = student.year.includes(tahunFilter) ||
                                  student.tahun == tahunFilter;
                }
                
                // Status filter
                let matchesStatus = true;
                if (statusFilter) {
                    matchesStatus = student.status === statusFilter;
                }
                
                // Performance filter
                let matchesPrestasi = true;
                if (prestasiFilter) {
                    switch(prestasiFilter) {
                        case 'excellent':
                            matchesPrestasi = student.performance >= 85;
                            break;
                        case 'good':
                            matchesPrestasi = student.performance >= 70 && student.performance < 85;
                            break;
                        case 'average':
                            matchesPrestasi = student.performance >= 60 && student.performance < 70;
                            break;
                        case 'poor':
                            matchesPrestasi = student.performance < 60;
                            break;
                    }
                }
                
                return matchesSearch && matchesKelas && matchesTahun && matchesStatus && matchesPrestasi;
            });
            
            currentPage = 1;
            loadStudentTable();
            updatePaginationInfo();
        }

        // Reset filters function
        function resetFilters() {
            const searchInput = document.getElementById('searchInput');
            const filterClass = document.getElementById('filterClass');
            const filterYear = document.getElementById('filterYear');
            const filterStatus = document.getElementById('filterStatus');
            const filterPerformance = document.getElementById('filterPerformance');
            
            if (searchInput) searchInput.value = '';
            if (filterClass) filterClass.value = '';
            if (filterYear) filterYear.value = '';
            if (filterStatus) filterStatus.value = '';
            if (filterPerformance) filterPerformance.value = '';
            
            currentPage = 1;
            filteredStudents = [...studentsData];
            loadStudentTable();
            updatePaginationInfo();
        }

        // Add new student function
        function tambahPelajar() {
            const modalTitle = document.getElementById('modalTitle');
            const studentForm = document.getElementById('studentForm');
            const currentStudentId = document.getElementById('currentStudentId');
            const icError = document.getElementById('icError');
            const submitBtn = document.getElementById('submitBtn');
            
            if (modalTitle) modalTitle.textContent = 'Tambah Pelajar Baru';
            if (studentForm) studentForm.action = '?action=add';
            if (studentForm) studentForm.reset();
            if (currentStudentId) currentStudentId.value = '';
            if (icError) icError.style.display = 'none';
            
            // Enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Simpan Pelajar';
            }
            
            if (studentModal) {
                studentModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Edit student function
        function editStudent(studentId) {
            const student = studentsData.find(s => s.id === studentId);
            if (!student) return;
            
            const modalTitle = document.getElementById('modalTitle');
            const studentForm = document.getElementById('studentForm');
            const studentName = document.getElementById('studentName');
            const studentIC = document.getElementById('studentIC');
            const studentGender = document.getElementById('studentGender');
            const studentKelas = document.getElementById('studentKelas');
            const studentStatus = document.getElementById('studentStatus');
            const currentStudentId = document.getElementById('currentStudentId');
            const icError = document.getElementById('icError');
            const submitBtn = document.getElementById('submitBtn');
            
            if (modalTitle) modalTitle.textContent = 'Edit Pelajar';
            if (studentForm) studentForm.action = `?action=edit&id=${studentId}`;
            
            // Populate form fields
            if (studentName) studentName.value = student.name;
            if (studentIC) studentIC.value = student.ic;
            
            // Set gender
            if (studentGender) {
                for (let i = 0; i < studentGender.options.length; i++) {
                    if (studentGender.options[i].value === (student.gender === 'male' ? 'L' : 'P')) {
                        studentGender.selectedIndex = i;
                        break;
                    }
                }
            }
            
            // Set kelas (need to find kelas id from name)
            if (studentKelas && student.kelas_nama) {
                for (let i = 0; i < studentKelas.options.length; i++) {
                    if (studentKelas.options[i].text === student.kelas_nama) {
                        studentKelas.selectedIndex = i;
                        break;
                    }
                }
            }
            
            // Set status
            if (studentStatus) {
                for (let i = 0; i < studentStatus.options.length; i++) {
                    if (studentStatus.options[i].value === student.status) {
                        studentStatus.selectedIndex = i;
                        break;
                    }
                }
            }
            
            if (currentStudentId) currentStudentId.value = studentId;
            if (icError) icError.style.display = 'none';
            
            // Enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kemaskini Pelajar';
            }
            
            if (studentModal) {
                studentModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // View student details
        function viewStudent(studentId) {
            const student = studentsData.find(s => s.id === studentId);
            if (!student) return;
            
            // Show student details in a modal or alert
            const detailsHtml = `
                <div class="student-details-modal">
                    <div class="student-header">
                        <div class="student-avatar-lg">${student.name.substring(0, 2).toUpperCase()}</div>
                        <div>
                            <h3>${student.name}</h3>
                            <p>${student.ic}</p>
                        </div>
                    </div>
                    
                    <div class="student-info-grid">
                        <div class="info-item">
                            <span class="info-label">Kelas:</span>
                            <span class="info-value">${student.class}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tahun:</span>
                            <span class="info-value">${student.year}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jantina:</span>
                            <span class="info-value">${student.genderText}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value">${student.statusText}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Prestasi:</span>
                            <span class="info-value">${student.performance.toFixed(1)}%</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Create modal for details
            const detailsModal = document.createElement('div');
            detailsModal.className = 'modal';
            detailsModal.innerHTML = `
                <div class="modal-content" style="max-width: 500px;">
                    <div class="modal-header">
                        <h3>Butiran Pelajar</h3>
                        <button class="modal-close" onclick="this.closest('.modal').remove(); document.body.style.overflow=''">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        ${detailsHtml}
                    </div>
                </div>
            `;
            
            document.body.appendChild(detailsModal);
            detailsModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Delete student function
        function deleteStudent(studentId, studentName) {
            if (confirm(`Adakah anda pasti ingin memadam pelajar: ${studentName}?\n\nTindakan ini tidak boleh dibatalkan.`)) {
                showLoading(true);
                
                fetch(`?ajax=delete_student&student_id=${studentId}`)
                    .then(response => response.json())
                    .then(data => {
                        showLoading(false);
                        
                        if (data.success) {
                            showNotification(data.message || 'Pelajar berjaya dipadam', 'success');
                            
                            // Remove from arrays
                            studentsData = studentsData.filter(s => s.id !== studentId);
                            filteredStudents = filteredStudents.filter(s => s.id !== studentId);
                            selectedStudents = selectedStudents.filter(id => id !== studentId);
                            
                            // Reload table
                            loadStudentTable();
                            updatePaginationInfo();
                            updateBulkActions();
                        } else {
                            showNotification(data.message || 'Gagal memadam pelajar', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showLoading(false);
                        showNotification('Ralat sistem. Sila cuba lagi.', 'error');
                    });
            }
        }

        // Check if IC exists
        function checkICExists() {
            const icInput = document.getElementById('studentIC');
            const icValue = icInput?.value.trim();
            const currentStudentId = document.getElementById('currentStudentId')?.value;
            const icError = document.getElementById('icError');
            const submitBtn = document.getElementById('submitBtn');
            
            if (!icValue) return;
            
            // Simple IC validation
            const icPattern = /^\d{6}-\d{2}-\d{4}$/;
            if (!icPattern.test(icValue)) {
                if (icError) {
                    icError.textContent = 'Format No. KP tidak sah. Contoh: 030101-14-1234';
                    icError.style.display = 'block';
                }
                if (submitBtn) submitBtn.disabled = true;
                return;
            }
            
            showLoading(true);
            
            let url = `?ajax=check_ic&no_ic=${encodeURIComponent(icValue)}`;
            if (currentStudentId) {
                url += `&exclude_id=${currentStudentId}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    showLoading(false);
                    
                    if (data.exists) {
                        if (icError) {
                            icError.textContent = 'No. Kad Pengenalan sudah wujud dalam sistem';
                            icError.style.display = 'block';
                        }
                        if (submitBtn) submitBtn.disabled = true;
                    } else {
                        if (icError) icError.style.display = 'none';
                        if (submitBtn) submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showLoading(false);
                    if (icError) icError.style.display = 'none';
                    if (submitBtn) submitBtn.disabled = false;
                });
        }

        // Toggle all student selection
        function toggleAllSelection() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const checked = selectAllCheckbox?.checked;
            
            selectedStudents = [];
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = checked;
                if (checked) {
                    selectedStudents.push(checkbox.value);
                }
            });
            
            updateBulkActions();
        }

        // Toggle individual student selection
        function toggleStudentSelection(studentId) {
            const checkbox = document.querySelector(`.student-checkbox[value="${studentId}"]`);
            const selectAllCheckbox = document.getElementById('selectAll');
            
            if (checkbox.checked) {
                if (!selectedStudents.includes(studentId)) {
                    selectedStudents.push(studentId);
                }
            } else {
                selectedStudents = selectedStudents.filter(id => id !== studentId);
            }
            
            // Update "select all" checkbox state
            const allCheckboxes = document.querySelectorAll('.student-checkbox');
            const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = !allChecked && selectedStudents.length > 0 && selectedStudents.length < allCheckboxes.length;
            }
            
            updateBulkActions();
        }

        // Update bulk actions display
        function updateBulkActions() {
            if (!bulkActions) return;
            
            if (selectedStudents.length > 0) {
                bulkActions.style.display = 'flex';
                if (selectedCount) selectedCount.textContent = selectedStudents.length;
            } else {
                bulkActions.style.display = 'none';
            }
        }

        // Apply bulk action
        function applyBulkAction() {
            const action = bulkAction?.value;
            
            if (!action || selectedStudents.length === 0) {
                showNotification('Sila pilih tindakan dan pastikan pelajar dipilih', 'warning');
                return;
            }
            
            if (action === 'delete') {
                if (confirm(`Adakah anda pasti ingin memadam ${selectedStudents.length} pelajar terpilih?`)) {
                    showLoading(true);
                    
                    // Create form data
                    const formData = new FormData();
                    selectedStudents.forEach(id => formData.append('student_ids[]', id));
                    formData.append('action', 'bulk_delete');
                    
                    fetch('?action=bulk_delete', {
                        method: 'POST',
                        body: formData
                    })
                    .then(() => {
                        showLoading(false);
                        showNotification('Memadam pelajar...', 'info');
                        setTimeout(() => {
                            loadStudentsFromServer();
                            selectedStudents = [];
                            updateBulkActions();
                        }, 1000);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showLoading(false);
                        showNotification('Ralat sistem', 'error');
                    });
                }
            } else if (action === 'update_status') {
                const newStatus = bulkValue?.value;
                if (!newStatus) {
                    showNotification('Sila pilih status baru', 'warning');
                    return;
                }
                
                showLoading(true);
                
                // Create form data
                const formData = new FormData();
                selectedStudents.forEach(id => formData.append('student_ids[]', id));
                formData.append('action', 'bulk_update');
                formData.append('bulk_action', 'update_status');
                formData.append('new_status', newStatus);
                
                fetch('?action=bulk_update', {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    showLoading(false);
                    showNotification('Mengemaskini status pelajar...', 'info');
                    setTimeout(() => {
                        loadStudentsFromServer();
                        selectedStudents = [];
                        updateBulkActions();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showLoading(false);
                    showNotification('Ralat sistem', 'error');
                });
            } else if (action === 'change_class') {
                const newClass = bulkClass?.value;
                if (!newClass) {
                    showNotification('Sila pilih kelas baru', 'warning');
                    return;
                }
                
                showLoading(true);
                
                // Create form data
                const formData = new FormData();
                selectedStudents.forEach(id => formData.append('student_ids[]', id));
                formData.append('action', 'bulk_update');
                formData.append('bulk_action', 'change_class');
                formData.append('new_kelas', newClass);
                
                fetch('?action=bulk_update', {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    showLoading(false);
                    showNotification('Menukar kelas pelajar...', 'info');
                    setTimeout(() => {
                        loadStudentsFromServer();
                        selectedStudents = [];
                        updateBulkActions();
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showLoading(false);
                    showNotification('Ralat sistem', 'error');
                });
            }
        }

        // Open import modal
        function openImportModal() {
            if (importModal) {
                importModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Close import modal
        function closeImportModal() {
            if (importModal) {
                importModal.classList.remove('active');
            }
            document.body.style.overflow = '';
        }

        // Show file name when selected
        function showFileName() {
            const fileInput = document.getElementById('importFile');
            const fileUploadDiv = document.querySelector('.file-upload p:first-of-type');
            
            if (fileInput && fileInput.files.length > 0 && fileUploadDiv) {
                fileUploadDiv.innerHTML = `<strong>Fail dipilih: ${fileInput.files[0].name}</strong>`;
            }
        }

        // Reload data
        function muatSemulaData() {
            currentPage = 1;
            loadStudentsFromServer();
            selectedStudents = [];
            updateBulkActions();
            showNotification('Data pelajar dimuat semula', 'success');
        }

        // Close modal function
        function closeModal() {
            if (studentModal) {
                studentModal.classList.remove('active');
            }
            document.body.style.overflow = '';
        }

        // Show loading overlay
        function showLoading(show) {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                if (show) {
                    loadingOverlay.classList.add('active');
                } else {
                    setTimeout(() => {
                        loadingOverlay.classList.remove('active');
                    }, 300);
                }
            }
        }

        // Show notification
        function showNotification(message, type) {
            // Remove existing notification
            const existingAlert = document.querySelector('.alert');
            if (existingAlert) existingAlert.remove();
            
            // Create new notification
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => {
                        if (alertDiv.parentNode) alertDiv.parentNode.removeChild(alertDiv);
                    }, 300);
                }
            }, 5000);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
            
            // Show success/error messages from PHP session
            <?php if (!empty($success_message)): ?>
                showNotification('<?= addslashes($success_message) ?>', 'success');
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                showNotification('<?= addslashes($error_message) ?>', 'error');
            <?php endif; ?>
        });
    </script>
</body>
</html>