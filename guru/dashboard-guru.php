<?php
session_start();
// Debug session
error_log("Session contents: " . print_r($_SESSION, true));

// Check login - PASTIKAN nama session betul
if (!isset($_SESSION['guru_id'])) {
    error_log("No guru_id in session, redirecting to login");
    header('Location: login-guru.php');
    exit();
}

$id_guru = $_SESSION['guru_id'];
error_log("guru_id from session: " . $id_guru);

// Include database connection
require_once __DIR__ . '/../config/connect.php';

// Debug database connection
if (!$database) {
    die("Database connection failed: " . mysqli_connect_error());
}

$current_page = basename($_SERVER['PHP_SELF']);

// Get teacher info
$sql_guru = "SELECT * FROM guru WHERE id = ?";
$stmt_guru = $database->prepare($sql_guru);
$stmt_guru->bind_param("i", $id_guru);
$stmt_guru->execute();
$guru = $stmt_guru->get_result()->fetch_assoc();

// Get classes taught (as class teacher)
$sql_kelas = "SELECT k.* FROM kelas k 
              WHERE k.id_guru = ? AND k.status = 'aktif'";
$stmt_kelas = $database->prepare($sql_kelas);
$stmt_kelas->bind_param("i", $id_guru);
$stmt_kelas->execute();
$kelas_result = $stmt_kelas->get_result();
$kelas_count = $kelas_result->num_rows;

// Get subjects taught
$sql_subjek = "SELECT DISTINCT mp.* 
               FROM guru_mata_pelajaran gmp
               JOIN mata_pelajaran mp ON gmp.mata_pelajaran_id = mp.id
               WHERE gmp.id_guru = ? 
               AND gmp.status = 1
               AND mp.status = 1";
$stmt_subjek = $database->prepare($sql_subjek);
$stmt_subjek->bind_param("i", $id_guru);
$stmt_subjek->execute();
$subjek_result = $stmt_subjek->get_result();
$subjek_count = $subjek_result->num_rows;

// Get total students
$sql_students = "SELECT COUNT(*) as total FROM pelajar p
                 JOIN kelas k ON p.id_kelas = k.id
                 WHERE k.id_guru = ? AND p.status = 'aktif'";
$stmt_students = $database->prepare($sql_students);
$stmt_students->bind_param("i", $id_guru);
$stmt_students->execute();
$student_count_result = $stmt_students->get_result();
$total_students = $student_count_result->fetch_assoc()['total'];

// Get unmarked exams
$sql_unmarked = "SELECT COUNT(*) as total 
                 FROM penilaian p
                 JOIN guru_mata_pelajaran gmp ON p.mata_pelajaran_id = gmp.mata_pelajaran_id
                 WHERE gmp.id_guru = ? AND (p.gred IS NULL OR p.gred = '')";
$stmt_unmarked = $database->prepare($sql_unmarked);
$stmt_unmarked->bind_param("i", $id_guru);
$stmt_unmarked->execute();
$unmarked_result = $stmt_unmarked->get_result();
$unmarked_count = $unmarked_result->fetch_assoc()['total'] ?? 0;

// Get teacher initials for avatar
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
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Guru - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            color: var(--medium-gray);
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

        /* Quick Stats */
        .quick-stats {
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

        .stat-icon.students { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .stat-icon.exams { background: linear-gradient(135deg, #10b981, #34d399); }
        .stat-icon.subjects { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-icon.classes { background: linear-gradient(135deg, #ef4444, #f87171); }

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

        .stat-change {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            color: var(--dark-gray);
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        /* Dashboard Cards */
        .dashboard-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Recent Exams List */
        .exam-list {
            list-style-type: none;
        }

        .exam-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .exam-item:hover {
            background: var(--light-gray);
        }

        .exam-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-graded {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-upcoming {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* Class Performance */
        .class-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .class-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 12px;
            transition: var(--transition);
        }

        .class-item:hover {
            background: var(--primary-light);
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
            
            .dashboard-sections {
                grid-template-columns: 1fr;
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
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .quick-stats {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .btn-sm {
                padding: 8px 16px !important;
                font-size: 13px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a href="dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Dashboard <?php echo isset($_SESSION['guru_role']) ? htmlspecialchars($_SESSION['guru_role']) : 'Guru'; ?></p>
                </div>
            </a>

            <div class="user-profile">
                <div class="user-avatar"><?php echo $initials; ?></div>
                <div class="user-info">
                    <h4><?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?></h4>
                    <p><?php echo isset($_SESSION['guru_role']) ? htmlspecialchars($_SESSION['guru_role']) : 'Guru'; ?></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu Utama</div>
            <a href="dashboard-guru.php" class="sidebar-item <?php echo ($current_page == 'dashboard-guru.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="modules/kelas-saya.php" class="sidebar-item">
                <i class="fas fa-users"></i>
                Kelas Saya
                <?php if ($kelas_count > 0): ?>
                <span class="badge"><?php echo $kelas_count; ?></span>
                <?php endif; ?>
            </a>
            <a href="modules/pelajar-saya.php" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <?php if ($total_students > 0): ?>
                <span class="badge"><?php echo $total_students; ?></span>
                <?php endif; ?>
            </a>
            <a href="modules/subjek-saya.php" class="sidebar-item">
                <i class="fas fa-book"></i>
                Subjek Saya
                <?php if ($subjek_count > 0): ?>
                <span class="badge"><?php echo $subjek_count; ?></span>
                <?php endif; ?>
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
        <div class="page-header">
            <div class="page-title">
                <h2>Selamat Datang, <?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?>! 👨‍🏫</h2>
                <p>Dashboard pentadbir untuk urusan akademik dan pentadbiran guru</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i> Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahTugasan()">
                    <i class="fas fa-plus-circle"></i> Tugasan Baru
                </button>
            </div>
        </div>

        <!-- Quick Stats with REAL DATA -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value"><?php echo $total_students; ?></div>
                    <?php if ($total_students > 0): ?>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> <?php echo $total_students; ?> pelajar aktif
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon exams">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>UJIAN BELUM DINILAI</h3>
                    <div class="stat-value"><?php echo $unmarked_count; ?></div>
                    <?php if ($unmarked_count > 0): ?>
                    <div class="stat-change negative">
                        <i class="fas fa-exclamation-circle"></i> Perlu dinilai
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon subjects">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>SUBJEK DIAJAR</h3>
                    <div class="stat-value"><?php echo $subjek_count; ?></div>
                    <div class="stat-change">
                        <?php 
                        // Get subject names
                        $subject_names = [];
                        $subjek_result->data_seek(0); // Reset pointer
                        while ($subject = $subjek_result->fetch_assoc()) {
                            $subject_names[] = $subject['nama'];
                        }
                        echo implode(', ', array_slice($subject_names, 0, 3));
                        if (count($subject_names) > 3) echo '...';
                        ?>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>KELAS DIKENDALIKAN</h3>
                    <div class="stat-value"><?php echo $kelas_count; ?></div>
                    <div class="stat-change">
                        <?php 
                        // Get class names
                        $class_names = [];
                        $kelas_result->data_seek(0); // Reset pointer
                        while ($kelas = $kelas_result->fetch_assoc()) {
                            $class_names[] = $kelas['nama'];
                        }
                        echo implode(', ', $class_names);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="modules/tambah-markah.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4>Tambah Markah</h4>
                <p>Masukkan markah peperiksaan baru</p>
            </a>

            <a href="modules/semak-markah.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4>Semak Markah</h4>
                <p>Semak dan analisis markah pelajar</p>
            </a>

            <a href="modules/jadual-ujian.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h4>Jadual Ujian</h4>
                <p>Urus jadual peperiksaan</p>
            </a>

            <a href="modules/laporan-prestasi.php" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4>Jana Laporan</h4>
                <p>Hasilkan laporan prestasi</p>
            </a>
        </div>

        <!-- Dashboard Sections -->
        <div class="dashboard-sections">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Ujian Terkini</h3>
                    <button class="btn btn-secondary btn-sm">Lihat Semua</button>
                </div>
                <div class="exam-list">
                    <?php
                    // Get recent exams
                    $sql_exams = "SELECT p.*, mp.nama as mata_pelajaran_nama, k.nama as kelas_nama
                                 FROM penilaian p
                                 JOIN mata_pelajaran mp ON p.mata_pelajaran_id = mp.id
                                 JOIN pelajar pl ON p.murid_id = pl.id
                                 JOIN kelas k ON pl.id_kelas = k.id
                                 JOIN guru_mata_pelajaran gmp ON (gmp.mata_pelajaran_id = mp.id AND gmp.kelas_id = k.id)
                                 WHERE gmp.id_guru = ?
                                 ORDER BY p.tarikh DESC
                                 LIMIT 5";
                    
                    $stmt_exams = $database->prepare($sql_exams);
                    $stmt_exams->bind_param("i", $id_guru);
                    $stmt_exams->execute();
                    $exams_result = $stmt_exams->get_result();
                    
                    if ($exams_result->num_rows > 0) {
                        while ($exam = $exams_result->fetch_assoc()) {
                            $status_class = (!empty($exam['gred'])) ? 'status-graded' : 'status-upcoming';
                            $status_text = (!empty($exam['gred'])) ? 'Telah Dinilai' : 'Belum Dinilai';
                            
                            echo '
                            <div class="exam-item">
                                <div class="exam-info">
                                    <h4>' . htmlspecialchars($exam['mata_pelajaran_nama']) . ' - ' . htmlspecialchars($exam['kelas_nama']) . '</h4>
                                    <p>' . htmlspecialchars($exam['jenis_penilaian']) . ' • ' . date('d M Y', strtotime($exam['tarikh'])) . '</p>
                                </div>
                                <span class="exam-status ' . $status_class . '">' . $status_text . '</span>
                            </div>';
                        }
                    } else {
                        echo '<div class="exam-item">
                                <div class="exam-info">
                                    <h4>Tiada ujian terkini</h4>
                                    <p>Belum ada rekod ujian</p>
                                </div>
                              </div>';
                    }
                    ?>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Prestasi Kelas</h3>
                    <button class="btn btn-secondary btn-sm">Analisis</button>
                </div>
                <div class="class-list">
                    <?php
                    // Get class performance
                    $kelas_result->data_seek(0); // Reset pointer
                    while ($kelas = $kelas_result->fetch_assoc()) {
                        // Get average performance for this class
                        $sql_performance = "SELECT AVG(p.markah) as avg_performance 
                                           FROM penilaian p
                                           JOIN pelajar pl ON p.murid_id = pl.id
                                           JOIN guru_mata_pelajaran gmp ON p.mata_pelajaran_id = gmp.mata_pelajaran_id
                                           WHERE pl.id_kelas = ?
                                           AND gmp.id_guru = ?";
                        
                        $stmt_perf = $database->prepare($sql_performance);
                        $stmt_perf->bind_param("ii", $kelas['id'], $id_guru);
                        $stmt_perf->execute();
                        $perf_result = $stmt_perf->get_result();
                        $performance = $perf_result->fetch_assoc();
                        $avg_performance = $performance['avg_performance'] ?? 0;
                        
                        echo '
                        <div class="class-item">
                            <div class="class-name">' . htmlspecialchars($kelas['nama']) . '</div>
                            <div class="class-average">' . number_format($avg_performance, 1) . '%</div>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="dashboard-card" style="margin-top: 30px; text-align: center;">
            <h3 style="color: var(--primary); margin-bottom: 10px;">
                <i class="fas fa-file-alt"></i> PEPERIKSAAN & PENILAIAN
            </h3>
            <p style="color: var(--medium-gray);">Sistem Pengurusan Sekolah <?php echo date('Y'); ?></p>
        </div>
    </main>

    <script>
        // Sidebar Toggle Function
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            
            // Adjust main content margin on mobile
            if (window.innerWidth <= 1024) {
                if (sidebar.classList.contains('active')) {
                    mainContent.style.marginLeft = '250px';
                } else {
                    mainContent.style.marginLeft = '0';
                }
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                    mainContent.style.marginLeft = '0';
                }
            }
        });

        // Add active class to clicked sidebar item
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                // Close sidebar on mobile after clicking
                if (window.innerWidth <= 1024) {
                    sidebar.classList.remove('active');
                    mainContent.style.marginLeft = '0';
                }
            });
        });

        // Simple functions for buttons
        function muatSemulaData() {
            alert('Data sedang dimuat semula...');
            location.reload();
        }

        function tambahTugasan() {
            alert('Membuka halaman tambah tugasan baru');
        }
    </script>
</body>
</html>
<?php
// Close database connections
$stmt_guru->close();
$stmt_kelas->close();
$stmt_subjek->close();
$stmt_students->close();
$stmt_unmarked->close();
if (isset($stmt_exams)) $stmt_exams->close();
if (isset($stmt_perf)) $stmt_perf->close();
?>