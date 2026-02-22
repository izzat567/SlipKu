<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors', '0');

// Helper JSON output
function jOut(array $data): void {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_GET['ajax']) || isset($_POST['ajax'])) jOut(['success' => false, 'message' => 'Sesi tamat']);
    header('Location: ../login-guru.php'); exit();
}

require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../includes/db_functions.php';

$guru_id      = intval($_SESSION['guru_id']);
$current_page = 'tambah-markah.php';
$guru_info    = getGuruById($guru_id);

$initials = '';
foreach (explode(' ', $_SESSION['guru_nama'] ?? $guru_info['nama'] ?? '') as $p)
    if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
$initials = substr($initials, 0, 2);

// ═══════════════════ AJAX GET ═══════════════════
if (isset($_GET['ajax'])) {
    switch ($_GET['ajax']) {
        case 'get_students':
            $kelas_id = intval($_GET['class'] ?? 0);
            jOut(['success' => true, 'students' => $kelas_id ? getStudentsByClass($kelas_id) : []]);

        case 'get_marks':
            $pep_id   = intval($_GET['peperiksaan_id'] ?? 0);
            $kelas_id = intval($_GET['kelas_id'] ?? 0);
            $marks    = getMarksByGuru($guru_id, $pep_id, $kelas_id);
            jOut(['success' => true, 'marks' => $marks]);

        default:
            jOut(['success' => false, 'message' => 'Tindakan tidak dikenali']);
    }
}

// ═══════════════════ AJAX POST ═══════════════════
if (isset($_POST['ajax'])) {
    switch ($_POST['ajax']) {
        case 'add_single_mark':
            $data = [
                'id_pelajar'      => intval($_POST['id_pelajar'] ?? 0),
                'id_perperiksaan' => intval($_POST['id_peperiksaan'] ?? 0),
                'markah'          => intval($_POST['markah'] ?? 0),
                'catatan'         => trim($_POST['catatan'] ?? ''),
            ];
            jOut(addMark($data));

        case 'add_bulk_marks':
            $raw = json_decode($_POST['marks_data'] ?? '[]', true);
            if (!is_array($raw) || empty($raw)) jOut(['success' => false, 'message' => 'Tiada data markah']);
            $marks_data = array_map(fn($d) => [
                'id_pelajar'      => intval($d['id_pelajar'] ?? 0),
                'id_perperiksaan' => intval($d['id_peperiksaan'] ?? $d['id_perperiksaan'] ?? 0),
                'markah'          => intval($d['markah'] ?? 0),
                'catatan'         => trim($d['catatan'] ?? ''),
            ], $raw);
            jOut(addMultipleMarks($marks_data));

        case 'update_mark':
            $mark_id = intval($_POST['mark_id'] ?? 0);
            $data    = ['markah' => intval($_POST['markah'] ?? 0), 'catatan' => trim($_POST['catatan'] ?? '')];
            jOut($mark_id ? updateMark($mark_id, $data) : ['success' => false, 'message' => 'ID markah tidak sah']);

        default:
            jOut(['success' => false, 'message' => 'Tindakan tidak dikenali']);
    }
}

// ═══════════════════ PAGE DATA ═══════════════════
$classes  = getKelasByGuru($guru_id);
$subjects = getSubjectsByGuru($guru_id);
$exams    = getExamsByGuru($guru_id);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Markah — SlipKu</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--primary:#4f46e5;--primary-light:#eef2ff;--secondary:#7c3aed;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;--dark-gray:#1f2937;--medium-gray:#6b7280;--light-gray:#f9fafb;--white:#ffffff;--radius:12px;--transition:all .3s ease}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);color:var(--dark-gray);min-height:100vh}
.header{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 30px}
.header-container{display:flex;align-items:center;justify-content:space-between;padding:15px 0}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none}
.logo-icon{width:42px;height:42px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.logo-text h1{font-size:22px;font-weight:800;color:var(--primary)}
.logo-text p{font-size:11px;color:var(--medium-gray)}
.menu-toggle{display:none;background:none;border:none;font-size:22px;color:var(--primary);cursor:pointer;padding:8px;border-radius:8px}
.user-profile{display:flex;align-items:center;gap:10px;padding:8px 14px;border-radius:10px;background:var(--light-gray)}
.user-avatar{width:38px;height:38px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px}
.user-info h4{font-size:13px;font-weight:600}
.user-info p{font-size:11px;color:var(--medium-gray)}
.sidebar{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;left:0;top:76px;bottom:0;width:255px;padding:25px 0;overflow-y:auto;z-index:900;transition:var(--transition)}
.sidebar-section{margin-bottom:25px;padding:0 20px}
.sidebar-title{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--medium-gray);margin-bottom:10px;font-weight:600}
.sidebar-item{display:flex;align-items:center;gap:12px;padding:11px 16px;color:var(--medium-gray);text-decoration:none;border-radius:10px;margin:3px 0;transition:var(--transition);font-size:14px}
.sidebar-item:hover{background:var(--light-gray);color:var(--primary)}
.sidebar-item.active{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 6px 20px rgba(79,70,229,.3)}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:850}
.main-content{margin-left:255px;margin-top:76px;padding:30px}
.page-title{margin-bottom:22px}
.page-title h2{font-size:26px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:5px}
.page-title p{color:var(--medium-gray);font-size:14px}
/* Tabs */
.tab-bar{display:flex;gap:4px;background:var(--light-gray);padding:5px;border-radius:12px;margin-bottom:22px;width:fit-content}
.tab-btn{padding:9px 22px;border-radius:9px;font-size:14px;font-weight:600;border:none;cursor:pointer;font-family:'Poppins',sans-serif;background:transparent;color:var(--medium-gray);transition:var(--transition)}
.tab-btn.active{background:var(--white);color:var(--primary);box-shadow:0 2px 10px rgba(0,0,0,.1)}
.tab-panel{display:none}
.tab-panel.active{display:block}
/* Card */
.card{background:var(--white);border-radius:var(--radius);padding:24px;box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:20px}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:10px}
.card-head h3{font-size:16px;font-weight:700}
/* Form */
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-row-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;font-weight:600;margin-bottom:5px;color:var(--dark-gray);text-transform:uppercase;letter-spacing:.3px}
.form-group label .req{color:var(--danger)}
.form-control{width:100%;padding:10px 13px;border:2px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;transition:var(--transition)}
.form-control:focus{border-color:var(--primary)}
/* Buttons */
.btn{padding:9px 18px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer;border:none;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:var(--transition)}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 5px 15px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-1px)}
.btn-success{background:var(--success);color:#fff}
.btn-success:hover{background:#059669}
.btn-danger{background:var(--danger);color:#fff}
.btn-ghost{background:var(--light-gray);color:var(--dark-gray)}
.btn-sm{padding:6px 12px;font-size:12px}
/* Table */
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{background:var(--light-gray);padding:11px 14px;text-align:left;font-size:12px;font-weight:600;color:var(--medium-gray);text-transform:uppercase}
td{padding:10px 14px;border-bottom:1px solid #f0f0f0;font-size:13px;vertical-align:middle}
tr:hover td{background:#fafafa}
.mark-input{width:80px;padding:6px 10px;border:2px solid #e5e7eb;border-radius:7px;font-size:13px;text-align:center;font-family:'Poppins',sans-serif;outline:none}
.mark-input:focus{border-color:var(--primary)}
.catatan-input{width:140px;padding:6px 10px;border:2px solid #e5e7eb;border-radius:7px;font-size:12px;font-family:'Poppins',sans-serif;outline:none}
.catatan-input:focus{border-color:var(--primary)}
.gred-cell{font-weight:700;font-size:14px;text-align:center}
/* Alert / Toast */
.toast{position:fixed;bottom:24px;right:24px;padding:14px 20px;border-radius:10px;color:#fff;font-size:14px;font-weight:600;z-index:9999;display:flex;align-items:center;gap:10px;box-shadow:0 8px 25px rgba(0,0,0,.2);transform:translateY(80px);opacity:0;transition:all .3s ease}
.toast.show{transform:translateY(0);opacity:1}
.toast.success{background:var(--success)}
.toast.danger{background:var(--danger)}
.empty-state{text-align:center;padding:40px 20px;color:var(--medium-gray)}
.empty-state i{font-size:44px;opacity:.2;display:block;margin-bottom:12px}
.spinner{text-align:center;padding:30px;color:var(--medium-gray)}
@media(max-width:1024px){.sidebar{transform:translateX(-100%)}.sidebar.active{transform:translateX(0)}.main-content{margin-left:0}.menu-toggle{display:block}.sidebar-overlay.active{display:block}}
@media(max-width:768px){.main-content{padding:16px;margin-top:70px}.header{padding:0 16px}.form-row{grid-template-columns:1fr}.form-row-3{grid-template-columns:1fr}}
</style>
</head>
<body>
<header class="header">
  <div class="header-container">
    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    <a href="../dashboard-guru.php" class="logo">
      <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="logo-text"><h1>SlipKu</h1><p>Sistem Pengurusan Markah</p></div>
    </a>
    <div class="user-profile">
      <div class="user-avatar"><?php echo htmlspecialchars($initials); ?></div>
      <div class="user-info">
        <h4><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? ''); ?></h4>
        <p>Guru</p>
      </div>
    </div>
  </div>
</header>

<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="main-content" id="mainContent">
  <div class="page-title">
    <h2><i class="fas fa-plus-circle"></i> Tambah & Kemaskini Markah</h2>
    <p>Masukkan atau kemaskini markah peperiksaan pelajar</p>
  </div>

  <!-- Tabs -->
  <div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('tambah', this)"><i class="fas fa-plus"></i> Tambah Markah</button>
    <button class="tab-btn" onclick="switchTab('kemaskini', this)"><i class="fas fa-edit"></i> Kemaskini Markah</button>
  </div>

  <!-- ══ TAB TAMBAH ══ -->
  <div class="tab-panel active" id="tab-tambah">
    <div class="card">
      <div class="card-head">
        <h3><i class="fas fa-plus-circle" style="color:var(--primary)"></i> Tambah Markah Baru</h3>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Peperiksaan <span class="req">*</span></label>
          <select id="t_exam" class="form-control" onchange="onTExamChange()">
            <option value="">— Pilih Peperiksaan —</option>
            <?php foreach ($exams as $e): ?>
            <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nama_peperiksaan']); ?> — <?php echo htmlspecialchars($e['nama_subjek']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Kelas <span class="req">*</span></label>
          <select id="t_kelas" class="form-control" onchange="loadStudentsForTambah()">
            <option value="">— Pilih Kelas —</option>
            <?php foreach ($classes as $k): ?>
            <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nama']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div id="t_students_wrap" style="display:none">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th style="width:40px"><input type="checkbox" id="t_selectAll" onchange="toggleTambahAll(this)"></th>
                <th>#</th><th>Nama Pelajar</th><th>No. IC</th>
                <th>Markah (0–100) <span class="req">*</span></th>
                <th>Gred</th><th>Catatan</th>
              </tr>
            </thead>
            <tbody id="t_students_body"></tbody>
          </table>
        </div>
        <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
          <button class="btn btn-primary" onclick="submitBulkMarks()"><i class="fas fa-save"></i> Simpan Semua Markah</button>
          <button class="btn btn-ghost" onclick="clearTambah()"><i class="fas fa-times"></i> Bersih</button>
        </div>
      </div>
      <div id="t_empty" class="empty-state"><i class="fas fa-arrow-up"></i><p>Pilih peperiksaan dan kelas untuk mula</p></div>
    </div>
  </div>

  <!-- ══ TAB KEMASKINI ══ -->
  <div class="tab-panel" id="tab-kemaskini">
    <div class="card">
      <div class="card-head">
        <h3><i class="fas fa-edit" style="color:var(--secondary)"></i> Kemaskini Markah Sedia Ada</h3>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Tapis Peperiksaan</label>
          <select id="k_exam" class="form-control" onchange="loadMarks()">
            <option value="">Semua Peperiksaan</option>
            <?php foreach ($exams as $e): ?>
            <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nama_peperiksaan']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Tapis Kelas</label>
          <select id="k_kelas" class="form-control" onchange="loadMarks()">
            <option value="">Semua Kelas</option>
            <?php foreach ($classes as $k): ?>
            <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nama']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div id="k_content">
        <div class="empty-state"><i class="fas fa-filter"></i><p>Pilih peperiksaan atau kelas untuk muat markah</p></div>
      </div>
    </div>
  </div>
</main>

<!-- Toast -->
<div class="toast" id="toast"></div>

<script>
// ═══ DATA dari PHP ═══
const PHP_EXAMS   = <?php echo json_encode($exams); ?>;
const PHP_CLASSES = <?php echo json_encode($classes); ?>;

// ═══ TAB ═══
function switchTab(tab, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    btn.classList.add('active');
    if (tab === 'kemaskini') loadMarks();
}

// ═══ TOAST ═══
function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.className = 'toast ' + type;
    t.innerHTML = `<i class="fas fa-${type==='success'?'check-circle':'exclamation-circle'}"></i> ${msg}`;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function calcGrade(m) {
    m = parseInt(m);
    if (isNaN(m)) return '';
    if (m >= 90) return 'A+'; if (m >= 80) return 'A'; if (m >= 70) return 'B';
    if (m >= 60) return 'C';  if (m >= 50) return 'D'; if (m >= 40) return 'E';
    return 'F';
}

function gradeColor(g) {
    if (!g) return '';
    const c = {'A+':'#10b981','A':'#10b981','B':'#3b82f6','C':'#f59e0b','D':'#ef4444','E':'#dc2626','F':'#6b7280'};
    return c[g] || '';
}

// ═══ TAB TAMBAH ═══
function onTExamChange() { if (document.getElementById('t_kelas').value) loadStudentsForTambah(); }

function loadStudentsForTambah() {
    const kelasId = document.getElementById('t_kelas').value;
    const wrap    = document.getElementById('t_students_wrap');
    const empty   = document.getElementById('t_empty');
    const body    = document.getElementById('t_students_body');

    if (!kelasId) { wrap.style.display = 'none'; empty.style.display = 'block'; return; }

    wrap.style.display = 'none';
    empty.innerHTML = '<div style="text-align:center;padding:25px"><i class="fas fa-spinner fa-spin"></i></div>';
    empty.style.display = 'block';

    fetch(`?ajax=get_students&class=${kelasId}`)
        .then(r => r.json())
        .then(d => {
            if (!d.success || !d.students.length) {
                empty.innerHTML = '<div class="empty-state"><i class="fas fa-users"></i><p>Tiada pelajar aktif dalam kelas ini</p></div>';
                return;
            }
            body.innerHTML = d.students.map((s, i) => `
              <tr id="row_${s.id}">
                <td><input type="checkbox" class="t-chk" value="${s.id}" checked></td>
                <td>${i+1}</td>
                <td><strong>${esc(s.nama)}</strong></td>
                <td style="font-size:12px;font-family:monospace">${esc(s.no_kp)}</td>
                <td><input type="number" class="mark-input" id="m_${s.id}" min="0" max="100" placeholder="0–100" oninput="updateGrade(${s.id})"></td>
                <td class="gred-cell" id="g_${s.id}" style="color:var(--medium-gray)">—</td>
                <td><input type="text" class="catatan-input" id="c_${s.id}" placeholder="Catatan (pilihan)"></td>
              </tr>`).join('');
            empty.style.display = 'none';
            wrap.style.display  = 'block';
        })
        .catch(() => { empty.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Ralat memuat pelajar</p></div>'; });
}

function updateGrade(id) {
    const m = document.getElementById('m_' + id).value;
    const g = calcGrade(m);
    const el = document.getElementById('g_' + id);
    el.textContent = g || '—';
    el.style.color = gradeColor(g) || 'var(--medium-gray)';
}

function toggleTambahAll(cb) {
    document.querySelectorAll('.t-chk').forEach(c => c.checked = cb.checked);
}

function clearTambah() {
    document.getElementById('t_exam').value  = '';
    document.getElementById('t_kelas').value = '';
    document.getElementById('t_students_wrap').style.display = 'none';
    document.getElementById('t_empty').innerHTML = '<div class="empty-state"><i class="fas fa-arrow-up"></i><p>Pilih peperiksaan dan kelas untuk mula</p></div>';
    document.getElementById('t_empty').style.display = 'block';
}

function submitBulkMarks() {
    const examId  = document.getElementById('t_exam').value;
    const kelasId = document.getElementById('t_kelas').value;
    if (!examId)  { showToast('Sila pilih peperiksaan', 'danger'); return; }
    if (!kelasId) { showToast('Sila pilih kelas', 'danger'); return; }

    const marks = [];
    document.querySelectorAll('.t-chk:checked').forEach(chk => {
        const id = chk.value;
        const m  = document.getElementById('m_' + id)?.value;
        if (m === '' || m === undefined) return;
        marks.push({
            id_pelajar: id, id_peperiksaan: examId,
            markah: m, catatan: document.getElementById('c_' + id)?.value || ''
        });
    });

    if (!marks.length) { showToast('Tiada markah diisi', 'danger'); return; }

    const fd = new FormData();
    fd.append('ajax', 'add_bulk_marks');
    fd.append('marks_data', JSON.stringify(marks));

    fetch(location.href, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            showToast(d.message, d.success ? 'success' : 'danger');
            if (d.success) { clearTambah(); if (document.getElementById('tab-kemaskini').classList.contains('active')) loadMarks(); }
        })
        .catch(() => showToast('Ralat sambungan', 'danger'));
}

// ═══ TAB KEMASKINI ═══
function loadMarks() {
    const pepId   = document.getElementById('k_exam').value;
    const kelasId = document.getElementById('k_kelas').value;
    const content = document.getElementById('k_content');

    content.innerHTML = '<div class="spinner"><i class="fas fa-spinner fa-spin"></i> Memuatkan...</div>';

    fetch(`?ajax=get_marks&peperiksaan_id=${pepId}&kelas_id=${kelasId}`)
        .then(r => r.json())
        .then(d => {
            if (!d.success) { content.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Ralat memuat data</p></div>'; return; }
            if (!d.marks.length) { content.innerHTML = '<div class="empty-state"><i class="fas fa-search"></i><p>Tiada markah dijumpai</p></div>'; return; }
            content.innerHTML = `<div class="table-wrap"><table>
              <thead><tr><th>#</th><th>Pelajar</th><th>No. IC</th><th>Kelas</th><th>Peperiksaan</th><th>Subjek</th><th>Markah</th><th>Gred</th><th>Catatan</th><th>Tindakan</th></tr></thead>
              <tbody>${d.marks.map((m, i) => `<tr id="kr_${m.id}">
                <td>${i+1}</td>
                <td><strong>${esc(m.nama_pelajar)}</strong></td>
                <td style="font-size:11px;font-family:monospace">${esc(m.no_kp)}</td>
                <td>${esc(m.nama_kelas)}</td>
                <td style="font-size:12px">${esc(m.nama_peperiksaan)}</td>
                <td style="font-size:12px">${esc(m.nama_subjek)}</td>
                <td><input type="number" class="mark-input" id="km_${m.id}" value="${m.markah}" min="0" max="100" oninput="updateKGrade(${m.id})"></td>
                <td class="gred-cell" id="kg_${m.id}" style="color:${gradeColor(m.gred)||'var(--medium-gray)'}">${esc(m.gred||'—')}</td>
                <td><input type="text" class="catatan-input" id="kc_${m.id}" value="${esc(m.catatan||'')}"></td>
                <td><button class="btn btn-success btn-sm" onclick="saveMarkUpdate(${m.id})"><i class="fas fa-save"></i></button></td>
              </tr>`).join('')}</tbody>
            </table></div>
            <div style="margin-top:12px"><button class="btn btn-primary" onclick="saveAllUpdates(${JSON.stringify(d.marks.map(x=>x.id))})"><i class="fas fa-save"></i> Simpan Semua</button></div>`;
        })
        .catch(() => { content.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Ralat sambungan</p></div>'; });
}

function updateKGrade(id) {
    const m = document.getElementById('km_' + id)?.value;
    const g = calcGrade(m);
    const el = document.getElementById('kg_' + id);
    if (el) { el.textContent = g || '—'; el.style.color = gradeColor(g) || 'var(--medium-gray)'; }
}

function saveMarkUpdate(id) {
    const markah  = document.getElementById('km_' + id)?.value;
    const catatan = document.getElementById('kc_' + id)?.value || '';
    if (markah === '' || markah === undefined) { showToast('Sila masukkan markah', 'danger'); return; }

    const fd = new FormData();
    fd.append('ajax', 'update_mark');
    fd.append('mark_id', id);
    fd.append('markah', markah);
    fd.append('catatan', catatan);

    fetch(location.href, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => showToast(d.message, d.success ? 'success' : 'danger'))
        .catch(() => showToast('Ralat sambungan', 'danger'));
}

function saveAllUpdates(ids) {
    let count = 0;
    const promises = ids.map(id => {
        const markah = document.getElementById('km_' + id)?.value;
        if (markah === '' || markah === undefined) return Promise.resolve();
        count++;
        const fd = new FormData();
        fd.append('ajax', 'update_mark');
        fd.append('mark_id', id);
        fd.append('markah', markah);
        fd.append('catatan', document.getElementById('kc_' + id)?.value || '');
        return fetch(location.href, { method: 'POST', body: fd }).then(r => r.json());
    });
    Promise.all(promises).then(() => showToast(`${count} markah berjaya dikemaskini!`, 'success'));
}

// Sidebar
const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const overlay    = document.getElementById('sidebarOverlay');
menuToggle.addEventListener('click', () => { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); });
</script>
</body>
</html>
