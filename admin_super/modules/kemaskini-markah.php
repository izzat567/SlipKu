<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Markah - SlipKu</title>
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

        /* Search Section */
        .search-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-input-group {
            flex: 1;
            min-width: 300px;
            position: relative;
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

        /* Student Table */
        .table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
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
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tr:hover td {
            background: var(--primary-light);
        }

        tr.editing td {
            background: rgba(245, 158, 11, 0.1);
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

        /* Mark Input */
        .mark-input-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mark-input {
            width: 80px;
            padding: 8px 10px;
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

        .mark-input.updated {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.05);
        }

        /* Grade Badge */
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

        /* Change Indicator */
        .change-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 12px;
            margin-left: 8px;
        }

        .change-positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .change-negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: var(--light-gray);
            color: var(--medium-gray);
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-icon.edit {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .btn-icon.edit:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .btn-icon.save {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .btn-icon.save:hover {
            background: rgba(16, 185, 129, 0.2);
        }

        .btn-icon.cancel {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .btn-icon.cancel:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-gray);
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .pagination-btn:hover:not(:disabled) {
            background: var(--primary-light);
            color: var(--primary);
        }

        .pagination-btn.active {
            background: var(--primary);
            color: white;
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .search-section {
                flex-direction: column;
            }
            
            .search-input-group {
                min-width: 100%;
            }
            
            .table-container {
                padding: 15px;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .table-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .pagination {
                gap: 5px;
            }
            
            .pagination-btn {
                width: 36px;
                height: 36px;
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
            
            .filter-section {
                padding: 15px;
            }
            
            th, td {
                padding: 12px 8px;
            }
            
            .mark-input {
                width: 70px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-icon {
                width: 32px;
                height: 32px;
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
            <a href="tambah-markah.html" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="#" class="sidebar-item active">
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
                <h2>Kemaskini Markah ✏️</h2>
                <p>Kemaskini markah peperiksaan yang sedia ada</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-success" onclick="simpanSemuaPerubahan()">
                    <i class="fas fa-save"></i>
                    Simpan Semua
                </button>
                <button class="btn btn-info" onclick="cetakMarkah()">
                    <i class="fas fa-print"></i>
                    Cetak Markah
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Jenis Peperiksaan</label>
                    <select class="filter-select" id="filterExam" onchange="filterData()">
                        <option value="">Semua Peperiksaan</option>
                        <option value="ujian1">Ujian 1</option>
                        <option value="ujian2">Ujian 2</option>
                        <option value="pertengahan">Peperiksaan Pertengahan</option>
                        <option value="akhir" selected>Peperiksaan Akhir</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterYear" onchange="filterData()">
                        <option value="">Semua Tahun</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6" selected>Tahun 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select class="filter-select" id="filterClass" onchange="filterData()">
                        <option value="">Semua Kelas</option>
                        <option value="A" selected>Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Mata Pelajaran</label>
                    <select class="filter-select" id="filterSubject" onchange="filterData()">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01" selected>PJ & Kesihatan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar mengikut nama atau ID..." onkeyup="searchTable()">
            </div>
            <button class="btn btn-secondary" onclick="resetSearch()">
                <i class="fas fa-times"></i>
                Reset Carian
            </button>
            <button class="btn btn-primary" onclick="filterData()">
                <i class="fas fa-filter"></i>
                Gunakan Penapis
            </button>
        </div>

        <!-- Student Marks Table -->
        <div class="table-container">
            <div class="table-header">
                <h3>Markah Peperiksaan <span id="tableCount">(45 pelajar)</span></h3>
                <div class="table-actions">
                    <button class="btn btn-secondary" onclick="bulkEditMode()">
                        <i class="fas fa-edit"></i>
                        Edit Pukal
                    </button>
                    <button class="btn btn-danger" onclick="resetAllChanges()">
                        <i class="fas fa-undo"></i>
                        Batalkan Semua
                    </button>
                </div>
            </div>
            
            <table id="marksTable">
                <thead>
                    <tr>
                        <th width="50px">#</th>
                        <th>NAMA PELAJAR</th>
                        <th>JENIS PEPERIKSAAN</th>
                        <th>MATA PELAJARAN</th>
                        <th>MARKAH SEDIA ADA</th>
                        <th>MARKAH BARU</th>
                        <th>PERUBAHAN</th>
                        <th>STATUS</th>
                        <th width="120px">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="marksTableBody">
                    <!-- Data akan dipenuhi oleh JavaScript -->
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" onclick="changePage(-1)" id="prevPage">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active" onclick="goToPage(1)">1</button>
                <button class="pagination-btn" onclick="goToPage(2)">2</button>
                <button class="pagination-btn" onclick="goToPage(3)">3</button>
                <span style="padding: 0 10px; color: var(--medium-gray);">...</span>
                <button class="pagination-btn" onclick="goToPage(5)">5</button>
                <button class="pagination-btn" onclick="changePage(1)" id="nextPage">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="filter-section">
            <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Ringkasan Perubahan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 5px;">Jumlah Pelajar</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--primary);" id="summaryTotal">45</div>
                </div>
                <div style="background: rgba(16, 185, 129, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--success); margin-bottom: 5px;">Markah Dikemaskini</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--success);" id="summaryUpdated">0</div>
                </div>
                <div style="background: rgba(245, 158, 11, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--warning); margin-bottom: 5px;">Dalam Pengeditan</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--warning);" id="summaryEditing">0</div>
                </div>
                <div style="background: rgba(59, 130, 246, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--info); margin-bottom: 5px;">Purata Perubahan</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--info);" id="summaryAverage">+0.0%</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <h4 id="toastTitle">Berjaya!</h4>
            <p id="toastMessage">Markah telah berjaya dikemaskini.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const marksTableBody = document.getElementById('marksTableBody');
        const toast = document.getElementById('toast');

        // Current state
        let currentData = [];
        let filteredData = [];
        let editingRowId = null;
        let currentPage = 1;
        const itemsPerPage = 10;

        // Sample data for student marks
        const marksData = [
            { id: 'M001', studentId: 'P001', studentName: 'AHMAD BIN ALI', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 95, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M002', studentId: 'P045', studentName: 'SITI NOR AISYAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 93, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M003', studentId: 'P023', studentName: 'MOHD AMIR BIN HASSAN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 92, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M004', studentId: 'P067', studentName: 'NURUL FATIMAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 91, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M005', studentId: 'P089', studentName: 'WAN AHMAD BIN WAN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 89, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M006', studentId: 'P034', studentName: 'NOR HIDAYAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 87, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M007', studentId: 'P078', studentName: 'ALI BIN KASSIM', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 85, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M008', studentId: 'P102', studentName: 'ROHAYU BINTI RAHIM', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 84, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M009', studentId: 'P056', studentName: 'FAIZ BIN FARID', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 82, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M010', studentId: 'P091', studentName: 'AIN NABIHAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 80, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M011', studentId: 'P112', studentName: 'KAMAL BIN KAMARUDDIN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 78, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M012', studentId: 'P123', studentName: 'ZAHRAH BINTI ZAINAL', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 75, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M013', studentId: 'P134', studentName: 'HASAN BIN HUSSEIN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 72, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M014', studentId: 'P145', studentName: 'NORA BINTI ISMAIL', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 68, newMark: null, grade: 'D', updated: false, editing: false },
            { id: 'M015', studentId: 'P156', studentName: 'FARID BIN FAUZI', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 65, newMark: null, grade: 'D', updated: false, editing: false }
        ];

        // Initialize page
        function initializePage() {
            currentData = [...marksData];
            filteredData = [...currentData];
            
            // Set up event listeners for filters
            document.getElementById('filterExam').addEventListener('change', filterData);
            document.getElementById('filterYear').addEventListener('change', filterData);
            document.getElementById('filterClass').addEventListener('change', filterData);
            document.getElementById('filterSubject').addEventListener('change', filterData);
            
            // Load initial data
            renderTable();
            updateSummary();
        }

        // Render table with current data
        function renderTable() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            // Update table count
            document.getElementById('tableCount').textContent = `(${filteredData.length} pelajar)`;
            
            // Render table rows
            marksTableBody.innerHTML = pageData.map((item, index) => {
                const globalIndex = startIndex + index + 1;
                const change = item.newMark !== null ? item.newMark - item.currentMark : 0;
                const changeClass = change > 0 ? 'change-positive' : change < 0 ? 'change-negative' : '';
                const changeText = change > 0 ? `+${change}` : change < 0 ? change : '';
                
                let statusBadge = '<span style="color: var(--medium-gray);">Tidak Berubah</span>';
                if (item.editing) {
                    statusBadge = '<span style="color: var(--warning); font-weight: 600;">Dalam Pengeditan</span>';
                } else if (item.updated) {
                    statusBadge = '<span style="color: var(--success); font-weight: 600;">Telah Dikemaskini</span>';
                }
                
                return `
                    <tr id="row-${item.id}" class="${item.editing ? 'editing' : ''}">
                        <td>${globalIndex}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="student-avatar">${item.studentName.charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 600;">${item.studentName}</div>
                                    <div style="font-size: 12px; color: var(--medium-gray);">ID: ${item.studentId}</div>
                                </div>
                            </div>
                        </td>
                        <td>${item.examType}</td>
                        <td>
                            <div>${item.subject}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Tahun ${item.year}, Kelas ${item.class}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--primary);">${item.currentMark}%</div>
                            <span class="grade-badge grade-${item.grade.toLowerCase()}">${item.grade}</span>
                        </td>
                        <td>
                            <div class="mark-input-container">
                                <input type="number" 
                                       class="mark-input ${item.newMark !== null ? 'updated' : ''}" 
                                       id="input-${item.id}" 
                                       value="${item.newMark !== null ? item.newMark : ''}"
                                       ${!item.editing ? 'disabled' : ''}
                                       min="0" 
                                       max="100" 
                                       oninput="validateMarkInput('${item.id}', this.value)"
                                       onblur="updateMark('${item.id}', this.value)"
                                       style="${!item.editing ? 'background: var(--light-gray);' : ''}">
                                <span style="font-size: 12px; color: var(--medium-gray);">%</span>
                            </div>
                        </td>
                        <td>
                            ${item.newMark !== null ? `
                                <div style="display: flex; align-items: center;">
                                    <span style="font-weight: 600;">${item.newMark}%</span>
                                    ${changeText ? `<span class="change-indicator ${changeClass}">${changeText}</span>` : ''}
                                </div>
                            ` : '-'}
                        </td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="action-buttons">
                                ${!item.editing ? `
                                    <button class="btn-icon edit" onclick="editRow('${item.id}')" title="Edit Markah">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                ` : `
                                    <button class="btn-icon save" onclick="saveRow('${item.id}')" title="Simpan Perubahan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-icon cancel" onclick="cancelEdit('${item.id}')" title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Update pagination controls
            updatePagination();
        }

        // Filter data based on filters
        function filterData() {
            const examFilter = document.getElementById('filterExam').value;
            const yearFilter = document.getElementById('filterYear').value;
            const classFilter = document.getElementById('filterClass').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            
            filteredData = currentData.filter(item => {
                // Apply filters
                if (examFilter && item.examType !== getExamTypeText(examFilter)) return false;
                if (yearFilter && item.year !== yearFilter) return false;
                if (classFilter && item.class !== classFilter) return false;
                if (subjectFilter && item.subject !== getSubjectText(subjectFilter)) return false;
                return true;
            });
            
            // Reset to first page
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Search in table
        function searchTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            if (!searchTerm) {
                // If search is empty, show filtered data
                filterData();
                return;
            }
            
            filteredData = currentData.filter(item => {
                return item.studentName.toLowerCase().includes(searchTerm) || 
                       item.studentId.toLowerCase().includes(searchTerm);
            });
            
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Reset search
        function resetSearch() {
            document.getElementById('searchInput').value = '';
            filterData();
        }

        // Edit row
        function editRow(rowId) {
            // Cancel any existing edit
            if (editingRowId) {
                cancelEdit(editingRowId);
            }
            
            const item = currentData.find(item => item.id === rowId);
            if (item) {
                item.editing = true;
                editingRowId = rowId;
                
                // Enable input
                const input = document.getElementById(`input-${rowId}`);
                if (input) {
                    input.disabled = false;
                    input.focus();
                }
                
                renderTable();
                updateSummary();
            }
        }

        // Save row
        function saveRow(rowId) {
            const item = currentData.find(item => item.id === rowId);
            if (!item) return;
            
            const input = document.getElementById(`input-${rowId}`);
            if (!input) return;
            
            const newMark = parseInt(input.value);
            
            // Validate mark
            if (isNaN(newMark) || newMark < 0 || newMark > 100) {
                showToast('Ralat', 'Sila masukkan markah antara 0 hingga 100', 'error');
                return;
            }
            
            // Update item
            item.newMark = newMark;
            item.editing = false;
            item.updated = true;
            item.grade = calculateGrade(newMark);
            editingRowId = null;
            
            // Show success message
            showToast('Berjaya!', `Markah ${item.studentName} telah dikemaskini: ${newMark}%`, 'success');
            
            renderTable();
            updateSummary();
        }

        // Cancel edit
        function cancelEdit(rowId) {
            const item = currentData.find(item => item.id === rowId);
            if (item) {
                item.editing = false;
                
                // Reset input to original value
                const input = document.getElementById(`input-${rowId}`);
                if (input) {
                    input.value = item.newMark !== null ? item.newMark : '';
                    input.disabled = true;
                }
                
                editingRowId = null;
                renderTable();
                updateSummary();
            }
        }

        // Update mark directly (for onblur event)
        function updateMark(rowId, value) {
            const item = currentData.find(item => item.id === rowId);
            if (!item || !item.editing) return;
            
            const newMark = parseInt(value);
            
            // Only update if valid
            if (!isNaN(newMark) && newMark >= 0 && newMark <= 100) {
                item.newMark = newMark;
                item.updated = true;
                item.grade = calculateGrade(newMark);
            }
            
            // Re-render to show updated status
            renderTable();
            updateSummary();
        }

        // Validate mark input
        function validateMarkInput(rowId, value) {
            const input = document.getElementById(`input-${rowId}`);
            const markValue = parseInt(value);
            
            if (value === '') {
                input.classList.remove('updated');
                return;
            }
            
            if (isNaN(markValue) || markValue < 0 || markValue > 100) {
                input.style.borderColor = 'var(--danger)';
            } else {
                input.style.borderColor = '';
                input.classList.add('updated');
            }
        }

        // Calculate grade from mark
        function calculateGrade(mark) {
            if (mark >= 90) return 'A';
            if (mark >= 80) return 'B';
            if (mark >= 70) return 'C';
            if (mark >= 60) return 'D';
            return 'F';
        }

        // Bulk edit mode
        function bulkEditMode() {
            // Enable editing for all rows
            currentData.forEach(item => {
                if (!item.editing) {
                    item.editing = true;
                }
            });
            
            showToast('Mod Edit Pukal', 'Semua medan markah telah dibuka untuk pengeditan', 'success');
            renderTable();
            updateSummary();
        }

        // Save all changes
        function simpanSemuaPerubahan() {
            const updatedItems = currentData.filter(item => item.updated || item.newMark !== null);
            
            if (updatedItems.length === 0) {
                showToast('Tiada Perubahan', 'Tiada markah yang perlu disimpan', 'error');
                return;
            }
            
            // Show confirmation
            if (confirm(`Adakah anda pasti ingin menyimpan ${updatedItems.length} perubahan markah?`)) {
                // Simulate API call
                showToast('Menyimpan...', 'Semua perubahan sedang disimpan ke sistem', 'success');
                
                setTimeout(() => {
                    // Mark all as saved (reset editing state)
                    currentData.forEach(item => {
                        if (item.updated) {
                            item.currentMark = item.newMark;
                            item.newMark = null;
                            item.updated = false;
                            item.editing = false;
                        }
                    });
                    
                    showToast('Berjaya Disimpan!', 
                        `${updatedItems.length} markah telah berjaya dikemaskini dalam sistem`, 
                        'success');
                    
                    renderTable();
                    updateSummary();
                }, 1500);
            }
        }

        // Reset all changes
        function resetAllChanges() {
            const changedItems = currentData.filter(item => item.newMark !== null || item.editing);
            
            if (changedItems.length === 0) {
                showToast('Tiada Perubahan', 'Tiada perubahan untuk dibatalkan', 'error');
                return;
            }
            
            if (confirm(`Adakah anda pasti ingin membatalkan semua ${changedItems.length} perubahan?`)) {
                // Reset all items
                currentData.forEach(item => {
                    item.newMark = null;
                    item.editing = false;
                    item.updated = false;
                });
                
                editingRowId = null;
                
                showToast('Dibatal', 'Semua perubahan telah dibatalkan', 'success');
                renderTable();
                updateSummary();
            }
        }

        // Update summary statistics
        function updateSummary() {
            const total = filteredData.length;
            const updated = filteredData.filter(item => item.updated).length;
            const editing = filteredData.filter(item => item.editing).length;
            
            // Calculate average change
            const changedItems = filteredData.filter(item => item.newMark !== null);
            let totalChange = 0;
            changedItems.forEach(item => {
                totalChange += (item.newMark - item.currentMark);
            });
            
            const averageChange = changedItems.length > 0 ? 
                (totalChange / changedItems.length).toFixed(1) : 0;
            
            // Update display
            document.getElementById('summaryTotal').textContent = total;
            document.getElementById('summaryUpdated').textContent = updated;
            document.getElementById('summaryEditing').textContent = editing;
            document.getElementById('summaryAverage').textContent = 
                averageChange > 0 ? `+${averageChange}%` : `${averageChange}%`;
            document.getElementById('summaryAverage').style.color = 
                averageChange > 0 ? 'var(--success)' : averageChange < 0 ? 'var(--danger)' : 'var(--info)';
        }

        // Pagination functions
        function changePage(direction) {
            const newPage = currentPage + direction;
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderTable();
            }
        }

        function goToPage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderTable();
            }
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            
            // Update button states
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            // Update page buttons (simplified for demo)
            // In a real app, you would generate dynamic page buttons
        }

        // Reload data
        function muatSemulaData() {
            // Reset filters
            document.getElementById('filterExam').value = 'akhir';
            document.getElementById('filterYear').value = '6';
            document.getElementById('filterClass').value = 'A';
            document.getElementById('filterSubject').value = 'PJH01';
            document.getElementById('searchInput').value = '';
            
            // Reset data
            currentData = [...marksData];
            filteredData = [...currentData];
            currentPage = 1;
            editingRowId = null;
            
            showToast('Data Dimuat Semula', 'Semua penapis dan perubahan telah diset semula', 'success');
            renderTable();
            updateSummary();
        }

        // Print marks
        function cetakMarkah() {
            const updatedItems = currentData.filter(item => item.updated || item.newMark !== null);
            
            if (updatedItems.length === 0 && filteredData.length === 0) {
                showToast('Tiada Data', 'Tiada data markah untuk dicetak', 'error');
                return;
            }
            
            alert('Menyediakan laporan markah untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable report
        }

        // Helper functions
        function getExamTypeText(code) {
            const examTypes = {
                'ujian1': 'Ujian 1',
                'ujian2': 'Ujian 2',
                'pertengahan': 'Peperiksaan Pertengahan',
                'akhir': 'Peperiksaan Akhir'
            };
            return examTypes[code] || code;
        }

        function getSubjectText(code) {
            const subjects = {
                'MAT01': 'Matematik',
                'BAH01': 'Bahasa Melayu',
                'BI01': 'Bahasa Inggeris',
                'SNS01': 'Sains',
                'PJH01': 'PJ & Kesihatan'
            };
            return subjects[code] || code;
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