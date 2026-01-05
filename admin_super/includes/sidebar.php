<?php
// includes/sidebar.php

// Pastikan $current_page wujud sebelum digunakan
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}

// Pastikan stats wujud sebelum digunakan
$total_students = isset($stats['total_students']) ? $stats['total_students'] : 0;
$total_subjects = isset($stats['total_subjects']) ? $stats['total_subjects'] : 0;
$active_exams = isset($stats['active_exams']) ? $stats['active_exams'] : 0;
?>

<!-- sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-title">Menu Utama</div>
        <a href="./../dashboard.php" class="sidebar-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Papan Pemuka
        </a>
        <a href="./../modules/pengurusan-pelajar.php" class="sidebar-item <?php echo $current_page == 'pengurusan-pelajar.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>
            Pengurusan Pelajar
            <span class="badge"><?php echo $total_students; ?></span>
        </a>
        <a href="./../modules/mata-pelajaran.php" class="sidebar-item <?php echo $current_page == 'mata-pelajaran.php' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Mata Pelajaran
            <span class="badge"><?php echo $total_subjects; ?></span>
        </a>
        <a href="./../modules/analisis-prestasi.php" class="sidebar-item <?php echo $current_page == 'analisis-prestasi.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-line"></i>
            Analisis Prestasi
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Peperiksaan</div>
        <a href="./../modules/tambah-markah.php" class="sidebar-item <?php echo $current_page == 'tambah-markah.php' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            Tambah Markah
        </a>
        <a href="./../modules/kemaskini-markah.php" class="sidebar-item <?php echo $current_page == 'kemaskini-markah.php' ? 'active' : ''; ?>">
            <i class="fas fa-edit"></i>
            Kemaskini Markah
        </a>
        <a href="./../modules/hasilkan-laporan.php" class="sidebar-item <?php echo $current_page == 'hasilkan-laporan.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-export"></i>
            Hasilkan Laporan
        </a>
        <a href="./../modules/semua-rekod.php" class="sidebar-item <?php echo $current_page == 'semua-rekod.php' ? 'active' : ''; ?>">
            <i class="fas fa-file-alt"></i>
            Semua Rekod
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Sistem</div>
        <a href="./../modules/kelas-tahun.php" class="sidebar-item <?php echo $current_page == 'kelas-tahun.php' ? 'active' : ''; ?>">
            <i class="fas fa-school"></i>
            Kelas & Tahun
        </a>
        <a href="./../modules/jadual-ujian.php" class="sidebar-item <?php echo $current_page == 'jadual-ujian.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i>
            Jadual Ujian
        </a>
        <a href="./../modules/bantuan.php" class="sidebar-item <?php echo $current_page == 'bantuan.php' ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i>
            Bantuan
        </a>
        <a href="logout.php" class="sidebar-item" style="color: var(--danger);">
            <i class="fas fa-sign-out-alt"></i>
            Log Keluar
        </a>
    </div>

   
</aside>
