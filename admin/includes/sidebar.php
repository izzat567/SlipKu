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
    
    <!-- untuk paparan dashboard -->
    <div class="sidebar-section">
        <div class="sidebar-title">MENU UTAMA</div>
        <a href="./dashboard.php" class="sidebar-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Papan Pemuka
        </a>
    </div>

    <!-- untuk bahagian crud pelajar -->
    <div class="sidebar-section">
        <div class="sidebar-title">PELAJAR</div>
        <a href="./pengurusan-pelajar.php" class="sidebar-item <?php echo $current_page == 'pengurusan-pelajar.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>
            Pengurusan Pelajar
            <span class="badge"><?php echo $total_students; ?></span>
        </a>
        <a href="./tambah-pelajar.php" class="sidebar-item <?php echo $current_page == 'tambah-pelajar.php' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i>
            Tambah Pelajar
            <span class="badge"><?php echo $total_subjects; ?></span>
        </a>
        <!-- <a href="./../modules/kemasini-pelajar.php" class="sidebar-item <?php echo $current_page == 'kemaskini-pelajar.php' ? 'active' : ''; ?>">
            <i class="fas fa-edit"></i>
            Kemaskini Pelajar
        </a> -->
       
    </div>

    <!-- untuk section guru -->

    <div class="sidebar-section">
    <div class="sidebar-title">GURU</div>
        <a href="./pengurusan-guru.php" class="sidebar-item <?php echo $current_page == 'pengurusan-guru.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i>
            Pengurusan Guru
            <span class="badge"><?php echo $total_students; ?></span>
        </a>
        <a href="./tambah-guru.php" class="sidebar-item <?php echo $current_page == 'mata-pelajaran.php' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Tambah Guru
            <span class="badge"><?php echo $total_subjects; ?></span>
        </a>


        <form action="../backend/admin.php" method="post">
            <br>
            <button type="submit" name="logout" class="sidebar-item" style="color: var(--danger);width:100%">
                <i class="fas fa-sign-out-alt"></i>
                Log Keluar
            </button>
            <a href="logout.php" >
            </a>
        </form>

    </div>

   
</aside>
