<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors', '0');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['guru_id'])) {
    header('Location: ../login-guru.php'); exit();
}

require_once __DIR__ . '/../../config/connect.php';
require_once __DIR__ . '/../includes/db_functions.php';

$guru_id      = intval($_SESSION['guru_id']);
$current_page = 'pelajar-saya.php';
$guru_info    = getGuruById($guru_id);

$initials = '';
foreach (explode(' ', $_SESSION['guru_nama'] ?? $guru_info['nama'] ?? '') as $p)
    if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
$initials = substr($initials, 0, 2);

$kelas_guru = getKelasByGuru($guru_id);
$all_kelas  = getAllKelas();

// ── AJAX ──
if (isset($_GET['ajax'])) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    switch ($_GET['ajax']) {
        case 'get_students':
            $list  = getPelajarByGuru($guru_id, $_GET['search'] ?? '', $_GET['kelas'] ?? '', $_GET['status'] ?? '');
            $stats = getStatistikPelajar($guru_id);
            echo json_encode(['success' => true, 'students' => $list, 'statistics' => $stats]);
            break;
        case 'delete_student':
            $sid = intval($_GET['student_id'] ?? 0);
            echo json_encode($sid && padamPelajar($sid)
                ? ['success' => true,  'message' => 'Pelajar berjaya dipadam']
                : ['success' => false, 'message' => 'Gagal memadam pelajar']);
            break;
        case 'check_ic':
            echo json_encode(['exists' => checkStudentExists($_GET['no_ic'] ?? '', $_GET['exclude_id'] ?? null)]);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Tindakan tidak dikenali']);
    }
    exit();
}

// ── POST ──
$action     = $_GET['action']  ?? '';
$student_id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'add':
            if (empty($_POST['nama']) || empty($_POST['no_ic']) || empty($_POST['jantina']) || empty($_POST['id_kelas'])) {
                $_SESSION['err'] = 'Sila isi semua maklumat.';
            } elseif (checkStudentExists(trim($_POST['no_ic']))) {
                $_SESSION['err'] = 'No. Kad Pengenalan sudah wujud.';
            } else {
                $ok = tambahPelajar(['nama' => trim($_POST['nama']), 'no_ic' => trim($_POST['no_ic']), 'jantina' => $_POST['jantina'], 'id_kelas' => intval($_POST['id_kelas']), 'status' => $_POST['status'] ?? 'aktif']);
                $_SESSION[$ok ? 'ok' : 'err'] = $ok ? 'Pelajar berjaya ditambah!' : 'Gagal menambah pelajar.';
            }
            header('Location: pelajar-saya.php'); exit();
        case 'edit':
            if (!$student_id || empty($_POST['nama']) || empty($_POST['no_ic']) || empty($_POST['jantina']) || empty($_POST['id_kelas'])) {
                $_SESSION['err'] = 'Data tidak lengkap.';
            } elseif (checkStudentExists(trim($_POST['no_ic']), $student_id)) {
                $_SESSION['err'] = 'No. Kad Pengenalan sudah wujud.';
            } else {
                $ok = kemaskiniPelajar($student_id, ['nama' => trim($_POST['nama']), 'no_ic' => trim($_POST['no_ic']), 'jantina' => $_POST['jantina'], 'id_kelas' => intval($_POST['id_kelas']), 'status' => $_POST['status'] ?? 'aktif']);
                $_SESSION[$ok ? 'ok' : 'err'] = $ok ? 'Pelajar berjaya dikemaskini!' : 'Gagal mengemaskini.';
            }
            header('Location: pelajar-saya.php'); exit();
        case 'delete':
            $ok = $student_id && padamPelajar($student_id);
            $_SESSION[$ok ? 'ok' : 'err'] = $ok ? 'Pelajar berjaya dipadam!' : 'Gagal memadam.';
            header('Location: pelajar-saya.php'); exit();
        case 'bulk_delete':
            if (!empty($_POST['student_ids'])) {
                $n = 0; foreach ($_POST['student_ids'] as $sid) if (padamPelajar($sid)) $n++;
                $_SESSION[$n ? 'ok' : 'err'] = $n ? "Berjaya memadam $n pelajar!" : 'Gagal memadam.';
            } else { $_SESSION['err'] = 'Tiada pelajar dipilih.'; }
            header('Location: pelajar-saya.php'); exit();
    }
}

$pelajar_list = getPelajarByGuru($guru_id);
$statistik    = getStatistikPelajar($guru_id);

$success_msg = $_SESSION['ok']  ?? ''; unset($_SESSION['ok']);
$error_msg   = $_SESSION['err'] ?? ''; unset($_SESSION['err']);

// Preload pelajar untuk edit
$edit_student = $student_id && $action === 'edit_form' ? getPelajarById($student_id) : null;

// Filter dari URL (dari kelas-saya.php)
$kelas_filter_url = $_GET['kelas'] ?? '';
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
:root{--primary:#4f46e5;--primary-dark:#4338ca;--primary-light:#eef2ff;--secondary:#7c3aed;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;--dark-gray:#1f2937;--medium-gray:#6b7280;--light-gray:#f9fafb;--white:#ffffff;--radius:12px;--transition:all .3s ease}
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
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px}
.page-title h2{font-size:26px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:4px}
.page-title p{color:var(--medium-gray);font-size:13px}
.alert{padding:14px 18px;border-radius:10px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:14px}
.alert-success{background:rgba(16,185,129,.1);color:#065f46;border:1px solid rgba(16,185,129,.3)}
.alert-danger{background:rgba(239,68,68,.1);color:#7f1d1d;border:1px solid rgba(239,68,68,.3)}
.stats-bar{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:22px}
.stat-mini{background:var(--white);border-radius:10px;padding:16px 18px;box-shadow:0 4px 14px rgba(0,0,0,.06);display:flex;align-items:center;gap:12px}
.stat-mini-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;flex-shrink:0}
.si-blue{background:linear-gradient(135deg,#6366f1,#8b5cf6)}
.si-green{background:linear-gradient(135deg,#10b981,#34d399)}
.stat-mini-info h4{font-size:11px;color:var(--medium-gray);text-transform:uppercase;margin-bottom:2px}
.stat-mini-info span{font-size:22px;font-weight:800;color:var(--dark-gray)}
.filters{background:var(--white);border-radius:var(--radius);padding:18px 22px;box-shadow:0 4px 14px rgba(0,0,0,.06);margin-bottom:18px;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end}
.filter-group{display:flex;flex-direction:column;gap:5px;flex:1;min-width:160px}
.filter-group label{font-size:11px;font-weight:600;color:var(--medium-gray);text-transform:uppercase}
.filter-group input,.filter-group select{padding:9px 12px;border:2px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;transition:var(--transition)}
.filter-group input:focus,.filter-group select:focus{border-color:var(--primary)}
.btn{padding:9px 18px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer;border:none;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:var(--transition)}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 5px 15px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-1px)}
.btn-danger{background:var(--danger);color:#fff}
.btn-ghost{background:var(--light-gray);color:var(--dark-gray)}
.btn-sm{padding:6px 12px;font-size:12px}
.card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:20px}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{background:var(--light-gray);padding:11px 14px;text-align:left;font-size:12px;font-weight:600;color:var(--medium-gray);text-transform:uppercase;letter-spacing:.5px}
td{padding:13px 14px;border-bottom:1px solid #f0f0f0;font-size:13px;vertical-align:middle}
tr:hover td{background:#fafafa}
.badge{padding:4px 10px;border-radius:20px;font-size:11px;font-weight:600}
.badge-aktif{background:rgba(16,185,129,.1);color:var(--success)}
.badge-tidak{background:rgba(107,114,128,.1);color:var(--medium-gray)}
.badge-tamat{background:rgba(239,68,68,.1);color:var(--danger)}
.empty-state{text-align:center;padding:40px 20px;color:var(--medium-gray)}
.empty-state i{font-size:48px;opacity:.2;display:block;margin-bottom:12px}
/* Modal */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center}
.modal-overlay.active{display:flex}
.modal{background:var(--white);border-radius:16px;padding:28px;width:min(520px,94vw);max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.modal-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
.modal-head h3{font-size:18px;font-weight:700}
.modal-close{background:none;border:none;font-size:20px;cursor:pointer;color:var(--medium-gray);padding:4px}
.modal-close:hover{color:var(--danger)}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-size:12px;font-weight:600;margin-bottom:5px;color:var(--dark-gray)}
.form-group label .req{color:var(--danger)}
.form-control{width:100%;padding:10px 13px;border:2px solid #e5e7eb;border-radius:8px;font-size:13px;font-family:'Poppins',sans-serif;outline:none;transition:var(--transition)}
.form-control:focus{border-color:var(--primary)}
.modal-foot{display:flex;justify-content:flex-end;gap:10px;margin-top:10px}
@media(max-width:1024px){.sidebar{transform:translateX(-100%)}.sidebar.active{transform:translateX(0)}.main-content{margin-left:0}.menu-toggle{display:block}.sidebar-overlay.active{display:block}}
@media(max-width:768px){.main-content{padding:16px;margin-top:70px}.header{padding:0 16px}.form-row{grid-template-columns:1fr}}
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
  <div class="page-header">
    <div class="page-title">
      <h2><i class="fas fa-user-graduate"></i> Pelajar Saya</h2>
      <p>Urus senarai pelajar dalam kelas anda</p>
    </div>
    <button class="btn btn-primary" onclick="openAdd()"><i class="fas fa-plus"></i> Tambah Pelajar</button>
  </div>

  <?php if ($success_msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?></div><?php endif; ?>
  <?php if ($error_msg): ?><div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_msg); ?></div><?php endif; ?>

  <!-- Stats -->
  <div class="stats-bar">
    <div class="stat-mini">
      <div class="stat-mini-icon si-blue"><i class="fas fa-users"></i></div>
      <div class="stat-mini-info"><h4>Jumlah</h4><span id="statTotal"><?php echo $statistik['total_pelajar']; ?></span></div>
    </div>
    <div class="stat-mini">
      <div class="stat-mini-icon si-green"><i class="fas fa-user-check"></i></div>
      <div class="stat-mini-info"><h4>Aktif</h4><span id="statAktif"><?php echo $statistik['pelajar_aktif']; ?></span></div>
    </div>
  </div>

  <!-- Filters -->
  <div class="filters">
    <div class="filter-group">
      <label>Carian</label>
      <input type="text" id="searchInput" placeholder="Nama / No. IC..." value="<?php echo htmlspecialchars($kelas_filter_url ? '' : ''); ?>">
    </div>
    <div class="filter-group">
      <label>Kelas</label>
      <select id="kelasFilter">
        <option value="">Semua Kelas</option>
        <?php foreach ($kelas_guru as $k): ?>
        <option value="<?php echo htmlspecialchars($k['nama']); ?>" <?php echo ($kelas_filter_url === $k['nama']) ? 'selected' : ''; ?>>
          <?php echo htmlspecialchars($k['nama']); ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="filter-group">
      <label>Status</label>
      <select id="statusFilter">
        <option value="">Semua Status</option>
        <option value="aktif">Aktif</option>
        <option value="tidak aktif">Tidak Aktif</option>
        <option value="tamat">Tamat</option>
      </select>
    </div>
    <button class="btn btn-primary" onclick="loadStudents()"><i class="fas fa-search"></i> Tapis</button>
    <button class="btn btn-ghost" onclick="clearFilters()"><i class="fas fa-times"></i> Reset</button>
  </div>

  <!-- Bulk Actions -->
  <div id="bulkBar" style="display:none;background:var(--primary-light);padding:12px 18px;border-radius:10px;margin-bottom:14px;display:none;align-items:center;gap:12px;flex-wrap:wrap">
    <span id="selectedCount" style="font-size:13px;font-weight:600">0 dipilih</span>
    <form method="POST" action="?action=bulk_delete" id="bulkDeleteForm" onsubmit="return confirmBulkDelete()">
      <div id="bulkIdsContainer"></div>
      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Padam Dipilih</button>
    </form>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="table-wrap">
      <table id="studentsTable">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll" onchange="toggleAll(this)"></th>
            <th>#</th>
            <th>Nama</th>
            <th>No. IC</th>
            <th>Jantina</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Tindakan</th>
          </tr>
        </thead>
        <tbody id="studentsBody">
          <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--medium-gray)"><i class="fas fa-spinner fa-spin"></i> Memuatkan...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- Modal Tambah/Edit -->
<div class="modal-overlay" id="modalPelajar">
  <div class="modal">
    <div class="modal-head">
      <h3 id="modalTitle">Tambah Pelajar</h3>
      <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="formPelajar">
      <input type="hidden" name="_method" value="POST">
      <div class="form-row">
        <div class="form-group" style="grid-column:1/-1">
          <label>Nama Penuh <span class="req">*</span></label>
          <input type="text" name="nama" id="fNama" class="form-control" required placeholder="Nama lengkap pelajar">
        </div>
        <div class="form-group">
          <label>No. Kad Pengenalan <span class="req">*</span></label>
          <input type="text" name="no_ic" id="fNoIC" class="form-control" required placeholder="xxxxxx-xx-xxxx">
        </div>
        <div class="form-group">
          <label>Jantina <span class="req">*</span></label>
          <select name="jantina" id="fJantina" class="form-control" required>
            <option value="">— Pilih —</option>
            <option value="L">Lelaki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
        <div class="form-group">
          <label>Kelas <span class="req">*</span></label>
          <select name="id_kelas" id="fKelas" class="form-control" required>
            <option value="">— Pilih Kelas —</option>
            <?php foreach ($kelas_guru as $k): ?>
            <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nama']); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Status</label>
          <select name="status" id="fStatus" class="form-control">
            <option value="aktif">Aktif</option>
            <option value="tidak aktif">Tidak Aktif</option>
            <option value="tamat">Tamat</option>
          </select>
        </div>
      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-ghost" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save"></i> Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const KELAS_FILTER_URL = <?php echo json_encode($kelas_filter_url); ?>;

function loadStudents() {
    const search = document.getElementById('searchInput').value;
    const kelas  = document.getElementById('kelasFilter').value;
    const status = document.getElementById('statusFilter').value;
    const body   = document.getElementById('studentsBody');
    body.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:30px"><i class="fas fa-spinner fa-spin"></i></td></tr>';

    fetch(`?ajax=get_students&search=${encodeURIComponent(search)}&kelas=${encodeURIComponent(kelas)}&status=${encodeURIComponent(status)}`)
        .then(r => r.json())
        .then(d => {
            if (!d.success) { body.innerHTML = '<tr><td colspan="8" style="text-align:center;color:red">Ralat memuat data</td></tr>'; return; }
            document.getElementById('statTotal').textContent = d.statistics.total_pelajar;
            document.getElementById('statAktif').textContent = d.statistics.pelajar_aktif;

            if (!d.students.length) {
                body.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:35px;color:var(--medium-gray)"><i class="fas fa-user-slash" style="font-size:36px;opacity:.2;display:block;margin-bottom:10px"></i>Tiada pelajar dijumpai</td></tr>';
                return;
            }
            body.innerHTML = d.students.map((s, i) => `
              <tr>
                <td><input type="checkbox" class="row-check" value="${s.id}" onchange="updateBulk()"></td>
                <td>${i+1}</td>
                <td><strong>${esc(s.nama)}</strong></td>
                <td style="font-family:monospace;font-size:12px">${esc(s.no_kp)}</td>
                <td>${s.jantina === 'L' ? '<i class="fas fa-mars" style="color:#3b82f6"></i> Lelaki' : '<i class="fas fa-venus" style="color:#ec4899"></i> Perempuan'}</td>
                <td>${esc(s.kelas_nama || '-')}</td>
                <td><span class="badge ${s.status==='aktif'?'badge-aktif':s.status==='tamat'?'badge-tamat':'badge-tidak'}">${esc(s.status)}</span></td>
                <td>
                  <button class="btn btn-ghost btn-sm" onclick='openEdit(${JSON.stringify(s)})'><i class="fas fa-edit"></i></button>
                  <button class="btn btn-danger btn-sm" onclick="deletePelajar(${s.id},'${esc(s.nama)}')"><i class="fas fa-trash"></i></button>
                </td>
              </tr>`).join('');
        })
        .catch(() => { body.innerHTML = '<tr><td colspan="8" style="text-align:center;color:red">Ralat sambungan</td></tr>'; });
}

function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('kelasFilter').value = '';
    document.getElementById('statusFilter').value = '';
    loadStudents();
}

function openAdd() {
    document.getElementById('modalTitle').textContent = 'Tambah Pelajar Baru';
    document.getElementById('formPelajar').action = '?action=add';
    document.getElementById('fNama').value = '';
    document.getElementById('fNoIC').value = '';
    document.getElementById('fJantina').value = '';
    document.getElementById('fKelas').value = '';
    document.getElementById('fStatus').value = 'aktif';
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus"></i> Tambah';
    document.getElementById('modalPelajar').classList.add('active');
}

function openEdit(s) {
    document.getElementById('modalTitle').textContent = 'Kemaskini Pelajar';
    document.getElementById('formPelajar').action = '?action=edit&id=' + s.id;
    document.getElementById('fNama').value = s.nama;
    document.getElementById('fNoIC').value = s.no_kp;
    document.getElementById('fJantina').value = s.jantina;
    document.getElementById('fKelas').value = s.id_kelas;
    document.getElementById('fStatus').value = s.status;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save"></i> Kemaskini';
    document.getElementById('modalPelajar').classList.add('active');
}

function closeModal() {
    document.getElementById('modalPelajar').classList.remove('active');
}

function deletePelajar(id, nama) {
    if (!confirm(`Padam pelajar "${nama}"?`)) return;
    fetch(`?ajax=delete_student&student_id=${id}`)
        .then(r => r.json())
        .then(d => { if (d.success) loadStudents(); else alert(d.message); });
}

function toggleAll(cb) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = cb.checked);
    updateBulk();
}

function updateBulk() {
    const checks = document.querySelectorAll('.row-check:checked');
    const bar    = document.getElementById('bulkBar');
    document.getElementById('selectedCount').textContent = checks.length + ' dipilih';
    bar.style.display = checks.length ? 'flex' : 'none';
    const cont = document.getElementById('bulkIdsContainer');
    cont.innerHTML = [...checks].map(c => `<input type="hidden" name="student_ids[]" value="${c.value}">`).join('');
}

function confirmBulkDelete() {
    const n = document.querySelectorAll('.row-check:checked').length;
    return confirm(`Padam ${n} pelajar terpilih?`);
}

// Close modal on overlay click
document.getElementById('modalPelajar').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Load on page ready
document.addEventListener('DOMContentLoaded', () => {
    if (KELAS_FILTER_URL) document.getElementById('kelasFilter').value = KELAS_FILTER_URL;
    loadStudents();
});

// Sidebar
const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const overlay    = document.getElementById('sidebarOverlay');
menuToggle.addEventListener('click', () => { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); });
</script>
</body>
</html>
