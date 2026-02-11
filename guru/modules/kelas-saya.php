<?php  
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// PATH FLEXIBLE
$possible_paths = [
    __DIR__ . '/../config/connect.php',
    __DIR__ . '/../../config/connect.php',
    dirname(__DIR__) . '/config/connect.php',
    dirname(dirname(__DIR__)) . '/config/connect.php',
    $_SERVER['DOCUMENT_ROOT'] . '/dashboard/SlipKu/config/connect.php',
    'C:/xampp/htdocs/dashboard/SlipKu/config/connect.php'
];

$connected = false;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $connected = true;
        break;
    }
}

if (!$connected) {
    die("<h3>ERROR: Cannot find connect.php</h3>
         <p>Please check the file path.</p>");
}

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login-guru.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'kelas-saya.php';

// Get teacher info for avatar
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    $name_parts = explode(' ', $_SESSION['guru_nama']);
    foreach ($name_parts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2);
}

// Database connection
$db = isset($conn) ? $conn : (isset($database) ? $database : null);


// GET KELAS - Hanya kelas yang diajar oleh guru ini

$classes = [];
$total_murid_keseluruhan = 0;
$total_prestasi = 0;

try {
  
   $sql = "SELECT 
            k.id, 
            k.nama,
            k.tingkatan,
            k.tahun,
            COUNT(DISTINCT pk.id_pelajar) as total_murid,
            COALESCE(AVG(m.markah), 0) as average_performance
        FROM pengajar pj
        JOIN kelas k ON pj.id_kelas = k.id
        LEFT JOIN pendaftaran_kelas pk ON k.id = pk.id_kelas AND pk.status = 'aktif'
        LEFT JOIN pelajar p ON pk.id_pelajar = p.id AND p.status = 'aktif'
        LEFT JOIN peperiksaan pe ON k.id = pe.id_kelas
        LEFT JOIN markah m ON pe.id = m.id_peperiksaan AND m.status = 'aktif'
        WHERE pj.id_guru = ? 
            AND pj.status = 'aktif' 
            AND k.status = 'aktif'
        GROUP BY k.id, k.nama, k.tingkatan, k.tahun
        ORDER BY k.tingkatan, k.nama";
    
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $guru_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $total_murid = (int)($row['total_murid'] ?? 0);
            $avg_performance = round((float)($row['average_performance'] ?? 0), 1);
            
            $classes[] = [
                'id' => $row['id'],
                'nama' => $row['nama'],
                'tingkatan' => $row['tingkatan'] ?? '',
                'tahun' => $row['tahun'] ?? date('Y'),
                'total_murid' => $total_murid,
                'average_performance' => $avg_performance
            ];
            
            $total_murid_keseluruhan += $total_murid;
            $total_prestasi += $avg_performance;
        }
        $stmt->close();
 
        }
} catch (Exception $e) {
    error_log("Exception: " . $e->getMessage());
}


$totalClasses = count($classes);
$totalStudents = $total_murid_keseluruhan;
$avgPerformance = $totalClasses > 0 ? round($total_prestasi / $totalClasses, 1) : 0;

// Get counts for sidebar badges
$total_students = 0;
$unmarked_count = 0;
$subjek_count = 0;

try {
    // Total students
    $sql_students = "SELECT COUNT(*) as total FROM pelajar WHERE status = 'aktif'";
    $stmt_students = $db->prepare($sql_students);
    $stmt_students->execute();
    $total_students = $stmt_students->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_students->close();
    
    // Unmarked exams
    $sql_unmarked = "SELECT COUNT(*) as total 
                     FROM markah m
                     JOIN peperiksaan p ON m.id_peperiksaan = p.id
                     JOIN pengajar pj ON p.id_matapelajaran = pj.id_matapelajaran 
                        AND p.id_kelas = pj.id_kelas
                     WHERE pj.id_guru = ? 
                        AND (m.gred IS NULL OR m.gred = '')
                        AND m.status = 'aktif'";
    $stmt_unmarked = $db->prepare($sql_unmarked);
    $stmt_unmarked->bind_param("i", $guru_id);
    $stmt_unmarked->execute();
    $unmarked_count = $stmt_unmarked->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_unmarked->close();
    
    // Subjects count
    $sql_subjek = "SELECT COUNT(DISTINCT id_matapelajaran) as total 
                   FROM pengajar 
                   WHERE id_guru = ? AND status = 'aktif'";
    $stmt_subjek = $db->prepare($sql_subjek);
    $stmt_subjek->bind_param("i", $guru_id);
    $stmt_subjek->execute();
    $subjek_count = $stmt_subjek->get_result()->fetch_assoc()['total'] ?? 0;
    $stmt_subjek->close();
    
} catch (Exception $e) {
    error_log("Error getting counts: " . $e->getMessage());
}


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

        /* Quick Stats */
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

        /* Class Table Container */
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

        /* Class Info Cell */
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

        /* Modal for Class Details */
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

        /* Mobile Responsive */
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
            
            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
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
            
            .quick-stats {
                grid-template-columns: 1fr;
            }
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
    </style>
</head>
<body>
    <!-- Modal for Class Details -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Maklumat Kelas</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="class-detail-header" style="text-align: center; margin-bottom: 25px;">
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
                
                <div style="margin-bottom: 20px;">
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

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu Utama</div>
            <a href="../dashboard-guru.php" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="kelas-saya.php" class="sidebar-item active">
                <i class="fas fa-users"></i>
                Kelas Saya
                <?php if ($totalClasses > 0): ?>
                <span class="badge"><?php echo $totalClasses; ?></span>
                <?php endif; ?>
            </a>
            <a href="pelajar-saya.php" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <?php if ($totalStudents > 0): ?>
                <span class="badge"><?php echo $totalStudents; ?></span>
                <?php endif; ?>
            </a>
            <a href="subjek-saya.php" class="sidebar-item">
                <i class="fas fa-book"></i>
                Subjek Saya
                <?php if ($subjek_count > 0): ?>
                <span class="badge"><?php echo $subjek_count; ?></span>
                <?php endif; ?>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan & Penilaian</div>
            <a href="tambah-markah.php" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="semak-markah.php" class="sidebar-item">
                <i class="fas fa-search"></i>
                Semak Markah
                <?php if ($unmarked_count > 0): ?>
                <span class="badge"><?php echo $unmarked_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="laporan-prestasi.php" class="sidebar-item">
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
            <a href="../logout.php" class="sidebar-item" style="color: var(--danger);">
                <i class="fas fa-sign-out-alt"></i>
                Log Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <h2>Kelas saya 🏫</h2>
                <p>Urus dan pantau semua kelas yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH KELAS</h3>
                    <div class="stat-value"><?php echo $totalClasses; ?></div>
                    <div class="stat-change">Kelas yang anda ajar</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value"><?php echo $totalStudents; ?></div>
                    <div class="stat-change">Dalam kelas anda</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>PRESTASI PURATA</h3>
                    <div class="stat-value"><?php echo number_format($avgPerformance, 1); ?>%</div>
                    <div class="stat-change">Keseluruhan kelas</div>
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
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                      <?php foreach ($classes as $class): ?>
                        <tr>
                            <td>
                                <div class="class-info-cell">
                                    <div class="class-icon">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div class="class-details">
                                        <div class="class-name"><?php echo htmlspecialchars($class['nama']); ?></div>
                                        <div class="class-subject">Tingkatan <?php echo htmlspecialchars($class['tingkatan']); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($class['tahun']); ?></td>
                            <td style="font-weight: 600;"><?php echo $class['total_murid']; ?> pelajar</td>
                            <td>
                                <div class="performance-cell">
                                    <div class="performance-bar">
                                        <div class="performance-fill <?php 
                                            $perf = $class['average_performance'];
                                            if ($perf >= 80) echo 'performance-excellent';
                                            elseif ($perf >= 70) echo 'performance-good';
                                            elseif ($perf >= 60) echo 'performance-average';
                                            else echo 'performance-poor';
                                        ?>" style="width: <?php echo min(100, $perf); ?>%"></div>
                                    </div>
                                    <div class="performance-value"><?php echo number_format($perf, 1); ?>%</div>
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
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const classModal = document.getElementById('classModal');

        // PHP data to JavaScript
        const classesData = <?php echo json_encode($classes); ?>;
        const teacherName = "<?php echo htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru'); ?>";

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            if (window.innerWidth <= 1024) {
                mainContent.classList.toggle('full-width');
            }
        }

        // Close Sidebar
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('sidebar-active');
                mainContent.classList.remove('full-width');
            }
        }

        // View Class Details
        function viewClass(classId, className) {
            const classData = classesData.find(c => c.id == classId);
            
            if (classData) {
              document.getElementById('modalTitle').textContent = 'Maklumat Kelas: ' + className;
                document.getElementById('classNameDetail').textContent = className;
                document.getElementById('classLevelDetail').textContent = `Tingkatan ${classData.tingkatan || ''}`;
                document.getElementById('classTeacherDetail').textContent = teacherName;
                document.getElementById('classYearDetail').textContent = classData.tahun || '2026';
                document.getElementById('classPerformanceDetail').textContent = classData.average_performance ? 
                    classData.average_performance.toFixed(1) + '%' : 'Tiada data';
                document.getElementById('classStudentsDetail').textContent = `${classData.total_murid} pelajar`;
                
                // Load students
                loadStudentList(classId);
             
                classModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        // Load Student List
        function loadStudentList(classId) {
            const studentListDiv = document.getElementById('studentListModal');
          
            studentListDiv.innerHTML = `
                <div style="text-align: center; padding: 30px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                    <p style="color: var(--medium-gray); margin-top: 15px;">Memuatkan senarai pelajar...</p>
                </div>
            `;
        
            fetch(`ajax/get-students-by-class.php?class_id=${classId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.students.length > 0) {
                        let html = '<div style="display: flex; flex-direction: column; gap: 10px;">';
                        data.students.forEach(student => {
                            html += `
                                <div style="display: flex; align-items: center; gap: 15px; padding: 12px; background: var(--light-gray); border-radius: 10px;">
                                    <div style="width: 40px; height: 40px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-weight: 600;">
                                        ${student.initials || 'P'}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--dark-gray);">${student.nama}</div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">No. KP: ${student.no_kp || 'Tiada'}</div>
                                    </div>
                                    <button class="action-btn view" onclick="viewStudent(${student.id})" style="padding: 6px 12px;">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                </div>
                            `;
                        });
                        html += '</div>';
                        studentListDiv.innerHTML = html;
                    } else {
                        studentListDiv.innerHTML = `
                            <div style="text-align: center; padding: 40px;">
                                <i class="fas fa-user-graduate" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: var(--medium-gray);">Tiada pelajar dalam kelas ini.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentListDiv.innerHTML = `
                        <div style="text-align: center; padding: 40px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: var(--warning); margin-bottom: 15px;"></i>
                            <p style="color: var(--medium-gray);">Ralat memuatkan data pelajar.</p>
                       </div>
                    `;
                });
        }

        // View Student
        function viewStudent(studentId) {
            window.location.href = `pelajar-detail.php?id=${studentId}`;
        }

        // Edit Class
        function editClass(classId) {
            window.location.href = `edit-kelas.php?id=${classId}`;
        }

        // Close Modal
        function closeModal() {
            classModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
         if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
        
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
        
            window.addEventListener('resize', closeSidebar);
           
            if (classModal) {
                classModal.addEventListener('click', function(event) {
                    if (event.target === classModal) {
                        closeModal();
                    }
                });
            }
         
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>
<?php
// Close database connection
if (isset($stmt)) $stmt->close();
if (isset($stmt_students)) $stmt_students->close();
if (isset($stmt_unmarked)) $stmt_unmarked->close();
if (isset($stmt_subjek)) $stmt_subjek->close();
if (isset($db)) $db->close();
?>