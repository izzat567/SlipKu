<?php
// includes/sidebar.php untuk Guru

// Pastikan $current_page wujud sebelum digunakan
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}

// Statistik untuk guru
$total_students = 85;
$total_subjects = 4;
$total_classes = 3;
$pending_exams = 3;
$pending_assignments = 12;
?>

<aside class="sidebar" id="sidebar">
    
    <!-- Menu Utama Guru -->
    <div class="sidebar-section">
        <div class="sidebar-title">Menu Utama</div>
        <a href="../dashboard-guru.php" class="sidebar-item <?php echo ($current_page == 'dashboard-guru.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="../modules/kelas-saya.php" class="sidebar-item <?php echo ($current_page == 'kelas-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Kelas Saya
            <span class="badge"><?php echo $total_classes; ?></span>
        </a>
        <a href="../modules/pelajar-saya.php" class="sidebar-item <?php echo ($current_page == 'pelajar-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>
            Pelajar Saya
            <span class="badge"><?php echo $total_students; ?></span>
        </a>
        <a href="../modules/subjek-saya.php" class="sidebar-item <?php echo ($current_page == 'subjek-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Subjek Saya
            <span class="badge"><?php echo $total_subjects; ?></span>
        </a>
    </div>

    <!-- Peperiksaan & Penilaian -->
    <div class="sidebar-section">
        <div class="sidebar-title">Peperiksaan & Penilaian</div>
        <a href="../modules/tambah-markah.php" class="sidebar-item <?php echo ($current_page == 'tambah-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            Tambah Markah
        </a>
        <a href="../modules/kemaskini-markah.php" class="sidebar-item <?php echo ($current_page == 'kemaskini-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-edit"></i>
            Kemaskini Markah
        </a>
        <a href="../modules/semak-markah.php" class="sidebar-item <?php echo ($current_page == 'semak-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-search"></i>
            Semak Markah
        </a>
        <a href="../modules/laporan-prestasi.php" class="sidebar-item <?php echo ($current_page == 'laporan-prestasi.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            Laporan Prestasi
        </a>
    </div>

    <!-- Pengurusan -->
    <div class="sidebar-section">
        <div class="sidebar-title">Pengurusan</div>
        <a href="../modules/jadual-ujian.php" class="sidebar-item <?php echo ($current_page == 'jadual-ujian.php') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i>
            Jadual Ujian
        </a>
        <a href="../modules/tugasan.php" class="sidebar-item <?php echo ($current_page == 'tugasan.php') ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i>
            Tugasan
            <span class="badge"><?php echo $pending_assignments; ?></span>
        </a>
        <a href="../modules/kehadiran.php" class="sidebar-item <?php echo ($current_page == 'kehadiran.php') ? 'active' : ''; ?>">
            <i class="fas fa-clipboard-check"></i>
            Kehadiran
        </a>
        <a href="../modules/komunikasi.php" class="sidebar-item <?php echo ($current_page == 'komunikasi.php') ? 'active' : ''; ?>">
            <i class="fas fa-comments"></i>
            Komunikasi Ibu Bapa
        </a>
    </div>

    <!-- Sistem -->
    <div class="sidebar-section">
        <div class="sidebar-title">Sistem</div>
        <a href="../modules/profil-saya.php" class="sidebar-item <?php echo ($current_page == 'profil-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-cog"></i>
            Profil Saya
        </a>
        <a href="../modules/tetapan.php" class="sidebar-item <?php echo ($current_page == 'tetapan.php') ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            Tetapan
        </a>
        <a href="../modules/bantuan.php" class="sidebar-item <?php echo ($current_page == 'bantuan.php') ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i>
            Bantuan
        </a>
        <a href="../logout.php" class="sidebar-item" style="color: #ef4444;">
            <i class="fas fa-sign-out-alt"></i>
            Log Keluar
        </a>
    </div>
</aside>

<!-- Overlay untuk mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>