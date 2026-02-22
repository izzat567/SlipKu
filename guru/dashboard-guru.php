<?php
ob_start();
session_start();
error_reporting(0);
ini_set('display_errors', '0');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['guru_id'])) {
    header('Location: login-guru.php'); exit();
}

$session_timeout = 1800;
if (isset($_SESSION['guru_login_time']) && (time() - $_SESSION['guru_login_time'] > $session_timeout)) {
    session_unset(); session_destroy(); session_start();
    header('Location: login-guru.php'); exit();
}
$_SESSION['guru_login_time'] = time();

require_once __DIR__ . '/../config/connect.php';
require_once __DIR__ . '/includes/db_functions.php';

$guru_id   = intval($_SESSION['guru_id']);
$guru_info = getGuruById($guru_id);

if (!$guru_info) {
    session_unset(); session_destroy(); session_start();
    header('Location: login-guru.php'); exit();
}

$_SESSION['guru_nama']  = $guru_info['nama'];
$_SESSION['guru_email'] = $guru_info['email'];

$current_page = 'dashboard-guru.php';

// Data dashboard
$kelas_list   = getKelasByGuru($guru_id);
$kelas_count  = count($kelas_list);
$subjek_list  = getSubjectsByGuru($guru_id);
$subjek_count = count($subjek_list);
$statistik    = getStatistikPelajar($guru_id);
$total_students = $statistik['total_pelajar'];
$exams_list   = getExamsByGuru($guru_id);

// Prestasi kelas (purata markah)
$prestasi_kelas = [];
try {
    $sql_p = "SELECT k.nama,
                     AVG(m.markah) AS purata_markah,
                     COUNT(m.id) AS jumlah_markah
              FROM markah m
              JOIN pelajar p ON m.id_pelajar = p.id
              JOIN kelas k ON p.id_kelas = k.id
              WHERE m.status = 'aktif'
                AND (k.id_guru = ? OR k.id IN (SELECT DISTINCT id_kelas FROM pengajar WHERE id_guru = ? AND status = 'aktif'))
              GROUP BY k.id, k.nama ORDER BY purata_markah DESC LIMIT 5";
    $st = $conn->prepare($sql_p);
    if ($st) {
        $st->bind_param("ii", $guru_id, $guru_id);
        $st->execute();
        $prestasi_kelas = $st->get_result()->fetch_all(MYSQLI_ASSOC);
        $st->close();
    }
} catch (Exception $e) { $prestasi_kelas = []; }

// Initials
$initials = '';
foreach (explode(' ', $guru_info['nama']) as $p)
    if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
$initials = substr($initials, 0, 2);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Guru — SlipKu</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{--primary:#4f46e5;--primary-dark:#4338ca;--primary-light:#eef2ff;--secondary:#7c3aed;--success:#10b981;--warning:#f59e0b;--danger:#ef4444;--dark-gray:#1f2937;--medium-gray:#6b7280;--light-gray:#f9fafb;--white:#ffffff;--radius:12px;--transition:all .3s ease}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}
body{background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);color:var(--dark-gray);min-height:100vh;overflow-x:hidden}
.header{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 30px}
.header-container{display:flex;align-items:center;justify-content:space-between;padding:15px 0}
.logo{display:flex;align-items:center;gap:12px;text-decoration:none}
.logo-icon{width:42px;height:42px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px}
.logo-text h1{font-size:22px;font-weight:800;color:var(--primary)}
.logo-text p{font-size:11px;color:var(--medium-gray)}
.menu-toggle{display:none;background:none;border:none;font-size:22px;color:var(--primary);cursor:pointer;padding:8px;border-radius:8px}
.user-profile{display:flex;align-items:center;gap:10px;padding:8px 14px;border-radius:10px;background:var(--light-gray);cursor:pointer}
.user-avatar{width:38px;height:38px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px}
.user-info h4{font-size:13px;font-weight:600}
.user-info p{font-size:11px;color:var(--medium-gray)}
.sidebar{background:var(--white);box-shadow:0 4px 20px rgba(0,0,0,.08);position:fixed;left:0;top:76px;bottom:0;width:255px;padding:25px 0;overflow-y:auto;z-index:900;transition:var(--transition)}
.sidebar-section{margin-bottom:25px;padding:0 20px}
.sidebar-title{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--medium-gray);margin-bottom:10px;font-weight:600}
.sidebar-item{display:flex;align-items:center;gap:12px;padding:11px 16px;color:var(--medium-gray);text-decoration:none;border-radius:10px;margin:3px 0;transition:var(--transition);font-size:14px}
.sidebar-item:hover{background:var(--light-gray);color:var(--primary);transform:translateX(4px)}
.sidebar-item.active{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 6px 20px rgba(79,70,229,.3)}
.sidebar-item.active i,.sidebar-item:hover i{color:inherit}
.sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:850}
.main-content{margin-left:255px;margin-top:76px;padding:30px;transition:var(--transition)}
.page-header{margin-bottom:28px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:15px}
.page-title h2{font-size:28px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:6px}
.page-title p{color:var(--medium-gray);font-size:14px}
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:22px;margin-bottom:28px}
.stat-card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07);display:flex;align-items:center;gap:18px;transition:var(--transition);border:2px solid transparent}
.stat-card:hover{border-color:var(--primary);transform:translateY(-4px)}
.stat-icon{width:62px;height:62px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;flex-shrink:0}
.si-blue{background:linear-gradient(135deg,#6366f1,#8b5cf6)}
.si-green{background:linear-gradient(135deg,#10b981,#34d399)}
.si-amber{background:linear-gradient(135deg,#f59e0b,#fbbf24)}
.si-red{background:linear-gradient(135deg,#ef4444,#f87171)}
.stat-info h3{font-size:12px;color:var(--medium-gray);margin-bottom:5px;font-weight:500;text-transform:uppercase}
.stat-value{font-size:30px;font-weight:800;color:var(--dark-gray);line-height:1;margin-bottom:4px}
.stat-sub{font-size:12px;color:var(--medium-gray)}
.actions-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:18px;margin-bottom:30px}
.action-card{background:var(--white);border-radius:var(--radius);padding:22px 18px;box-shadow:0 6px 20px rgba(0,0,0,.07);text-align:center;text-decoration:none;color:var(--dark-gray);border:2px solid transparent;transition:var(--transition)}
.action-card:hover{border-color:var(--primary);transform:translateY(-4px)}
.action-icon{width:56px;height:56px;background:linear-gradient(135deg,var(--primary),var(--secondary));border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;margin:0 auto 12px}
.action-card h4{font-size:13px;font-weight:600;margin-bottom:4px}
.action-card p{font-size:11px;color:var(--medium-gray)}
.dash-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:22px}
.dash-card{background:var(--white);border-radius:var(--radius);padding:22px;box-shadow:0 6px 20px rgba(0,0,0,.07)}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px}
.card-head h3{font-size:16px;font-weight:700}
.btn{padding:9px 18px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer;border:none;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:var(--transition)}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 6px 18px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-2px)}
.btn-ghost{background:var(--light-gray);color:var(--dark-gray)}
.btn-ghost:hover{background:#e5e7eb}
.exam-item{padding:13px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center}
.exam-item:last-child{border-bottom:none}
.exam-item h4{font-size:13px;font-weight:600;margin-bottom:3px}
.exam-item p{font-size:11px;color:var(--medium-gray)}
.badge-status{padding:4px 10px;border-radius:20px;font-size:10px;font-weight:600}
.bs-aktif{background:rgba(16,185,129,.1);color:var(--success)}
.bs-tamat{background:rgba(107,114,128,.1);color:var(--medium-gray)}
.class-item{display:flex;justify-content:space-between;align-items:center;padding:12px 14px;background:var(--light-gray);border-radius:10px;margin-bottom:8px}
.class-item:last-child{margin-bottom:0}
.class-item:hover{background:var(--primary-light)}
.empty-state{text-align:center;padding:35px 20px;color:var(--medium-gray)}
.empty-state i{font-size:44px;margin-bottom:12px;opacity:.3;display:block}
.empty-state p{font-size:13px}
@media(max-width:1024px){.sidebar{transform:translateX(-100%)}.sidebar.active{transform:translateX(0)}.main-content{margin-left:0}.menu-toggle{display:block}.sidebar-overlay.active{display:block}.dash-grid{grid-template-columns:1fr}}
@media(max-width:768px){.header{padding:0 16px}.main-content{padding:16px;margin-top:70px}.stats-grid{grid-template-columns:repeat(2,1fr)}.actions-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:480px){.stats-grid{grid-template-columns:1fr}.actions-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<header class="header">
  <div class="header-container">
    <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
    <a href="dashboard-guru.php" class="logo">
      <div class="logo-icon"><i class="fas fa-graduation-cap"></i></div>
      <div class="logo-text"><h1>SlipKu</h1><p>Sistem Pengurusan Markah</p></div>
    </a>
    <div class="user-profile">
      <div class="user-avatar"><?php echo htmlspecialchars($initials); ?></div>
      <div class="user-info">
        <h4><?php echo htmlspecialchars($guru_info['nama']); ?></h4>
        <p>Guru</p>
      </div>
    </div>
  </div>
</header>

<?php require_once __DIR__ . '/includes/sidebar.php'; ?>

<main class="main-content" id="mainContent">
  <div class="page-header">
    <div class="page-title">
      <h2>Selamat Datang, <?php echo htmlspecialchars(explode(' ', $guru_info['nama'])[0]); ?>! 👨‍🏫</h2>
      <p>Papan pemuka peribadi anda — data khas untuk anda sahaja</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon si-blue"><i class="fas fa-user-graduate"></i></div>
      <div class="stat-info">
        <h3>Jumlah Pelajar</h3>
        <div class="stat-value"><?php echo $total_students; ?></div>
        <div class="stat-sub">Dalam kelas anda</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon si-green"><i class="fas fa-book"></i></div>
      <div class="stat-info">
        <h3>Subjek Diajar</h3>
        <div class="stat-value"><?php echo $subjek_count; ?></div>
        <div class="stat-sub"><?php echo !empty($subjek_list) ? htmlspecialchars($subjek_list[0]['nama']) : 'Tiada subjek'; ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon si-amber"><i class="fas fa-chalkboard-teacher"></i></div>
      <div class="stat-info">
        <h3>Kelas Dikendalikan</h3>
        <div class="stat-value"><?php echo $kelas_count; ?></div>
        <div class="stat-sub"><?php echo !empty($kelas_list) ? htmlspecialchars($kelas_list[0]['nama']) : 'Tiada kelas'; ?></div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon si-red"><i class="fas fa-file-alt"></i></div>
      <div class="stat-info">
        <h3>Peperiksaan Aktif</h3>
        <div class="stat-value"><?php echo count($exams_list); ?></div>
        <div class="stat-sub">Dalam rekod sistem</div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="actions-grid">
    <a href="modules/kelas-saya.php" class="action-card">
      <div class="action-icon"><i class="fas fa-chalkboard-teacher"></i></div>
      <h4>Kelas Saya</h4><p>Urus kelas anda</p>
    </a>
    <a href="modules/pelajar-saya.php" class="action-card">
      <div class="action-icon"><i class="fas fa-user-graduate"></i></div>
      <h4>Pelajar Saya</h4><p>Urus senarai pelajar</p>
    </a>
    <a href="modules/subjek-saya.php" class="action-card">
      <div class="action-icon"><i class="fas fa-book"></i></div>
      <h4>Subjek Saya</h4><p>Lihat subjek anda</p>
    </a>
    <a href="modules/tambah-markah.php" class="action-card">
      <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
      <h4>Tambah Markah</h4><p>Masukkan markah pelajar</p>
    </a>
  </div>

  <!-- Info sections -->
  <div class="dash-grid">
    <div class="dash-card">
      <div class="card-head">
        <h3><i class="fas fa-file-alt" style="color:var(--primary);margin-right:8px"></i>Peperiksaan Terkini</h3>
        <a href="modules/tambah-markah.php" class="btn btn-ghost"><i class="fas fa-arrow-right"></i> Semua</a>
      </div>
      <?php if (!empty($exams_list)): ?>
        <?php foreach (array_slice($exams_list, 0, 5) as $e): ?>
        <div class="exam-item">
          <div>
            <h4><?php echo htmlspecialchars($e['nama_peperiksaan']); ?></h4>
            <p><?php echo htmlspecialchars($e['nama_subjek']); ?> • <?php echo $e['tarikh_mula'] ? date('d M Y', strtotime($e['tarikh_mula'])) : '-'; ?></p>
          </div>
          <span class="badge-status bs-aktif">Aktif</span>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state"><i class="fas fa-file-alt"></i><p>Tiada peperiksaan dalam sistem</p></div>
      <?php endif; ?>
    </div>

    <div class="dash-card">
      <div class="card-head">
        <h3><i class="fas fa-chart-bar" style="color:var(--secondary);margin-right:8px"></i>Prestasi Kelas</h3>
      </div>
      <?php if (!empty($prestasi_kelas)): ?>
        <?php foreach ($prestasi_kelas as $cl): ?>
          <?php $purata = round($cl['purata_markah'], 1); $clr = $purata >= 75 ? 'var(--success)' : ($purata >= 50 ? 'var(--warning)' : 'var(--danger)'); ?>
        <div class="class-item">
          <div><div style="font-weight:600;font-size:14px"><?php echo htmlspecialchars($cl['nama']); ?></div><small style="color:var(--medium-gray)"><?php echo $cl['jumlah_markah']; ?> rekod markah</small></div>
          <div style="font-size:22px;font-weight:800;color:<?php echo $clr; ?>"><?php echo $purata; ?>%</div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state"><i class="fas fa-chart-bar"></i><p>Belum ada markah direkodkan</p></div>
      <?php endif; ?>
    </div>
  </div>
</main>

<script>
const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const overlay    = document.getElementById('sidebarOverlay');
const main       = document.getElementById('mainContent');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});
overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});
</script>
</body>
</html>
