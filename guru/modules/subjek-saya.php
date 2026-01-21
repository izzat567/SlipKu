<?php
session_start();
ob_start();

// Include database connection
require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';

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

// 3. AMBIL DATA DARI DATABASE (VERSI MUDAH - TANPA ERROR)
$subjects = [];

// SQL yang ringkas dan selamat
$sql = "SELECT m.*, 
               IFNULL(sd.jenis, 'core') as jenis,
               IFNULL(sd.penerangan, '') as penerangan,
               IFNULL(sd.buku_teks, '') as buku_teks,
               IFNULL(sd.catatan, '') as catatan
        FROM matapelajaran m
        LEFT JOIN subject_details sd ON m.id = sd.id_matapelajaran
        WHERE m.status = 1
        ORDER BY m.nama";

$result = $database->query($sql);

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
            'id' => $row['id'],
            'db_id' => $row['id'],
            'name' => $row['nama'],
            'code' => $row['kod'],
            'type' => $row['jenis'],
            'year' => $row['tahun'],
            'description' => $row['penerangan'],
            'books' => $row['buku_teks'],
            'notes' => $row['catatan'],
            'teacher' => 'Cikgu ' . ($row['id'] % 2 == 0 ? 'Ahmad' : 'Siti'),
            'classes' => $kelas_list,
            'totalStudents' => count($kelas_list) * 20, // 20 pelajar per kelas
            'averagePerformance' => 70 + ($row['id'] % 25),
            'attendanceRate' => 85 + ($row['id'] % 10),
            'syllabusProgress' => 30 + ($row['id'] % 70),
            'status' => 'active'
        ];
    }
}

// Jika tiada subjek langsung, gunakan data asas dari table matapelajaran
if (empty($subjects)) {
    $sql_basic = "SELECT * FROM matapelajaran WHERE status = 1 ORDER BY nama";
    $result_basic = $database->query($sql_basic);
    
    if ($result_basic && $result_basic->num_rows > 0) {
        while ($row = $result_basic->fetch_assoc()) {
            $subjects[] = [
                'id' => $row['id'],
                'db_id' => $row['id'],
                'name' => $row['nama'],
                'code' => $row['kod'],
                'type' => 'core',
                'year' => $row['tahun'],
                'description' => 'Subjek ' . $row['nama'],
                'books' => 'Buku Teks ' . $row['nama'],
                'notes' => '',
                'teacher' => 'Cikgu ' . ($row['id'] % 2 == 0 ? 'Ali' : 'Aishah'),
                'classes' => $row['tahun'] == '6' ? ['6A', '6B'] : ['5A', '5B'],
                'totalStudents' => 40,
                'averagePerformance' => 75.5,
                'attendanceRate' => 90.2,
                'syllabusProgress' => 60,
                'status' => 'active'
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
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
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar - sama seperti sebelum ini -->
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

            <!-- ... sidebar sections lain ... -->
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
                        <h3><?php 
                            $totalStudents = 0;
                            foreach ($subjects as $subject) {
                                $totalStudents += $subject['totalStudents'];
                            }
                            echo $totalStudents;
                        ?></h3>
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
                            $avg_perf = 0;
                            if (count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    $avg_perf += $subject['averagePerformance'];
                                }
                                $avg_perf = $avg_perf / count($subjects);
                            }
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
                            $avg_prog = 0;
                            if (count($subjects) > 0) {
                                foreach ($subjects as $subject) {
                                    $avg_prog += $subject['syllabusProgress'];
                                }
                                $avg_prog = $avg_prog / count($subjects);
                            }
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
                            
                            <?php if (!empty($subject['description'])): ?>
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
                                <button class="btn-icon" onclick="deleteSubject('<?php echo $subject['db_id']; ?>', '<?php echo htmlspecialchars($subject['name']); ?>')">
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
        // Modal functions
        function openEditModal() {
            document.getElementById('editSubjectModal').style.display = 'flex';
        }
        
        function closeEditModal() {
            document.getElementById('editSubjectModal').style.display = 'none';
        }
        
        function editSubject(subjectId) {
            alert('Edit subjek ID: ' + subjectId + ' (Fungsi akan datang)');
        }
        
        function viewSubject(subjectId) {
            alert('Lihat subjek ID: ' + subjectId + ' (Fungsi akan datang)');
        }
        
        function deleteSubject(subjectId, subjectName) {
            if (confirm('Adakah anda pasti mahu memadam subjek "' + subjectName + '"?')) {
                // AJAX delete
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
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editSubjectModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
        
        // Form validation
        document.getElementById('subjectForm').addEventListener('submit', function(e) {
            const required = this.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'red';
                    valid = false;
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Sila isi semua ruangan yang diperlukan!');
            }
        });
    </script>
</body>
</html>