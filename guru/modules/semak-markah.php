<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semak Markah - SlipKu</title>
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

        /* Search Section */
        .search-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .search-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-title i {
            color: var(--primary);
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 15px 20px 15px 50px;
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
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
            font-size: 16px;
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

        /* Marks Overview Cards */
        .overview-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .overview-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .overview-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .overview-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .overview-icon {
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

        .overview-icon.total {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .overview-icon.average {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .overview-icon.highest {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .overview-icon.lowest {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .overview-icon.passed {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .overview-icon.failed {
            background: linear-gradient(135deg, var(--danger), #f87171);
        }

        .overview-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--medium-gray);
            margin-bottom: 5px;
        }

        .overview-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .overview-trend {
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

        /* Marks Table Container */
        .marks-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .marks-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .marks-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .marks-title i {
            color: var(--info);
        }

        .marks-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .marks-table th {
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

        .marks-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .marks-table tr:hover td {
            background: var(--primary-light);
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

        /* Mark Cell */
        .mark-cell {
            text-align: center;
        }

        .mark-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 3px;
        }

        .mark-status {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-block;
        }

        .status-passed {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Grade Badge */
        .grade-badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            min-width: 60px;
        }

        .grade-a {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .grade-b {
            background: rgba(59, 130, 246, 0.15);
            color: var(--info);
            border: 2px solid rgba(59, 130, 246, 0.3);
        }

        .grade-c {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 2px solid rgba(245, 158, 11, 0.3);
        }

        .grade-d {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 2px solid rgba(239, 68, 68, 0.3);
        }

        .grade-e {
            background: rgba(107, 114, 128, 0.15);
            color: var(--medium-gray);
            border: 2px solid rgba(107, 114, 128, 0.3);
        }

        .grade-f {
            background: rgba(156, 163, 175, 0.15);
            color: var(--dark-gray);
            border: 2px solid rgba(156, 163, 175, 0.3);
        }

        /* Performance Indicators */
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
        }

        .performance-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--dark-gray);
            min-width: 40px;
            text-align: right;
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

        /* Subject Cards */
        .subject-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .subject-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            cursor: pointer;
        }

        .subject-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .subject-card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .subject-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            flex-shrink: 0;
        }

        .subject-icon.math {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .subject-icon.science {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .subject-icon.bm {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .subject-icon.bi {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .subject-icon.pj {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
        }

        .subject-info h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .subject-info p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .subject-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .subject-stat {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            padding: 20px;
            backdrop-filter: blur(3px);
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius);
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 25px;
            border-bottom: 2px solid var(--light-gray);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-gray);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            padding: 5px;
            border-radius: 8px;
        }

        .modal-close:hover {
            background: var(--light-gray);
            color: var(--danger);
        }

        .modal-body {
            padding: 25px;
        }

        /* Student Details Modal */
        .student-details-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-gray);
        }

        .student-details-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 24px;
            flex-shrink: 0;
        }

        .student-details-info h3 {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .student-details-info p {
            color: var(--medium-gray);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .student-details-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .student-stat {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .student-stat-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .student-stat-label {
            font-size: 12px;
            color: var(--medium-gray);
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

        /* Export Options */
        .export-options {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .export-option {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .export-option:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .export-option i {
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .export-option h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .export-option p {
            font-size: 12px;
            color: var(--medium-gray);
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
            
            .subject-cards {
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
            
            .filter-options {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .filter-select {
                flex: 1;
            }
            
            .overview-cards {
                grid-template-columns: 1fr 1fr;
            }
            
            .student-details-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .export-options {
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
            
            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
            
            .overview-cards {
                grid-template-columns: 1fr;
            }
            
            .student-details-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Modal for Student Details -->
    <div class="modal" id="studentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Butiran Prestasi Pelajar</h3>
                <button class="modal-close" onclick="closeStudentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="student-details-header">
                    <div class="student-details-avatar" id="studentModalAvatar">AA</div>
                    <div class="student-details-info">
                        <h3 id="studentModalName">Ahmad bin Ali</h3>
                        <p id="studentModalInfo">ID: STU001 • Kelas: 6A • Tahun: 6</p>
                        <p id="studentModalPerformance">Purata Keseluruhan: <strong>84.5%</strong></p>
                    </div>
                </div>
                
                <div class="student-details-stats">
                    <div class="student-stat">
                        <div class="student-stat-value" id="modalRank">1</div>
                        <div class="student-stat-label">Kedudukan</div>
                    </div>
                    <div class="student-stat">
                        <div class="student-stat-value" id="modalAverage">84.5%</div>
                        <div class="student-stat-label">Purata</div>
                    </div>
                    <div class="student-stat">
                        <div class="student-stat-value" id="modalPassed">8</div>
                        <div class="student-stat-label">Lulus</div>
                    </div>
                    <div class="student-stat">
                        <div class="student-stat-value" id="modalFailed">0</div>
                        <div class="student-stat-label">Gagal</div>
                    </div>
                </div>
                
                <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Prestasi Mengikut Subjek</h4>
                <div style="overflow-x: auto;">
                    <table class="marks-table" style="min-width: 700px;">
                        <thead>
                            <tr>
                                <th>Subjek</th>
                                <th>Markah</th>
                                <th>Gred</th>
                                <th>Status</th>
                                <th>Kedudukan</th>
                                <th>Tarikh</th>
                            </tr>
                        </thead>
                        <tbody id="studentSubjectsBody">
                            <!-- Student subject rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid var(--light-gray);">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Analisis Prestasi</h4>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <p style="color: var(--medium-gray); font-size: 13px; line-height: 1.6;" id="studentAnalysis">
                            Pelajar menunjukkan prestasi yang cemerlang dalam semua subjek. Pencapaian tertinggi dalam Matematik (95%) dan terendah dalam Bahasa Inggeris (78%). Disyorkan untuk meningkatkan penguasaan Bahasa Inggeris.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Export Options -->
    <div class="modal" id="exportModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Eksport Data Markah</h3>
                <button class="modal-close" onclick="closeExportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="export-options">
                    <div class="export-option" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i>
                        <h4>PDF</h4>
                        <p>Format dokumen</p>
                    </div>
                    <div class="export-option" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i>
                        <h4>Excel</h4>
                        <p>Format spreadsheet</p>
                    </div>
                    <div class="export-option" onclick="exportToCSV()">
                        <i class="fas fa-file-csv"></i>
                        <h4>CSV</h4>
                        <p>Data ringkas</p>
                    </div>
                    <div class="export-option" onclick="exportToPrint()">
                        <i class="fas fa-print"></i>
                        <h4>Cetak</h4>
                        <p>Paparan cetak</p>
                    </div>
                </div>
                
                <div style="background: var(--primary-light); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--primary);">
                        <i class="fas fa-info-circle"></i> Pilihan Penapis
                    </h4>
                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="exportAll" checked>
                            <label for="exportAll" style="font-size: 13px; cursor: pointer;">Semua Pelajar</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="exportRank">
                            <label for="exportRank" style="font-size: 13px; cursor: pointer;">Sertakan Kedudukan</label>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="exportAnalysis" checked>
                            <label for="exportAnalysis" style="font-size: 13px; cursor: pointer;">Sertakan Analisis</label>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center;">
                    <button class="btn btn-primary" onclick="startExport()">
                        <i class="fas fa-download"></i>
                        Mula Eksport
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <p>Semak Markah</p>
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
                    <span class="notification-badge">4</span>
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
            <a href="semak-markah.html" class="sidebar-item active">
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
                <span class="badge">5</span>
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
                <h2>Semak Markah 🔍</h2>
                <p>Semak dan analisis markah pelajar secara menyeluruh</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="openExportModal()">
                    <i class="fas fa-download"></i>
                    Eksport Data
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="changeView('overview')">
                <i class="fas fa-chart-bar"></i>
                Gambaran Keseluruhan
            </button>
            <button class="tab-btn" onclick="changeView('subject')">
                <i class="fas fa-book"></i>
                Mengikut Subjek
            </button>
            <button class="tab-btn" onclick="changeView('student')">
                <i class="fas fa-user-graduate"></i>
                Mengikut Pelajar
            </button>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-title">
                <i class="fas fa-filter"></i>
                Cari & Tapis Markah
            </div>
            
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar atau subjek..." onkeyup="searchData()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="filterData()">
                        <option value="">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Subjek:</label>
                    <select class="filter-select" id="filterSubject" onchange="filterData()">
                        <option value="">Semua Subjek</option>
                        <option value="matematik">Matematik</option>
                        <option value="sains">Sains</option>
                        <option value="bahasa_melayu">Bahasa Melayu</option>
                        <option value="bahasa_inggeris">Bahasa Inggeris</option>
                        <option value="pj">PJ & Kesihatan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Jenis Penilaian:</label>
                    <select class="filter-select" id="filterAssessment" onchange="filterData()">
                        <option value="">Semua Jenis</option>
                        <option value="exam">Peperiksaan Akhir</option>
                        <option value="midterm">Peperiksaan Pertengahan</option>
                        <option value="quiz">Kuiz</option>
                        <option value="assignment">Tugasan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Gred:</label>
                    <select class="filter-select" id="filterGrade" onchange="filterData()">
                        <option value="">Semua Gred</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="overview-cards" id="overviewCards">
            <!-- Cards will be loaded here -->
        </div>

        <!-- Subject View -->
        <div class="subject-cards" id="subjectView" style="display: none;">
            <!-- Subject cards will be loaded here -->
        </div>

        <!-- Student Table View -->
        <div class="marks-container" id="studentView" style="display: none;">
            <div class="marks-header">
                <div class="marks-title">
                    <i class="fas fa-list"></i>
                    <span>Senarai Markah Pelajar</span>
                </div>
                <div class="marks-actions">
                    <button class="action-btn info" onclick="susunMengikutMarkah()">
                        <i class="fas fa-sort-amount-down"></i>
                        Susun Markah
                    </button>
                    <button class="action-btn warning" onclick="tunjukkanLulusSahaja()">
                        <i class="fas fa-check-circle"></i>
                        Lulus Sahaja
                    </button>
                    <button class="action-btn danger" onclick="tunjukkanGagalSahaja()">
                        <i class="fas fa-times-circle"></i>
                        Gagal Sahaja
                    </button>
                </div>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="marks-table" id="studentTable">
                    <thead>
                        <tr>
                            <th>KED</th>
                            <th>PELAJAR</th>
                            <th>MATEMATIK</th>
                            <th>SAINS</th>
                            <th>BAHASA MELAYU</th>
                            <th>BAHASA INGGERIS</th>
                            <th>PJ & KESIHATAN</th>
                            <th>PURATA</th>
                            <th>GRED</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Student rows will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 13px; color: var(--medium-gray);">
                <span id="tableInfo">Memaparkan 0 pelajar</span>
            </div>
        </div>

        <!-- Detailed Marks Table -->
        <div class="marks-container" id="detailedView">
            <div class="marks-header">
                <div class="marks-title">
                    <i class="fas fa-table"></i>
                    <span>Markah Terperinci</span>
                </div>
                <div class="marks-actions">
                    <button class="action-btn primary" onclick="tambahPenilaian()">
                        <i class="fas fa-plus-circle"></i>
                        Tambah Penilaian
                    </button>
                    <button class="action-btn success" onclick="janaAnalisis()">
                        <i class="fas fa-chart-line"></i>
                        Jana Analisis
                    </button>
                </div>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="marks-table" id="detailedTable">
                    <thead>
                        <tr>
                            <th>PELAJAR</th>
                            <th>KELAS</th>
                            <th>UJIAN 1</th>
                            <th>UJIAN 2</th>
                            <th>KUIZ 1</th>
                            <th>KUIZ 2</th>
                            <th>TUGASAN</th>
                            <th>PEPERIKSAAN AKHIR</th>
                            <th>JUMLAH</th>
                            <th>GRED</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="detailedTableBody">
                        <!-- Detailed rows will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div class="empty-state" id="emptyDetailed" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Tiada Data Ditemui</h3>
                <p>Tiada markah yang sepadan dengan carian anda.</p>
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Penapis
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
        const studentModal = document.getElementById('studentModal');
        const exportModal = document.getElementById('exportModal');
        const overviewCards = document.getElementById('overviewCards');
        const subjectView = document.getElementById('subjectView');
        const studentView = document.getElementById('studentView');
        const detailedView = document.getElementById('detailedView');
        const studentTableBody = document.getElementById('studentTableBody');
        const detailedTableBody = document.getElementById('detailedTableBody');
        const studentSubjectsBody = document.getElementById('studentSubjectsBody');
        const emptyDetailed = document.getElementById('emptyDetailed');

        // Current state
        let currentView = 'overview';
        let currentStudents = [];
        let currentSubjects = [];
        let filteredStudents = [];
        let filteredSubjects = [];

        // Sample data for students
        const sampleStudents = [
            {
                id: 'STU001',
                name: 'Ahmad bin Ali',
                class: '6A',
                ic: '120101-01-1234',
                marks: {
                    matematik: { score: 95, grade: 'A', status: 'passed' },
                    sains: { score: 88, grade: 'A', status: 'passed' },
                    bahasa_melayu: { score: 82, grade: 'B', status: 'passed' },
                    bahasa_inggeris: { score: 78, grade: 'B', status: 'passed' },
                    pj: { score: 90, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 92,
                    ujian2: 88,
                    kuiz1: 95,
                    kuiz2: 90,
                    tugasan: 85,
                    peperiksaan: 94
                },
                total: 544,
                average: 90.7,
                overallGrade: 'A',
                rank: 1,
                performance: 'excellent'
            },
            {
                id: 'STU002',
                name: 'Siti binti Abu',
                class: '6A',
                ic: '120202-02-5678',
                marks: {
                    matematik: { score: 85, grade: 'A', status: 'passed' },
                    sains: { score: 72, grade: 'B', status: 'passed' },
                    bahasa_melayu: { score: 90, grade: 'A', status: 'passed' },
                    bahasa_inggeris: { score: 68, grade: 'C', status: 'passed' },
                    pj: { score: 88, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 80,
                    ujian2: 75,
                    kuiz1: 85,
                    kuiz2: 78,
                    tugasan: 82,
                    peperiksaan: 82
                },
                total: 482,
                average: 80.3,
                overallGrade: 'B',
                rank: 3,
                performance: 'good'
            },
            {
                id: 'STU003',
                name: 'Muhammad bin Hassan',
                class: '6A',
                ic: '120303-03-9012',
                marks: {
                    matematik: { score: 92, grade: 'A', status: 'passed' },
                    sains: { score: 85, grade: 'A', status: 'passed' },
                    bahasa_melayu: { score: 88, grade: 'A', status: 'passed' },
                    bahasa_inggeris: { score: 82, grade: 'B', status: 'passed' },
                    pj: { score: 95, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 90,
                    ujian2: 88,
                    kuiz1: 92,
                    kuiz2: 85,
                    tugasan: 87,
                    peperiksaan: 91
                },
                total: 533,
                average: 88.8,
                overallGrade: 'A',
                rank: 2,
                performance: 'excellent'
            },
            {
                id: 'STU004',
                name: 'Aisyah binti Musa',
                class: '6A',
                ic: '120404-04-3456',
                marks: {
                    matematik: { score: 68, grade: 'C', status: 'passed' },
                    sains: { score: 72, grade: 'B', status: 'passed' },
                    bahasa_melayu: { score: 85, grade: 'A', status: 'passed' },
                    bahasa_inggeris: { score: 65, grade: 'C', status: 'passed' },
                    pj: { score: 80, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 70,
                    ujian2: 65,
                    kuiz1: 68,
                    kuiz2: 72,
                    tugasan: 75,
                    peperiksaan: 69
                },
                total: 419,
                average: 69.8,
                overallGrade: 'C',
                rank: 5,
                performance: 'average'
            },
            {
                id: 'STU005',
                name: 'Ali bin Abdullah',
                class: '6A',
                ic: '120505-05-7890',
                marks: {
                    matematik: { score: 79, grade: 'B', status: 'passed' },
                    sains: { score: 75, grade: 'B', status: 'passed' },
                    bahasa_melayu: { score: 82, grade: 'B', status: 'passed' },
                    bahasa_inggeris: { score: 70, grade: 'C', status: 'passed' },
                    pj: { score: 85, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 78,
                    ujian2: 75,
                    kuiz1: 79,
                    kuiz2: 72,
                    tugasan: 80,
                    peperiksaan: 77
                },
                total: 461,
                average: 76.8,
                overallGrade: 'B',
                rank: 4,
                performance: 'good'
            },
            {
                id: 'STU006',
                name: 'Fatimah binti Omar',
                class: '6A',
                ic: '120606-06-2345',
                marks: {
                    matematik: { score: 55, grade: 'E', status: 'passed' },
                    sains: { score: 48, grade: 'E', status: 'passed' },
                    bahasa_melayu: { score: 62, grade: 'D', status: 'passed' },
                    bahasa_inggeris: { score: 58, grade: 'E', status: 'passed' },
                    pj: { score: 70, grade: 'C', status: 'passed' }
                },
                assessments: {
                    ujian1: 52,
                    ujian2: 48,
                    kuiz1: 55,
                    kuiz2: 50,
                    tugasan: 58,
                    peperiksaan: 53
                },
                total: 316,
                average: 52.7,
                overallGrade: 'E',
                rank: 8,
                performance: 'poor'
            },
            {
                id: 'STU007',
                name: 'Hassan bin Ismail',
                class: '6A',
                ic: '120707-07-6789',
                marks: {
                    matematik: { score: 92, grade: 'A', status: 'passed' },
                    sains: { score: 90, grade: 'A', status: 'passed' },
                    bahasa_melayu: { score: 88, grade: 'A', status: 'passed' },
                    bahasa_inggeris: { score: 85, grade: 'A', status: 'passed' },
                    pj: { score: 94, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 90,
                    ujian2: 92,
                    kuiz1: 94,
                    kuiz2: 88,
                    tugasan: 89,
                    peperiksaan: 93
                },
                total: 546,
                average: 91.0,
                overallGrade: 'A',
                rank: 1,
                performance: 'excellent'
            },
            {
                id: 'STU008',
                name: 'Zainab binti Yusuf',
                class: '6A',
                ic: '120808-08-1234',
                marks: {
                    matematik: { score: 61, grade: 'D', status: 'passed' },
                    sains: { score: 58, grade: 'E', status: 'passed' },
                    bahasa_melayu: { score: 72, grade: 'B', status: 'passed' },
                    bahasa_inggeris: { score: 65, grade: 'C', status: 'passed' },
                    pj: { score: 80, grade: 'A', status: 'passed' }
                },
                assessments: {
                    ujian1: 60,
                    ujian2: 58,
                    kuiz1: 61,
                    kuiz2: 55,
                    tugasan: 65,
                    peperiksaan: 62
                },
                total: 361,
                average: 60.2,
                overallGrade: 'D',
                rank: 7,
                performance: 'average'
            }
        ];

        // Sample data for subjects
        const sampleSubjects = [
            {
                id: 'SUB001',
                name: 'Matematik',
                code: 'MAT601',
                class: '6A',
                totalStudents: 8,
                averageScore: 78.4,
                highestScore: 95,
                lowestScore: 55,
                passed: 8,
                failed: 0,
                gradeDistribution: { A: 3, B: 2, C: 1, D: 1, E: 1, F: 0 }
            },
            {
                id: 'SUB002',
                name: 'Sains',
                code: 'SNS601',
                class: '6A',
                totalStudents: 8,
                averageScore: 73.5,
                highestScore: 90,
                lowestScore: 48,
                passed: 8,
                failed: 0,
                gradeDistribution: { A: 2, B: 2, C: 1, D: 1, E: 2, F: 0 }
            },
            {
                id: 'SUB003',
                name: 'Bahasa Melayu',
                code: 'BML601',
                class: '6A',
                totalStudents: 8,
                averageScore: 81.3,
                highestScore: 90,
                lowestScore: 62,
                passed: 8,
                failed: 0,
                gradeDistribution: { A: 4, B: 3, C: 0, D: 1, E: 0, F: 0 }
            },
            {
                id: 'SUB004',
                name: 'Bahasa Inggeris',
                code: 'ENG601',
                class: '6A',
                totalStudents: 8,
                averageScore: 71.4,
                highestScore: 85,
                lowestScore: 58,
                passed: 8,
                failed: 0,
                gradeDistribution: { A: 1, B: 2, C: 3, D: 1, E: 1, F: 0 }
            },
            {
                id: 'SUB005',
                name: 'PJ & Kesihatan',
                code: 'PJK601',
                class: '6A',
                totalStudents: 8,
                averageScore: 85.3,
                highestScore: 95,
                lowestScore: 70,
                passed: 8,
                failed: 0,
                gradeDistribution: { A: 5, B: 2, C: 1, D: 0, E: 0, F: 0 }
            }
        ];

        // Initialize page
        function initializePage() {
            currentStudents = [...sampleStudents];
            currentSubjects = [...sampleSubjects];
            filteredStudents = [...currentStudents];
            filteredSubjects = [...currentSubjects];
            
            // Set up event listeners
            setupEventListeners();
            
            // Load initial data
            loadOverviewCards();
            loadSubjectView();
            loadStudentTableView();
            loadDetailedTableView();
            
            // Update table info
            updateTableInfo();
        }

        // Change view
        function changeView(view) {
            currentView = view;
            
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active view
            overviewCards.style.display = 'none';
            subjectView.style.display = 'none';
            studentView.style.display = 'none';
            detailedView.style.display = 'block';
            
            if (view === 'overview') {
                overviewCards.style.display = 'grid';
                detailedView.style.display = 'block';
            } else if (view === 'subject') {
                subjectView.style.display = 'grid';
                detailedView.style.display = 'none';
            } else if (view === 'student') {
                studentView.style.display = 'block';
                detailedView.style.display = 'none';
            }
        }

        // Load overview cards
        function loadOverviewCards() {
            // Calculate statistics
            const totalStudents = currentStudents.length;
            const averageScore = currentStudents.reduce((sum, student) => sum + student.average, 0) / totalStudents;
            const highestScore = Math.max(...currentStudents.map(s => s.average));
            const lowestScore = Math.min(...currentStudents.map(s => s.average));
            const passedStudents = currentStudents.filter(s => s.overallGrade !== 'F').length;
            const failedStudents = totalStudents - passedStudents;
            
            overviewCards.innerHTML = `
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon total">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Jumlah Pelajar</h3>
                            <div class="overview-value">${totalStudents}</div>
                            <div class="overview-trend">
                                <i class="fas fa-arrow-up trend-up"></i>
                                <span>+2 dari bulan lepas</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon average">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Purata Keseluruhan</h3>
                            <div class="overview-value">${averageScore.toFixed(1)}%</div>
                            <div class="overview-trend">
                                <i class="fas fa-arrow-up trend-up"></i>
                                <span>+3.2% dari ujian lepas</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon highest">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Markah Tertinggi</h3>
                            <div class="overview-value">${highestScore.toFixed(1)}%</div>
                            <div class="overview-trend">
                                <i class="fas fa-star trend-up"></i>
                                <span>Hassan bin Ismail</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon lowest">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Markah Terendah</h3>
                            <div class="overview-value">${lowestScore.toFixed(1)}%</div>
                            <div class="overview-trend">
                                <i class="fas fa-exclamation-triangle trend-down"></i>
                                <span>Perlu bimbingan</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon passed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Pelajar Lulus</h3>
                            <div class="overview-value">${passedStudents}</div>
                            <div class="overview-trend">
                                <i class="fas fa-check trend-up"></i>
                                <span>${Math.round((passedStudents / totalStudents) * 100)}% lulus</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="overview-card">
                    <div class="overview-card-header">
                        <div class="overview-icon failed">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="overview-info">
                            <h3>Pelajar Gagal</h3>
                            <div class="overview-value">${failedStudents}</div>
                            <div class="overview-trend">
                                <i class="fas fa-times trend-down"></i>
                                <span>Perlu perhatian</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Load subject view
        function loadSubjectView() {
            subjectView.innerHTML = filteredSubjects.map(subject => {
                // Get subject icon
                const iconClass = {
                    'Matematik': 'math',
                    'Sains': 'science',
                    'Bahasa Melayu': 'bm',
                    'Bahasa Inggeris': 'bi',
                    'PJ & Kesihatan': 'pj'
                }[subject.name] || 'math';
                
                const icon = {
                    'Matematik': 'fas fa-calculator',
                    'Sains': 'fas fa-flask',
                    'Bahasa Melayu': 'fas fa-book-open',
                    'Bahasa Inggeris': 'fas fa-language',
                    'PJ & Kesihatan': 'fas fa-running'
                }[subject.name] || 'fas fa-book';
                
                return `
                    <div class="subject-card" onclick="viewSubjectDetails('${subject.id}')">
                        <div class="subject-card-header">
                            <div class="subject-icon ${iconClass}">
                                <i class="${icon}"></i>
                            </div>
                            <div class="subject-info">
                                <h3>${subject.name}</h3>
                                <p>${subject.code} • ${subject.class} • ${subject.totalStudents} pelajar</p>
                            </div>
                        </div>
                        
                        <div class="subject-stats">
                            <div class="subject-stat">
                                <div class="stat-value">${subject.averageScore.toFixed(1)}%</div>
                                <div class="stat-label">Purata</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.highestScore}</div>
                                <div class="stat-label">Tertinggi</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.lowestScore}</div>
                                <div class="stat-label">Terendah</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.passed}/${subject.totalStudents}</div>
                                <div class="stat-label">Lulus</div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 15px;">
                            <h4 style="font-size: 13px; margin-bottom: 10px; color: var(--dark-gray);">Taburan Gred:</h4>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${(subject.gradeDistribution.A / subject.totalStudents) * 100}%; background: var(--success);"></div>
                                </div>
                                <div class="performance-value">${subject.gradeDistribution.A} A</div>
                            </div>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${(subject.gradeDistribution.B / subject.totalStudents) * 100}%; background: var(--info);"></div>
                                </div>
                                <div class="performance-value">${subject.gradeDistribution.B} B</div>
                            </div>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${(subject.gradeDistribution.C / subject.totalStudents) * 100}%; background: var(--warning);"></div>
                                </div>
                                <div class="performance-value">${subject.gradeDistribution.C} C</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Load student table view
        function loadStudentTableView() {
            if (filteredStudents.length === 0) {
                studentTableBody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: var(--medium-gray);">
                            <i class="fas fa-user-graduate" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            Tiada pelajar ditemui
                        </td>
                    </tr>
                `;
                return;
            }
            
            studentTableBody.innerHTML = filteredStudents.map(student => {
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
                                    <p>${student.class} • ${student.ic}</p>
                                </div>
                            </div>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${student.marks.matematik.score}</div>
                            <span class="grade-badge grade-${student.marks.matematik.grade.toLowerCase()}">
                                ${student.marks.matematik.grade}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${student.marks.sains.score}</div>
                            <span class="grade-badge grade-${student.marks.sains.grade.toLowerCase()}">
                                ${student.marks.sains.grade}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${student.marks.bahasa_melayu.score}</div>
                            <span class="grade-badge grade-${student.marks.bahasa_melayu.grade.toLowerCase()}">
                                ${student.marks.bahasa_melayu.grade}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${student.marks.bahasa_inggeris.score}</div>
                            <span class="grade-badge grade-${student.marks.bahasa_inggeris.grade.toLowerCase()}">
                                ${student.marks.bahasa_inggeris.grade}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${student.marks.pj.score}</div>
                            <span class="grade-badge grade-${student.marks.pj.grade.toLowerCase()}">
                                ${student.marks.pj.grade}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value" style="font-size: 18px;">${student.average.toFixed(1)}%</div>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${student.average}%; background: ${getPerformanceColor(student.average)};"></div>
                                </div>
                                <div class="performance-value">${getPerformanceLabel(student.average)}</div>
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${student.overallGrade.toLowerCase()}">
                                ${student.overallGrade}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn info" onclick="viewStudentDetails('${student.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load detailed table view
        function loadDetailedTableView() {
            if (filteredStudents.length === 0) {
                detailedTableBody.innerHTML = '';
                emptyDetailed.style.display = 'block';
                return;
            }
            
            emptyDetailed.style.display = 'none';
            
            detailedTableBody.innerHTML = filteredStudents.map(student => {
                // Get student initials for avatar
                const names = student.name.split(' ');
                const initials = names.length >= 2 
                    ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                    : names[0].substring(0, 2);
                
                const assessments = student.assessments;
                const total = assessments.ujian1 + assessments.ujian2 + assessments.kuiz1 + 
                             assessments.kuiz2 + assessments.tugasan + assessments.peperiksaan;
                
                return `
                    <tr>
                        <td>
                            <div class="student-row">
                                <div class="student-avatar">${initials}</div>
                                <div class="student-info">
                                    <h4>${student.name}</h4>
                                    <p>${student.ic}</p>
                                </div>
                            </div>
                        </td>
                        <td>${student.class}</td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.ujian1}</div>
                            <span class="mark-status ${assessments.ujian1 >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.ujian1 >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.ujian2}</div>
                            <span class="mark-status ${assessments.ujian2 >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.ujian2 >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.kuiz1}</div>
                            <span class="mark-status ${assessments.kuiz1 >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.kuiz1 >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.kuiz2}</div>
                            <span class="mark-status ${assessments.kuiz2 >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.kuiz2 >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.tugasan}</div>
                            <span class="mark-status ${assessments.tugasan >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.tugasan >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value">${assessments.peperiksaan}</div>
                            <span class="mark-status ${assessments.peperiksaan >= 40 ? 'status-passed' : 'status-failed'}">
                                ${assessments.peperiksaan >= 40 ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td class="mark-cell">
                            <div class="mark-value" style="font-size: 18px;">${total}</div>
                            <div class="performance-indicator">
                                <div class="performance-bar">
                                    <div class="performance-fill" style="width: ${(total / 600) * 100}%; background: ${getPerformanceColor((total / 600) * 100)};"></div>
                                </div>
                                <div class="performance-value">${Math.round((total / 600) * 100)}%</div>
                            </div>
                        </td>
                        <td>
                            <span class="grade-badge grade-${student.overallGrade.toLowerCase()}">
                                ${student.overallGrade}
                            </span>
                        </td>
                        <td>
                            <span class="mark-status ${student.overallGrade !== 'F' ? 'status-passed' : 'status-failed'}">
                                ${student.overallGrade !== 'F' ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Search data
        function searchData() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            filteredStudents = currentStudents.filter(student => {
                return student.name.toLowerCase().includes(searchTerm) ||
                       student.id.toLowerCase().includes(searchTerm) ||
                       student.class.toLowerCase().includes(searchTerm) ||
                       student.ic.toLowerCase().includes(searchTerm);
            });
            
            filteredSubjects = currentSubjects.filter(subject => {
                return subject.name.toLowerCase().includes(searchTerm) ||
                       subject.code.toLowerCase().includes(searchTerm) ||
                       subject.class.toLowerCase().includes(searchTerm);
            });
            
            updateViews();
        }

        // Filter data
        function filterData() {
            const classFilter = document.getElementById('filterClass').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            const assessmentFilter = document.getElementById('filterAssessment').value;
            const gradeFilter = document.getElementById('filterGrade').value;
            
            filteredStudents = currentStudents.filter(student => {
                // Apply class filter
                if (classFilter && student.class !== classFilter) return false;
                
                // Apply subject filter
                if (subjectFilter) {
                    const subjectKey = subjectFilter;
                    if (!student.marks[subjectKey]) return false;
                }
                
                // Apply grade filter
                if (gradeFilter && student.overallGrade !== gradeFilter) return false;
                
                return true;
            });
            
            filteredSubjects = currentSubjects.filter(subject => {
                // Apply class filter
                if (classFilter && subject.class !== classFilter) return false;
                
                // Apply subject filter
                if (subjectFilter && subject.name.toLowerCase() !== subjectFilter.replace('_', ' ')) return false;
                
                return true;
            });
            
            updateViews();
        }

        // Update all views
        function updateViews() {
            loadOverviewCards();
            loadSubjectView();
            loadStudentTableView();
            loadDetailedTableView();
            updateTableInfo();
        }

        // Update table info
        function updateTableInfo() {
            document.getElementById('tableInfo').textContent = `Memaparkan ${filteredStudents.length} pelajar`;
        }

        // Get performance color
        function getPerformanceColor(score) {
            if (score >= 80) return 'var(--success)';
            if (score >= 60) return 'var(--info)';
            if (score >= 40) return 'var(--warning)';
            return 'var(--danger)';
        }

        // Get performance label
        function getPerformanceLabel(score) {
            if (score >= 80) return 'Cemerlang';
            if (score >= 60) return 'Baik';
            if (score >= 40) return 'Memuaskan';
            return 'Lemah';
        }

        // View student details
        function viewStudentDetails(studentId) {
            const student = currentStudents.find(s => s.id === studentId);
            if (!student) return;
            
            // Get student initials for avatar
            const names = student.name.split(' ');
            const initials = names.length >= 2 
                ? names[0].charAt(0) + names[names.length - 1].charAt(0)
                : names[0].substring(0, 2);
            
            // Calculate passed subjects
            const passedSubjects = Object.values(student.marks).filter(mark => mark.status === 'passed').length;
            const totalSubjects = Object.keys(student.marks).length;
            const failedSubjects = totalSubjects - passedSubjects;
            
            // Update modal content
            document.getElementById('studentModalAvatar').textContent = initials;
            document.getElementById('studentModalName').textContent = student.name;
            document.getElementById('studentModalInfo').textContent = `ID: ${student.id} • Kelas: ${student.class} • Tahun: 6`;
            document.getElementById('studentModalPerformance').innerHTML = `Purata Keseluruhan: <strong>${student.average.toFixed(1)}%</strong>`;
            document.getElementById('modalRank').textContent = student.rank;
            document.getElementById('modalAverage').textContent = `${student.average.toFixed(1)}%`;
            document.getElementById('modalPassed').textContent = passedSubjects;
            document.getElementById('modalFailed').textContent = failedSubjects;
            
            // Load student subjects
            studentSubjectsBody.innerHTML = Object.entries(student.marks).map(([subject, data]) => {
                const subjectName = {
                    'matematik': 'Matematik',
                    'sains': 'Sains',
                    'bahasa_melayu': 'Bahasa Melayu',
                    'bahasa_inggeris': 'Bahasa Inggeris',
                    'pj': 'PJ & Kesihatan'
                }[subject] || subject;
                
                // Find subject rank (simplified)
                const subjectStudents = currentStudents.filter(s => s.marks[subject]);
                const sortedBySubject = [...subjectStudents].sort((a, b) => b.marks[subject].score - a.marks[subject].score);
                const subjectRank = sortedBySubject.findIndex(s => s.id === student.id) + 1;
                
                return `
                    <tr>
                        <td>${subjectName}</td>
                        <td>${data.score}</td>
                        <td>
                            <span class="grade-badge grade-${data.grade.toLowerCase()}">
                                ${data.grade}
                            </span>
                        </td>
                        <td>
                            <span class="mark-status ${data.status === 'passed' ? 'status-passed' : 'status-failed'}">
                                ${data.status === 'passed' ? 'LULUS' : 'GAGAL'}
                            </span>
                        </td>
                        <td>${subjectRank}</td>
                        <td>15 Okt 2023</td>
                    </tr>
                `;
            }).join('');
            
            // Update analysis
            const analysis = getStudentAnalysis(student);
            document.getElementById('studentAnalysis').textContent = analysis;
            
            studentModal.classList.add('active');
        }

        // Get student analysis
        function getStudentAnalysis(student) {
            if (student.average >= 80) {
                return `Pelajar menunjukkan prestasi yang cemerlang dalam semua subjek. Pencapaian tertinggi dalam Matematik (${student.marks.matematik.score}%) dan terendah dalam Bahasa Inggeris (${student.marks.bahasa_inggeris.score}%). Teruskan usaha!`;
            } else if (student.average >= 60) {
                return `Prestasi pelajar adalah baik. Mencapai gred B secara keseluruhan. Perlu meningkatkan prestasi dalam Bahasa Inggeris (${student.marks.bahasa_inggeris.score}%) untuk mencapai keputusan yang lebih baik.`;
            } else if (student.average >= 40) {
                return `Pelajar berada pada tahap memuaskan. Perlu bimbingan tambahan khususnya dalam subjek Sains (${student.marks.sains.score}%) dan Matematik (${student.marks.matematik.score}%).`;
            } else {
                return `Prestasi pelajar memerlukan perhatian serius. Semua subjek menunjukkan pencapaian rendah. Disyorkan untuk program bimbingan intensif dan komunikasi dengan ibu bapa.`;
            }
        }

        // View subject details
        function viewSubjectDetails(subjectId) {
            const subject = currentSubjects.find(s => s.id === subjectId);
            if (!subject) return;
            
            // Get students for this subject
            const subjectKey = subject.name.toLowerCase().replace(' & ', '_').replace(' ', '_');
            const subjectStudents = currentStudents
                .filter(s => s.marks[subjectKey])
                .sort((a, b) => b.marks[subjectKey].score - a.marks[subjectKey].score);
            
            // Create analysis
            let analysis = `Subjek ${subject.name} menunjukkan purata ${subject.averageScore.toFixed(1)}% untuk kelas ${subject.class}. `;
            analysis += `Pencapaian tertinggi: ${subject.highestScore}%, terendah: ${subject.lowestScore}%. `;
            
            if (subject.averageScore >= 80) {
                analysis += `Prestasi keseluruhan adalah cemerlang dengan ${subject.gradeDistribution.A} pelajar mencapai gred A.`;
            } else if (subject.averageScore >= 60) {
                analysis += `Prestasi keseluruhan adalah baik. ${subject.gradeDistribution.A + subject.gradeDistribution.B} pelajar mencapai gred A atau B.`;
            } else {
                analysis += `Perlu perhatian khusus kerana ${subject.gradeDistribution.E + subject.gradeDistribution.F} pelajar berada di bawah tahap memuaskan.`;
            }
            
            showNotification(`Butiran Subjek: ${subject.name}`, 'info', analysis);
        }

        // Sort by marks
        function susunMengikutMarkah() {
            filteredStudents.sort((a, b) => b.average - a.average);
            
            // Update ranks
            filteredStudents.forEach((student, index) => {
                student.rank = index + 1;
            });
            
            loadStudentTableView();
            showNotification('Pelajar disusun mengikut markah tertinggi', 'info');
        }

        // Show passed only
        function tunjukkanLulusSahaja() {
            filteredStudents = currentStudents.filter(student => student.overallGrade !== 'F');
            updateViews();
            showNotification('Memaparkan pelajar lulus sahaja', 'info');
        }

        // Show failed only
        function tunjukkanGagalSahaja() {
            filteredStudents = currentStudents.filter(student => student.overallGrade === 'F');
            updateViews();
            showNotification('Memaparkan pelajar gagal sahaja', 'warning');
        }

        // Add assessment
        function tambahPenilaian() {
            showNotification('Fungsi tambah penilaian akan dibuka dalam halaman berasingan', 'info');
        }

        // Generate analysis
        function janaAnalisis() {
            const totalStudents = currentStudents.length;
            const averageScore = currentStudents.reduce((sum, student) => sum + student.average, 0) / totalStudents;
            const topPerformer = [...currentStudents].sort((a, b) => b.average - a.average)[0];
            const lowestPerformer = [...currentStudents].sort((a, b) => a.average - b.average)[0];
            
            const analysis = `
                <strong>Analisis Prestasi Kelas:</strong><br><br>
                • Purata Keseluruhan: ${averageScore.toFixed(1)}%<br>
                • Pelajar Terbaik: ${topPerformer.name} (${topPerformer.average.toFixed(1)}%)<br>
                • Perlu Bimbingan: ${lowestPerformer.name} (${lowestPerformer.average.toFixed(1)}%)<br>
                • Subjek Terbaik: Matematik (78.4%)<br>
                • Subjek Perlu Peningkatan: Bahasa Inggeris (71.4%)<br><br>
                <strong>Cadangan:</strong> Fokus kepada bimbingan Bahasa Inggeris dan program sokongan untuk pelajar lemah.
            `;
            
            showNotification('Analisis Prestasi Dijana', 'success', analysis);
        }

        // Open export modal
        function openExportModal() {
            exportModal.classList.add('active');
        }

        // Close export modal
        function closeExportModal() {
            exportModal.classList.remove('active');
        }

        // Export to PDF
        function exportToPDF() {
            showNotification('Menyediakan dokumen PDF...', 'info');
            setTimeout(() => {
                showNotification('Dokumen PDF berjaya dijana', 'success');
            }, 1500);
        }

        // Export to Excel
        function exportToExcel() {
            showNotification('Menyediakan fail Excel...', 'info');
            setTimeout(() => {
                showNotification('Fail Excel berjaya dijana', 'success');
            }, 1500);
        }

        // Export to CSV
        function exportToCSV() {
            showNotification('Menyediakan fail CSV...', 'info');
            setTimeout(() => {
                showNotification('Fail CSV berjaya dijana', 'success');
            }, 1500);
        }

        // Export to print
        function exportToPrint() {
            window.print();
            showNotification('Paparan cetak dibuka', 'info');
        }

        // Start export
        function startExport() {
            const includeRank = document.getElementById('exportRank').checked;
            const includeAnalysis = document.getElementById('exportAnalysis').checked;
            
            showNotification(`Mengeksport data dengan pilihan: ${includeRank ? 'Dengan kedudukan' : 'Tanpa kedudukan'}`, 'info');
            closeExportModal();
            
            setTimeout(() => {
                showNotification('Data berjaya dieksport', 'success');
            }, 2000);
        }

        // Close student modal
        function closeStudentModal() {
            studentModal.classList.remove('active');
        }

        // Reload data
        function muatSemulaData() {
            filteredStudents = [...currentStudents];
            filteredSubjects = [...currentSubjects];
            
            // Reset filters
            document.getElementById('searchInput').value = '';
            document.getElementById('filterClass').value = '';
            document.getElementById('filterSubject').value = '';
            document.getElementById('filterAssessment').value = '';
            document.getElementById('filterGrade').value = '';
            
            updateViews();
            showNotification('Data disegarkan semula', 'success');
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterClass').value = '';
            document.getElementById('filterSubject').value = '';
            document.getElementById('filterAssessment').value = '';
            document.getElementById('filterGrade').value = '';
            
            filteredStudents = [...currentStudents];
            filteredSubjects = [...currentSubjects];
            
            updateViews();
            showNotification('Semua penapis telah dikembalikan kepada tetapan asal', 'success');
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
            
            // Close modal when clicking outside
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    closeStudentModal();
                    closeExportModal();
                }
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
            
            @media print {
                .header, .sidebar, .page-actions, .marks-actions, .tabs, .search-section {
                    display: none !important;
                }
                
                .main-content {
                    margin: 0 !important;
                    padding: 20px !important;
                }
                
                body {
                    background: white !important;
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