<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugasan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN yang lebih stabil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        /* Assignment Stats */
        .assignment-stats {
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

        .stat-icon.pending {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .stat-icon.submitted {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .stat-icon.graded {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .stat-icon.late {
            background: linear-gradient(135deg, var(--danger), #f87171);
        }

        .stat-icon.overdue {
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

        /* Assignment Cards */
        .assignment-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .assignment-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
        }

        .assignment-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .assignment-card.pending {
            border-left: 5px solid var(--warning);
        }

        .assignment-card.submitted {
            border-left: 5px solid var(--info);
        }

        .assignment-card.graded {
            border-left: 5px solid var(--success);
        }

        .assignment-card.late {
            border-left: 5px solid var(--danger);
        }

        .assignment-card.overdue {
            border-left: 5px solid #7c3aed;
        }

        .assignment-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .assignment-title h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .assignment-subject {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .assignment-priority {
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .priority-high {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .priority-medium {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .priority-low {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .assignment-details {
            margin: 15px 0;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .detail-item i {
            width: 16px;
            color: var(--primary);
        }

        /* Progress Bar */
        .progress-section {
            margin: 15px 0;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .progress-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
        }

        .progress-bar {
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 0.5s ease;
        }

        /* Student List */
        .student-list {
            margin-top: 15px;
        }

        .student-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 8px;
            background: var(--light-gray);
            transition: var(--transition);
        }

        .student-item:hover {
            background: var(--primary-light);
        }

        .student-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 12px;
            flex-shrink: 0;
        }

        .student-info {
            flex: 1;
        }

        .student-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-status {
            font-size: 11px;
            font-weight: 600;
        }

        .status-submitted {
            color: var(--success);
        }

        .status-pending {
            color: var(--warning);
        }

        .status-late {
            color: var(--danger);
        }

        .status-overdue {
            color: #7c3aed;
        }

        .assignment-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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

        /* Assignment Table */
        .assignment-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .assignment-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .assignment-table th {
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

        .assignment-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .assignment-table tr:hover td {
            background: var(--primary-light);
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }

        .status-pending-badge {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-submitted-badge {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .status-graded-badge {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-late-badge {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-overdue-badge {
            background: rgba(124, 58, 237, 0.1);
            color: #7c3aed;
        }

        /* Due Date Indicator */
        .due-date {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .due-date.urgent {
            color: var(--danger);
            font-weight: 600;
        }

        .due-date.warning {
            color: var(--warning);
            font-weight: 600;
        }

        .due-date.normal {
            color: var(--success);
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
            max-width: 600px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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

        /* File Upload */
        .file-upload {
            border: 2px dashed #e5e7eb;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload i {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .file-upload p {
            color: var(--medium-gray);
            margin-bottom: 10px;
        }

        .file-upload input {
            display: none;
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
            
            .assignment-cards {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            
            .assignment-cards {
                grid-template-columns: 1fr;
            }
            
            .assignment-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-row {
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
            
            .assignment-stats {
                grid-template-columns: 1fr;
            }
            
            .assignment-actions {
                flex-wrap: wrap;
            }
            
            .tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Add Assignment Modal -->
    <div class="modal" id="addAssignmentModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Tambah Tugasan Baharu</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="assignmentForm" onsubmit="tambahTugasan(event)">
                <div class="form-group">
                    <label class="form-label">Tajuk Tugasan</label>
                    <input type="text" class="form-input" id="assignmentTitle" placeholder="Contoh: Kerja Kursus Matematik - Pecahan" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <select class="form-select" id="assignmentSubject" required>
                            <option value="">Pilih Subjek</option>
                            <option value="matematik">Matematik</option>
                            <option value="sains">Sains</option>
                            <option value="bm">Bahasa Melayu</option>
                            <option value="bi">Bahasa Inggeris</option>
                            <option value="pj">PJ & Kesihatan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" id="assignmentClass" required>
                            <option value="">Pilih Kelas</option>
                            <option value="6A">Kelas 6A</option>
                            <option value="6B">Kelas 6B</option>
                            <option value="5A">Kelas 5A</option>
                            <option value="5B">Kelas 5B</option>
                            <option value="all">Semua Kelas</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tarikh Diberi</label>
                        <input type="date" class="form-input" id="assignmentGivenDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tarikh Hantar</label>
                        <input type="date" class="form-input" id="assignmentDueDate" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Keutamaan</label>
                        <select class="form-select" id="assignmentPriority" required>
                            <option value="">Pilih Keutamaan</option>
                            <option value="high">Tinggi</option>
                            <option value="medium">Sederhana</option>
                            <option value="low">Rendah</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Markah Penuh</label>
                        <input type="number" class="form-input" id="assignmentTotalMarks" placeholder="100" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Butiran Tugasan</label>
                    <textarea class="form-textarea" id="assignmentDetails" placeholder="Arahan tugasan, keperluan, format penyerahan..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lampiran (Pilihan)</label>
                    <div class="file-upload" onclick="document.getElementById('assignmentFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Klik untuk muat naik fail</p>
                        <small>PDF, DOC, PPT, JPG (Maks: 10MB)</small>
                        <input type="file" id="assignmentFile" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png">
                    </div>
                    <div id="fileName" style="margin-top: 10px; font-size: 12px; color: var(--medium-gray);"></div>
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Terbitkan Tugasan
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Submission Modal -->
    <div class="modal" id="viewSubmissionModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Serahan Tugasan</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="submissionContent">
                <!-- Submission details will be loaded here -->
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
                    <p>Tugasan</p>
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
                    <span class="notification-badge">7</span>
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
            <a href="tugasan.html" class="sidebar-item active">
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
                <h2>Tugasan 📚</h2>
                <p>Pengurusan dan penilaian tugasan pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaTugasan()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahTugasanModal()">
                    <i class="fas fa-plus-circle"></i>
                    Tugasan Baru
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="ubahTab('all')">
                <i class="fas fa-list"></i>
                Semua Tugasan
            </button>
            <button class="tab-btn" onclick="ubahTab('pending')">
                <i class="fas fa-clock"></i>
                Belum Selesai
            </button>
            <button class="tab-btn" onclick="ubahTab('graded')">
                <i class="fas fa-check-circle"></i>
                Dinilai
            </button>
            <button class="tab-btn" onclick="ubahTab('overdue')">
                <i class="fas fa-exclamation-triangle"></i>
                Lewat
            </button>
        </div>

        <!-- Assignment Statistics -->
        <div class="assignment-stats">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" id="pendingCount">7</div>
                <div class="stat-label">Belum Selesai</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon submitted">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-value" id="submittedCount">15</div>
                <div class="stat-label">Telah Dihantar</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon graded">
                    <i class="fas fa-check-double"></i>
                </div>
                <div class="stat-value" id="gradedCount">22</div>
                <div class="stat-label">Telah Dinilai</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon late">
                    <i class="fas fa-hourglass-end"></i>
                </div>
                <div class="stat-value" id="lateCount">3</div>
                <div class="stat-label">Lewat Hantar</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Tapis Tugasan
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="tapilkanTugasan()">
                        <option value="all">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Subjek:</label>
                    <select class="filter-select" id="filterSubject" onchange="tapilkanTugasan()">
                        <option value="all">Semua Subjek</option>
                        <option value="matematik">Matematik</option>
                        <option value="sains">Sains</option>
                        <option value="bm">Bahasa Melayu</option>
                        <option value="bi">Bahasa Inggeris</option>
                        <option value="pj">PJ & Kesihatan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="tapilkanTugasan()">
                        <option value="all">Semua Status</option>
                        <option value="pending">Belum Selesai</option>
                        <option value="submitted">Telah Dihantar</option>
                        <option value="graded">Telah Dinilai</option>
                        <option value="late">Lewat</option>
                        <option value="overdue">Lewat Tempoh</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Keutamaan:</label>
                    <select class="filter-select" id="filterPriority" onchange="tapilkanTugasan()">
                        <option value="all">Semua Keutamaan</option>
                        <option value="high">Tinggi</option>
                        <option value="medium">Sederhana</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- All Assignments Tab -->
        <div id="allTabContent">
            <div class="assignment-cards" id="allAssignments">
                <!-- All assignments will be loaded here -->
            </div>
        </div>

        <!-- Pending Assignments Tab -->
        <div id="pendingTabContent" style="display: none;">
            <div class="assignment-cards" id="pendingAssignments">
                <!-- Pending assignments will be loaded here -->
            </div>
        </div>

        <!-- Graded Assignments Tab -->
        <div id="gradedTabContent" style="display: none;">
            <div class="assignment-cards" id="gradedAssignments">
                <!-- Graded assignments will be loaded here -->
            </div>
        </div>

        <!-- Overdue Assignments Tab -->
        <div id="overdueTabContent" style="display: none;">
            <div class="assignment-cards" id="overdueAssignments">
                <!-- Overdue assignments will be loaded here -->
            </div>
        </div>

        <!-- Detailed Table View -->
        <div class="assignment-table-container">
            <div class="filter-title">
                <i class="fas fa-table"></i>
                Senarai Terperinci Tugasan
            </div>
            
            <div style="overflow-x: auto; margin-top: 20px;">
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>TAJUK</th>
                            <th>SUBJEK</th>
                            <th>KELAS</th>
                            <th>TARIKH DIBERI</th>
                            <th>TARIKH HANTAR</th>
                            <th>STATUS</th>
                            <th>KEUTAMAAN</th>
                            <th>KEMAJUAN</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="assignmentTableBody">
                        <!-- Assignment table rows will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const addAssignmentModal = document.getElementById('addAssignmentModal');
        const viewSubmissionModal = document.getElementById('viewSubmissionModal');
        const submissionContent = document.getElementById('submissionContent');
        const assignmentFile = document.getElementById('assignmentFile');
        const fileName = document.getElementById('fileName');

        // Tabs
        const allTabContent = document.getElementById('allTabContent');
        const pendingTabContent = document.getElementById('pendingTabContent');
        const gradedTabContent = document.getElementById('gradedTabContent');
        const overdueTabContent = document.getElementById('overdueTabContent');

        // Assignment containers
        const allAssignments = document.getElementById('allAssignments');
        const pendingAssignments = document.getElementById('pendingAssignments');
        const gradedAssignments = document.getElementById('gradedAssignments');
        const overdueAssignments = document.getElementById('overdueAssignments');
        const assignmentTableBody = document.getElementById('assignmentTableBody');

        // Sample assignment data
        const sampleAssignments = [
            {
                id: 'ASG001',
                title: 'Kerja Kursus Matematik - Pecahan',
                subject: 'matematik',
                class: '6A',
                givenDate: new Date(2024, 2, 10), // 10 March 2024
                dueDate: new Date(2024, 2, 25), // 25 March 2024
                priority: 'high',
                totalMarks: 100,
                details: 'Selesaikan semua soalan pecahan di muka surat 45-50. Hantar dalam bentuk cetakan.',
                attachment: 'matematik_pecahan.pdf',
                status: 'pending',
                progress: {
                    total: 30,
                    submitted: 15,
                    graded: 8
                },
                students: [
                    { id: 'STU001', name: 'Ahmad bin Ali', status: 'submitted', submittedDate: new Date(2024, 2, 20), marks: 85 },
                    { id: 'STU002', name: 'Siti binti Abu', status: 'submitted', submittedDate: new Date(2024, 2, 22), marks: 78 },
                    { id: 'STU003', name: 'Muhammad bin Hassan', status: 'graded', submittedDate: new Date(2024, 2, 18), marks: 92 },
                    { id: 'STU004', name: 'Aisyah binti Musa', status: 'pending' },
                    { id: 'STU005', name: 'Ali bin Abdullah', status: 'submitted', submittedDate: new Date(2024, 2, 24), marks: null },
                    { id: 'STU006', name: 'Fatimah binti Omar', status: 'late', submittedDate: new Date(2024, 2, 26), marks: 65 },
                    { id: 'STU007', name: 'Hassan bin Ismail', status: 'graded', submittedDate: new Date(2024, 2, 15), marks: 95 },
                    { id: 'STU008', name: 'Zainab binti Yusuf', status: 'overdue' }
                ]
            },
            {
                id: 'ASG002',
                title: 'Eksperimen Sains - Tenaga',
                subject: 'sains',
                class: '6A',
                givenDate: new Date(2024, 2, 5),
                dueDate: new Date(2024, 2, 20),
                priority: 'medium',
                totalMarks: 50,
                details: 'Lakukan eksperimen tentang penukaran tenaga dan sediakan laporan lengkap.',
                attachment: 'eksperimen_tenaga.docx',
                status: 'submitted',
                progress: {
                    total: 30,
                    submitted: 25,
                    graded: 20
                },
                students: [
                    { id: 'STU001', name: 'Ahmad bin Ali', status: 'graded', submittedDate: new Date(2024, 2, 18), marks: 45 },
                    { id: 'STU002', name: 'Siti binti Abu', status: 'graded', submittedDate: new Date(2024, 2, 19), marks: 38 },
                    { id: 'STU003', name: 'Muhammad bin Hassan', status: 'graded', submittedDate: new Date(2024, 2, 17), marks: 48 },
                    { id: 'STU004', name: 'Aisyah binti Musa', status: 'graded', submittedDate: new Date(2024, 2, 20), marks: 42 },
                    { id: 'STU005', name: 'Ali bin Abdullah', status: 'graded', submittedDate: new Date(2024, 2, 20), marks: 40 },
                    { id: 'STU006', name: 'Fatimah binti Omar', status: 'late', submittedDate: new Date(2024, 2, 22), marks: 35 },
                    { id: 'STU007', name: 'Hassan bin Ismail', status: 'graded', submittedDate: new Date(2024, 2, 16), marks: 50 },
                    { id: 'STU008', name: 'Zainab binti Yusuf', status: 'graded', submittedDate: new Date(2024, 2, 20), marks: 44 }
                ]
            },
            {
                id: 'ASG003',
                title: 'Karangan Bahasa Melayu',
                subject: 'bm',
                class: '6A',
                givenDate: new Date(2024, 2, 1),
                dueDate: new Date(2024, 2, 15),
                priority: 'medium',
                totalMarks: 40,
                details: 'Tulis karangan bertajuk "Kepentingan Membaca" (250-300 patah perkataan).',
                attachment: null,
                status: 'graded',
                progress: {
                    total: 30,
                    submitted: 28,
                    graded: 28
                },
                students: [
                    { id: 'STU001', name: 'Ahmad bin Ali', status: 'graded', submittedDate: new Date(2024, 2, 13), marks: 35 },
                    { id: 'STU002', name: 'Siti binti Abu', status: 'graded', submittedDate: new Date(2024, 2, 14), marks: 32 },
                    { id: 'STU003', name: 'Muhammad bin Hassan', status: 'graded', submittedDate: new Date(2024, 2, 12), marks: 38 },
                    { id: 'STU004', name: 'Aisyah binti Musa', status: 'graded', submittedDate: new Date(2024, 2, 15), marks: 30 },
                    { id: 'STU005', name: 'Ali bin Abdullah', status: 'graded', submittedDate: new Date(2024, 2, 15), marks: 28 },
                    { id: 'STU006', name: 'Fatimah binti Omar', status: 'graded', submittedDate: new Date(2024, 2, 16), marks: 25 },
                    { id: 'STU007', name: 'Hassan bin Ismail', status: 'graded', submittedDate: new Date(2024, 2, 10), marks: 40 },
                    { id: 'STU008', name: 'Zainab binti Yusuf', status: 'graded', submittedDate: new Date(2024, 2, 15), marks: 31 }
                ]
            },
            {
                id: 'ASG004',
                title: 'English Essay - My Family',
                subject: 'bi',
                class: '6A',
                givenDate: new Date(2024, 2, 8),
                dueDate: new Date(2024, 2, 22),
                priority: 'low',
                totalMarks: 30,
                details: 'Write an essay about your family (200-250 words). Submit online via Google Classroom.',
                attachment: 'english_essay_instructions.pdf',
                status: 'pending',
                progress: {
                    total: 30,
                    submitted: 10,
                    graded: 5
                },
                students: [
                    { id: 'STU001', name: 'Ahmad bin Ali', status: 'submitted', submittedDate: new Date(2024, 2, 18), marks: null },
                    { id: 'STU002', name: 'Siti binti Abu', status: 'pending' },
                    { id: 'STU003', name: 'Muhammad bin Hassan', status: 'graded', submittedDate: new Date(2024, 2, 15), marks: 28 },
                    { id: 'STU004', name: 'Aisyah binti Musa', status: 'pending' },
                    { id: 'STU005', name: 'Ali bin Abdullah', status: 'pending' },
                    { id: 'STU006', name: 'Fatimah binti Omar', status: 'pending' },
                    { id: 'STU007', name: 'Hassan bin Ismail', status: 'graded', submittedDate: new Date(2024, 2, 12), marks: 30 },
                    { id: 'STU008', name: 'Zainab binti Yusuf', status: 'submitted', submittedDate: new Date(2024, 2, 20), marks: null }
                ]
            },
            {
                id: 'ASG005',
                title: 'Laporan Aktiviti PJ',
                subject: 'pj',
                class: '6A',
                givenDate: new Date(2024, 1, 25), // 25 Feb 2024
                dueDate: new Date(2024, 2, 10), // 10 March 2024
                priority: 'medium',
                totalMarks: 20,
                details: 'Sediakan laporan aktiviti sukan mingguan selama 2 minggu.',
                attachment: null,
                status: 'late',
                progress: {
                    total: 30,
                    submitted: 22,
                    graded: 18
                },
                students: [
                    { id: 'STU001', name: 'Ahmad bin Ali', status: 'graded', submittedDate: new Date(2024, 2, 8), marks: 18 },
                    { id: 'STU002', name: 'Siti binti Abu', status: 'graded', submittedDate: new Date(2024, 2, 9), marks: 15 },
                    { id: 'STU003', name: 'Muhammad bin Hassan', status: 'graded', submittedDate: new Date(2024, 2, 5), marks: 20 },
                    { id: 'STU004', name: 'Aisyah binti Musa', status: 'graded', submittedDate: new Date(2024, 2, 10), marks: 16 },
                    { id: 'STU005', name: 'Ali bin Abdullah', status: 'late', submittedDate: new Date(2024, 2, 12), marks: 14 },
                    { id: 'STU006', name: 'Fatimah binti Omar', status: 'overdue' },
                    { id: 'STU007', name: 'Hassan bin Ismail', status: 'graded', submittedDate: new Date(2024, 2, 3), marks: 19 },
                    { id: 'STU008', name: 'Zainab binti Yusuf', status: 'graded', submittedDate: new Date(2024, 2, 10), marks: 17 }
                ]
            },
            {
                id: 'ASG006',
                title: 'Projek Matematik - Geometri',
                subject: 'matematik',
                class: '6B',
                givenDate: new Date(2024, 2, 12),
                dueDate: new Date(2024, 3, 5), // 5 April 2024
                priority: 'high',
                totalMarks: 100,
                details: 'Bina model 3D bentuk geometri dan sediakan laporan pengiraan.',
                attachment: 'projek_geometri.pptx',
                status: 'pending',
                progress: {
                    total: 25,
                    submitted: 5,
                    graded: 2
                },
                students: [
                    { id: 'STU009', name: 'Aiman bin Karim', status: 'submitted', submittedDate: new Date(2024, 2, 25), marks: null },
                    { id: 'STU010', name: 'Nurul binti Hamid', status: 'graded', submittedDate: new Date(2024, 2, 20), marks: 88 },
                    { id: 'STU011', name: 'Farhan bin Ismail', status: 'pending' },
                    { id: 'STU012', name: 'Sarah binti Musa', status: 'pending' },
                    { id: 'STU013', name: 'Hakim bin Yusof', status: 'graded', submittedDate: new Date(2024, 2, 22), marks: 92 }
                ]
            },
            {
                id: 'ASG007',
                title: 'Kuiz Sains Bab 4',
                subject: 'sains',
                class: 'all',
                givenDate: new Date(2024, 2, 18),
                dueDate: new Date(2024, 2, 25),
                priority: 'low',
                totalMarks: 20,
                details: 'Jawab kuiz dalam talian di laman web sekolah sebelum tarikh tutup.',
                attachment: 'kuiz_sains_link.txt',
                status: 'overdue',
                progress: {
                    total: 85,
                    submitted: 65,
                    graded: 60
                },
                students: []
            }
        ];

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Load assignment data
            loadAllAssignments();
            loadPendingAssignments();
            loadGradedAssignments();
            loadOverdueAssignments();
            loadAssignmentTable();
            
            // Update statistics
            updateAssignmentStatistics();
            
            // Set default dates in form
            setDefaultDates();
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
            addAssignmentModal.addEventListener('click', function(e) {
                if (e.target === addAssignmentModal) {
                    tutupModal();
                }
            });
            
            viewSubmissionModal.addEventListener('click', function(e) {
                if (e.target === viewSubmissionModal) {
                    tutupModal();
                }
            });
            
            // File upload handler
            assignmentFile.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    fileName.textContent = `Fail dipilih: ${this.files[0].name}`;
                    fileName.style.color = 'var(--success)';
                } else {
                    fileName.textContent = '';
                }
            });
        }

        // Change tab
        function ubahTab(tab) {
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active tab content
            allTabContent.style.display = 'none';
            pendingTabContent.style.display = 'none';
            gradedTabContent.style.display = 'none';
            overdueTabContent.style.display = 'none';
            
            if (tab === 'all') {
                allTabContent.style.display = 'block';
            } else if (tab === 'pending') {
                pendingTabContent.style.display = 'block';
            } else if (tab === 'graded') {
                gradedTabContent.style.display = 'block';
            } else if (tab === 'overdue') {
                overdueTabContent.style.display = 'block';
            }
        }

        // Load all assignments
        function loadAllAssignments() {
            allAssignments.innerHTML = sampleAssignments.map(assignment => createAssignmentCard(assignment)).join('');
        }

        // Load pending assignments
        function loadPendingAssignments() {
            const pending = sampleAssignments.filter(assignment => assignment.status === 'pending');
            pendingAssignments.innerHTML = pending.map(assignment => createAssignmentCard(assignment)).join('');
        }

        // Load graded assignments
        function loadGradedAssignments() {
            const graded = sampleAssignments.filter(assignment => assignment.status === 'graded');
            gradedAssignments.innerHTML = graded.map(assignment => createAssignmentCard(assignment)).join('');
        }

        // Load overdue assignments
        function loadOverdueAssignments() {
            const overdue = sampleAssignments.filter(assignment => assignment.status === 'overdue' || assignment.status === 'late');
            overdueAssignments.innerHTML = overdue.map(assignment => createAssignmentCard(assignment)).join('');
        }

        // Load assignment table
        function loadAssignmentTable() {
            assignmentTableBody.innerHTML = sampleAssignments.map(assignment => createAssignmentTableRow(assignment)).join('');
        }

        // Create assignment card
        function createAssignmentCard(assignment) {
            // Format dates
            const givenDateStr = assignment.givenDate.toLocaleDateString('ms-MY', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            const dueDateStr = assignment.dueDate.toLocaleDateString('ms-MY', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            // Get subject name
            const subjectNames = {
                'matematik': 'Matematik',
                'sains': 'Sains',
                'bm': 'Bahasa Melayu',
                'bi': 'Bahasa Inggeris',
                'pj': 'PJ & Kesihatan'
            };
            const subjectName = subjectNames[assignment.subject] || assignment.subject;
            
            // Get priority class
            const priorityClasses = {
                'high': 'priority-high',
                'medium': 'priority-medium',
                'low': 'priority-low'
            };
            
            // Calculate progress percentage
            const progressPercentage = assignment.progress ? 
                Math.round((assignment.progress.submitted / assignment.progress.total) * 100) : 0;
            
            // Check if overdue
            const now = new Date();
            const isOverdue = assignment.dueDate < now && assignment.status !== 'graded';
            const daysRemaining = Math.ceil((assignment.dueDate - now) / (1000 * 60 * 60 * 24));
            
            let dueDateClass = 'normal';
            if (isOverdue) {
                dueDateClass = 'urgent';
            } else if (daysRemaining <= 3) {
                dueDateClass = 'warning';
            }
            
            return `
                <div class="assignment-card ${assignment.status}">
                    <div class="assignment-header">
                        <div class="assignment-title">
                            <h4>${assignment.title}</h4>
                            <div class="assignment-subject">${subjectName}</div>
                            <div class="${priorityClasses[assignment.priority]} assignment-priority">
                                ${assignment.priority === 'high' ? 'Tinggi' : assignment.priority === 'medium' ? 'Sederhana' : 'Rendah'}
                            </div>
                        </div>
                        <div class="due-date ${dueDateClass}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>${isOverdue ? 'Lewat!' : `${daysRemaining} hari`}</span>
                        </div>
                    </div>
                    
                    <div class="assignment-details">
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <span>${assignment.class === 'all' ? 'Semua Kelas' : 'Kelas ' + assignment.class}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Diberi: ${givenDateStr}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar-check"></i>
                            <span>Hantar: ${dueDateStr}</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-star"></i>
                            <span>Markah Penuh: ${assignment.totalMarks}</span>
                        </div>
                    </div>
                    
                    ${assignment.progress ? `
                    <div class="progress-section">
                        <div class="progress-header">
                            <div class="progress-label">Kemajuan Serahan</div>
                            <div class="progress-value">${assignment.progress.submitted}/${assignment.progress.total} (${progressPercentage}%)</div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${progressPercentage}%"></div>
                        </div>
                    </div>
                    ` : ''}
                    
                    ${assignment.students && assignment.students.length > 0 ? `
                    <div class="student-list">
                        ${assignment.students.slice(0, 3).map(student => {
                            const initials = student.name.split(' ').map(n => n.charAt(0)).join('');
                            let statusClass = '';
                            if (student.status === 'graded') statusClass = 'status-submitted';
                            else if (student.status === 'submitted') statusClass = 'status-submitted';
                            else if (student.status === 'pending') statusClass = 'status-pending';
                            else if (student.status === 'late') statusClass = 'status-late';
                            else if (student.status === 'overdue') statusClass = 'status-overdue';
                            
                            return `
                                <div class="student-item">
                                    <div class="student-avatar">${initials}</div>
                                    <div class="student-info">
                                        <div class="student-name">${student.name}</div>
                                        <div class="student-status ${statusClass}">
                                            ${student.status === 'graded' ? 'Dinilai' : 
                                              student.status === 'submitted' ? 'Dihantar' : 
                                              student.status === 'pending' ? 'Belum Hantar' :
                                              student.status === 'late' ? 'Lewat' : 'Lewat Tempoh'}
                                            ${student.marks ? ` (${student.marks}/${assignment.totalMarks})` : ''}
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                        ${assignment.students.length > 3 ? `
                            <div style="text-align: center; padding: 10px; font-size: 12px; color: var(--medium-gray);">
                                + ${assignment.students.length - 3} pelajar lagi
                            </div>
                        ` : ''}
                    </div>
                    ` : ''}
                    
                    <div class="assignment-actions">
                        <button class="action-btn info" onclick="lihatTugasan('${assignment.id}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                        <button class="action-btn success" onclick="semakSerahan('${assignment.id}')">
                            <i class="fas fa-check-circle"></i>
                            Semak
                        </button>
                        <button class="action-btn warning" onclick="kemaskiniTugasan('${assignment.id}')">
                            <i class="fas fa-edit"></i>
                            Kemaskini
                        </button>
                    </div>
                </div>
            `;
        }

        // Create assignment table row
        function createAssignmentTableRow(assignment) {
            // Format dates
            const givenDateStr = assignment.givenDate.toLocaleDateString('ms-MY');
            const dueDateStr = assignment.dueDate.toLocaleDateString('ms-MY');
            
            // Get subject name
            const subjectNames = {
                'matematik': 'Matematik',
                'sains': 'Sains',
                'bm': 'Bahasa Melayu',
                'bi': 'Bahasa Inggeris',
                'pj': 'PJ & Kesihatan'
            };
            const subjectName = subjectNames[assignment.subject] || assignment.subject;
            
            // Get status badge
            let statusBadge = '';
            if (assignment.status === 'pending') {
                statusBadge = '<span class="status-badge status-pending-badge">Belum Selesai</span>';
            } else if (assignment.status === 'submitted') {
                statusBadge = '<span class="status-badge status-submitted-badge">Telah Dihantar</span>';
            } else if (assignment.status === 'graded') {
                statusBadge = '<span class="status-badge status-graded-badge">Telah Dinilai</span>';
            } else if (assignment.status === 'late') {
                statusBadge = '<span class="status-badge status-late-badge">Lewat Hantar</span>';
            } else if (assignment.status === 'overdue') {
                statusBadge = '<span class="status-badge status-overdue-badge">Lewat Tempoh</span>';
            }
            
            // Get priority text
            const priorityText = assignment.priority === 'high' ? 'Tinggi' : 
                                assignment.priority === 'medium' ? 'Sederhana' : 'Rendah';
            
            // Calculate progress percentage
            const progressPercentage = assignment.progress ? 
                Math.round((assignment.progress.submitted / assignment.progress.total) * 100) : 0;
            
            return `
                <tr>
                    <td>
                        <div style="font-weight: 600;">${assignment.title}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">${subjectName}</div>
                    </td>
                    <td>${subjectName}</td>
                    <td>${assignment.class === 'all' ? 'Semua' : assignment.class}</td>
                    <td>${givenDateStr}</td>
                    <td>${dueDateStr}</td>
                    <td>${statusBadge}</td>
                    <td>${priorityText}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; background: #e5e7eb; height: 6px; border-radius: 3px;">
                                <div style="background: var(--primary); height: 100%; border-radius: 3px; width: ${progressPercentage}%"></div>
                            </div>
                            <div style="font-size: 12px; font-weight: 600; min-width: 40px;">${progressPercentage}%</div>
                        </div>
                    </td>
                    <td>
                        <button class="action-btn primary" onclick="lihatTugasan('${assignment.id}')" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fas fa-eye"></i>
                            Tindakan
                        </button>
                    </td>
                </tr>
            `;
        }

        // Update assignment statistics
        function updateAssignmentStatistics() {
            const pendingCount = sampleAssignments.filter(a => a.status === 'pending').length;
            const submittedCount = sampleAssignments.filter(a => a.status === 'submitted').length;
            const gradedCount = sampleAssignments.filter(a => a.status === 'graded').length;
            const lateCount = sampleAssignments.filter(a => a.status === 'late' || a.status === 'overdue').length;
            
            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('submittedCount').textContent = submittedCount;
            document.getElementById('gradedCount').textContent = gradedCount;
            document.getElementById('lateCount').textContent = lateCount;
        }

        // Set default dates in form
        function setDefaultDates() {
            const today = new Date();
            const nextWeek = new Date();
            nextWeek.setDate(today.getDate() + 7);
            
            document.getElementById('assignmentGivenDate').value = today.toISOString().split('T')[0];
            document.getElementById('assignmentDueDate').value = nextWeek.toISOString().split('T')[0];
        }

        // Add assignment modal
        function tambahTugasanModal() {
            // Show modal
            addAssignmentModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function tutupModal() {
            addAssignmentModal.classList.remove('active');
            viewSubmissionModal.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('assignmentForm').reset();
            fileName.textContent = '';
        }

        // Add assignment
        function tambahTugasan(event) {
            event.preventDefault();
            
            // Get form values
            const title = document.getElementById('assignmentTitle').value;
            const subject = document.getElementById('assignmentSubject').value;
            const assignmentClass = document.getElementById('assignmentClass').value;
            const givenDate = new Date(document.getElementById('assignmentGivenDate').value);
            const dueDate = new Date(document.getElementById('assignmentDueDate').value);
            const priority = document.getElementById('assignmentPriority').value;
            const totalMarks = parseInt(document.getElementById('assignmentTotalMarks').value);
            const details = document.getElementById('assignmentDetails').value;
            
            // Create new assignment object
            const newAssignment = {
                id: 'ASG' + (sampleAssignments.length + 1).toString().padStart(3, '0'),
                title: title,
                subject: subject,
                class: assignmentClass,
                givenDate: givenDate,
                dueDate: dueDate,
                priority: priority,
                totalMarks: totalMarks,
                details: details || 'Tiada butiran tambahan',
                attachment: null,
                status: 'pending',
                progress: {
                    total: 30,
                    submitted: 0,
                    graded: 0
                },
                students: []
            };
            
            // Add to sample data
            sampleAssignments.push(newAssignment);
            
            // Close modal
            tutupModal();
            
            // Update UI
            loadAllAssignments();
            loadPendingAssignments();
            loadAssignmentTable();
            updateAssignmentStatistics();
            
            // Show success message
            showNotification('Tugasan berjaya ditambah', 'success');
        }

        // View assignment details
        function lihatTugasan(assignmentId) {
            const assignment = sampleAssignments.find(a => a.id === assignmentId);
            if (!assignment) return;
            
            // Format dates
            const givenDateStr = assignment.givenDate.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const dueDateStr = assignment.dueDate.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            // Get subject name
            const subjectNames = {
                'matematik': 'Matematik',
                'sains': 'Sains',
                'bm': 'Bahasa Melayu',
                'bi': 'Bahasa Inggeris',
                'pj': 'PJ & Kesihatan'
            };
            const subjectName = subjectNames[assignment.subject] || assignment.subject;
            
            // Get priority text
            const priorityText = assignment.priority === 'high' ? 'Tinggi' : 
                                assignment.priority === 'medium' ? 'Sederhana' : 'Rendah';
            
            // Get status text
            const statusText = assignment.status === 'pending' ? 'Belum Selesai' :
                             assignment.status === 'submitted' ? 'Telah Dihantar' :
                             assignment.status === 'graded' ? 'Telah Dinilai' :
                             assignment.status === 'late' ? 'Lewat Hantar' : 'Lewat Tempoh';
            
            const details = `
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-gray);">${assignment.title}</h3>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                        <span style="background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${subjectName}</span>
                        <span style="background: ${assignment.priority === 'high' ? 'rgba(239, 68, 68, 0.1)' : assignment.priority === 'medium' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)'}; color: ${assignment.priority === 'high' ? 'var(--danger)' : assignment.priority === 'medium' ? 'var(--warning)' : 'var(--success)'}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${priorityText}</span>
                        <span style="background: var(--light-gray); color: var(--medium-gray); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${assignment.class === 'all' ? 'Semua Kelas' : 'Kelas ' + assignment.class}</span>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 10px; color: var(--dark-gray);">Butiran Tugasan</h4>
                    <p style="color: var(--medium-gray); line-height: 1.6;">${assignment.details || 'Tiada butiran tambahan'}</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Tarikh Diberi</div>
                        <div style="color: var(--dark-gray);">${givenDateStr}</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Tarikh Hantar</div>
                        <div style="color: var(--dark-gray);">${dueDateStr}</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Markah Penuh</div>
                        <div style="color: var(--dark-gray);">${assignment.totalMarks}</div>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Status</div>
                        <div style="color: var(--dark-gray);">${statusText}</div>
                    </div>
                </div>
                
                ${assignment.progress ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 10px; color: var(--dark-gray);">Kemajuan Serahan</h4>
                    <div style="background: #e5e7eb; height: 8px; border-radius: 4px; margin-bottom: 10px;">
                        <div style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; border-radius: 4px; width: ${Math.round((assignment.progress.submitted / assignment.progress.total) * 100)}%"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 12px; color: var(--medium-gray);">${assignment.progress.submitted} daripada ${assignment.progress.total} pelajar telah menghantar</span>
                        <span style="font-size: 12px; font-weight: 600; color: var(--primary);">${Math.round((assignment.progress.submitted / assignment.progress.total) * 100)}%</span>
                    </div>
                </div>
                ` : ''}
                
                ${assignment.attachment ? `
                <div style="margin-bottom: 20px;">
                    <h4 style="margin-bottom: 10px; color: var(--dark-gray);">Lampiran</h4>
                    <div style="display: flex; align-items: center; gap: 10px; background: var(--light-gray); padding: 10px; border-radius: 8px;">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                        <span style="color: var(--dark-gray);">${assignment.attachment}</span>
                        <button class="action-btn primary" style="margin-left: auto; padding: 5px 10px; font-size: 12px;">
                            <i class="fas fa-download"></i>
                            Muat Turun
                        </button>
                    </div>
                </div>
                ` : ''}
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn success" onclick="semakSerahan('${assignment.id}')" style="flex: 1;">
                        <i class="fas fa-check-circle"></i>
                        Semak Serahan
                    </button>
                    <button class="action-btn warning" onclick="kemaskiniTugasan('${assignment.id}')" style="flex: 1;">
                        <i class="fas fa-edit"></i>
                        Kemaskini
                    </button>
                </div>
            `;
            
            showNotification('Butiran Tugasan', 'info', details);
        }

        // Check submissions
        function semakSerahan(assignmentId) {
            const assignment = sampleAssignments.find(a => a.id === assignmentId);
            if (!assignment) return;
            
            let submissionsHTML = '';
            
            if (assignment.students && assignment.students.length > 0) {
                submissionsHTML = `
                    <h4 style="margin-bottom: 15px; color: var(--dark-gray);">Senarai Serahan Pelajar</h4>
                    <div style="max-height: 300px; overflow-y: auto;">
                        ${assignment.students.map(student => {
                            const initials = student.name.split(' ').map(n => n.charAt(0)).join('');
                            let statusClass = '';
                            let statusText = '';
                            let marksInput = '';
                            
                            if (student.status === 'graded') {
                                statusClass = 'status-submitted-badge';
                                statusText = 'Dinilai';
                                marksInput = `<input type="number" value="${student.marks}" style="width: 60px; padding: 5px; border: 1px solid #e5e7eb; border-radius: 4px;" onchange="kemaskiniMarkah('${assignment.id}', '${student.id}', this.value)">`;
                            } else if (student.status === 'submitted') {
                                statusClass = 'status-submitted-badge';
                                statusText = 'Dihantar';
                                marksInput = `<input type="number" placeholder="Markah" style="width: 60px; padding: 5px; border: 1px solid #e5e7eb; border-radius: 4px;" onchange="kemaskiniMarkah('${assignment.id}', '${student.id}', this.value)">`;
                            } else if (student.status === 'pending') {
                                statusClass = 'status-pending-badge';
                                statusText = 'Belum Hantar';
                            } else if (student.status === 'late') {
                                statusClass = 'status-late-badge';
                                statusText = 'Lewat Hantar';
                                marksInput = `<input type="number" value="${student.marks || ''}" style="width: 60px; padding: 5px; border: 1px solid #e5e7eb; border-radius: 4px;" onchange="kemaskiniMarkah('${assignment.id}', '${student.id}', this.value)">`;
                            } else if (student.status === 'overdue') {
                                statusClass = 'status-overdue-badge';
                                statusText = 'Lewat Tempoh';
                            }
                            
                            const submittedDate = student.submittedDate ? 
                                student.submittedDate.toLocaleDateString('ms-MY') : '-';
                            
                            return `
                                <div style="display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #e5e7eb;">
                                    <div class="student-avatar" style="width: 30px; height: 30px; font-size: 10px;">${initials}</div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 13px;">${student.name}</div>
                                        <div style="font-size: 11px; color: var(--medium-gray);">Dihantar: ${submittedDate}</div>
                                    </div>
                                    <span class="${statusClass}" style="font-size: 11px; padding: 3px 8px;">${statusText}</span>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        ${marksInput}
                                        <span style="font-size: 11px; color: var(--medium-gray);">/ ${assignment.totalMarks}</span>
                                    </div>
                                    ${student.submittedDate ? `
                                    <button class="action-btn info" onclick="lihatSerahan('${assignment.id}', '${student.id}')" style="padding: 5px 10px; font-size: 11px;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ` : ''}
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            } else {
                submissionsHTML = `
                    <div style="text-align: center; padding: 40px 20px;">
                        <i class="fas fa-inbox" style="font-size: 48px; color: var(--primary-light); margin-bottom: 15px;"></i>
                        <h3 style="margin-bottom: 10px; color: var(--dark-gray);">Tiada Serahan</h3>
                        <p style="color: var(--medium-gray);">Tiada pelajar yang menghantar tugasan ini lagi</p>
                    </div>
                `;
            }
            
            submissionContent.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-gray);">${assignment.title}</h3>
                    <div style="font-size: 14px; color: var(--medium-gray); margin-bottom: 20px;">
                        Kelas: ${assignment.class === 'all' ? 'Semua Kelas' : 'Kelas ' + assignment.class} | 
                        Tarikh Hantar: ${assignment.dueDate.toLocaleDateString('ms-MY')}
                    </div>
                </div>
                ${submissionsHTML}
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn primary" onclick="hantarPeringatanTugasan('${assignment.id}')" style="flex: 1;">
                        <i class="fas fa-bell"></i>
                        Hantar Peringatan
                    </button>
                    <button class="action-btn success" onclick="eksportSenarai('${assignment.id}')" style="flex: 1;">
                        <i class="fas fa-file-export"></i>
                        Eksport Senarai
                    </button>
                </div>
            `;
            
            viewSubmissionModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Update marks
        function kemaskiniMarkah(assignmentId, studentId, marks) {
            const assignment = sampleAssignments.find(a => a.id === assignmentId);
            if (!assignment || !assignment.students) return;
            
            const student = assignment.students.find(s => s.id === studentId);
            if (!student) return;
            
            student.marks = parseInt(marks);
            if (student.status === 'submitted') {
                student.status = 'graded';
            }
            
            // Update progress
            if (assignment.progress) {
                assignment.progress.graded = assignment.students.filter(s => s.status === 'graded').length;
            }
            
            // Check if all submissions are graded
            if (assignment.progress && assignment.progress.graded === assignment.progress.total) {
                assignment.status = 'graded';
            }
            
            showNotification(`Markah untuk ${student.name} telah dikemaskini`, 'success');
        }

        // View submission
        function lihatSerahan(assignmentId, studentId) {
            const assignment = sampleAssignments.find(a => a.id === assignmentId);
            if (!assignment || !assignment.students) return;
            
            const student = assignment.students.find(s => s.id === studentId);
            if (!student) return;
            
            const submittedDate = student.submittedDate ? 
                student.submittedDate.toLocaleDateString('ms-MY', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : '-';
            
            const details = `
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-gray);">Serahan Tugasan</h3>
                    <div style="font-size: 14px; color: var(--medium-gray);">${assignment.title}</div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 15px; background: var(--light-gray); border-radius: 10px;">
                    <div class="student-avatar" style="width: 50px; height: 50px; font-size: 16px;">${student.name.split(' ').map(n => n.charAt(0)).join('')}</div>
                    <div>
                        <div style="font-weight: 600; font-size: 16px;">${student.name}</div>
                        <div style="font-size: 13px; color: var(--medium-gray);">Kelas: ${assignment.class}</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Tarikh & Masa Hantar</div>
                    <div style="color: var(--dark-gray);">${submittedDate}</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Markah</div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="number" value="${student.marks || ''}" style="width: 100px; padding: 10px; border: 2px solid #e5e7eb; border-radius: 8px;" onchange="kemaskiniMarkah('${assignment.id}', '${student.id}', this.value)">
                        <span style="color: var(--medium-gray);">/ ${assignment.totalMarks}</span>
                        <button class="action-btn success" onclick="kemaskiniMarkah('${assignment.id}', '${student.id}', document.querySelector('input[type=\"number\"]').value)" style="margin-left: auto;">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Komen & Maklum Balas</div>
                    <textarea style="width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 8px; min-height: 100px;" placeholder="Tulis komen atau maklum balas untuk pelajar..."></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button class="action-btn primary" style="flex: 1;">
                        <i class="fas fa-download"></i>
                        Muat Turun Fail
                    </button>
                    <button class="action-btn success" style="flex: 1;">
                        <i class="fas fa-share"></i>
                        Hantar Maklum Balas
                    </button>
                </div>
            `;
            
            showNotification('Serahan Pelajar', 'info', details);
        }

        // Update assignment
        function kemaskiniTugasan(assignmentId) {
            const assignment = sampleAssignments.find(a => a.id === assignmentId);
            if (!assignment) return;
            
            // In a real app, this would open a pre-filled form
            showNotification('Kemaskini Tugasan', 'info', `Fitur kemaskini akan dibuka untuk: ${assignment.title}`);
        }

        // Send assignment reminder
        function hantarPeringatanTugasan(assignmentId) {
            showNotification('Peringatan', 'info', 'Peringatan akan dihantar kepada pelajar yang belum menghantar tugasan.');
        }

        // Export list
        function eksportSenarai(assignmentId) {
            showNotification('Eksport Senarai', 'info', 'Senarai serahan sedang disediakan untuk eksport...');
            setTimeout(() => {
                showNotification('Senarai berjaya dieksport', 'success');
            }, 1500);
        }

        // Filter assignments
        function tapilkanTugasan() {
            const classFilter = document.getElementById('filterClass').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const priorityFilter = document.getElementById('filterPriority').value;
            
            // Filter assignments
            const filteredAssignments = sampleAssignments.filter(assignment => {
                if (classFilter !== 'all' && assignment.class !== classFilter && assignment.class !== 'all') return false;
                if (subjectFilter !== 'all' && assignment.subject !== subjectFilter) return false;
                if (statusFilter !== 'all' && assignment.status !== statusFilter) return false;
                if (priorityFilter !== 'all' && assignment.priority !== priorityFilter) return false;
                return true;
            });
            
            // Update display based on active tab
            const activeTab = document.querySelector('.tab-btn.active').textContent;
            if (activeTab.includes('Semua')) {
                displayFilteredAssignments(filteredAssignments, 'allAssignments');
            } else if (activeTab.includes('Belum')) {
                displayFilteredAssignments(filteredAssignments.filter(a => a.status === 'pending'), 'pendingAssignments');
            } else if (activeTab.includes('Dinilai')) {
                displayFilteredAssignments(filteredAssignments.filter(a => a.status === 'graded'), 'gradedAssignments');
            } else if (activeTab.includes('Lewat')) {
                displayFilteredAssignments(filteredAssignments.filter(a => a.status === 'late' || a.status === 'overdue'), 'overdueAssignments');
            }
            
            // Update table
            displayFilteredTable(filteredAssignments);
        }

        // Display filtered assignments
        function displayFilteredAssignments(assignments, containerId) {
            const container = document.getElementById(containerId);
            
            if (assignments.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Tiada Tugasan Ditemui</h3>
                        <p>Cuba ubah tetapan tapis anda</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = assignments.map(assignment => createAssignmentCard(assignment)).join('');
        }

        // Display filtered table
        function displayFilteredTable(assignments) {
            if (assignments.length === 0) {
                assignmentTableBody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px;">
                            <div class="empty-state" style="padding: 0;">
                                <i class="fas fa-search"></i>
                                <h3>Tiada Tugasan Ditemui</h3>
                                <p>Cuba ubah tetapan tapis anda</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            assignmentTableBody.innerHTML = assignments.map(assignment => createAssignmentTableRow(assignment)).join('');
        }

        // Reload assignments
        function muatSemulaTugasan() {
            showNotification('Muat semula tugasan...', 'info');
            
            // Simulate reload
            setTimeout(() => {
                loadAllAssignments();
                loadPendingAssignments();
                loadGradedAssignments();
                loadOverdueAssignments();
                loadAssignmentTable();
                updateAssignmentStatistics();
                
                showNotification('Tugasan berjaya dimuat semula', 'success');
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