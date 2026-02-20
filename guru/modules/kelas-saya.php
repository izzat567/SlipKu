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

// =============================================
// Handle TAMBAH KELAS (assign guru ke kelas sedia ada dari DB)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah_kelas') {
    $kelas_id = intval($_POST['kelas_id'] ?? 0);
    $tahun_akademik = trim($_POST['tahun_akademik'] ?? date('Y'));

    if (!$kelas_id) {
        $error_message = 'Sila pilih kelas.';
    } else {
        try {
            // Semak sama ada sudah wujud
            $cek = $conn->prepare("SELECT id FROM guru_kelas WHERE guru_id = ? AND kelas_id = ? AND tahun = ?");
            $cek->bind_param("iii", $guru_id, $kelas_id, $tahun_akademik);
            $cek->execute();
            $cek->store_result();
            if ($cek->num_rows > 0) {
                $error_message = 'Anda sudah ditugaskan ke kelas ini.';
            } else {
                $ins = $conn->prepare("INSERT INTO guru_kelas (guru_id, kelas_id, tahun, status) VALUES (?, ?, ?, 1)");
                $ins->bind_param("iii", $guru_id, $kelas_id, $tahun_akademik);
                $ins->execute();
                $ins->close();
                header("Location: kelas-saya.php?success=1"); exit();
            }
            $cek->close();
        } catch (Exception $e) {
            $error_message = 'Ralat: ' . $e->getMessage();
        }
    }
}

// =============================================
// Handle EDIT KELAS
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_kelas') {
    $guru_kelas_id = intval($_POST['guru_kelas_id'] ?? 0);
    $kelas_id_baru = intval($_POST['kelas_id_baru'] ?? 0);
    $tahun_baru = intval($_POST['tahun_baru'] ?? date('Y'));

    if (!$guru_kelas_id || !$kelas_id_baru) {
        $error_message = 'Data tidak lengkap.';
    } else {
        try {
            $upd = $conn->prepare("UPDATE guru_kelas SET kelas_id=?, tahun=? WHERE id=? AND guru_id=?");
            $upd->bind_param("iiii", $kelas_id_baru, $tahun_baru, $guru_kelas_id, $guru_id);
            $upd->execute();
            $upd->close();
            header("Location: kelas-saya.php?success=edit"); exit();
        } catch (Exception $e) {
            $error_message = 'Ralat: ' . $e->getMessage();
        }
    }
}

if (isset($_GET['success'])) {
    if ($_GET['success'] === 'edit') $success_message = 'Kelas berjaya dikemaskini!';
    else $success_message = 'Kelas berjaya ditambah!';
}

// =============================================
// GET semua kelas dari DB (untuk dropdown tambah/edit)
// =============================================
$all_kelas = [];
try {
    $res = $conn->query("SELECT id, nama, tahun FROM kelas WHERE status = 1 ORDER BY tahun ASC, nama ASC");
    while ($row = $res->fetch_assoc()) {
        $all_kelas[] = $row;
    }
} catch (Exception $e) {}

// =============================================
// GET kelas yang diajar guru ini (dari guru_kelas)
// =============================================
$classes = [];
$total_murid_keseluruhan = 0;

try {
    $sql = "SELECT gk.id as guru_kelas_id, k.id, k.nama, k.tahun,
                COUNT(DISTINCT p.id) as total_murid,
                COALESCE(AVG(m.markah), 0) as average_performance
            FROM guru_kelas gk
            JOIN kelas k ON gk.kelas_id = k.id
            LEFT JOIN pelajar p ON k.id = p.id_kelas AND p.status = 1
            LEFT JOIN markah m ON m.id_pelajar = p.id AND m.status = 1
            WHERE gk.guru_id = ? AND gk.status = 1 AND k.status = 1
            GROUP BY gk.id, k.id, k.nama, k.tahun
            ORDER BY k.tahun ASC, k.nama ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $guru_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $classes[] = [
            'guru_kelas_id' => $row['guru_kelas_id'],
            'id'            => $row['id'],
            'nama'          => $row['nama'],
            'tahun'         => $row['tahun'],
            'total_murid'   => (int)$row['total_murid'],
            'average_performance' => round((float)$row['average_performance'], 1)
        ];
        $total_murid_keseluruhan += (int)$row['total_murid'];
    }
    $stmt->close();
} catch (Exception $e) { error_log($e->getMessage()); }

$totalClasses     = count($classes);
$totalStudents    = $total_murid_keseluruhan;
$avgPerformance   = $totalClasses > 0
    ? round(array_sum(array_column($classes, 'average_performance')) / $totalClasses, 1)
    : 0;
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #4f46e5; --primary-dark: #4338ca; --primary-light: #eef2ff;
            --secondary: #7c3aed; --success: #10b981; --warning: #f59e0b;
            --danger: #ef4444; --info: #3b82f6; --dark-gray: #1f2937;
            --medium-gray: #6b7280; --light-gray: #f9fafb; --white: #ffffff;
            --border-radius: 20px; --transition: all 0.3s ease;
        }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); color: var(--dark-gray); line-height: 1.6; min-height: 100vh; overflow-x: hidden; }
        .header { background: var(--white); box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: fixed; top: 0; left: 0; right: 0; z-index: 1000; padding: 0 30px; }
        .header-container { display: flex; align-items: center; justify-content: space-between; padding: 20px 0; }
        .logo { display: flex; align-items: center; gap: 15px; text-decoration: none; }
        .logo-icon { width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--white); font-size: 22px; }
        .logo-text h1 { font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 2px; }
        .logo-text p { font-size: 12px; color: var(--medium-gray); font-weight: 500; }
        .menu-toggle { display: none; background: none; border: none; font-size: 24px; color: var(--primary); cursor: pointer; padding: 10px; border-radius: 8px; transition: var(--transition); }
        .menu-toggle:hover { background: var(--primary-light); }
        .sidebar { background: var(--white); box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: fixed; left: 0; top: 85px; bottom: 0; width: 260px; padding: 30px 0; overflow-y: auto; z-index: 900; transition: var(--transition); }
        .sidebar-section { margin-bottom: 30px; padding: 0 25px; }
        .sidebar-title { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--medium-gray); margin-bottom: 15px; font-weight: 600; }
        .sidebar-item { display: flex; align-items: center; gap: 15px; padding: 12px 20px; color: var(--medium-gray); text-decoration: none; border-radius: 12px; margin: 5px 0; transition: var(--transition); }
        .sidebar-item:hover { background: var(--light-gray); color: var(--primary); transform: translateX(5px); }
        .sidebar-item.active { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; box-shadow: 0 8px 25px rgba(79,70,229,0.3); }
        .sidebar-item.active i { color: white; }
        .sidebar-item i { width: 20px; font-size: 16px; color: var(--medium-gray); }
        .main-content { margin-left: 260px; margin-top: 85px; padding: 30px; transition: var(--transition); }
        .page-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .page-title h2 { font-size: 32px; font-weight: 800; margin-bottom: 10px; background: linear-gradient(135deg, var(--primary), var(--secondary)); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .page-title p { color: var(--medium-gray); font-size: 16px; }
        .page-actions { display: flex; gap: 15px; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: var(--transition); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 10px; border: none; font-family: 'Poppins', sans-serif; white-space: nowrap; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: var(--white); box-shadow: 0 8px 25px rgba(79,70,229,0.3); }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(79,70,229,0.4); }
        .btn-success { background: linear-gradient(135deg, var(--success), #059669); color: var(--white); box-shadow: 0 8px 25px rgba(16,185,129,0.3); }
        .btn-success:hover { transform: translateY(-2px); }
        .btn-secondary { background: var(--white); color: var(--dark-gray); border: 2px solid #e5e7eb; }
        .btn-secondary:hover { background: var(--light-gray); transform: translateY(-2px); }
        .alert { padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; animation: slideIn 0.3s ease; }
        .alert-success { background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(5,150,105,0.1)); border-left: 4px solid var(--success); color: var(--success); }
        .alert-error { background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(220,38,38,0.1)); border-left: 4px solid var(--danger); color: var(--danger); }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .quick-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: var(--white); border-radius: var(--border-radius); padding: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); transition: var(--transition); display: flex; align-items: center; gap: 15px; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
        .stat-icon.classes { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .stat-icon.students { background: linear-gradient(135deg, #10b981, #34d399); }
        .stat-icon.performance { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-info h3 { font-size: 14px; color: var(--medium-gray); margin-bottom: 8px; font-weight: 500; }
        .stat-value { font-size: 24px; font-weight: 800; color: var(--dark-gray); line-height: 1; }
        .class-table-container { background: var(--white); border-radius: var(--border-radius); padding: 25px; box-shadow: 0 8px 25px rgba(0,0,0,0.08); margin-bottom: 30px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th { background: var(--light-gray); padding: 18px; text-align: left; font-weight: 600; font-size: 13px; color: var(--medium-gray); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        td { padding: 15px; border-bottom: 1px solid #e5e7eb; font-size: 14px; vertical-align: middle; }
        tr:hover td { background: var(--primary-light); }
        .class-info-cell { display: flex; align-items: center; gap: 15px; }
        .class-icon { width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; flex-shrink: 0; }
        .class-name { font-weight: 700; color: var(--primary); font-size: 16px; }
        .class-subject { font-size: 13px; color: var(--medium-gray); }
        .performance-cell { display: flex; align-items: center; gap: 10px; }
        .performance-bar { flex: 1; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden; }
        .performance-fill { height: 100%; border-radius: 4px; }
        .performance-excellent { background: var(--success); }
        .performance-good { background: #3b82f6; }
        .performance-average { background: var(--warning); }
        .performance-poor { background: var(--danger); }
        .performance-value { font-weight: 600; color: var(--dark-gray); min-width: 40px; text-align: right; }
        .action-cell { display: flex; gap: 8px; }
        .action-btn { padding: 8px 15px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: var(--transition); border: none; display: inline-flex; align-items: center; gap: 6px; }
        .action-btn.view { background: var(--info); color: white; }
        .action-btn.view:hover { background: #2563eb; }
        .action-btn.edit { background: var(--warning); color: white; }
        .action-btn.edit:hover { background: #d97706; }
        .action-btn.delete { background: var(--danger); color: white; }
        .action-btn.delete:hover { background: #dc2626; }
        .user-profile { display: flex; align-items: center; gap: 12px; padding: 8px 15px; border-radius: 12px; background: var(--light-gray); cursor: pointer; transition: var(--transition); }
        .user-profile:hover { background: var(--primary-light); }
        .user-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px; }
        .user-info h4 { font-size: 14px; font-weight: 600; color: var(--dark-gray); }
        .user-info p { font-size: 12px; color: var(--medium-gray); }
        .modal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 10000; justify-content: center; align-items: center; padding: 20px; backdrop-filter: blur(3px); }
        .modal.active { display: flex; }
        .modal-content { background: var(--white); border-radius: var(--border-radius); width: 100%; max-width: 550px; max-height: 90vh; overflow-y: auto; animation: modalSlideIn 0.3s ease; }
        @keyframes modalSlideIn { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 25px 30px; border-bottom: 2px solid var(--light-gray); display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, var(--primary-light), var(--white)); }
        .modal-header h3 { font-size: 22px; font-weight: 700; color: var(--primary); display: flex; align-items: center; gap: 10px; }
        .modal-close { background: none; border: none; font-size: 22px; color: var(--medium-gray); cursor: pointer; transition: var(--transition); padding: 8px; border-radius: 10px; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
        .modal-close:hover { background: var(--light-gray); color: var(--danger); transform: rotate(90deg); }
        .modal-body { padding: 30px; }
        .modal-footer { padding: 20px 30px 30px; display: flex; justify-content: flex-end; gap: 15px; border-top: 1px solid var(--light-gray); }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--dark-gray); margin-bottom: 8px; }
        .form-label i { color: var(--primary); margin-right: 8px; width: 18px; }
        .form-control, .form-select { width: 100%; padding: 14px 18px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; font-family: 'Poppins', sans-serif; transition: var(--transition); background: var(--white); }
        .form-control:focus, .form-select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79,70,229,0.1); }
        .form-select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 18px center; padding-right: 50px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .empty-state { text-align: center; padding: 60px 20px; color: var(--medium-gray); }
        .empty-state i { font-size: 48px; margin-bottom: 15px; color: var(--primary-light); }
        .empty-state h3 { font-size: 18px; margin-bottom: 10px; color: var(--dark-gray); }
        .darjah-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .darjah-1 { background: #dbeafe; color: #1d4ed8; }
        .darjah-2 { background: #dcfce7; color: #15803d; }
        .darjah-3 { background: #fef9c3; color: #854d0e; }
        .darjah-4 { background: #fce7f3; color: #9d174d; }
        .darjah-5 { background: #ede9fe; color: #5b21b6; }
        .darjah-6 { background: #ffedd5; color: #c2410c; }
        @media (max-width: 1024px) { .sidebar { transform: translateX(-100%); } .sidebar.sidebar-active { transform: translateX(0); } .main-content { margin-left: 0; } .menu-toggle { display: block; } }
        @media (max-width: 768px) { .header { padding: 0 20px; } .main-content { padding: 20px; margin-top: 75px; } .quick-stats { grid-template-columns: repeat(2, 1fr); } .form-row { grid-template-columns: 1fr; } }
        @media (max-width: 576px) { .quick-stats { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <!-- MODAL: Lihat Maklumat Kelas -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-chalkboard-teacher"></i> Maklumat Kelas</h3>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="text-align:center; margin-bottom:25px;">
                    <div class="class-icon" style="width:80px;height:80px;margin:0 auto 15px;font-size:32px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 id="classNameDetail" style="font-size:24px;color:var(--primary);margin-bottom:5px;">-</h3>
                    <p id="classLevelDetail" style="color:var(--medium-gray);font-size:16px;"></p>
                </div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px;margin-bottom:25px;">
                    <div style="background:var(--light-gray);padding:15px;border-radius:12px;">
                        <div style="font-size:13px;color:var(--medium-gray);margin-bottom:5px;">Guru</div>
                        <div style="font-weight:600;color:var(--dark-gray);"><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru'); ?></div>
                    </div>
                    <div style="background:var(--light-gray);padding:15px;border-radius:12px;">
                        <div style="font-size:13px;color:var(--medium-gray);margin-bottom:5px;">Darjah</div>
                        <div style="font-weight:600;color:var(--dark-gray);" id="classYearDetail">-</div>
                    </div>
                    <div style="background:var(--light-gray);padding:15px;border-radius:12px;">
                        <div style="font-size:13px;color:var(--medium-gray);margin-bottom:5px;">Prestasi Purata</div>
                        <div style="font-weight:600;color:var(--dark-gray);" id="classPerformanceDetail">-</div>
                    </div>
                    <div style="background:var(--light-gray);padding:15px;border-radius:12px;">
                        <div style="font-size:13px;color:var(--medium-gray);margin-bottom:5px;">Jumlah Pelajar</div>
                        <div style="font-weight:600;color:var(--dark-gray);" id="classStudentsDetail">-</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: Tambah Kelas -->
    <div class="modal" id="addClassModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Tambah Kelas</h3>
                <button class="modal-close" onclick="closeAddClassModal()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="tambah_kelas">
                <div class="modal-body">
                    <?php if ($error_message): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-school"></i> Pilih Kelas</label>
                        <select class="form-select" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $darjah_labels = ['', 'Darjah 1', 'Darjah 2', 'Darjah 3', 'Darjah 4', 'Darjah 5', 'Darjah 6'];
                            $current_darjah = 0;
                            foreach ($all_kelas as $kls):
                                if ($kls['tahun'] != $current_darjah) {
                                    if ($current_darjah > 0) echo '</optgroup>';
                                    $current_darjah = $kls['tahun'];
                                    $label = isset($darjah_labels[$current_darjah]) ? $darjah_labels[$current_darjah] : "Darjah $current_darjah";
                                    echo "<optgroup label=\"$label\">";
                                }
                            ?>
                            <option value="<?php echo $kls['id']; ?>"><?php echo htmlspecialchars($kls['nama']); ?></option>
                            <?php endforeach; if ($current_darjah > 0) echo '</optgroup>'; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar"></i> Tahun Akademik</label>
                        <select class="form-select" name="tahun_akademik" required>
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == date('Y')) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddClassModal()">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: Edit Kelas -->
    <div class="modal" id="editClassModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Kelas</h3>
                <button class="modal-close" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="edit_kelas">
                <input type="hidden" name="guru_kelas_id" id="edit_guru_kelas_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-school"></i> Tukar Kelas</label>
                        <select class="form-select" name="kelas_id_baru" id="edit_kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                            $current_darjah = 0;
                            foreach ($all_kelas as $kls):
                                if ($kls['tahun'] != $current_darjah) {
                                    if ($current_darjah > 0) echo '</optgroup>';
                                    $current_darjah = $kls['tahun'];
                                    $label = isset($darjah_labels[$current_darjah]) ? $darjah_labels[$current_darjah] : "Darjah $current_darjah";
                                    echo "<optgroup label=\"$label\">";
                                }
                            ?>
                            <option value="<?php echo $kls['id']; ?>"><?php echo htmlspecialchars($kls['nama']); ?></option>
                            <?php endforeach; if ($current_darjah > 0) echo '</optgroup>'; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-calendar"></i> Tahun Akademik</label>
                        <select class="form-select" name="tahun_baru" id="edit_tahun" required>
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == date('Y')) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Kemaskini</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <a href="../dashboard-guru.php" class="logo">
                <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="logo-text"><h1>SlipKu</h1><p>Kelas Saya</p></div>
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

    <?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

    <main class="main-content" id="mainContent">
        <div class="page-header">
            <div class="page-title">
                <h2>Kelas Saya 🏫</h2>
                <p>Urus dan pantau semua kelas yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-success" onclick="openAddClassModal()">
                    <i class="fas fa-plus-circle"></i> Tambah Kelas
                </button>
                <button class="btn btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Muat Semula
                </button>
            </div>
        </div>

        <?php if ($success_message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon classes"><i class="fas fa-chalkboard-teacher"></i></div>
                <div class="stat-info">
                    <h3>JUMLAH KELAS</h3>
                    <div class="stat-value"><?php echo $totalClasses; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon students"><i class="fas fa-user-graduate"></i></div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value"><?php echo $totalStudents; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon performance"><i class="fas fa-chart-line"></i></div>
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
                        <th>DARJAH</th>
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
                                <button class="btn btn-success" onclick="openAddClassModal()">Tambah Kelas Sekarang</button>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($classes as $class):
                            $perf_class = 'performance-poor';
                            if ($class['average_performance'] >= 80) $perf_class = 'performance-excellent';
                            elseif ($class['average_performance'] >= 70) $perf_class = 'performance-good';
                            elseif ($class['average_performance'] >= 60) $perf_class = 'performance-average';
                            $darjah_num = $class['tahun'];
                            $darjah_badge = "darjah-" . min(6, max(1, (int)$darjah_num));
                        ?>
                        <tr>
                            <td>
                                <div class="class-info-cell">
                                    <div class="class-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                    <div>
                                        <div class="class-name"><?php echo htmlspecialchars($class['nama']); ?></div>
                                        <div class="class-subject">Darjah <?php echo $class['tahun']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="darjah-badge <?php echo $darjah_badge; ?>">Darjah <?php echo $class['tahun']; ?></span>
                            </td>
                            <td><?php echo $class['total_murid']; ?> pelajar</td>
                            <td>
                                <div class="performance-cell">
                                    <div class="performance-bar">
                                        <div class="performance-fill <?php echo $perf_class; ?>"
                                             style="width:<?php echo min(100, $class['average_performance']); ?>%"></div>
                                    </div>
                                    <div class="performance-value"><?php echo number_format($class['average_performance'], 1); ?>%</div>
                                </div>
                            </td>
                            <td>
                                <div class="action-cell">
                                    <button class="action-btn view" onclick="viewClass(<?php echo htmlspecialchars(json_encode($class)); ?>)">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="action-btn edit" onclick="editClass(<?php echo $class['guru_kelas_id']; ?>, <?php echo $class['id']; ?>, <?php echo $class['tahun']; ?>)">
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
        const sidebar    = document.getElementById('sidebar');
        const classModal      = document.getElementById('classModal');
        const addClassModal   = document.getElementById('addClassModal');
        const editClassModal  = document.getElementById('editClassModal');

        menuToggle.addEventListener('click', () => sidebar.classList.toggle('sidebar-active'));

        function openAddClassModal() {
            addClassModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeAddClassModal() {
            addClassModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // VIEW CLASS
        function viewClass(classData) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-chalkboard-teacher"></i> ' + classData.nama;
            document.getElementById('classNameDetail').textContent = classData.nama;
            document.getElementById('classLevelDetail').textContent = 'Darjah ' + classData.tahun;
            document.getElementById('classYearDetail').textContent = 'Darjah ' + classData.tahun;
            document.getElementById('classPerformanceDetail').textContent = parseFloat(classData.average_performance).toFixed(1) + '%';
            document.getElementById('classStudentsDetail').textContent = classData.total_murid + ' pelajar';
            classModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            classModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        // EDIT CLASS — buka modal dengan data kelas semasa
        function editClass(guruKelasId, kelasId, tahun) {
            document.getElementById('edit_guru_kelas_id').value = guruKelasId;
            document.getElementById('edit_kelas_id').value = kelasId;
            document.getElementById('edit_tahun').value = tahun;
            editClassModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeEditModal() {
            editClassModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        window.addEventListener('click', function(e) {
            if (e.target === classModal)     closeModal();
            if (e.target === addClassModal)  closeAddClassModal();
            if (e.target === editClassModal) closeEditModal();
        });
    </script>
</body>
</html>
