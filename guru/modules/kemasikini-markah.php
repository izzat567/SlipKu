<?php
// kemasikini-markah.php - FIXED VERSION
session_start();
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config/connect.php';

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['guru_id'])) {
    header('Location: ../login-guru.php'); exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'kemasikini-markah.php';

require_once __DIR__ . '/../includes/db_functions.php';

$guru_info = getGuruById($guru_id);
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    foreach (explode(' ', $_SESSION['guru_nama']) as $p)
        if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
    $initials = substr($initials, 0, 2);
}

// AJAX: GET MARKS
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_marks') {
    header('Content-Type: application/json');
    $peperiksaan_id = intval($_GET['peperiksaan_id'] ?? 0);
    $kelas_id = intval($_GET['kelas_id'] ?? 0);
    
    if (!$peperiksaan_id && !$kelas_id) {
        // Return all marks for this guru's classes
        $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                    p.nama as nama_pelajar, p.no_kp,
                    k.nama as nama_kelas, k.id as id_kelas,
                    pp.nama_peperiksaan, pp.id as id_peperiksaan,
                    COALESCE(mp.nama, '-') as nama_subjek
                FROM markah m
                JOIN pelajar p ON m.id_pelajar = p.id
                JOIN kelas k ON p.id_kelas = k.id
                JOIN peperiksaan pp ON m.id_perperiksaan = pp.id
                LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                WHERE m.status = 'aktif'
                AND (p.status = 'aktif' OR p.status = '1')
                AND (
                    k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                    OR k.id_guru = ?
                )
                ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $guru_id, $guru_id);
    } elseif ($peperiksaan_id) {
        $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                    p.nama as nama_pelajar, p.no_kp,
                    k.nama as nama_kelas, k.id as id_kelas,
                    pp.nama_peperiksaan, pp.id as id_peperiksaan,
                    COALESCE(mp.nama, '-') as nama_subjek
                FROM markah m
                JOIN pelajar p ON m.id_pelajar = p.id
                JOIN kelas k ON p.id_kelas = k.id
                JOIN peperiksaan pp ON m.id_perperiksaan = pp.id
                LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                WHERE m.id_perperiksaan = ? AND m.status = 'aktif'
                ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $peperiksaan_id);
    } else {
        $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                    p.nama as nama_pelajar, p.no_kp,
                    k.nama as nama_kelas, k.id as id_kelas,
                    pp.nama_peperiksaan, pp.id as id_peperiksaan,
                    COALESCE(mp.nama, '-') as nama_subjek
                FROM markah m
                JOIN pelajar p ON m.id_pelajar = p.id
                JOIN kelas k ON p.id_kelas = k.id
                JOIN peperiksaan pp ON m.id_perperiksaan = pp.id
                LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                WHERE p.id_kelas = ? AND m.status = 'aktif'
                ORDER BY p.nama ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $kelas_id);
    }
    
    if ($stmt) {
        $stmt->execute();
        $marks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $marks = [];
    }
    
    echo json_encode(['success' => true, 'marks' => $marks]);
    exit();
}

// AJAX: SAVE MARK UPDATE
if (isset($_POST['ajax']) && $_POST['ajax'] === 'update_mark') {
    header('Content-Type: application/json');
    $mark_id = intval($_POST['mark_id'] ?? 0);
    $markah_baru = intval($_POST['markah_baru'] ?? 0);
    $today = date('Y-m-d');
    $gred = calculateGrade($markah_baru);
    
    if (!$mark_id) {
        echo json_encode(['success' => false, 'message' => 'ID markah tidak sah']); exit();
    }
    
    $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, tarikh_kemaskini=? WHERE id=?");
    $stmt->bind_param("issi", $markah_baru, $gred, $today, $mark_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Markah berjaya dikemaskini', 'gred' => $gred]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ralat: ' . $conn->error]);
    }
    $stmt->close();
    exit();
}

// AJAX: SAVE BULK UPDATES
if (isset($_POST['ajax']) && $_POST['ajax'] === 'update_bulk') {
    header('Content-Type: application/json');
    $updates = json_decode($_POST['updates'] ?? '[]', true);
    $today = date('Y-m-d');
    $count = 0;
    foreach ($updates as $update) {
        $mark_id = intval($update['mark_id'] ?? 0);
        $markah_baru = intval($update['markah_baru'] ?? 0);
        $gred = calculateGrade($markah_baru);
        $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, tarikh_kemaskini=? WHERE id=?");
        $stmt->bind_param("issi", $markah_baru, $gred, $today, $mark_id);
        if ($stmt->execute()) $count++;
        $stmt->close();
    }
    echo json_encode(['success' => true, 'message' => "$count markah berjaya dikemaskini"]);
    exit();
}

// GET dropdown data
$peperiksaan_list = getExamsByGuru($guru_id);
$kelas_list = getKelasByGuru($guru_id);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Markah - SlipKu</title>
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

        /* Search Section */
        .search-section {
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

        /* Marks Table Container */
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
            color: var(--warning);
        }

        .marks-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
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

        .marks-table tr.changed {
            background: rgba(245, 158, 11, 0.05);
        }

        .marks-table tr.changed:hover td {
            background: rgba(245, 158, 11, 0.1);
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

        .mark-input.changed {
            border-color: var(--warning);
            background-color: rgba(245, 158, 11, 0.05);
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

        /* Change Indicator */
        .change-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 12px;
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* History Section */
        .history-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .history-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-title i {
            color: var(--info);
        }

        .history-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 12px;
            transition: var(--transition);
        }

        .history-item:hover {
            background: var(--primary-light);
        }

        .history-item-info {
            flex: 1;
        }

        .history-item-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .history-item-details {
            font-size: 13px;
            color: var(--medium-gray);
            display: flex;
            gap: 15px;
        }

        .history-item-action {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .history-item-action button {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .history-item-action .btn-view {
            background: var(--info);
            color: white;
        }

        .history-item-action .btn-view:hover {
            background: #2563eb;
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
            max-width: 800px;
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

        /* Change Log Table */
        .change-log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .change-log-table th {
            background: var(--light-gray);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            border-bottom: 2px solid #e5e7eb;
        }

        .change-log-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .mark-old {
            color: var(--danger);
            text-decoration: line-through;
        }

        .mark-new {
            color: var(--success);
            font-weight: 600;
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
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .marks-actions {
                width: 100%;
                justify-content: flex-start;
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
            
            .history-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .history-item-action {
                width: 100%;
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <!-- Modal for Change History -->
    <div class="modal" id="historyModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sejarah Perubahan Markah</h3>
                <button class="modal-close" onclick="closeHistoryModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <div class="student-row" style="margin-bottom: 15px;">
                        <div class="student-avatar" id="historyStudentAvatar">AA</div>
                        <div class="student-info">
                            <h4 id="historyStudentName">Ahmad bin Ali</h4>
                            <p id="historyStudentInfo">ID: STU001 • Kelas: 6A • Subjek: Matematik</p>
                        </div>
                    </div>
                </div>
                
                <table class="change-log-table">
                    <thead>
                        <tr>
                            <th>Tarikh & Masa</th>
                            <th>Jenis Penilaian</th>
                            <th>Markah Lama</th>
                            <th>Markah Baru</th>
                            <th>Perubahan</th>
                            <th>Dikemaskini Oleh</th>
                        </tr>
                    </thead>
                    <tbody id="changeLogBody">
                        <!-- Change log rows will be loaded here -->
                    </tbody>
                </table>
                
                <div class="empty-state" id="emptyChangeLog" style="display: none; padding: 40px 20px;">
                    <i class="fas fa-history"></i>
                    <h3>Tiada Sejarah Perubahan</h3>
                    <p>Tiada perubahan markah direkodkan untuk pelajar ini.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Confirmation -->
    <div class="modal" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Kemaskini Markah</h3>
                <button class="modal-close" onclick="closeConfirmationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 25px;">
                    <i class="fas fa-question-circle" style="font-size: 48px; color: var(--warning); margin-bottom: 15px;"></i>
                    <h4 id="confirmationMessage" style="font-size: 18px; color: var(--dark-gray); margin-bottom: 10px;">Adakah anda pasti ingin mengemaskini markah?</h4>
                    <p id="confirmationDetails" style="color: var(--medium-gray); font-size: 14px;">Perubahan akan direkodkan dalam sejarah.</p>
                </div>
                
                <div id="changesSummary" style="background: var(--light-gray); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <h5 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">
                        <i class="fas fa-list"></i> Ringkasan Perubahan:
                    </h5>
                    <p style="font-size: 13px; color: var(--medium-gray);" id="changesList">
                        Tiada perubahan
                    </p>
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="closeConfirmationModal()">
                        Batal
                    </button>
                    <button class="btn btn-primary" id="confirmActionBtn" onclick="confirmUpdate()">
                        Ya, Kemaskini
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
                    <h4 id="successMessage" style="font-size: 18px; color: var(--dark-gray); margin-bottom: 10px;">Markah Berjaya Dikemaskini!</h4>
                    <p id="successDetails" style="color: var(--medium-gray); font-size: 14px;">Semua perubahan telah berjaya disimpan.</p>
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
            <a href="../dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Kemaskini Markah</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="top-nav">
                <a href="../dashboard-guru.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    Utama
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    Pemberitahuan
                    <span class="notification-badge">2</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    Mesej
                    <span class="notification-badge">1</span>
                </a>
            </nav>

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
                <h2>Kemaskini Markah 🔄</h2>
                <p>Kemaskini dan betulkan markah peperiksaan sedia ada</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaHalaman()">
                    <i class="fas fa-redo"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="kemaskiniSemuaPerubahan()">
                    <i class="fas fa-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar mengikut nama, ID, atau kelas..." onkeyup="searchStudents()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Subjek:</label>
                    <select class="filter-select" id="filterSubject" onchange="filterMarksData()">
                        <option value="">Semua Peperiksaan</option>
                        <?php foreach ($peperiksaan_list as $pp): ?>
                        <option value="<?= htmlspecialchars($pp['nama_peperiksaan']) ?>"><?= htmlspecialchars($pp['nama_peperiksaan']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="filterMarksData()">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelas_list as $kl): ?>
                        <option value="<?= htmlspecialchars($kl['nama']) ?>"><?= htmlspecialchars($kl['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Jenis Penilaian:</label>
                    <select class="filter-select" id="filterAssessment" onchange="filterMarksData()">
                        <option value="">Semua Jenis</option>
                        <option value="bertulis">Bertulis</option>
                        <option value="lisan">Lisan</option>
                        <option value="amali">Amali</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="filterMarks()">
                        <option value="">Semua</option>
                        <option value="changed">Ada Perubahan</option>
                        <option value="unchanged">Tiada Perubahan</option>
                        <option value="passed">Lulus</option>
                        <option value="failed">Gagal</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Marks Table -->
        <div class="marks-container">
            <div class="marks-header">
                <div class="marks-title">
                    <i class="fas fa-edit"></i>
                    <span>Senarai Markah untuk Dikemaskini</span>
                </div>
                <div class="marks-actions">
                    <button class="action-btn info" onclick="tampilkanSemua()">
                        <i class="fas fa-eye"></i>
                        Tampilkan Semua
                    </button>
                    <button class="action-btn warning" onclick="tampilkanBerubah()">
                        <i class="fas fa-exchange-alt"></i>
                        Tampilkan Berubah
                    </button>
                    <button class="action-btn danger" onclick="batalkanSemuaPerubahan()">
                        <i class="fas fa-undo"></i>
                        Batalkan Semua
                    </button>
                </div>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="marks-table" id="marksTable">
                    <thead>
                        <tr>
                            <th>BIL</th>
                            <th>PELAJAR</th>
                            <th>MARKAH ASAL</th>
                            <th>MARKAH BARU (0-100)</th>
                            <th>GRED ASAL</th>
                            <th>GRED BARU</th>
                            <th>STATUS</th>
                            <th>SEJARAH</th>
                        </tr>
                    </thead>
                    <tbody id="marksTableBody">
                        <!-- Student rows will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div style="text-align: right; margin-top: 20px; font-size: 13px; color: var(--medium-gray);">
                <span id="changesStatus">Tiada perubahan</span>
            </div>
        </div>

        <!-- History Section -->
        <div class="history-section">
            <div class="history-title">
                <i class="fas fa-history"></i>
                Sejarah Perubahan Terkini
            </div>
            
            <div class="history-list" id="historyList">
                <!-- History items will be loaded here -->
            </div>
            
            <div class="empty-state" id="emptyHistory" style="display: none;">
                <i class="fas fa-history"></i>
                <h3>Tiada Sejarah Perubahan</h3>
                <p>Belum ada perubahan markah direkodkan.</p>
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const historyModal = document.getElementById('historyModal');
        const confirmationModal = document.getElementById('confirmationModal');
        const successModal = document.getElementById('successModal');
        const marksTableBody = document.getElementById('marksTableBody');
        const historyList = document.getElementById('historyList');
        const emptyHistory = document.getElementById('emptyHistory');

        // Current state
        let currentMarksData = [];
        let originalMarksData = [];
        let changeHistory = [];
        let changesCount = 0;

        // Sample data for marks
        const sampleMarksData = [];

        // Sample change history
        const sampleChangeHistory = [];

        // Initialize page
        function initializePage() {
            loadRealMarksData();
        }
        
        function loadRealMarksData() {
            fetch('kemasikini-markah.php?ajax=get_marks')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        currentMarksData = data.marks.map(m => ({
                            id: m.id,
                            studentId: m.id_pelajar,
                            studentName: m.nama_pelajar,
                            studentIC: m.no_kp,
                            studentClass: m.nama_kelas,
                            subjectName: m.nama_subjek,
                            assessmentType: m.nama_peperiksaan,
                            subject: m.nama_subjek,
                            originalMark: m.markah,
                            currentMark: m.markah,
                            originalGrade: m.gred,
                            catatan: m.catatan
                        }));
                        originalMarksData = JSON.parse(JSON.stringify(currentMarksData));
                        changeHistory = [];

                        // Set up event listeners
                        setupEventListeners();

                        // Load initial data
                        loadMarksData();
                        loadChangeHistory();

                        // Update changes status
                        updateChangesStatus();
                    }
                })
                .catch(err => {
                    console.error('Ralat memuatkan data:', err);
                    marksTableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--medium-gray);">Tiada data markah dijumpai. Sila pastikan markah telah dimasukkan.</td></tr>';
                    setupEventListeners();
                    loadChangeHistory();
                    updateChangesStatus();
                });
        }

        // Load marks data
        function filterMarksData() {
            const subjectFilter = document.getElementById('filterSubject').value;
            const classFilter = document.getElementById('filterClass').value;
            let filtered = currentMarksData;
            if (subjectFilter) filtered = filtered.filter(i => i.assessmentType === subjectFilter);
            if (classFilter) filtered = filtered.filter(i => i.studentClass === classFilter);
            loadMarksTable(filtered);
        }

        function loadMarksData() {
            const subjectFilter = document.getElementById('filterSubject').value;
            const classFilter = document.getElementById('filterClass').value;
            const assessmentFilter = document.getElementById('filterAssessment').value;
            
            // Filter data
            let filteredData = currentMarksData;
            
            if (subjectFilter) {
                filteredData = filteredData.filter(item => item.subject === subjectFilter);
            }
            
            if (classFilter) {
                filteredData = filteredData.filter(item => item.studentClass === classFilter);
            }
            
            if (assessmentFilter) {
                filteredData = filteredData.filter(item => item.assessmentType === assessmentFilter);
            }
            
            // Load marks table
            loadMarksTable(filteredData);
        }

        // Load marks table
        function loadMarksTable(data) {
            if (data.length === 0) {
                marksTableBody.innerHTML = `
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--medium-gray);">
                            <i class="fas fa-search" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            Tiada markah ditemui untuk kriteria carian ini
                        </td>
                    </tr>
                `;
                return;
            }
            
            marksTableBody.innerHTML = data.map((mark, index) => {
                // Get student initials for avatar
                const names = mark.studentName.split(' ');
                const initials = names.length >= 2 
                    ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                    : names[0].substring(0, 2);
                
                // Check if mark has been changed
                const isChanged = mark.currentMark !== mark.originalMark;
                const rowClass = isChanged ? 'changed' : '';
                
                // Calculate new grade
                const newGrade = calculateGrade(mark.currentMark);
                
                // Get status
                const status = mark.currentMark >= 40 ? 'passed' : 'failed';
                const statusText = status === 'passed' ? 'LULUS' : 'GAGAL';
                const statusClass = status === 'passed' ? 'status-success' : 'status-danger';
                
                return `
                    <tr class="${rowClass}" id="row-${mark.id}">
                        <td>${index + 1}</td>
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <h4>${mark.studentName}</h4>
                                    <p>${mark.studentClass} • ${mark.studentIC}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--dark-gray);">${mark.originalMark}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">${mark.originalGrade}</div>
                        </td>
                        <td>
                            <div class="mark-input-container">
                                <input type="number" 
                                       class="mark-input ${isChanged ? 'changed' : ''} ${getMarkInputClass(mark.currentMark)}" 
                                       id="mark-input-${mark.id}"
                                       min="0" 
                                       max="100"
                                       value="${mark.currentMark}"
                                       oninput="updateCurrentMark('${mark.id}', this.value)"
                                       onblur="validateMarkInput('${mark.id}')"
                                       placeholder="0-100">
                                ${isChanged ? '<div class="change-indicator"><i class="fas fa-exchange-alt"></i> Diubah</div>' : ''}
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${mark.originalGrade.toLowerCase()}">
                                ${mark.originalGrade}
                            </span>
                        </td>
                        <td>
                            <span class="grade-badge grade-${newGrade.toLowerCase()}" id="new-grade-${mark.id}">
                                ${newGrade}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge ${statusClass}" id="status-${mark.id}">
                                ${statusText}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn info" onclick="viewChangeHistory('${mark.studentId}', '${mark.studentName}', '${initials}')">
                                <i class="fas fa-history"></i>
                                Lihat
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update current mark
        function updateCurrentMark(markId, newMark) {
            const markItem = currentMarksData.find(item => item.id === markId);
            if (!markItem) return;
            
            // Parse mark value
            const markValue = newMark === '' ? null : parseInt(newMark);
            
            // Update current mark
            markItem.currentMark = markValue;
            
            // Update input class
            const input = document.getElementById(`mark-input-${markId}`);
            const isChanged = markValue !== markItem.originalMark;
            
            if (input) {
                input.classList.remove('changed', 'excellent', 'good', 'average');
                
                if (isChanged) {
                    input.classList.add('changed');
                }
                
                if (markValue !== null) {
                    input.classList.add(getMarkInputClass(markValue));
                }
            }
            
            // Update row class
            const row = document.getElementById(`row-${markId}`);
            if (row) {
                if (isChanged) {
                    row.classList.add('changed');
                } else {
                    row.classList.remove('changed');
                }
            }
            
            // Update new grade
            if (markValue !== null) {
                const newGrade = calculateGrade(markValue);
                const gradeBadge = document.getElementById(`new-grade-${markId}`);
                if (gradeBadge) {
                    gradeBadge.textContent = newGrade;
                    gradeBadge.className = `grade-badge grade-${newGrade.toLowerCase()}`;
                }
                
                // Update status
                const status = markValue >= 40 ? 'passed' : 'failed';
                const statusText = status === 'passed' ? 'LULUS' : 'GAGAL';
                const statusClass = status === 'passed' ? 'status-success' : 'status-danger';
                
                const statusBadge = document.getElementById(`status-${markId}`);
                if (statusBadge) {
                    statusBadge.textContent = statusText;
                    statusBadge.className = `status-badge ${statusClass}`;
                }
            }
            
            // Update changes status
            updateChangesStatus();
        }

        // Validate mark input
        function validateMarkInput(markId) {
            const input = document.getElementById(`mark-input-${markId}`);
            const markValue = input.value === '' ? null : parseInt(input.value);
            
            if (markValue !== null) {
                if (markValue < 0 || markValue > 100) {
                    input.classList.add('out-of-range');
                    showNotification('Markah mesti antara 0 dan 100', 'warning');
                } else {
                    input.classList.remove('out-of-range');
                }
            }
        }

        // Calculate grade based on mark
        function calculateGrade(mark) {
            if (mark === null) return '-';
            
            if (mark >= 80) return 'A';
            if (mark >= 70) return 'B';
            if (mark >= 60) return 'C';
            if (mark >= 50) return 'D';
            if (mark >= 40) return 'E';
            return 'F';
        }

        // Get mark input class based on mark value
        function getMarkInputClass(mark) {
            if (mark === null) return '';
            
            if (mark >= 80) return 'excellent';
            if (mark >= 60) return 'good';
            if (mark >= 40) return 'average';
            return '';
        }

        // Filter marks
        function filterMarks() {
            const statusFilter = document.getElementById('filterStatus').value;
            let filteredData = currentMarksData;
            
            // Apply subject, class, and assessment filters first
            const subjectFilter = document.getElementById('filterSubject').value;
            const classFilter = document.getElementById('filterClass').value;
            const assessmentFilter = document.getElementById('filterAssessment').value;
            
            if (subjectFilter) {
                filteredData = filteredData.filter(item => item.subject === subjectFilter);
            }
            
            if (classFilter) {
                filteredData = filteredData.filter(item => item.studentClass === classFilter);
            }
            
            if (assessmentFilter) {
                filteredData = filteredData.filter(item => item.assessmentType === assessmentFilter);
            }
            
            // Apply status filter
            if (statusFilter === 'changed') {
                filteredData = filteredData.filter(item => item.currentMark !== item.originalMark);
            } else if (statusFilter === 'unchanged') {
                filteredData = filteredData.filter(item => item.currentMark === item.originalMark);
            } else if (statusFilter === 'passed') {
                filteredData = filteredData.filter(item => item.currentMark >= 40);
            } else if (statusFilter === 'failed') {
                filteredData = filteredData.filter(item => item.currentMark < 40 && item.currentMark !== null);
            }
            
            // Load filtered table
            loadMarksTable(filteredData);
        }

        // Search students
        function searchStudents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            let filteredData = currentMarksData;
            
            // Apply existing filters first
            const subjectFilter = document.getElementById('filterSubject').value;
            const classFilter = document.getElementById('filterClass').value;
            const assessmentFilter = document.getElementById('filterAssessment').value;
            
            if (subjectFilter) {
                filteredData = filteredData.filter(item => item.subject === subjectFilter);
            }
            
            if (classFilter) {
                filteredData = filteredData.filter(item => item.studentClass === classFilter);
            }
            
            if (assessmentFilter) {
                filteredData = filteredData.filter(item => item.assessmentType === assessmentFilter);
            }
            
            // Apply search filter
            if (searchTerm) {
                filteredData = filteredData.filter(item => 
                    item.studentName.toLowerCase().includes(searchTerm) ||
                    item.studentId.toLowerCase().includes(searchTerm) ||
                    item.studentClass.toLowerCase().includes(searchTerm) ||
                    item.studentIC.toLowerCase().includes(searchTerm)
                );
            }
            
            // Load filtered table
            loadMarksTable(filteredData);
        }

        // Load change history
        function loadChangeHistory() {
            if (changeHistory.length === 0) {
                emptyHistory.style.display = 'block';
                historyList.innerHTML = '';
                return;
            }
            
            emptyHistory.style.display = 'none';
            
            historyList.innerHTML = changeHistory.map(change => {
                // Format date
                const dateObj = new Date(change.date.replace(' ', 'T'));
                const formattedDate = dateObj.toLocaleDateString('ms-MY', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
                const formattedTime = dateObj.toLocaleTimeString('ms-MY', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                return `
                    <div class="history-item">
                        <div class="history-item-info">
                            <div class="history-item-title">${change.studentName}</div>
                            <div class="history-item-details">
                                <span>${change.subject}</span>
                                <span>${change.assessmentType}</span>
                                <span>${formattedDate} ${formattedTime}</span>
                            </div>
                        </div>
                        <div class="history-item-action">
                            <span style="font-size: 13px; color: var(--dark-gray);">
                                <span style="color: var(--danger); text-decoration: line-through;">${change.oldMark}</span>
                                <span style="margin: 0 5px;">→</span>
                                <span style="color: var(--success); font-weight: 600;">${change.newMark}</span>
                            </span>
                            <button class="btn-view" onclick="viewSpecificChange('${change.id}')">
                                <i class="fas fa-eye"></i>
                                Butiran
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // View change history for a student
        function viewChangeHistory(studentId, studentName, studentInitials) {
            // Filter history for this student
            const studentHistory = currentMarksData.find(item => item.studentId === studentId)?.history || [];
            
            // Update modal header
            document.getElementById('historyStudentAvatar').textContent = studentInitials;
            document.getElementById('historyStudentName').textContent = studentName;
            document.getElementById('historyStudentInfo').textContent = `ID: ${studentId} • Subjek: Matematik`;
            
            // Load change log
            const changeLogBody = document.getElementById('changeLogBody');
            const emptyChangeLog = document.getElementById('emptyChangeLog');
            
            if (studentHistory.length === 0) {
                changeLogBody.innerHTML = '';
                emptyChangeLog.style.display = 'block';
            } else {
                emptyChangeLog.style.display = 'none';
                
                changeLogBody.innerHTML = studentHistory.map(change => {
                    // Format date
                    const dateObj = new Date(change.date.replace(' ', 'T'));
                    const formattedDate = dateObj.toLocaleDateString('ms-MY', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Calculate change
                    const changeAmount = change.oldMark !== null ? change.newMark - change.oldMark : 'N/A';
                    
                    return `
                        <tr>
                            <td>${formattedDate}</td>
                            <td>Kuiz 1</td>
                            <td>${change.oldMark !== null ? change.oldMark : '-'}</td>
                            <td>${change.newMark}</td>
                            <td>
                                ${changeAmount !== 'N/A' ? 
                                    `<span style="color: ${changeAmount >= 0 ? 'var(--success)' : 'var(--danger)'}; font-weight: 600;">
                                        ${changeAmount >= 0 ? '+' : ''}${changeAmount}
                                    </span>` 
                                    : 'N/A'
                                }
                            </td>
                            <td>${change.changedBy}</td>
                        </tr>
                    `;
                }).join('');
            }
            
            historyModal.classList.add('active');
        }

        // View specific change details
        function viewSpecificChange(changeId) {
            const change = changeHistory.find(item => item.id === changeId);
            if (!change) return;
            
            // Format date
            const dateObj = new Date(change.date.replace(' ', 'T'));
            const formattedDate = dateObj.toLocaleDateString('ms-MY', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Show details in alert
            const details = `
                <strong>Pelajar:</strong> ${change.studentName}<br>
                <strong>Subjek:</strong> ${change.subject}<br>
                <strong>Jenis Penilaian:</strong> ${change.assessmentType}<br>
                <strong>Tarikh Perubahan:</strong> ${formattedDate}<br>
                <strong>Markah Lama:</strong> <span style="color: var(--danger); text-decoration: line-through;">${change.oldMark} (${change.oldGrade})</span><br>
                <strong>Markah Baru:</strong> <span style="color: var(--success); font-weight: 600;">${change.newMark} (${change.newGrade})</span><br>
                <strong>Perubahan:</strong> <span style="color: ${change.newMark - change.oldMark >= 0 ? 'var(--success)' : 'var(--danger)'}; font-weight: 600;">
                    ${change.newMark - change.oldMark >= 0 ? '+' : ''}${change.newMark - change.oldMark}
                </span><br>
                <strong>Dikemaskini Oleh:</strong> ${change.changedBy}<br>
                <strong>Sebab:</strong> ${change.reason}
            `;
            
            showNotification(`Butiran perubahan untuk ${change.studentName}`, 'info', details);
        }

        // Update changes status
        function updateChangesStatus() {
            changesCount = currentMarksData.filter(item => item.currentMark !== item.originalMark).length;
            
            const statusElement = document.getElementById('changesStatus');
            if (changesCount === 0) {
                statusElement.textContent = 'Tiada perubahan';
                statusElement.style.color = 'var(--medium-gray)';
            } else {
                statusElement.textContent = `${changesCount} perubahan belum disimpan`;
                statusElement.style.color = 'var(--warning)';
            }
        }

        // Show all marks
        function tampilkanSemua() {
            document.getElementById('filterStatus').value = '';
            loadMarksData();
            showNotification('Semua markah dipaparkan', 'info');
        }

        // Show only changed marks
        function tampilkanBerubah() {
            document.getElementById('filterStatus').value = 'changed';
            filterMarks();
            showNotification('Hanya markah yang diubah dipaparkan', 'info');
        }

        // Cancel all changes
        function batalkanSemuaPerubahan() {
            if (changesCount === 0) {
                showNotification('Tiada perubahan untuk dibatalkan', 'info');
                return;
            }
            
            if (!confirm(`Adakah anda pasti ingin membatalkan semua ${changesCount} perubahan?`)) {
                return;
            }
            
            // Reset all marks to original values
            currentMarksData.forEach(item => {
                item.currentMark = item.originalMark;
            });
            
            // Reload data
            loadMarksData();
            updateChangesStatus();
            
            showNotification(`Semua ${changesCount} perubahan telah dibatalkan`, 'success');
        }

        // Update all changes
        function kemaskiniSemuaPerubahan() {
            if (changesCount === 0) {
                showNotification('Tiada perubahan untuk disimpan', 'warning');
                return;
            }
            
            // Prepare changes summary
            const changedItems = currentMarksData.filter(item => item.currentMark !== item.originalMark);
            let changesList = '';
            
            changedItems.forEach((item, index) => {
                if (index < 3) {
                    changesList += `${item.studentName}: ${item.originalMark} → ${item.currentMark}<br>`;
                }
            });
            
            if (changedItems.length > 3) {
                changesList += `... dan ${changedItems.length - 3} lagi`;
            }
            
            // Update confirmation modal
            document.getElementById('confirmationMessage').textContent = `Adakah anda pasti ingin menyimpan ${changesCount} perubahan?`;
            document.getElementById('confirmationDetails').textContent = 'Perubahan akan direkodkan dalam sejarah dan tidak boleh dibatalkan.';
            document.getElementById('changesList').innerHTML = changesList;
            
            confirmationModal.classList.add('active');
        }

        // Confirm update - REAL DB SAVE
        function confirmUpdate() {
            const changedItems = currentMarksData.filter(item => item.currentMark !== item.originalMark);
            if (changedItems.length === 0) { closeConfirmationModal(); return; }
            
            showNotification('Menyimpan perubahan...', 'info');
            
            const updates = changedItems.map(item => ({
                mark_id: item.id,
                markah_baru: item.currentMark
            }));
            
            const formData = new FormData();
            formData.append('ajax', 'update_bulk');
            formData.append('updates', JSON.stringify(updates));
            
            fetch('kemasikini-markah.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    closeConfirmationModal();
                    if (data.success) {
                        changedItems.forEach(item => { item.originalMark = item.currentMark; });
                        document.getElementById('successMessage').textContent = 'Perubahan Berjaya Disimpan!';
                        document.getElementById('successDetails').textContent = data.message;
                        successModal.classList.add('active');
                        loadMarksData();
                        updateChangesStatus();
                    } else {
                        showNotification('Ralat: ' + data.message, 'error');
                    }
                })
                .catch(e => {
                    closeConfirmationModal();
                    showNotification('Ralat sambungan: ' + e.message, 'error');
                });
        }

        // Close history modal
        function closeHistoryModal() {
            historyModal.classList.remove('active');
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
            if (changesCount > 0) {
                if (!confirm('Anda mempunyai perubahan yang belum disimpan. Adakah anda pasti ingin memuat semula halaman?')) {
                    return;
                }
            }
            
            location.reload();
        }

        // Show notification
        function showNotification(message, type = 'info', details = null) {
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
                max-width: 400px;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <div>
                    <div style="font-weight: 600;">${message}</div>
                    ${details ? `<div style="font-size: 12px; margin-top: 5px; opacity: 0.9;">${details}</div>` : ''}
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after appropriate time
            const duration = details ? 5000 : 3000;
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, duration);
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
                    closeHistoryModal();
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
            
            @media print {
                .header, .sidebar, .page-actions, .marks-actions, .tabs, .search-section {
                    display: none !important;
                }
                
                .main-content {
                    margin: 0 !important;
                    padding: 20px !important;
                }
                
                body {
                    background: white !important;
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