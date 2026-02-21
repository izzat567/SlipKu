<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check login
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['guru_id'])) {
    session_unset(); session_destroy(); session_start();
    header('Location: login-guru.php'); exit();
}

// Session timeout (30 min)
$session_timeout = 1800;
if (isset($_SESSION['guru_login_time']) && (time() - $_SESSION['guru_login_time'] > $session_timeout)) {
    session_unset(); session_destroy(); session_start();
    $_SESSION['login_error'] = 'Sesi anda telah tamat. Sila log masuk semula.';
    header('Location: login-guru.php'); exit();
}
$_SESSION['guru_login_time'] = time();

require_once __DIR__ . '/../config/connect.php';

$id_guru = $_SESSION['guru_id'];

// Verify guru exists
$stmt_verify = $conn->prepare("SELECT id, nama, email FROM guru WHERE id = ? AND status = 'aktif'");
$stmt_verify->bind_param("i", $id_guru);
$stmt_verify->execute();
$verify_result = $stmt_verify->get_result();

if ($verify_result->num_rows === 0) {
    session_unset(); session_destroy(); session_start();
    header('Location: login-guru.php'); exit();
}
$guru = $verify_result->fetch_assoc();
$_SESSION['guru_nama'] = $guru['nama'];
$_SESSION['guru_email'] = $guru['email'];
$stmt_verify->close();

$current_page = 'dashboard-guru.php';

// ---- DATA DASHBOARD ----

// 1. KELAS yang diajar guru ini (dari pengajar DAN kelas.id_guru)
try {
    $sql_kelas = "SELECT DISTINCT k.id, k.nama, k.tahun,
                    COUNT(DISTINCT p.id) as jumlah_pelajar
                  FROM kelas k
                  LEFT JOIN pelajar p ON k.id = p.id_kelas AND (p.status = 'aktif' OR p.status = '1')
                  WHERE k.status = 'aktif'
                  AND (
                      k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                      OR k.id_guru = ?
                  )
                  GROUP BY k.id, k.nama, k.tahun";
    $stmt_kelas = $conn->prepare($sql_kelas);
    $stmt_kelas->bind_param("ii", $id_guru, $id_guru);
    $stmt_kelas->execute();
    $kelas_list = $stmt_kelas->get_result()->fetch_all(MYSQLI_ASSOC);
    $kelas_count = count($kelas_list);
    $stmt_kelas->close();
} catch (Exception $e) { $kelas_list = []; $kelas_count = 0; }

// 2. SUBJEK yang diajar guru ini
try {
    $sql_subjek = "SELECT DISTINCT m.id, m.nama, m.kod,
                    COUNT(DISTINCT pj.id_kelas) as jumlah_kelas
                  FROM pengajar pj
                  JOIN matapelajaran m ON pj.id_matapelajaran = m.id
                  WHERE pj.id_guru = ? AND pj.status = 'aktif' AND m.status = 'aktif'
                  GROUP BY m.id, m.nama, m.kod";
    $stmt_subjek = $conn->prepare($sql_subjek);
    $stmt_subjek->bind_param("i", $id_guru);
    $stmt_subjek->execute();
    $subjek_list = $stmt_subjek->get_result()->fetch_all(MYSQLI_ASSOC);
    $subjek_count = count($subjek_list);
    $stmt_subjek->close();
} catch (Exception $e) { $subjek_list = []; $subjek_count = 0; }

// 3. JUMLAH PELAJAR dalam kelas guru ini
try {
    $sql_pelajar = "SELECT COUNT(DISTINCT p.id) as total
                  FROM pelajar p
                  JOIN kelas k ON p.id_kelas = k.id
                  WHERE k.status = 'aktif'
                  AND (p.status = 'aktif' OR p.status = '1')
                  AND (
                      k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                      OR k.id_guru = ?
                  )";
    $stmt_pelajar = $conn->prepare($sql_pelajar);
    $stmt_pelajar->bind_param("ii", $id_guru, $id_guru);
    $stmt_pelajar->execute();
    $row = $stmt_pelajar->get_result()->fetch_assoc();
    $total_students = $row['total'] ?? 0;
    $stmt_pelajar->close();
} catch (Exception $e) { $total_students = 0; }

// 4. UJIAN BELUM DINILAI
$unmarked_count = 0;
try {
    $sql_unmarked = "SELECT COUNT(DISTINCT p.id) as total
                    FROM pelajar p
                    JOIN kelas k ON p.id_kelas = k.id
                    JOIN peperiksaan pp ON pp.status = 'aktif'
                    LEFT JOIN markah m ON p.id = m.id_pelajar AND m.id_perperiksaan = pp.id
                    WHERE k.status = 'aktif' AND m.id IS NULL
                    AND (p.status = 'aktif' OR p.status = '1')
                    AND (
                        k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                        OR k.id_guru = ?
                    )";
    $stmt_unmarked = $conn->prepare($sql_unmarked);
    if ($stmt_unmarked) {
        $stmt_unmarked->bind_param("ii", $id_guru, $id_guru);
        $stmt_unmarked->execute();
        $row = $stmt_unmarked->get_result()->fetch_assoc();
        $unmarked_count = $row['total'] ?? 0;
        $stmt_unmarked->close();
    }
} catch (Exception $e) { $unmarked_count = 0; }

// 5. PEPERIKSAAN terkini (semua peperiksaan aktif)
try {
    $sql_peperiksaan = "SELECT DISTINCT p.id, p.nama_peperiksaan, p.tarikh_mula, p.tarikh_tamat,
                          p.jenis, COALESCE(m.nama, '-') as mata_pelajaran, p.status
                        FROM peperiksaan p
                        LEFT JOIN matapelajaran m ON p.id_matapelajaran = m.id
                        WHERE p.status = 'aktif'
                        ORDER BY p.tarikh_mula DESC LIMIT 5";
    $stmt_peperiksaan = $conn->prepare($sql_peperiksaan);
    $stmt_peperiksaan->execute();
    $peperiksaan_list = $stmt_peperiksaan->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_peperiksaan->close();
} catch (Exception $e) { $peperiksaan_list = []; }

// 6. PRESTASI KELAS - purata markah
try {
    $sql_prestasi = "SELECT k.nama,
                      AVG(m.markah) as purata_markah,
                      COUNT(m.id) as jumlah_markah
                    FROM markah m
                    JOIN pelajar p ON m.id_pelajar = p.id
                    JOIN kelas k ON p.id_kelas = k.id
                    WHERE m.markah IS NOT NULL AND m.status = 'aktif'
                    AND (
                        k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                        OR k.id_guru = ?
                    )
                    GROUP BY k.id, k.nama
                    ORDER BY purata_markah DESC LIMIT 5";
    $stmt_prestasi = $conn->prepare($sql_prestasi);
    $stmt_prestasi->bind_param("ii", $id_guru, $id_guru);
    $stmt_prestasi->execute();
    $prestasi_kelas = $stmt_prestasi->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_prestasi->close();
} catch (Exception $e) { $prestasi_kelas = []; }

// Get initials
$initials = '';
if (isset($_SESSION['guru_nama'])) {
    foreach (explode(' ', $_SESSION['guru_nama']) as $part) {
        if (!empty($part)) $initials .= strtoupper(substr($part, 0, 1));
    }
    $initials = substr($initials, 0, 2);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - SlipKu</title>
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


        .main-content {
            margin-left: 260px;
            margin-top: 85px;
            padding: 30px;
            transition: var(--transition);
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

    
    <main class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <h2>Selamat Datang, <?php echo isset($_SESSION['guru_nama']) ? htmlspecialchars($_SESSION['guru_nama']) : 'Guru'; ?>! 👨‍🏫</h2>
                <p>Dashboard peribadi anda - hanya data kelas dan subjek yang anda ajar</p>
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

        <!-- Quick Stats - DATA KHAS UNTUK GURU INI -->
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
                        <i class="fas fa-users"></i> Pelajar dalam kelas anda
                    </div>
                    <?php else: ?>
                    <div class="stat-change">
                        <i class="fas fa-info-circle"></i> Tiada pelajar
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
                    <?php else: ?>
                    <div class="stat-change positive">
                        <i class="fas fa-check-circle"></i> Semua telah dinilai
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
                        if (!empty($subjek_list)) {
                            $subject_names = array_column($subjek_list, 'nama');
                            echo implode(', ', array_slice($subject_names, 0, 2));
                            if (count($subject_names) > 2) echo ' +' . (count($subject_names) - 2) . ' lagi';
                        } else {
                            echo 'Tiada subjek';
                        }
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
                        if (!empty($kelas_list)) {
                            $class_names = array_column($kelas_list, 'nama');
                            echo implode(', ', array_slice($class_names, 0, 2));
                            if (count($class_names) > 2) echo ' +' . (count($class_names) - 2) . ' lagi';
                        } else {
                            echo 'Tiada kelas';
                        }
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

        <!-- Dashboard Sections - DATA SEBENAR DARI DATABASE -->
        <div class="dashboard-sections">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Ujian Terkini</h3>
                    <a href="modules/peperiksaan-saya.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-right"></i> Lihat Semua
                    </a>
                </div>
                <div class="exam-list">
                    <?php
                    if (!empty($peperiksaan_list)) {
                        foreach ($peperiksaan_list as $exam) {
                            $jumlah_markah = $exam['jumlah_markah'] ?? 0;
                            $sudah_dinilai = $exam['sudah_dinilai'] ?? 0;
                            $status = ($sudah_dinilai == $jumlah_markah && $jumlah_markah > 0) ? 'graded' : 'upcoming';
                            $status_class = ($status == 'graded') ? 'status-graded' : 'status-upcoming';
                            $status_text = ($status == 'graded') ? 'Telah Dinilai' : 'Belum Dinilai';
                            $tarikh = !empty($exam['tarikh_mula']) ? date('d M Y', strtotime($exam['tarikh_mula'])) : '-';
                            $kelas_nama = $exam['kelas'] ?? $exam['nama_peperiksaan'] ?? '-';
                            
                            echo '
                            <div class="exam-item">
                                <div class="exam-info">
                                    <h4>' . htmlspecialchars($exam['mata_pelajaran'] ?? '-') . ' — ' . htmlspecialchars($kelas_nama) . '</h4>
                                    <p>' . htmlspecialchars($exam['jenis'] ?? '-') . ' • ' . $tarikh . '</p>
                                    <small style="color: var(--medium-gray);">' . $sudah_dinilai . '/' . $jumlah_markah . ' dinilai</small>
                                </div>
                                <span class="exam-status ' . $status_class . '">' . $status_text . '</span>
                            </div>';
                        }
                    } else {
                        echo '<div style="text-align: center; padding: 30px;">
                                <i class="fas fa-file-alt" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: #999;">Tiada ujian dijumpai</p>
                                <p style="color: #999; font-size: 13px;">Anda belum mempunyai sebarang peperiksaan</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Prestasi Kelas</h3>
                    <a href="modules/laporan-prestasi.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-chart-line"></i> Analisis
                    </a>
                </div>
                <div class="class-list">
                    <?php
                    if (!empty($prestasi_kelas)) {
                        foreach ($prestasi_kelas as $class) {
                            $purata = number_format($class['purata_markah'], 1);
                            $color = ($purata >= 75) ? 'var(--success)' : (($purata >= 50) ? 'var(--warning)' : 'var(--danger)');
                            
                            echo '
                            <div class="class-item">
                                <div>
                                    <div class="class-name">' . htmlspecialchars($class['nama']) . '</div>
                                    <small style="color: var(--medium-gray);">Purata Kelas</small>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 20px; font-weight: 700; color: ' . $color . ';">' . $purata . '%</div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<div style="text-align: center; padding: 30px;">
                                <i class="fas fa-chart-bar" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="color: #999;">Tiada data prestasi</p>
                                <p style="color: #999; font-size: 13px;">Belum ada markah direkodkan</p>
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
            <p style="color: var(--medium-gray); font-size: 12px; margin-top: 5px;">
                <i class="fas fa-database"></i> Data dipaparkan adalah khusus untuk guru: <?php echo htmlspecialchars($_SESSION['guru_nama']); ?>
            </p>
        </div>
    </main>

    <script>
        // Sidebar Toggle Function
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            
         if (window.innerWidth <= 1024) {
                if (sidebar.classList.contains('active')) {
                    mainContent.style.marginLeft = '250px';
                } else {
                    mainContent.style.marginLeft = '0';
                }
            }
        });

    document.addEventListener('click', function(event) {
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                    mainContent.style.marginLeft = '0';
                }
            }
        });

    function muatSemulaData() {
        location.reload();
        }

        function tambahTugasan() {
            alert('Fitur akan datang: Tambah Tugasan Baru');
        }
    </script>
</body>
</html>
<?php
// Tutup sambungan database
if (isset($conn)) $conn->close();
?>