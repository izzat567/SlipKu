<?php
// sidebar.php — Sidebar navigasi guru (hanya 5 modul)
// $current_page mesti ditetapkan dalam fail pemanggil
$current_page = $current_page ?? '';
?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-title">Menu Utama</div>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'dashboard-guru.php' : '../dashboard-guru.php'; ?>" class="sidebar-item <?php echo ($current_page === 'dashboard-guru.php') ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'modules/kelas-saya.php' : 'kelas-saya.php'; ?>" class="sidebar-item <?php echo ($current_page === 'kelas-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-teacher"></i> Kelas Saya
        </a>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'modules/pelajar-saya.php' : 'pelajar-saya.php'; ?>" class="sidebar-item <?php echo ($current_page === 'pelajar-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i> Pelajar Saya
        </a>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'modules/subjek-saya.php' : 'subjek-saya.php'; ?>" class="sidebar-item <?php echo ($current_page === 'subjek-saya.php') ? 'active' : ''; ?>">
            <i class="fas fa-book"></i> Subjek Saya
        </a>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'modules/tambah-markah.php' : 'tambah-markah.php'; ?>" class="sidebar-item <?php echo ($current_page === 'tambah-markah.php') ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i> Tambah Markah
        </a>
    </div>
    <div class="sidebar-section">
        <div class="sidebar-title">Sistem</div>
        <a href="<?php echo (strpos($current_page,'dashboard') !== false) ? 'logout.php' : '../logout.php'; ?>" class="sidebar-item" style="color: #ef4444;">
            <i class="fas fa-sign-out-alt"></i> Log Keluar
        </a>
    </div>
</aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
