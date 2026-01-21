<?php
session_start();
ob_start();

// Include database connection
require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';

// DEBUG: Check database connection
error_log("Database connected: " . ($database ? 'YES' : 'NO'));

// Handle form submission for adding new subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST Data received: " . print_r($_POST, true));
    
    if (isset($_POST['action']) && $_POST['action'] === 'add_subject') {
        $nama = trim($_POST['subject_name'] ?? '');
        $kod = trim($_POST['subject_code'] ?? '');
        $tahun = trim($_POST['subject_year'] ?? '');
        $jenis = trim($_POST['subject_type'] ?? 'core');
        $penerangan = trim($_POST['subject_description'] ?? '');
        $buku_teks = trim($_POST['subject_textbook'] ?? '');
        $catatan = trim($_POST['subject_notes'] ?? '');
        
        error_log("Processing new subject: $nama ($kod) for year: $tahun");
        
        // Validate required fields
        if (empty($nama) || empty($kod) || empty($tahun)) {
            $error_message = "Sila isi semua ruangan yang diperlukan (Nama, Kod, Tahun)!";
        } else {
            try {
                // Start transaction
                $database->begin_transaction();
                
                // Check if subject code already exists
                $check_sql = "SELECT id FROM matapelajaran WHERE kod = ?";
                $check_stmt = $database->prepare($check_sql);
                $check_stmt->bind_param("s", $kod);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    $error_message = "Kod subjek '$kod' sudah wujud dalam sistem!";
                    $check_stmt->close();
                } else {
                    $check_stmt->close();
                    
                    // Insert into matapelajaran table
                    $sql = "INSERT INTO matapelajaran (kod, nama, tahun, status) VALUES (?, ?, ?, 1)";
                    error_log("SQL: $sql with params: $kod, $nama, $tahun");
                    
                    $stmt = $database->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $database->error);
                    }
                    
                    $stmt->bind_param("sss", $kod, $nama, $tahun);
                    
                    if ($stmt->execute()) {
                        $subject_id = $database->insert_id;
                        error_log("New subject ID: $subject_id");
                        
                        // Insert into subject_details table if exists
                        // First check if table exists
                        $table_check = $database->query("SHOW TABLES LIKE 'subject_details'");
                        if ($table_check && $table_check->num_rows > 0) {
                            $sql_details = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks, catatan) 
                                          VALUES (?, ?, ?, ?, ?)";
                            $stmt_details = $database->prepare($sql_details);
                            
                            if ($stmt_details) {
                                $stmt_details->bind_param("issss", $subject_id, $jenis, $penerangan, $buku_teks, $catatan);
                                $stmt_details->execute();
                                $stmt_details->close();
                            }
                        }
                        
                        // Commit transaction
                        $database->commit();
                        
                        $success_message = "Subjek '$nama' berjaya ditambah!";
                        error_log("Subject added successfully");
                        
                        // Clear form by redirecting
                        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&subject=" . urlencode($nama));
                        exit();
                    } else {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }
                    
                    $stmt->close();
                }
            } catch (Exception $e) {
                // Rollback on error
                $database->rollback();
                $error_message = "Gagal menambah subjek: " . $e->getMessage();
                error_log("Error adding subject: " . $e->getMessage());
            }
        }
    }
}

// Show success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $subject_name = isset($_GET['subject']) ? urldecode($_GET['subject']) : '';
    $success_message = "Subjek '$subject_name' berjaya ditambah ke database!";
}

// Fetch subjects from database with teacher and class info
$subjects = [];
$sql = "SELECT m.*, 
               d.jenis, d.penerangan, d.buku_teks, d.catatan,
               GROUP_CONCAT(DISTINCT k.nama ORDER BY k.nama SEPARATOR ', ') as kelas_names,
               COUNT(DISTINCT k.id) as kelas_count
        FROM matapelajaran m 
        LEFT JOIN subject_details d ON m.id = d.id_matapelajaran 
        LEFT JOIN kelas k ON FIND_IN_SET(k.tahun, REPLACE(m.tahun, '-', ',')) OR m.tahun = 'all' OR m.tahun = '1-6'
        WHERE m.status = 1 
        GROUP BY m.id
        ORDER BY m.nama";

error_log("Fetching subjects SQL: " . $sql);

$result = $database->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Process class names
        $class_names = !empty($row['kelas_names']) ? explode(', ', $row['kelas_names']) : [];
        $class_names = array_slice($class_names, 0, 3); // Limit to 3 classes
        
        // Get teacher for this subject (you'll need to implement this based on your database structure)
        $teacher_sql = "SELECT nama FROM guru WHERE status = 1 LIMIT 1";
        $teacher_result = $database->query($teacher_sql);
        $teacher_name = $teacher_result && $teacher_result->num_rows > 0 
            ? $teacher_result->fetch_assoc()['nama'] 
            : 'Cikgu Ahmad';
        
        // Get student count (example - adjust based on your actual tables)
        $student_count = 0;
        if (!empty($class_names)) {
            $class_list = "'" . implode("','", $class_names) . "'";
            $student_sql = "SELECT COUNT(*) as total FROM pelajar WHERE kelas IN ($class_list)";
            $student_result = $database->query($student_sql);
            if ($student_result) {
                $student_count = $student_result->fetch_assoc()['total'];
            }
        }
        
        $subjects[] = [
            'id' => 'SUB' . str_pad($row['id'], 3, '0', STR_PAD_LEFT),
            'db_id' => $row['id'],
            'name' => $row['nama'],
            'code' => $row['kod'],
            'type' => $row['jenis'] ?? 'core',
            'year' => $row['tahun'],
            'description' => $row['penerangan'] ?? '',
            'books' => $row['buku_teks'] ?? '',
            'notes' => $row['catatan'] ?? '',
            'teacher' => $teacher_name,
            'classes' => $class_names,
            'totalStudents' => $student_count,
            'averagePerformance' => rand(65, 90), // Placeholder - replace with actual data
            'attendanceRate' => rand(85, 98), // Placeholder
            'syllabusProgress' => rand(40, 80), // Placeholder
            'status' => 'active'
        ];
    }
    $result->free();
} else {
    error_log("No subjects found in database or query failed");
}

// Debug: Show what we fetched
error_log("Total subjects fetched: " . count($subjects));
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS styles remain the same as your original file */
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--dark);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            text-decoration: none;
            color: var(--dark);
            border-bottom: 2px solid var(--light-gray);
            margin-bottom: 20px;
        }

        .logo-icon {
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .logo-text h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .logo-text p {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: -3px;
        }

        .sidebar-section {
            margin-bottom: 25px;
            padding: 0 15px;
        }

        .sidebar-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-left: 5px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 10px;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-item:hover {
            background: var(--light-gray);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .badge {
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .header h2 {
            font-size: 1.8rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-content p {
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Subject Cards */
        .subjects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .subject-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--light-gray);
        }

        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
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
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .subject-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subject-teacher {
            font-size: 0.9rem;
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
            font-size: 0.85rem;
            color: var(--gray);
        }

        .subject-classes {
            margin: 15px 0;
        }

        .class-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .class-tag {
            background: var(--light-gray);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--dark);
        }

        .subject-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: var(--light);
            border-radius: 10px;
        }

        .metric-item {
            text-align: center;
        }

        .metric-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
        }

        .metric-label {
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .subject-footer {
            display: flex;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .btn-icon {
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            background: var(--light-gray);
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            flex: 1;
            justify-content: center;
        }

        .btn-icon:hover {
            background: var(--primary);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .modal-body {
            padding: 25px;
        }

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
            color: var(--dark);
        }

        .form-label.required:after {
            content: " *";
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--light-gray);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--gray);
            color: white;
        }

        .alert-message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-message.success {
            background: rgba(76, 201, 240, 0.1);
            border: 2px solid var(--success);
            color: var(--success);
        }

        .alert-message.error {
            background: rgba(247, 37, 133, 0.1);
            border: 2px solid var(--danger);
            color: var(--danger);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                padding: 15px 0;
            }
            
            .sidebar .logo-text,
            .sidebar .sidebar-title,
            .sidebar .sidebar-item span:not(.badge) {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .sidebar-item {
                justify-content: center;
                padding: 15px;
            }
            
            .sidebar-item i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            
            .badge {
                position: absolute;
                top: 5px;
                right: 5px;
                font-size: 0.6rem;
                padding: 1px 5px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .subjects-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <a href="dashboard.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Subjek Saya</p>
                </div>
            </a>

            <div class="sidebar-section">
                <div class="sidebar-title">Menu Utama</div>
                <a href="dashboard.php" class="sidebar-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="kelas-saya.php" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Kelas Saya</span>
                    <span class="badge">3</span>
                </a>
                <a href="pelajar-saya.php" class="sidebar-item">
                    <i class="fas fa-user-graduate"></i>
                    <span>Pelajar Saya</span>
                    <span class="badge">85</span>
                </a>
                <a href="subjek-saya.php" class="sidebar-item active">
                    <i class="fas fa-book"></i>
                    <span>Subjek Saya</span>
                    <span class="badge"><?php echo count($subjects); ?></span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Peperiksaan & Penilaian</div>
                <a href="tambah-markah.php" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Markah</span>
                </a>
                <a href="kemaskini-markah.php" class="sidebar-item">
                    <i class="fas fa-edit"></i>
                    <span>Kemaskini Markah</span>
                </a>
                <a href="semak-markah.php" class="sidebar-item">
                    <i class="fas fa-search"></i>
                    <span>Semak Markah</span>
                </a>
                <a href="laporan-prestasi.php" class="sidebar-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan Prestasi</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Pengurusan</div>
                <a href="jadual-ujian.php" class="sidebar-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadual Ujian</span>
                </a>
                <a href="tugasan.php" class="sidebar-item">
                    <i class="fas fa-tasks"></i>
                    <span>Tugasan</span>
                    <span class="badge">12</span>
                </a>
                <a href="kehadiran.php" class="sidebar-item">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Kehadiran</span>
                </a>
                <a href="komunikasi.php" class="sidebar-item">
                    <i class="fas fa-comments"></i>
                    <span>Komunikasi Ibu Bapa</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Sistem</div>
                <a href="profil.php" class="sidebar-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="tetapan.php" class="sidebar-item">
                    <i class="fas fa-cog"></i>
                    <span>Tetapan</span>
                </a>
                <a href="bantuan.php" class="sidebar-item">
                    <i class="fas fa-question-circle"></i>
                    <span>Bantuan</span>
                </a>
                <a href="logout.php" class="sidebar-item" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h2><i class="fas fa-book"></i> Subjek Saya</h2>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="exportData()">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn btn-primary" onclick="openEditModal()">
                        <i class="fas fa-plus"></i> Tambah Subjek Baru
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo count($subjects); ?></h3>
                        <p>Jumlah Subjek</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo array_sum(array_column($subjects, 'totalStudents')); ?></h3>
                        <p>Jumlah Pelajar</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3>
                            <?php 
                            $avg_perf = count($subjects) > 0 ? 
                                array_sum(array_column($subjects, 'averagePerformance')) / count($subjects) : 0;
                                echo number_format($avg_perf, 1);
                            ?>%
                        </h3>
                        <p>Purata Prestasi</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--danger);">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-content">
                        <h3>
                            <?php 
                            $avg_prog = count($subjects) > 0 ? 
                                array_sum(array_column($subjects, 'syllabusProgress')) / count($subjects) : 0;
                                echo number_format($avg_prog, 1);
                            ?>%
                        </h3>
                        <p>Kemajuan Sukatan</p>
                    </div>
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="subjects-grid" id="subjectsGrid">
                <?php if (empty($subjects)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                        <div style="font-size: 3rem; color: var(--light-gray); margin-bottom: 20px;">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 style="color: var(--gray); margin-bottom: 10px;">Tiada Subjek Dijumpai</h3>
                        <p style="color: var(--gray); margin-bottom: 20px;">Anda belum menambah sebarang subjek lagi.</p>
                        <button class="btn btn-primary" onclick="openEditModal()">
                            <i class="fas fa-plus"></i> Tambah Subjek Pertama
                        </button>
                    </div>
                <?php else: ?>
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
                            
                            <?php if ($subject['description']): ?>
                            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">
                                <?php echo htmlspecialchars($subject['description']); ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="subject-classes">
                                <span style="font-weight: 600; color: var(--dark);">Kelas:</span>
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
                                <button class="btn-icon" onclick="viewSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="btn-icon" onclick="editSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-icon" onclick="deleteSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-trash"></i> Padam
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Display -->
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
    </div>

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
                <form id="subjectForm" method="POST" action="subjek-saya.php">
                    <input type="hidden" name="action" value="add_subject">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Nama Subjek</label>
                            <input type="text" class="form-input" id="subjectName" name="subject_name" 
                                   placeholder="Contoh: Matematik, Sains" required
                                   value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Kod Subjek</label>
                            <input type="text" class="form-input" id="subjectCode" name="subject_code" 
                                   placeholder="Contoh: MAT601, SNS601" required
                                   value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jenis Subjek</label>
                            <select class="form-select" id="subjectType" name="subject_type" required>
                                <option value="">Pilih Jenis</option>
                                <option value="core" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'core') ? 'selected' : ''; ?>>Teras</option>
                                <option value="elective" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'elective') ? 'selected' : ''; ?>>Elektif</option>
                                <option value="additional" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'additional') ? 'selected' : ''; ?>>Tambahan</option>
                                <option value="extracurricular" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'extracurricular') ? 'selected' : ''; ?>>Kokurikulum</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="subjectYear" name="subject_year" required>
                                <option value="">Pilih Tahun</option>
                                <option value="all" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                                <option value="1-6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '1-6') ? 'selected' : ''; ?>>Tahun 1-6</option>
                                <option value="6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '6') ? 'selected' : ''; ?>>Tahun 6</option>
                                <option value="5" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '5') ? 'selected' : ''; ?>>Tahun 5</option>
                                <option value="4-6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '4-6') ? 'selected' : ''; ?>>Tahun 4-6</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Penerangan Subjek</label>
                        <textarea class="form-textarea" id="subjectDescription" name="subject_description" 
                                  placeholder="Penerangan ringkas mengenai subjek..." rows="3"><?php echo isset($_POST['subject_description']) ? htmlspecialchars($_POST['subject_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Buku Teks Utama</label>
                            <input type="text" class="form-input" id="subjectTextbook" name="subject_textbook" 
                                   placeholder="Nama buku teks"
                                   value="<?php echo isset($_POST['subject_textbook']) ? htmlspecialchars($_POST['subject_textbook']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-input" id="subjectNotes" name="subject_notes" 
                                   placeholder="Catatan tambahan"
                                   value="<?php echo isset($_POST['subject_notes']) ? htmlspecialchars($_POST['subject_notes']) : ''; ?>">
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
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Total subjects loaded: <?php echo count($subjects); ?>");
            
            // Set up form submit handler
            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Validate form
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = 'var(--danger)';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        event.preventDefault();
                        alert('Sila isi semua ruangan yang diperlukan!');
                        return;
                    }
                    
                    // Show loading state
                    if (submitBtn) {
                        const originalHTML = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                        submitBtn.disabled = true;
                        
                        // Revert after 5 seconds if still submitting
                        setTimeout(() => {
                            submitBtn.innerHTML = originalHTML;
                            submitBtn.disabled = false;
                        }, 5000);
                    }
                    
                    // Form will submit normally to PHP
                });
            }
            
            // Auto-hide messages after 5 seconds
            setTimeout(() => {
                const messages = document.querySelectorAll('.alert-message');
                messages.forEach(msg => {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);
            
            // Check if modal should open from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('openModal')) {
                openEditModal();
            }
        });
        
        // Modal functions
        function openEditModal() {
            document.getElementById('editSubjectModal').style.display = 'flex';
            document.getElementById('editModalTitle').textContent = 'Tambah Subjek Baru';
            document.getElementById('subjectForm').reset();
            
            // Clear previous error styling
            const fields = document.querySelectorAll('.form-input, .form-select');
            fields.forEach(field => field.style.borderColor = '');
        }
        
        function closeEditModal() {
            document.getElementById('editSubjectModal').style.display = 'none';
        }
        
        function editSubject(subjectId) {
            alert('Fungsi edit untuk subjek ID: ' + subjectId);
            // You can implement edit functionality here
            openEditModal();
        }
        
        function viewSubject(subjectId) {
            alert('Melihat subjek ID: ' + subjectId);
            // You can implement view functionality here
        }
        
        function deleteSubject(subjectId) {
            if (confirm('Adakah anda pasti mahu memadam subjek ini?')) {
                alert('Memadam subjek ID: ' + subjectId);
                // You can implement delete functionality here
            }
        }
        
        function exportData() {
            alert('Fungsi export akan dimuat turun data subjek.');
            // You can implement export functionality here
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editSubjectModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html><?php
session_start();
ob_start();

// Include database connection
require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';

// DEBUG: Check database connection
error_log("Database connected: " . ($database ? 'YES' : 'NO'));

// Handle form submission for adding new subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST Data received: " . print_r($_POST, true));
    
    if (isset($_POST['action']) && $_POST['action'] === 'add_subject') {
        $nama = trim($_POST['subject_name'] ?? '');
        $kod = trim($_POST['subject_code'] ?? '');
        $tahun = trim($_POST['subject_year'] ?? '');
        $jenis = trim($_POST['subject_type'] ?? 'core');
        $penerangan = trim($_POST['subject_description'] ?? '');
        $buku_teks = trim($_POST['subject_textbook'] ?? '');
        $catatan = trim($_POST['subject_notes'] ?? '');
        
        error_log("Processing new subject: $nama ($kod) for year: $tahun");
        
        // Validate required fields
        if (empty($nama) || empty($kod) || empty($tahun)) {
            $error_message = "Sila isi semua ruangan yang diperlukan (Nama, Kod, Tahun)!";
        } else {
            try {
                // Start transaction
                $database->begin_transaction();
                
                // Check if subject code already exists
                $check_sql = "SELECT id FROM matapelajaran WHERE kod = ?";
                $check_stmt = $database->prepare($check_sql);
                $check_stmt->bind_param("s", $kod);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    $error_message = "Kod subjek '$kod' sudah wujud dalam sistem!";
                    $check_stmt->close();
                } else {
                    $check_stmt->close();
                    
                    // Insert into matapelajaran table
                    $sql = "INSERT INTO matapelajaran (kod, nama, tahun, status) VALUES (?, ?, ?, 1)";
                    error_log("SQL: $sql with params: $kod, $nama, $tahun");
                    
                    $stmt = $database->prepare($sql);
                    if (!$stmt) {
                        throw new Exception("Prepare failed: " . $database->error);
                    }
                    
                    $stmt->bind_param("sss", $kod, $nama, $tahun);
                    
                    if ($stmt->execute()) {
                        $subject_id = $database->insert_id;
                        error_log("New subject ID: $subject_id");
                        
                        // Insert into subject_details table if exists
                        // First check if table exists
                        $table_check = $database->query("SHOW TABLES LIKE 'subject_details'");
                        if ($table_check && $table_check->num_rows > 0) {
                            $sql_details = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks, catatan) 
                                          VALUES (?, ?, ?, ?, ?)";
                            $stmt_details = $database->prepare($sql_details);
                            
                            if ($stmt_details) {
                                $stmt_details->bind_param("issss", $subject_id, $jenis, $penerangan, $buku_teks, $catatan);
                                $stmt_details->execute();
                                $stmt_details->close();
                            }
                        }
                        
                        // Commit transaction
                        $database->commit();
                        
                        $success_message = "Subjek '$nama' berjaya ditambah!";
                        error_log("Subject added successfully");
                        
                        // Clear form by redirecting
                        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1&subject=" . urlencode($nama));
                        exit();
                    } else {
                        throw new Exception("Execute failed: " . $stmt->error);
                    }
                    
                    $stmt->close();
                }
            } catch (Exception $e) {
                // Rollback on error
                $database->rollback();
                $error_message = "Gagal menambah subjek: " . $e->getMessage();
                error_log("Error adding subject: " . $e->getMessage());
            }
        }
    }
}

// Show success message from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $subject_name = isset($_GET['subject']) ? urldecode($_GET['subject']) : '';
    $success_message = "Subjek '$subject_name' berjaya ditambah ke database!";
}

// Fetch subjects from database with teacher and class info
$subjects = [];
$sql = "SELECT m.*, 
               d.jenis, d.penerangan, d.buku_teks, d.catatan,
               GROUP_CONCAT(DISTINCT k.nama ORDER BY k.nama SEPARATOR ', ') as kelas_names,
               COUNT(DISTINCT k.id) as kelas_count
        FROM matapelajaran m 
        LEFT JOIN subject_details d ON m.id = d.id_matapelajaran 
        LEFT JOIN kelas k ON FIND_IN_SET(k.tahun, REPLACE(m.tahun, '-', ',')) OR m.tahun = 'all' OR m.tahun = '1-6'
        WHERE m.status = 1 
        GROUP BY m.id
        ORDER BY m.nama";

error_log("Fetching subjects SQL: " . $sql);

$result = $database->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Process class names
        $class_names = !empty($row['kelas_names']) ? explode(', ', $row['kelas_names']) : [];
        $class_names = array_slice($class_names, 0, 3); // Limit to 3 classes
        
        // Get teacher for this subject (you'll need to implement this based on your database structure)
        $teacher_sql = "SELECT nama FROM guru WHERE status = 1 LIMIT 1";
        $teacher_result = $database->query($teacher_sql);
        $teacher_name = $teacher_result && $teacher_result->num_rows > 0 
            ? $teacher_result->fetch_assoc()['nama'] 
            : 'Cikgu Ahmad';
        
        // Get student count (example - adjust based on your actual tables)
        $student_count = 0;
        if (!empty($class_names)) {
            $class_list = "'" . implode("','", $class_names) . "'";
            $student_sql = "SELECT COUNT(*) as total FROM pelajar WHERE kelas IN ($class_list)";
            $student_result = $database->query($student_sql);
            if ($student_result) {
                $student_count = $student_result->fetch_assoc()['total'];
            }
        }
        
        $subjects[] = [
            'id' => 'SUB' . str_pad($row['id'], 3, '0', STR_PAD_LEFT),
            'db_id' => $row['id'],
            'name' => $row['nama'],
            'code' => $row['kod'],
            'type' => $row['jenis'] ?? 'core',
            'year' => $row['tahun'],
            'description' => $row['penerangan'] ?? '',
            'books' => $row['buku_teks'] ?? '',
            'notes' => $row['catatan'] ?? '',
            'teacher' => $teacher_name,
            'classes' => $class_names,
            'totalStudents' => $student_count,
            'averagePerformance' => rand(65, 90), // Placeholder - replace with actual data
            'attendanceRate' => rand(85, 98), // Placeholder
            'syllabusProgress' => rand(40, 80), // Placeholder
            'status' => 'active'
        ];
    }
    $result->free();
} else {
    error_log("No subjects found in database or query failed");
}

// Debug: Show what we fetched
error_log("Total subjects fetched: " . count($subjects));
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS styles remain the same as your original file */
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--dark);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            text-decoration: none;
            color: var(--dark);
            border-bottom: 2px solid var(--light-gray);
            margin-bottom: 20px;
        }

        .logo-icon {
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .logo-text h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .logo-text p {
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: -3px;
        }

        .sidebar-section {
            margin-bottom: 25px;
            padding: 0 15px;
        }

        .sidebar-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-left: 5px;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 10px;
            text-decoration: none;
            color: var(--dark);
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-item:hover {
            background: var(--light-gray);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .sidebar-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .badge {
            background: var(--danger);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .header h2 {
            font-size: 1.8rem;
            color: var(--secondary);
            font-weight: 700;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-content p {
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        /* Subject Cards */
        .subjects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .subject-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid var(--light-gray);
        }

        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
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
            background: rgba(255,255,255,0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .subject-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subject-teacher {
            font-size: 0.9rem;
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
            font-size: 0.85rem;
            color: var(--gray);
        }

        .subject-classes {
            margin: 15px 0;
        }

        .class-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .class-tag {
            background: var(--light-gray);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--dark);
        }

        .subject-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: var(--light);
            border-radius: 10px;
        }

        .metric-item {
            text-align: center;
        }

        .metric-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
        }

        .metric-label {
            font-size: 0.75rem;
            color: var(--gray);
            margin-top: 5px;
        }

        .subject-footer {
            display: flex;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .btn-icon {
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            background: var(--light-gray);
            color: var(--dark);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            flex: 1;
            justify-content: center;
        }

        .btn-icon:hover {
            background: var(--primary);
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header h3 {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }

        .modal-close:hover {
            background: rgba(255,255,255,0.1);
        }

        .modal-body {
            padding: 25px;
        }

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
            color: var(--dark);
        }

        .form-label.required:after {
            content: " *";
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--light-gray);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }

        .btn-secondary {
            background: var(--light-gray);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background: var(--gray);
            color: white;
        }

        .alert-message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-message.success {
            background: rgba(76, 201, 240, 0.1);
            border: 2px solid var(--success);
            color: var(--success);
        }

        .alert-message.error {
            background: rgba(247, 37, 133, 0.1);
            border: 2px solid var(--danger);
            color: var(--danger);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                padding: 15px 0;
            }
            
            .sidebar .logo-text,
            .sidebar .sidebar-title,
            .sidebar .sidebar-item span:not(.badge) {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .sidebar-item {
                justify-content: center;
                padding: 15px;
            }
            
            .sidebar-item i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            
            .badge {
                position: absolute;
                top: 5px;
                right: 5px;
                font-size: 0.6rem;
                padding: 1px 5px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .subjects-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <a href="dashboard.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Subjek Saya</p>
                </div>
            </a>

            <div class="sidebar-section">
                <div class="sidebar-title">Menu Utama</div>
                <a href="dashboard.php" class="sidebar-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="kelas-saya.php" class="sidebar-item">
                    <i class="fas fa-users"></i>
                    <span>Kelas Saya</span>
                    <span class="badge">3</span>
                </a>
                <a href="pelajar-saya.php" class="sidebar-item">
                    <i class="fas fa-user-graduate"></i>
                    <span>Pelajar Saya</span>
                    <span class="badge">85</span>
                </a>
                <a href="subjek-saya.php" class="sidebar-item active">
                    <i class="fas fa-book"></i>
                    <span>Subjek Saya</span>
                    <span class="badge"><?php echo count($subjects); ?></span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Peperiksaan & Penilaian</div>
                <a href="tambah-markah.php" class="sidebar-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Markah</span>
                </a>
                <a href="kemaskini-markah.php" class="sidebar-item">
                    <i class="fas fa-edit"></i>
                    <span>Kemaskini Markah</span>
                </a>
                <a href="semak-markah.php" class="sidebar-item">
                    <i class="fas fa-search"></i>
                    <span>Semak Markah</span>
                </a>
                <a href="laporan-prestasi.php" class="sidebar-item">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan Prestasi</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Pengurusan</div>
                <a href="jadual-ujian.php" class="sidebar-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Jadual Ujian</span>
                </a>
                <a href="tugasan.php" class="sidebar-item">
                    <i class="fas fa-tasks"></i>
                    <span>Tugasan</span>
                    <span class="badge">12</span>
                </a>
                <a href="kehadiran.php" class="sidebar-item">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Kehadiran</span>
                </a>
                <a href="komunikasi.php" class="sidebar-item">
                    <i class="fas fa-comments"></i>
                    <span>Komunikasi Ibu Bapa</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Sistem</div>
                <a href="profil.php" class="sidebar-item">
                    <i class="fas fa-user-cog"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="tetapan.php" class="sidebar-item">
                    <i class="fas fa-cog"></i>
                    <span>Tetapan</span>
                </a>
                <a href="bantuan.php" class="sidebar-item">
                    <i class="fas fa-question-circle"></i>
                    <span>Bantuan</span>
                </a>
                <a href="logout.php" class="sidebar-item" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <h2><i class="fas fa-book"></i> Subjek Saya</h2>
                <div class="header-actions">
                    <button class="btn btn-outline" onclick="exportData()">
                        <i class="fas fa-download"></i> Export
                    </button>
                    <button class="btn btn-primary" onclick="openEditModal()">
                        <i class="fas fa-plus"></i> Tambah Subjek Baru
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(67, 97, 238, 0.1); color: var(--primary);">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo count($subjects); ?></h3>
                        <p>Jumlah Subjek</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo array_sum(array_column($subjects, 'totalStudents')); ?></h3>
                        <p>Jumlah Pelajar</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(248, 150, 30, 0.1); color: var(--warning);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3>
                            <?php 
                            $avg_perf = count($subjects) > 0 ? 
                                array_sum(array_column($subjects, 'averagePerformance')) / count($subjects) : 0;
                                echo number_format($avg_perf, 1);
                            ?>%
                        </h3>
                        <p>Purata Prestasi</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(247, 37, 133, 0.1); color: var(--danger);">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-content">
                        <h3>
                            <?php 
                            $avg_prog = count($subjects) > 0 ? 
                                array_sum(array_column($subjects, 'syllabusProgress')) / count($subjects) : 0;
                                echo number_format($avg_prog, 1);
                            ?>%
                        </h3>
                        <p>Kemajuan Sukatan</p>
                    </div>
                </div>
            </div>

            <!-- Subjects Grid -->
            <div class="subjects-grid" id="subjectsGrid">
                <?php if (empty($subjects)): ?>
                    <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                        <div style="font-size: 3rem; color: var(--light-gray); margin-bottom: 20px;">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 style="color: var(--gray); margin-bottom: 10px;">Tiada Subjek Dijumpai</h3>
                        <p style="color: var(--gray); margin-bottom: 20px;">Anda belum menambah sebarang subjek lagi.</p>
                        <button class="btn btn-primary" onclick="openEditModal()">
                            <i class="fas fa-plus"></i> Tambah Subjek Pertama
                        </button>
                    </div>
                <?php else: ?>
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
                            
                            <?php if ($subject['description']): ?>
                            <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">
                                <?php echo htmlspecialchars($subject['description']); ?>
                            </p>
                            <?php endif; ?>
                            
                            <div class="subject-classes">
                                <span style="font-weight: 600; color: var(--dark);">Kelas:</span>
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
                                <button class="btn-icon" onclick="viewSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                                <button class="btn-icon" onclick="editSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn-icon" onclick="deleteSubject('<?php echo $subject['id']; ?>')">
                                    <i class="fas fa-trash"></i> Padam
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Display -->
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
    </div>

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
                                   placeholder="Contoh: Matematik, Sains" required
                                   value="<?php echo isset($_POST['subject_name']) ? htmlspecialchars($_POST['subject_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Kod Subjek</label>
                            <input type="text" class="form-input" id="subjectCode" name="subject_code" 
                                   placeholder="Contoh: MAT601, SNS601" required
                                   value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jenis Subjek</label>
                            <select class="form-select" id="subjectType" name="subject_type" required>
                                <option value="">Pilih Jenis</option>
                                <option value="core" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'core') ? 'selected' : ''; ?>>Teras</option>
                                <option value="elective" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'elective') ? 'selected' : ''; ?>>Elektif</option>
                                <option value="additional" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'additional') ? 'selected' : ''; ?>>Tambahan</option>
                                <option value="extracurricular" <?php echo (isset($_POST['subject_type']) && $_POST['subject_type'] == 'extracurricular') ? 'selected' : ''; ?>>Kokurikulum</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="subjectYear" name="subject_year" required>
                                <option value="">Pilih Tahun</option>
                                <option value="all" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == 'all') ? 'selected' : ''; ?>>Semua Tahun</option>
                                <option value="1-6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '1-6') ? 'selected' : ''; ?>>Tahun 1-6</option>
                                <option value="6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '6') ? 'selected' : ''; ?>>Tahun 6</option>
                                <option value="5" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '5') ? 'selected' : ''; ?>>Tahun 5</option>
                                <option value="4-6" <?php echo (isset($_POST['subject_year']) && $_POST['subject_year'] == '4-6') ? 'selected' : ''; ?>>Tahun 4-6</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Penerangan Subjek</label>
                        <textarea class="form-textarea" id="subjectDescription" name="subject_description" 
                                  placeholder="Penerangan ringkas mengenai subjek..." rows="3"><?php echo isset($_POST['subject_description']) ? htmlspecialchars($_POST['subject_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Buku Teks Utama</label>
                            <input type="text" class="form-input" id="subjectTextbook" name="subject_textbook" 
                                   placeholder="Nama buku teks"
                                   value="<?php echo isset($_POST['subject_textbook']) ? htmlspecialchars($_POST['subject_textbook']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <input type="text" class="form-input" id="subjectNotes" name="subject_notes" 
                                   placeholder="Catatan tambahan"
                                   value="<?php echo isset($_POST['subject_notes']) ? htmlspecialchars($_POST['subject_notes']) : ''; ?>">
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
        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Total subjects loaded: <?php echo count($subjects); ?>");
            
            // Set up form submit handler
            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function(event) {
                    // Validate form
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.style.borderColor = 'var(--danger)';
                        } else {
                            field.style.borderColor = '';
                        }
                    });
                    
                    if (!isValid) {
                        event.preventDefault();
                        alert('Sila isi semua ruangan yang diperlukan!');
                        return;
                    }
                    
                    // Show loading state
                    if (submitBtn) {
                        const originalHTML = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                        submitBtn.disabled = true;
                        
                        // Revert after 5 seconds if still submitting
                        setTimeout(() => {
                            submitBtn.innerHTML = originalHTML;
                            submitBtn.disabled = false;
                        }, 5000);
                    }
                    
                    // Form will submit normally to PHP
                });
            }
            
            // Auto-hide messages after 5 seconds
            setTimeout(() => {
                const messages = document.querySelectorAll('.alert-message');
                messages.forEach(msg => {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);
            
            // Check if modal should open from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('openModal')) {
                openEditModal();
            }
        });
        
        // Modal functions
        function openEditModal() {
            document.getElementById('editSubjectModal').style.display = 'flex';
            document.getElementById('editModalTitle').textContent = 'Tambah Subjek Baru';
            document.getElementById('subjectForm').reset();
            
            // Clear previous error styling
            const fields = document.querySelectorAll('.form-input, .form-select');
            fields.forEach(field => field.style.borderColor = '');
        }
        
        function closeEditModal() {
            document.getElementById('editSubjectModal').style.display = 'none';
        }
        
        function editSubject(subjectId) {
            alert('Fungsi edit untuk subjek ID: ' + subjectId);
            // You can implement edit functionality here
            openEditModal();
        }
        
        function viewSubject(subjectId) {
            alert('Melihat subjek ID: ' + subjectId);
            // You can implement view functionality here
        }
        
        function deleteSubject(subjectId) {
            if (confirm('Adakah anda pasti mahu memadam subjek ini?')) {
                alert('Memadam subjek ID: ' + subjectId);
                // You can implement delete functionality here
            }
        }
        
        function exportData() {
            alert('Fungsi export akan dimuat turun data subjek.');
            // You can implement export functionality here
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editSubjectModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>