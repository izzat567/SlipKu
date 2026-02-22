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
$current_page = 'subjek-saya.php';
$guru_info    = getGuruById($guru_id);

$initials = '';
foreach (explode(' ', $_SESSION['guru_nama'] ?? $guru_info['nama'] ?? '') as $p)
    if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
$initials = substr($initials, 0, 2);

// AJAX: dapatkan pelajar & markah untuk subjek
if (isset($_GET['action']) && $_GET['action'] === 'get_students_marks') {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    $subject_id = intval($_GET['subject_id'] ?? 0);
    if (!$subject_id) { echo json_encode(['error' => 'ID subjek tidak sah']); exit(); }

    try {
        // Kelas yang diajar guru untuk subjek ini
        $st = $conn->prepare("SELECT k.id, k.nama FROM kelas k JOIN pengajar p ON k.id = p.id_kelas WHERE p.id_matapelajaran = ? AND p.id_guru = ? AND p.status = 'aktif'");
        $st->bind_param("ii", $subject_id, $guru_id); $st->execute();
        $kelas_rows = $st->get_result()->fetch_all(MYSQLI_ASSOC); $st->close();

        if (empty($kelas_rows)) {
            // Jika tiada kelas spesifik, ambil dari kelas guru
            $kelas_guru = getKelasByGuru($guru_id);
            $kelas_rows = array_slice($kelas_guru, 0, 3); // tunjuk 3 kelas pertama
        }

        $kelas_ids   = array_column($kelas_rows, 'id');
        $kelas_names = array_column($kelas_rows, 'nama', 'id');

        if (empty($kelas_ids)) { echo json_encode(['error' => 'Tiada kelas untuk subjek ini']); exit(); }

        $ph = implode(',', array_fill(0, count($kelas_ids), '?'));
        $st2 = $conn->prepare("SELECT id, nama, no_kp, id_kelas FROM pelajar WHERE id_kelas IN ($ph) AND status = 'aktif' ORDER BY nama");
        $st2->bind_param(str_repeat('i', count($kelas_ids)), ...$kelas_ids);
        $st2->execute();
        $pelajar = $st2->get_result()->fetch_all(MYSQLI_ASSOC); $st2->close();

        // Markah pelajar untuk subjek ini
        $marks = [];
        if (!empty($pelajar)) {
            $p_ids = array_column($pelajar, 'id');
            $ph2   = implode(',', array_fill(0, count($p_ids), '?'));
            $st3   = $conn->prepare("SELECT m.id_pelajar, m.markah, m.gred, pp.nama_peperiksaan AS ujian
                                     FROM markah m JOIN peperiksaan pp ON m.id_perperiksaan = pp.id
                                     WHERE m.id_pelajar IN ($ph2) AND pp.id_matapelajaran = ? AND m.status = 'aktif'");
            if ($st3) {
                $t3 = str_repeat('i', count($p_ids)) . 'i';
                $p3 = array_merge($p_ids, [$subject_id]);
                $st3->bind_param($t3, ...$p3); $st3->execute();
                while ($r = $st3->get_result()->fetch_assoc())
                    $marks[$r['id_pelajar']][] = ['ujian' => $r['ujian'], 'markah' => $r['markah'], 'gred' => $r['gred']];
                $st3->close();
            }
        }

        $out = array_map(function($p) use ($marks, $kelas_names) {
            $pm = $marks[$p['id']] ?? [];
            $avg = !empty($pm) ? round(array_sum(array_column($pm, 'markah')) / count($pm), 1) : null;
            return ['id' => $p['id'], 'nama' => $p['nama'], 'no_kp' => $p['no_kp'],
                    'kelas' => $kelas_names[$p['id_kelas']] ?? '-', 'markah_list' => $pm,
                    'purata' => $avg, 'gred_purata' => $avg !== null ? calculateGrade($avg) : '-'];
        }, $pelajar);

        echo json_encode(['students' => $out, 'kelas_list' => $kelas_rows]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

$subjects = getSubjectsByGuru($guru_id);
$exams    = getExamsByGuru($guru_id);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Subjek Saya — SlipKu</title>
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
.page-title{margin-bottom:25px}
.page-title h2{font-size:26px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:5px}
.page-title p{color:var(--medium-gray);font-size:14px}
.grid-subjek{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;margin-bottom:30px}
.subjek-card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07);cursor:pointer;border:2px solid transparent;transition:var(--transition)}
.subjek-card:hover{border-color:var(--primary);transform:translateY(-3px)}
.subjek-card.selected{border-color:var(--primary);background:var(--primary-light)}
.subjek-kod{display:inline-block;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:10px}
.subjek-nama{font-size:16px;font-weight:700;margin-bottom:6px}
.subjek-tahun{font-size:12px;color:var(--medium-gray)}
.card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07)}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
.card-head h3{font-size:16px;font-weight:700}
.table-wrap{overflow-x:auto}
table{width:100%;border-collapse:collapse}
th{background:var(--light-gray);padding:11px 14px;text-align:left;font-size:12px;font-weight:600;color:var(--medium-gray);text-transform:uppercase}
td{padding:12px 14px;border-bottom:1px solid #f0f0f0;font-size:13px;vertical-align:middle}
tr:hover td{background:#fafafa}
.gred-badge{padding:3px 9px;border-radius:6px;font-size:12px;font-weight:700}
.gA{background:#d1fae5;color:#065f46}.gB{background:#dbeafe;color:#1e40af}
.gC{background:#fef9c3;color:#713f12}.gD,.gE{background:#fee2e2;color:#7f1d1d}
.gF{background:#f3f4f6;color:#374151}
.btn{padding:9px 18px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer;border:none;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:var(--transition)}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 5px 15px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-1px)}
.loading{text-align:center;padding:30px;color:var(--medium-gray)}
.empty-state{text-align:center;padding:40px;color:var(--medium-gray)}
.empty-state i{font-size:48px;opacity:.2;display:block;margin-bottom:12px}
@media(max-width:1024px){.sidebar{transform:translateX(-100%)}.sidebar.active{transform:translateX(0)}.main-content{margin-left:0}.menu-toggle{display:block}.sidebar-overlay.active{display:block}}
@media(max-width:768px){.main-content{padding:16px;margin-top:70px}.header{padding:0 16px}.grid-subjek{grid-template-columns:1fr}}
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
    <h2><i class="fas fa-book"></i> Subjek Saya</h2>
    <p>Lihat subjek yang anda ajar dan prestasi pelajar</p>
  </div>

  <!-- Grid Subjek -->
  <?php if (empty($subjects)): ?>
    <div class="empty-state"><i class="fas fa-book"></i><p>Anda belum mempunyai subjek yang ditetapkan.<br>Hubungi pentadbir untuk penetapan subjek.</p></div>
  <?php else: ?>
  <div class="grid-subjek">
    <?php foreach ($subjects as $s): ?>
    <div class="subjek-card" onclick="selectSubject(<?php echo $s['id']; ?>, this)">
      <div class="subjek-kod"><?php echo htmlspecialchars($s['kod'] ?? substr($s['nama'], 0, 3)); ?></div>
      <div class="subjek-nama"><?php echo htmlspecialchars($s['nama']); ?></div>
      <div class="subjek-tahun"><i class="fas fa-calendar"></i> Tahun <?php echo $s['tahun'] ?? date('Y'); ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Panel Pelajar -->
  <div class="card" id="panelPelajar" style="display:none">
    <div class="card-head">
      <h3 id="panelTitle"><i class="fas fa-users" style="color:var(--primary)"></i> Senarai Pelajar</h3>
      <button class="btn btn-primary btn-sm" onclick="window.location='tambah-markah.php'"><i class="fas fa-plus-circle"></i> Tambah Markah</button>
    </div>
    <div id="panelContent">
      <div class="loading"><i class="fas fa-spinner fa-spin"></i> Memuatkan...</div>
    </div>
  </div>
  <?php endif; ?>
</main>

<script>
let selectedCard = null;

function selectSubject(id, card) {
    if (selectedCard) selectedCard.classList.remove('selected');
    card.classList.add('selected');
    selectedCard = card;

    const panel = document.getElementById('panelPelajar');
    const content = document.getElementById('panelContent');
    const title = document.getElementById('panelTitle');
    title.innerHTML = `<i class="fas fa-users" style="color:var(--primary)"></i> Pelajar — ${card.querySelector('.subjek-nama').textContent}`;
    panel.style.display = 'block';
    content.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Memuatkan...</div>';

    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch(`?action=get_students_marks&subject_id=${id}`)
        .then(r => r.json())
        .then(d => {
            if (d.error) { content.innerHTML = `<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>${d.error}</p></div>`; return; }
            if (!d.students.length) { content.innerHTML = '<div class="empty-state"><i class="fas fa-users"></i><p>Tiada pelajar dalam kelas ini</p></div>'; return; }
            content.innerHTML = `<div class="table-wrap"><table>
              <thead><tr><th>#</th><th>Nama</th><th>No. IC</th><th>Kelas</th><th>Ujian</th><th>Purata</th><th>Gred</th></tr></thead>
              <tbody>${d.students.map((s,i)=>`<tr>
                <td>${i+1}</td>
                <td><strong>${esc(s.nama)}</strong></td>
                <td style="font-family:monospace;font-size:12px">${esc(s.no_kp)}</td>
                <td>${esc(s.kelas)}</td>
                <td>${s.markah_list.length ? s.markah_list.map(m=>`<div style="font-size:12px">${esc(m.ujian)}: <strong>${m.markah}</strong></div>`).join('') : '<span style="color:var(--medium-gray);font-size:12px">Tiada markah</span>'}</td>
                <td>${s.purata !== null ? `<strong>${s.purata}</strong>` : '-'}</td>
                <td>${s.gred_purata !== '-' ? `<span class="gred-badge g${s.gred_purata[0]}">${s.gred_purata}</span>` : '-'}</td>
              </tr>`).join('')}</tbody>
            </table></div>`;
        })
        .catch(() => { content.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Ralat sambungan</p></div>'; });
}

function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const overlay    = document.getElementById('sidebarOverlay');
menuToggle.addEventListener('click', () => { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); });
</script>
</body>
</html>
