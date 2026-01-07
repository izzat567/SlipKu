<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Pelajar - SlipKu</title>
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

        /* Search and Filter */
        .search-filter {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .search-box {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 15px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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

        /* Students Table */
        .students-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-header h3 {
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

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light-gray);
            color: var(--medium-gray);
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.view {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .action-btn.edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .pagination-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            color: var(--dark-gray);
            border: 2px solid #e5e7eb;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }

        .pagination-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .pagination-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .pagination-info {
            color: var(--medium-gray);
            font-size: 14px;
            margin: 0 20px;
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
            
            .search-box {
                flex-direction: column;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .students-table-container {
                padding: 20px;
            }
            
            .table-header {
                flex-direction: column;
                align-items: flex-start;
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
            
            .search-filter,
            .students-table-container {
                padding: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .action-btn {
                width: 32px;
                height: 32px;
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
                <a href="pengurusan-pelajar.html" class="nav-item active">
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
            <a href="pengurusan-pelajar.html" class="sidebar-item active">
                <i class="fas fa-user-graduate"></i>
                Pengurusan Pelajar
                <span class="badge">245</span>
            </a>
            <a href="mata-pelajaran.html" class="sidebar-item">
                <i class="fas fa-book"></i>
                Mata Pelajaran
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
                <h2>Pengurusan Pelajar 📋</h2>
                <p>Urus dan kelola maklumat pelajar sekolah rendah</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" onclick="tambahPelajar()">
                    <i class="fas fa-user-plus"></i>
                    Tambah Pelajar Baru
                </button>
                <button class="btn btn-secondary" onclick="muatSemula()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Cari pelajar dengan nama atau ID..." id="searchInput">
                <button class="btn btn-primary" onclick="cariPelajar()">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterTahun" onchange="filterPelajar()">
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
                    <select class="filter-select" id="filterKelas" onchange="filterPelajar()">
                        <option value="">Semua Kelas</option>
                        <option value="A">Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="filterStatus" onchange="filterPelajar()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jantina</label>
                    <select class="filter-select" id="filterJantina" onchange="filterPelajar()">
                        <option value="">Semua Jantina</option>
                        <option value="L">Lelaki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="students-table-container">
            <div class="table-header">
                <h3>Senarai Pelajar (245 pelajar)</h3>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="eksportData()">
                        <i class="fas fa-download"></i>
                        Eksport
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID PELAJAR</th>
                            <th>NAMA PELAJAR</th>
                            <th>TAHUN/KELAS</th>
                            <th>JANTINA</th>
                            <th>NO. KP</th>
                            <th>STATUS</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn" onclick="changePage(2)">2</button>
                <button class="pagination-btn" onclick="changePage(3)">3</button>
                <button class="pagination-btn" onclick="changePage(4)">4</button>
                <button class="pagination-btn" onclick="changePage(5)">5</button>
                <span class="pagination-info">Muka surat 1 daripada 8</span>
                <button class="pagination-btn" onclick="changePage('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const studentsTableBody = document.getElementById('studentsTableBody');

        // Sample data pelajar sekolah rendah
        const pelajarData = [
            { id: 'P001', nama: 'AHMAD BIN ALI', tahun: '1', kelas: 'A', jantina: 'L', nokp: '180101010101', status: 'active' },
            { id: 'P002', nama: 'SITI BINTI ABU', tahun: '1', kelas: 'A', jantina: 'P', nokp: '180102020202', status: 'active' },
            { id: 'P003', nama: 'MOHD AMIR BIN HASSAN', tahun: '1', kelas: 'B', jantina: 'L', nokp: '180103030303', status: 'active' },
            { id: 'P004', nama: 'NOR AISYAH BINTI RAMLI', tahun: '2', kelas: 'A', jantina: 'P', nokp: '170201010101', status: 'active' },
            { id: 'P005', nama: 'ALI BIN KASSIM', tahun: '2', kelas: 'B', jantina: 'L', nokp: '170202020202', status: 'active' },
            { id: 'P006', nama: 'FATIMAH BINTI ZAINAL', tahun: '3', kelas: 'A', jantina: 'P', nokp: '160301010101', status: 'active' },
            { id: 'P007', nama: 'WAN AHMAD BIN WAN', tahun: '3', kelas: 'B', jantina: 'L', nokp: '160302020202', status: 'inactive' },
            { id: 'P008', nama: 'NURUL HIDAYAH BINTI KAMAL', tahun: '4', kelas: 'A', jantina: 'P', nokp: '150401010101', status: 'active' },
            { id: 'P009', nama: 'HASAN BIN HUSIN', tahun: '4', kelas: 'B', jantina: 'L', nokp: '150402020202', status: 'active' },
            { id: 'P010', nama: 'AMIRAH BINTI ISMAIL', tahun: '5', kelas: 'A', jantina: 'P', nokp: '140501010101', status: 'active' },
            { id: 'P011', nama: 'ZULKIFLI BIN ZAINAL', tahun: '5', kelas: 'B', jantina: 'L', nokp: '140502020202', status: 'active' },
            { id: 'P012', nama: 'ROHAYU BINTI RAHIM', tahun: '6', kelas: 'A', jantina: 'P', nokp: '130601010101', status: 'active' },
            { id: 'P013', nama: 'FAIZ BIN FARID', tahun: '6', kelas: 'B', jantina: 'L', nokp: '130602020202', status: 'inactive' },
            { id: 'P014', nama: 'AINA BINTI ADNAN', tahun: '1', kelas: 'C', jantina: 'P', nokp: '180104040404', status: 'active' },
            { id: 'P015', nama: 'HAKIM BIN HALIM', tahun: '2', kelas: 'C', jantina: 'L', nokp: '170203030303', status: 'active' }
        ];

        // Populate students table
        function populateStudentsTable(data = pelajarData) {
            studentsTableBody.innerHTML = data.map(pelajar => `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${pelajar.nama.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${pelajar.id}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${formatNoKP(pelajar.nokp)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${pelajar.nama}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--primary);">Tahun ${pelajar.tahun}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">Kelas ${pelajar.kelas}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: ${pelajar.jantina === 'L' ? '#4f46e5' : '#f59e0b'};"></div>
                            <span>${pelajar.jantina === 'L' ? 'Lelaki' : 'Perempuan'}</span>
                        </div>
                    </td>
                    <td>${formatNoKP(pelajar.nokp)}</td>
                    <td>
                        <span class="status-badge ${pelajar.status === 'active' ? 'status-active' : 'status-inactive'}">
                            ${pelajar.status === 'active' ? 'AKTIF' : 'TIDAK AKTIF'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" title="Lihat" onclick="lihatPelajar('${pelajar.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" title="Edit" onclick="editPelajar('${pelajar.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" title="Padam" onclick="padamPelajar('${pelajar.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Format No. KP
        function formatNoKP(nokp) {
            return nokp.replace(/(\d{6})(\d{2})(\d{4})/, '$1-$2-$3');
        }

        // Filter pelajar
        function filterPelajar() {
            const tahun = document.getElementById('filterTahun').value;
            const kelas = document.getElementById('filterKelas').value;
            const status = document.getElementById('filterStatus').value;
            const jantina = document.getElementById('filterJantina').value;
            
            let filteredData = pelajarData;
            
            if (tahun) {
                filteredData = filteredData.filter(p => p.tahun === tahun);
            }
            
            if (kelas) {
                filteredData = filteredData.filter(p => p.kelas === kelas);
            }
            
            if (status) {
                filteredData = filteredData.filter(p => p.status === status);
            }
            
            if (jantina) {
                filteredData = filteredData.filter(p => p.jantina === jantina);
            }
            
            populateStudentsTable(filteredData);
        }

        // Search pelajar
        function cariPelajar() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            if (!searchTerm) {
                populateStudentsTable();
                return;
            }
            
            const filteredData = pelajarData.filter(pelajar => 
                pelajar.nama.toLowerCase().includes(searchTerm) || 
                pelajar.id.toLowerCase().includes(searchTerm) ||
                pelajar.nokp.includes(searchTerm)
            );
            
            populateStudentsTable(filteredData);
        }

        // Actions functions
        function lihatPelajar(id) {
            alert(`Lihat maklumat pelajar: ${id}`);
            // Boleh redirect ke halaman detail
        }

        function editPelajar(id) {
            alert(`Edit maklumat pelajar: ${id}`);
            // Boleh redirect ke halaman edit
        }

        function padamPelajar(id) {
            if (confirm(`Adakah anda pasti ingin memadam pelajar ${id}?`)) {
                alert(`Pelajar ${id} telah dipadam (simulasi)`);
                // Dalam realiti, buat API call untuk delete
            }
        }

        function tambahPelajar() {
            alert('Buka modal/form untuk tambah pelajar baru');
            // Boleh buka modal atau redirect ke halaman tambah
        }

        function muatSemula() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterKelas').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterJantina').value = '';
            populateStudentsTable();
        }

        function eksportData() {
            alert('Data pelajar sedang dieksport...');
            // Dalam realiti, buat API call untuk export
        }

        function changePage(page) {
            alert(`Pergi ke muka surat ${page}`);
            // Dalam realiti, handle pagination
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

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Populate initial data
            populateStudentsTable();
            
            // Add search input event listener
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    cariPelajar();
                }
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
        });
    </script>
</body>
</html>