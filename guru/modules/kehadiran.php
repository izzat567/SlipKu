<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kehadiran - SlipKu</title>
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

        /* Attendance Stats */
        .attendance-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            margin: 0 auto 15px;
        }

        .stat-icon.present {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .stat-icon.absent {
            background: linear-gradient(135deg, var(--danger), #f87171);
        }

        .stat-icon.late {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .stat-icon.medical {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .stat-icon.permission {
            background: linear-gradient(135deg, #7c3aed, #a78bfa);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--medium-gray);
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

        /* Attendance Calendar */
        .attendance-calendar {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .calendar-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .calendar-title i {
            color: var(--info);
        }

        .calendar-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .calendar-month {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            min-width: 200px;
            text-align: center;
        }

        /* Calendar Grid */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .calendar-day-header {
            background: var(--primary-light);
            padding: 15px;
            text-align: center;
            font-weight: 600;
            color: var(--primary);
            font-size: 14px;
        }

        .calendar-day {
            background: var(--white);
            padding: 15px;
            min-height: 120px;
            border: 1px solid #f3f4f6;
            transition: var(--transition);
            position: relative;
        }

        .calendar-day:hover {
            background: var(--light-gray);
        }

        .calendar-day.empty {
            background: #f9fafb;
        }

        .calendar-day-number {
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 10px;
            font-size: 16px;
        }

        .calendar-day.today .calendar-day-number {
            background: var(--primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .attendance-summary {
            font-size: 11px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .attendance-count {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .count-present {
            color: var(--success);
        }

        .count-absent {
            color: var(--danger);
        }

        .count-late {
            color: var(--warning);
        }

        /* Attendance Table */
        .attendance-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .attendance-table th {
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

        .attendance-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .attendance-table tr:hover td {
            background: var(--primary-light);
        }

        /* Student Info */
        .student-info {
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

        .student-details h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-details p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 80px;
        }

        .status-present {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-absent {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-late {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-medical {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .status-permission {
            background: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }

        /* Attendance Actions */
        .attendance-actions {
            display: flex;
            gap: 8px;
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

        /* Attendance Chart */
        .attendance-chart {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 20px;
        }

        /* Daily Attendance */
        .daily-attendance {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .daily-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .daily-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .daily-title i {
            color: var(--success);
        }

        .date-picker {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            padding: 10px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .date-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Attendance Quick Actions */
        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        /* Modal Styles */
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
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
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
        }

        .modal-close:hover {
            color: var(--danger);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            min-height: 100px;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        /* Attendance Status Selector */
        .status-selector {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .status-option {
            flex: 1;
            text-align: center;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: var(--transition);
        }

        .status-option:hover {
            background: var(--light-gray);
        }

        .status-option.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .status-option.present.active {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.1);
        }

        .status-option.absent.active {
            border-color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }

        .status-option.late.active {
            border-color: var(--warning);
            background: rgba(245, 158, 11, 0.1);
        }

        .status-option.medical.active {
            border-color: var(--info);
            background: rgba(59, 130, 246, 0.1);
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
            
            .attendance-stats {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
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
            
            .attendance-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                font-size: 12px;
            }
            
            .calendar-day {
                min-height: 80px;
                padding: 10px;
            }
            
            .calendar-day-header {
                padding: 10px;
            }
            
            .status-selector {
                flex-wrap: wrap;
            }
            
            .status-option {
                min-width: calc(50% - 5px);
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
            
            .attendance-stats {
                grid-template-columns: 1fr;
            }
            
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
            }
            
            .calendar-day-header {
                font-size: 11px;
                padding: 5px;
            }
            
            .calendar-day {
                padding: 5px;
                min-height: 70px;
            }
            
            .calendar-day-number {
                font-size: 12px;
            }
            
            .tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .attendance-actions {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Take Attendance Modal -->
    <div class="modal" id="attendanceModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Ambil Kehadiran</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="attendanceForm" onsubmit="simpanKehadiran(event)">
                <div class="form-group">
                    <label class="form-label">Tarikh</label>
                    <input type="date" class="form-input" id="attendanceDate" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kelas</label>
                    <select class="form-select" id="attendanceClass" required>
                        <option value="">Pilih Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sesi</label>
                    <select class="form-select" id="attendanceSession" required>
                        <option value="">Pilih Sesi</option>
                        <option value="morning">Pagi</option>
                        <option value="afternoon">Petang</option>
                        <option value="full">Sepenuh Hari</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status Kehadiran</label>
                    <div class="status-selector">
                        <div class="status-option present" onclick="pilihStatus('present')">
                            <i class="fas fa-check-circle" style="color: var(--success); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Hadir</div>
                        </div>
                        <div class="status-option absent" onclick="pilihStatus('absent')">
                            <i class="fas fa-times-circle" style="color: var(--danger); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Tidak Hadir</div>
                        </div>
                        <div class="status-option late" onclick="pilihStatus('late')">
                            <i class="fas fa-clock" style="color: var(--warning); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Lewat</div>
                        </div>
                    </div>
                    <input type="hidden" id="attendanceStatus" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Catatan (Pilihan)</label>
                    <textarea class="form-textarea" id="attendanceNote" placeholder="Catatan khas seperti sebab ketidakhadiran..."></textarea>
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Simpan Kehadiran
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Update Modal -->
    <div class="modal" id="bulkUpdateModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Kemaskini Kehadiran Pukal</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="bulkUpdateContent">
                <!-- Bulk update content will be loaded here -->
            </div>
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
            <a href="dashboard-admin.html" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Kehadiran</p>
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
                    <span class="notification-badge">5</span>
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
                <span class="badge">7</span>
            </a>
            <a href="kehadiran.html" class="sidebar-item active">
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
                <h2>Kehadiran 📋</h2>
                <p>Pengurusan dan pemantauan kehadiran pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaKehadiran()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="ambilKehadiranModal()">
                    <i class="fas fa-clipboard-check"></i>
                    Ambil Kehadiran
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="ubahTab('today')">
                <i class="fas fa-calendar-day"></i>
                Hari Ini
            </button>
            <button class="tab-btn" onclick="ubahTab('calendar')">
                <i class="fas fa-calendar-alt"></i>
                Kalendar
            </button>
            <button class="tab-btn" onclick="ubahTab('reports')">
                <i class="fas fa-chart-bar"></i>
                Laporan
            </button>
            <button class="tab-btn" onclick="ubahTab('history')">
                <i class="fas fa-history"></i>
                Sejarah
            </button>
        </div>

        <!-- Attendance Statistics -->
        <div class="attendance-stats">
            <div class="stat-card">
                <div class="stat-icon present">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-value" id="presentCount">28</div>
                <div class="stat-label">Hadir</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon absent">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-value" id="absentCount">2</div>
                <div class="stat-label">Tidak Hadir</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon late">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" id="lateCount">3</div>
                <div class="stat-label">Lewat</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon medical">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="stat-value" id="medicalCount">1</div>
                <div class="stat-label">Cuti Sakit</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon permission">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-value" id="permissionCount">0</div>
                <div class="stat-label">Kebenaran</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Tapis Kehadiran
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="tapilkanKehadiran()">
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                        <option value="all">Semua Kelas</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Bulan:</label>
                    <select class="filter-select" id="filterMonth" onchange="tapilkanKehadiran()">
                        <option value="3">Mac 2024</option>
                        <option value="2">Februari 2024</option>
                        <option value="1">Januari 2024</option>
                        <option value="12">Disember 2023</option>
                        <option value="all">Keseluruhan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="tapilkanKehadiran()">
                        <option value="all">Semua Status</option>
                        <option value="present">Hadir</option>
                        <option value="absent">Tidak Hadir</option>
                        <option value="late">Lewat</option>
                        <option value="medical">Cuti Sakit</option>
                        <option value="permission">Kebenaran</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Sesi:</label>
                    <select class="filter-select" id="filterSession" onchange="tapilkanKehadiran()">
                        <option value="all">Semua Sesi</option>
                        <option value="morning">Pagi</option>
                        <option value="afternoon">Petang</option>
                        <option value="full">Sepenuh Hari</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Today's Attendance Tab -->
        <div id="todayTab">
            <!-- Quick Actions -->
            <div class="quick-actions">
                <button class="action-btn success" onclick="tandakanSemuaHadir()">
                    <i class="fas fa-check-circle"></i>
                    Tandakan Semua Hadir
                </button>
                <button class="action-btn danger" onclick="tandakanSemuaTidakHadir()">
                    <i class="fas fa-times-circle"></i>
                    Tandakan Semua Tidak Hadir
                </button>
                <button class="action-btn primary" onclick="kemaskiniPukal()">
                    <i class="fas fa-edit"></i>
                    Kemaskini Pukal
                </button>
                <button class="action-btn info" onclick="janaLaporanHarian()">
                    <i class="fas fa-file-export"></i>
                    Laporan Harian
                </button>
            </div>

            <!-- Daily Attendance -->
            <div class="daily-attendance">
                <div class="daily-header">
                    <div class="daily-title">
                        <i class="fas fa-calendar-day"></i>
                        <span>Kehadiran Harian - <span id="currentDate">15 Mac 2024</span></span>
                    </div>
                    <div class="date-picker">
                        <input type="date" class="date-input" id="datePicker" onchange="ubahTarikhHarian()">
                        <button class="action-btn secondary" onclick="kembaliKeHariIni()">
                            <i class="fas fa-calendar-day"></i>
                            Hari Ini
                        </button>
                    </div>
                </div>
                
                <div class="attendance-table-container">
                    <div style="overflow-x: auto;">
                        <table class="attendance-table">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>PELAJAR</th>
                                    <th>KELAS</th>
                                    <th>STATUS PAGI</th>
                                    <th>STATUS PETANG</th>
                                    <th>CATATAN</th>
                                    <th>TINDAKAN</th>
                                </tr>
                            </thead>
                            <tbody id="dailyAttendanceBody">
                                <!-- Daily attendance rows will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Tab -->
        <div id="calendarTab" style="display: none;">
            <div class="attendance-calendar">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Kalendar Kehadiran</span>
                    </div>
                    <div class="calendar-nav">
                        <button class="action-btn secondary" onclick="ubahBulan('prev')">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="calendar-month" id="currentMonth">Mac 2024</div>
                        <button class="action-btn secondary" onclick="ubahBulan('next')">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <button class="action-btn primary" onclick="kembaliKeHariIni()">
                            <i class="fas fa-calendar-day"></i>
                            Hari Ini
                        </button>
                    </div>
                </div>
                
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Calendar will be generated here -->
                </div>
            </div>
        </div>

        <!-- Reports Tab -->
        <div id="reportsTab" style="display: none;">
            <!-- Attendance Chart -->
            <div class="attendance-chart">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-chart-bar"></i>
                        <span>Analisis Kehadiran</span>
                    </div>
                    <div class="calendar-nav">
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
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="attendance-table-container">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-table"></i>
                        <span>Ringkasan Kehadiran Bulanan</span>
                    </div>
                    <div class="calendar-nav">
                        <button class="action-btn success" onclick="janaLaporanBulanan()">
                            <i class="fas fa-file-pdf"></i>
                            Laporan PDF
                        </button>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>PELAJAR</th>
                                <th>KELAS</th>
                                <th>HADIR</th>
                                <th>TIDAK HADIR</th>
                                <th>LEWAT</th>
                                <th>CUTI SAKIT</th>
                                <th>PERATUSAN</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceSummaryBody">
                            <!-- Attendance summary rows will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- History Tab -->
        <div id="historyTab" style="display: none;">
            <div class="attendance-table-container">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-history"></i>
                        <span>Sejarah Kehadiran</span>
                    </div>
                    <div class="calendar-nav">
                        <button class="action-btn info" onclick="eksportSejarah()">
                            <i class="fas fa-file-export"></i>
                            Eksport Data
                        </button>
                    </div>
                </div>
                
                <div style="overflow-x: auto;">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>TARIKH</th>
                                <th>KELAS</th>
                                <th>SESI</th>
                                <th>HADIR</th>
                                <th>TIDAK HADIR</th>
                                <th>LEWAT</th>
                                <th>CATATAN</th>
                                <th>DIKEMASKINI OLEH</th>
                                <th>TINDAKAN</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceHistoryBody">
                            <!-- Attendance history rows will be loaded here -->
                        </tbody>
                    </table>
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
        const attendanceModal = document.getElementById('attendanceModal');
        const bulkUpdateModal = document.getElementById('bulkUpdateModal');
        const bulkUpdateContent = document.getElementById('bulkUpdateContent');

        // Tabs
        const todayTab = document.getElementById('todayTab');
        const calendarTab = document.getElementById('calendarTab');
        const reportsTab = document.getElementById('reportsTab');
        const historyTab = document.getElementById('historyTab');

        // Calendar state
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let currentChart = null;
        let selectedStatus = 'present';

        // Sample data
        const sampleStudents = [
            { id: 'STU001', name: 'Ahmad bin Ali', class: '6A', avatar: 'AA' },
            { id: 'STU002', name: 'Siti binti Abu', class: '6A', avatar: 'SA' },
            { id: 'STU003', name: 'Muhammad bin Hassan', class: '6A', avatar: 'MH' },
            { id: 'STU004', name: 'Aisyah binti Musa', class: '6A', avatar: 'AM' },
            { id: 'STU005', name: 'Ali bin Abdullah', class: '6A', avatar: 'AA' },
            { id: 'STU006', name: 'Fatimah binti Omar', class: '6A', avatar: 'FO' },
            { id: 'STU007', name: 'Hassan bin Ismail', class: '6A', avatar: 'HI' },
            { id: 'STU008', name: 'Zainab binti Yusuf', class: '6A', avatar: 'ZY' },
            { id: 'STU009', name: 'Aiman bin Karim', class: '6B', avatar: 'AK' },
            { id: 'STU010', name: 'Nurul binti Hamid', class: '6B', avatar: 'NH' }
        ];

        // Sample attendance data
        const sampleAttendance = [
            {
                id: 'ATT001',
                studentId: 'STU001',
                date: new Date(2024, 2, 15), // 15 March 2024
                session: 'morning',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 30)
            },
            {
                id: 'ATT002',
                studentId: 'STU001',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT003',
                studentId: 'STU002',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'late',
                note: 'Tiba lewat 15 minit',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 45)
            },
            {
                id: 'ATT004',
                studentId: 'STU002',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT005',
                studentId: 'STU003',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 30)
            },
            {
                id: 'ATT006',
                studentId: 'STU003',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT007',
                studentId: 'STU004',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'medical',
                note: 'Cuti sakit, ada surat doktor',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 9, 0)
            },
            {
                id: 'ATT008',
                studentId: 'STU004',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'medical',
                note: 'Cuti sakit, ada surat doktor',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT009',
                studentId: 'STU005',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 30)
            },
            {
                id: 'ATT010',
                studentId: 'STU005',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT011',
                studentId: 'STU006',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'late',
                note: 'Tiba lewat 10 minit',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 40)
            },
            {
                id: 'ATT012',
                studentId: 'STU006',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'absent',
                note: 'Ibu bapa telefon, ada urusan keluarga',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 14, 0)
            },
            {
                id: 'ATT013',
                studentId: 'STU007',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 30)
            },
            {
                id: 'ATT014',
                studentId: 'STU007',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            {
                id: 'ATT015',
                studentId: 'STU008',
                date: new Date(2024, 2, 15),
                session: 'morning',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 8, 30)
            },
            {
                id: 'ATT016',
                studentId: 'STU008',
                date: new Date(2024, 2, 15),
                session: 'afternoon',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 15, 13, 30)
            },
            // Previous days data
            {
                id: 'ATT017',
                studentId: 'STU001',
                date: new Date(2024, 2, 14),
                session: 'full',
                status: 'present',
                note: '',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 14, 8, 30)
            },
            {
                id: 'ATT018',
                studentId: 'STU002',
                date: new Date(2024, 2, 14),
                session: 'full',
                status: 'late',
                note: 'Tiba lewat 20 minit',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 14, 8, 50)
            },
            {
                id: 'ATT019',
                studentId: 'STU006',
                date: new Date(2024, 2, 14),
                session: 'full',
                status: 'absent',
                note: 'Demam',
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date(2024, 2, 14, 9, 0)
            }
        ];

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Update current date display
            updateCurrentDate();
            
            // Load today's attendance
            loadDailyAttendance();
            
            // Initialize calendar
            generateCalendar(currentMonth, currentYear);
            
            // Load attendance summary
            loadAttendanceSummary();
            
            // Load attendance history
            loadAttendanceHistory();
            
            // Initialize chart
            initializeAttendanceChart();
            
            // Update statistics
            updateAttendanceStatistics();
            
            // Set today's date in date picker
            document.getElementById('datePicker').value = currentDate.toISOString().split('T')[0];
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
            attendanceModal.addEventListener('click', function(e) {
                if (e.target === attendanceModal) {
                    tutupModal();
                }
            });
            
            bulkUpdateModal.addEventListener('click', function(e) {
                if (e.target === bulkUpdateModal) {
                    tutupModal();
                }
            });
            
            // Set default date in attendance modal
            document.getElementById('attendanceDate').value = currentDate.toISOString().split('T')[0];
        }

        // Change tab
        function ubahTab(tab) {
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active tab content
            todayTab.style.display = 'none';
            calendarTab.style.display = 'none';
            reportsTab.style.display = 'none';
            historyTab.style.display = 'none';
            
            if (tab === 'today') {
                todayTab.style.display = 'block';
                loadDailyAttendance();
            } else if (tab === 'calendar') {
                calendarTab.style.display = 'block';
            } else if (tab === 'reports') {
                reportsTab.style.display = 'block';
                updateAttendanceChart();
            } else if (tab === 'history') {
                historyTab.style.display = 'block';
            }
        }

        // Update current date display
        function updateCurrentDate() {
            const dateStr = currentDate.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('currentDate').textContent = dateStr;
        }

        // Load daily attendance
        function loadDailyAttendance() {
            const dailyAttendanceBody = document.getElementById('dailyAttendanceBody');
            const selectedDate = document.getElementById('datePicker').value 
                ? new Date(document.getElementById('datePicker').value)
                : currentDate;
            
            // Filter students by class
            const selectedClass = document.getElementById('filterClass').value;
            const filteredStudents = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            dailyAttendanceBody.innerHTML = filteredStudents.map((student, index) => {
                // Get attendance for this student on selected date
                const morningAttendance = sampleAttendance.find(att => 
                    att.studentId === student.id && 
                    att.date.toDateString() === selectedDate.toDateString() &&
                    att.session === 'morning'
                );
                
                const afternoonAttendance = sampleAttendance.find(att => 
                    att.studentId === student.id && 
                    att.date.toDateString() === selectedDate.toDateString() &&
                    att.session === 'afternoon'
                );
                
                const fullDayAttendance = sampleAttendance.find(att => 
                    att.studentId === student.id && 
                    att.date.toDateString() === selectedDate.toDateString() &&
                    att.session === 'full'
                );
                
                let morningStatus = 'Belum Diambil';
                let afternoonStatus = 'Belum Diambil';
                
                if (fullDayAttendance) {
                    morningStatus = fullDayAttendance.status;
                    afternoonStatus = fullDayAttendance.status;
                } else {
                    if (morningAttendance) morningStatus = morningAttendance.status;
                    if (afternoonAttendance) afternoonStatus = afternoonAttendance.status;
                }
                
                // Get status badge
                const getStatusBadge = (status) => {
                    switch(status) {
                        case 'present': return '<span class="status-badge status-present">Hadir</span>';
                        case 'absent': return '<span class="status-badge status-absent">Tidak Hadir</span>';
                        case 'late': return '<span class="status-badge status-late">Lewat</span>';
                        case 'medical': return '<span class="status-badge status-medical">Cuti Sakit</span>';
                        case 'permission': return '<span class="status-badge status-permission">Kebenaran</span>';
                        default: return '<span class="status-badge" style="background: #e5e7eb; color: var(--medium-gray);">Belum Diambil</span>';
                    }
                };
                
                // Get notes
                const notes = [];
                if (fullDayAttendance && fullDayAttendance.note) notes.push(fullDayAttendance.note);
                if (morningAttendance && morningAttendance.note && !fullDayAttendance) notes.push(`Pagi: ${morningAttendance.note}`);
                if (afternoonAttendance && afternoonAttendance.note && !fullDayAttendance) notes.push(`Petang: ${afternoonAttendance.note}`);
                
                return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">${student.avatar}</div>
                                <div class="student-details">
                                    <h4>${student.name}</h4>
                                    <p>${student.class}</p>
                                </div>
                            </div>
                        </td>
                        <td>${student.class}</td>
                        <td>${getStatusBadge(morningStatus)}</td>
                        <td>${getStatusBadge(afternoonStatus)}</td>
                        <td>${notes.join('<br>') || '-'}</td>
                        <td>
                            <div class="attendance-actions">
                                <button class="action-btn primary" onclick="kemaskiniKehadiran('${student.id}', 'morning')">
                                    <i class="fas fa-edit"></i>
                                    Pagi
                                </button>
                                <button class="action-btn info" onclick="kemaskiniKehadiran('${student.id}', 'afternoon')">
                                    <i class="fas fa-edit"></i>
                                    Petang
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update attendance statistics
        function updateAttendanceStatistics() {
            const today = currentDate.toDateString();
            
            // Count different statuses for today
            const todayAttendance = sampleAttendance.filter(att => 
                att.date.toDateString() === today
            );
            
            const presentCount = todayAttendance.filter(att => att.status === 'present').length;
            const absentCount = todayAttendance.filter(att => att.status === 'absent').length;
            const lateCount = todayAttendance.filter(att => att.status === 'late').length;
            const medicalCount = todayAttendance.filter(att => att.status === 'medical').length;
            const permissionCount = todayAttendance.filter(att => att.status === 'permission').length;
            
            document.getElementById('presentCount').textContent = presentCount;
            document.getElementById('absentCount').textContent = absentCount;
            document.getElementById('lateCount').textContent = lateCount;
            document.getElementById('medicalCount').textContent = medicalCount;
            document.getElementById('permissionCount').textContent = permissionCount;
        }

        // Generate calendar
        function generateCalendar(month, year) {
            const monthNames = [
                "Januari", "Februari", "Mac", "April", "Mei", "Jun",
                "Julai", "Ogos", "September", "Oktober", "November", "Disember"
            ];
            
            // Update month display
            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
            
            // Clear calendar
            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';
            
            // Add day headers
            const dayHeaders = ['Ahd', 'Isn', 'Sel', 'Rab', 'Kha', 'Jum', 'Sab'];
            dayHeaders.forEach(day => {
                const dayHeader = document.createElement('div');
                dayHeader.className = 'calendar-day-header';
                dayHeader.textContent = day;
                calendarGrid.appendChild(dayHeader);
            });
            
            // Get first day of month
            const firstDay = new Date(year, month, 1);
            const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            // Get last day of month
            const lastDay = new Date(year, month + 1, 0);
            const totalDays = lastDay.getDate();
            
            // Get today's date for highlighting
            const today = new Date();
            const isToday = (day) => {
                return day === today.getDate() && 
                       month === today.getMonth() && 
                       year === today.getFullYear();
            };
            
            // Add empty cells for days before first day of month
            for (let i = 0; i < startingDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendarGrid.appendChild(emptyDay);
            }
            
            // Add days of month
            for (let day = 1; day <= totalDays; day++) {
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';
                
                // Check if today
                if (isToday(day)) {
                    dayElement.classList.add('today');
                }
                
                // Add day number
                const dayNumber = document.createElement('div');
                dayNumber.className = 'calendar-day-number';
                dayNumber.textContent = day;
                dayElement.appendChild(dayNumber);
                
                // Get attendance data for this day
                const currentDate = new Date(year, month, day);
                const dayAttendance = sampleAttendance.filter(att => 
                    att.date.toDateString() === currentDate.toDateString()
                );
                
                if (dayAttendance.length > 0) {
                    // Count different statuses
                    const presentCount = dayAttendance.filter(att => att.status === 'present').length;
                    const absentCount = dayAttendance.filter(att => att.status === 'absent').length;
                    const lateCount = dayAttendance.filter(att => att.status === 'late').length;
                    
                    // Add attendance summary
                    const summary = document.createElement('div');
                    summary.className = 'attendance-summary';
                    
                    if (presentCount > 0) {
                        const presentSpan = document.createElement('div');
                        presentSpan.className = 'attendance-count count-present';
                        presentSpan.innerHTML = `<i class="fas fa-user-check"></i> ${presentCount}`;
                        summary.appendChild(presentSpan);
                    }
                    
                    if (absentCount > 0) {
                        const absentSpan = document.createElement('div');
                        absentSpan.className = 'attendance-count count-absent';
                        absentSpan.innerHTML = `<i class="fas fa-user-times"></i> ${absentCount}`;
                        summary.appendChild(absentSpan);
                    }
                    
                    if (lateCount > 0) {
                        const lateSpan = document.createElement('div');
                        lateSpan.className = 'attendance-count count-late';
                        lateSpan.innerHTML = `<i class="fas fa-clock"></i> ${lateCount}`;
                        summary.appendChild(lateSpan);
                    }
                    
                    dayElement.appendChild(summary);
                }
                
                // Add click event to view day's attendance
                dayElement.onclick = () => lihatKehadiranHarian(year, month, day);
                
                calendarGrid.appendChild(dayElement);
            }
            
            // Add empty cells for remaining days
            const totalCells = 42; // 6 rows * 7 days
            const filledCells = startingDay + totalDays;
            for (let i = filledCells; i < totalCells; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day empty';
                calendarGrid.appendChild(emptyDay);
            }
        }

        // Change month
        function ubahBulan(direction) {
            if (direction === 'prev') {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
            } else if (direction === 'next') {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
            }
            
            generateCalendar(currentMonth, currentYear);
        }

        // Go back to today
        function kembaliKeHariIni() {
            currentDate = new Date();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
            
            // Update date picker
            document.getElementById('datePicker').value = currentDate.toISOString().split('T')[0];
            
            // Update displays
            updateCurrentDate();
            generateCalendar(currentMonth, currentYear);
            loadDailyAttendance();
            updateAttendanceStatistics();
            
            showNotification('Kembali ke hari ini', 'info');
        }

        // Change date for daily view
        function ubahTarikhHarian() {
            const dateInput = document.getElementById('datePicker').value;
            if (dateInput) {
                currentDate = new Date(dateInput);
                updateCurrentDate();
                loadDailyAttendance();
            }
        }

        // Filter attendance
        function tapilkanKehadiran() {
            loadDailyAttendance();
            loadAttendanceSummary();
            loadAttendanceHistory();
            
            showNotification('Kehadiran ditapis', 'info');
        }

        // Load attendance summary
        function loadAttendanceSummary() {
            const attendanceSummaryBody = document.getElementById('attendanceSummaryBody');
            const selectedClass = document.getElementById('filterClass').value;
            const selectedMonth = parseInt(document.getElementById('filterMonth').value);
            
            // Filter students
            const filteredStudents = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            attendanceSummaryBody.innerHTML = filteredStudents.map(student => {
                // Get attendance for this student
                let studentAttendance = sampleAttendance.filter(att => 
                    att.studentId === student.id
                );
                
                // Filter by month if not "all"
                if (selectedMonth !== 'all') {
                    studentAttendance = studentAttendance.filter(att => 
                        att.date.getMonth() + 1 === selectedMonth
                    );
                }
                
                // Count different statuses
                const presentCount = studentAttendance.filter(att => att.status === 'present').length;
                const absentCount = studentAttendance.filter(att => att.status === 'absent').length;
                const lateCount = studentAttendance.filter(att => att.status === 'late').length;
                const medicalCount = studentAttendance.filter(att => att.status === 'medical').length;
                
                // Calculate percentage
                const totalDays = studentAttendance.length;
                const attendancePercentage = totalDays > 0 ? Math.round((presentCount / totalDays) * 100) : 0;
                
                // Get status
                let status = '';
                let statusColor = '';
                if (attendancePercentage >= 95) {
                    status = 'Cemerlang';
                    statusColor = 'var(--success)';
                } else if (attendancePercentage >= 90) {
                    status = 'Baik';
                    statusColor = 'var(--info)';
                } else if (attendancePercentage >= 85) {
                    status = 'Memuaskan';
                    statusColor = 'var(--warning)';
                } else {
                    status = 'Perlu Perhatian';
                    statusColor = 'var(--danger)';
                }
                
                return `
                    <tr>
                        <td>
                            <div class="student-info">
                                <div class="student-avatar">${student.avatar}</div>
                                <div class="student-details">
                                    <h4>${student.name}</h4>
                                </div>
                            </div>
                        </td>
                        <td>${student.class}</td>
                        <td>${presentCount}</td>
                        <td>${absentCount}</td>
                        <td>${lateCount}</td>
                        <td>${medicalCount}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; background: #e5e7eb; height: 6px; border-radius: 3px;">
                                    <div style="background: var(--primary); height: 100%; border-radius: 3px; width: ${attendancePercentage}%"></div>
                                </div>
                                <div style="font-size: 12px; font-weight: 600; min-width: 40px;">${attendancePercentage}%</div>
                            </div>
                        </td>
                        <td>
                            <span style="color: ${statusColor}; font-weight: 600;">${status}</span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load attendance history
        function loadAttendanceHistory() {
            const attendanceHistoryBody = document.getElementById('attendanceHistoryBody');
            const selectedClass = document.getElementById('filterClass').value;
            const selectedStatus = document.getElementById('filterStatus').value;
            
            // Group attendance by date and class
            const attendanceByDate = {};
            
            sampleAttendance.forEach(att => {
                const dateStr = att.date.toLocaleDateString('ms-MY');
                const classOfStudent = sampleStudents.find(s => s.id === att.studentId)?.class;
                
                if (!attendanceByDate[dateStr]) {
                    attendanceByDate[dateStr] = {};
                }
                
                if (!attendanceByDate[dateStr][classOfStudent]) {
                    attendanceByDate[dateStr][classOfStudent] = {
                        present: 0,
                        absent: 0,
                        late: 0,
                        medical: 0,
                        permission: 0,
                        notes: [],
                        updatedBy: att.updatedBy
                    };
                }
                
                // Count statuses
                attendanceByDate[dateStr][classOfStudent][att.status]++;
                
                // Collect notes
                if (att.note && !attendanceByDate[dateStr][classOfStudent].notes.includes(att.note)) {
                    attendanceByDate[dateStr][classOfStudent].notes.push(att.note);
                }
            });
            
            // Convert to array and sort by date (newest first)
            const historyArray = [];
            Object.keys(attendanceByDate).forEach(dateStr => {
                Object.keys(attendanceByDate[dateStr]).forEach(className => {
                    const data = attendanceByDate[dateStr][className];
                    
                    // Filter by class
                    if (selectedClass !== 'all' && className !== selectedClass) return;
                    
                    // Filter by status
                    if (selectedStatus !== 'all' && data[selectedStatus] === 0) return;
                    
                    historyArray.push({
                        date: new Date(dateStr.split('/').reverse().join('-')),
                        class: className,
                        data: data
                    });
                });
            });
            
            // Sort by date (newest first)
            historyArray.sort((a, b) => b.date - a.date);
            
            attendanceHistoryBody.innerHTML = historyArray.map(item => {
                const dateStr = item.date.toLocaleDateString('ms-MY');
                const totalStudents = Object.values(item.data).slice(0, 5).reduce((a, b) => a + b, 0);
                const notes = item.data.notes.slice(0, 2).join('; ');
                
                return `
                    <tr>
                        <td>${dateStr}</td>
                        <td>${item.class}</td>
                        <td>${item.data.session || 'Sepenuh Hari'}</td>
                        <td>${item.data.present}</td>
                        <td>${item.data.absent}</td>
                        <td>${item.data.late}</td>
                        <td>${notes || '-'}</td>
                        <td>${item.data.updatedBy}</td>
                        <td>
                            <button class="action-btn info" onclick="lihatKehadiranTerperinci('${dateStr}', '${item.class}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Initialize attendance chart
        function initializeAttendanceChart() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            currentChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat'],
                    datasets: [
                        {
                            label: 'Hadir',
                            data: [28, 30, 29, 28, 27],
                            backgroundColor: 'rgba(16, 185, 129, 0.6)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Tidak Hadir',
                            data: [2, 0, 1, 2, 3],
                            backgroundColor: 'rgba(239, 68, 68, 0.6)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 2
                        },
                        {
                            label: 'Lewat',
                            data: [3, 2, 4, 3, 2],
                            backgroundColor: 'rgba(245, 158, 11, 0.6)',
                            borderColor: 'rgba(245, 158, 11, 1)',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Kehadiran Mingguan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });
        }

        // Update chart type
        function ubahJenisGraf(type) {
            if (currentChart) {
                currentChart.destroy();
            }
            
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            
            currentChart = new Chart(ctx, {
                type: type,
                data: {
                    labels: ['Hadir', 'Tidak Hadir', 'Lewat', 'Cuti Sakit', 'Kebenaran'],
                    datasets: [{
                        data: [28, 2, 3, 1, 0],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.6)',
                            'rgba(239, 68, 68, 0.6)',
                            'rgba(245, 158, 11, 0.6)',
                            'rgba(59, 130, 246, 0.6)',
                            'rgba(124, 58, 237, 0.6)'
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(124, 58, 237, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Taburan Kehadiran Hari Ini'
                        }
                    }
                }
            });
            
            showNotification(`Graf ditukar kepada jenis ${type === 'bar' ? 'Bar' : type === 'line' ? 'Line' : 'Pie'}`, 'info');
        }

        // Update attendance chart
        function updateAttendanceChart() {
            if (currentChart) {
                currentChart.update();
            }
        }

        // View day's attendance
        function lihatKehadiranHarian(year, month, day) {
            const date = new Date(year, month, day);
            const dateStr = date.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            // Get attendance for this day
            const dayAttendance = sampleAttendance.filter(att => 
                att.date.toDateString() === date.toDateString()
            );
            
            // Group by student
            const attendanceByStudent = {};
            dayAttendance.forEach(att => {
                const student = sampleStudents.find(s => s.id === att.studentId);
                if (student) {
                    if (!attendanceByStudent[student.id]) {
                        attendanceByStudent[student.id] = {
                            student: student,
                            morning: null,
                            afternoon: null,
                            full: null
                        };
                    }
                    
                    if (att.session === 'full') {
                        attendanceByStudent[student.id].full = att;
                    } else if (att.session === 'morning') {
                        attendanceByStudent[student.id].morning = att;
                    } else if (att.session === 'afternoon') {
                        attendanceByStudent[student.id].afternoon = att;
                    }
                }
            });
            
            let details = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Kehadiran ${dateStr}</h3>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                        <span style="background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-users"></i> ${Object.keys(attendanceByStudent).length} Pelajar
                        </span>
                        <span style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-user-check"></i> ${dayAttendance.filter(a => a.status === 'present').length} Hadir
                        </span>
                        <span style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-user-times"></i> ${dayAttendance.filter(a => a.status === 'absent').length} Tidak Hadir
                        </span>
                    </div>
                </div>
            `;
            
            if (Object.keys(attendanceByStudent).length === 0) {
                details += `
                    <div style="text-align: center; padding: 40px 20px;">
                        <i class="fas fa-calendar-times" style="font-size: 48px; color: var(--primary-light); margin-bottom: 15px;"></i>
                        <h3 style="margin-bottom: 10px; color: var(--dark-gray);">Tiada Rekod Kehadiran</h3>
                        <p style="color: var(--medium-gray);">Tiada kehadiran direkodkan untuk hari ini</p>
                    </div>
                `;
            } else {
                details += `
                    <div style="max-height: 300px; overflow-y: auto;">
                        ${Object.values(attendanceByStudent).map(data => {
                            const getStatusBadge = (status) => {
                                switch(status) {
                                    case 'present': return '<span class="status-badge status-present" style="font-size: 10px; padding: 3px 8px;">Hadir</span>';
                                    case 'absent': return '<span class="status-badge status-absent" style="font-size: 10px; padding: 3px 8px;">Tidak Hadir</span>';
                                    case 'late': return '<span class="status-badge status-late" style="font-size: 10px; padding: 3px 8px;">Lewat</span>';
                                    case 'medical': return '<span class="status-badge status-medical" style="font-size: 10px; padding: 3px 8px;">Cuti Sakit</span>';
                                    default: return '<span style="font-size: 10px; color: var(--medium-gray);">Tiada</span>';
                                }
                            };
                            
                            const morningStatus = data.full ? data.full.status : (data.morning ? data.morning.status : null);
                            const afternoonStatus = data.full ? data.full.status : (data.afternoon ? data.afternoon.status : null);
                            
                            return `
                                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                    <div class="student-avatar" style="width: 30px; height: 30px; font-size: 10px;">${data.student.avatar}</div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 13px;">${data.student.name}</div>
                                        <div style="font-size: 11px; color: var(--medium-gray);">${data.student.class}</div>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <div style="text-align: center;">
                                            <div style="font-size: 10px; color: var(--medium-gray); margin-bottom: 3px;">Pagi</div>
                                            ${getStatusBadge(morningStatus)}
                                        </div>
                                        <div style="text-align: center;">
                                            <div style="font-size: 10px; color: var(--medium-gray); margin-bottom: 3px;">Petang</div>
                                            ${getStatusBadge(afternoonStatus)}
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }
            
            showNotification('Kehadiran Harian', 'info', details);
        }

        // View detailed attendance
        function lihatKehadiranTerperinci(dateStr, className) {
            const details = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Kehadiran ${className} - ${dateStr}</h3>
                <p style="color: var(--medium-gray); margin-bottom: 20px;">Butiran lengkap kehadiran untuk kelas ${className} pada ${dateStr}</p>
                
                <div style="margin-bottom: 20px;">
                    <button class="action-btn primary" onclick="kemaskiniKehadiranKelas('${dateStr}', '${className}')" style="width: 100%;">
                        <i class="fas fa-edit"></i>
                        Kemaskini Kehadiran Kelas
                    </button>
                </div>
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Statistik Ringkas</div>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--success);">25</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Hadir</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--danger);">1</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Tidak Hadir</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--warning);">2</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Lewat</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--info);">1</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Cuti Sakit</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Catatan Khas</div>
                    <div style="font-size: 13px; color: var(--medium-gray);">
                        <p><i class="fas fa-circle" style="font-size: 6px; color: var(--warning); margin-right: 8px;"></i> Ahmad bin Ali: Tiba lewat 15 minit</p>
                        <p><i class="fas fa-circle" style="font-size: 6px; color: var(--info); margin-right: 8px;"></i> Siti binti Abu: Cuti sakit dengan surat doktor</p>
                    </div>
                </div>
            `;
            
            showNotification('Butiran Kehadiran', 'info', details);
        }

        // Take attendance modal
        function ambilKehadiranModal() {
            // Show modal
            attendanceModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Set default date to today
            document.getElementById('attendanceDate').value = currentDate.toISOString().split('T')[0];
            
            // Reset status selection
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('active');
            });
            document.querySelector('.status-option.present').classList.add('active');
            document.getElementById('attendanceStatus').value = 'present';
        }

        // Select status
        function pilihStatus(status) {
            selectedStatus = status;
            
            // Update UI
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('active');
            });
            document.querySelector(`.status-option.${status}`).classList.add('active');
            document.getElementById('attendanceStatus').value = status;
        }

        // Close modal
        function tutupModal() {
            attendanceModal.classList.remove('active');
            bulkUpdateModal.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('attendanceForm').reset();
        }

        // Save attendance
        function simpanKehadiran(event) {
            event.preventDefault();
            
            // Get form values
            const date = new Date(document.getElementById('attendanceDate').value);
            const className = document.getElementById('attendanceClass').value;
            const session = document.getElementById('attendanceSession').value;
            const status = document.getElementById('attendanceStatus').value;
            const note = document.getElementById('attendanceNote').value;
            
            // Create new attendance record
            const newAttendance = {
                id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                studentId: 'STU001', // In real app, this would be dynamic
                date: date,
                session: session,
                status: status,
                note: note,
                updatedBy: 'Cikgu Ahmad',
                updatedAt: new Date()
            };
            
            // Add to sample data
            sampleAttendance.push(newAttendance);
            
            // Close modal
            tutupModal();
            
            // Update UI
            loadDailyAttendance();
            loadAttendanceHistory();
            updateAttendanceStatistics();
            
            // Show success message
            showNotification('Kehadiran berjaya direkodkan', 'success');
        }

        // Update individual attendance
        function kemaskiniKehadiran(studentId, session) {
            const student = sampleStudents.find(s => s.id === studentId);
            if (!student) return;
            
            const dateStr = currentDate.toLocaleDateString('ms-MY', {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });
            
            const details = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Kemaskini Kehadiran</h3>
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                        <div class="student-avatar">${student.avatar}</div>
                        <div>
                            <div style="font-weight: 600; font-size: 16px;">${student.name}</div>
                            <div style="font-size: 13px; color: var(--medium-gray);">${student.class} | ${dateStr} | ${session === 'morning' ? 'Sesi Pagi' : 'Sesi Petang'}</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Pilih Status</div>
                    <div class="status-selector">
                        <div class="status-option present ${selectedStatus === 'present' ? 'active' : ''}" onclick="pilihStatusModal('present')">
                            <i class="fas fa-check-circle" style="color: var(--success); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Hadir</div>
                        </div>
                        <div class="status-option absent ${selectedStatus === 'absent' ? 'active' : ''}" onclick="pilihStatusModal('absent')">
                            <i class="fas fa-times-circle" style="color: var(--danger); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Tidak Hadir</div>
                        </div>
                        <div class="status-option late ${selectedStatus === 'late' ? 'active' : ''}" onclick="pilihStatusModal('late')">
                            <i class="fas fa-clock" style="color: var(--warning); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Lewat</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Catatan (Pilihan)</label>
                    <textarea class="form-textarea" id="updateNote" placeholder="Contoh: Tiba lewat 15 minit, ada urusan keluarga..."></textarea>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn success" onclick="simpanKemaskiniKehadiran('${studentId}', '${session}')" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <button class="action-btn secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            `;
            
            bulkUpdateContent.innerHTML = details;
            bulkUpdateModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Select status in modal
        function pilihStatusModal(status) {
            selectedStatus = status;
            
            // Update UI
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('active');
            });
            document.querySelector(`.status-option.${status}`).classList.add('active');
        }

        // Save updated attendance
        function simpanKemaskiniKehadiran(studentId, session) {
            const note = document.getElementById('updateNote').value;
            
            // Check if attendance already exists
            const existingIndex = sampleAttendance.findIndex(att => 
                att.studentId === studentId && 
                att.date.toDateString() === currentDate.toDateString() &&
                att.session === session
            );
            
            if (existingIndex !== -1) {
                // Update existing
                sampleAttendance[existingIndex].status = selectedStatus;
                sampleAttendance[existingIndex].note = note;
                sampleAttendance[existingIndex].updatedAt = new Date();
            } else {
                // Create new
                const newAttendance = {
                    id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                    studentId: studentId,
                    date: currentDate,
                    session: session,
                    status: selectedStatus,
                    note: note,
                    updatedBy: 'Cikgu Ahmad',
                    updatedAt: new Date()
                };
                sampleAttendance.push(newAttendance);
            }
            
            // Close modal
            tutupModal();
            
            // Update UI
            loadDailyAttendance();
            loadAttendanceHistory();
            updateAttendanceStatistics();
            
            // Show success message
            showNotification('Kehadiran berjaya dikemaskini', 'success');
        }

        // Mark all present
        function tandakanSemuaHadir() {
            const selectedClass = document.getElementById('filterClass').value;
            const studentsToMark = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            studentsToMark.forEach(student => {
                // Check and update morning session
                const morningIndex = sampleAttendance.findIndex(att => 
                    att.studentId === student.id && 
                    att.date.toDateString() === currentDate.toDateString() &&
                    att.session === 'morning'
                );
                
                if (morningIndex !== -1) {
                    sampleAttendance[morningIndex].status = 'present';
                    sampleAttendance[morningIndex].updatedAt = new Date();
                } else {
                    sampleAttendance.push({
                        id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                        studentId: student.id,
                        date: currentDate,
                        session: 'morning',
                        status: 'present',
                        note: '',
                        updatedBy: 'Cikgu Ahmad',
                        updatedAt: new Date()
                    });
                }
                
                // Check and update afternoon session
                const afternoonIndex = sampleAttendance.findIndex(att => 
                    att.studentId === student.id && 
                    att.date.toDateString() === currentDate.toDateString() &&
                    att.session === 'afternoon'
                );
                
                if (afternoonIndex !== -1) {
                    sampleAttendance[afternoonIndex].status = 'present';
                    sampleAttendance[afternoonIndex].updatedAt = new Date();
                } else {
                    sampleAttendance.push({
                        id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                        studentId: student.id,
                        date: currentDate,
                        session: 'afternoon',
                        status: 'present',
                        note: '',
                        updatedBy: 'Cikgu Ahmad',
                        updatedAt: new Date()
                    });
                }
            });
            
            // Update UI
            loadDailyAttendance();
            updateAttendanceStatistics();
            
            showNotification('Semua pelajar ditandakan sebagai hadir', 'success');
        }

        // Mark all absent
        function tandakanSemuaTidakHadir() {
            if (!confirm('Adakah anda pasti ingin menandakan semua pelajar sebagai tidak hadir?')) {
                return;
            }
            
            const selectedClass = document.getElementById('filterClass').value;
            const studentsToMark = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            studentsToMark.forEach(student => {
                // Update both sessions
                ['morning', 'afternoon'].forEach(session => {
                    const index = sampleAttendance.findIndex(att => 
                        att.studentId === student.id && 
                        att.date.toDateString() === currentDate.toDateString() &&
                        att.session === session
                    );
                    
                    if (index !== -1) {
                        sampleAttendance[index].status = 'absent';
                        sampleAttendance[index].note = 'Ditandakan secara pukal';
                        sampleAttendance[index].updatedAt = new Date();
                    } else {
                        sampleAttendance.push({
                            id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                            studentId: student.id,
                            date: currentDate,
                            session: session,
                            status: 'absent',
                            note: 'Ditandakan secara pukal',
                            updatedBy: 'Cikgu Ahmad',
                            updatedAt: new Date()
                        });
                    }
                });
            });
            
            // Update UI
            loadDailyAttendance();
            updateAttendanceStatistics();
            
            showNotification('Semua pelajar ditandakan sebagai tidak hadir', 'warning');
        }

        // Bulk update
        function kemaskiniPukal() {
            const selectedClass = document.getElementById('filterClass').value;
            const studentsInClass = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            let bulkContent = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Kemaskini Kehadiran Pukal</h3>
                <div style="margin-bottom: 15px; color: var(--medium-gray);">
                    Kemaskini kehadiran untuk ${studentsInClass.length} pelajar ${selectedClass === 'all' ? '' : 'dalam kelas ' + selectedClass}
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Pilih Status</div>
                    <div class="status-selector">
                        <div class="status-option present ${selectedStatus === 'present' ? 'active' : ''}" onclick="pilihStatusModal('present')">
                            <i class="fas fa-check-circle" style="color: var(--success); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Hadir</div>
                        </div>
                        <div class="status-option absent ${selectedStatus === 'absent' ? 'active' : ''}" onclick="pilihStatusModal('absent')">
                            <i class="fas fa-times-circle" style="color: var(--danger); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Tidak Hadir</div>
                        </div>
                        <div class="status-option late ${selectedStatus === 'late' ? 'active' : ''}" onclick="pilihStatusModal('late')">
                            <i class="fas fa-clock" style="color: var(--warning); font-size: 24px;"></i>
                            <div style="margin-top: 5px; font-weight: 600;">Lewat</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sesi</label>
                    <select class="form-select" id="bulkSession">
                        <option value="morning">Pagi Sahaja</option>
                        <option value="afternoon">Petang Sahaja</option>
                        <option value="full">Kedua-dua Sesi</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Catatan (Pilihan)</label>
                    <textarea class="form-textarea" id="bulkNote" placeholder="Catatan untuk semua pelajar..."></textarea>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Senarai Pelajar</div>
                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px;">
                        ${studentsInClass.map(student => `
                            <div style="display: flex; align-items: center; gap: 10px; padding: 5px 0;">
                                <div class="student-avatar" style="width: 24px; height: 24px; font-size: 10px;">${student.avatar}</div>
                                <div style="font-size: 13px;">${student.name}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn success" onclick="simpanKemaskiniPukal()" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Simpan Pukal
                    </button>
                    <button class="action-btn secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            `;
            
            bulkUpdateContent.innerHTML = bulkContent;
            bulkUpdateModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Save bulk update
        function simpanKemaskiniPukal() {
            const session = document.getElementById('bulkSession').value;
            const note = document.getElementById('bulkNote').value;
            const selectedClass = document.getElementById('filterClass').value;
            const studentsInClass = sampleStudents.filter(student => 
                selectedClass === 'all' || student.class === selectedClass
            );
            
            studentsInClass.forEach(student => {
                if (session === 'full') {
                    // Update both sessions
                    ['morning', 'afternoon'].forEach(sess => {
                        const index = sampleAttendance.findIndex(att => 
                            att.studentId === student.id && 
                            att.date.toDateString() === currentDate.toDateString() &&
                            att.session === sess
                        );
                        
                        if (index !== -1) {
                            sampleAttendance[index].status = selectedStatus;
                            sampleAttendance[index].note = note || sampleAttendance[index].note;
                            sampleAttendance[index].updatedAt = new Date();
                        } else {
                            sampleAttendance.push({
                                id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                                studentId: student.id,
                                date: currentDate,
                                session: sess,
                                status: selectedStatus,
                                note: note,
                                updatedBy: 'Cikgu Ahmad',
                                updatedAt: new Date()
                            });
                        }
                    });
                } else {
                    // Update single session
                    const index = sampleAttendance.findIndex(att => 
                        att.studentId === student.id && 
                        att.date.toDateString() === currentDate.toDateString() &&
                        att.session === session
                    );
                    
                    if (index !== -1) {
                        sampleAttendance[index].status = selectedStatus;
                        sampleAttendance[index].note = note || sampleAttendance[index].note;
                        sampleAttendance[index].updatedAt = new Date();
                    } else {
                        sampleAttendance.push({
                            id: 'ATT' + (sampleAttendance.length + 1).toString().padStart(3, '0'),
                            studentId: student.id,
                            date: currentDate,
                            session: session,
                            status: selectedStatus,
                            note: note,
                            updatedBy: 'Cikgu Ahmad',
                            updatedAt: new Date()
                        });
                    }
                }
            });
            
            // Close modal
            tutupModal();
            
            // Update UI
            loadDailyAttendance();
            updateAttendanceStatistics();
            
            // Show success message
            showNotification(`${studentsInClass.length} pelajar berjaya dikemaskini`, 'success');
        }

        // Update class attendance
        function kemaskiniKehadiranKelas(dateStr, className) {
            showNotification('Kemaskini Kelas', 'info', `Fitur kemaskini untuk kelas ${className} pada ${dateStr} akan dibuka`);
        }

        // Generate daily report
        function janaLaporanHarian() {
            showNotification('Laporan Harian', 'info', 'Laporan kehadiran harian sedang disediakan...');
            setTimeout(() => {
                showNotification('Laporan harian berjaya dijana', 'success');
            }, 1500);
        }

        // Generate monthly report
        function janaLaporanBulanan() {
            showNotification('Laporan Bulanan', 'info', 'Laporan kehadiran bulanan sedang disediakan...');
            setTimeout(() => {
                showNotification('Laporan bulanan berjaya dijana', 'success');
            }, 1500);
        }

        // Export history
        function eksportSejarah() {
            showNotification('Eksport Sejarah', 'info', 'Data sejarah kehadiran sedang disediakan untuk eksport...');
            setTimeout(() => {
                showNotification('Data sejarah berjaya dieksport', 'success');
            }, 1500);
        }

        // Reload attendance
        function muatSemulaKehadiran() {
            showNotification('Muat semula kehadiran...', 'info');
            
            // Simulate reload
            setTimeout(() => {
                loadDailyAttendance();
                generateCalendar(currentMonth, currentYear);
                loadAttendanceSummary();
                loadAttendanceHistory();
                updateAttendanceStatistics();
                
                showNotification('Kehadiran berjaya dimuat semula', 'success');
            }, 1000);
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

        // Add CSS for animation
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