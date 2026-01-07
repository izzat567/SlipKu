<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Prestasi - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Filter Section */
        .filter-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .filter-select {
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Stats Overview */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card.purple::before {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, var(--success), #0da271);
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, var(--info), #2563eb);
        }

        .stat-card.orange::before {
            background: linear-gradient(90deg, var(--warning), #d97706);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stat-header h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .purple .stat-icon {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .green .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .blue .stat-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .orange .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }

        .chart-card:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Top Students */
        .top-students {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
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
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: var(--light-gray);
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 18px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tr:hover td {
            background: var(--primary-light);
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 15px;
        }

        .rank-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .rank-1 {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .rank-2 {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .rank-3 {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .rank-other {
            background: rgba(107, 114, 128, 0.1);
            color: var(--medium-gray);
        }

        .grade-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .grade-a {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .grade-b {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .grade-c {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .grade-d {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .progress-percentage {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 1s ease-in-out;
        }

        /* Subject Performance */
        .subject-performance {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .subject-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .subject-item {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 20px;
            transition: var(--transition);
        }

        .subject-item:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .subject-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .subject-name {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .subject-avg {
            font-weight: 700;
            color: var(--primary);
            font-size: 20px;
        }

        .subject-progress {
            margin-bottom: 10px;
        }

        .subject-stats {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--medium-gray);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--primary-light);
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--dark-gray);
        }

        .empty-state p {
            font-size: 14px;
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
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

        /* RESPONSIVE DESIGN */
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
            
            .charts-grid {
                grid-template-columns: 1fr;
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
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .top-students,
            .subject-performance {
                padding: 20px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 13px;
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
            
            .filter-section,
            .stat-card,
            .chart-card {
                padding: 15px;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 250px;
            }
            
            th, td {
                padding: 12px 8px;
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
                <a href="#" class="nav-item active">
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
            <a href="#" class="sidebar-item active">
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
            <a href="bantuan.html" class="sidebar-item">
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
                <h2>Analisis Prestasi 📊</h2>
                <p>Prestasi akademik dan analisis pencapaian pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemula()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="janaLaporan()">
                    <i class="fas fa-file-export"></i>
                    Jana Laporan
                </button>
                <button class="btn btn-info" onclick="cetakAnalisis()">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterTahun" onchange="updateCharts()">
                        <option value="">Semua Tahun</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6">Tahun 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select class="filter-select" id="filterKelas" onchange="updateCharts()">
                        <option value="">Semua Kelas</option>
                        <option value="A">Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Semester</label>
                    <select class="filter-select" id="filterSemester" onchange="updateCharts()">
                        <option value="1">Semester 1</option>
                        <option value="2" selected>Semester 2</option>
                        <option value="all">Kedua-dua Semester</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Mata Pelajaran</label>
                    <select class="filter-select" id="filterSubject" onchange="updateCharts()">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01">PJ & Kesihatan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-card purple">
                <div class="stat-header">
                    <h4>Purata Keseluruhan</h4>
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div class="stat-value" id="overallAverage">78.5%</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+2.3% dari semester lepas</span>
                </div>
            </div>
            
            <div class="stat-card green">
                <div class="stat-header">
                    <h4>Pelajar Cemerlang</h4>
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <div class="stat-value" id="excellentStudents">45</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5 pelajar</span>
                </div>
            </div>
            
            <div class="stat-card blue">
                <div class="stat-header">
                    <h4>Pelajar Perlukan Bimbingan</h4>
                    <div class="stat-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                </div>
                <div class="stat-value" id="needHelpStudents">12</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-3 pelajar</span>
                </div>
            </div>
            
            <div class="stat-card orange">
                <div class="stat-header">
                    <h4>Kadar Kehadiran</h4>
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="stat-value" id="attendanceRate">94.2%</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+1.5%</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Grade Distribution Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Taburan Gred Keseluruhan</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('gradeChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            <!-- Performance Trend Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Tren Prestasi Mengikut Bulan</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('trendChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Subject Comparison Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Perbandingan Mata Pelajaran</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('subjectChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>

            <!-- Class Performance Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Prestasi Mengikut Kelas</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('classChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Students -->
        <div class="top-students">
            <div class="section-header">
                <h3>10 Pelajar Terbaik</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaPelajar()">
                    <i class="fas fa-list"></i>
                    Lihat Semua
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>KEDUDUKAN</th>
                            <th>NAMA PELAJAR</th>
                            <th>TAHUN/KELAS</th>
                            <th>PURATA MARKAH</th>
                            <th>GRED</th>
                            <th>PRESTASI</th>
                        </tr>
                    </thead>
                    <tbody id="topStudentsTable">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subject Performance -->
        <div class="subject-performance">
            <div class="section-header">
                <h3>Prestasi Mengikut Mata Pelajaran</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaMataPelajaran()">
                    <i class="fas fa-book"></i>
                    Semua Mata Pelajaran
                </button>
            </div>
            <div class="subject-list" id="subjectList">
                <!-- Data akan dipenuhi oleh JavaScript -->
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const topStudentsTable = document.getElementById('topStudentsTable');
        const subjectList = document.getElementById('subjectList');

        // Chart instances
        let gradeChartInstance, trendChartInstance, subjectChartInstance, classChartInstance;
        let gradeChartType = 'doughnut', trendChartType = 'line', subjectChartType = 'bar', classChartType = 'bar';

        // Sample data for analysis
        const analysisData = {
            topStudents: [
                { id: 'P001', nama: 'AHMAD BIN ALI', tahun: '6', kelas: 'A', purata: 95.2, gred: 'A', prestasi: 95 },
                { id: 'P045', nama: 'SITI NOR AISYAH', tahun: '6', kelas: 'A', purata: 93.8, gred: 'A', prestasi: 94 },
                { id: 'P023', nama: 'MOHD AMIR BIN HASSAN', tahun: '5', kelas: 'B', purata: 92.5, gred: 'A', prestasi: 93 },
                { id: 'P067', nama: 'NURUL FATIMAH', tahun: '6', kelas: 'C', purata: 91.7, gred: 'A', prestasi: 92 },
                { id: 'P089', nama: 'WAN AHMAD BIN WAN', tahun: '5', kelas: 'A', purata: 89.3, gred: 'A', prestasi: 89 },
                { id: 'P034', nama: 'NOR HIDAYAH', tahun: '4', kelas: 'A', purata: 87.6, gred: 'B', prestasi: 88 },
                { id: 'P078', nama: 'ALI BIN KASSIM', tahun: '5', kelas: 'B', purata: 85.9, gred: 'B', prestasi: 86 },
                { id: 'P102', nama: 'ROHAYU BINTI RAHIM', tahun: '6', kelas: 'B', purata: 84.2, gred: 'B', prestasi: 84 },
                { id: 'P056', nama: 'FAIZ BIN FARID', tahun: '4', kelas: 'C', purata: 82.8, gred: 'B', prestasi: 83 },
                { id: 'P091', nama: 'AIN NABIHAH', tahun: '5', kelas: 'C', purata: 80.5, gred: 'B', prestasi: 81 }
            ],
            
            subjectPerformance: [
                { kod: 'MAT01', nama: 'Matematik', purata: 82.5, tinggi: 98, rendah: 65, pelajar: 45 },
                { kod: 'BAH01', nama: 'Bahasa Melayu', purata: 78.3, tinggi: 96, rendah: 60, pelajar: 45 },
                { kod: 'BI01', nama: 'Bahasa Inggeris', purata: 75.8, tinggi: 94, rendah: 55, pelajar: 45 },
                { kod: 'SNS01', nama: 'Sains', purata: 80.2, tinggi: 97, rendah: 62, pelajar: 45 },
                { kod: 'PJH01', nama: 'PJ & Kesihatan', purata: 88.7, tinggi: 100, rendah: 70, pelajar: 45 },
                { kod: 'PIS01', nama: 'Pendidikan Islam', purata: 85.4, tinggi: 99, rendah: 68, pelajar: 32 }
            ],
            
            gradeDistribution: {
                A: 45,  // 45 students
                B: 120, // 120 students
                C: 65,  // 65 students
                D: 15   // 15 students
            },
            
            monthlyTrend: {
                labels: ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun'],
                averages: [75.2, 76.8, 77.5, 78.3, 79.1, 78.5]
            },
            
            subjectComparison: {
                labels: ['Matematik', 'BM', 'BI', 'Sains', 'PJ & Kesihatan'],
                averages: [82.5, 78.3, 75.8, 80.2, 88.7]
            },
            
            classPerformance: {
                labels: ['Kelas A', 'Kelas B', 'Kelas C'],
                averages: [85.2, 78.6, 72.4]
            }
        };

        // Initialize page
        function initializePage() {
            // Populate top students table
            populateTopStudentsTable();
            
            // Populate subject performance list
            populateSubjectList();
            
            // Initialize charts
            initializeCharts();
            
            // Update stats
            updateStats();
        }

        // Populate top students table
        function populateTopStudentsTable() {
            topStudentsTable.innerHTML = analysisData.topStudents.map((student, index) => `
                <tr>
                    <td>
                        <span class="rank-badge rank-${index + 1}">
                            ${index + 1}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${student.nama.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${student.nama}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">ID: ${student.id}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--primary);">Tahun ${student.tahun}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">Kelas ${student.kelas}</div>
                    </td>
                    <td style="font-weight: 700; color: var(--primary);">${student.purata}%</td>
                    <td>
                        <span class="grade-badge grade-${student.gred.toLowerCase()}">
                            ${student.gred}
                        </span>
                    </td>
                    <td>
                        <div class="progress-percentage">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${student.prestasi}%"></div>
                            </div>
                            <span style="font-weight: 600; color: var(--dark-gray); min-width: 40px;">${student.prestasi}%</span>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Populate subject performance list
        function populateSubjectList() {
            subjectList.innerHTML = analysisData.subjectPerformance.map(subject => `
                <div class="subject-item">
                    <div class="subject-header">
                        <div class="subject-name">${subject.nama}</div>
                        <div class="subject-avg">${subject.purata}%</div>
                    </div>
                    <div class="subject-progress">
                        <div class="progress-percentage">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${subject.purata}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="subject-stats">
                        <span>Tertinggi: ${subject.tinggi}%</span>
                        <span>Terendah: ${subject.rendah}%</span>
                        <span>${subject.pelajar} pelajar</span>
                    </div>
                </div>
            `).join('');
        }

        // Initialize charts
        function initializeCharts() {
            // Grade Distribution Chart
            const gradeCtx = document.getElementById('gradeChart').getContext('2d');
            gradeChartInstance = new Chart(gradeCtx, {
                type: gradeChartType,
                data: {
                    labels: ['A', 'B', 'C', 'D'],
                    datasets: [{
                        data: [
                            analysisData.gradeDistribution.A,
                            analysisData.gradeDistribution.B,
                            analysisData.gradeDistribution.C,
                            analysisData.gradeDistribution.D
                        ],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} pelajar (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Performance Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            trendChartInstance = new Chart(trendCtx, {
                type: trendChartType,
                data: {
                    labels: analysisData.monthlyTrend.labels,
                    datasets: [{
                        label: 'Purata Markah (%)',
                        data: analysisData.monthlyTrend.averages,
                        borderColor: 'rgba(79, 70, 229, 1)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 90,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });

            // Subject Comparison Chart
            const subjectCtx = document.getElementById('subjectChart').getContext('2d');
            subjectChartInstance = new Chart(subjectCtx, {
                type: subjectChartType,
                data: {
                    labels: analysisData.subjectComparison.labels,
                    datasets: [{
                        label: 'Purata Markah (%)',
                        data: analysisData.subjectComparison.averages,
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 90,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Class Performance Chart
            const classCtx = document.getElementById('classChart').getContext('2d');
            classChartInstance = new Chart(classCtx, {
                type: classChartType,
                data: {
                    labels: analysisData.classPerformance.labels,
                    datasets: [{
                        label: 'Purata Kelas (%)',
                        data: analysisData.classPerformance.averages,
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(245, 158, 11, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 70,
                            max: 90,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Toggle chart type
        function toggleChartType(chartId) {
            switch(chartId) {
                case 'gradeChart':
                    gradeChartType = gradeChartType === 'doughnut' ? 'pie' : 'doughnut';
                    gradeChartInstance.destroy();
                    const gradeCtx = document.getElementById('gradeChart').getContext('2d');
                    gradeChartInstance = new Chart(gradeCtx, {
                        type: gradeChartType,
                        data: gradeChartInstance.data,
                        options: gradeChartInstance.options
                    });
                    break;
                    
                case 'trendChart':
                    trendChartType = trendChartType === 'line' ? 'bar' : 'line';
                    trendChartInstance.destroy();
                    const trendCtx = document.getElementById('trendChart').getContext('2d');
                    trendChartInstance = new Chart(trendCtx, {
                        type: trendChartType,
                        data: trendChartInstance.data,
                        options: trendChartInstance.options
                    });
                    break;
                    
                case 'subjectChart':
                    subjectChartType = subjectChartType === 'bar' ? 'radar' : 'bar';
                    subjectChartInstance.destroy();
                    const subjectCtx = document.getElementById('subjectChart').getContext('2d');
                    subjectChartInstance = new Chart(subjectCtx, {
                        type: subjectChartType,
                        data: subjectChartInstance.data,
                        options: subjectChartInstance.options
                    });
                    break;
                    
                case 'classChart':
                    classChartType = classChartType === 'bar' ? 'horizontalBar' : 'bar';
                    classChartInstance.destroy();
                    const classCtx = document.getElementById('classChart').getContext('2d');
                    classChartInstance = new Chart(classCtx, {
                        type: classChartType === 'horizontalBar' ? 'bar' : 'horizontalBar',
                        data: classChartInstance.data,
                        options: classChartInstance.options
                    });
                    break;
            }
        }

        // Update charts based on filters
        function updateCharts() {
            const tahun = document.getElementById('filterTahun').value;
            const kelas = document.getElementById('filterKelas').value;
            const semester = document.getElementById('filterSemester').value;
            const subject = document.getElementById('filterSubject').value;
            
            // In a real app, you would fetch new data based on filters
            // For demo, we'll just update the stats with random variations
            updateStats();
            
            // Show filter applied notification
            const filterCount = [tahun, kelas, semester, subject].filter(f => f).length;
            if (filterCount > 0) {
                alert(`Analisis dikemas kini dengan ${filterCount} penapis yang digunakan.`);
            }
        }

        // Update statistics
        function updateStats() {
            // Calculate random variations for demo
            const overallRandom = (Math.random() * 5) - 2.5; // -2.5 to +2.5
            const excellentRandom = Math.floor(Math.random() * 6) - 3; // -3 to +3
            const helpRandom = Math.floor(Math.random() * 4) - 2; // -2 to +2
            const attendanceRandom = (Math.random() * 3) - 1.5; // -1.5 to +1.5
            
            // Update stats with random variations
            document.getElementById('overallAverage').textContent = (78.5 + overallRandom).toFixed(1) + '%';
            document.getElementById('excellentStudents').textContent = Math.max(0, 45 + excellentRandom);
            document.getElementById('needHelpStudents').textContent = Math.max(0, 12 + helpRandom);
            document.getElementById('attendanceRate').textContent = (94.2 + attendanceRandom).toFixed(1) + '%';
            
            // Update trend arrows
            document.querySelectorAll('.stat-change').forEach(change => {
                if (change.classList.contains('positive')) {
                    change.querySelector('span').textContent = 
                        change.textContent.includes('pelajar') ? `+${Math.abs(excellentRandom)} pelajar` : 
                        change.textContent.includes('dari') ? `+${Math.abs(overallRandom).toFixed(1)}% dari semester lepas` :
                        `+${Math.abs(attendanceRandom).toFixed(1)}%`;
                } else if (change.classList.contains('negative')) {
                    change.querySelector('span').textContent = 
                        `-${Math.abs(helpRandom)} pelajar`;
                }
            });
        }

        // Action functions
        function muatSemula() {
            // Reset filters
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterKelas').value = '';
            document.getElementById('filterSemester').value = '2';
            document.getElementById('filterSubject').value = '';
            
            // Reinitialize charts and data
            updateCharts();
            alert('Data analisis dimuat semula dengan tetapan asal.');
        }

        function janaLaporan() {
            const tahun = document.getElementById('filterTahun').value || 'Semua';
            const kelas = document.getElementById('filterKelas').value || 'Semua';
            
            alert(`Laporan analisis untuk Tahun ${tahun}, Kelas ${kelas} sedang dijana...\n\nLaporan akan dimuat turun sebagai fail PDF.`);
            
            // Simulate report generation
            setTimeout(() => {
                alert('Laporan berjaya dijana! Fail sedang dimuat turun.');
            }, 1500);
        }

        function cetakAnalisis() {
            alert('Menyediakan halaman untuk cetakan...\n\nTekan Ctrl+P untuk mencetak analisis ini.');
            // In a real app, you might open a print-friendly version
        }

        function lihatSemuaPelajar() {
            alert('Akan dibawa ke halaman senarai penuh pelajar dengan prestasi terperinci.');
            // In a real app, redirect to detailed student performance page
        }

        function lihatSemuaMataPelajaran() {
            alert('Akan dibawa ke halaman prestasi terperinci untuk semua mata pelajaran.');
            // In a real app, redirect to detailed subject analysis page
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
            // Set up event listeners
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
            
            // Initialize page components
            initializePage();
            
            // Animate progress bars
            setTimeout(() => {
                document.querySelectorAll('.progress-fill').forEach(fill => {
                    const width = fill.style.width;
                    fill.style.width = '0%';
                    setTimeout(() => {
                        fill.style.width = width;
                    }, 100);
                });
            }, 500);
        });
    </script>
</body>
</html>