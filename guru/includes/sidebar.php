<?php
// ============================================================
// includes/sidebar.php untuk Guru - Connected dengan Database
// ============================================================

if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}

// Tentukan base path bergantung lokasi fail pemanggil
$is_in_modules = (strpos($_SERVER['PHP_SELF'], '/modules/') !== false);
$base_path = $is_in_modules ? '../' : '';

// ============================================================
// AMBIL DATA SEBENAR DARI DATABASE
// ============================================================
$sidebar_kelas_count    = 0;
$sidebar_students_count = 0;
$sidebar_subjek_count   = 0;
$sidebar_unmarked       = 0;

$_db = isset($conn) ? $conn : (isset($database) ? $database : null);

if (isset($_SESSION['guru_id']) && $_db instanceof mysqli) {
    $sid = (int) $_SESSION['guru_id'];

    // Jumlah kelas
    try {
        $q = $_db->prepare("SELECT COUNT(DISTINCT k.id) as c FROM pengajar pj JOIN kelas k ON pj.id_kelas = k.id WHERE pj.id_guru = ? AND pj.status = 'aktif' AND k.status = 'aktif'");
        if ($q) { $q->bind_param("i", $sid); $q->execute(); $sidebar_kelas_count = (int) $q->get_result()->fetch_assoc()['c']; }
    } catch (Exception $e) {}

    // Jumlah pelajar
    try {
        $q = $_db->prepare("SELECT COUNT(DISTINCT pk.id_pelajar) as c FROM pengajar pj JOIN kelas k ON pj.id_kelas = k.id JOIN pendaftaran_kelas pk ON k.id = pk.id_kelas AND pk.status = 'aktif' WHERE pj.id_guru = ? AND pj.status = 'aktif' AND k.status = 'aktif'");
        if ($q) { $q->bind_param("i", $sid); $q->execute(); $sidebar_students_count = (int) $q->get_result()->fetch_assoc()['c']; }
    } catch (Exception $e) {}

    // Jumlah subjek
    try {
        $q = $_db->prepare("SELECT COUNT(DISTINCT pj.id_matapelajaran) as c FROM pengajar pj WHERE pj.id_guru = ? AND pj.status = 'aktif'");
        if ($q) { $q->bind_param("i", $sid); $q->execute(); $sidebar_subjek_count = (int) $q->get_result()->fetch_assoc()['c']; }
    } catch (Exception $e) {}

    // Ujian belum dinilai
    try {
        $q = $_db->prepare("SELECT COUNT(DISTINCT p.id) as c FROM peperiksaan p JOIN pengajar pj ON p.id_matapelajaran = pj.id_matapelajaran AND p.id_kelas = pj.id_kelas WHERE pj.id_guru = ? AND pj.status = 'aktif' AND p.status = 'aktif' AND NOT EXISTS (SELECT 1 FROM markah m WHERE m.id_peperiksaan = p.id AND m.markah IS NOT NULL)");
        if ($q) { $q->bind_param("i", $sid); $q->execute(); $sidebar_unmarked = (int) $q->get_result()->fetch_assoc()['c']; }
    } catch (Exception $e) {}
}

$sidebar_guru_nama = $_SESSION['guru_nama'] ?? 'Guru';
$sidebar_initials  = '';
foreach (explode(' ', $sidebar_guru_nama) as $part) {
    if (!empty($part)) $sidebar_initials .= strtoupper(substr($part, 0, 1));
}
$sidebar_initials = substr($sidebar_initials, 0, 2);
?>

<aside class="sidebar" id="sidebar">

    <div class="sidebar-profile">
        <div class="sidebar-avatar"><?php echo htmlspecialchars($sidebar_initials); ?></div>
        <div class="sidebar-user-info">
            <h4><?php echo htmlspecialchars($sidebar_guru_nama); ?></h4>
            <p><?php echo htmlspecialchars($_SESSION['guru_role'] ?? 'Guru'); ?></p>
        </div>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Menu Utama</div>
        <a href="<?php echo $base_path; ?>dashboard-guru.php" class="sidebar-item <?php echo ($current_page == 'dashboard-guru.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>Dashboard
        </a>
        <a href="<?php echo $base_path; ?>modules/kelas-saya.php" class="sidebar-item <?php echo ($current_page == 'kelas-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>Kelas Saya
            <?php if ($sidebar_kelas_count > 0): ?><span class="badge"><?php echo $sidebar_kelas_count; ?></span><?php endif; ?>
        </a>
        <a href="<?php echo $base_path; ?>modules/pelajar-saya.php" class="sidebar-item <?php echo ($current_page == 'pelajar-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>Pelajar Saya
            <?php if ($sidebar_students_count > 0): ?><span class="badge"><?php echo $sidebar_students_count; ?></span><?php endif; ?>
        </a>
        <a href="<?php echo $base_path; ?>modules/subjek-saya.php" class="sidebar-item <?php echo ($current_page == 'subjek-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>Subjek Saya
            <?php if ($sidebar_subjek_count > 0): ?><span class="badge"><?php echo $sidebar_subjek_count; ?></span><?php endif; ?>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Peperiksaan & Penilaian</div>
        <a href="<?php echo $base_path; ?>modules/tambah-markah.php" class="sidebar-item <?php echo ($current_page == 'tambah-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>Tambah Markah
        </a>
        <a href="<?php echo $base_path; ?>modules/kemasikini-markah.php" class="sidebar-item <?php echo ($current_page == 'kemasikini-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-edit"></i>Kemaskini Markah
        </a>
        <a href="<?php echo $base_path; ?>modules/semak-markah.php" class="sidebar-item <?php echo ($current_page == 'semak-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-search"></i>Semak Markah
            <?php if ($sidebar_unmarked > 0): ?><span class="badge"><?php echo $sidebar_unmarked; ?></span><?php endif; ?>
        </a>
        <a href="<?php echo $base_path; ?>modules/laporan-prestasi.php" class="sidebar-item <?php echo ($current_page == 'laporan-prestasi.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>Laporan Prestasi
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Pengurusan</div>
        <a href="<?php echo $base_path; ?>modules/jadual-ujian.php" class="sidebar-item <?php echo ($current_page == 'jadual-ujian.php') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i>Jadual Ujian
        </a>
        <a href="<?php echo $base_path; ?>modules/tugasan.php" class="sidebar-item <?php echo ($current_page == 'tugasan.php') ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i>Tugasan
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Sistem</div>
        <a href="<?php echo $base_path; ?>profil-saya.php" class="sidebar-item <?php echo ($current_page == 'profil-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-cog"></i>Profil Saya
        </a>
        <a href="<?php echo $base_path; ?>logout.php" class="sidebar-item" style="color: var(--danger);">
            <i class="fas fa-sign-out-alt"></i>Log Keluar
        </a>
    </div>
</aside>

<style>
.sidebar-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 25px 15px;
    border-bottom: 1px solid #f1f5f9;
    margin-bottom: 10px;
}
.sidebar-avatar {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, var(--primary, #4f46e5), var(--secondary, #7c3aed));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 15px;
    flex-shrink: 0;
}
.sidebar-user-info h4 {
    font-size: 13px;
    font-weight: 600;
    color: var(--dark-gray, #1f2937);
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}
.sidebar-user-info p {
    font-size: 11px;
    color: var(--medium-gray, #6b7280);
}
</style>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="this.style.display='none'; document.getElementById('sidebar').classList.remove('sidebar-active');"></div>
