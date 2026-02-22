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
$current_page = 'kelas-saya.php';
$guru_info    = getGuruById($guru_id);

// Initials
$initials = '';
foreach (explode(' ', $_SESSION['guru_nama'] ?? $guru_info['nama'] ?? '') as $p)
    if (!empty($p)) $initials .= strtoupper(substr($p, 0, 1));
$initials = substr($initials, 0, 2);

$success_msg = ''; $error_msg = '';

// ── POST: Tambah kelas ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'tambah_kelas') {
    $kelas_id = intval($_POST['kelas_id'] ?? 0);
    if (!$kelas_id) {
        $error_msg = 'Sila pilih kelas.';
    } else {
        $cek = $conn->prepare("SELECT id FROM pengajar WHERE id_guru = ? AND id_kelas = ? AND status = 'aktif'");
        $cek->bind_param("ii", $guru_id, $kelas_id); $cek->execute(); $cek->store_result();
        if ($cek->num_rows > 0) {
            $error_msg = 'Anda sudah ditugaskan ke kelas ini.';
        } else {
            $cek->close();
            // Kemaskini kelas.id_guru
            $u = $conn->prepare("UPDATE kelas SET id_guru = ? WHERE id = ?");
            $u->bind_param("ii", $guru_id, $kelas_id); $u->execute(); $u->close();
            // Tambah dalam pengajar
            $tahun = date('Y');
            $i = $conn->prepare("INSERT INTO pengajar (id_kelas, id_guru, tahun_akademik, status) VALUES (?,?,?,'aktif')");
            $i->bind_param("iis", $kelas_id, $guru_id, $tahun); $i->execute(); $i->close();
            $success_msg = 'Kelas berjaya ditambah!';
        }
        if ($cek) $cek->close();
    }
}

// ── POST: Buang kelas ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'buang_kelas') {
    $kelas_id = intval($_POST['kelas_id'] ?? 0);
    if ($kelas_id) {
        $u = $conn->prepare("UPDATE kelas SET id_guru = NULL WHERE id = ? AND id_guru = ?");
        $u->bind_param("ii", $kelas_id, $guru_id); $u->execute(); $u->close();
        $u2 = $conn->prepare("UPDATE pengajar SET status = 'tidak_aktif' WHERE id_guru = ? AND id_kelas = ?");
        $u2->bind_param("ii", $guru_id, $kelas_id); $u2->execute(); $u2->close();
        $success_msg = 'Kelas berjaya dikeluarkan.';
    }
}

$kelas_guru  = getKelasByGuru($guru_id);
$semua_kelas = getAllKelas();

// Kelas yang belum dimiliki guru ini
$kelas_guru_ids    = array_column($kelas_guru, 'id');
$kelas_available   = array_filter($semua_kelas, fn($k) => !in_array($k['id'], $kelas_guru_ids));
?>
<!DOCTYPE html>
<html lang="ms">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelas Saya — SlipKu</title>
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
.page-title{margin-bottom:25px}
.page-title h2{font-size:26px;font-weight:800;background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;background-clip:text;color:transparent;margin-bottom:5px}
.page-title p{color:var(--medium-gray);font-size:14px}
.alert{padding:14px 18px;border-radius:10px;margin-bottom:18px;display:flex;align-items:center;gap:10px;font-size:14px}
.alert-success{background:rgba(16,185,129,.1);color:#065f46;border:1px solid rgba(16,185,129,.3)}
.alert-danger{background:rgba(239,68,68,.1);color:#7f1d1d;border:1px solid rgba(239,68,68,.3)}
.card{background:var(--white);border-radius:var(--radius);padding:24px;box-shadow:0 6px 20px rgba(0,0,0,.07);margin-bottom:24px}
.card-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px}
.card-head h3{font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px}
.btn{padding:9px 18px;border-radius:9px;font-weight:600;font-size:13px;cursor:pointer;border:none;font-family:'Poppins',sans-serif;display:inline-flex;align-items:center;gap:8px;text-decoration:none;transition:var(--transition)}
.btn-primary{background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 6px 18px rgba(79,70,229,.3)}
.btn-primary:hover{transform:translateY(-1px)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{background:#dc2626}
.btn-ghost{background:var(--light-gray);color:var(--dark-gray)}
.grid-kelas{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:18px}
.kelas-card{background:var(--white);border:2px solid #e5e7eb;border-radius:var(--radius);padding:20px;transition:var(--transition)}
.kelas-card:hover{border-color:var(--primary);transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.1)}
.kelas-card-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:14px}
.kelas-nama{font-size:18px;font-weight:700;color:var(--dark-gray)}
.kelas-tahun{font-size:12px;color:var(--medium-gray);background:var(--primary-light);padding:3px 10px;border-radius:20px;color:var(--primary)}
.kelas-stats{display:flex;gap:15px;margin-bottom:14px}
.kelas-stat{font-size:12px;color:var(--medium-gray);display:flex;align-items:center;gap:5px}
.kelas-actions{display:flex;gap:8px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:13px;font-weight:600;margin-bottom:6px;color:var(--dark-gray)}
.form-select{width:100%;padding:10px 14px;border:2px solid #e5e7eb;border-radius:9px;font-size:14px;font-family:'Poppins',sans-serif;outline:none;transition:var(--transition);background:var(--white)}
.form-select:focus{border-color:var(--primary)}
.empty-state{text-align:center;padding:40px 20px;color:var(--medium-gray)}
.empty-state i{font-size:48px;opacity:.2;display:block;margin-bottom:12px}
.empty-state p{font-size:14px}
@media(max-width:1024px){.sidebar{transform:translateX(-100%)}.sidebar.active{transform:translateX(0)}.main-content{margin-left:0}.menu-toggle{display:block}.sidebar-overlay.active{display:block}}
@media(max-width:768px){.main-content{padding:16px;margin-top:70px}.header{padding:0 16px}}
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
        <h4><?php echo htmlspecialchars($_SESSION['guru_nama'] ?? $guru_info['nama'] ?? 'Guru'); ?></h4>
        <p>Guru</p>
      </div>
    </div>
  </div>
</header>

<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<main class="main-content" id="mainContent">
  <div class="page-title">
    <h2><i class="fas fa-chalkboard-teacher"></i> Kelas Saya</h2>
    <p>Urus kelas yang anda kendalikan</p>
  </div>

  <?php if ($success_msg): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_msg); ?></div><?php endif; ?>
  <?php if ($error_msg): ?><div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_msg); ?></div><?php endif; ?>

  <!-- Tambah Kelas -->
  <div class="card">
    <div class="card-head">
      <h3><i class="fas fa-plus-circle" style="color:var(--primary)"></i> Tambah Kelas</h3>
    </div>
    <form method="POST">
      <input type="hidden" name="action" value="tambah_kelas">
      <div class="form-group">
        <label>Pilih Kelas</label>
        <select name="kelas_id" class="form-select" required>
          <option value="">— Pilih kelas —</option>
          <?php foreach ($kelas_available as $k): ?>
          <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nama']); ?> (<?php echo $k['tahun']; ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kelas</button>
    </form>
  </div>

  <!-- Senarai Kelas -->
  <div class="card">
    <div class="card-head">
      <h3><i class="fas fa-list" style="color:var(--secondary)"></i> Kelas Saya (<?php echo count($kelas_guru); ?>)</h3>
    </div>
    <?php if (empty($kelas_guru)): ?>
      <div class="empty-state"><i class="fas fa-chalkboard-teacher"></i><p>Anda belum mempunyai kelas. Tambah kelas di atas.</p></div>
    <?php else: ?>
    <div class="grid-kelas">
      <?php foreach ($kelas_guru as $k):
        // Kira pelajar dalam kelas
        $pelajar_k = getStudentsByClass($k['id']);
        $jumlah_p  = count($pelajar_k);
      ?>
      <div class="kelas-card">
        <div class="kelas-card-header">
          <div class="kelas-nama"><?php echo htmlspecialchars($k['nama']); ?></div>
          <span class="kelas-tahun"><?php echo $k['tahun']; ?></span>
        </div>
        <div class="kelas-stats">
          <span class="kelas-stat"><i class="fas fa-users"></i> <?php echo $jumlah_p; ?> Pelajar</span>
        </div>
        <div class="kelas-actions">
          <a href="pelajar-saya.php?kelas=<?php echo urlencode($k['nama']); ?>" class="btn btn-ghost" style="flex:1;justify-content:center;font-size:12px"><i class="fas fa-users"></i> Pelajar</a>
          <form method="POST" style="display:inline" onsubmit="return confirm('Buang kelas ini dari senarai anda?')">
            <input type="hidden" name="action" value="buang_kelas">
            <input type="hidden" name="kelas_id" value="<?php echo $k['id']; ?>">
            <button type="submit" class="btn btn-danger" style="font-size:12px"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</main>

<script>
const menuToggle = document.getElementById('menuToggle');
const sidebar    = document.getElementById('sidebar');
const overlay    = document.getElementById('sidebarOverlay');
menuToggle.addEventListener('click', () => { sidebar.classList.toggle('active'); overlay.classList.toggle('active'); });
overlay.addEventListener('click', () => { sidebar.classList.remove('active'); overlay.classList.remove('active'); });
</script>
</body>
</html>
