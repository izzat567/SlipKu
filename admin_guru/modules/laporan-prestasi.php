<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Prestasi - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN yang lebih stabil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        .filter-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: var(--primary);
        }

        .filter-options {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
            white-space: nowrap;
        }

        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
            min-width: 150px;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Report Cards */
        .report-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .report-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .report-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .report-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .report-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            flex-shrink: 0;
        }

        .report-icon.overall {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .report-icon.trend {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .report-icon.comparison {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .report-icon.analysis {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .report-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--medium-gray);
            margin-bottom: 5px;
        }

        .report-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .report-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            font-weight: 600;
        }

        .trend-up {
            color: var(--success);
        }

        .trend-down {
            color: var(--danger);
        }

        .trend-neutral {
            color: var(--medium-gray);
        }

        /* Chart Container */
        .chart-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-title i {
            color: var(--info);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        /* Performance Table */
        .performance-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .performance-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .performance-table th {
            background: var(--light-gray);
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            position: sticky;
            top: 0;
        }

        .performance-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .performance-table tr:hover td {
            background: var(--primary-light);
        }

        /* Rank Badge */
        .rank-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 700;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .rank-2 {
            background: linear-gradient(135deg, #d1d5db, #9ca3af);
            color: white;
        }

        .rank-3 {
            background: linear-gradient(135deg, #fcd34d, #fbbf24);
            color: white;
        }

        .rank-other {
            background: var(--light-gray);
            color: var(--medium-gray);
        }

        /* Student Row */
        .student-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
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
            flex-shrink: 0;
        }

        .student-info h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-info p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Performance Indicator */
        .performance-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .performance-bar {
            flex: 1;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .performance-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .performance-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--dark-gray);
            min-width: 40px;
            text-align: right;
        }

        /* Grade Badge */
        .grade-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 60px;
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

        .grade-e {
            background: rgba(107, 114, 128, 0.1);
            color: var(--medium-gray);
        }

        /* Subject Comparison */
        .subject-comparison {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .subject-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .subject-item {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
        }

        .subject-item:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }

        .subject-item-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin: 0 auto 15px;
        }

        .subject-item-icon.math {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .subject-item-icon.science {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .subject-item-icon.bm {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .subject-item-icon.bi {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .subject-item-icon.pj {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .subject-item h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .subject-item p {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn.primary {
            background: var(--primary);
            color: white;
        }

        .action-btn.primary:hover {
            background: var(--primary-dark);
        }

        .action-btn.success {
            background: var(--success);
            color: white;
        }

        .action-btn.success:hover {
            background: #0da271;
        }

        .action-btn.info {
            background: var(--info);
            color: white;
        }

        .action-btn.info:hover {
            background: #2563eb;
        }

        .action-btn.warning {
            background: var(--warning);
            color: white;
        }

        .action-btn.warning:hover {
            background: #d97706;
        }

        .action-btn.danger {
            background: var(--danger);
            color: white;
        }

        .action-btn.danger:hover {
            background: #dc2626;
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

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 12px 24px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .tab-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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
            
            .report-cards {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
            
            .filter-options {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-select {
                flex: 1;
            }
            
            .report-cards {
                grid-template-columns: 1fr;
            }
            
            .subject-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
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
            
            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .subject-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Fallback icons for Font Awesome issues */
        .fa-fallback {
            font-family: "Segoe UI Emoji", "Apple Color Emoji", sans-serif;
            font-style: normal;
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
                    <p>Laporan Prestasi</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="top-nav">
                <a href="dashboard-admin.html" class="nav-item">
                    <i class="fas fa-home"></i>
                    Utama
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-bell"></i>
                    Pemberitahuan
                    <span class="notification-badge">3</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    Mesej
                    <span class="notification-badge">2</span>
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
            <a href="dashboard-admin.html" class="sidebar-item">
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
            <a href="laporan-prestasi.html" class="sidebar-item active">
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
                <span class="badge">7</span>
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
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Laporan Prestasi 📊</h2>
                <p>Analisis dan laporan prestasi akademik pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaLaporan()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="janaLaporanPenuh()">
                    <i class="fas fa-file-pdf"></i>
                    Jana Laporan
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="ubahTab('overview')">
                <i class="fas fa-chart-bar"></i>
                Gambaran Keseluruhan
            </button>
            <button class="tab-btn" onclick="ubahTab('comparison')">
                <i class="fas fa-chart-line"></i>
                Perbandingan
            </button>
            <button class="tab-btn" onclick="ubahTab('detailed')">
                <i class="fas fa-table"></i>
                Terperinci
            </button>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Tapis Laporan
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="tapilkanData()">
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                        <option value="all">Semua Kelas</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Semester:</label>
                    <select class="filter-select" id="filterSemester" onchange="tapilkanData()">
                        <option value="1">Semester 1</option>
                        <option value="2" selected>Semester 2</option>
                        <option value="all">Keseluruhan Tahun</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Jenis Penilaian:</label>
                    <select class="filter-select" id="filterAssessment" onchange="tapilkanData()">
                        <option value="all">Semua Penilaian</option>
                        <option value="exam">Peperiksaan Akhir</option>
                        <option value="midterm">Peperiksaan Pertengahan</option>
                        <option value="quiz">Kuiz</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="tapilkanData()">
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Overview Tab -->
        <div id="overviewTab">
            <!-- Report Cards -->
            <div class="report-cards">
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon overall">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="report-info">
                            <h3>Purata Keseluruhan</h3>
                            <div class="report-value" id="overallAverage">78.4%</div>
                            <div class="report-trend">
                                <i class="fas fa-arrow-up trend-up"></i>
                                <span>+5.2% dari semester lepas</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon trend">
                            <i class="fas fa-trend-up"></i>
                        </div>
                        <div class="report-info">
                            <h3>Trend Prestasi</h3>
                            <div class="report-value" id="performanceTrend">Meningkat</div>
                            <div class="report-trend">
                                <i class="fas fa-chart-line trend-up"></i>
                                <span>62% pelajar menunjukkan peningkatan</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon comparison">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="report-info">
                            <h3>Perbandingan Kelas</h3>
                            <div class="report-value" id="classComparison">Kedudukan 1</div>
                            <div class="report-trend">
                                <i class="fas fa-trophy trend-up"></i>
                                <span>Terbaik antara 4 kelas</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon analysis">
                            <i class="fas fa-analytics"></i>
                        </div>
                        <div class="report-info">
                            <h3>Analisis Prestasi</h3>
                            <div class="report-value" id="performanceAnalysis">Stabil</div>
                            <div class="report-trend">
                                <i class="fas fa-check-circle trend-up"></i>
                                <span>88% pelajar mencapai sasaran</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        <span>Prestasi Mengikut Subjek</span>
                    </div>
                    <div class="chart-actions">
                        <button class="action-btn primary" onclick="ubahJenisGraf('bar')">
                            <i class="fas fa-chart-bar"></i>
                            Bar
                        </button>
                        <button class="action-btn info" onclick="ubahJenisGraf('line')">
                            <i class="fas fa-chart-line"></i>
                            Line
                        </button>
                        <button class="action-btn warning" onclick="ubahJenisGraf('pie')">
                            <i class="fas fa-chart-pie"></i>
                            Pie
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Subject Comparison -->
            <div class="subject-comparison">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-balance-scale"></i>
                        <span>Perbandingan Prestasi Subjek</span>
                    </div>
                </div>
                
                <div class="subject-grid">
                    <div class="subject-item" onclick="tunjukkanAnalisisSubjek('matematik')">
                        <div class="subject-item-icon math">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h4>Matematik</h4>
                        <p>78.4%</p>
                        <div class="performance-indicator">
                            <div class="performance-bar">
                                <div class="performance-fill" style="width: 78.4%"></div>
                            </div>
                            <div class="performance-value">78.4%</div>
                        </div>
                    </div>
                    
                    <div class="subject-item" onclick="tunjukkanAnalisisSubjek('sains')">
                        <div class="subject-item-icon science">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h4>Sains</h4>
                        <p>73.5%</p>
                        <div class="performance-indicator">
                            <div class="performance-bar">
                                <div class="performance-fill" style="width: 73.5%"></div>
                            </div>
                            <div class="performance-value">73.5%</div>
                        </div>
                    </div>
                    
                    <div class="subject-item" onclick="tunjukkanAnalisisSubjek('bahasa_melayu')">
                        <div class="subject-item-icon bm">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4>Bahasa Melayu</h4>
                        <p>81.3%</p>
                        <div class="performance-indicator">
                            <div class="performance-bar">
                                <div class="performance-fill" style="width: 81.3%"></div>
                            </div>
                            <div class="performance-value">81.3%</div>
                        </div>
                    </div>
                    
                    <div class="subject-item" onclick="tunjukkanAnalisisSubjek('bahasa_inggeris')">
                        <div class="subject-item-icon bi">
                            <i class="fas fa-language"></i>
                        </div>
                        <h4>Bahasa Inggeris</h4>
                        <p>71.4%</p>
                        <div class="performance-indicator">
                            <div class="performance-bar">
                                <div class="performance-fill" style="width: 71.4%"></div>
                            </div>
                            <div class="performance-value">71.4%</div>
                        </div>
                    </div>
                    
                    <div class="subject-item" onclick="tunjukkanAnalisisSubjek('pj')">
                        <div class="subject-item-icon pj">
                            <i class="fas fa-running"></i>
                        </div>
                        <h4>PJ & Kesihatan</h4>
                        <p>85.3%</p>
                        <div class="performance-indicator">
                            <div class="performance-bar">
                                <div class="performance-fill" style="width: 85.3%"></div>
                            </div>
                            <div class="performance-value">85.3%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Performers Table -->
            <div class="performance-table-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-trophy"></i>
                        <span>10 Pelajar Terbaik</span>
                    </div>
                    <div class="chart-actions">
                        <button class="action-btn info" onclick="tunjukkanSemuaPelajar()">
                            <i class="fas fa-list"></i>
                            Lihat Semua
                        </button>
                        <button class="action-btn success" onclick="eksportSenaraiTerbaik()">
                            <i class="fas fa-download"></i>
                            Eksport
                        </button>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="performance-table">
                        <thead>
                            <tr>
                                <th>KED</th>
                                <th>PELAJAR</th>
                                <th>KELAS</th>
                                <th>MATEMATIK</th>
                                <th>SAINS</th>
                                <th>BAHASA MELAYU</th>
                                <th>BAHASA INGGERIS</th>
                                <th>PJ & KESIHATAN</th>
                                <th>PURATA</th>
                                <th>GRED</th>
                                <th>TREND</th>
                            </tr>
                        </thead>
                        <tbody id="topPerformersBody">
                            <!-- Top performers will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Comparison Tab -->
        <div id="comparisonTab" style="display: none;">
            <!-- Comparison Charts -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-line"></i>
                        <span>Perbandingan Prestasi Semester</span>
                    </div>
                    <div class="chart-actions">
                        <button class="action-btn primary" onclick="ubahPerbandingan('semester')">
                            <i class="fas fa-calendar-alt"></i>
                            Semester
                        </button>
                        <button class="action-btn info" onclick="ubahPerbandingan('tahun')">
                            <i class="fas fa-history"></i>
                            Tahun
                        </button>
                        <button class="action-btn warning" onclick="ubahPerbandingan('kelas')">
                            <i class="fas fa-users"></i>
                            Kelas
                        </button>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>

            <!-- Improvement Analysis -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        <span>Analisis Peningkatan Prestasi</span>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="improvementChart"></canvas>
                </div>
            </div>

            <!-- Class Comparison Table -->
            <div class="performance-table-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-users"></i>
                        <span>Perbandingan Prestasi Kelas</span>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="performance-table">
                        <thead>
                            <tr>
                                <th>KELAS</th>
                                <th>BIL. PELAJAR</th>
                                <th>PURATA KESELURUHAN</th>
                                <th>PELAJAR TERBAIK</th>
                                <th>PURATA TERBAIK</th>
                                <th>KEDUDUKAN</th>
                                <th>TREND</th>
                                <th>ANALISIS</th>
                            </tr>
                        </thead>
                        <tbody id="classComparisonBody">
                            <!-- Class comparison rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detailed Tab -->
        <div id="detailedTab" style="display: none;">
            <!-- Detailed Performance Table -->
            <div class="performance-table-container">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-table"></i>
                        <span>Laporan Prestasi Terperinci</span>
                    </div>
                    <div class="chart-actions">
                        <button class="action-btn primary" onclick="tambahKomenPrestasi()">
                            <i class="fas fa-comment"></i>
                            Tambah Komen
                        </button>
                        <button class="action-btn success" onclick="janaLaporanIndividu()">
                            <i class="fas fa-user-graduate"></i>
                            Laporan Individu
                        </button>
                        <button class="action-btn info" onclick="eksportLaporanPenuh()">
                            <i class="fas fa-file-export"></i>
                            Eksport Penuh
                        </button>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="performance-table">
                        <thead>
                            <tr>
                                <th>KED</th>
                                <th>PELAJAR</th>
                                <th>MATEMATIK</th>
                                <th>SAINS</th>
                                <th>BAHASA MELAYU</th>
                                <th>BAHASA INGGERIS</th>
                                <th>PJ & KESIHATAN</th>
                                <th>UJIAN 1</th>
                                <th>UJIAN 2</th>
                                <th>PEPERIKSAAN AKHIR</th>
                                <th>PURATA</th>
                                <th>GRED</th>
                                <th>ANALISIS</th>
                            </tr>
                        </thead>
                        <tbody id="detailedReportBody">
                            <!-- Detailed report rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="report-cards">
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon overall">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="report-info">
                            <h3>Taburan Gred</h3>
                            <div class="report-value" id="gradeDistribution">A: 25%</div>
                            <div class="report-trend">
                                <i class="fas fa-percentage"></i>
                                <span>A: 25%, B: 38%, C: 25%, D: 12%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon trend">
                            <i class="fas fa-trend-up"></i>
                        </div>
                        <div class="report-info">
                            <h3>Peningkatan Purata</h3>
                            <div class="report-value" id="averageImprovement">+5.2%</div>
                            <div class="report-trend">
                                <i class="fas fa-arrow-up trend-up"></i>
                                <span>Dari 73.2% ke 78.4%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon comparison">
                            <i class="fas fa-medal"></i>
                        </div>
                        <div class="report-info">
                            <h3>Pelajar Cemerlang</h3>
                            <div class="report-value" id="excellentStudents">3</div>
                            <div class="report-trend">
                                <i class="fas fa-star trend-up"></i>
                                <span>Purata ≥ 80%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="report-card">
                    <div class="report-card-header">
                        <div class="report-icon analysis">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="report-info">
                            <h3>Perlu Perhatian</h3>
                            <div class="report-value" id="attentionStudents">2</div>
                            <div class="report-trend">
                                <i class="fas fa-hand-paper trend-down"></i>
                                <span>Purata ≤ 50%</span>
                            </div>
                        </div>
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
        const overviewTab = document.getElementById('overviewTab');
        const comparisonTab = document.getElementById('comparisonTab');
        const detailedTab = document.getElementById('detailedTab');
        const topPerformersBody = document.getElementById('topPerformersBody');
        const classComparisonBody = document.getElementById('classComparisonBody');
        const detailedReportBody = document.getElementById('detailedReportBody');

        // Chart instances
        let performanceChart = null;
        let comparisonChart = null;
        let improvementChart = null;
        let currentChartType = 'bar';

        // Sample data
        const sampleStudents = [
            {
                id: 'STU001',
                name: 'Ahmad bin Ali',
                class: '6A',
                marks: {
                    matematik: 95,
                    sains: 88,
                    bahasa_melayu: 82,
                    bahasa_inggeris: 78,
                    pj: 90
                },
                assessments: {
                    ujian1: 92,
                    ujian2: 88,
                    peperiksaan: 94
                },
                average: 90.7,
                grade: 'A',
                rank: 1,
                trend: 'up',
                analysis: 'Cemerlang dalam semua subjek'
            },
            {
                id: 'STU002',
                name: 'Siti binti Abu',
                class: '6A',
                marks: {
                    matematik: 85,
                    sains: 72,
                    bahasa_melayu: 90,
                    bahasa_inggeris: 68,
                    pj: 88
                },
                assessments: {
                    ujian1: 80,
                    ujian2: 75,
                    peperiksaan: 82
                },
                average: 80.3,
                grade: 'B',
                rank: 3,
                trend: 'up',
                analysis: 'Baik, perlu tingkatkan Bahasa Inggeris'
            },
            {
                id: 'STU003',
                name: 'Muhammad bin Hassan',
                class: '6A',
                marks: {
                    matematik: 92,
                    sains: 85,
                    bahasa_melayu: 88,
                    bahasa_inggeris: 82,
                    pj: 95
                },
                assessments: {
                    ujian1: 90,
                    ujian2: 88,
                    peperiksaan: 91
                },
                average: 88.8,
                grade: 'A',
                rank: 2,
                trend: 'stable',
                analysis: 'Prestasi konsisten dan cemerlang'
            },
            {
                id: 'STU004',
                name: 'Aisyah binti Musa',
                class: '6A',
                marks: {
                    matematik: 68,
                    sains: 72,
                    bahasa_melayu: 85,
                    bahasa_inggeris: 65,
                    pj: 80
                },
                assessments: {
                    ujian1: 70,
                    ujian2: 65,
                    peperiksaan: 69
                },
                average: 69.8,
                grade: 'C',
                rank: 5,
                trend: 'up',
                analysis: 'Peningkatan baik, teruskan usaha'
            },
            {
                id: 'STU005',
                name: 'Ali bin Abdullah',
                class: '6A',
                marks: {
                    matematik: 79,
                    sains: 75,
                    bahasa_melayu: 82,
                    bahasa_inggeris: 70,
                    pj: 85
                },
                assessments: {
                    ujian1: 78,
                    ujian2: 75,
                    peperiksaan: 77
                },
                average: 76.8,
                grade: 'B',
                rank: 4,
                trend: 'down',
                analysis: 'Sedikit penurunan, perlu perhatian'
            },
            {
                id: 'STU006',
                name: 'Fatimah binti Omar',
                class: '6A',
                marks: {
                    matematik: 55,
                    sains: 48,
                    bahasa_melayu: 62,
                    bahasa_inggeris: 58,
                    pj: 70
                },
                assessments: {
                    ujian1: 52,
                    ujian2: 48,
                    peperiksaan: 53
                },
                average: 52.7,
                grade: 'E',
                rank: 8,
                trend: 'down',
                analysis: 'Perlu bimbingan intensif'
            },
            {
                id: 'STU007',
                name: 'Hassan bin Ismail',
                class: '6A',
                marks: {
                    matematik: 92,
                    sains: 90,
                    bahasa_melayu: 88,
                    bahasa_inggeris: 85,
                    pj: 94
                },
                assessments: {
                    ujian1: 90,
                    ujian2: 92,
                    peperiksaan: 93
                },
                average: 91.0,
                grade: 'A',
                rank: 1,
                trend: 'up',
                analysis: 'Pencapaian terbaik dalam kelas'
            },
            {
                id: 'STU008',
                name: 'Zainab binti Yusuf',
                class: '6A',
                marks: {
                    matematik: 61,
                    sains: 58,
                    bahasa_melayu: 72,
                    bahasa_inggeris: 65,
                    pj: 80
                },
                assessments: {
                    ujian1: 60,
                    ujian2: 58,
                    peperiksaan: 62
                },
                average: 60.2,
                grade: 'D',
                rank: 7,
                trend: 'stable',
                analysis: 'Prestasi sederhana, ada potensi'
            }
        ];

        const classData = [
            { class: '6A', students: 8, average: 78.4, topStudent: 'Hassan bin Ismail', topAverage: 91.0, rank: 1, trend: 'up', analysis: 'Kelas terbaik' },
            { class: '6B', students: 7, average: 72.3, topStudent: 'Ahmad bin Ibrahim', topAverage: 85.5, rank: 2, trend: 'stable', analysis: 'Prestasi baik' },
            { class: '5A', students: 8, average: 68.9, topStudent: 'Sarah binti Musa', topAverage: 82.0, rank: 3, trend: 'up', analysis: 'Meningkat baik' },
            { class: '5B', students: 6, average: 65.4, topStudent: 'Ali bin Hassan', topAverage: 78.5, rank: 4, trend: 'down', analysis: 'Perlu bimbingan' }
        ];

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Load initial data
            loadTopPerformers();
            loadClassComparison();
            loadDetailedReport();
            
            // Initialize charts
            initializeCharts();
            
            // Update report cards
            updateReportCards();
        }

        // Change tab
        function ubahTab(tab) {
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active tab content
            overviewTab.style.display = 'none';
            comparisonTab.style.display = 'none';
            detailedTab.style.display = 'none';
            
            if (tab === 'overview') {
                overviewTab.style.display = 'block';
            } else if (tab === 'comparison') {
                comparisonTab.style.display = 'block';
                updateComparisonCharts();
            } else if (tab === 'detailed') {
                detailedTab.style.display = 'block';
            }
        }

        // Load top performers
        function loadTopPerformers() {
            // Sort students by average (highest first)
            const sortedStudents = [...sampleStudents].sort((a, b) => b.average - a.average);
            
            // Take top 10 or all if less than 10
            const topStudents = sortedStudents.slice(0, 10);
            
            topPerformersBody.innerHTML = topStudents.map(student => {
                // Get student initials for avatar
                const names = student.name.split(' ');
                const initials = names.length >= 2 
                    ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                    : names[0].substring(0, 2);
                
                // Get trend icon
                const trendIcon = student.trend === 'up' ? 'fa-arrow-up' : 
                                student.trend === 'down' ? 'fa-arrow-down' : 'fa-minus';
                const trendColor = student.trend === 'up' ? 'trend-up' : 
                                 student.trend === 'down' ? 'trend-down' : 'trend-neutral';
                
                return `
                    <tr>
                        <td>
                            <div class="rank-badge rank-${student.rank}">
                                ${student.rank}
                            </div>
                        </td>
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <h4>${student.name}</h4>
                                    <p>${student.class}</p>
                                </div>
                            </div>
                        </td>
                        <td>${student.class}</td>
                        <td>${student.marks.matematik}</td>
                        <td>${student.marks.sains}</td>
                        <td>${student.marks.bahasa_melayu}</td>
                        <td>${student.marks.bahasa_inggeris}</td>
                        <td>${student.marks.pj}</td>
                        <td>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${student.average}%"></div>
                                </div>
                                <div class="performance-value">${student.average.toFixed(1)}%</div>
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${student.grade.toLowerCase()}">
                                ${student.grade}
                            </span>
                        </td>
                        <td>
                            <div class="report-trend ${trendColor}">
                                <i class="fas ${trendIcon}"></i>
                                <span>${student.trend === 'up' ? 'Meningkat' : student.trend === 'down' ? 'Menurun' : 'Stabil'}</span>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load class comparison
        function loadClassComparison() {
            classComparisonBody.innerHTML = classData.map(cls => {
                // Get trend icon
                const trendIcon = cls.trend === 'up' ? 'fa-arrow-up' : 
                                cls.trend === 'down' ? 'fa-arrow-down' : 'fa-minus';
                const trendColor = cls.trend === 'up' ? 'trend-up' : 
                                 cls.trend === 'down' ? 'trend-down' : 'trend-neutral';
                
                return `
                    <tr>
                        <td>${cls.class}</td>
                        <td>${cls.students}</td>
                        <td>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${cls.average}%"></div>
                                </div>
                                <div class="performance-value">${cls.average.toFixed(1)}%</div>
                            </div>
                        </td>
                        <td>${cls.topStudent}</td>
                        <td>${cls.topAverage.toFixed(1)}%</td>
                        <td>
                            <div class="rank-badge rank-${cls.rank}">
                                ${cls.rank}
                            </div>
                        </td>
                        <td>
                            <div class="report-trend ${trendColor}">
                                <i class="fas ${trendIcon}"></i>
                                <span>${cls.trend === 'up' ? 'Meningkat' : cls.trend === 'down' ? 'Menurun' : 'Stabil'}</span>
                            </div>
                        </td>
                        <td>${cls.analysis}</td>
                    </tr>
                `;
            }).join('');
        }

        // Load detailed report
        function loadDetailedReport() {
            // Sort students by rank
            const sortedStudents = [...sampleStudents].sort((a, b) => a.rank - b.rank);
            
            detailedReportBody.innerHTML = sortedStudents.map(student => {
                // Get student initials for avatar
                const names = student.name.split(' ');
                const initials = names.length >= 2 
                    ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                    : names[0].substring(0, 2);
                
                return `
                    <tr>
                        <td>
                            <div class="rank-badge rank-${student.rank}">
                                ${student.rank}
                            </div>
                        </td>
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <h4>${student.name}</h4>
                                    <p>${student.class}</p>
                                </div>
                            </div>
                        </td>
                        <td>${student.marks.matematik}</td>
                        <td>${student.marks.sains}</td>
                        <td>${student.marks.bahasa_melayu}</td>
                        <td>${student.marks.bahasa_inggeris}</td>
                        <td>${student.marks.pj}</td>
                        <td>${student.assessments.ujian1}</td>
                        <td>${student.assessments.ujian2}</td>
                        <td>${student.assessments.peperiksaan}</td>
                        <td>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${student.average}%"></div>
                                </div>
                                <div class="performance-value">${student.average.toFixed(1)}%</div>
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${student.grade.toLowerCase()}">
                                ${student.grade}
                            </span>
                        </td>
                        <td>${student.analysis}</td>
                    </tr>
                `;
            }).join('');
        }

        // Initialize charts
        function initializeCharts() {
            // Performance Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            
            const subjectData = {
                labels: ['Matematik', 'Sains', 'Bahasa Melayu', 'Bahasa Inggeris', 'PJ & Kesihatan'],
                datasets: [{
                    label: 'Purata Markah',
                    data: [78.4, 73.5, 81.3, 71.4, 85.3],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.6)',
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(239, 68, 68, 0.6)',
                        'rgba(59, 130, 246, 0.6)'
                    ],
                    borderColor: [
                        'rgba(79, 70, 229, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(59, 130, 246, 1)'
                    ],
                    borderWidth: 2
                }]
            };
            
            performanceChart = new Chart(performanceCtx, {
                type: currentChartType,
                data: subjectData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Prestasi Mengikut Subjek'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
            
            // Comparison Chart
            initializeComparisonChart();
            
            // Improvement Chart
            initializeImprovementChart();
        }

        // Initialize comparison chart
        function initializeComparisonChart() {
            const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
            
            const comparisonData = {
                labels: ['Semester 1', 'Semester 2'],
                datasets: [
                    {
                        label: 'Matematik',
                        data: [72.5, 78.4],
                        borderColor: 'rgba(79, 70, 229, 1)',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Sains',
                        data: [68.3, 73.5],
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Bahasa Melayu',
                        data: [78.9, 81.3],
                        borderColor: 'rgba(245, 158, 11, 1)',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Bahasa Inggeris',
                        data: [66.8, 71.4],
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4
                    }
                ]
            };
            
            comparisonChart = new Chart(comparisonCtx, {
                type: 'line',
                data: comparisonData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Perbandingan Prestasi Semester'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize improvement chart
        function initializeImprovementChart() {
            const improvementCtx = document.getElementById('improvementChart').getContext('2d');
            
            const improvementData = {
                labels: sampleStudents.map(s => s.name.split(' ')[0]),
                datasets: [{
                    label: 'Peningkatan (%)',
                    data: [5.2, 3.8, 2.1, 7.5, -1.2, -3.4, 4.9, 0.8],
                    backgroundColor: function(context) {
                        const value = context.raw;
                        return value >= 0 ? 'rgba(16, 185, 129, 0.7)' : 'rgba(239, 68, 68, 0.7)';
                    },
                    borderColor: function(context) {
                        const value = context.raw;
                        return value >= 0 ? 'rgba(16, 185, 129, 1)' : 'rgba(239, 68, 68, 1)';
                    },
                    borderWidth: 1
                }]
            };
            
            improvementChart = new Chart(improvementCtx, {
                type: 'bar',
                data: improvementData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Peningkatan Prestasi Pelajar'
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Change chart type
        function ubahJenisGraf(type) {
            currentChartType = type;
            
            // Destroy existing chart
            if (performanceChart) {
                performanceChart.destroy();
            }
            
            // Create new chart with selected type
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            
            const subjectData = {
                labels: ['Matematik', 'Sains', 'Bahasa Melayu', 'Bahasa Inggeris', 'PJ & Kesihatan'],
                datasets: [{
                    label: 'Purata Markah',
                    data: [78.4, 73.5, 81.3, 71.4, 85.3],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.6)',
                        'rgba(16, 185, 129, 0.6)',
                        'rgba(245, 158, 11, 0.6)',
                        'rgba(239, 68, 68, 0.6)',
                        'rgba(59, 130, 246, 0.6)'
                    ],
                    borderColor: [
                        'rgba(79, 70, 229, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(59, 130, 246, 1)'
                    ],
                    borderWidth: 2
                }]
            };
            
            performanceChart = new Chart(performanceCtx, {
                type: type,
                data: subjectData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Prestasi Mengikut Subjek'
                        }
                    },
                    scales: type === 'bar' ? {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    } : {}
                }
            });
            
            showNotification(`Graf ditukar kepada jenis ${type === 'bar' ? 'Bar' : type === 'line' ? 'Line' : 'Pie'}`, 'info');
        }

        // Change comparison type
        function ubahPerbandingan(type) {
            // Destroy existing chart
            if (comparisonChart) {
                comparisonChart.destroy();
            }
            
            const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
            let newData, newTitle;
            
            if (type === 'semester') {
                newData = {
                    labels: ['Semester 1', 'Semester 2'],
                    datasets: [
                        {
                            label: 'Matematik',
                            data: [72.5, 78.4],
                            borderColor: 'rgba(79, 70, 229, 1)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4
                        },
                        {
                            label: 'Sains',
                            data: [68.3, 73.5],
                            borderColor: 'rgba(16, 185, 129, 1)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4
                        }
                    ]
                };
                newTitle = 'Perbandingan Prestasi Semester';
            } else if (type === 'tahun') {
                newData = {
                    labels: ['2021', '2022', '2023'],
                    datasets: [
                        {
                            label: 'Purata Keseluruhan',
                            data: [70.2, 74.8, 78.4],
                            borderColor: 'rgba(79, 70, 229, 1)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                };
                newTitle = 'Trend Prestasi Tahunan';
            } else if (type === 'kelas') {
                newData = {
                    labels: classData.map(c => c.class),
                    datasets: [
                        {
                            label: 'Purata Kelas',
                            data: classData.map(c => c.average),
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.6)',
                                'rgba(16, 185, 129, 0.6)',
                                'rgba(245, 158, 11, 0.6)',
                                'rgba(59, 130, 246, 0.6)'
                            ],
                            borderColor: [
                                'rgba(79, 70, 229, 1)',
                                'rgba(16, 185, 129, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(59, 130, 246, 1)'
                            ],
                            borderWidth: 2
                        }
                    ]
                };
                newTitle = 'Perbandingan Prestasi Kelas';
            }
            
            comparisonChart = new Chart(comparisonCtx, {
                type: type === 'kelas' ? 'bar' : 'line',
                data: newData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: newTitle
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
            
            showNotification(`Perbandingan ditukar kepada ${type === 'semester' ? 'Semester' : type === 'tahun' ? 'Tahun' : 'Kelas'}`, 'info');
        }

        // Update comparison charts
        function updateComparisonCharts() {
            // Charts are already initialized, just ensure they're visible
            if (comparisonChart) {
                comparisonChart.update();
            }
            if (improvementChart) {
                improvementChart.update();
            }
        }

        // Update report cards
        function updateReportCards() {
            // Calculate statistics
            const totalStudents = sampleStudents.length;
            const totalAverage = sampleStudents.reduce((sum, student) => sum + student.average, 0) / totalStudents;
            const excellentStudents = sampleStudents.filter(s => s.average >= 80).length;
            const attentionStudents = sampleStudents.filter(s => s.average <= 50).length;
            
            // Calculate grade distribution
            const gradeCounts = {
                A: sampleStudents.filter(s => s.grade === 'A').length,
                B: sampleStudents.filter(s => s.grade === 'B').length,
                C: sampleStudents.filter(s => s.grade === 'C').length,
                D: sampleStudents.filter(s => s.grade === 'D').length,
                E: sampleStudents.filter(s => s.grade === 'E').length,
                F: sampleStudents.filter(s => s.grade === 'F').length
            };
            
            // Update detailed tab cards
            document.getElementById('gradeDistribution').textContent = `A: ${Math.round((gradeCounts.A / totalStudents) * 100)}%`;
            document.getElementById('averageImprovement').textContent = '+5.2%';
            document.getElementById('excellentStudents').textContent = excellentStudents;
            document.getElementById('attentionStudents').textContent = attentionStudents;
        }

        // Filter data
        function tapilkanData() {
            const classFilter = document.getElementById('filterClass').value;
            const semesterFilter = document.getElementById('filterSemester').value;
            const assessmentFilter = document.getElementById('filterAssessment').value;
            const yearFilter = document.getElementById('filterYear').value;
            
            // Simulate filtering
            showNotification(`Menapis data untuk Kelas: ${classFilter}, Semester: ${semesterFilter}`, 'info');
            
            // Update report cards based on filter
            setTimeout(() => {
                updateReportCards();
                loadTopPerformers();
                loadClassComparison();
                loadDetailedReport();
                
                showNotification('Data berjaya ditapis', 'success');
            }, 500);
        }

        // Show subject analysis
        function tunjukkanAnalisisSubjek(subject) {
            const subjectNames = {
                'matematik': 'Matematik',
                'sains': 'Sains',
                'bahasa_melayu': 'Bahasa Melayu',
                'bahasa_inggeris': 'Bahasa Inggeris',
                'pj': 'PJ & Kesihatan'
            };
            
            const subjectName = subjectNames[subject] || subject;
            const averageScore = {
                'matematik': 78.4,
                'sains': 73.5,
                'bahasa_melayu': 81.3,
                'bahasa_inggeris': 71.4,
                'pj': 85.3
            }[subject] || 0;
            
            let analysis = '';
            if (averageScore >= 80) {
                analysis = `Prestasi ${subjectName} adalah cemerlang dengan purata ${averageScore}%. Pelajar menunjukkan penguasaan yang baik dalam subjek ini.`;
            } else if (averageScore >= 70) {
                analysis = `Prestasi ${subjectName} adalah baik dengan purata ${averageScore}%. Terdapat ruang untuk penambahbaikan terutama dalam topik-topik tertentu.`;
            } else {
                analysis = `Prestasi ${subjectName} memerlukan perhatian dengan purata ${averageScore}%. Disyorkan program bimbingan tambahan untuk subjek ini.`;
            }
            
            showNotification(`Analisis Subjek: ${subjectName}`, 'info', analysis);
        }

        // Show all students
        function tunjukkanSemuaPelajar() {
            ubahTab('detailed');
            showNotification('Memaparkan semua pelajar', 'info');
        }

        // Export top list
        function eksportSenaraiTerbaik() {
            showNotification('Menyediakan senarai pelajar terbaik untuk eksport...', 'info');
            setTimeout(() => {
                showNotification('Senarai berjaya dieksport', 'success');
            }, 1500);
        }

        // Add performance comment
        function tambahKomenPrestasi() {
            const comment = prompt('Masukkan komen prestasi untuk pelajar:');
            if (comment && comment.trim() !== '') {
                showNotification('Komen prestasi berjaya ditambah', 'success');
            }
        }

        // Generate individual report
        function janaLaporanIndividu() {
            showNotification('Menjana laporan prestasi individu...', 'info');
            setTimeout(() => {
                showNotification('Laporan individu berjaya dijana', 'success');
            }, 1500);
        }

        // Export full report
        function eksportLaporanPenuh() {
            showNotification('Menyediakan laporan penuh untuk eksport...', 'info');
            setTimeout(() => {
                showNotification('Laporan penuh berjaya dieksport', 'success');
            }, 1500);
        }

        // Generate full report
        function janaLaporanPenuh() {
            showNotification('Menjana laporan prestasi penuh...', 'info');
            setTimeout(() => {
                showNotification('Laporan penuh berjaya dijana dan sedia untuk muat turun', 'success');
            }, 2000);
        }

        // Reload report
        function muatSemulaLaporan() {
            showNotification('Muat semula data laporan...', 'info');
            
            // Simulate reload
            setTimeout(() => {
                loadTopPerformers();
                loadClassComparison();
                loadDetailedReport();
                updateReportCards();
                
                if (performanceChart) performanceChart.update();
                if (comparisonChart) comparisonChart.update();
                if (improvementChart) improvementChart.update();
                
                showNotification('Laporan berjaya dimuat semula', 'success');
            }, 1000);
        }

        // Show notification
        function showNotification(message, type = 'info', details = null) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 30px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : type === 'warning' ? 'var(--warning)' : 'var(--info)'};
                color: white;
                padding: 15px 25px;
                border-radius: 12px;
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
                max-width: 400px;
            `;
            
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                <div>
                    <div style="font-weight: 600;">${message}</div>
                    ${details ? `<div style="font-size: 12px; margin-top: 5px; opacity: 0.9;">${details}</div>` : ''}
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after appropriate time
            const duration = details ? 5000 : 3000;
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, duration);
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
        `;
        document.head.appendChild(style);

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    </script>
</body>
</html>