<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Markah - SlipKu</title>
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

        /* Form Container */
        .form-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .form-label.required::after {
            content: ' *';
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        /* Student Selection */
        .student-selection {
            margin-bottom: 30px;
        }

        .selection-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .selection-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        /* Student Table */
        .student-table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            -webkit-overflow-scrolling: touch;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .student-table th {
            background: var(--light-gray);
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .student-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .student-table tr:hover td {
            background: var(--primary-light);
        }

        .student-table tr.selected td {
            background: rgba(16, 185, 129, 0.1);
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

        .select-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        /* Marks Input Grid */
        .marks-input-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .mark-input-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .mark-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--medium-gray);
        }

        .mark-input {
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            text-align: center;
            transition: var(--transition);
        }

        .mark-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
        }

        .mark-input.full-mark {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.05);
        }

        /* Grade Preview */
        .grade-preview {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 15px;
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .grade-info {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .grade-f {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        /* Subject Info Card */
        .subject-info-card {
            background: var(--primary-light);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 5px solid var(--primary);
        }

        .subject-info-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .subject-details h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .subject-details p {
            font-size: 14px;
            color: var(--medium-gray);
        }

        .subject-stats {
            display: flex;
            gap: 20px;
        }

        .subject-stat {
            text-align: center;
        }

        .subject-stat .value {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .subject-stat .label {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Search Bar */
        .search-bar {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--white);
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 10000;
            transform: translateY(100px);
            opacity: 0;
            transition: var(--transition);
            border-left: 4px solid var(--success);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            border-left-color: var(--success);
        }

        .toast.error {
            border-left-color: var(--danger);
        }

        .toast-icon {
            font-size: 20px;
        }

        .toast.success .toast-icon {
            color: var(--success);
        }

        .toast.error .toast-icon {
            color: var(--danger);
        }

        .toast-content h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .toast-content p {
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
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .subject-info-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .subject-stats {
                width: 100%;
                justify-content: space-between;
            }
            
            .marks-input-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
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
            
            .marks-input-grid {
                grid-template-columns: 1fr;
            }
            
            .toast {
                left: 15px;
                right: 15px;
                bottom: 15px;
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
            <a href="#" class="sidebar-item active">
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
                <h2>Tambah Markah 📝</h2>
                <p>Masukkan markah peperiksaan untuk pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i>
                    Set Semula
                </button>
                <button class="btn btn-info" onclick="cetakPrestasi()">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <h3 style="margin-bottom: 20px; color: var(--dark-gray);">Maklumat Peperiksaan</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required">Jenis Peperiksaan</label>
                    <select class="form-select" id="examType">
                        <option value="">Sila Pilih</option>
                        <option value="ujian1">Ujian 1</option>
                        <option value="ujian2">Ujian 2</option>
                        <option value="pertengahan">Peperiksaan Pertengahan Tahun</option>
                        <option value="akhir" selected>Peperiksaan Akhir Tahun</option>
                        <option value="lain">Lain-lain</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Mata Pelajaran</label>
                    <select class="form-select" id="subject">
                        <option value="">Sila Pilih</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01" selected>PJ & Kesihatan</option>
                        <option value="PIS01">Pendidikan Islam</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Tahun</label>
                    <select class="form-select" id="year">
                        <option value="">Sila Pilih</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6" selected>Tahun 6</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Kelas</label>
                    <select class="form-select" id="class">
                        <option value="">Sila Pilih</option>
                        <option value="A" selected>Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea class="form-textarea" id="notes" placeholder="Masukkan catatan tentang peperiksaan ini..."></textarea>
            </div>
        </div>

        <!-- Subject Info Card -->
        <div class="subject-info-card" id="subjectInfoCard">
            <div class="subject-info-content">
                <div class="subject-details">
                    <h4 id="subjectNameDisplay">PJ & Kesihatan</h4>
                    <p id="subjectClassDisplay">Tahun 6, Kelas A</p>
                </div>
                <div class="subject-stats">
                    <div class="subject-stat">
                        <div class="value" id="totalStudents">45</div>
                        <div class="label">Jumlah Pelajar</div>
                    </div>
                    <div class="subject-stat">
                        <div class="value" id="marksEntered">0</div>
                        <div class="label">Markah Dimasukkan</div>
                    </div>
                    <div class="subject-stat">
                        <div class="value" id="averageMark">0%</div>
                        <div class="label">Purata Sementara</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Selection -->
        <div class="student-selection">
            <div class="selection-header">
                <h3>Senarai Pelajar</h3>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-secondary" onclick="selectAllStudents()">
                        <i class="fas fa-check-square"></i>
                        Pilih Semua
                    </button>
                    <button class="btn btn-secondary" onclick="deselectAllStudents()">
                        <i class="fas fa-square"></i>
                        Nyahpilih Semua
                    </button>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="studentSearch" placeholder="Cari pelajar..." onkeyup="searchStudents()">
            </div>
            
            <div class="student-table-container">
                <table class="student-table" id="studentTable">
                    <thead>
                        <tr>
                            <th width="50px">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="select-checkbox">
                            </th>
                            <th>NAMA PELAJAR</th>
                            <th>NO. KAD PENGENALAN</th>
                            <th>MARKAH TERKINI</th>
                            <th>GRED</th>
                            <th>MARKAH BARU</th>
                            <th>GRED BARU</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button class="btn btn-danger" onclick="resetForm()">
                <i class="fas fa-times"></i>
                Batal
            </button>
            <button class="btn btn-success" onclick="saveMarks()">
                <i class="fas fa-save"></i>
                Simpan Semua Markah
            </button>
            <button class="btn btn-primary" onclick="saveAndNext()">
                <i class="fas fa-arrow-right"></i>
                Simpan & Seterusnya
            </button>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <h4 id="toastTitle">Berjaya!</h4>
            <p id="toastMessage">Markah telah berjaya disimpan.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const studentTableBody = document.getElementById('studentTableBody');
        const toast = document.getElementById('toast');
        const subjectInfoCard = document.getElementById('subjectInfoCard');

        // Sample data for students
        const studentData = [
            { id: 'P001', name: 'AHMAD BIN ALI', ic: '080101-01-1234', currentMark: 95, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P045', name: 'SITI NOR AISYAH', ic: '080202-02-2345', currentMark: 93, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P023', name: 'MOHD AMIR BIN HASSAN', ic: '080303-03-3456', currentMark: 92, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P067', name: 'NURUL FATIMAH', ic: '080404-04-4567', currentMark: 91, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P089', name: 'WAN AHMAD BIN WAN', ic: '080505-05-5678', currentMark: 89, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P034', name: 'NOR HIDAYAH', ic: '080606-06-6789', currentMark: 87, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P078', name: 'ALI BIN KASSIM', ic: '080707-07-7890', currentMark: 85, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P102', name: 'ROHAYU BINTI RAHIM', ic: '080808-08-8901', currentMark: 84, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P056', name: 'FAIZ BIN FARID', ic: '080909-09-9012', currentMark: 82, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P091', name: 'AIN NABIHAH', ic: '080101-10-0123', currentMark: 80, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P112', name: 'KAMAL BIN KAMARUDDIN', ic: '080202-11-1234', currentMark: 78, grade: 'C', newMark: '', newGrade: '' },
            { id: 'P123', name: 'ZAHRAH BINTI ZAINAL', ic: '080303-12-2345', currentMark: 75, grade: 'C', newMark: '', newGrade: '' }
        ];

        // Initialize page
        function initializePage() {
            // Populate student table
            populateStudentTable();
            
            // Set up event listeners for form fields
            document.getElementById('subject').addEventListener('change', updateSubjectInfo);
            document.getElementById('year').addEventListener('change', updateSubjectInfo);
            document.getElementById('class').addEventListener('change', updateSubjectInfo);
            
            // Initialize subject info
            updateSubjectInfo();
            
            // Set up mark input listeners
            setTimeout(setupMarkInputListeners, 100);
        }

        // Populate student table
        function populateStudentTable() {
            studentTableBody.innerHTML = studentData.map((student, index) => `
                <tr id="studentRow-${student.id}" class="${student.newMark ? 'selected' : ''}">
                    <td>
                        <input type="checkbox" class="select-checkbox student-checkbox" id="select-${student.id}" onchange="toggleStudentSelection('${student.id}')">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${student.name.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${student.name}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">ID: ${student.id}</div>
                            </div>
                        </div>
                    </td>
                    <td>${student.ic}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--primary);">${student.currentMark}%</div>
                        <span class="grade-badge grade-${student.grade.toLowerCase()}">${student.grade}</span>
                    </td>
                    <td>
                        <div class="mark-input-group">
                            <input type="number" 
                                   class="mark-input" 
                                   id="mark-${student.id}" 
                                   min="0" 
                                   max="100" 
                                   placeholder="0-100" 
                                   value="${student.newMark || ''}"
                                   oninput="updateGrade('${student.id}', this.value)"
                                   onblur="validateMark('${student.id}', this.value)">
                        </div>
                    </td>
                    <td>
                        <div id="gradeDisplay-${student.id}" class="grade-preview">
                            <div class="grade-info">
                                <span id="gradeText-${student.id}" style="font-weight: 600; color: var(--medium-gray);">-</span>
                                <span id="gradeBadge-${student.id}" class="grade-badge"></span>
                            </div>
                            <div id="markChange-${student.id}" style="font-size: 12px; color: var(--medium-gray);"></div>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            // Update grade displays
            studentData.forEach(student => {
                if (student.newMark) {
                    updateGrade(student.id, student.newMark);
                }
            });
        }

        // Update grade based on mark
        function updateGrade(studentId, mark) {
            const student = studentData.find(s => s.id === studentId);
            if (!student) return;
            
            const markValue = parseInt(mark);
            const currentMark = parseInt(student.currentMark);
            
            if (!isNaN(markValue) && markValue >= 0 && markValue <= 100) {
                student.newMark = markValue;
                
                // Calculate grade
                let grade, gradeClass;
                if (markValue >= 90) { grade = 'A'; gradeClass = 'grade-a'; }
                else if (markValue >= 80) { grade = 'B'; gradeClass = 'grade-b'; }
                else if (markValue >= 70) { grade = 'C'; gradeClass = 'grade-c'; }
                else if (markValue >= 60) { grade = 'D'; gradeClass = 'grade-d'; }
                else { grade = 'F'; gradeClass = 'grade-f'; }
                
                student.newGrade = grade;
                
                // Update display
                const gradeText = document.getElementById(`gradeText-${studentId}`);
                const gradeBadge = document.getElementById(`gradeBadge-${studentId}`);
                const markChange = document.getElementById(`markChange-${studentId}`);
                const row = document.getElementById(`studentRow-${studentId}`);
                
                if (gradeText && gradeBadge) {
                    gradeText.textContent = `${markValue}%`;
                    gradeBadge.textContent = grade;
                    gradeBadge.className = `grade-badge ${gradeClass}`;
                    
                    // Show change from previous mark
                    const change = markValue - currentMark;
                    if (change !== 0) {
                        markChange.innerHTML = change > 0 ? 
                            `<span style="color: var(--success);">+${change}</span>` : 
                            `<span style="color: var(--danger);">${change}</span>`;
                    } else {
                        markChange.innerHTML = '';
                    }
                    
                    // Highlight row if mark is entered
                    if (row) {
                        row.classList.add('selected');
                    }
                }
            } else if (mark === '' || isNaN(markValue)) {
                // Clear if empty or invalid
                student.newMark = '';
                student.newGrade = '';
                
                const gradeText = document.getElementById(`gradeText-${studentId}`);
                const gradeBadge = document.getElementById(`gradeBadge-${studentId}`);
                const markChange = document.getElementById(`markChange-${studentId}`);
                const row = document.getElementById(`studentRow-${studentId}`);
                
                if (gradeText && gradeBadge) {
                    gradeText.textContent = '-';
                    gradeBadge.textContent = '';
                    gradeBadge.className = 'grade-badge';
                    markChange.innerHTML = '';
                    
                    if (row) {
                        row.classList.remove('selected');
                    }
                }
            }
            
            // Update marks entered count
            updateMarksEnteredCount();
            
            // Update checkbox
            const checkbox = document.getElementById(`select-${studentId}`);
            if (checkbox) {
                checkbox.checked = !!student.newMark;
            }
        }

        // Validate mark input
        function validateMark(studentId, value) {
            const input = document.getElementById(`mark-${studentId}`);
            const markValue = parseInt(value);
            
            if (value === '') {
                input.classList.remove('full-mark');
                return;
            }
            
            if (isNaN(markValue) || markValue < 0 || markValue > 100) {
                input.style.borderColor = 'var(--danger)';
                showToast('Ralat', 'Sila masukkan markah antara 0 hingga 100', 'error');
            } else if (markValue === 100) {
                input.classList.add('full-mark');
                input.style.borderColor = '';
            } else {
                input.classList.remove('full-mark');
                input.style.borderColor = '';
            }
        }

        // Update subject info based on form selections
        function updateSubjectInfo() {
            const subjectSelect = document.getElementById('subject');
            const yearSelect = document.getElementById('year');
            const classSelect = document.getElementById('class');
            
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Sila pilih mata pelajaran';
            const yearText = yearSelect.value ? `Tahun ${yearSelect.value}` : 'Sila pilih tahun';
            const classText = classSelect.value ? `Kelas ${classSelect.value}` : 'Sila pilih kelas';
            
            document.getElementById('subjectNameDisplay').textContent = subjectText;
            document.getElementById('subjectClassDisplay').textContent = `${yearText}, ${classText}`;
            
            // Show/hide subject info card
            if (subjectSelect.value && yearSelect.value && classSelect.value) {
                subjectInfoCard.style.display = 'block';
                
                // Update stats based on selections
                const classFilter = classSelect.value;
                const filteredStudents = classFilter === 'A' ? studentData : 
                                       classFilter === 'B' ? studentData.slice(0, 8) : 
                                       studentData.slice(0, 6);
                
                document.getElementById('totalStudents').textContent = filteredStudents.length;
                
                // Calculate marks entered and average
                updateMarksEnteredCount();
            } else {
                subjectInfoCard.style.display = 'none';
            }
        }

        // Update marks entered count and average
        function updateMarksEnteredCount() {
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            const totalStudents = studentData.length;
            
            document.getElementById('marksEntered').textContent = `${marksEntered}/${totalStudents}`;
            
            // Calculate average
            const marksWithValues = studentData.filter(s => s.newMark !== '');
            if (marksWithValues.length > 0) {
                const total = marksWithValues.reduce((sum, s) => sum + parseInt(s.newMark), 0);
                const average = Math.round(total / marksWithValues.length);
                document.getElementById('averageMark').textContent = `${average}%`;
            } else {
                document.getElementById('averageMark').textContent = '0%';
            }
        }

        // Setup mark input listeners
        function setupMarkInputListeners() {
            studentData.forEach(student => {
                const input = document.getElementById(`mark-${student.id}`);
                if (input) {
                    input.addEventListener('keyup', function(e) {
                        if (e.key === 'Enter') {
                            // Move to next input
                            const inputs = Array.from(document.querySelectorAll('.mark-input'));
                            const currentIndex = inputs.indexOf(this);
                            if (currentIndex < inputs.length - 1) {
                                inputs[currentIndex + 1].focus();
                            }
                        }
                    });
                }
            });
        }

        // Search students
        function searchStudents() {
            const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#studentTableBody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const ic = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || ic.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Toggle select all students
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const isChecked = selectAllCheckbox.checked;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                const studentId = checkbox.id.replace('select-', '');
                
                if (isChecked) {
                    // Find student and set a default mark if empty
                    const student = studentData.find(s => s.id === studentId);
                    const markInput = document.getElementById(`mark-${studentId}`);
                    
                    if (student && (!student.newMark || student.newMark === '')) {
                        // Set mark to current mark or random between 70-95
                        const newMark = student.currentMark || Math.floor(Math.random() * 26) + 70;
                        markInput.value = newMark;
                        updateGrade(studentId, newMark);
                    }
                } else {
                    // Clear marks for all
                    const markInput = document.getElementById(`mark-${studentId}`);
                    markInput.value = '';
                    updateGrade(studentId, '');
                }
            });
        }

        // Select all students
        function selectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = true;
            toggleSelectAll();
        }

        // Deselect all students
        function deselectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = false;
            toggleSelectAll();
        }

        // Toggle student selection
        function toggleStudentSelection(studentId) {
            const checkbox = document.getElementById(`select-${studentId}`);
            const markInput = document.getElementById(`mark-${studentId}`);
            const student = studentData.find(s => s.id === studentId);
            
            if (checkbox.checked) {
                if (!student.newMark || student.newMark === '') {
                    const newMark = student.currentMark || Math.floor(Math.random() * 26) + 70;
                    markInput.value = newMark;
                    updateGrade(studentId, newMark);
                }
            } else {
                markInput.value = '';
                updateGrade(studentId, '');
            }
        }

        // Save marks
        function saveMarks() {
            const examType = document.getElementById('examType').value;
            const subject = document.getElementById('subject').value;
            const year = document.getElementById('year').value;
            const className = document.getElementById('class').value;
            
            // Validation
            if (!examType || !subject || !year || !className) {
                showToast('Ralat', 'Sila isi semua maklumat peperiksaan terlebih dahulu', 'error');
                return;
            }
            
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            if (marksEntered === 0) {
                showToast('Ralat', 'Sila masukkan markah untuk sekurang-kurangnya seorang pelajar', 'error');
                return;
            }
            
            // Show loading/saving state
            showToast('Menyimpan...', 'Markah sedang disimpan ke sistem', 'success');
            
            // Simulate API call
            setTimeout(() => {
                // Calculate statistics
                const marksWithValues = studentData.filter(s => s.newMark !== '');
                const total = marksWithValues.reduce((sum, s) => sum + parseInt(s.newMark), 0);
                const average = Math.round(total / marksWithValues.length);
                
                showToast('Berjaya!', 
                    `Markah untuk ${marksEntered} pelajar telah disimpan. Purata: ${average}%`, 
                    'success');
                
                // Reset form after success
                setTimeout(() => {
                    if (confirm(`Markah berjaya disimpan!\n\nJumlah pelajar: ${marksEntered}\nPurata markah: ${average}%\n\nAdakah anda ingin set semula borang untuk mata pelajaran seterusnya?`)) {
                        resetForm();
                    }
                }, 1500);
                
            }, 1000);
        }

        // Save and proceed to next
        function saveAndNext() {
            saveMarks();
            
            // In a real app, this would redirect or load next subject/class
            setTimeout(() => {
                alert('Anda akan dibawa ke halaman tambah markah untuk mata pelajaran seterusnya.');
                // Simulate redirect
                window.location.href = 'tambah-markah.html?next=subject';
            }, 2000);
        }

        // Reset form
        function resetForm() {
            if (confirm('Adakah anda pasti ingin set semula semua markah? Tindakan ini tidak boleh dibatalkan.')) {
                // Reset form fields
                document.getElementById('examType').value = 'akhir';
                document.getElementById('subject').value = 'PJH01';
                document.getElementById('year').value = '6';
                document.getElementById('class').value = 'A';
                document.getElementById('notes').value = '';
                document.getElementById('studentSearch').value = '';
                document.getElementById('selectAll').checked = false;
                
                // Clear all marks
                studentData.forEach(student => {
                    student.newMark = '';
                    student.newGrade = '';
                });
                
                // Re-populate table
                populateStudentTable();
                
                // Update subject info
                updateSubjectInfo();
                
                // Reset search
                searchStudents();
                
                showToast('Diset semula', 'Semua markah telah dikosongkan', 'success');
            }
        }

        // Print function
        function cetakPrestasi() {
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            if (marksEntered === 0) {
                showToast('Ralat', 'Tiada markah untuk dicetak', 'error');
                return;
            }
            
            alert('Menyediakan laporan markah untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable report
        }

        // Show toast notification
        function showToast(title, message, type = 'success') {
            document.getElementById('toastTitle').textContent = title;
            document.getElementById('toastMessage').textContent = message;
            
            toast.className = `toast ${type}`;
            toast.classList.add('show');
            
            // Update icon based on type
            const icon = toast.querySelector('.toast-icon i');
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else {
                icon.className = 'fas fa-exclamation-circle';
            }
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
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
        });
    </script>
</body>
</html>