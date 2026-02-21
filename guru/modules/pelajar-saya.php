<?php
session_start();
ob_start();

require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../includes/db_functions.php';

// Auth check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login-guru.php'); exit();
}

$guru_id = $_SESSION['guru_id'];
$current_page = 'pelajar-saya.php';
date_default_timezone_set('Asia/Kuala_Lumpur');

$guru_info  = getGuruById($guru_id);
$kelas_guru = getKelasByGuru($guru_id);
$all_kelas  = getAllKelas();

// Avatar initials
$initials = '';
if (!empty($_SESSION['guru_nama'])) {
    foreach (explode(' ', $_SESSION['guru_nama']) as $p)
        if (!empty($p)) $initials .= strtoupper(substr($p,0,1));
    $initials = substr($initials,0,2);
}

// ── POST HANDLERS ─────────────────────────────────────────────────────────────
$action     = $_GET['action'] ?? '';
$student_id = $_GET['id']     ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'add':
            $req = ['nama','no_ic','jantina','id_kelas'];
            $ok  = true;
            foreach ($req as $f) if (empty($_POST[$f])) { $ok = false; break; }
            if (!$ok) {
                $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
            } elseif (checkStudentExists(trim($_POST['no_ic']))) {
                $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
            } else {
                $data = [
                    'nama'     => trim($_POST['nama']),
                    'no_ic'    => trim($_POST['no_ic']),
                    'jantina'  => $_POST['jantina'],
                    'id_kelas' => intval($_POST['id_kelas']),
                    'status'   => $_POST['status'] ?? 'aktif',
                ];
                if (tambahPelajar($data))
                    $_SESSION['success_message'] = 'Pelajar berjaya ditambah!';
                else
                    $_SESSION['error_message'] = 'Gagal menambah pelajar. Sila cuba lagi.';
            }
            header('Location: pelajar-saya.php'); exit();

        case 'edit':
            if (empty($student_id)) {
                $_SESSION['error_message'] = 'ID pelajar tidak sah.';
            } else {
                $req = ['nama','no_ic','jantina','id_kelas'];
                $ok  = true;
                foreach ($req as $f) if (empty($_POST[$f])) { $ok = false; break; }
                if (!$ok) {
                    $_SESSION['error_message'] = 'Sila isi semua maklumat yang diperlukan.';
                } elseif (checkStudentExists(trim($_POST['no_ic']), $student_id)) {
                    $_SESSION['error_message'] = 'No. Kad Pengenalan sudah wujud dalam sistem.';
                } else {
                    $data = [
                        'nama'     => trim($_POST['nama']),
                        'no_ic'    => trim($_POST['no_ic']),
                        'jantina'  => $_POST['jantina'],
                        'id_kelas' => intval($_POST['id_kelas']),
                        'status'   => $_POST['status'] ?? 'aktif',
                    ];
                    if (kemaskiniPelajar($student_id, $data))
                        $_SESSION['success_message'] = 'Maklumat pelajar berjaya dikemaskini!';
                    else
                        $_SESSION['error_message'] = 'Gagal mengemaskini pelajar.';
                }
            }
            header('Location: pelajar-saya.php'); exit();

        case 'delete':
            if (!empty($student_id) && padamPelajar($student_id))
                $_SESSION['success_message'] = 'Pelajar berjaya dipadam!';
            else
                $_SESSION['error_message'] = 'Gagal memadam pelajar.';
            header('Location: pelajar-saya.php'); exit();

        case 'bulk_delete':
            if (!empty($_POST['student_ids']) && is_array($_POST['student_ids'])) {
                $n = 0;
                foreach ($_POST['student_ids'] as $sid) if (padamPelajar($sid)) $n++;
                if ($n > 0) $_SESSION['success_message'] = "Berjaya memadam $n pelajar!";
                else        $_SESSION['error_message'] = 'Gagal memadam pelajar terpilih.';
            } else {
                $_SESSION['error_message'] = 'Tiada pelajar dipilih.';
            }
            header('Location: pelajar-saya.php'); exit();

        case 'bulk_update':
            if (!empty($_POST['student_ids']) && is_array($_POST['student_ids']) && !empty($_POST['bulk_action'])) {
                $ids  = array_map('intval', $_POST['student_ids']);
                $bact = $_POST['bulk_action'];
                if ($bact === 'update_status' && !empty($_POST['new_status'])) {
                    $ok = bulkUpdateStudents($ids, ['status' => $_POST['new_status']]);
                    $_SESSION[$ok ? 'success_message' : 'error_message'] = $ok ? 'Status pelajar berjaya dikemaskini!' : 'Gagal kemaskini status.';
                } elseif ($bact === 'change_class' && !empty($_POST['new_kelas'])) {
                    $ok = bulkUpdateStudents($ids, ['id_kelas' => intval($_POST['new_kelas'])]);
                    $_SESSION[$ok ? 'success_message' : 'error_message'] = $ok ? 'Kelas pelajar berjaya ditukar!' : 'Gagal tukar kelas.';
                }
            } else {
                $_SESSION['error_message'] = 'Tiada pelajar dipilih.';
            }
            header('Location: pelajar-saya.php'); exit();

        case 'import':
            if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error_message'] = 'Gagal memuat naik fail.';
            } else {
                $file_ext = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));
                if ($file_ext !== 'csv') {
                    $_SESSION['error_message'] = 'Hanya format CSV disokong.';
                } else {
                    $ok_n = 0; $err_n = 0;
                    if (($fh = fopen($_FILES['import_file']['tmp_name'], 'r')) !== false) {
                        $row = 0;
                        while (($d = fgetcsv($fh, 1000)) !== false) {
                            $row++;
                            if ($row === 1) continue;
                            if (count($d) < 4) { $err_n++; continue; }
                            $j = strtoupper(trim($d[2]));
                            if (!in_array($j,['L','P'])) { $err_n++; continue; }
                            if (checkStudentExists(trim($d[1]))) { $err_n++; continue; }
                            if (tambahPelajar(['nama'=>trim($d[0]),'no_ic'=>trim($d[1]),'jantina'=>$j,'id_kelas'=>intval($d[3]),'status'=>'aktif']))
                                $ok_n++;
                            else
                                $err_n++;
                        }
                        fclose($fh);
                    }
                    if ($ok_n > 0) $_SESSION['success_message'] = "Berjaya import $ok_n pelajar!" . ($err_n > 0 ? " ($err_n gagal)" : '');
                    else           $_SESSION['error_message']   = "Tiada pelajar berjaya diimport. $err_n rekod gagal.";
                }
            }
            header('Location: pelajar-saya.php'); exit();
    }
}

// ── AJAX handlers ─────────────────────────────────────────────────────────────
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    switch ($_GET['ajax']) {
        case 'get_students':
            $list  = getPelajarByGuru($guru_id, $_GET['search']??'', $_GET['kelas']??'', $_GET['status']??'');
            $stats = getStatistikPelajar($guru_id);
            echo json_encode(['success'=>true,'students'=>$list,'statistics'=>$stats]);
            exit;

        case 'get_students_by_class':
            $kid  = intval($_GET['kelas_id'] ?? 0);
            $list = getStudentsByClass($kid);
            echo json_encode(['success'=>true,'students'=>$list,'total'=>count($list)]);
            exit;

        case 'delete_student':
            $sid = intval($_GET['student_id'] ?? 0);
            if (padamPelajar($sid))
                echo json_encode(['success'=>true,'message'=>'Pelajar berjaya dipadam']);
            else
                echo json_encode(['success'=>false,'message'=>'Gagal memadam pelajar']);
            exit;

        case 'check_ic':
            $ic  = $_GET['no_ic']      ?? '';
            $eid = $_GET['exclude_id'] ?? null;
            echo json_encode(['exists'=> checkStudentExists($ic, $eid)]);
            exit;
    }
}

// ── Data for view ─────────────────────────────────────────────────────────────
$pelajar_list = getPelajarByGuru($guru_id);
$statistik    = getStatistikPelajar($guru_id);

// Flash messages
$success_msg = $_SESSION['success_message'] ?? '';
$error_msg   = $_SESSION['error_message']   ?? '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pelajar Saya — SlipKu</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --primary:#4f46e5;--primary-light:#eef2ff;
  --secondary:#7c3aed;--success:#10b981;--warning:#f59e0b;
  --danger:#ef4444;--info:#3b82f6;
  --dark:#1f2937;--gray:#6b7280;--light:#f9fafb;--white:#ffffff;
  --radius:20px;--tr:all .3s ease;
}
body{font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#f8fafc,#f1f5f9);color:var(--dark);min-height:100vh;overflow-x:hidden}

/* HEADER */
.header{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 30px}
.header-container{display:flex;align-items:center;justify-content:space-between;padding:18px 0}
.logo{display:flex;align-items:center;gap:14px;text-decoration:none}
.logo-icon{width:44px;height:44px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.logo-text h1{font-size:22px;font-weight:800;color:var(--primary)}
.logo-text p{font-size:11px;color:var(--gray);font-weight:500}
.menu-toggle{display:none;background:none;border:none;font-size:24px;color:var(--primary);cursor:pointer;padding:8px;border-radius:8px}
.user-profile{display:flex;align-items:center;gap:12px;padding:8px 14px;border-radius:12px;background:var(--light);cursor:pointer}
.user-avatar{width:38px;height:38px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px}
.user-info h4{font-size:13px;font-weight:600;color:var(--dark)}
.user-info p{font-size:11px;color:var(--gray)}

/* SIDEBAR */
.sidebar{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;left:0;top:80px;bottom:0;width:255px;padding:28px 0;overflow-y:auto;z-index:900;transition:var(--tr)}
.sidebar-section{margin-bottom:28px;padding:0 22px}
.sidebar-title{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--gray);margin-bottom:12px;font-weight:600}
.sidebar-item{display:flex;align-items:center;gap:14px;padding:11px 18px;color:var(--gray);text-decoration:none;border-radius:12px;margin:4px 0;transition:var(--tr)}
.sidebar-item:hover{background:var(--light);color:var(--primary);transform:translateX(4px)}
.sidebar-item.active{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 8px 25px rgba(79,70,229,.3)}
.sidebar-item.active i{color:#fff}
.sidebar-item i{width:18px;font-size:15px}
.badge{background:var(--danger);color:#fff;font-size:10px;padding:2px 7px;border-radius:10px;margin-left:auto}
.sidebar-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:899;display:none}
.sidebar-overlay.active{display:block}

/* MAIN */
.main-content{margin-left:255px;margin-top:80px;padding:28px;transition:var(--tr)}

/* PAGE HEADER */
.page-header{margin-bottom:26px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px}
.page-title h2{font-size:28px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:6px}
.page-title p{color:var(--gray);font-size:14px}
.page-actions{display:flex;gap:10px;flex-wrap:wrap}

/* TABS */
.view-tabs{display:flex;gap:10px;margin-bottom:24px;flex-wrap:wrap}
.tab-btn{padding:11px 22px;border-radius:12px;font-weight:600;font-size:13px;cursor:pointer;transition:var(--tr);border:2px solid #e5e7eb;background:var(--white);color:var(--gray);font-family:'Poppins',sans-serif;display:flex;align-items:center;gap:8px}
.tab-btn.active{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;border-color:transparent;box-shadow:0 8px 20px rgba(79,70,229,.3)}
.tab-btn:hover:not(.active){background:var(--primary-light);color:var(--primary);border-color:var(--primary)}

/* BUTTONS */
.btn{padding:11px 22px;border-radius:12px;font-weight:600;font-size:13px;cursor:pointer;transition:var(--tr);text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;font-family:'Poppins',sans-serif;white-space:nowrap}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 6px 20px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(79,70,229,.4)}
.btn-secondary{background:var(--white);color:var(--dark);border:2px solid #e5e7eb}
.btn-secondary:hover{background:var(--light);transform:translateY(-2px)}
.btn-success{background:var(--success);color:#fff}
.btn-success:hover{background:#0da271;transform:translateY(-2px)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{background:#dc2626}
.btn-sm{padding:7px 14px;font-size:12px}
.action-btn{padding:7px 13px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:var(--tr);border:none;display:inline-flex;align-items:center;gap:5px;font-family:'Poppins',sans-serif}
.action-btn.view{background:var(--info);color:#fff}.action-btn.view:hover{background:#2563eb}
.action-btn.edit{background:var(--warning);color:#fff}.action-btn.edit:hover{background:#d97706}
.action-btn.delete{background:var(--danger);color:#fff}.action-btn.delete:hover{background:#dc2626}

/* SUMMARY BANNER */
.summary-banner{background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:var(--radius);padding:24px 30px;color:#fff;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:18px}
.summary-banner h3{font-size:18px;font-weight:700;margin-bottom:4px}
.summary-banner p{opacity:.85;font-size:13px}
.summary-stats{display:flex;gap:28px;flex-wrap:wrap}
.stat-box{text-align:center}
.stat-num{font-size:26px;font-weight:800}
.stat-lbl{font-size:11px;opacity:.8;text-transform:uppercase;letter-spacing:.5px}

/* SEARCH/FILTER */
.search-section{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:22px}
.search-wrap{position:relative;margin-bottom:16px}
.search-input{width:100%;padding:13px 18px 13px 46px;border:2px solid #e5e7eb;border-radius:12px;font-size:14px;font-family:'Poppins',sans-serif;transition:var(--tr)}
.search-input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,70,229,.1)}
.search-icon{position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--gray)}
.filter-row{display:flex;gap:14px;flex-wrap:wrap;align-items:center}
.filter-label{font-size:13px;font-weight:600;white-space:nowrap}
.filter-select{padding:9px 14px;border:2px solid #e5e7eb;border-radius:10px;font-size:13px;font-family:'Poppins',sans-serif;background:var(--white);cursor:pointer;min-width:140px;transition:var(--tr)}
.filter-select:focus{outline:none;border-color:var(--primary)}

/* TABLE */
.table-card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:22px;overflow-x:auto}
table{width:100%;border-collapse:collapse;min-width:800px}
th{background:var(--light);padding:14px 16px;text-align:left;font-weight:600;font-size:12px;color:var(--gray);text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e5e7eb}
td{padding:12px 16px;border-bottom:1px solid #e5e7eb;font-size:13px;vertical-align:middle}
tr:hover td{background:var(--primary-light)}
.stu-cell{display:flex;align-items:center;gap:11px}
.stu-av{width:38px;height:38px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0}
.stu-name{font-weight:600;color:var(--dark);font-size:13px}
.stu-ic{font-size:11px;color:var(--gray)}
.badge-status{padding:4px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;display:inline-block;min-width:80px;text-align:center}
.s-aktif{background:rgba(16,185,129,.12);color:var(--success)}
.s-inactive{background:rgba(239,68,68,.1);color:var(--danger)}
.s-tamat{background:rgba(59,130,246,.1);color:var(--info)}

/* BULK */
.bulk-bar{background:var(--light);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:none;gap:14px;align-items:center;flex-wrap:wrap}
.bulk-bar.visible{display:flex}

/* PAGINATION */
.pagination{display:flex;justify-content:space-between;align-items:center;padding:16px 22px;background:var(--white);border-radius:var(--radius);box-shadow:0 6px 20px rgba(0,0,0,.07)}
.pagination-info{font-size:13px;color:var(--gray)}
.page-controls{display:flex;gap:8px}
.pg-btn{width:36px;height:36px;border:2px solid #e5e7eb;border-radius:8px;background:var(--white);color:var(--dark);cursor:pointer;transition:var(--tr);display:flex;align-items:center;justify-content:center;font-size:13px;font-family:'Poppins',sans-serif}
.pg-btn:hover:not(:disabled){background:var(--primary-light);border-color:var(--primary)}
.pg-btn.active{background:var(--primary);color:#fff;border-color:var(--primary)}
.pg-btn:disabled{opacity:.4;cursor:not-allowed}

/* PER-KELAS */
.kelas-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:20px;margin-bottom:24px}
.kelas-card{background:var(--white);border-radius:var(--radius);box-shadow:0 6px 20px rgba(0,0,0,.07);overflow:hidden;transition:var(--tr)}
.kelas-card:hover{box-shadow:0 12px 32px rgba(0,0,0,.12);transform:translateY(-2px)}
.kelas-card-hd{padding:18px 22px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;display:flex;justify-content:space-between;align-items:center;cursor:pointer;user-select:none}
.kelas-card-hd h3{font-size:15px;font-weight:700;margin-bottom:3px}
.kelas-meta{font-size:12px;opacity:.85}
.kelas-bdg{background:rgba(255,255,255,.25);color:#fff;border-radius:20px;padding:4px 12px;font-size:12px;font-weight:700;white-space:nowrap}
.kelas-card-bd{max-height:0;overflow:hidden;transition:max-height .4s ease}
.kelas-card-bd.open{max-height:700px;overflow-y:auto}
.kelas-row{display:flex;align-items:center;justify-content:space-between;padding:10px 18px;border-bottom:1px solid #f1f5f9;gap:10px}
.kelas-row:last-child{border-bottom:none}
.kelas-row:hover{background:var(--primary-light)}
.kelas-stu-info{display:flex;align-items:center;gap:10px;flex:1;min-width:0}
.kelas-av{width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:11px;flex-shrink:0}
.kelas-name{font-size:13px;font-weight:600;color:var(--dark);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.kelas-ic{font-size:11px;color:var(--gray)}
.kelas-footer{padding:12px 18px;background:var(--light);display:flex;gap:8px;justify-content:flex-end}
.kelas-empty{padding:28px;text-align:center;color:var(--gray);font-size:13px}
.kelas-loading{padding:18px;text-align:center;color:var(--gray);font-size:13px}

/* MODAL */
.modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:10000;justify-content:center;align-items:center;padding:16px;backdrop-filter:blur(3px)}
.modal.active{display:flex}
.modal-content{background:var(--white);border-radius:var(--radius);width:100%;max-width:580px;max-height:90vh;overflow-y:auto;animation:slideIn .3s ease}
@keyframes slideIn{from{opacity:0;transform:translateY(-20px)}to{opacity:1;transform:translateY(0)}}
.modal-header{padding:22px 26px;border-bottom:2px solid var(--light);display:flex;justify-content:space-between;align-items:center}
.modal-header h3{font-size:18px;font-weight:700}
.modal-close{background:none;border:none;font-size:18px;color:var(--gray);cursor:pointer;padding:5px;border-radius:8px;transition:var(--tr)}
.modal-close:hover{background:var(--light);color:var(--danger)}
.modal-body{padding:22px 26px}
.form-group{margin-bottom:18px}
.form-label{display:block;font-size:13px;font-weight:600;margin-bottom:7px}
.form-label.req::after{content:' *';color:var(--danger)}
.form-input,.form-select{width:100%;padding:11px 14px;border:2px solid #e5e7eb;border-radius:12px;font-size:13px;font-family:'Poppins',sans-serif;background:var(--white);transition:var(--tr)}
.form-input:focus,.form-select:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(79,70,229,.1)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.form-actions{display:flex;gap:12px;justify-content:flex-end;padding-top:18px;border-top:2px solid var(--light)}
.field-error{font-size:11px;color:var(--danger);margin-top:5px;display:none}

/* EMPTY */
.empty-state{text-align:center;padding:50px 20px;color:var(--gray)}
.empty-state i{font-size:44px;color:#d1d5db;margin-bottom:14px;display:block}
.empty-state h3{font-size:16px;color:var(--dark);margin-bottom:8px}

/* NOTIF */
.notif{position:fixed;top:92px;right:26px;z-index:20000;padding:14px 22px;border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,.2);display:flex;align-items:center;gap:10px;max-width:380px;animation:slideInR .3s ease;font-family:'Poppins',sans-serif;font-size:13px}
@keyframes slideInR{from{transform:translateX(120%);opacity:0}to{transform:translateX(0);opacity:1}}
@keyframes slideOutR{from{transform:translateX(0);opacity:1}to{transform:translateX(120%);opacity:0}}
.notif-success{background:var(--success);color:#fff}
.notif-error{background:var(--danger);color:#fff}
.notif-warning{background:var(--warning);color:#fff}

/* LOADING */
#loadingOverlay{position:fixed;inset:0;background:rgba(255,255,255,.8);z-index:15000;display:none;align-items:center;justify-content:center}
#loadingOverlay.active{display:flex}
.spinner{width:46px;height:46px;border:5px solid var(--primary-light);border-top-color:var(--primary);border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 14px}
@keyframes spin{to{transform:rotate(360deg)}}

/* RESPONSIVE */
@media(max-width:1024px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.active{transform:translateX(0)}
  .main-content{margin-left:0}
  .menu-toggle{display:block}
  .user-info{display:none}
}
@media(max-width:768px){
  .main-content{padding:16px;margin-top:72px}
  .page-header{flex-direction:column;align-items:flex-start}
  .form-row{grid-template-columns:1fr}
  .summary-banner{flex-direction:column}
  .kelas-grid{grid-template-columns:1fr}
  .pagination{flex-direction:column;gap:12px}
  .filter-row{flex-direction:column;align-items:flex-start}
}
</style>
</head>
<body>

<!-- Loading -->
<div id="loadingOverlay"><div style="text-align:center"><div class="spinner"></div><p style="color:var(--primary);font-weight:600">Memuatkan...</p></div></div>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL: TAMBAH / EDIT PELAJAR
═══════════════════════════════════════════════════════════════ -->
<div class="modal" id="modalPelajar">
  <div class="modal-content">
    <div class="modal-header">
      <h3 id="modalTitle"><i class="fas fa-user-plus" style="color:var(--primary)"></i> Tambah Pelajar Baru</h3>
      <button class="modal-close" onclick="tutupModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form id="formPelajar" method="POST">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label req">Nama Penuh</label>
            <input type="text" class="form-input" name="nama" id="f_nama" placeholder="Nama penuh pelajar" required>
          </div>
          <div class="form-group">
            <label class="form-label req">No. Kad Pengenalan</label>
            <input type="text" class="form-input" name="no_ic" id="f_ic"
                   placeholder="Cth: 030101-14-1234"
                   onblur="semakIC()" required>
            <div class="field-error" id="icError">No. KP sudah wujud dalam sistem</div>
            <div class="field-error" id="icFmt">Format tidak sah. Gunakan: 030101-14-1234</div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label class="form-label req">Jantina</label>
            <select class="form-select" name="jantina" id="f_jantina" required>
              <option value="">Pilih Jantina</option>
              <option value="L">Lelaki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label req">Kelas</label>
            <select class="form-select" name="id_kelas" id="f_kelas" required>
              <option value="">Pilih Kelas</option>
              <?php
              // Show guru's own classes first, then others
              $shown = [];
              foreach ($kelas_guru as $k):
                $shown[] = $k['id'];
              ?>
              <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?> — Tahun <?= $k['tahun'] ?></option>
              <?php endforeach;
              foreach ($all_kelas as $k):
                if (!in_array($k['id'], $shown)): ?>
              <option value="<?= $k['id'] ?>" style="color:var(--gray)"><?= htmlspecialchars($k['nama']) ?> — Tahun <?= $k['tahun'] ?></option>
              <?php endif; endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Status Pelajar</label>
          <select class="form-select" name="status" id="f_status">
            <option value="aktif">Aktif</option>
            <option value="tidak aktif">Tidak Aktif</option>
            <option value="tamat">Tamat</option>
          </select>
        </div>
        <input type="hidden" name="_edit_id" id="f_edit_id">
        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="tutupModal()">
            <i class="fas fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary" id="btnSimpan">
            <i class="fas fa-save"></i> Simpan Pelajar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL: IMPORT
═══════════════════════════════════════════════════════════════ -->
<div class="modal" id="modalImport">
  <div class="modal-content">
    <div class="modal-header">
      <h3><i class="fas fa-file-upload" style="color:var(--success)"></i> Import Pelajar dari CSV</h3>
      <button class="modal-close" onclick="tutupImport()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form method="POST" action="?action=import" enctype="multipart/form-data">
        <div class="file-upload" onclick="document.getElementById('impFile').click()" style="border:2px dashed #e5e7eb;border-radius:12px;padding:32px;text-align:center;cursor:pointer;transition:var(--tr)">
          <i class="fas fa-cloud-upload-alt" style="font-size:40px;color:var(--primary);margin-bottom:12px;display:block"></i>
          <p style="font-weight:600;margin-bottom:6px">Klik untuk pilih fail CSV</p>
          <p id="impFileName" style="color:var(--primary);font-weight:600;margin-top:8px;min-height:18px"></p>
          <p style="color:var(--gray);font-size:12px">Format: .csv sahaja</p>
        </div>
        <input type="file" id="impFile" name="import_file" accept=".csv" style="display:none"
               onchange="document.getElementById('impFileName').textContent=this.files[0]?.name||''">
        <div style="margin:16px 0;padding:14px;background:var(--light);border-radius:12px;font-size:12px;color:var(--gray);line-height:2">
          <strong style="color:var(--primary)"><i class="fas fa-info-circle"></i> Format CSV:</strong><br>
          <code>Nama,No_KP,Jantina,ID_Kelas</code><br>
          <em>Cth: Ahmad bin Ali,030101-14-1234,L,1</em><br>
          Jantina: <strong>L</strong> = Lelaki &nbsp;|&nbsp; <strong>P</strong> = Perempuan
        </div>
        <div style="display:flex;gap:12px;justify-content:flex-end">
          <button type="button" class="btn btn-secondary" onclick="tutupImport()">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Mula Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════
     MODAL: BUTIRAN PELAJAR
═══════════════════════════════════════════════════════════════ -->
<div class="modal" id="modalButiran">
  <div class="modal-content" style="max-width:480px">
    <div class="modal-header">
      <h3>Butiran Pelajar</h3>
      <button class="modal-close" onclick="tutupButiran()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body" id="butiranBody"></div>
  </div>
</div>

<!-- Sidebar overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ═══════════════════════════════════════════════════════════════
     HEADER
═══════════════════════════════════════════════════════════════ -->
<header class="header">
  <div class="header-container">
    <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <a href="../dashboard-guru.php" class="logo">
      <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="logo-text"><h1>SlipKu</h1><p>Pelajar Saya</p></div>
    </a>
    <div class="user-profile">
      <div class="user-avatar"><?= htmlspecialchars($initials ?: 'GU') ?></div>
      <div class="user-info">
        <h4><?= htmlspecialchars($_SESSION['guru_nama'] ?? 'Guru') ?></h4>
        <p>Guru</p>
      </div>
      <i class="fas fa-chevron-down" style="color:var(--gray);font-size:12px"></i>
    </div>
  </div>
</header>

<!-- ═══════════════════════════════════════════════════════════════
     SIDEBAR
═══════════════════════════════════════════════════════════════ -->
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<!-- ═══════════════════════════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════════════════════════ -->
<main class="main-content" id="mainContent">

  <!-- Page Header -->
  <div class="page-header">
    <div class="page-title">
      <h2>Pelajar Saya 👨‍🎓</h2>
      <p>Urus dan pantau pelajar di bawah kendalian anda</p>
    </div>
    <div class="page-actions">
      <button class="btn btn-secondary" onclick="bukaImport()">
        <i class="fas fa-upload"></i> Import CSV
      </button>
      <button class="btn btn-secondary" onclick="muatSemula()">
        <i class="fas fa-sync-alt"></i> Muat Semula
      </button>
      <button class="btn btn-primary" onclick="bukaTambah()">
        <i class="fas fa-plus-circle"></i> Tambah Pelajar
      </button>
    </div>
  </div>

  <!-- View Tabs -->
  <div class="view-tabs">
    <button class="tab-btn active" id="tabSemua" onclick="switchTab('semua')">
      <i class="fas fa-list-ul"></i> Semua Pelajar
    </button>
    <button class="tab-btn" id="tabKelas" onclick="switchTab('kelas')">
      <i class="fas fa-school"></i> Lihat Per Kelas
    </button>
  </div>

  <!-- ══════════════════ TAB: SEMUA PELAJAR ══════════════════ -->
  <div id="viewSemua">

    <!-- Summary Banner -->
    <div class="summary-banner">
      <div>
        <h3>Jumlah Pelajar Anda</h3>
        <p>Semua kelas yang anda kendalikan</p>
      </div>
      <div class="summary-stats">
        <div class="stat-box">
          <div class="stat-num" id="sTot"><?= $statistik['total_pelajar'] ?? 0 ?></div>
          <div class="stat-lbl">Pelajar</div>
        </div>
        <div class="stat-box">
          <div class="stat-num" id="sAkt"><?= $statistik['pelajar_aktif'] ?? 0 ?></div>
          <div class="stat-lbl">Aktif</div>
        </div>
        <div class="stat-box">
          <div class="stat-num"><?= count($kelas_guru) ?></div>
          <div class="stat-lbl">Kelas</div>
        </div>
      </div>
    </div>

    <!-- Search & Filter -->
    <div class="search-section">
      <div class="search-wrap">
        <i class="fas fa-search search-icon"></i>
        <input type="text" class="search-input" id="cariInput"
               placeholder="Cari nama, No. KP, atau kelas..." oninput="cariPelajar()">
      </div>
      <div class="filter-row">
        <span class="filter-label">Kelas:</span>
        <select class="filter-select" id="filtKelas" onchange="filterPelajar()">
          <option value="">Semua Kelas</option>
          <?php foreach ($kelas_guru as $k): ?>
          <option value="<?= htmlspecialchars($k['nama']) ?>"><?= htmlspecialchars($k['nama']) ?></option>
          <?php endforeach; ?>
        </select>
        <span class="filter-label">Status:</span>
        <select class="filter-select" id="filtStatus" onchange="filterPelajar()">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="tidak aktif">Tidak Aktif</option>
          <option value="tamat">Tamat</option>
        </select>
        <button class="btn btn-secondary btn-sm" onclick="resetFilter()">
          <i class="fas fa-undo"></i> Reset
        </button>
      </div>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-bar" id="bulkBar">
      <span id="bulkCount" style="font-weight:700;color:var(--primary)">0 dipilih</span>
      <select class="filter-select" id="bulkAct" onchange="showBulkExtra()">
        <option value="">-- Pilih Tindakan Massal --</option>
        <option value="update_status">Kemaskini Status</option>
        <option value="change_class">Tukar Kelas</option>
        <option value="delete">Padam Semua</option>
      </select>
      <select class="filter-select" id="bulkStatus" style="display:none">
        <option value="aktif">Aktif</option>
        <option value="tidak aktif">Tidak Aktif</option>
        <option value="tamat">Tamat</option>
      </select>
      <select class="filter-select" id="bulkKelas" style="display:none">
        <option value="">Pilih Kelas</option>
        <?php foreach ($kelas_guru as $k): ?>
        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-primary btn-sm" onclick="gunakanBulk()">
        <i class="fas fa-check"></i> Guna
      </button>
      <button class="btn btn-secondary btn-sm" onclick="batalBulk()">
        <i class="fas fa-times"></i> Batal
      </button>
    </div>

    <!-- Table -->
    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th style="width:40px">
              <input type="checkbox" id="selectAll" onchange="togolSemua()">
            </th>
            <th>PELAJAR</th>
            <th>KELAS</th>
            <th>TAHUN</th>
            <th>JANTINA</th>
            <th>STATUS</th>
            <th style="width:160px">TINDAKAN</th>
          </tr>
        </thead>
        <tbody id="jadualBody">
          <tr>
            <td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">
              <i class="fas fa-spinner fa-spin"></i> Memuatkan data...
            </td>
          </tr>
        </tbody>
      </table>
      <div class="empty-state" id="emptyState" style="display:none">
        <i class="fas fa-user-graduate"></i>
        <h3>Tiada Pelajar Ditemui</h3>
        <p>Cuba ubah penapis atau tambah pelajar baru.</p>
        <button class="btn btn-secondary btn-sm" style="margin-top:10px" onclick="resetFilter()">
          <i class="fas fa-undo"></i> Reset Penapis
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
      <div class="pagination-info" id="pgInfo">–</div>
      <div class="page-controls" id="pgControls"></div>
    </div>

  </div><!-- /#viewSemua -->

  <!-- ══════════════════ TAB: PER KELAS ══════════════════ -->
  <div id="viewKelas" style="display:none">

    <!-- Header info -->
    <div style="background:var(--white);border-radius:var(--radius);padding:20px 26px;
                box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:22px;
                display:flex;align-items:center;gap:14px">
      <div style="width:44px;height:44px;background:var(--primary-light);border-radius:12px;
                  display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--primary)">
        <i class="fas fa-school"></i>
      </div>
      <div>
        <h3 style="font-size:16px;font-weight:700;color:var(--dark)">
          Senarai Pelajar Mengikut Kelas
        </h3>
        <p style="font-size:13px;color:var(--gray)">
          Klik pada mana-mana kelas untuk kembangkan dan lihat senarai pelajar
        </p>
      </div>
      <button class="btn btn-primary btn-sm" style="margin-left:auto" onclick="bukaTambah()">
        <i class="fas fa-plus"></i> Tambah Pelajar
      </button>
    </div>

    <?php if (empty($kelas_guru)): ?>
    <div class="empty-state">
      <i class="fas fa-school"></i>
      <h3>Tiada Kelas Dijumpai</h3>
      <p>Anda belum ditetapkan ke mana-mana kelas.</p>
    </div>
    <?php else: ?>

    <div class="kelas-grid">
      <?php foreach ($kelas_guru as $k):
        $bil = count(array_filter($pelajar_list, fn($p) => $p['id_kelas'] == $k['id']));
      ?>
      <div class="kelas-card" id="card_<?= $k['id'] ?>">

        <!-- Card Header (clickable) -->
        <div class="kelas-card-hd" onclick="toggleKelas(<?= $k['id'] ?>)">
          <div>
            <h3><?= htmlspecialchars($k['nama']) ?></h3>
            <div class="kelas-meta">
              Tahun <?= $k['tahun'] ?> &nbsp;•&nbsp;
              <span id="cnt_<?= $k['id'] ?>"><?= $bil ?></span> pelajar
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:10px">
            <span class="kelas-bdg" id="bdg_<?= $k['id'] ?>"><?= $bil ?></span>
            <i class="fas fa-chevron-down" id="chv_<?= $k['id'] ?>"
               style="transition:transform .3s ease"></i>
          </div>
        </div>

        <!-- Card Body (expandable) -->
        <div class="kelas-card-bd" id="body_<?= $k['id'] ?>">
          <div class="kelas-loading" id="ld_<?= $k['id'] ?>">
            <i class="fas fa-spinner fa-spin"></i> Memuatkan pelajar...
          </div>
          <div id="list_<?= $k['id'] ?>"></div>
          <div class="kelas-footer">
            <button class="btn btn-success btn-sm"
                    onclick="bukaTambahKelas(<?= $k['id'] ?>, '<?= htmlspecialchars($k['nama'], ENT_QUOTES) ?>')">
              <i class="fas fa-user-plus"></i> Tambah Pelajar ke Kelas Ini
            </button>
          </div>
        </div>

      </div><!-- /.kelas-card -->
      <?php endforeach; ?>
    </div><!-- /.kelas-grid -->

    <?php endif; ?>
  </div><!-- /#viewKelas -->

</main>

<script>
// ── STATE ──────────────────────────────────────────────────────────────────────
let allStudents  = [];
let filtStudents = [];
let selStudents  = [];
let curPage      = 1;
const PER_PAGE   = 15;
let kelasLoaded  = {};
let activeTab    = 'semua';

// ── INIT ───────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  muatData();
  <?php if ($success_msg): ?>
  notif('<?= addslashes($success_msg) ?>', 'success');
  <?php endif; ?>
  <?php if ($error_msg): ?>
  notif('<?= addslashes($error_msg) ?>', 'error');
  <?php endif; ?>
});

// ── LOAD ALL STUDENTS ──────────────────────────────────────────────────────────
function muatData() {
  loading(true);
  fetch('?ajax=get_students')
    .then(r => r.json())
    .then(d => {
      loading(false);
      if (d.success) {
        allStudents  = d.students || [];
        filtStudents = [...allStudents];
        if (d.statistics) {
          setTxt('sTot', d.statistics.total_pelajar || 0);
          setTxt('sAkt', d.statistics.pelajar_aktif || 0);
        }
        renderJadual();
        renderPaging();
      } else notif('Gagal memuatkan data pelajar', 'error');
    })
    .catch(() => { loading(false); notif('Ralat sambungan. Sila muat semula.','error'); });
}

function muatSemula() {
  selStudents = [];
  kemaskiniBlukBar();
  muatData();
  notif('Data dimuat semula','success');
}

// ── TABS ───────────────────────────────────────────────────────────────────────
function switchTab(tab) {
  activeTab = tab;
  document.getElementById('viewSemua').style.display = tab === 'semua' ? 'block' : 'none';
  document.getElementById('viewKelas').style.display = tab === 'kelas' ? 'block' : 'none';
  document.getElementById('tabSemua').className = 'tab-btn' + (tab === 'semua' ? ' active' : '');
  document.getElementById('tabKelas').className = 'tab-btn' + (tab === 'kelas' ? ' active' : '');
}

// ── RENDER TABLE ───────────────────────────────────────────────────────────────
function renderJadual() {
  const tbody = document.getElementById('jadualBody');
  const empty = document.getElementById('emptyState');
  if (!filtStudents.length) {
    tbody.innerHTML = '';
    empty.style.display = 'block';
    return;
  }
  empty.style.display = 'none';
  const start = (curPage - 1) * PER_PAGE;
  const slice = filtStudents.slice(start, start + PER_PAGE);
  tbody.innerHTML = slice.map(p => {
    const ini = initials(p.nama);
    const sel = selStudents.includes(String(p.id));
    const jt  = p.jantina === 'L' ? 'Lelaki' : p.jantina === 'P' ? 'Perempuan' : (p.jantina || '–');
    const [sc, st] = statusInfo(p.status);
    return `<tr>
      <td><input type="checkbox" class="stu-cb" value="${p.id}" ${sel ? 'checked' : ''} onchange="togolSatu('${p.id}')"></td>
      <td><div class="stu-cell">
        <div class="stu-av">${ini}</div>
        <div><div class="stu-name">${esc(p.nama||'')}</div><div class="stu-ic">${esc(p.no_kp||'–')}</div></div>
      </div></td>
      <td>${esc(p.kelas_nama||'–')}</td>
      <td>${p.tahun ? 'Tahun '+p.tahun : '–'}</td>
      <td>${jt}</td>
      <td><span class="badge-status ${sc}">${st}</span></td>
      <td><div style="display:flex;gap:6px">
        <button class="action-btn view" onclick="lihatPelajar(${p.id})" title="Lihat"><i class="fas fa-eye"></i></button>
        <button class="action-btn edit" onclick="editPelajar(${p.id})" title="Edit"><i class="fas fa-edit"></i></button>
        <button class="action-btn delete" onclick="padamPelajar(${p.id},'${esc(p.nama||'').replace(/'/g,\"\\\\'\")}' )" title="Padam"><i class="fas fa-trash"></i></button>
      </div></td>
    </tr>`;
  }).join('');
}

function statusInfo(s) {
  if (!s || s === 'aktif' || s === '1')  return ['s-aktif',    'AKTIF'];
  if (s === 'tamat' || s === '2')        return ['s-tamat',    'TAMAT'];
  return ['s-inactive', 'TIDAK AKTIF'];
}

// ── PAGINATION ─────────────────────────────────────────────────────────────────
function renderPaging() {
  const tot   = filtStudents.length;
  const pages = Math.ceil(tot / PER_PAGE) || 1;
  const start = (curPage - 1) * PER_PAGE + 1;
  const end   = Math.min(curPage * PER_PAGE, tot);
  setTxt('pgInfo', tot ? `Menunjukkan ${start}–${end} daripada ${tot} pelajar` : 'Tiada pelajar');
  let html = `<button class="pg-btn" onclick="tukarHalaman(${curPage-1})" ${curPage<=1?'disabled':''}>
    <i class="fas fa-chevron-left"></i></button>`;
  const s = Math.max(1, curPage-2), e = Math.min(pages, curPage+2);
  for (let i = s; i <= e; i++)
    html += `<button class="pg-btn ${i===curPage?'active':''}" onclick="tukarHalaman(${i})">${i}</button>`;
  html += `<button class="pg-btn" onclick="tukarHalaman(${curPage+1})" ${curPage>=pages?'disabled':''}>
    <i class="fas fa-chevron-right"></i></button>`;
  document.getElementById('pgControls').innerHTML = html;
}

function tukarHalaman(p) {
  const pages = Math.ceil(filtStudents.length / PER_PAGE) || 1;
  if (p < 1 || p > pages) return;
  curPage = p;
  renderJadual();
  renderPaging();
  window.scrollTo({top:0,behavior:'smooth'});
}

// ── SEARCH / FILTER ────────────────────────────────────────────────────────────
function cariPelajar() { curPage = 1; filterPelajar(); }

function filterPelajar() {
  const cari   = (document.getElementById('cariInput').value||'').toLowerCase();
  const kelas  = (document.getElementById('filtKelas').value||'').toLowerCase();
  const status = (document.getElementById('filtStatus').value||'').toLowerCase();
  filtStudents = allStudents.filter(p => {
    const mc = !cari   || (p.nama||'').toLowerCase().includes(cari)
                       || (p.no_kp||'').toLowerCase().includes(cari)
                       || (p.kelas_nama||'').toLowerCase().includes(cari);
    const mk = !kelas  || (p.kelas_nama||'').toLowerCase().includes(kelas);
    const ms = !status || (p.status||'aktif').toLowerCase() === status
                       || (status==='aktif' && (p.status==='1'||!p.status));
    return mc && mk && ms;
  });
  curPage = 1;
  renderJadual();
  renderPaging();
}

function resetFilter() {
  document.getElementById('cariInput').value  = '';
  document.getElementById('filtKelas').value  = '';
  document.getElementById('filtStatus').value = '';
  filtStudents = [...allStudents];
  curPage = 1;
  renderJadual();
  renderPaging();
}

// ── SELECTION & BULK ───────────────────────────────────────────────────────────
function togolSemua() {
  const cb  = document.getElementById('selectAll');
  const cbs = document.querySelectorAll('.stu-cb');
  selStudents = cb.checked ? Array.from(cbs).map(c => c.value) : [];
  cbs.forEach(c => c.checked = cb.checked);
  kemaskiniBlukBar();
}

function togolSatu(id) {
  const idx = selStudents.indexOf(String(id));
  if (idx > -1) selStudents.splice(idx, 1); else selStudents.push(String(id));
  const cbs    = document.querySelectorAll('.stu-cb');
  const selAll = document.getElementById('selectAll');
  selAll.checked       = selStudents.length === cbs.length && cbs.length > 0;
  selAll.indeterminate = selStudents.length > 0 && selStudents.length < cbs.length;
  kemaskiniBlukBar();
}

function kemaskiniBlukBar() {
  setTxt('bulkCount', selStudents.length + ' dipilih');
  document.getElementById('bulkBar').className = 'bulk-bar' + (selStudents.length > 0 ? ' visible' : '');
}

function showBulkExtra() {
  const act = document.getElementById('bulkAct').value;
  document.getElementById('bulkStatus').style.display = act === 'update_status' ? 'inline-block' : 'none';
  document.getElementById('bulkKelas').style.display  = act === 'change_class'  ? 'inline-block' : 'none';
}

function batalBulk() {
  selStudents = [];
  document.querySelectorAll('.stu-cb').forEach(c => c.checked = false);
  const sa = document.getElementById('selectAll');
  sa.checked = false; sa.indeterminate = false;
  kemaskiniBlukBar();
}

function gunakanBulk() {
  const act = document.getElementById('bulkAct').value;
  if (!act || !selStudents.length) { notif('Sila pilih tindakan dan pelajar','warning'); return; }
  if (act === 'delete' && !confirm(`Padam ${selStudents.length} pelajar terpilih? Tindakan ini tidak boleh dibatalkan.`)) return;
  const fd = new FormData();
  selStudents.forEach(id => fd.append('student_ids[]', id));
  let url = '';
  if (act === 'delete') {
    url = '?action=bulk_delete';
  } else if (act === 'update_status') {
    url = '?action=bulk_update';
    fd.append('bulk_action', 'update_status');
    fd.append('new_status', document.getElementById('bulkStatus').value);
  } else if (act === 'change_class') {
    url = '?action=bulk_update';
    fd.append('bulk_action', 'change_class');
    fd.append('new_kelas', document.getElementById('bulkKelas').value);
  }
  loading(true);
  fetch(url, {method:'POST', body:fd})
    .then(() => { loading(false); location.reload(); })
    .catch(() => { loading(false); notif('Ralat sistem','error'); });
}

// ── TAMBAH / EDIT PELAJAR ──────────────────────────────────────────────────────
function bukaTambah() {
  setTxt('modalTitle', '<i class="fas fa-user-plus" style="color:var(--primary)"></i> Tambah Pelajar Baru');
  document.getElementById('formPelajar').action = '?action=add';
  document.getElementById('formPelajar').reset();
  document.getElementById('f_edit_id').value = '';
  document.getElementById('btnSimpan').innerHTML = '<i class="fas fa-save"></i> Simpan Pelajar';
  document.getElementById('btnSimpan').disabled = false;
  sembunyiErrorIC();
  bukaModal('modalPelajar');
}

function bukaTambahKelas(kid, knama) {
  bukaTambah();
  // Pre-select the class
  const sel = document.getElementById('f_kelas');
  for (let i = 0; i < sel.options.length; i++) {
    if (Number(sel.options[i].value) === kid) { sel.selectedIndex = i; break; }
  }
  setTxt('modalTitle', `<i class="fas fa-user-plus" style="color:var(--primary)"></i> Tambah ke Kelas ${knama}`);
}

function editPelajar(id) {
  const p = allStudents.find(s => s.id == id);
  if (!p) { notif('Pelajar tidak ditemui','error'); return; }
  setTxt('modalTitle', '<i class="fas fa-edit" style="color:var(--warning)"></i> Edit Pelajar');
  document.getElementById('formPelajar').action = `?action=edit&id=${id}`;
  document.getElementById('f_nama').value    = p.nama   || '';
  document.getElementById('f_ic').value      = p.no_kp  || '';
  document.getElementById('f_edit_id').value = p.id;
  setSelectVal('f_jantina', p.jantina);
  setSelectVal('f_kelas',   p.id_kelas);
  const sVal = (p.status === '1' || p.status === 'aktif') ? 'aktif'
             : (p.status === '2' || p.status === 'tamat') ? 'tamat' : 'tidak aktif';
  setSelectVal('f_status', sVal);
  document.getElementById('btnSimpan').innerHTML = '<i class="fas fa-save"></i> Kemaskini Pelajar';
  document.getElementById('btnSimpan').disabled = false;
  sembunyiErrorIC();
  bukaModal('modalPelajar');
}

function padamPelajar(id, nama) {
  if (!confirm(`Padam pelajar: ${nama}?\n\nTindakan ini tidak boleh dibatalkan.`)) return;
  loading(true);
  fetch(`?ajax=delete_student&student_id=${id}`)
    .then(r => r.json())
    .then(d => {
      loading(false);
      if (d.success) {
        allStudents  = allStudents.filter(p  => p.id  != id);
        filtStudents = filtStudents.filter(p => p.id != id);
        selStudents  = selStudents.filter(i  => i    != String(id));
        renderJadual(); renderPaging(); kemaskiniBlukBar();
        notif(d.message || 'Pelajar dipadam', 'success');
        // invalidate per-kelas cache
        Object.keys(kelasLoaded).forEach(k => { kelasLoaded[k] = false; });
      } else notif(d.message || 'Gagal memadam', 'error');
    })
    .catch(() => { loading(false); notif('Ralat sistem','error'); });
}

// ── LIHAT BUTIRAN ──────────────────────────────────────────────────────────────
function lihatPelajar(id) {
  const p = allStudents.find(s => s.id == id);
  if (!p) return;
  const [sc, st] = statusInfo(p.status);
  const jt = p.jantina === 'L' ? 'Lelaki' : p.jantina === 'P' ? 'Perempuan' : (p.jantina || '–');
  const ini = initials(p.nama);
  document.getElementById('butiranBody').innerHTML = `
    <div style="display:flex;align-items:center;gap:16px;margin-bottom:22px">
      <div style="width:56px;height:56px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;
                  display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:18px;flex-shrink:0">${ini}</div>
      <div>
        <div style="font-size:17px;font-weight:700;color:var(--dark)">${esc(p.nama||'')}</div>
        <div style="font-size:13px;color:var(--gray)">${esc(p.no_kp||'–')}</div>
      </div>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px">
      ${iBox('Kelas',   esc(p.kelas_nama||'–'))}
      ${iBox('Tahun',   p.tahun ? 'Tahun '+p.tahun : '–')}
      ${iBox('Jantina', jt)}
      ${iBox('Status',  `<span class="badge-status ${sc}">${st}</span>`)}
      ${iBox('No. KP',  esc(p.no_kp||'–'))}
    </div>
    <div style="display:flex;gap:10px">
      <button class="btn btn-primary btn-sm" onclick="tutupButiran();editPelajar(${p.id})">
        <i class="fas fa-edit"></i> Edit
      </button>
      <button class="btn btn-danger btn-sm" onclick="tutupButiran();padamPelajar(${p.id},'${esc(p.nama||'').replace(/'/g,"\\\\'")}')" >
        <i class="fas fa-trash"></i> Padam
      </button>
    </div>`;
  bukaModal('modalButiran');
}
function iBox(lbl, val) {
  return `<div style="background:var(--light);padding:12px;border-radius:10px">
    <div style="font-size:11px;color:var(--gray);margin-bottom:4px">${lbl}</div>
    <div style="font-weight:600;color:var(--dark);font-size:13px">${val}</div></div>`;
}
function tutupButiran() { document.getElementById('modalButiran').classList.remove('active'); document.body.style.overflow=''; }

// ── IC VALIDATION ──────────────────────────────────────────────────────────────
function semakIC() {
  const ic  = document.getElementById('f_ic').value.trim();
  const eid = document.getElementById('f_edit_id').value;
  sembunyiErrorIC();
  if (!ic) return;
  if (!/^\d{6}-\d{2}-\d{4}$/.test(ic)) {
    document.getElementById('icFmt').style.display = 'block';
    document.getElementById('btnSimpan').disabled  = true;
    return;
  }
  let url = `?ajax=check_ic&no_ic=${encodeURIComponent(ic)}`;
  if (eid) url += `&exclude_id=${eid}`;
  fetch(url).then(r => r.json()).then(d => {
    document.getElementById('icError').style.display = d.exists ? 'block' : 'none';
    document.getElementById('btnSimpan').disabled    = d.exists;
  }).catch(() => { document.getElementById('btnSimpan').disabled = false; });
}
function sembunyiErrorIC() {
  document.getElementById('icError').style.display = 'none';
  document.getElementById('icFmt').style.display   = 'none';
  document.getElementById('btnSimpan').disabled    = false;
}

// ── PER-KELAS VIEW ─────────────────────────────────────────────────────────────
function toggleKelas(kid) {
  const body = document.getElementById(`body_${kid}`);
  const chv  = document.getElementById(`chv_${kid}`);
  if (body.classList.contains('open')) {
    body.classList.remove('open');
    chv.style.transform = 'rotate(0deg)';
  } else {
    body.classList.add('open');
    chv.style.transform = 'rotate(180deg)';
    if (!kelasLoaded[kid]) muatPelajarKelas(kid);
  }
}

function muatPelajarKelas(kid) {
  const ld   = document.getElementById(`ld_${kid}`);
  const list = document.getElementById(`list_${kid}`);
  if (ld) ld.style.display = 'block';
  fetch(`?ajax=get_students_by_class&kelas_id=${kid}`)
    .then(r => r.json())
    .then(d => {
      if (ld) ld.style.display = 'none';
      kelasLoaded[kid] = true;
      const cnt = d.total || 0;
      setTxt(`cnt_${kid}`, cnt);
      setTxt(`bdg_${kid}`, cnt);
      if (!d.success || !d.students || !d.students.length) {
        list.innerHTML = `<div class="kelas-empty">
          <i class="fas fa-user-slash" style="font-size:28px;color:#d1d5db;margin-bottom:8px;display:block"></i>
          Tiada pelajar dalam kelas ini</div>`;
        return;
      }
      list.innerHTML = d.students.map((p, idx) => {
        const ini = initials(p.nama);
        const jt  = p.jantina === 'L' ? 'L' : 'P';
        const [sc, st] = statusInfo(p.status);
        const nm  = esc(p.nama || '');
        return `<div class="kelas-row">
          <div class="kelas-stu-info">
            <div class="kelas-av">${ini}</div>
            <div>
              <div class="kelas-name">${nm}</div>
              <div class="kelas-ic">${esc(p.no_kp||'–')} &nbsp;|&nbsp; ${jt}</div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
            <span class="badge-status ${sc}" style="font-size:9px">${st}</span>
            <div style="display:flex;gap:5px">
              <button class="action-btn edit" style="padding:5px 9px"
                      onclick="switchTab('semua');editPelajarById(${p.id})"
                      title="Edit"><i class="fas fa-edit"></i></button>
              <button class="action-btn delete" style="padding:5px 9px"
                      onclick="padamDariKelas(${p.id},'${nm.replace(/'/g,"\\\\'")  }',${kid})"
                      title="Padam"><i class="fas fa-trash"></i></button>
            </div>
          </div>
        </div>`;
      }).join('');
    })
    .catch(() => {
      if (ld) ld.style.display = 'none';
      if (list) list.innerHTML = '<div class="kelas-empty">Ralat memuatkan pelajar</div>';
    });
}

function editPelajarById(id) {
  // Try to find in allStudents first; if not, reload
  if (allStudents.find(p => p.id == id)) {
    editPelajar(id);
  } else {
    loading(true);
    fetch('?ajax=get_students').then(r=>r.json()).then(d => {
      loading(false);
      if (d.success) { allStudents = d.students; editPelajar(id); }
    }).catch(() => loading(false));
  }
}

function padamDariKelas(id, nama, kid) {
  if (!confirm(`Padam pelajar: ${nama}?`)) return;
  loading(true);
  fetch(`?ajax=delete_student&student_id=${id}`)
    .then(r => r.json())
    .then(d => {
      loading(false);
      if (d.success) {
        notif(d.message || 'Pelajar dipadam','success');
        kelasLoaded[kid] = false;
        muatPelajarKelas(kid);
        allStudents  = allStudents.filter(p => p.id != id);
        filtStudents = filtStudents.filter(p => p.id != id);
        renderJadual(); renderPaging();
      } else notif(d.message || 'Gagal memadam','error');
    })
    .catch(() => { loading(false); notif('Ralat sistem','error'); });
}

// ── MODAL HELPERS ──────────────────────────────────────────────────────────────
function bukaModal(id) { document.getElementById(id).classList.add('active'); document.body.style.overflow='hidden'; }
function tutupModal()  { document.getElementById('modalPelajar').classList.remove('active'); document.body.style.overflow=''; }
function bukaImport()  { bukaModal('modalImport'); }
function tutupImport() { document.getElementById('modalImport').classList.remove('active'); document.body.style.overflow=''; }

// Close on backdrop click
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal')) {
    e.target.classList.remove('active');
    document.body.style.overflow = '';
  }
});

// ── SIDEBAR ────────────────────────────────────────────────────────────────────
function toggleSidebar() {
  document.getElementById('sidebar')?.classList.toggle('active');
  document.getElementById('sidebarOverlay')?.classList.toggle('active');
}
function closeSidebar() {
  document.getElementById('sidebar')?.classList.remove('active');
  document.getElementById('sidebarOverlay')?.classList.remove('active');
}

// ── UTILITIES ──────────────────────────────────────────────────────────────────
function loading(show) {
  const el = document.getElementById('loadingOverlay');
  if (show) el.classList.add('active');
  else      setTimeout(() => el.classList.remove('active'), 200);
}

function notif(msg, type='success') {
  document.querySelectorAll('.notif').forEach(n => n.remove());
  const icon = {success:'check-circle',error:'exclamation-circle',warning:'exclamation-triangle'}[type]||'info-circle';
  const el = Object.assign(document.createElement('div'), {className:`notif notif-${type}`});
  el.innerHTML = `<i class="fas fa-${icon}"></i><span>${msg}</span>`;
  document.body.appendChild(el);
  setTimeout(() => { el.style.animation='slideOutR .3s ease'; setTimeout(()=>el.remove(),300); }, 5000);
}

function esc(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function initials(name) { return (name||'?').split(' ').map(w=>w[0]||'').join('').substring(0,2).toUpperCase(); }
function setTxt(id, v)  { const el=document.getElementById(id); if(el) el.innerHTML=String(v); }
function setSelectVal(id, val) {
  const s = document.getElementById(id);
  if (!s) return;
  for (let i=0;i<s.options.length;i++) {
    if (String(s.options[i].value) === String(val)) { s.selectedIndex=i; return; }
  }
}
</script>
</body>
</html>
