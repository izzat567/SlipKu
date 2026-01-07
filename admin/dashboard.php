<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Pemuka Admin - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Dashboard Header */
        .dashboard-header {
            margin-bottom: 30px;
        }

        .dashboard-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .dashboard-header p {
            color: var(--medium-gray);
            font-size: 16px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }

        .stat-info h3 {
            font-size: 14px;
            color: var(--medium-gray);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--dark-gray);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            background: var(--primary-light);
            color: var(--primary);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        /* Charts Section */
        .charts-section {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 40px;
        }

        .chart-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .chart-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-select {
            background: var(--white);
            border: 2px solid #e5e7eb;
            color: var(--dark-gray);
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .chart-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Activity Section */
        .activity-section {
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
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: var(--transition);
        }

        .view-all:hover {
            gap: 10px;
        }

        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            border-radius: 16px;
            background: var(--light-gray);
            transition: var(--transition);
        }

        .activity-item:hover {
            background: var(--primary-light);
            transform: translateX(5px);
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .activity-content h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .activity-content p {
            color: var(--medium-gray);
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .activity-time {
            color: var(--medium-gray);
            font-size: 12px;
            font-weight: 500;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .action-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
            border: 2px solid transparent;
        }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .action-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
        }

        .action-card h3 {
            font-size: 17px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .action-card p {
            color: var(--medium-gray);
            font-size: 14px;
            line-height: 1.4;
        }

        /* Recent Students Table */
        .recent-students {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
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

        .status-completed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
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

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
            flex-direction: column;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid transparent;
            border-top: 4px solid var(--primary);
            border-right: 4px solid var(--secondary);
            border-bottom: 4px solid var(--primary);
            border-left: 4px solid var(--secondary);
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
                border-top-color: var(--primary);
                border-right-color: var(--secondary);
            }
            50% {
                border-top-color: var(--secondary);
                border-right-color: var(--primary);
            }
            100% {
                transform: rotate(360deg);
                border-top-color: var(--primary);
                border-right-color: var(--secondary);
            }
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 100px;
            right: 30px;
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 15px;
            z-index: 2000;
            transform: translateX(150%);
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 5px solid var(--success);
            max-width: 350px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
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
        @media (max-width: 1200px) {
            .charts-section {
                grid-template-columns: 1fr;
            }
        }

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
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .dashboard-header h2 {
                font-size: 24px;
            }
            
            .dashboard-header p {
                font-size: 14px;
            }
            
            .stat-card,
            .chart-container,
            .activity-section,
            .recent-students {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 28px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 13px;
            }
            
            .notification {
                top: 80px;
                right: 20px;
                left: 20px;
                max-width: none;
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
            
            .stat-card,
            .chart-container,
            .activity-section,
            .recent-students {
                padding: 15px;
            }
            
            .activity-item {
                padding: 15px;
                gap: 15px;
            }
            
            .action-card {
                padding: 20px;
            }
            
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .chart-actions {
                width: 100%;
            }
            
            .chart-select {
                flex: 1;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .sidebar {
                width: 280px;
            }
        }

        @media (max-width: 400px) {
            .header-container {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .logo {
                flex: 1;
            }
            
            .menu-toggle {
                order: 1;
            }
            
            .user-profile {
                order: 2;
            }
            
            .stat-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .stat-icon {
                align-self: flex-end;
            }
            
            .activity-content h4 {
                font-size: 14px;
            }
            
            .activity-content p {
                font-size: 12px;
            }
        }

        /* Touch Device Optimizations */
        @media (hover: none) and (pointer: coarse) {
            .sidebar-item:hover {
                transform: none;
                background: var(--light-gray);
            }
            
            .stat-card:hover {
                transform: none;
            }
            
            .action-card:hover {
                transform: none;
            }
            
            .activity-item:hover {
                transform: none;
            }
            
            .btn:hover {
                transform: none;
            }
            
            /* Increase tap target sizes */
            .sidebar-item {
                padding: 15px 20px;
                margin: 8px 0;
            }
            
            .btn {
                min-height: 44px;
                padding: 12px 20px;
            }
            
            .nav-item {
                min-height: 44px;
            }
            
            .menu-toggle {
                min-height: 44px;
                min-width: 44px;
            }
            
            /* Prevent text selection on tap */
            .sidebar-item,
            .btn,
            .nav-item,
            .menu-toggle {
                -webkit-tap-highlight-color: transparent;
                user-select: none;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div style="color: var(--dark-gray); font-size: 18px; font-weight: 600;">Memuatkan papan pemuka...</div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <div style="font-weight: 600; color: var(--dark-gray);">Berjaya!</div>
            <div style="font-size: 14px; color: var(--medium-gray);" id="notificationMessage">Data telah dikemaskini dengan jayanya</div>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Mobile Menu Toggle -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo -->
            <a href="#" class="logo">
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
                <a href="#" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    Papan Pemuka
                </a>
                <a href="./modules/pengurusan-pelajar.php" class="nav-item">
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
            <a href="#" class="sidebar-item active">
                <i class="fas fa-tachometer-alt"></i>
                Papan Pemuka
                <span class="badge">5</span>
            </a>
            <a href="./modules/pengurusan-pelajar.php" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pengurusan Pelajar
            </a>
            <a href="./modules/mata-pelajaran.php" class="sidebar-item">
                <i class="fas fa-book"></i>
                Mata Pelajaran
            </a>
            <a href="./modules/analisis-prestasi.php" class="sidebar-item">
                <i class="fas fa-chart-line"></i>
                Analisis Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Peperiksaan</div>
            <a href="#" class="sidebar-item">
                <i class="fas fa-plus-circle"></i>
                Tambah Markah
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-edit"></i>
                Kemaskini Markah
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-file-export"></i>
                Hasilkan Laporan
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-file-alt"></i>
                Semua Rekod
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Sistem</div>
            <a href="#" class="sidebar-item">
                <i class="fas fa-school"></i>
                Kelas & Tahun
                <span class="badge">12</span>
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-calendar-alt"></i>
                Jadual Ujian
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-question-circle"></i>
                Bantuan
            </a>
            <a href="#" class="sidebar-item" style="color: var(--danger);">
                <i class="fas fa-sign-out-alt"></i>
                Log Keluar
            </a>
        </div>

        <!-- Mobile Footer -->
        <div class="sidebar-section" style="margin-top: auto; padding-top: 20px; border-top: 1px solid #e5e7eb; display: none;" id="mobileSidebarFooter">
            <div style="text-align: center; color: var(--medium-gray); font-size: 12px; padding: 15px;">
                <p>Sekolah Kebangsaan Rantau Panjang</p>
                <p>Tahun 1-6 • Versi 2.1.0</p>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h2>Selamat kembali, Cikgu! 👋</h2>
            <p>Berikut adalah ringkasan pengurusan peperiksaan untuk hari ini</p>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <!-- Total Students -->
            <div class="stat-card" style="animation-delay: 0.1s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>JUMLAH PELAJAR</h3>
                        <div class="stat-value" id="totalStudents">245</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8 dari bulan lepas</span>
                </div>
            </div>

            <!-- Completed Marks -->
            <div class="stat-card" style="animation-delay: 0.2s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>MARKAH LENGKAP</h3>
                        <div class="stat-value" id="completedMarks">198</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>81% telah lengkap</span>
                </div>
            </div>

            <!-- Pending Marks -->
            <div class="stat-card" style="animation-delay: 0.3s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>MARKAH TERTUNGGU</h3>
                        <div class="stat-value" id="pendingMarks">47</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>19% perlu perhatian</span>
                </div>
            </div>

            <!-- Average Marks -->
            <div class="stat-card" style="animation-delay: 0.4s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>PURATA MARKAH</h3>
                        <div class="stat-value" id="averageMarks">76.8</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+1.5 dari penggal lepas</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <!-- Performance Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Prestasi Keseluruhan</h3>
                    <div class="chart-actions">
                        <select class="chart-select" id="chartRange">
                            <option>7 Hari Lalu</option>
                            <option>30 Hari Lalu</option>
                            <option>Penggal Ini</option>
                        </select>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Subject Distribution -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Taburan Mata Pelajaran</h3>
                </div>
                <div style="height: 300px;">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="section-header">
                <h3>Aktiviti Terkini</h3>
                <a href="#" class="view-all">
                    Lihat Semua
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dimasukkan markah Matematik</h4>
                        <p>ID Pelajar: P001 • Markah: 85/100</p>
                        <div class="activity-time">2 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dikemaskini markah Bahasa Melayu</h4>
                        <p>ID Pelajar: P045 • Markah: 92/100</p>
                        <div class="activity-time">4 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dihasilkan laporan prestasi</h4>
                        <p>Kelas 6A ringkasan penggal</p>
                        <div class="activity-time">Semalam</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3>Tambah Markah Baru</h3>
                <p>Masukkan markah dan gred peperiksaan pelajar</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h3>Lihat Semua Rekod</h3>
                <p>Semak pangkalan data markah pelajar</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Kemaskini Rekod</h3>
                <p>Ubah suai markah dan maklumat pelajar sedia ada</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h3>Hasilkan Laporan</h3>
                <p>Cipta laporan peperiksaan menyeluruh</p>
            </a>
        </div>

        <!-- Recent Students Table -->
        <div class="recent-students">
            <div class="section-header">
                <h3>Kemaskini Pelajar Terkini</h3>
                <button class="btn btn-secondary" onclick="refreshStudents()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID PELAJAR</th>
                            <th>NAMA</th>
                            <th>MATA PELAJARAN</th>
                            <th>MARKAH</th>
                            <th>STATUS</th>
                            <th>TARIKH</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTable">
                        <!-- Table akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Quick Actions (Bottom) -->
        <div class="quick-actions" style="display: none;" id="mobileQuickActions">
            <button class="btn btn-primary" onclick="showNotification('Tindakan berjaya!')">
                <i class="fas fa-plus"></i>
                Tambah Markah
            </button>
            <button class="btn btn-secondary" onclick="refreshStudents()">
                <i class="fas fa-sync-alt"></i>
                Muat Semula
            </button>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const mobileSidebarFooter = document.getElementById('mobileSidebarFooter');
        const mobileQuickActions = document.getElementById('mobileQuickActions');

        // Initialize Charts
        let performanceChart, subjectChart;

        function initializeCharts() {
            // Performance Chart - Mata Pelajaran Sekolah Rendah Malaysia
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            performanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: ['Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat', 'Sabtu', 'Ahad'],
                    datasets: [{
                        label: 'Purata Markah',
                        data: [75, 78, 82, 80, 85, 83, 88],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
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
                            max: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#6b7280',
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });

            // Subject Distribution Chart - Mata Pelajaran Sekolah Rendah
            const subjectCtx = document.getElementById('subjectChart').getContext('2d');
            subjectChart = new Chart(subjectCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Matematik', 'Sains', 'BM', 'BI', 'Pendidikan Islam', 'Sejarah', 'PJK'],
                    datasets: [{
                        data: [25, 20, 18, 15, 10, 7, 5],
                        backgroundColor: [
                            '#4f46e5',
                            '#7c3aed',
                            '#10b981',
                            '#f59e0b',
                            '#3b82f6',
                            '#ef4444',
                            '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#6b7280',
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }

        // Populate Students Table - Data Sekolah Rendah
        function populateStudentsTable() {
            const students = [
                { id: 'P001', name: 'AHMAD BIN ALI', subject: 'Matematik', score: '85/100', status: 'LENGKAP', date: '2024-01-15' },
                { id: 'P002', name: 'SITI BINTI ABU', subject: 'Sains', score: '92/100', status: 'LENGKAP', date: '2024-01-14' },
                { id: 'P003', name: 'MOHD AMIR BIN HASSAN', subject: 'Bahasa Melayu', score: '78/100', status: 'TERTUNGGU', date: '2024-01-14' },
                { id: 'P004', name: 'NOR AISYAH BINTI RAMLI', subject: 'Bahasa Inggeris', score: '88/100', status: 'LENGKAP', date: '2024-01-13' },
                { id: 'P005', name: 'ALI BIN KASSIM', subject: 'Pendidikan Islam', score: '95/100', status: 'LENGKAP', date: '2024-01-13' },
                { id: 'P006', name: 'FATIMAH BINTI ZAINAL', subject: 'Sejarah', score: '76/100', status: 'TERTUNGGU', date: '2024-01-12' },
                { id: 'P007', name: 'WAN AHMAD BIN WAN', subject: 'PJK', score: '89/100', status: 'LENGKAP', date: '2024-01-12' }
            ];

            const tableBody = document.getElementById('studentsTable');
            tableBody.innerHTML = students.map(student => `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${student.name.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${student.id}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${student.name.includes('BINTI') ? 'Perempuan' : 'Lelaki'}</div>
                            </div>
                        </div>
                    </td>
                    <td>${student.name}</td>
                    <td>${student.subject}</td>
                    <td>
                        <div style="font-weight: 700; ${parseInt(student.score) >= 90 ? 'color: #10b981' : parseInt(student.score) >= 80 ? 'color: #4f46e5' : 'color: #ef4444'}">
                            ${student.score}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge ${student.status === 'LENGKAP' ? 'status-completed' : 'status-pending'}">
                            ${student.status}
                        </span>
                    </td>
                    <td>${student.date}</td>
                </tr>
            `).join('');
        }

        // Animate Statistics Counters
        function animateStatistics() {
            const counters = [
                { element: 'totalStudents', target: 245 },
                { element: 'completedMarks', target: 198 },
                { element: 'pendingMarks', target: 47 }
            ];

            counters.forEach(counter => {
                const element = document.getElementById(counter.element);
                const duration = 1500;
                const step = 20;
                const increment = counter.target / (duration / step);
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= counter.target) {
                        element.textContent = counter.target;
                        clearInterval(timer);
                    } else {
                        element.textContent = Math.floor(current);
                    }
                }, step);
            });

            // Animate purata markah
            const avgElement = document.getElementById('averageMarks');
            const avgTarget = 76.8;
            const avgDuration = 1500;
            const avgStep = 20;
            const avgIncrement = avgTarget / (avgDuration / avgStep);
            let avgCurrent = 0;

            const avgTimer = setInterval(() => {
                avgCurrent += avgIncrement;
                if (avgCurrent >= avgTarget) {
                    avgElement.textContent = avgTarget.toFixed(1);
                    clearInterval(avgTimer);
                } else {
                    avgElement.textContent = avgCurrent.toFixed(1);
                }
            }, avgStep);
        }

        // Show Notification
        function showNotification(message = 'Data telah dikemaskini dengan jayanya') {
            const notification = document.getElementById('notification');
            const messageDiv = document.getElementById('notificationMessage');
            messageDiv.textContent = message;
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Refresh Students Table
        function refreshStudents() {
            const refreshBtn = event.target.closest('button');
            if (refreshBtn) {
                const icon = refreshBtn.querySelector('i');
                icon.classList.add('fa-spin');
                refreshBtn.disabled = true;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Simulate API call delay
            setTimeout(() => {
                // Repopulate table
                populateStudentsTable();
                
                // Remove spinning animation
                if (refreshBtn) {
                    const icon = refreshBtn.querySelector('i');
                    icon.classList.remove('fa-spin');
                    refreshBtn.disabled = false;
                }
                
                // Hide loading overlay
                document.getElementById('loadingOverlay').style.display = 'none';
                
                // Show success notification
                showNotification('Data pelajar telah dimuat semula');
            }, 1000);
        }

        // Update Statistics in Real-time
        function updateStatistics() {
            setInterval(() => {
                const total = document.getElementById('totalStudents');
                const completed = document.getElementById('completedMarks');
                
                // Simulate small changes
                const newTotal = parseInt(total.textContent) + (Math.random() > 0.5 ? 1 : 0);
                const newCompleted = Math.min(newTotal, parseInt(completed.textContent) + (Math.random() > 0.3 ? 1 : 0));
                const newPending = newTotal - newCompleted;
                const newAverage = (parseFloat(document.getElementById('averageMarks').textContent) + (Math.random() - 0.5)).toFixed(1);
                
                // Update with animation
                total.textContent = newTotal;
                completed.textContent = newCompleted;
                document.getElementById('pendingMarks').textContent = newPending;
                document.getElementById('averageMarks').textContent = newAverage;
            }, 30000); // Update setiap 30 saat
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

        // Check Mobile Device and Adjust Layout
        function checkMobileLayout() {
            if (window.innerWidth <= 768) {
                // Show mobile sidebar footer
                mobileSidebarFooter.style.display = 'block';
                
                // Show mobile quick actions
                mobileQuickActions.style.display = 'grid';
                
                // Adjust chart options for mobile
                if (performanceChart) {
                    performanceChart.options.plugins.legend.display = false;
                    performanceChart.update();
                }
                
                if (subjectChart) {
                    subjectChart.options.plugins.legend.position = 'bottom';
                    subjectChart.options.plugins.legend.labels.font.size = 10;
                    subjectChart.update();
                }
            } else {
                // Hide mobile sidebar footer
                mobileSidebarFooter.style.display = 'none';
                
                // Hide mobile quick actions
                mobileQuickActions.style.display = 'none';
                
                // Restore chart options for desktop
                if (performanceChart) {
                    performanceChart.options.plugins.legend.display = true;
                    performanceChart.update();
                }
                
                if (subjectChart) {
                    subjectChart.options.plugins.legend.position = 'right';
                    subjectChart.options.plugins.legend.labels.font.size = 11;
                    subjectChart.update();
                }
            }
        }

        // Initialize Dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts
            initializeCharts();
            
            // Populate students table
            populateStudentsTable();
            
            // Animate statistics
            animateStatistics();
            
            // Start real-time updates
            updateStatistics();
            
            // Check initial layout
            checkMobileLayout();
            
            // Set up event listeners
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Add user profile click event
            document.getElementById('userProfile').addEventListener('click', function() {
                showNotification('Profil cikgu akan dibuka');
            });
            
            // Add event listener to chart range selector
            document.getElementById('chartRange').addEventListener('change', function() {
                showNotification('Data carta dikemaskini untuk ' + this.value);
            });
            
            // Hide loading overlay after 1.5 seconds
            setTimeout(() => {
                document.getElementById('loadingOverlay').style.display = 'none';
            }, 1500);
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                checkMobileLayout();
                closeSidebar();
            });
            
            // Prevent body scroll when sidebar is open on mobile
            sidebar.addEventListener('touchmove', function(e) {
                if (window.innerWidth <= 1024) {
                    e.preventDefault();
                }
            }, { passive: false });
            
            // Add swipe to close sidebar on mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            sidebar.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            sidebar.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 100) { // Swipe left
                    closeSidebar();
                }
            });
            
            // Set current date and time
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update setiap minit
        });
        
        // Update Date and Time
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const dateTimeStr = now.toLocaleDateString('ms-MY', options);
            
            // Update dashboard header
            const dashboardP = document.querySelector('.dashboard-header p');
            if (dashboardP) {
                dashboardP.textContent = `Berikut adalah ringkasan pengurusan peperiksaan untuk ${dateTimeStr}`;
            }
        }
    </script>
</body>
</html>