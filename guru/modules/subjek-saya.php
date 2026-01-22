<?php
session_start();
ob_start();

// Include database connection
require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';
$subjects = []; // INI YANG PENTING: Initialize variable

// 1. PROSES TAMBAH SUBJEK BARU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_subject') {
    $nama = trim($_POST['subject_name'] ?? '');
    $kod = trim($_POST['subject_code'] ?? '');
    $tahun = trim($_POST['subject_year'] ?? '');
    $jenis = trim($_POST['subject_type'] ?? 'core');
    $penerangan = trim($_POST['subject_description'] ?? '');
    $buku_teks = trim($_POST['subject_textbook'] ?? '');
    $catatan = trim($_POST['subject_notes'] ?? '');
    
    // Validate
    if (empty($nama) || empty($kod) || empty($tahun)) {
        $error_message = "Sila isi Nama, Kod dan Tahun subjek!";
    } else {
        // Check if kod already exists
        $check_sql = "SELECT id FROM matapelajaran WHERE kod = ?";
        $check_stmt = $database->prepare($check_sql);
        $check_stmt->bind_param("s", $kod);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "Kod subjek '$kod' sudah wujud!";
        } else {
            // Insert into matapelajaran
            $sql1 = "INSERT INTO matapelajaran (kod, nama, tahun, status) VALUES (?, ?, ?, 1)";
            $stmt1 = $database->prepare($sql1);
            $stmt1->bind_param("sss", $kod, $nama, $tahun);
            
            if ($stmt1->execute()) {
                $subject_id = $database->insert_id;
                
                // Insert into subject_details (jika table wujud)
                $table_check = $database->query("SHOW TABLES LIKE 'subject_details'");
                if ($table_check && $table_check->num_rows > 0) {
                    $sql2 = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks, catatan) 
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt2 = $database->prepare($sql2);
                    $stmt2->bind_param("issss", $subject_id, $jenis, $penerangan, $buku_teks, $catatan);
                    $stmt2->execute();
                    $stmt2->close();
                }
                
                $success_message = "Subjek '$nama' berjaya ditambah!";
                header("Location: subjek-saya.php?success=1&name=" . urlencode($nama));
                exit();
            } else {
                $error_message = "Gagal tambah subjek: " . $database->error;
            }
            $stmt1->close();
        }
        $check_stmt->close();
    }
}

// 2. TUNJUK MESEJ KEJAYAAN
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $subject_name = $_GET['name'] ?? '';
    $success_message = "Subjek '$subject_name' berjaya ditambah!";
}

// 3. AMBIL DATA DARI DATABASE - DENGAN ERROR HANDLING
try {
    // Debug: Check database connection
    if (!$database) {
        throw new Exception("Database connection failed");
    }
    
    // SQL untuk ambil semua data dengan error handling
    $sql = "SELECT m.*, 
                   COALESCE(sd.jenis, 'core') as jenis,
                   COALESCE(sd.penerangan, '') as penerangan,
                   COALESCE(sd.buku_teks, '') as buku_teks,
                   COALESCE(sd.catatan, '') as catatan
            FROM matapelajaran m
            LEFT JOIN subject_details sd ON m.id = sd.id_matapelajaran
            WHERE m.status = 1
            ORDER BY m.nama";
    
    error_log("SQL Query: " . $sql); // Debug log
    
    $result = $database->query($sql);
    
    if ($result === false) {
        throw new Exception("Query failed: " . $database->error);
    }
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Tentukan kelas berdasarkan tahun
            $kelas_list = [];
            if (strpos($row['tahun'], '6') !== false) {
                $kelas_list = ['6A', '6B'];
            } elseif (strpos($row['tahun'], '5') !== false) {
                $kelas_list = ['5A', '5B'];
            } elseif (strpos($row['tahun'], '4') !== false) {
                $kelas_list = ['4A', '4B'];
            } else {
                $kelas_list = ['3A', '3B'];
            }
            
            $subjects[] = [
                'id' => 'SUB' . str_pad($row['id'], 3, '0', STR_PAD_LEFT),
                'db_id' => $row['id'],
                'name' => $row['nama'],
                'code' => $row['kod'],
                'year' => $row['tahun'],
                'type' => $row['jenis'],
                'description' => $row['penerangan'],
                'books' => $row['buku_teks'],
                'notes' => $row['catatan'],
                'teacher' => 'Cikgu ' . ($row['id'] % 2 == 0 ? 'Ahmad' : 'Siti'),
                'classes' => $kelas_list,
                'totalStudents' => count($kelas_list) * 20,
                'averagePerformance' => 70 + ($row['id'] % 25),
                'attendanceRate' => 85 + ($row['id'] % 10),
                'syllabusProgress' => 30 + ($row['id'] % 70),
                'status' => 'active'
            ];
        }
        $result->free();
    } else {
        error_log("No subjects found in database");
        // Jika tiada subjek, biarkan $subjects sebagai array kosong
    }
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    // Jangan throw error, biarkan $subjects sebagai array kosong
}

// Debug: Check subjects count
error_log("Total subjects loaded: " . count($subjects));

// Pastikan $subjects sentiasa array (walaupun kosong)
if (!is_array($subjects)) {
    $subjects = [];
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Header */
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
            padding: 15px 0;
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

        .top-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-item {
            position: relative;
            padding: 10px 15px;
            color: var(--medium-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Menu Toggle */
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

        /* Sidebar */
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

        .sidebar-item i {
            width: 20px;
            font-size: 16px;
        }

        .sidebar-item.active i {
            color: white;
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

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            font-family: 'Poppins', sans-serif;
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

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-card:hover {
            border: 2px solid var(--primary);
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .stat-icon.books { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .stat-icon.students { background: linear-gradient(135deg, #10b981, #34d399); }
        .stat-icon.performance { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-icon.progress { background: linear-gradient(135deg, #ef4444, #f87171); }

        .stat-info h3 {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark-gray);
            line-height: 1;
            margin-bottom: 5px;
        }

        /* Subjects Grid */
        .subjects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .subject-card {
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .subject-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .subject-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px;
            position: relative;
        }

        .subject-code {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .subject-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subject-teacher {
            font-size: 13px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .subject-body {
            padding: 20px;
        }

        .subject-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 13px;
            color: var(--medium-gray);
        }

        .subject-classes {
            margin: 15px 0;
        }

        .subject-classes span {
            font-weight: 600;
            color: var(--dark-gray);
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .class-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .class-tag {
            background: var(--primary-light);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: var(--primary-dark);
            font-weight: 500;
        }

        .subject-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .metric-item {
            text-align: center;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .metric-label {
            font-size: 11px;
            color: var(--medium-gray);
            margin-top: 5px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subject-footer {
            display: flex;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-icon {
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            background: var(--light-gray);
            color: var(--dark-gray);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
            flex: 1;
            font-weight: 500;
        }

        .btn-icon:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Alert Messages */
        .alert-message {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-message.success {
            background: rgba(16, 185, 129, 0.1);
            border: 2px solid var(--success);
            color: var(--success);
        }

        .alert-message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 2px solid var(--danger);
            color: var(--danger);
        }

        /* No Subjects State */
        .no-subjects {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .no-subjects-icon {
            font-size: 72px;
            color: var(--primary-light);
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .no-subjects h3 {
            font-size: 24px;
            color: var(--dark-gray);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .no-subjects p {
            color: var(--medium-gray);
            margin-bottom: 25px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1100;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.4s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .modal-header h3 {
            font-size: 22px;
            font-weight: 700;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 14px;
        }

        .form-label.required:after {
            content: " *";
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: var(--transition);
            background: var(--white);
            color: var(--dark-gray);
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .subjects-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 20px;
            }
            
            .main-content {
                padding: 20px;
                margin-top: 75px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .subjects-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .page-header {
                flex-direction: column;
                text-align: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .modal-content {
                width: 95%;
            }
            
            .subject-metrics {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .subject-footer {
                flex-direction: column;
            }
            
            .btn-icon {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <a href="dashboard.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Subjek Saya</p>
                </div>
            </a>
            
            <nav class="top-nav">
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">3</span>
                </a>
                <a href="profil.php" class="nav-item">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
         <div class="sidebar-title">Menu Utama</div>
            <a href="dashboard-guru.php" class="sidebar-item <?php echo ($current_page == 'dashboard-guru.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="modules/kelas-saya.php" class="sidebar-item">
                <i class="fas fa-users"></i>
                Kelas Saya
                <span class="badge">3</span>
            </a>
            <a href="modules/pelajar-saya.php" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <span class="badge">85</span>
            </a>
            <a href="modules/subjek-saya.php" class="sidebar-item">
                <i class="fas fa-book"></i>
                Subjek Saya
                <span class="badge">4</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan & Penilaian</div>
            <a href="modules/tambah-markah.php" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="modules/semak-markah.php" class="sidebar-item">
                <i class="fas fa-search"></i>
                Semak Markah
            </a>
            <a href="modules/laporan-prestasi.php" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Sistem</div>
            <a href="profil-saya.php" class="sidebar-item">
                <i class="fas fa-user-cog"></i>
                Profil Saya
            </a>
            <a href="logout.php" class="sidebar-item" style="color: var(--danger);">
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
                <h2><i class="fas fa-book"></i> Subjek Saya</h2>
                <p>Urus dan pantau semua subjek yang anda ajar</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="exportData()">
                    <i class="fas fa-download"></i> Export Data
                </button>
                <button class="btn btn-primary" onclick="openEditModal()">
                    <i class="fas fa-plus"></i> Tambah Subjek
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon books">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="stat-info">
                    <h3>Jumlah Subjek</h3>
                    <div class="stat-value"><?php echo count($subjects); ?></div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Jumlah Pelajar</h3>
                    <div class="stat-value">
                        <?php 
                        $totalStudents = 0;
                        foreach ($subjects as $subject) {
                            $totalStudents += $subject['totalStudents'];
                        }
                        echo $totalStudents;
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>Purata Prestasi</h3>
                    <div class="stat-value">
                        <?php 
                        $avg_perf = 0;
                        if (count($subjects) > 0) {
                            foreach ($subjects as $subject) {
                                $avg_perf += $subject['averagePerformance'];
                            }
                            $avg_perf = $avg_perf / count($subjects);
                        }
                        echo number_format($avg_perf, 1) . '%';
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon progress">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-info">
                    <h3>Kemajuan Sukatan</h3>
                    <div class="stat-value">
                        <?php 
                        $avg_prog = 0;
                        if (count($subjects) > 0) {
                            foreach ($subjects as $subject) {
                                $avg_prog += $subject['syllabusProgress'];
                            }
                            $avg_prog = $avg_prog / count($subjects);
                        }
                        echo number_format($avg_prog, 1) . '%';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subjects Grid -->
        <?php if (empty($subjects)): ?>
            <div class="no-subjects">
                <div class="no-subjects-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Tiada Subjek Dijumpai</h3>
                <p>Anda belum menambah sebarang subjek. Mulakan dengan menambah subjek pertama anda.</p>
                <button class="btn btn-primary" onclick="openEditModal()">
                    <i class="fas fa-plus"></i> Tambah Subjek Pertama
                </button>
            </div>
        <?php else: ?>
            <div class="subjects-grid" id="subjectsGrid">
                <?php foreach ($subjects as $subject): ?>
                <div class="subject-card">
                    <div class="subject-header">
                        <div class="subject-code"><?php echo htmlspecialchars($subject['code']); ?></div>
                        <h3 class="subject-title"><?php echo htmlspecialchars($subject['name']); ?></h3>
                        <div class="subject-teacher">
                            <i class="fas fa-user-tie"></i>
                            <?php echo htmlspecialchars($subject['teacher']); ?>
                        </div>
                    </div>
                    
                    <div class="subject-body">
                        <div class="subject-info">
                            <span><i class="fas fa-calendar"></i> Tahun <?php echo htmlspecialchars($subject['year']); ?></span>
                            <span><i class="fas fa-tag"></i> <?php echo ucfirst($subject['type']); ?></span>
                        </div>
                        
                        <?php if (!empty($subject['description'])): ?>
                        <p style="color: var(--medium-gray); font-size: 14px; margin-bottom: 15px; line-height: 1.5;">
                            <?php echo htmlspecialchars($subject['description']); ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="subject-classes">
                            <span>Kelas:</span>
                            <div class="class-tags">
                                <?php if (!empty($subject['classes'])): ?>
                                    <?php foreach ($subject['classes'] as $class): ?>
                                        <span class="class-tag"><?php echo htmlspecialchars($class); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="class-tag">Belum Ditetapkan</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="subject-metrics">
                            <div class="metric-item">
                                <div class="metric-value"><?php echo $subject['totalStudents']; ?></div>
                                <div class="metric-label">Pelajar</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-value"><?php echo $subject['averagePerformance']; ?>%</div>
                                <div class="metric-label">Prestasi</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-value"><?php echo $subject['attendanceRate']; ?>%</div>
                                <div class="metric-label">Kehadiran</div>
                            </div>
                            <div class="metric-item">
                                <div class="metric-value"><?php echo $subject['syllabusProgress']; ?>%</div>
                                <div class="metric-label">Sukatan</div>
                            </div>
                        </div>
                        
                        <div class="subject-footer">
                            <button class="btn-icon" onclick="viewSubject(<?php echo $subject['db_id']; ?>)">
                                <i class="fas fa-eye"></i> Lihat
                            </button>
                            <button class="btn-icon" onclick="editSubject(<?php echo $subject['db_id']; ?>)">
                                <i class="fas fa-edit"></i> Edit
                                
                            </button>
                            <button class="btn-icon" onclick="deleteSubject(<?php echo $subject['db_id']; ?>, '<?php echo htmlspecialchars($subject['name']); ?>')">
                                <i class="fas fa-trash"></i> Padam
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Alert Messages -->
        <?php if ($success_message): ?>
            <div class="alert-message success" id="successMessage">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert-message error" id="errorMessage">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- Modal for Add/Edit Subject -->
    <div class="modal" id="editSubjectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="editModalTitle">Tambah Subjek Baru</h3>
                <button class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="subjectForm" method="POST" action="">
                    <input type="hidden" name="action" value="add_subject">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Nama Subjek</label>
                            <input type="text" class="form-input" id="subjectName" name="subject_name" 
                                   placeholder="Contoh: Matematik, Sains" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Kod Subjek</label>
                            <input type="text" class="form-input" id="subjectCode" name="subject_code" 
                                   placeholder="Contoh: MAT01, SNS01" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jenis Subjek</label>
                            <select class="form-select" id="subjectType" name="subject_type" required>
                                <option value="">Pilih Jenis</option>
                                <option value="core" selected>Teras</option>
                                <option value="elective">Elektif</option>
                                <option value="additional">Tambahan</option>
                                <option value="extracurricular">Kokurikulum</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="subjectYear" name="subject_year" required>
                                <option value="">Pilih Tahun</option>
                                <option value="1">Tahun 1</option>
                                <option value="2">Tahun 2</option>
                                <option value="3">Tahun 3</option>
                                <option value="4">Tahun 4</option>
                                <option value="5">Tahun 5</option>
                                <option value="6">Tahun 6</option>
                                <option value="1-6">Tahun 1-6</option>
                                <option value="4-6">Tahun 4-6</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Penerangan Subjek</label>
                        <textarea class="form-textarea" id="subjectDescription" name="subject_description" 
                                  placeholder="Penerangan ringkas mengenai subjek..." rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Buku Teks Utama</label>
                            <input type="text" class="form-input" id="subjectTextbook" name="subject_textbook" 
                                   placeholder="Nama buku teks">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-input" id="subjectNotes" name="subject_notes" 
                                   placeholder="Catatan tambahan">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Subjek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editSubject(subjectId) {
    // Pastikan ini path yang betul
    window.location.href = 'edit-subject.php?id=' + subjectId;
    }
        // Toggle Sidebar on Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            sidebar.classList.toggle('active');
            
            if (sidebar.classList.contains('active')) {
                mainContent.style.marginLeft = '250px';
            } else {
                mainContent.style.marginLeft = '0';
            }
        }

        // Modal functions
        function openEditModal() {
            document.getElementById('editSubjectModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        
        function closeEditModal() {
            document.getElementById('editSubjectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        function editSubject(subjectId) {
            alert('Edit subjek ID: ' + subjectId + ' (Fungsi akan datang)');
            openEditModal();
        }
        
        function viewSubject(subjectId) {
            window.location.href = 'subjek-detail.php?id=' + subjectId;
        }
        
        function deleteSubject(subjectId, subjectName) {
            if (confirm('Adakah anda pasti mahu memadam subjek "' + subjectName + '"? Tindakan ini tidak boleh dipulihkan.')) {
                fetch('delete-subject.php?id=' + subjectId, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Subjek berjaya dipadam!');
                        location.reload();
                    } else {
                        alert('Gagal memadam: ' + data.error);
                    }
                })
                .catch(error => {
                    alert('Ralat: ' + error);
                });
            }
        }
        
        function exportData() {
            alert('Fungsi export akan datang');
        }
        
        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.alert-message');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
        
        // Form validation
        document.getElementById('subjectForm').addEventListener('submit', function(e) {
            const required = this.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--danger)';
                    valid = false;
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Sila isi semua ruangan yang diperlukan!');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
        });
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editSubjectModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</body>
</html>