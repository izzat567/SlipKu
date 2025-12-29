<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --black: #000000;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 20px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Mobile Menu Toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .menu-toggle:hover {
            background: var(--primary-light);
        }

        /* Header Styles */
        .header {
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0 30px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 22px;
        }

        .logo-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .logo-text p {
            font-size: 12px;
            color: var(--medium-gray);
            font-weight: 500;
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            left: 0;
            top: 85px;
            bottom: 0;
            width: 260px;
            padding: 30px 0;
            overflow-y: auto;
            z-index: 900;
            transition: var(--transition);
            transform: translateX(0);
        }

        .sidebar.sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-section {
            margin-bottom: 30px;
            padding: 0 25px;
        }

        .sidebar-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--medium-gray);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: var(--medium-gray);
            text-decoration: none;
            border-radius: 12px;
            margin: 5px 0;
            transition: var(--transition);
            position: relative;
        }

        .sidebar-item:hover {
            background: var(--light-gray);
            color: var(--primary);
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .sidebar-item.active i {
            color: white;
        }

        .sidebar-item i {
            width: 20px;
            font-size: 16px;
            color: var(--medium-gray);
        }

        .badge {
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: auto;
            min-width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 85px;
            padding: 30px;
            transition: var(--transition);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-title h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .page-title p {
            color: var(--medium-gray);
            font-size: 16px;
        }

        .page-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            font-family: 'Poppins', sans-serif;
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--dark-gray);
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #0da271;
            transform: translateY(-2px);
        }

        .btn-info {
            background: var(--info);
            color: white;
        }

        .btn-info:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Help Sections */
        .help-sections {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .help-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            height: 100%;
        }

        .help-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .help-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .help-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .help-card-title h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .help-card-title p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .help-card-list {
            list-style-type: none;
        }

        .help-card-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .help-card-list li:last-child {
            border-bottom: none;
        }

        .help-card-list i {
            color: var(--success);
            font-size: 14px;
        }

        .help-card-list a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
        }

        .help-card-list a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        /* FAQ Section */
        .faq-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-gray);
        }

        .faq-search {
            position: relative;
            width: 300px;
        }

        .faq-search input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .faq-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .faq-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
            font-size: 16px;
        }

        .faq-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .faq-item {
            background: var(--light-gray);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .faq-item.active {
            border-color: var(--primary);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.1);
        }

        .faq-question {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .faq-question:hover {
            background: rgba(79, 70, 229, 0.05);
        }

        .faq-question i {
            color: var(--primary);
            transition: var(--transition);
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: var(--transition);
        }

        .faq-item.active .faq-answer {
            padding: 0 20px 20px;
            max-height: 500px;
        }

        .faq-answer p {
            color: var(--medium-gray);
            line-height: 1.7;
        }

        .faq-answer ul {
            margin-left: 20px;
            color: var(--medium-gray);
            margin-top: 10px;
        }

        /* Contact Section */
        .contact-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            padding: 40px;
            color: white;
            margin-bottom: 40px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
        }

        .contact-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .contact-info h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .contact-info p {
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .contact-link {
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            font-weight: 500;
            transition: var(--transition);
        }

        .contact-link:hover {
            text-decoration: underline;
            transform: translateX(5px);
        }

        /* Video Tutorials */
        .videos-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 25px;
        }

        .video-card {
            background: var(--light-gray);
            border-radius: 15px;
            overflow: hidden;
            transition: var(--transition);
        }

        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .video-thumbnail {
            height: 180px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            position: relative;
        }

        .video-play-btn {
            position: absolute;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .video-play-btn:hover {
            background: white;
            transform: scale(1.1);
        }

        .video-info {
            padding: 20px;
        }

        .video-info h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--dark-gray);
        }

        .video-info p {
            font-size: 13px;
            color: var(--medium-gray);
            margin-bottom: 15px;
        }

        .video-duration {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--medium-gray);
            background: rgba(0, 0, 0, 0.05);
            padding: 5px 10px;
            border-radius: 20px;
        }

        /* Quick Links */
        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 30px;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: var(--light-gray);
            border-radius: 12px;
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .quick-link:hover {
            background: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* Top Navigation for Mobile */
        .top-nav {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--medium-gray);
            text-decoration: none;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 12px;
            transition: var(--transition);
            position: relative;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary);
        }

        .nav-item.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* User Profile */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 15px;
            border-radius: 12px;
            background: var(--light-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-profile:hover {
            background: var(--primary-light);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .user-info p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 899;
            display: none;
            backdrop-filter: blur(3px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.sidebar-active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .top-nav {
                display: none;
            }
            
            .user-profile .user-info {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 20px;
            }
            
            .header-container {
                padding: 15px 0;
            }
            
            .main-content {
                padding: 20px;
                margin-top: 75px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .help-sections {
                grid-template-columns: 1fr;
            }
            
            .faq-search {
                width: 100%;
            }
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .videos-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .header {
                padding: 0 15px;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .logo-text h1 {
                font-size: 20px;
            }
            
            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 13px;
            }
            
            .contact-section {
                padding: 25px;
            }
            
            .help-card, .faq-section, .videos-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo -->
            <a href="dashboard.html" class="logo">
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
                <a href="dashboard.html" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i>
                    Papan Pemuka
                </a>
                <a href="pengurusan-pelajar.html" class="nav-item">
                    <i class="fas fa-users"></i>
                    Pelajar
                </a>
                <a href="mata-pelajaran.html" class="nav-item">
                    <i class="fas fa-book"></i>
                    Mata Pelajaran
                </a>
                <a href="analisis-prestasi.html" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    Analisis Prestasi
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    Pemberitahuan
                    <span class="notification-badge">3</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">GU</div>
                <div class="user-info">
                    <h4>Cikgu Admin</h4>
                    <p>Pentadbir Sistem</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu Utama</div>
            <a href="dashboard.html" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                Papan Pemuka
            </a>
            <a href="pengurusan-pelajar.html" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pengurusan Pelajar
                <span class="badge">245</span>
            </a>
            <a href="mata-pelajaran.html" class="sidebar-item">
                <i class="fas fa-book"></i>
                Mata Pelajaran
                <span class="badge">12</span>
            </a>
            <a href="analisis-prestasi.html" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                Analisis Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan</div>
            <a href="tambah-markah.html" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="kemaskini-markah.html" class="sidebar-item">
                <i class="fas fa-edit"></i>
                Kemaskini Markah
            </a>
            <a href="hasilkan-laporan.html" class="sidebar-item">
                <i class="fas fa-file-export"></i>
                Hasilkan Laporan
            </a>
            <a href="semua-rekod.html" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                Semua Rekod
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Sistem</div>
            <a href="kelas-tahun.html" class="sidebar-item">
                <i class="fas fa-school"></i>
                Kelas & Tahun
                <span class="badge">12</span>
            </a>
            <a href="jadual-ujian.html" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                Jadual Ujian
            </a>
            <a href="#" class="sidebar-item active">
                <i class="fas fa-question-circle"></i>
                Bantuan
            </a>
            <a href="#" class="sidebar-item" style="color: var(--danger);">
                <i class="fas fa-sign-out-alt"></i>
                Log Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pusat Bantuan SlipKu ❓</h2>
                <p>Dapatkan bantuan dan jawapan untuk semua soalan anda</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaHalaman()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="hubungiSokongan()">
                    <i class="fas fa-headset"></i>
                    Hubungi Sokongan
                </button>
            </div>
        </div>

        <!-- Help Sections -->
        <div class="help-sections">
            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Panduan Pengguna</h3>
                        <p>Panduan lengkap untuk sistem SlipKu</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('pengenalan')">Pengenalan kepada SlipKu</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('dashboard')">Panduan Papan Pemuka</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('pelajar')">Pengurusan Pelajar</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('markah')">Tambah & Kemaskini Markah</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('jadual')">Jadual Ujian & Peperiksaan</a></li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Video Tutorial</h3>
                        <p>Panduan visual langkah demi langkah</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('pengenalan')">Pengenalan Sistem SlipKu</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('import-data')">Import Data Pelajar</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('jana-laporan')">Menjana Laporan Prestasi</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('urus-ujian')">Mengurus Jadual Ujian</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('analisis')">Analisis Prestasi Pelajar</a></li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Alatan & Sumber</h3>
                        <p>Sumber berguna untuk pentadbir</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('template-import')">Template Import Data</a></li>
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('manual-pengguna')">Manual Pengguna (PDF)</a></li>
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('template-laporan')">Template Laporan</a></li>
                    <li><i class="fas fa-calculator"></i> <a href="#" onclick="bukaKalkulator()">Kalkulator Gred & Purata</a></li>
                    <li><i class="fas fa-calendar-alt"></i> <a href="#" onclick="bukaKalendarAkademik()">Kalendar Akademik 2023/2024</a></li>
                </ul>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="section-header">
                <h3>Soalan Lazim (FAQ)</h3>
                <div class="faq-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="faqSearch" placeholder="Cari soalan..." onkeyup="cariFAQ()">
                </div>
            </div>
            
            <div class="faq-container" id="faqContainer">
                <!-- FAQ items will be loaded here -->
            </div>
        </div>

        <!-- Video Tutorials Section -->
        <div class="videos-section">
            <div class="section-header">
                <h3>Video Tutorial Terkini</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaVideo()">
                    <i class="fas fa-eye"></i>
                    Lihat Semua
                </button>
            </div>
            
            <div class="videos-grid" id="videosGrid">
                <!-- Video cards will be loaded here -->
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3 style="color: white; font-size: 24px; margin-bottom: 10px;">Hubungi Kami</h3>
            <p style="opacity: 0.9; margin-bottom: 20px;">Kami sentiasa sedia membantu anda. Hubungi kami melalui mana-mana saluran berikut:</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Hotline Sokongan</h4>
                        <p>03-1234 5678</p>
                        <p>Isnin - Jumaat: 9:00 pagi - 6:00 petang</p>
                        <a href="tel:0312345678" class="contact-link">
                            <i class="fas fa-phone-alt"></i> Hubungi Sekarang
                        </a>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Emel Sokongan</h4>
                        <p>sokongan@slipku.edu.my</p>
                        <p>Masa tindak balas: Dalam 24 jam</p>
                        <a href="mailto:sokongan@slipku.edu.my" class="contact-link">
                            <i class="fas fa-envelope"></i> Hantar Emel
                        </a>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Live Chat</h4>
                        <p>Chat langsung dengan ejen kami</p>
                        <p>Tersedia: 9:00 pagi - 5:00 petang</p>
                        <a href="#" class="contact-link" onclick="bukaLiveChat()">
                            <i class="fas fa-comment-dots"></i> Mulakan Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <a href="#" class="quick-link" onclick="laporkanMasalah()">
                <i class="fas fa-bug"></i>
                Laporkan Masalah
            </a>
            <a href="#" class="quick-link" onclick="cadangkanFungsi()">
                <i class="fas fa-lightbulb"></i>
                Cadangkan Fungsi Baru
            </a>
            <a href="#" class="quick-link" onclick="semakStatusSistem()">
                <i class="fas fa-server"></i>
                Semak Status Sistem
            </a>
            <a href="#" class="quick-link" onclick="aksesForum()">
                <i class="fas fa-users"></i>
                Akses Forum Komuniti
            </a>
            <a href="#" class="quick-link" onclick="aksesKb()">
                <i class="fas fa-database"></i>
                Pangkalan Pengetahuan
            </a>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const faqContainer = document.getElementById('faqContainer');
        const videosGrid = document.getElementById('videosGrid');

        // Sample FAQ Data
        const faqData = [
            {
                question: "Bagaimana cara menambah pelajar baru ke dalam sistem?",
                answer: "Untuk menambah pelajar baru, pergi ke halaman 'Pengurusan Pelajar' dan klik butang 'Tambah Pelajar'. Isi borang dengan maklumat pelajar yang diperlukan. Anda juga boleh mengimport data pelajar secara pukal menggunakan template Excel yang disediakan."
            },
            {
                question: "Bagaimana sistem mengira gred dan purata pelajar?",
                answer: "Sistem SlipKu menggunakan formula standard KPM untuk mengira gred. Anda boleh tetapkan skala gred anda sendiri melalui Tetapan Sistem. Purata dikira berdasarkan berat markah setiap peperiksaan yang ditetapkan."
            },
            {
                question: "Bolehkah saya jadualkan ujian untuk beberapa kelas sekaligus?",
                answer: "Ya, anda boleh. Di halaman 'Jadual Ujian', pilih 'Tambah Ujian' dan pilih semua kelas yang terlibat. Sistem akan menjana jadual untuk setiap kelas secara automatik."
            },
            {
                question: "Bagaimana cara mencetak slip keputusan peperiksaan?",
                answer: "Pergi ke halaman 'Hasilkan Laporan', pilih kelas dan peperiksaan, kemudian klik 'Jana Slip'. Sistem akan menghasilkan slip keputusan dalam format PDF yang boleh dicetak atau dimuat turun."
            },
            {
                question: "Adakah data saya selamat di dalam sistem SlipKu?",
                answer: "Ya, keselamatan data adalah keutamaan kami. Sistem menggunakan enkripsi SSL, sandaran data harian, dan akses terkawal berdasarkan peranan pengguna. Hanya pengguna yang dibenarkan sahaja boleh mengakses data."
            },
            {
                question: "Bagaimana jika saya terlupa kata laluan?",
                answer: "Di halaman log masuk, klik 'Lupa Kata Laluan'. Sistem akan menghantar pautan reset kata laluan ke emel anda yang didaftarkan. Jika masalah berterusan, hubungi sokongan teknikal."
            },
            {
                question: "Bolehkah saya mengubah format laporan?",
                answer: "Ya, anda boleh pilih antara beberapa template laporan yang tersedia. Untuk penyesuaian lanjut, hubungi pasukan sokongan untuk bantuan."
            },
            {
                question: "Berapa lama data peperiksaan disimpan dalam sistem?",
                answer: "Data peperiksaan disimpan selama 5 tahun secara automatik. Anda boleh memuat turun arkib data untuk simpanan luar jika diperlukan."
            }
        ];

        // Sample Video Data
        const videoData = [
            {
                title: "Pengenalan kepada SlipKu",
                description: "Ketahui ciri-ciri utama sistem pengurusan peperiksaan digital kami",
                duration: "4:32",
                id: "pengenalan"
            },
            {
                title: "Import Data Pelajar Secara Pukal",
                description: "Panduan lengkap untuk mengimport data pelajar menggunakan Excel",
                duration: "6:15",
                id: "import"
            },
            {
                title: "Menjana Laporan Prestasi Kelas",
                description: "Cara menghasilkan laporan analisis prestasi untuk keseluruhan kelas",
                duration: "7:48",
                id: "laporan"
            },
            {
                title: "Mengurus Jadual Ujian & Peperiksaan",
                description: "Panduan menyusun jadual peperiksaan untuk semua kelas",
                duration: "5:23",
                id: "jadual"
            }
        ];

        // Initialize page
        function initializePage() {
            // Load FAQ
            loadFAQ();
            
            // Load videos
            loadVideos();
            
            // Set up event listeners
            setupEventListeners();
        }

        // Load FAQ items
        function loadFAQ() {
            faqContainer.innerHTML = faqData.map((faq, index) => `
                <div class="faq-item" id="faq-${index}">
                    <div class="faq-question" onclick="toggleFAQ(${index})">
                        <span>${faq.question}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>${faq.answer}</p>
                    </div>
                </div>
            `).join('');
        }

        // Load video cards
        function loadVideos() {
            videosGrid.innerHTML = videoData.map(video => `
                <div class="video-card">
                    <div class="video-thumbnail">
                        <i class="fas fa-play-circle"></i>
                        <div class="video-play-btn" onclick="playVideo('${video.id}')">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-info">
                        <h4>${video.title}</h4>
                        <p>${video.description}</p>
                        <div class="video-duration">
                            <i class="fas fa-clock"></i>
                            ${video.duration}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Toggle FAQ item
        function toggleFAQ(index) {
            const faqItem = document.getElementById(`faq-${index}`);
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it was closed
            if (!isActive) {
                faqItem.classList.add('active');
            }
        }

        // Search FAQ
        function cariFAQ() {
            const searchTerm = document.getElementById('faqSearch').value.toLowerCase();
            
            faqData.forEach((faq, index) => {
                const faqItem = document.getElementById(`faq-${index}`);
                const question = faq.question.toLowerCase();
                const answer = faq.answer.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    faqItem.style.display = 'block';
                } else {
                    faqItem.style.display = 'none';
                }
            });
        }

        // Open user guide
        function bukaPanduan(guide) {
            alert(`Membuka panduan: ${guide}\n\nDalam versi sebenar, ini akan membuka PDF atau halaman panduan yang berkaitan.`);
            // In a real app, this would open the corresponding guide
        }

        // Watch video
        function tontonVideo(videoId) {
            alert(`Memainkan video tutorial: ${videoId}\n\nDalam versi sebenar, ini akan memainkan video yang dipilih.`);
            // In a real app, this would open a video player
        }

        // Download resource
        function muatTurun(resource) {
            alert(`Memuat turun: ${resource}\n\nDalam versi sebenar, ini akan memuat turun fail yang dipilih.`);
            // In a real app, this would download the resource
        }

        // Open calculator
        function bukaKalkulator() {
            alert("Membuka kalkulator gred dan purata...");
            // In a real app, this would open a calculator tool
        }

        // Open academic calendar
        function bukaKalendarAkademik() {
            alert("Membuka kalendar akademik 2023/2024...");
            // In a real app, this would open the academic calendar
        }

        // Play video
        function playVideo(videoId) {
            alert(`Memainkan video: ${videoId}\n\nDalam aplikasi sebenar, video akan dipapar dalam pemain video.`);
            // In a real app, this would play the selected video
        }

        // View all videos
        function lihatSemuaVideo() {
            alert("Membuka semua video tutorial...");
            // In a real app, this would navigate to all videos page
        }

        // Contact support
        function hubungiSokongan() {
            alert("Menghubungi pasukan sokongan...\n\nSila pilih saluran:\n1. Telefon: 03-1234 5678\n2. Emel: sokongan@slipku.edu.my\n3. Live Chat (9am-5pm)");
            // In a real app, this would open contact options
        }

        // Open live chat
        function bukaLiveChat() {
            alert("Membuka live chat dengan ejen sokongan...\n\nSila tunggu sebentar untuk dihubungkan.");
            // In a real app, this would open a chat window
        }

        // Report problem
        function laporkanMasalah() {
            const problem = prompt("Sila terangkan masalah yang dihadapi:");
            if (problem) {
                alert(`Terima kasih! Laporan anda telah dihantar:\n\n"${problem}"\n\nNo. Rujukan: SR-${Date.now().toString().slice(-6)}`);
            }
        }

        // Suggest feature
        function cadangkanFungsi() {
            const suggestion = prompt("Cadangkan fungsi baru untuk SlipKu:");
            if (suggestion) {
                alert(`Terima kasih atas cadangan anda!\n\n"${suggestion}"\n\nCadangan anda telah direkodkan.`);
            }
        }

        // Check system status
        function semakStatusSistem() {
            alert("Status Sistem SlipKu:\n\n✅ Semua sistem beroperasi normal\n✅ Server responsif\n✅ Tiada gangguan dilaporkan\n✅ Sandaran terakhir: 2 jam lalu\n\nSemua perkhidmatan berjalan lancar.");
        }

        // Access forum
        function aksesForum() {
            alert("Mengakses forum komuniti SlipKu...\n\nForum komuniti membolehkan anda berkongsi pengalaman dengan pengguna lain dan mendapatkan tips dari pakar.");
        }

        // Access knowledge base
        function aksesKb() {
            alert("Mengakses pangkalan pengetahuan SlipKu...\n\nPangkalan pengetahuan mengandungi artikel teknikal, panduan penyelesaian masalah, dan dokumentasi lengkap.");
        }

        // Reload page
        function muatSemulaHalaman() {
            location.reload();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Toggle sidebar
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
        }

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            sidebarOverlay.classList.toggle('active');
            mainContent.classList.toggle('full-width');
            document.body.style.overflow = sidebar.classList.contains('sidebar-active') ? 'hidden' : '';
        }

        // Close Sidebar on Mobile
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('sidebar-active');
                sidebarOverlay.classList.remove('active');
                mainContent.classList.remove('full-width');
                document.body.style.overflow = '';
            }
        }

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    </script>
</body>
</html>==========