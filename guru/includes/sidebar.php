<?php
// includes/sidebar.php
// File ini digunakan oleh semua halaman modules untuk sidebar yang konsisten

// Pastikan session sudah dimulakan di halaman utama
// $current_page perlu ditetapkan di halaman utama sebelum include file ini

// Jika $current_page tidak ditetapkan, gunakan nama fail semasa
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF']);
}
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-title">Menu Utama</div>
        <a href="../dashboard-guru.php" class="sidebar-item <?php echo ($current_page == 'dashboard-guru.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="kelas-saya.php" class="sidebar-item <?php echo ($current_page == 'kelas-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Kelas Saya
        </a>
        <a href="pelajar-saya.php" class="sidebar-item <?php echo ($current_page == 'pelajar-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>
            Pelajar Saya
        </a>
        <a href="subjek-saya.php" class="sidebar-item <?php echo ($current_page == 'subjek-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Subjek Saya
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Peperiksaan & Penilaian</div>
        <a href="tambah-markah.php" class="sidebar-item <?php echo ($current_page == 'tambah-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            Tambah Markah
        </a>
        <a href="kemaskini-markah.php" class="sidebar-item <?php echo ($current_page == 'kemaskini-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-search"></i>
            Kemaskini Markah
        </a>
        <a href="semak-markah.php" class="sidebar-item <?php echo ($current_page == 'semak-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-search"></i>
            Semak Markah
        </a>
        <a href="laporan-prestasi.php" class="sidebar-item <?php echo ($current_page == 'laporan-prestasi.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            Laporan Prestasi
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Sistem</div>
        <a href="profil-saya.php" class="sidebar-item <?php echo ($current_page == 'profil-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-cog"></i>
            Profil Saya
        </a>
        <a href="../logout.php" class="sidebar-item" style="color: var(--danger);">
            <i class="fas fa-sign-out-alt"></i>
            Log Keluar
        </a>
    </div>
</aside>

<script>
    // Untuk mobile: tutup sidebar apabila klik pada item
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                document.getElementById('sidebar').classList.remove('sidebar-active');
                document.getElementById('sidebarOverlay')?.classList.remove('active');
                document.getElementById('mainContent')?.classList.remove('full-width');
                document.body.style.overflow = '';
            }
        });
    });
</script>