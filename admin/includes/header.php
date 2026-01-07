<?php
// includes/header.php
?>
<!-- Header -->
<header class="header">
    <div class="header-container">
        <!-- Mobile Menu Toggle -->
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Logo -->
        <a href="dashboard.php" class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h1>SlipKu</h1>
                <p>Sistem Peperiksaan Digital</p>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="top-nav">
            <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                Papan Pemuka
            </a>
            <a href="pengurusan-pelajar.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pengurusan-pelajar.php' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                Pelajar
            </a>
            <a href="#" class="nav-item">
                <i class="fas fa-bell"></i>
                Pemberitahuan
                <span class="notification-badge">3</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="user-profile" id="userProfile">
            <div class="user-avatar"><?php echo isset($_SESSION['admin_name']) ? strtoupper(substr($_SESSION['admin_name'], 0, 2)) : 'GU'; ?></div>
            <div class="user-info">
                <h4><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Cikgu Admin'; ?></h4>
                <p><?php echo isset($_SESSION['admin_role']) ? ucfirst($_SESSION['admin_role']) : 'Pentadbir'; ?></p>
            </div>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>