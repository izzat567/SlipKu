<?php
ob_start();
session_start();

// Suppress display errors so they don't pollute JSON responses
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/connect.php';

// Auth check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_GET['ajax']) || isset($_POST['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Sesi tamat. Sila log masuk semula.']);
        exit();
    }
    header('Location: ../login-guru.php');
    exit();
}

$guru_id = $_SESSION['guru_id'];

require_once __DIR__ . '/../includes/db_functions.php';

// =====================================================================
// AJAX HANDLERS — must come BEFORE any HTML output or heavy queries
// =====================================================================

// --- GET AJAX ---
if (isset($_GET['ajax'])) {
    // Clear any buffered output to keep JSON clean
    ob_clean();
    header('Content-Type: application/json');

    if ($_GET['ajax'] === 'get_students') {
        $class   = $_GET['class'] ?? '';
        $rows    = is_numeric($class)
            ? getStudentsByClass((int)$class)
            : getStudentsByClassName($class);
        echo json_encode(['success' => true, 'students' => $rows]);
        exit();
    }

    if ($_GET['ajax'] === 'get_marks') {
        $pep_id   = intval($_GET['peperiksaan_id'] ?? 0);
        $kelas_id = intval($_GET['kelas_id']      ?? 0);

        if (!$pep_id && !$kelas_id) {
            $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                        p.nama AS nama_pelajar, p.no_kp,
                        k.nama AS nama_kelas,   k.id AS id_kelas,
                        COALESCE(pp.nama_peperiksaan,'Tidak Dikenal Pasti') AS nama_peperiksaan,
                        COALESCE(pp.id,0)                                   AS id_peperiksaan,
                        COALESCE(mp.nama,'-')                               AS nama_subjek
                    FROM markah m
                    JOIN pelajar p ON m.id_pelajar   = p.id
                    JOIN kelas   k ON p.id_kelas     = k.id
                    LEFT JOIN peperiksaan  pp ON m.id_perperiksaan  = pp.id
                    LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                    WHERE m.status = 'aktif'
                      AND (p.status = 'aktif' OR p.status = '1')
                      AND (
                          k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif')
                          OR k.id_guru = ?
                      )
                    ORDER BY p.nama ASC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) { echo json_encode(['success'=>false,'message'=>$conn->error]); exit(); }
            $stmt->bind_param("ii", $guru_id, $guru_id);

        } elseif ($pep_id) {
            $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                        p.nama AS nama_pelajar, p.no_kp,
                        k.nama AS nama_kelas,   k.id AS id_kelas,
                        COALESCE(pp.nama_peperiksaan,'Tidak Dikenal Pasti') AS nama_peperiksaan,
                        COALESCE(pp.id,0)                                   AS id_peperiksaan,
                        COALESCE(mp.nama,'-')                               AS nama_subjek
                    FROM markah m
                    JOIN pelajar p ON m.id_pelajar   = p.id
                    JOIN kelas   k ON p.id_kelas     = k.id
                    LEFT JOIN peperiksaan  pp ON m.id_perperiksaan  = pp.id
                    LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                    WHERE m.id_perperiksaan = ? AND m.status = 'aktif'
                    ORDER BY p.nama ASC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) { echo json_encode(['success'=>false,'message'=>$conn->error]); exit(); }
            $stmt->bind_param("i", $pep_id);

        } else {
            $sql = "SELECT m.id, m.id_pelajar, m.markah, m.gred, m.catatan,
                        p.nama AS nama_pelajar, p.no_kp,
                        k.nama AS nama_kelas,   k.id AS id_kelas,
                        COALESCE(pp.nama_peperiksaan,'Tidak Dikenal Pasti') AS nama_peperiksaan,
                        COALESCE(pp.id,0)                                   AS id_peperiksaan,
                        COALESCE(mp.nama,'-')                               AS nama_subjek
                    FROM markah m
                    JOIN pelajar p ON m.id_pelajar = p.id
                    JOIN kelas   k ON p.id_kelas   = k.id
                    LEFT JOIN peperiksaan  pp ON m.id_perperiksaan  = pp.id
                    LEFT JOIN matapelajaran mp ON pp.id_matapelajaran = mp.id
                    WHERE p.id_kelas = ? AND m.status = 'aktif'
                    ORDER BY p.nama ASC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) { echo json_encode(['success'=>false,'message'=>$conn->error]); exit(); }
            $stmt->bind_param("i", $kelas_id);
        }

        $stmt->execute();
        $marks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        echo json_encode(['success' => true, 'marks' => $marks]);
        exit();
    }

    // Unknown GET ajax
    echo json_encode(['success' => false, 'message' => 'Tindakan tidak dikenali']);
    exit();
}

// --- POST AJAX ---
if (isset($_POST['ajax'])) {
    ob_clean();
    header('Content-Type: application/json');

    if ($_POST['ajax'] === 'add_single_mark') {
        $data = [
            'id_pelajar'    => intval($_POST['id_pelajar']    ?? 0),
            'id_peperiksaan'=> intval($_POST['id_peperiksaan']?? 0),
            'markah'        => intval($_POST['markah']        ?? 0),
            'catatan'       => trim($_POST['catatan']         ?? ''),
        ];
        echo json_encode(addMark($data));
        exit();
    }

    if ($_POST['ajax'] === 'add_bulk_marks') {
        $marks_data = json_decode($_POST['marks_data'] ?? '[]', true);
        if (!$marks_data) {
            echo json_encode(['success' => false, 'message' => 'Tiada data markah yang valid']);
            exit();
        }
        echo json_encode(addMultipleMarks($marks_data));
        exit();
    }

    if ($_POST['ajax'] === 'update_mark') {
        $mark_id     = intval($_POST['mark_id']    ?? 0);
        $markah_baru = intval($_POST['markah_baru']?? 0);
        $catatan     = trim($_POST['catatan']      ?? '');
        $today       = date('Y-m-d');
        $gred        = calculateGrade($markah_baru);

        if (!$mark_id) {
            echo json_encode(['success' => false, 'message' => 'ID markah tidak sah']);
            exit();
        }
        $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, catatan=?, tarikh_kemaskini=? WHERE id=?");
        $stmt->bind_param("isssi", $markah_baru, $gred, $catatan, $today, $mark_id);
        if ($stmt->execute())
            echo json_encode(['success' => true, 'message' => 'Markah dikemaskini', 'gred' => $gred]);
        else
            echo json_encode(['success' => false, 'message' => 'Ralat: ' . $conn->error]);
        $stmt->close();
        exit();
    }

    if ($_POST['ajax'] === 'update_bulk') {
        $updates = json_decode($_POST['updates'] ?? '[]', true);
        $today   = date('Y-m-d');
        $count   = 0;
        foreach ($updates as $u) {
            $mid  = intval($u['mark_id']    ?? 0);
            $mb   = intval($u['markah_baru']?? 0);
            $cat  = trim($u['catatan']      ?? '');
            $gred = calculateGrade($mb);
            $stmt = $conn->prepare("UPDATE markah SET markah=?, gred=?, catatan=?, tarikh_kemaskini=? WHERE id=?");
            $stmt->bind_param("isssi", $mb, $gred, $cat, $today, $mid);
            if ($stmt->execute()) $count++;
            $stmt->close();
        }
        echo json_encode(['success' => true, 'message' => "$count markah berjaya dikemaskini"]);
        exit();
    }

    echo json_encode(['success' => false, 'message' => 'Tindakan tidak dikenali']);
    exit();
}

// =====================================================================
// PAGE LOAD — load dropdown data only when rendering HTML
// =====================================================================
$current_page = 'tambah-markah.php';
$guru_info    = getGuruById($guru_id);
$subjects     = getSubjectsByGuru($guru_id);
$classes      = getKelasByGuru($guru_id);
$exams        = getExamsByGuru($guru_id);

$students         = [];
$selected_subject = '';
$selected_class   = '';
$selected_exam    = '';
$markah_penuh     = 100;
$markah_lulus     = 40;
$active_tab       = $_GET['tab'] ?? 'tambah';

if (isset($_GET['subject']))      $selected_subject = $_GET['subject'];
if (isset($_GET['class']))        $selected_class   = $_GET['class'];
if (isset($_GET['exam']))         $selected_exam    = $_GET['exam'];
if (isset($_GET['markah_penuh'])) $markah_penuh     = (int)$_GET['markah_penuh'];
if (isset($_GET['markah_lulus'])) $markah_lulus     = (int)$_GET['markah_lulus'];

if ($selected_class && $active_tab === 'tambah') {
    $students = is_numeric($selected_class)
        ? getStudentsByClass((int)$selected_class)
        : getStudentsByClassName($selected_class);
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pengurusan Markah - SlipKu</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --primary:#4f46e5;--primary-dark:#4338ca;--primary-light:#eef2ff;
  --secondary:#7c3aed;--success:#10b981;--warning:#f59e0b;
  --danger:#ef4444;--info:#3b82f6;--dark-gray:#1f2937;
  --medium-gray:#6b7280;--light-gray:#f9fafb;--white:#ffffff;
  --border-radius:20px;--transition:all 0.3s ease;
}
body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);color:var(--dark-gray);min-height:100vh;overflow-x:hidden}
.menu-toggle{display:none;background:none;border:none;font-size:24px;color:var(--primary);cursor:pointer;padding:10px;border-radius:8px;transition:var(--transition)}
.header{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 30px}
.header-container{display:flex;align-items:center;justify-content:space-between;padding:20px 0}
.logo{display:flex;align-items:center;gap:15px;text-decoration:none}
.logo-icon{width:45px;height:45px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--white);font-size:22px}
.logo-text h1{font-size:24px;font-weight:800;color:var(--primary);margin-bottom:2px}
.logo-text p{font-size:12px;color:var(--medium-gray);font-weight:500}
.sidebar{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;left:0;top:85px;bottom:0;width:260px;padding:30px 0;overflow-y:auto;z-index:900;transition:var(--transition)}
.sidebar-section{margin-bottom:30px;padding:0 25px}
.sidebar-title{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--medium-gray);margin-bottom:15px;font-weight:600}
.sidebar-item{display:flex;align-items:center;gap:15px;padding:12px 20px;color:var(--medium-gray);text-decoration:none;border-radius:12px;margin:5px 0;transition:var(--transition)}
.sidebar-item:hover{background:var(--light-gray);color:var(--primary);transform:translateX(5px)}
.sidebar-item.active{background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;box-shadow:0 8px 25px rgba(79,70,229,.3)}
.sidebar-item.active i{color:white}
.sidebar-item i{width:20px;font-size:16px;color:var(--medium-gray)}
.main-content{margin-left:260px;margin-top:85px;padding:30px;transition:var(--transition)}
.page-title h2{font-size:30px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:8px}
.page-title p{color:var(--medium-gray);font-size:15px;margin-bottom:25px}
.tab-container{background:var(--white);border-radius:var(--border-radius);box-shadow:0 8px 25px rgba(0,0,0,.08);margin-bottom:25px;overflow:hidden}
.tab-header{display:flex;border-bottom:2px solid #e5e7eb}
.tab-btn{flex:1;padding:18px 20px;background:none;border:none;font-family:'Poppins',sans-serif;font-size:15px;font-weight:600;color:var(--medium-gray);cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;gap:10px;border-bottom:3px solid transparent;margin-bottom:-2px}
.tab-btn:hover{color:var(--primary);background:var(--primary-light)}
.tab-btn.active{color:var(--primary);border-bottom-color:var(--primary);background:var(--primary-light)}
.tab-content{display:none;padding:25px}
.tab-content.active{display:block}
.section-card{background:var(--white);border-radius:16px;padding:22px;margin-bottom:20px;border:1.5px solid #e5e7eb}
.section-title{font-size:16px;font-weight:700;color:var(--dark-gray);margin-bottom:18px;display:flex;align-items:center;gap:8px}
.section-title i{color:var(--primary)}
.form-group{margin-bottom:16px}
.form-label{display:block;font-size:13px;font-weight:600;color:var(--dark-gray);margin-bottom:6px}
.form-label.required::after{content:' *';color:var(--danger)}
.form-input,.form-select,.form-date,.form-textarea{width:100%;padding:10px 14px;border:2px solid #e5e7eb;border-radius:10px;font-size:14px;font-family:'Poppins',sans-serif;background:var(--white);transition:var(--transition)}
.form-input:focus,.form-select:focus,.form-date:focus,.form-textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,70,229,.1)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.btn{padding:11px 22px;border-radius:10px;font-weight:600;font-size:14px;cursor:pointer;transition:var(--transition);text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;font-family:'Poppins',sans-serif;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:var(--white);box-shadow:0 6px 20px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-2px)}
.btn-secondary{background:var(--white);color:var(--dark-gray);border:2px solid #e5e7eb}
.btn-secondary:hover{background:var(--light-gray)}
.btn-success{background:var(--success);color:white;box-shadow:0 4px 15px rgba(16,185,129,.3)}
.btn-success:hover{background:#0da271;transform:translateY(-2px)}
.btn-sm{padding:8px 14px;font-size:13px;border-radius:8px}
.action-btn{padding:7px 13px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:var(--transition);border:none;display:inline-flex;align-items:center;gap:5px;font-family:'Poppins',sans-serif}
.action-btn.primary{background:var(--primary);color:white}.action-btn.primary:hover{background:var(--primary-dark)}
.action-btn.success{background:var(--success);color:white}
.action-btn.danger{background:var(--danger);color:white}
.action-btn.secondary{background:#e5e7eb;color:var(--dark-gray)}
.marks-table{width:100%;border-collapse:collapse}
.marks-table th{background:var(--light-gray);padding:12px 10px;text-align:left;font-weight:600;font-size:11px;color:var(--medium-gray);text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e5e7eb}
.marks-table td{padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:middle}
.marks-table tr:hover td{background:var(--primary-light)}
.marks-table tr.row-changed td{background:rgba(245,158,11,.05)}
.student-row{display:flex;align-items:center;gap:10px}
.student-avatar{width:34px;height:34px;min-width:34px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:12px}
.student-info h4{font-size:13px;font-weight:700;color:var(--dark-gray);margin-bottom:1px}
.student-info p{font-size:11px;color:var(--medium-gray)}
.mark-input{width:80px;padding:7px 8px;border:2px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;text-align:center;transition:var(--transition)}
.mark-input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 2px rgba(79,70,229,.15)}
.mark-input.changed{border-color:var(--warning);background:rgba(245,158,11,.06)}
.mark-input.out-of-range{border-color:var(--danger);background:rgba(239,68,68,.06)}
.mark-input.saved{border-color:var(--success);background:rgba(16,185,129,.06)}
.notes-input{width:120px;padding:6px 10px;border:2px solid #e5e7eb;border-radius:8px;font-size:12px;font-family:'Poppins',sans-serif}
.notes-input:focus{outline:none;border-color:var(--primary)}
.grade-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;text-align:center;min-width:44px}
.grade-aplus,.grade-a{background:rgba(16,185,129,.12);color:var(--success)}
.grade-b{background:rgba(59,130,246,.12);color:var(--info)}
.grade-c{background:rgba(245,158,11,.12);color:var(--warning)}
.grade-d,.grade-e{background:rgba(239,68,68,.12);color:var(--danger)}
.grade-f{background:rgba(107,114,128,.12);color:var(--medium-gray)}
.status-badge{padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;display:inline-block;letter-spacing:.5px}
.status-success{background:rgba(16,185,129,.1);color:var(--success)}
.status-warning{background:rgba(245,158,11,.1);color:var(--warning)}
.status-danger{background:rgba(239,68,68,.1);color:var(--danger)}
.summary-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;margin-bottom:18px}
.summary-stat{background:var(--light-gray);padding:16px;border-radius:12px;text-align:center}
.summary-stat .stat-icon{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;margin:0 auto 8px}
.summary-stat .stat-value{font-size:20px;font-weight:800;color:var(--dark-gray)}
.summary-stat .stat-label{font-size:12px;color:var(--medium-gray)}
.search-bar{display:flex;gap:10px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
.search-wrap{position:relative;flex:1;min-width:180px}
.search-wrap i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--medium-gray)}
.search-field{width:100%;padding:9px 13px 9px 38px;border:2px solid #e5e7eb;border-radius:10px;font-size:13px;font-family:'Poppins',sans-serif}
.search-field:focus{outline:none;border-color:var(--primary)}
.filter-row{display:flex;gap:12px;flex-wrap:wrap;align-items:center}
.filter-group{display:flex;align-items:center;gap:7px}
.filter-group label{font-size:12px;font-weight:600;color:var(--dark-gray);white-space:nowrap}
.filter-select{padding:7px 11px;border:2px solid #e5e7eb;border-radius:8px;font-size:12px;font-family:'Poppins',sans-serif;background:var(--white)}
.user-profile{display:flex;align-items:center;gap:12px;padding:8px 15px;border-radius:12px;background:var(--light-gray);cursor:pointer;transition:var(--transition)}
.user-profile:hover{background:var(--primary-light)}
.user-avatar{width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:15px}
.user-info h4{font-size:13px;font-weight:600;color:var(--dark-gray)}
.user-info p{font-size:11px;color:var(--medium-gray)}
.sidebar-overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:899;display:none}
.sidebar-overlay.active{display:block}
.modal{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.5);z-index:10000;justify-content:center;align-items:center;padding:20px;backdrop-filter:blur(3px)}
.modal.active{display:flex}
.modal-box{background:var(--white);border-radius:var(--border-radius);width:100%;max-width:440px;animation:mIn .3s ease}
@keyframes mIn{from{opacity:0;transform:translateY(-15px)}to{opacity:1;transform:translateY(0)}}
.modal-header{padding:18px 22px;border-bottom:2px solid var(--light-gray);display:flex;justify-content:space-between;align-items:center}
.modal-header h3{font-size:17px;font-weight:700}
.modal-close{background:none;border:none;font-size:18px;color:var(--medium-gray);cursor:pointer;padding:4px;border-radius:6px}
.modal-close:hover{background:var(--light-gray);color:var(--danger)}
.modal-body{padding:22px}
.modal-footer{padding:14px 22px;border-top:2px solid var(--light-gray);display:flex;justify-content:flex-end;gap:10px}
.empty-state{text-align:center;padding:45px 20px;color:var(--medium-gray)}
.empty-state i{font-size:44px;margin-bottom:12px;display:block;opacity:.35}
.empty-state h3{font-size:17px;font-weight:700;margin-bottom:6px}
.loading-wrap{text-align:center;padding:40px}
.spinner{width:36px;height:36px;border:4px solid #e5e7eb;border-top-color:var(--primary);border-radius:50%;animation:spin .7s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}
.toast{position:fixed;top:100px;right:28px;padding:12px 20px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,.2);z-index:99999;animation:slideIn .3s ease;display:flex;align-items:center;gap:10px;font-family:'Poppins',sans-serif;font-size:13px;font-weight:500;color:white;max-width:320px}
.toast-success{background:var(--success)}.toast-error{background:var(--danger)}.toast-warning{background:var(--warning)}.toast-info{background:var(--info)}
@keyframes slideIn{from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1}}
.actions-bar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:14px}
@media(max-width:1024px){
  .sidebar{transform:translateX(-100%)}.sidebar.sidebar-active{transform:translateX(0)}
  .main-content{margin-left:0}.menu-toggle{display:block}
}
@media(max-width:768px){
  .header{padding:0 16px}.main-content{padding:16px;margin-top:75px}
  .form-row{grid-template-columns:1fr}.tab-btn{font-size:13px;padding:13px 10px}
  .marks-table{min-width:820px}.page-title h2{font-size:24px}
}
</style>
</head>
<body>

<!-- Confirm Modal -->
<div class="modal" id="confirmModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3>Pengesahan</h3>
      <button class="modal-close" onclick="closeModal('confirmModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body" style="text-align:center;">
      <i class="fas fa-question-circle" style="font-size:40px;color:var(--warning);margin-bottom:12px;display:block;"></i>
      <p id="confirmMsg" style="font-size:15px;color:var(--dark-gray);"></p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" onclick="closeModal('confirmModal')">Batal</button>
      <button class="btn btn-primary" id="confirmOkBtn">Ya, Teruskan</button>
    </div>
  </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Header -->
<header class="header">
  <div class="header-container">
    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    <a href="../dashboard-guru.php" class="logo">
      <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="logo-text"><h1>SlipKu</h1><p>Pengurusan Markah</p></div>
    </a>
    <?php if ($guru_info): ?>
    <div class="user-profile">
      <div class="user-avatar"><?php echo strtoupper(substr($guru_info['nama'], 0, 2)); ?></div>
      <div class="user-info">
        <h4><?php echo htmlspecialchars($guru_info['nama']); ?></h4>
        <p>Guru</p>
      </div>
    </div>
    <?php endif; ?>
  </div>
</header>

<!-- Sidebar -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- Main Content -->
<main class="main-content" id="mainContent">
  <div class="page-title">
    <h2>Pengurusan Markah ✏️</h2>
    <p>Tambah markah baru atau kemaskini markah sedia ada pelajar</p>
  </div>

  <div class="tab-container">
    <div class="tab-header">
      <button class="tab-btn <?php echo $active_tab==='tambah'?'active':''; ?>" onclick="switchTab('tambah')">
        <i class="fas fa-plus-circle"></i> Tambah Markah
      </button>
      <button class="tab-btn <?php echo $active_tab==='kemaskini'?'active':''; ?>" onclick="switchTab('kemaskini')">
        <i class="fas fa-edit"></i> Kemaskini Markah
      </button>
    </div>

    <!-- ======== TAB: TAMBAH MARKAH ======== -->
    <div id="tab-tambah" class="tab-content <?php echo $active_tab==='tambah'?'active':''; ?>">

      <div class="section-card">
        <div class="section-title"><i class="fas fa-filter"></i> Pilih Penilaian</div>
        <form method="GET" action="">
          <input type="hidden" name="tab" value="tambah">
          <div class="form-row">
            <div class="form-group">
              <label class="form-label required">Subjek</label>
              <select class="form-select" name="subject" required>
                <option value="">Pilih Subjek</option>
                <?php foreach ($subjects as $s): ?>
                  <option value="<?php echo $s['id']; ?>" <?php echo $selected_subject==$s['id']?'selected':''; ?>>
                    <?php echo htmlspecialchars($s['nama']).' ('.htmlspecialchars($s['kod']).')'; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label required">Kelas</label>
              <select class="form-select" name="class" required>
                <option value="">Pilih Kelas</option>
                <?php foreach ($classes as $c): ?>
                  <option value="<?php echo htmlspecialchars($c['nama']); ?>" <?php echo $selected_class==$c['nama']?'selected':''; ?>>
                    Tahun <?php echo $c['tahun'].' '.htmlspecialchars($c['nama']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label required">Peperiksaan</label>
              <select class="form-select" name="exam" required>
                <option value="">Pilih Peperiksaan</option>
                <?php foreach ($exams as $e): ?>
                  <option value="<?php echo $e['id']; ?>" <?php echo $selected_exam==$e['id']?'selected':''; ?>>
                    <?php echo htmlspecialchars($e['nama_peperiksaan']).' ('.date('d/m/Y', strtotime($e['tarikh_mula'])).')'; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label required">Tarikh Penilaian</label>
              <input type="date" class="form-date" name="assessment_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label required">Markah Penuh</label>
              <input type="number" class="form-input" name="markah_penuh" id="fullMarks"
                     value="<?php echo $markah_penuh; ?>" min="1" max="200" required>
            </div>
            <div class="form-group">
              <label class="form-label required">Markah Lulus</label>
              <input type="number" class="form-input" name="markah_lulus" id="passingMarks"
                     value="<?php echo $markah_lulus; ?>" min="0" max="200" required>
            </div>
          </div>
          <div style="text-align:right;margin-top:8px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-users"></i> Muatkan Pelajar</button>
          </div>
        </form>
      </div>

      <?php if (!empty($students)): ?>
      <!-- Summary -->
      <div class="summary-grid">
        <div class="summary-stat">
          <div class="stat-icon" style="background:linear-gradient(135deg,var(--primary),var(--secondary));color:white;"><i class="fas fa-calculator"></i></div>
          <div class="stat-value" id="avgMark">0.0</div><div class="stat-label">Purata Markah</div>
        </div>
        <div class="summary-stat">
          <div class="stat-icon" style="background:linear-gradient(135deg,var(--success),#34d399);color:white;"><i class="fas fa-trophy"></i></div>
          <div class="stat-value" id="highMark">0</div><div class="stat-label">Tertinggi</div>
        </div>
        <div class="summary-stat">
          <div class="stat-icon" style="background:linear-gradient(135deg,var(--danger),#f87171);color:white;"><i class="fas fa-arrow-down"></i></div>
          <div class="stat-value" id="lowMark">0</div><div class="stat-label">Terendah</div>
        </div>
        <div class="summary-stat">
          <div class="stat-icon" style="background:linear-gradient(135deg,var(--info),#60a5fa);color:white;"><i class="fas fa-check-circle"></i></div>
          <div class="stat-value" id="completedCount">0/<?php echo count($students); ?></div><div class="stat-label">Pelajar Selesai</div>
        </div>
      </div>

      <div class="actions-bar">
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
          <button class="action-btn danger" onclick="kosongkanSemua()"><i class="fas fa-eraser"></i> Kosongkan Semua</button>
          <span id="marksStatusText" style="font-size:12px;color:var(--medium-gray);"><?php echo count($students); ?> pelajar. Belum ada markah.</span>
        </div>
        <button class="btn btn-success" onclick="simpanSemuaMarkah()"><i class="fas fa-save"></i> Simpan Semua</button>
      </div>

      <div style="overflow-x:auto;">
        <table class="marks-table">
          <thead>
            <tr>
              <th>BIL</th><th>PELAJAR</th><th>NO. KP</th>
              <th>MARKAH (0–<span id="fullMarksDisplay"><?php echo $markah_penuh; ?></span>)</th>
              <th>GRED</th><th>STATUS</th><th>CATATAN</th><th>SIMPAN</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($students as $i => $st): ?>
              <?php
              $names = explode(' ', $st['nama']);
              $ini   = count($names) >= 2
                ? $names[0][0] . $names[count($names)-1][0]
                : substr($st['nama'], 0, 2);
              ?>
              <tr>
                <td><?php echo $i + 1; ?></td>
                <td>
                  <div class="student-row">
                    <div class="student-avatar"><?php echo strtoupper($ini); ?></div>
                    <div class="student-info">
                      <h4><?php echo htmlspecialchars($st['nama']); ?></h4>
                      <p><?php echo htmlspecialchars($selected_class); ?></p>
                    </div>
                  </div>
                </td>
                <td><?php echo htmlspecialchars($st['no_kp']); ?></td>
                <td>
                  <input type="number" class="mark-input" id="mark-<?php echo $st['id']; ?>"
                    min="0" max="<?php echo $markah_penuh; ?>"
                    placeholder="0–<?php echo $markah_penuh; ?>"
                    oninput="onMarkInput(<?php echo $st['id']; ?>, this.value)"
                    onblur="validateMI(<?php echo $st['id']; ?>, <?php echo $markah_penuh; ?>)">
                </td>
                <td><span class="grade-badge" id="grade-<?php echo $st['id']; ?>">–</span></td>
                <td><span class="status-badge status-warning" id="status-<?php echo $st['id']; ?>">BELUM DIISI</span></td>
                <td><input type="text" class="notes-input" id="notes-<?php echo $st['id']; ?>" placeholder="Catatan..."></td>
                <td>
                  <button class="action-btn primary" id="savebtn-<?php echo $st['id']; ?>"
                    onclick="simpanSatu(<?php echo $st['id']; ?>, <?php echo (int)$selected_exam; ?>, this)">
                    <i class="fas fa-save"></i> Simpan
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>Pilih Kelas &amp; Peperiksaan</h3>
        <p>Isikan maklumat di atas dan klik <strong>Muatkan Pelajar</strong></p>
      </div>
      <?php endif; ?>

    </div><!-- /tab-tambah -->

    <!-- ======== TAB: KEMASKINI MARKAH ======== -->
    <div id="tab-kemaskini" class="tab-content <?php echo $active_tab==='kemaskini'?'active':''; ?>">

      <div class="section-card" style="margin-bottom:15px;">
        <div class="search-bar">
          <div class="search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" class="search-field" id="kmSearch"
                   placeholder="Cari nama, No. KP, kelas..." oninput="filterKmTable()">
          </div>
          <button class="btn btn-primary btn-sm" onclick="loadKemaskiniData()"><i class="fas fa-sync"></i> Muat Semula</button>
        </div>
        <div class="filter-row">
          <div class="filter-group">
            <label>Peperiksaan:</label>
            <select class="filter-select" id="kmFilterPep" onchange="filterKmTable()">
              <option value="">Semua</option>
              <?php foreach ($exams as $e): ?>
                <option value="<?php echo htmlspecialchars($e['nama_peperiksaan']); ?>">
                  <?php echo htmlspecialchars($e['nama_peperiksaan']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filter-group">
            <label>Kelas:</label>
            <select class="filter-select" id="kmFilterKelas" onchange="filterKmTable()">
              <option value="">Semua</option>
              <?php foreach ($classes as $c): ?>
                <option value="<?php echo htmlspecialchars($c['nama']); ?>"><?php echo htmlspecialchars($c['nama']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="filter-group">
            <label>Status:</label>
            <select class="filter-select" id="kmFilterStatus" onchange="filterKmTable()">
              <option value="">Semua</option>
              <option value="lulus">Lulus</option>
              <option value="gagal">Gagal</option>
              <option value="berubah">Ada Perubahan</option>
            </select>
          </div>
        </div>
      </div>

      <div class="actions-bar">
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
          <button class="action-btn secondary" onclick="batalkanSemua()"><i class="fas fa-undo"></i> Batal Perubahan</button>
          <span id="changesBadge" style="display:none;background:var(--warning);color:white;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:700;"></span>
        </div>
        <button class="btn btn-success" onclick="simpanSemuaKemaskini()"><i class="fas fa-save"></i> Simpan Semua Perubahan</button>
      </div>

      <div id="kmTableWrap" style="overflow-x:auto;">
        <div class="loading-wrap"><div class="spinner"></div><p>Memuatkan data markah...</p></div>
      </div>

    </div><!-- /tab-kemaskini -->
  </div>
</main>

<script>
// ===================== STATE =====================
const FULL_MARKS   = <?php echo (int)$markah_penuh; ?>;
const PASS_MARKS   = <?php echo (int)$markah_lulus; ?>;
const EXAM_ID      = <?php echo (int)$selected_exam; ?>;
const ALL_STUDENTS = <?php echo json_encode($students); ?>;

let tambahMarks = {};
let kmData      = [];
let kmChanges   = {};

// ===================== TAB =====================
function switchTab(tab) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
  document.querySelector(`[onclick="switchTab('${tab}')"]`).classList.add('active');
  document.getElementById(`tab-${tab}`).classList.add('active');
  if (tab === 'kemaskini' && kmData.length === 0) loadKemaskiniData();
}

// ===================== TAMBAH =====================
function onMarkInput(sid, val) {
  const fm = parseInt(document.getElementById('fullMarks').value) || FULL_MARKS;
  const pm = parseInt(document.getElementById('passingMarks').value) || PASS_MARKS;
  const mv = val === '' ? null : parseInt(val);

  if (mv !== null) tambahMarks[sid] = { markah: mv };
  else delete tambahMarks[sid];

  const ge = document.getElementById(`grade-${sid}`);
  const se = document.getElementById(`status-${sid}`);

  if (mv !== null && !isNaN(mv)) {
    const g = gradeOf(mv, fm);
    ge.textContent = g; ge.className = `grade-badge ${gradeClass(g)}`;
    const pass = mv >= pm;
    se.textContent = pass ? 'LULUS' : 'GAGAL';
    se.className = `status-badge ${pass ? 'status-success' : 'status-danger'}`;
  } else {
    ge.textContent = '–'; ge.className = 'grade-badge';
    se.textContent = 'BELUM DIISI'; se.className = 'status-badge status-warning';
  }
  updateTambahSummary();
  updateTambahStatus();
}

function validateMI(sid, fm) {
  const inp = document.getElementById(`mark-${sid}`);
  const v = parseInt(inp.value);
  if (!isNaN(v) && (v < 0 || v > fm)) {
    inp.classList.add('out-of-range');
    toast(`Markah mesti antara 0 dan ${fm}`, 'warning');
  } else {
    inp.classList.remove('out-of-range');
  }
}

function updateTambahSummary() {
  const vals = Object.values(tambahMarks).map(m => m.markah).filter(m => !isNaN(m));
  const total = ALL_STUDENTS.length;
  const avg  = vals.length ? (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) : '0.0';
  const high = vals.length ? Math.max(...vals) : 0;
  const low  = vals.length ? Math.min(...vals) : 0;
  const a = document.getElementById('avgMark'); if (a) a.textContent = avg;
  const h = document.getElementById('highMark'); if (h) h.textContent = high;
  const l = document.getElementById('lowMark'); if (l) l.textContent = low;
  const c = document.getElementById('completedCount'); if (c) c.textContent = `${vals.length}/${total}`;
}

function updateTambahStatus() {
  const total  = ALL_STUDENTS.length;
  const filled = Object.keys(tambahMarks).length;
  const el = document.getElementById('marksStatusText'); if (!el) return;
  if (filled === 0) { el.textContent = 'Belum ada markah dimasukkan.'; el.style.color = 'var(--danger)'; }
  else if (filled < total) { el.textContent = `${filled} daripada ${total} pelajar dimasukkan.`; el.style.color = 'var(--warning)'; }
  else { el.textContent = `Semua ${total} pelajar siap dimasukkan.`; el.style.color = 'var(--success)'; }
}

function kosongkanSemua() {
  confirm2('Kosongkan semua markah yang dimasukkan?', () => {
    ALL_STUDENTS.forEach(s => {
      const inp = document.getElementById(`mark-${s.id}`);
      if (inp) { inp.value = ''; inp.classList.remove('out-of-range', 'saved'); }
      onMarkInput(s.id, '');
    });
    tambahMarks = {};
    updateTambahSummary(); updateTambahStatus();
    toast('Semua markah dikosongkan', 'info');
  });
}

function simpanSatu(sid, examId, btn) {
  const inp = document.getElementById(`mark-${sid}`);
  if (!inp || inp.value === '') { toast('Sila masukkan markah terlebih dahulu', 'warning'); return; }
  const fm  = parseInt(document.getElementById('fullMarks').value) || FULL_MARKS;
  const mv  = parseInt(inp.value);
  if (mv < 0 || mv > fm) { toast(`Markah mesti antara 0 dan ${fm}`, 'warning'); return; }
  if (!examId) { toast('Sila pilih peperiksaan', 'warning'); return; }

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

  const notes = (document.getElementById(`notes-${sid}`) || {}).value || '';

  fetch('tambah-markah.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      ajax: 'add_single_mark',
      id_pelajar: sid,
      id_peperiksaan: examId,
      markah: mv,
      catatan: notes
    })
  })
  .then(r => {
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
  })
  .then(d => {
    btn.disabled = false;
    if (d.success) {
      toast('Markah berjaya disimpan!', 'success');
      btn.className = 'action-btn success';
      btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan';
      inp.classList.add('saved');
    } else {
      toast(d.message || 'Gagal menyimpan', 'error');
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
    console.error('Fetch error:', err);
    toast('Gagal menyambung ke pelayan. Semak konsol untuk butiran.', 'error');
  });
}

function simpanSemuaMarkah() {
  const keys = Object.keys(tambahMarks);
  if (!keys.length) { toast('Tiada markah untuk disimpan', 'warning'); return; }
  if (!EXAM_ID)     { toast('Sila pilih peperiksaan dahulu', 'warning'); return; }

  confirm2(`Simpan markah untuk ${keys.length} pelajar?`, () => {
    const data = keys.map(sid => ({
      id_pelajar:     parseInt(sid),
      id_peperiksaan: EXAM_ID,
      markah:         tambahMarks[sid].markah,
      catatan:        (document.getElementById(`notes-${sid}`) || {}).value || ''
    }));

    fetch('tambah-markah.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ ajax: 'add_bulk_marks', marks_data: JSON.stringify(data) })
    })
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(d => {
      if (d.success) toast(d.message || `${keys.length} markah disimpan!`, 'success');
      else toast(d.message || 'Gagal menyimpan', 'error');
    })
    .catch(err => { console.error(err); toast('Gagal menyambung ke pelayan', 'error'); });
  });
}

// ===================== KEMASKINI =====================
function loadKemaskiniData() {
  const wrap = document.getElementById('kmTableWrap');
  wrap.innerHTML = '<div class="loading-wrap"><div class="spinner"></div><p>Memuatkan data markah...</p></div>';

  fetch('tambah-markah.php?ajax=get_marks')
    .then(r => {
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.text(); // read as text first to debug
    })
    .then(text => {
      let d;
      try { d = JSON.parse(text); }
      catch(e) {
        console.error('JSON parse error. Raw response:', text);
        wrap.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Ralat respons pelayan</h3><p>Semak konsol untuk butiran.</p></div>';
        return;
      }
      if (d.success) {
        kmData = d.marks.map(m => ({
          id: m.id, studentName: m.nama_pelajar, studentIC: m.no_kp,
          studentClass: m.nama_kelas, peperiksaan: m.nama_peperiksaan,
          originalMark: parseInt(m.markah), catatan: m.catatan || '',
          originalGrade: m.gred || ''
        }));
        kmChanges = {};
        renderKmTable(kmData);
      } else {
        wrap.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><h3>Tiada data</h3><p>${d.message||'Tiada markah dijumpai'}</p></div>`;
      }
    })
    .catch(err => {
      console.error('Kemaskini load error:', err);
      wrap.innerHTML = '<div class="empty-state"><i class="fas fa-wifi"></i><h3>Ralat sambungan</h3><p>Pastikan anda telah log masuk</p></div>';
    });
}

function renderKmTable(data) {
  const wrap = document.getElementById('kmTableWrap');
  if (!data.length) {
    wrap.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><h3>Tiada markah dijumpai</h3><p>Tiada data. Sila tambah markah pelajar melalui tab <strong>Tambah Markah</strong> terlebih dahulu.</p></div>';
    return;
  }
  let html = `<table class="marks-table" id="kmTable">
    <thead><tr>
      <th>BIL</th><th>PELAJAR</th><th>PEPERIKSAAN / KELAS</th>
      <th>MARKAH ASAL</th><th>MARKAH BARU</th>
      <th>GRED ASAL</th><th>GRED BARU</th><th>STATUS</th><th>CATATAN</th>
    </tr></thead><tbody>`;

  data.forEach((m, i) => {
    const names = m.studentName.split(' ');
    const ini   = names.length >= 2 ? names[0].charAt(0) + names[names.length-1].charAt(0) : names[0].substring(0, 2);
    const chg   = kmChanges[m.id] !== undefined;
    const disp  = chg ? kmChanges[m.id].markah_baru : m.originalMark;
    const cat   = chg ? kmChanges[m.id].catatan : m.catatan;
    const ng    = gradeOf(disp, 100);
    const pass  = disp >= 40;

    html += `<tr class="${chg?'row-changed':''}" id="kmrow-${m.id}"
      data-name="${esc(m.studentName).toLowerCase()}"
      data-ic="${esc(m.studentIC)}"
      data-kelas="${esc(m.studentClass).toLowerCase()}"
      data-pep="${esc(m.peperiksaan).toLowerCase()}"
      data-status="${pass?'lulus':'gagal'}">
      <td>${i+1}</td>
      <td>
        <div class="student-row">
          <div class="student-avatar">${ini.toUpperCase()}</div>
          <div class="student-info"><h4>${esc(m.studentName)}</h4><p>${esc(m.studentIC)}</p></div>
        </div>
      </td>
      <td>
        <div style="font-size:12px;font-weight:600;">${esc(m.peperiksaan)}</div>
        <div style="font-size:11px;color:var(--medium-gray);">${esc(m.studentClass)}</div>
      </td>
      <td style="font-weight:700;">${m.originalMark}</td>
      <td>
        <input type="number" class="mark-input ${chg?'changed':''}" id="km-${m.id}"
          min="0" max="100" value="${disp}"
          oninput="onKmInput(${m.id}, this.value)" placeholder="0–100">
      </td>
      <td><span class="grade-badge ${gradeClass(m.originalGrade)}">${m.originalGrade||'–'}</span></td>
      <td><span class="grade-badge ${gradeClass(ng)}" id="km-grade-${m.id}">${ng}</span></td>
      <td><span class="status-badge ${pass?'status-success':'status-danger'}" id="km-status-${m.id}">${pass?'LULUS':'GAGAL'}</span></td>
      <td><input type="text" class="notes-input" id="km-notes-${m.id}" value="${esc(cat)}" placeholder="Catatan..." oninput="onKmNotes(${m.id}, this.value)"></td>
    </tr>`;
  });
  html += '</tbody></table>';
  wrap.innerHTML = html;
  updateChangesBadge();
}

function onKmInput(mid, val) {
  const m  = kmData.find(x => x.id == mid); if (!m) return;
  const nv = val === '' ? m.originalMark : parseInt(val);
  const chg = nv !== m.originalMark;
  if (chg) kmChanges[mid] = { markah_baru: nv, catatan: (document.getElementById(`km-notes-${mid}`) || {}).value || m.catatan };
  else delete kmChanges[mid];

  const ng   = gradeOf(nv, 100);
  const pass = nv >= 40;
  const ge   = document.getElementById(`km-grade-${mid}`);
  const se   = document.getElementById(`km-status-${mid}`);
  const inp  = document.getElementById(`km-${mid}`);
  const row  = document.getElementById(`kmrow-${mid}`);
  if (ge) { ge.textContent = ng; ge.className = `grade-badge ${gradeClass(ng)}`; }
  if (se) { se.textContent = pass?'LULUS':'GAGAL'; se.className = `status-badge ${pass?'status-success':'status-danger'}`; }
  if (inp) inp.className = `mark-input ${chg?'changed':''}`;
  if (row) { row.className = chg ? 'row-changed' : ''; row.dataset.status = pass ? 'lulus' : 'gagal'; }
  updateChangesBadge();
}

function onKmNotes(mid, val) {
  const m = kmData.find(x => x.id == mid); if (!m) return;
  const curMark = kmChanges[mid] !== undefined ? kmChanges[mid].markah_baru : m.originalMark;
  kmChanges[mid] = { markah_baru: curMark, catatan: val };
}

function updateChangesBadge() {
  const cnt = Object.keys(kmChanges).length;
  const el  = document.getElementById('changesBadge');
  if (el) { el.style.display = cnt > 0 ? 'inline-block' : 'none'; el.textContent = `${cnt} perubahan`; }
}

function filterKmTable() {
  const s   = (document.getElementById('kmSearch').value || '').toLowerCase();
  const pep = (document.getElementById('kmFilterPep').value || '').toLowerCase();
  const kel = (document.getElementById('kmFilterKelas').value || '').toLowerCase();
  const sta = (document.getElementById('kmFilterStatus').value || '').toLowerCase();
  document.querySelectorAll('#kmTable tbody tr').forEach(r => {
    const ok = (!s   || r.dataset.name.includes(s) || r.dataset.ic.includes(s) || r.dataset.kelas.includes(s))
            && (!pep || r.dataset.pep.includes(pep))
            && (!kel || r.dataset.kelas.includes(kel))
            && (!sta || (sta==='berubah' ? r.classList.contains('row-changed') : r.dataset.status===sta));
    r.style.display = ok ? '' : 'none';
  });
}

function batalkanSemua() {
  if (!Object.keys(kmChanges).length) { toast('Tiada perubahan untuk dibatalkan', 'info'); return; }
  confirm2('Batalkan semua perubahan yang belum disimpan?', () => {
    kmChanges = {};
    renderKmTable(kmData);
    toast('Semua perubahan dibatalkan', 'info');
  });
}

function simpanSemuaKemaskini() {
  const keys = Object.keys(kmChanges);
  if (!keys.length) { toast('Tiada perubahan untuk disimpan', 'warning'); return; }
  confirm2(`Simpan ${keys.length} perubahan markah?`, () => {
    const updates = keys.map(mid => ({
      mark_id:     parseInt(mid),
      markah_baru: kmChanges[mid].markah_baru,
      catatan:     kmChanges[mid].catatan || ''
    }));
    fetch('tambah-markah.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ ajax: 'update_bulk', updates: JSON.stringify(updates) })
    })
    .then(r => { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(d => {
      if (d.success) {
        toast(d.message || 'Markah berjaya dikemaskini!', 'success');
        kmData.forEach(m => {
          if (kmChanges[m.id]) {
            m.originalMark  = kmChanges[m.id].markah_baru;
            m.originalGrade = gradeOf(m.originalMark, 100);
            m.catatan       = kmChanges[m.id].catatan;
          }
        });
        kmChanges = {};
        renderKmTable(kmData);
      } else toast(d.message || 'Gagal menyimpan', 'error');
    })
    .catch(err => { console.error(err); toast('Gagal menyambung ke pelayan', 'error'); });
  });
}

// ===================== HELPERS =====================
function gradeOf(m, fm) {
  fm = fm || 100;
  const p = (parseInt(m) / fm) * 100;
  if (p >= 90) return 'A+'; if (p >= 80) return 'A'; if (p >= 70) return 'B';
  if (p >= 60) return 'C';  if (p >= 50) return 'D'; if (p >= 40) return 'E';
  return 'F';
}
function gradeClass(g) {
  return { 'A+':'grade-aplus','A':'grade-a','B':'grade-b','C':'grade-c','D':'grade-d','E':'grade-e','F':'grade-f' }[g] || 'grade-f';
}
function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function toast(msg, type = 'info') {
  const t = document.createElement('div');
  const icons = { success:'check-circle', error:'exclamation-circle', warning:'exclamation-triangle', info:'info-circle' };
  t.className = `toast toast-${type}`;
  t.innerHTML = `<i class="fas fa-${icons[type]||'info-circle'}"></i> <span>${msg}</span>`;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

function confirm2(msg, onOk) {
  document.getElementById('confirmMsg').textContent = msg;
  const modal = document.getElementById('confirmModal');
  modal.classList.add('active');
  const btn    = document.getElementById('confirmOkBtn');
  const newBtn = btn.cloneNode(true);
  btn.parentNode.replaceChild(newBtn, btn);
  newBtn.onclick = () => { modal.classList.remove('active'); onOk(); };
}
function closeModal(id) { document.getElementById(id).classList.remove('active'); }

// Sidebar
const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const so         = document.getElementById('sidebarOverlay');
if (menuToggle) menuToggle.addEventListener('click', () => { sidebar.classList.toggle('sidebar-active'); so.classList.toggle('active'); });
if (so) so.addEventListener('click', () => { sidebar.classList.remove('sidebar-active'); so.classList.remove('active'); });
document.getElementById('confirmModal').addEventListener('click', function(e) { if (e.target === this) closeModal('confirmModal'); });

// Sync fullMarks display
const fmInp  = document.getElementById('fullMarks');
const fmDisp = document.getElementById('fullMarksDisplay');
if (fmInp && fmDisp) fmInp.addEventListener('input', () => fmDisp.textContent = fmInp.value);

<?php if ($active_tab === 'kemaskini'): ?>
loadKemaskiniData();
<?php endif; ?>
</script>
</body>
</html>
