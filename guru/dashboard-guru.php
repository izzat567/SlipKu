<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Guru - SlipKu</title>
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

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 20px;
            border: 2px solid transparent;
        }

        .stat-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .stat-icon.students {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .stat-icon.exams {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.subjects {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-icon.classes {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .stat-info h3 {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark-gray);
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-change {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Dashboard Sections */
        .dashboard-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
        }

        /* Recent Exams */
        .exam-list {
            list-style-type: none;
        }

        .exam-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .exam-item:hover {
            background: var(--light-gray);
        }

        .exam-item:last-child {
            border-bottom: none;
        }

        .exam-info h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .exam-info p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .exam-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-upcoming {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-graded {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        /* To-Do List */
        .todo-list {
            list-style-type: none;
        }

        .todo-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
        }

        .todo-item:hover {
            background: var(--light-gray);
        }

        .todo-item:last-child {
            border-bottom: none;
        }

        .todo-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .todo-checkbox.checked {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .todo-content {
            flex: 1;
        }

        .todo-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .todo-content p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .todo-actions {
            display: flex;
            gap: 10px;
        }

        /* Class Performance */
        .class-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .class-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 12px;
            transition: var(--transition);
        }

        .class-item:hover {
            background: var(--primary-light);
        }

        .class-name {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .class-average {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .class-progress {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }

        .class-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
        }

        /* Calendar Widget */
        .calendar-widget {
            margin-top: 20px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }

        .calendar-day {
            text-align: center;
            padding: 8px;
            font-size: 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .calendar-day:hover {
            background: var(--primary-light);
        }

        .calendar-day.today {
            background: var(--primary);
            color: white;
        }

        .calendar-day.has-event {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            font-weight: 600;
        }

        /* Announcements */
        .announcement-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .announcement-item {
            padding: 15px;
            background: var(--light-gray);
            border-radius: 12px;
            border-left: 4px solid var(--info);
            transition: var(--transition);
        }

        .announcement-item:hover {
            background: var(--primary-light);
            transform: translateX(5px);
        }

        .announcement-item.urgent {
            border-left-color: var(--danger);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .announcement-header h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .announcement-date {
            font-size: 11px;
            color: var(--medium-gray);
        }

        .announcement-content p {
            font-size: 13px;
            color: var(--medium-gray);
            line-height: 1.6;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            text-align: center;
            text-decoration: none;
            color: var(--dark-gray);
            border: 2px solid transparent;
        }

        .action-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            color: var(--primary);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 15px;
        }

        .action-card h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .action-card p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Performance Chart */
        .performance-chart {
            height: 250px;
            margin-top: 20px;
            position: relative;
        }

        .chart-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--medium-gray);
        }

        .chart-placeholder i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
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
            
            .dashboard-sections {
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
            
            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
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
            
            .quick-stats {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 28px;
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
            <a href="dashboard-admin.html" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Dashboard Admin Guru</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="top-nav">
                <a href="#" class="nav-item">
                    <i class="fas fa-home"></i>
                    Utama
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    Pemberitahuan
                    <span class="notification-badge">5</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    Mesej
                    <span class="notification-badge">3</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">GU</div>
                <div class="user-info">
                    <h4>Cikgu Ahmad</h4>
                    <p>Admin Guru Tahun 6</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-title">Menu Utama</div>
            <a href="dashboard-admin.html" class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="kelas-saya.html" class="sidebar-item">
                <i class="fas fa-users"></i>
                Kelas Saya
                <span class="badge">3</span>
            </a>
            <a href="pelajar-saya.html" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <span class="badge">85</span>
            </a>
            <a href="subjek-saya.html" class="sidebar-item">
                <i class="fas fa-book"></i>
                Subjek Saya
                <span class="badge">4</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan & Penilaian</div>
            <a href="tambah-markah.html" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="kemaskini-markah.html" class="sidebar-item">
                <i class="fas fa-edit"></i>
                Kemaskini Markah
            </a>
            <a href="semak-markah.html" class="sidebar-item">
                <i class="fas fa-search"></i>
                Semak Markah
            </a>
            <a href="laporan-prestasi.html" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Pengurusan</div>
            <a href="jadual-ujian.html" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                Jadual Ujian
            </a>
            <a href="tugasan.html" class="sidebar-item">
                <i class="fas fa-tasks"></i>
                Tugasan
                <span class="badge">12</span>
            </a>
            <a href="kehadiran.html" class="sidebar-item">
                <i class="fas fa-clipboard-check"></i>
                Kehadiran
            </a>
            <a href="komunikasi.html" class="sidebar-item">
                <i class="fas fa-comments"></i>
                Komunikasi Ibu Bapa
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Sistem</div>
            <a href="profil.html" class="sidebar-item">
                <i class="fas fa-user-cog"></i>
                Profil Saya
            </a>
            <a href="tetapan.html" class="sidebar-item">
                <i class="fas fa-cog"></i>
                Tetapan
            </a>
            <a href="bantuan-admin.html" class="sidebar-item">
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
        <!-- Welcome Section -->
        <div class="page-header">
            <div class="page-title">
                <h2>Selamat Datang, Cikgu Ahmad! 👨‍🏫</h2>
                <p>Dashboard pentadbir untuk urusan akademik dan pentadbiran guru</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahTugasan()">
                    <i class="fas fa-plus-circle"></i>
                    Tugasan Baru
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value">85</div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        5 pelajar baru
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon exams">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>UJIAN BELUM DINILAI</h3>
                    <div class="stat-value">3</div>
                    <div class="stat-change negative">
                        <i class="fas fa-exclamation-circle"></i>
                        Deadline: 2 hari
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon subjects">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>SUBJEK DIAJAR</h3>
                    <div class="stat-value">4</div>
                    <div class="stat-change">
                        Matematik, Sains, BM, PJ
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>KELAS DIKENDALIKAN</h3>
                    <div class="stat-value">3</div>
                    <div class="stat-change">
                        6A, 6B, 5A
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="tambah-markah.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h4>Tambah Markah</h4>
                <p>Masukkan markah peperiksaan baru</p>
            </a>

            <a href="semak-markah.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h4>Semak Markah</h4>
                <p>Semak dan analisis markah pelajar</p>
            </a>

            <a href="jadual-ujian.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h4>Jadual Ujian</h4>
                <p>Urus jadual peperiksaan</p>
            </a>

            <a href="laporan-prestasi.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h4>Jana Laporan</h4>
                <p>Hasilkan laporan prestasi</p>
            </a>

            <a href="tugasan.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h4>Tugasan</h4>
                <p>Urus tugasan pelajar</p>
            </a>

            <a href="komunikasi.html" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h4>Komunikasi</h4>
                <p>Hubungi ibu bapa</p>
            </a>
        </div>

        <!-- Dashboard Sections -->
        <div class="dashboard-sections">
            <!-- Recent Exams & To-Do -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Ujian Terkini & Tugasan</h3>
                    <button class="btn btn-secondary btn-sm" onclick="lihatSemuaUjian()">
                        Lihat Semua
                    </button>
                </div>
                
                <ul class="exam-list" id="recentExams">
                    <!-- Recent exams will be loaded here -->
                </ul>
                
                <div style="margin-top: 25px;">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Senarai Tugasan</h4>
                    <ul class="todo-list" id="todoList">
                        <!-- To-do items will be loaded here -->
                    </ul>
                </div>
            </div>

            <!-- Class Performance -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Prestasi Kelas</h3>
                    <button class="btn btn-secondary btn-sm" onclick="analisisTerperinci()">
                        Analisis
                    </button>
                </div>
                
                <div class="class-list" id="classPerformance">
                    <!-- Class performance items will be loaded here -->
                </div>
                
                <div class="performance-chart">
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line"></i>
                        <p>Graf Prestasi Kelas</p>
                        <small>Purata markah mengikut bulan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Second Row Sections -->
        <div class="dashboard-sections">
            <!-- Calendar Widget -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Kalendar Akademik</h3>
                    <button class="btn btn-secondary btn-sm" onclick="lihatKalendarPenuh()">
                        <i class="fas fa-calendar"></i>
                    </button>
                </div>
                
                <div class="calendar-widget">
                    <div class="calendar-header">
                        <h4 style="font-size: 16px; color: var(--dark-gray);">Disember 2023</h4>
                        <div style="display: flex; gap: 10px;">
                            <button class="btn btn-sm" onclick="prevMonth()" style="padding: 5px 10px;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm" onclick="nextMonth()" style="padding: 5px 10px;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="calendar-grid" id="miniCalendar">
                        <!-- Mini calendar will be loaded here -->
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <h4 style="font-size: 15px; margin-bottom: 10px; color: var(--dark-gray);">Acara Hari Ini</h4>
                        <div style="background: var(--light-gray); padding: 12px; border-radius: 10px; border-left: 4px solid var(--info);">
                            <div style="font-weight: 600; color: var(--dark-gray);">Perjumpaan Guru</div>
                            <div style="font-size: 13px; color: var(--medium-gray);">2:00 PM - 4:00 PM</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Pengumuman Penting</h3>
                    <button class="btn btn-secondary btn-sm" onclick="semuaPengumuman()">
                        Semua
                    </button>
                </div>
                
                <div class="announcement-list" id="announcements">
                    <!-- Announcements will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="dashboard-card" style="margin-top: 30px;">
            <div class="card-header">
                <h3>Tarikh Akhir Menghampiri ⏰</h3>
                <button class="btn btn-primary" onclick="tambahPeringatan()">
                    <i class="fas fa-bell"></i>
                    Tetapkan Peringatan
                </button>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
                <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 20px; border-radius: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 50px; height: 50px; background: #f59e0b; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--dark-gray);">Hantar Markah Ujian 2</div>
                            <div style="font-size: 13px; color: var(--medium-gray);">Matematik Tahun 6</div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: var(--warning);">
                            <i class="fas fa-clock"></i> 2 hari lagi
                        </span>
                        <button class="btn btn-sm" style="background: #f59e0b; color: white; padding: 5px 15px;" onclick="tindakanSegera('markah')">
                            Tindakan
                        </button>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #fee2e2, #fecaca); padding: 20px; border-radius: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 50px; height: 50px; background: #ef4444; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--dark-gray);">Laporan Disiplin</div>
                            <div style="font-size: 13px; color: var(--medium-gray);">Kelas 6B - 3 kes</div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: var(--danger);">
                            <i class="fas fa-clock"></i> Hari ini
                        </span>
                        <button class="btn btn-sm" style="background: #ef4444; color: white; padding: 5px 15px;" onclick="tindakanSegera('disiplin')">
                            Selesaikan
                        </button>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); padding: 20px; border-radius: 15px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div style="width: 50px; height: 50px; background: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; color: var(--dark-gray);">Mesyuarat Guru</div>
                            <div style="font-size: 13px; color: var(--medium-gray);">Bengkel PBS Tahun 6</div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: var(--info);">
                            <i class="fas fa-clock"></i> 3 hari lagi
                        </span>
                        <button class="btn btn-sm" style="background: #3b82f6; color: white; padding: 5px 15px;" onclick="tindakanSegera('mesyuarat')">
                            Detail
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const recentExams = document.getElementById('recentExams');
        const todoList = document.getElementById('todoList');
        const classPerformance = document.getElementById('classPerformance');
        const miniCalendar = document.getElementById('miniCalendar');
        const announcements = document.getElementById('announcements');

        // Sample Data
        const sampleExams = [
            {
                id: 'EXM001',
                subject: 'Matematik',
                class: '6A',
                date: '15 Dis 2023',
                type: 'Ujian Akhir Tahun',
                status: 'graded'
            },
            {
                id: 'EXM002',
                subject: 'Sains',
                class: '6B',
                date: '16 Dis 2023',
                type: 'Ujian Akhir Tahun',
                status: 'upcoming'
            },
            {
                id: 'EXM003',
                subject: 'Bahasa Melayu',
                class: '6A',
                date: '14 Dis 2023',
                type: 'Ujian 2',
                status: 'graded'
            },
            {
                id: 'EXM004',
                subject: 'PJ',
                class: '5A',
                date: '18 Dis 2023',
                type: 'Ujian Praktikal',
                status: 'upcoming'
            }
        ];

        const sampleTodos = [
            {
                id: 'TODO1',
                title: 'Semak buku latihan Matematik 6A',
                dueDate: 'Hari ini',
                completed: false
            },
            {
                id: 'TODO2',
                title: 'Sediakan soalan ulangkaji Sains',
                dueDate: 'Esok',
                completed: true
            },
            {
                id: 'TODO3',
                title: 'Hantar laporan prestasi pelajar',
                dueDate: '2 hari lagi',
                completed: false
            },
            {
                id: 'TODO4',
                title: 'Mesyuarat dengan ibu bapa (Ali, 6A)',
                dueDate: '18 Dis',
                completed: false
            }
        ];

        const sampleClasses = [
            {
                name: 'Kelas 6A',
                average: 82.5,
                totalStudents: 28,
                improvement: '+5.2%'
            },
            {
                name: 'Kelas 6B',
                average: 78.3,
                totalStudents: 30,
                improvement: '+3.8%'
            },
            {
                name: 'Kelas 5A',
                average: 75.6,
                totalStudents: 27,
                improvement: '+2.1%'
            }
        ];

        const sampleAnnouncements = [
            {
                title: 'Mesyuarat Guru Tahunan',
                date: '15 Dis 2023',
                content: 'Semua guru diwajibkan hadir mesyuarat tahunan di Bilik Guru pada pukul 2:00 PM.',
                urgent: false
            },
            {
                title: 'Perubahan Jadual Ujian Akhir Tahun',
                date: '14 Dis 2023',
                content: 'Terdapat sedikit perubahan pada jadual Ujian Akhir Tahun untuk subjek Matematik dan Sains.',
                urgent: true
            },
            {
                title: 'Bengkel PBS Tahun 6',
                date: '12 Dis 2023',
                content: 'Bengkel Pentaksiran Berasaskan Sekolah (PBS) akan diadakan pada 20 Disember.',
                urgent: false
            }
        ];

        // Initialize page
        function initializePage() {
            // Load all dashboard components
            loadRecentExams();
            loadTodoList();
            loadClassPerformance();
            loadMiniCalendar();
            loadAnnouncements();
            
            // Set up event listeners
            setupEventListeners();
            
            // Initialize calendar
            updateMiniCalendar();
        }

        // Load recent exams
        function loadRecentExams() {
            recentExams.innerHTML = sampleExams.map(exam => `
                <li class="exam-item" onclick="viewExam('${exam.id}')">
                    <div class="exam-info">
                        <h4>${exam.subject} - ${exam.class}</h4>
                        <p>${exam.type} • ${exam.date}</p>
                    </div>
                    <span class="exam-status status-${exam.status}">
                        ${exam.status === 'graded' ? 'Telah Dinilai' : 'Belum Dinilai'}
                    </span>
                </li>
            `).join('');
        }

        // Load to-do list
        function loadTodoList() {
            todoList.innerHTML = sampleTodos.map(todo => `
                <li class="todo-item">
                    <div class="todo-checkbox ${todo.completed ? 'checked' : ''}" 
                         onclick="toggleTodo('${todo.id}')">
                        ${todo.completed ? '<i class="fas fa-check"></i>' : ''}
                    </div>
                    <div class="todo-content">
                        <h4>${todo.title}</h4>
                        <p>Tarikh akhir: ${todo.dueDate}</p>
                    </div>
                    <div class="todo-actions">
                        <button class="btn btn-sm" style="padding: 5px 10px;" onclick="editTodo('${todo.id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </li>
            `).join('');
        }

        // Load class performance
        function loadClassPerformance() {
            classPerformance.innerHTML = sampleClasses.map(cls => `
                <div class="class-item">
                    <div style="flex: 1;">
                        <div class="class-name">${cls.name}</div>
                        <div style="font-size: 13px; color: var(--medium-gray);">
                            ${cls.totalStudents} pelajar • ${cls.improvement}
                        </div>
                        <div class="class-progress">
                            <div class="class-progress-bar" style="width: ${cls.average}%"></div>
                        </div>
                    </div>
                    <div class="class-average">${cls.average}%</div>
                </div>
            `).join('');
        }

        // Load mini calendar
        function loadMiniCalendar() {
            // Day headers
            const dayHeaders = ['Ah', 'Is', 'Se', 'Ra', 'Kh', 'Ju', 'Sa'];
            miniCalendar.innerHTML = dayHeaders.map(day => `
                <div class="calendar-day" style="font-weight: 600; color: var(--medium-gray);">${day}</div>
            `).join('');
            
            updateMiniCalendar();
        }

        // Update mini calendar
        function updateMiniCalendar() {
            const currentDate = new Date();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Get first day of month
            const firstDay = new Date(year, month, 1);
            // Get last day of month
            const lastDay = new Date(year, month + 1, 0);
            // Get number of days in month
            const daysInMonth = lastDay.getDate();
            // Get day of week for first day (0 = Sunday, 1 = Monday, etc.)
            const firstDayIndex = firstDay.getDay();
            
            // Clear existing days except headers
            const existingDays = Array.from(miniCalendar.children).slice(7);
            existingDays.forEach(day => day.remove());
            
            // Previous month days (empty cells)
            for (let i = 0; i < firstDayIndex; i++) {
                miniCalendar.innerHTML += `<div class="calendar-day"></div>`;
            }
            
            // Current month days
            const today = new Date();
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
                const hasEvent = day === 15 || day === 20; // Example event days
                
                let dayClass = 'calendar-day';
                if (isToday) dayClass += ' today';
                if (hasEvent) dayClass += ' has-event';
                
                miniCalendar.innerHTML += `<div class="${dayClass}">${day}</div>`;
            }
        }

        // Load announcements
        function loadAnnouncements() {
            announcements.innerHTML = sampleAnnouncements.map(announcement => `
                <div class="announcement-item ${announcement.urgent ? 'urgent' : ''}">
                    <div class="announcement-header">
                        <h4>${announcement.title}</h4>
                        <span class="announcement-date">${announcement.date}</span>
                    </div>
                    <div class="announcement-content">
                        <p>${announcement.content}</p>
                    </div>
                </div>
            `).join('');
        }

        // Toggle to-do item
        function toggleTodo(todoId) {
            const todo = sampleTodos.find(t => t.id === todoId);
            if (todo) {
                todo.completed = !todo.completed;
                loadTodoList();
                showNotification(`${todo.title} ${todo.completed ? 'diselesaikan' : 'dibuka semula'}`);
            }
        }

        // Edit to-do item
        function editTodo(todoId) {
            const todo = sampleTodos.find(t => t.id === todoId);
            if (todo) {
                const newTitle = prompt('Edit tugasan:', todo.title);
                if (newTitle && newTitle.trim() !== '') {
                    todo.title = newTitle.trim();
                    loadTodoList();
                    showNotification('Tugasan dikemaskini');
                }
            }
        }

        // View exam details
        function viewExam(examId) {
            const exam = sampleExams.find(e => e.id === examId);
            if (exam) {
                alert(`Maklumat Ujian:\n\nSubjek: ${exam.subject}\nKelas: ${exam.class}\nJenis: ${exam.type}\nTarikh: ${exam.date}\nStatus: ${exam.status === 'graded' ? 'Telah Dinilai' : 'Belum Dinilai'}`);
            }
        }

        // Show notification
        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 30px;
                background: var(--success);
                color: white;
                padding: 15px 25px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Navigation functions
        function lihatSemuaUjian() {
            alert('Membuka semua ujian...');
            // In real app, navigate to exams page
        }

        function analisisTerperinci() {
            alert('Membuka analisis terperinci prestasi kelas...');
            // In real app, navigate to analysis page
        }

        function lihatKalendarPenuh() {
            alert('Membuka kalendar penuh...');
            // In real app, navigate to full calendar
        }

        function semuaPengumuman() {
            alert('Membuka semua pengumuman...');
            // In real app, navigate to announcements page
        }

        function tambahTugasan() {
            const taskTitle = prompt('Tajuk tugasan baru:');
            if (taskTitle && taskTitle.trim() !== '') {
                const newTodo = {
                    id: 'TODO' + (sampleTodos.length + 1),
                    title: taskTitle.trim(),
                    dueDate: 'Esok',
                    completed: false
                };
                sampleTodos.unshift(newTodo);
                loadTodoList();
                showNotification('Tugasan baru ditambah');
            }
        }

        function tambahPeringatan() {
            const reminder = prompt('Tajuk peringatan:');
            if (reminder && reminder.trim() !== '') {
                alert(`Peringatan ditetapkan: "${reminder}"\n\nAnda akan diingatkan 1 hari sebelum tarikh akhir.`);
            }
        }

        function tindakanSegera(type) {
            if (type === 'markah') {
                alert('Membuka halaman hantar markah...');
            } else if (type === 'disiplin') {
                alert('Membuka haporan disiplin...');
            } else if (type === 'mesyuarat') {
                alert('Membuka detail mesyuarat...');
            }
        }

        function muatSemulaData() {
            // Simulate data refresh
            showNotification('Menyegarkan data...');
            setTimeout(() => {
                loadRecentExams();
                loadTodoList();
                loadClassPerformance();
                showNotification('Data disegarkan');
            }, 1000);
        }

        function prevMonth() {
            alert('Bulan sebelumnya');
            // In real app, update calendar to previous month
        }

        function nextMonth() {
            alert('Bulan seterusnya');
            // In real app, update calendar to next month
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

        // Add CSS for notification animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .btn-sm {
                padding: 5px 15px !important;
                font-size: 12px !important;
            }
        `;
        document.head.appendChild(style);

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    </script>
</body>
</html>