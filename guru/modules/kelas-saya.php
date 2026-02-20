<?php
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
$current_page = 'kelas-saya.php';

// Initials
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    foreach (explode(' ', $_SESSION['guru_nama']) as $part)
        if (!empty($part)) $initials .= strtoupper(substr($part, 0, 1));
    $initials = substr($initials, 0, 2);
}

$success_message = '';
$error_message = '';

// Handle TAMBAH KELAS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah_kelas') {
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $tahun = trim($_POST['tahun'] ?? date('Y'));
    
    if (empty($nama_kelas)) {
        $error_message = 'Sila isi nama kelas.';
    } else {
        try {
            $conn->begin_transaction();
            $sql_ins = "INSERT INTO kelas (nama, tahun, id_guru, status) VALUES (?, ?, ?, 'aktif')";
            $stmt_ins = $conn->prepare($sql_ins);
            $stmt_ins->bind_param("sii", $nama_kelas, $tahun, $guru_id);
            $stmt_ins->execute();
            $kelas_id = $conn->insert_id;
            $stmt_ins->close();
            
            // Insert into pengajar
            $sql_p = "INSERT INTO pengajar (id_kelas, id_guru, tahun_akademik, status) VALUES (?, ?, ?, 'aktif')";
            $tahun_akademik = "$tahun";
            $stmt_p = $conn->prepare($sql_p);
            $stmt_p->bind_param("iis", $kelas_id, $guru_id, $tahun_akademik);
            $stmt_p->execute();
            $stmt_p->close();
            
            $conn->commit();
            header("Location: kelas-saya.php?success=1"); exit();
        } catch (Exception $e) {
            $conn->rollback();
            $error_message = 'Ralat: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) $success_message = 'Kelas berjaya ditambah!';

// GET KELAS - kelas yang diajar guru ini
$classes = [];
$total_murid_keseluruhan = 0;

try {
    $sql = "SELECT k.id, k.nama, k.tahun,
                COUNT(DISTINCT p.id) as total_murid,
                COALESCE(AVG(m.markah), 0) as average_performance
            FROM pengajar pj
            JOIN kelas k ON pj.id_kelas = k.id
            LEFT JOIN pelajar p ON k.id = p.id_kelas AND p.status = 'aktif'
            LEFT JOIN markah m ON m.id_pelajar = p.id AND m.status = 'aktif'
            WHERE pj.id_guru = ? AND pj.status = 'aktif' AND k.status = 'aktif'
            GROUP BY k.id, k.nama, k.tahun
            ORDER BY k.tahun DESC, k.nama ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $classes[] = [
            'id' => $row['id'],
            'nama' => $row['nama'],
            'tahun' => $row['tahun'] ?? date('Y'),
            'total_murid' => (int)$row['total_murid'],
            'average_performance' => round((float)$row['average_performance'], 1)
        ];
        $total_murid_keseluruhan += (int)$row['total_murid'];
    }
    $stmt->close();
} catch (Exception $e) { error_log($e->getMessage()); }

$totalClasses = count($classes);
$totalStudents = $total_murid_keseluruhan;
$avgPerformance = $totalClasses > 0 ? round(array_sum(array_column($classes, 'average_performance')) / $totalClasses, 1) : 0;

// Sidebar badge counts
$total_students = $totalStudents;
$subjek_count = 0;
try {
    $ss = $conn->prepare("SELECT COUNT(DISTINCT id_matapelajaran) as c FROM pengajar WHERE id_guru = ? AND status = 'aktif'");
    $ss->bind_param("i", $guru_id);
    $ss->execute();
    $subjek_count = $ss->get_result()->fetch_assoc()['c'] ?? 0;
    $ss->close();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya - SlipKu</title>
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

        
        .main-content {
            margin-left: 260px;
            margin-top: 85px;
            padding: 30px;
            transition: var(--transition);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        
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

        .btn-success {
            background: linear-gradient(135deg, var(--success), #059669);
            color: var(--white);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.4);
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

        
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1));
            border-left: 4px solid var(--danger);
            color: var(--danger);
        }

        .alert i {
            font-size: 20px;
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

        
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.classes {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .stat-icon.students {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.performance {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-info h3 {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark-gray);
            line-height: 1;
            margin-bottom: 5px;
        }

        
        .class-table-container {
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
            min-width: 800px;
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

        
        .class-info-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .class-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .class-details {
            display: flex;
            flex-direction: column;
        }

        .class-name {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
            font-size: 16px;
        }

        .class-subject {
            font-size: 13px;
            color: var(--medium-gray);
        }

        
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

        
        .action-cell {
            display: flex;
            gap: 8px;
        }

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
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 2px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-light), var(--white));
        
        }
    

        .modal-header h3 {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }



        .modal-close {
            background: none;
            border: none;
            font-size: 22px;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            padding: 8px;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: var(--light-gray);
            color: var(--danger);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            padding: 20px 30px 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            border-top: 1px solid var(--light-gray);
        }


        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .form-label i {
            color: var(--primary);
            margin-right: 8px;
            width: 18px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
            background: var(--white);
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }



        .form-select {

            cursor: pointer;

            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            padding-right: 50px;
        }



        .form-text {
            font-size: 12px;
            color: var(--medium-gray);
            margin-top: 6px;
            margin-left: 5px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }


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

        }


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
        }

        @media (max-width: 768px) {
            .header {
             
                padding: 0 20px;

            

            }
            
            .main-content {
                padding: 20px;
                margin-top: 75px;
            }



            
            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
           
            .form-row {
                grid-template-columns: 1fr;
           
            }
        }

        @media (max-width: 576px) {
           
            .quick-stats {
                grid-template-columns: 1fr;
            }
       
        }
    </style>
</head>
<body>
    <!-- MODAL: Maklumat Kelas Details -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Maklumat Kelas</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 25px;">
                    <div class="class-icon" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 id="classNameDetail" style="font-size: 24px; color: var(--primary); margin-bottom: 5px;">Loading...</h3>
                    <p id="classLevelDetail" style="color: var(--medium-gray); font-size: 16px;"></p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px;">
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Guru Kelas</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classTeacherDetail"><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru'); ?></div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Tahun</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classYearDetail">Loading...</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Prestasi Purata</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classPerformanceDetail">Loading...</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Jumlah Pelajar</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classStudentsDetail">Loading...</div>
                    </div>
                </div>
                
                <div>
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Senarai Pelajar</h4>
                    <div id="studentListModal" style="max-height: 300px; overflow-y: auto;">
                        <div style="text-align: center; padding: 30px;">
                            <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                            <p style="color: var(--medium-gray); margin-top: 15px;">Memuatkan senarai pelajar...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Tambah Kelas Baru -->
    <div class="modal" id="addClassModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>
                    <i class="fas fa-plus-circle"></i>
                    Tambah Kelas Baru
                </h3>
                <button class="modal-close" onclick="closeAddClassModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="tambah_kelas">
                <div class="modal-body">
                    <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>

                    
                    
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-school"></i>
                            Nama Kelas
                        </label>
                        <input type="text" class="form-control" name="nama_kelas" 
                               placeholder="Cth: 3 Bijak, 4 Cerdas, 5 Pintar" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-layer-group"></i>
                                Darjah
                            </label>
                            <select class="form-select" name="darjah" required>
                                <option value="1">Darjah 1</option>
                                <option value="2">Darjah 2</option>
                                <option value="3">Darjah 3</option>
                                <option value="4">Darjah 4</option>
                                <option value="5">Darjah 5</option>
                                <option value="6">Darjah 6</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i>
                                Tahun
                            </label>
                            <select class="form-select" name="tahun" required>
                                <option value="">Pilih Tahun</option>
                                <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo $y; ?>" <?php echo ($y == date('Y')) ? 'selected' : ''; ?>>
                                    <?php echo $y; ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                 
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddClassModal()">
                    
                    Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a href="../dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Kelas Saya</p>
                </div>
            </a>

            <div class="user-profile">
                <div class="user-avatar"><?php echo $initials; ?></div>
                <div class="user-info">
                    <h4><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru'); ?></h4>
                    <p>Guru</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar - Include dari includes/sidebar.php -->
    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <h2>Kelas Saya 🏫</h2>
                <p>Urus dan pantau semua kelas yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-success" onclick="openAddClassModal()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Kelas
                </button>
                <button class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
        </div>

        <?php if ($success_message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH KELAS</h3>
             
                    <div class="stat-value"><?php echo $totalClasses; ?></div>
              
                </div>
            </div>
           
            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value"><?php echo $totalStudents; ?></div>
                </div>
           
            </div>
            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>PRESTASI PURATA</h3>
            
                    <div class="stat-value"><?php echo number_format($avgPerformance, 1); ?>%</div>
                
                </div>

            </div>
        </div>

        <!-- Class Table -->
        <div class="class-table-container">
            <table>
                <thead>
                    <tr>
                        <th>KELAS</th>
                        <th>TAHUN</th>
                        <th>PELAJAR</th>
                        <th>PRESTASI</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classes)): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <h3>Tiada Kelas Ditemui</h3>
                                <p>Anda belum ditugaskan untuk mengajar mana-mana kelas.</p>
                                <button class="btn btn-success" onclick="openAddClassModal()">
                                    Tambah Kelas Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($classes as $class): ?>
                        <?php
                        $perf_class = 'performance-average';
                        if ($class['average_performance'] >= 80) $perf_class = 'performance-excellent';
                        elseif ($class['average_performance'] >= 70) $perf_class = 'performance-good';
                        elseif ($class['average_performance'] >= 60) $perf_class = 'performance-average';
                        else $perf_class = 'performance-poor';
                        ?>
                        <tr>
                            <td>
                                <div class="class-info-cell">
                                    <div class="class-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="class-details">
                                        <div class="class-name"><?php echo htmlspecialchars($class['nama']); ?></div>
                                        <div class="class-subject">Darjah <?php echo htmlspecialchars($class['tahun']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($class['tahun']); ?></td>
                            <td><?php echo $class['total_murid']; ?> pelajar</td>
                            <td>
                                <div class="performance-cell">
                                    <div class="performance-bar">
                                        <div class="performance-fill <?php echo $perf_class; ?>" 
                                             style="width: <?php echo min(100, $class['average_performance']); ?>%"></div>
                                    </div>
                                    <div class="performance-value"><?php echo number_format($class['average_performance'], 1); ?>%</div>
                                </div>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <button class="action-btn view" onclick="viewClass(<?php echo $class['id']; ?>, '<?php echo htmlspecialchars($class['nama']); ?>')">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="action-btn edit" onclick="editClass(<?php echo $class['id']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
      
        </div>
    </main>

    <script>
       
       const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
       
        const classModal = document.getElementById('classModal');
        const addClassModal = document.getElementById('addClassModal');
       
        const classesData = <?php echo json_encode($classes); ?>;



        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-active');
        });

        function openAddClassModal() {
            addClassModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }


        function closeAddClassModal() {
            addClassModal.classList.remove('active');
            document.body.style.overflow = '';
        }


        function viewClass(classId, className) {
  
        const classData = classesData.find(c => c.id == classId);
            if (classData) {
                document.getElementById('modalTitle').textContent = 'Maklumat Kelas: ' + className;
                document.getElementById('classNameDetail').textContent = className;

                document.getElementById('classLevelDetail').textContent = `Darjah ${classData.tahun || ''}`;
 
                document.getElementById('classYearDetail').textContent = classData.tahun || '2026';
                document.getElementById('classPerformanceDetail').textContent = classData.average_performance.toFixed(1) + '%';
                document.getElementById('classStudentsDetail').textContent = classData.total_murid + ' pelajar';
                
                // Mock student list
                document.getElementById('studentListModal').innerHTML = `
                    <div style="padding: 20px; text-align: center; color: var(--medium-gray);">
                        <i class="fas fa-info-circle"></i> Fungsi AJAK akan datang
                    </div>
                `;
                
                classModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }


        function closeModal() {
            classModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function editClass(classId) {
            alert('Fungsi edit akan datang. Class ID: ' + classId);
        }

        window.addEventListener('click', function(e) {
            if (e.target === classModal) closeModal();
            if (e.target === addClassModal) closeAddClassModal();
        });
    </script>
</body>
</html>
