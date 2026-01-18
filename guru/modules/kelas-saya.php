<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas Saya - SlipKu</title>
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

        .action-btn.view {
            background: var(--info);
            color: white;
        }

        .action-btn.view:hover {
            background: #2563eb;
        }

        .action-btn.edit {
            background: var(--warning);
            color: white;
        }

        .action-btn.edit:hover {
            background: #d97706;
        }

        .action-btn.delete {
            background: var(--danger);
            color: white;
        }

        .action-btn.delete:hover {
            background: #dc2626;
        }

        .action-btn.manage {
            background: var(--success);
            color: white;
        }

        .action-btn.manage:hover {
            background: #0da271;
        }

        /* Search and Filter Section */
        .search-filter-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
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

        /* Class Summary */
        .class-summary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            padding: 25px;
            color: white;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .summary-info h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .summary-info p {
            opacity: 0.9;
        }

        .summary-stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .summary-stat {
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Class Table Container */
        .class-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
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
            position: sticky;
            top: 0;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        tr:hover td {
            background: var(--primary-light);
        }

        /* Class Info Cell */
        .class-info-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .class-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            flex-shrink: 0;
        }

        .class-details {
            display: flex;
            flex-direction: column;
        }

        .class-name {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
            font-size: 16px;
        }

        .class-subject {
            font-size: 13px;
            color: var(--medium-gray);
        }

        /* Performance Indicators */
        .performance-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .performance-bar {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .performance-fill {
            height: 100%;
            border-radius: 4px;
        }

        .performance-excellent {
            background: var(--success);
        }

        .performance-good {
            background: #3b82f6;
        }

        .performance-average {
            background: var(--warning);
        }

        .performance-poor {
            background: var(--danger);
        }

        .performance-value {
            font-weight: 600;
            color: var(--dark-gray);
            min-width: 40px;
            text-align: right;
        }

        /* Student Count Cell */
        .student-count-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-avatars {
            display: flex;
        }

        .student-avatar {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 11px;
            margin-left: -8px;
            border: 2px solid var(--white);
            cursor: pointer;
            transition: var(--transition);
        }

        .student-avatar:first-child {
            margin-left: 0;
        }

        .student-avatar:hover {
            transform: translateY(-2px);
            z-index: 1;
        }

        .student-avatar.more {
            background: var(--light-gray);
            color: var(--medium-gray);
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-completed {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        /* Action Cells */
        .action-cell {
            display: flex;
            gap: 8px;
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
            max-width: 800px;
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

        .form-label.required::after {
            content: ' *';
            color: var(--danger);
        }

        .form-input, .form-select, .form-date {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-date:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            border-top: 2px solid var(--light-gray);
        }

        /* Student List in Modal */
        .student-list-modal {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .student-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: var(--transition);
        }

        .student-item:hover {
            background: var(--light-gray);
        }

        .student-item:last-child {
            border-bottom: none;
        }

        .student-item-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-item-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .student-item-details h4 {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-item-details p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .pagination-info {
            font-size: 14px;
            color: var(--medium-gray);
        }

        .pagination-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .page-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: var(--white);
            color: var(--dark-gray);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .page-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
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

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.classes {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .stat-icon.students {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .stat-icon.performance {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .stat-icon.attendance {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .stat-info h3 {
            font-size: 14px;
            color: var(--medium-gray);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark-gray);
            line-height: 1;
            margin-bottom: 5px;
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
            
            .class-summary {
                flex-direction: column;
                text-align: center;
            }
            
            .summary-stats {
                justify-content: center;
            }
            
            .quick-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .pagination {
                flex-direction: column;
                gap: 15px;
                text-align: center;
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
            
            .summary-stat {
                min-width: 80px;
            }
            
            .stat-number {
                font-size: 24px;
            }
            
            .quick-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // Set current page untuk sidebar
    $current_page = 'kelas-saya.php';
    ?>
    <!-- Modal for Class Details -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Maklumat Kelas</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="class-detail-header" style="text-align: center; margin-bottom: 25px;">
                    <div class="class-icon" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 id="classNameDetail" style="font-size: 24px; color: var(--primary); margin-bottom: 5px;">Kelas 6A</h3>
                    <p id="classSubjectDetail" style="color: var(--medium-gray); font-size: 16px;">Matematik</p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px;">
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Guru Kelas</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classTeacherDetail">Cikgu Ahmad</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Tahun</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classYearDetail">Tahun 6</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Prestasi Purata</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classPerformanceDetail">82.5%</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Kehadiran</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="classAttendanceDetail">94.2%</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Senarai Pelajar</h4>
                    <div class="student-list-modal" id="studentListModal">
                        <!-- Student list will be loaded here -->
                    </div>
                </div>
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">Catatan</h4>
                    <p id="classNotesDetail" style="color: var(--medium-gray); font-size: 13px; line-height: 1.6;">Tiada catatan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit Class -->
    <div class="modal" id="editClassModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="editModalTitle">Tambah Kelas Baru</h3>
                <button class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="classForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Nama Kelas</label>
                            <input type="text" class="form-input" id="className" placeholder="Contoh: 6A, 5B" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="classYear" required>
                                <option value="">Pilih Tahun</option>
                                <option value="6">Tahun 6</option>
                                <option value="5">Tahun 5</option>
                                <option value="4">Tahun 4</option>
                                <option value="3">Tahun 3</option>
                                <option value="2">Tahun 2</option>
                                <option value="1">Tahun 1</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Subjek</label>
                            <select class="form-select" id="classSubject" required>
                                <option value="">Pilih Subjek</option>
                                <option value="Matematik">Matematik</option>
                                <option value="Sains">Sains</option>
                                <option value="Bahasa Melayu">Bahasa Melayu</option>
                                <option value="Bahasa Inggeris">Bahasa Inggeris</option>
                                <option value="PJ & Kesihatan">PJ & Kesihatan</option>
                                <option value="Pendidikan Islam">Pendidikan Islam</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Guru Kelas</label>
                            <select class="form-select" id="classTeacher" required>
                                <option value="">Pilih Guru</option>
                                <option value="Cikgu Ahmad">Cikgu Ahmad</option>
                                <option value="Cikgu Siti">Cikgu Siti</option>
                                <option value="Cikgu Ali">Cikgu Ali</option>
                                <option value="Cikgu Fatimah">Cikgu Fatimah</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-input" id="classNotes" placeholder="Catatan tambahan mengenai kelas..." rows="3"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Kelas
                        </button>
                    </div>
                </form>
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
            <a href="dashboard-guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Kelas Saya</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="top-nav">
                <a href="dashboard-guru.php" class="nav-item">
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
       
    <?php
    require_once '../includes/session.php';
    require_once '../includes/functions.php';
    SessionManager::requireGuruLogin();

    $functions = new GuruFunctions();
    $pelajar_list = $functions->getAllPelajar();
    ?>

    <?php include '../includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Kelas saya 🏫</h2>
                <p>Urus dan pantau semua kelas yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahKelas()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Kelas
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon classes">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH KELAS</h3>
                    <div class="stat-value" id="totalClasses">3</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <h3>JUMLAH PELAJAR</h3>
                    <div class="stat-value" id="totalStudents">85</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>PRESTASI PURATA</h3>
                    <div class="stat-value" id="avgPerformance">78.9%</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon attendance">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-info">
                    <h3>KEHADIRAN PURATA</h3>
                    <div class="stat-value" id="avgAttendance">92.3%</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari kelas mengikut nama, subjek, atau guru..." onkeyup="searchClasses()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="filterClasses()">
                        <option value="">Semua Tahun</option>
                        <option value="6">Tahun 6</option>
                        <option value="5">Tahun 5</option>
                        <option value="4">Tahun 4</option>
                        <option value="3">Tahun 3</option>
                        <option value="2">Tahun 2</option>
                        <option value="1">Tahun 1</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Subjek:</label>
                    <select class="filter-select" id="filterSubject" onchange="filterClasses()">
                        <option value="">Semua Subjek</option>
                        <option value="Matematik">Matematik</option>
                        <option value="Sains">Sains</option>
                        <option value="Bahasa Melayu">Bahasa Melayu</option>
                        <option value="Bahasa Inggeris">Bahasa Inggeris</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="filterClasses()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="completed">Tamat</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Susunan:</label>
                    <select class="filter-select" id="filterSort" onchange="filterClasses()">
                        <option value="name">Nama Kelas (A-Z)</option>
                        <option value="performance">Prestasi (Tertinggi)</option>
                        <option value="students">Bilangan Pelajar</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Class Table -->
        <div class="class-table-container">
            <table id="classTable">
                <thead>
                    <tr>
                        <th>KELAS</th>
                        <th>TAHUN</th>
                        <th>SUBJEK</th>
                        <th>PELAJAR</th>
                        <th>PRESTASI</th>
                        <th>KEHADIRAN</th>
                        <th>STATUS</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="classTableBody">
                    <!-- Class rows will be loaded here -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Tiada Kelas Ditemui</h3>
                <p>Tiada kelas yang sepadan dengan carian atau penapis anda.</p>
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Penapis
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info" id="paginationInfo">
                Menunjukkan 1-3 daripada 3 kelas
            </div>
            <div class="pagination-controls">
                <button class="page-btn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="page-btn active">1</button>
                <button class="page-btn" onclick="changePage('next')">
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
        const classModal = document.getElementById('classModal');
        const editClassModal = document.getElementById('editClassModal');
        const classTableBody = document.getElementById('classTableBody');
        const emptyState = document.getElementById('emptyState');
        const classForm = document.getElementById('classForm');

        // Current state
        let classesData = [];
        let filteredClasses = [];
        let displayedClasses = [];
        let isEditingClass = false;
        let currentClassId = null;
        let currentPage = 1;
        const classesPerPage = 10;

        // Sample data for classes with more detail
        const sampleClasses = [
            {
                id: 'CLS001',
                name: 'Kelas 6A',
                year: '6',
                subject: 'Matematik',
                teacher: 'Cikgu Ahmad',
                totalStudents: 28,
                averagePerformance: 82.5,
                attendanceRate: 94.2,
                completionRate: 87.5,
                notes: 'Kelas cemerlang, fokus kepada topik Algebra dan Geometri. Perlu lebih latihan untuk topik Pecahan.',
                status: 'active',
                students: [
                    { id: 'ST001', name: 'Ahmad bin Ali', performance: 85 },
                    { id: 'ST002', name: 'Siti binti Abu', performance: 90 },
                    { id: 'ST003', name: 'Muhammad bin Hassan', performance: 78 },
                    { id: 'ST004', name: 'Aisha binti Kamal', performance: 92 },
                    { id: 'ST005', name: 'Ali bin Omar', performance: 76 },
                    { id: 'ST006', name: 'Fatimah binti Yusuf', performance: 88 },
                    { id: 'ST007', name: 'Hakim bin Zamri', performance: 70 },
                    { id: 'ST008', name: 'Nurul binti Hamid', performance: 65 }
                ],
                createdAt: '2023-01-15'
            },
            {
                id: 'CLS002',
                name: 'Kelas 6B',
                year: '6',
                subject: 'Sains',
                teacher: 'Cikgu Ahmad',
                totalStudents: 30,
                averagePerformance: 78.3,
                attendanceRate: 91.5,
                completionRate: 82.3,
                notes: 'Perlu fokus kepada pelajar yang lemah dalam topik Biologi. Latihan tambahan untuk eksperimen sains.',
                status: 'active',
                students: [
                    { id: 'ST009', name: 'Ravi a/l Kumar', performance: 72 },
                    { id: 'ST010', name: 'Priya a/p Raj', performance: 85 },
                    { id: 'ST011', name: 'Kumar a/l Muthu', performance: 68 },
                    { id: 'ST012', name: 'Mei Ling', performance: 90 },
                    { id: 'ST013', name: 'Wei Jian', performance: 79 },
                    { id: 'ST014', name: 'Sofia binti David', performance: 82 },
                    { id: 'ST015', name: 'Amir bin Razak', performance: 80 },
                    { id: 'ST016', name: 'Zainab binti Musa', performance: 72 }
                ],
                createdAt: '2023-01-15'
            },
            {
                id: 'CLS003',
                name: 'Kelas 5A',
                year: '5',
                subject: 'Matematik',
                teacher: 'Cikgu Ahmad',
                totalStudents: 27,
                averagePerformance: 75.6,
                attendanceRate: 89.8,
                completionRate: 80.1,
                notes: 'Kelas sederhana, banyak latihan tambahan diperlukan untuk topik Pecahan dan Perpuluhan.',
                status: 'active',
                students: [
                    { id: 'ST017', name: 'Faris bin Ismail', performance: 85 },
                    { id: 'ST018', name: 'Sarah binti Adam', performance: 78 },
                    { id: 'ST019', name: 'Hafiz bin Johari', performance: 72 },
                    { id: 'ST020', name: 'Nadia binti Kamal', performance: 68 },
                    { id: 'ST021', name: 'Daniel bin Lee', performance: 82 },
                    { id: 'ST022', name: 'Chong Wei', performance: 75 },
                    { id: 'ST023', name: 'Anisah binti Samad', performance: 70 },
                    { id: 'ST024', name: 'Rahman bin Ali', performance: 65 }
                ],
                createdAt: '2023-01-20'
            },
            {
                id: 'CLS004',
                name: 'Kelas 4A',
                year: '4',
                subject: 'Bahasa Melayu',
                teacher: 'Cikgu Siti',
                totalStudents: 25,
                averagePerformance: 70.2,
                attendanceRate: 88.5,
                completionRate: 75.3,
                notes: 'Kelas memerlukan bimbingan dalam penulisan karangan dan tatabahasa.',
                status: 'inactive',
                students: [
                    { id: 'ST025', name: 'Ahmad bin Kamal', performance: 65 },
                    { id: 'ST026', name: 'Siti binti Ali', performance: 72 },
                    { id: 'ST027', name: 'Muhammad bin Yusuf', performance: 68 },
                    { id: 'ST028', name: 'Aminah binti Hassan', performance: 75 }
                ],
                createdAt: '2023-02-10'
            },
            {
                id: 'CLS005',
                name: 'Kelas 6C',
                year: '6',
                subject: 'Bahasa Inggeris',
                teacher: 'Cikgu Ali',
                totalStudents: 26,
                averagePerformance: 80.5,
                attendanceRate: 93.1,
                completionRate: 85.2,
                notes: 'Kelas menunjukkan peningkatan dalam vocabulary dan grammar.',
                status: 'completed',
                students: [
                    { id: 'ST029', name: 'John Doe', performance: 88 },
                    { id: 'ST030', name: 'Jane Smith', performance: 82 },
                    { id: 'ST031', name: 'Robert Johnson', performance: 79 },
                    { id: 'ST032', name: 'Emily Davis', performance: 85 }
                ],
                createdAt: '2023-01-25'
            }
        ];

        // Initialize page
        function initializePage() {
            classesData = [...sampleClasses];
            filteredClasses = [...classesData];
            
            // Set up form submit handler
            classForm.addEventListener('submit', saveClass);
            
            // Load initial data
            updateStats();
            loadClassTable();
            updatePaginationInfo();
            
            // Set up filter listeners
            document.getElementById('filterYear').addEventListener('change', filterClasses);
            document.getElementById('filterSubject').addEventListener('change', filterClasses);
            document.getElementById('filterStatus').addEventListener('change', filterClasses);
            document.getElementById('filterSort').addEventListener('change', filterClasses);
        }

        // Load class table
        function loadClassTable() {
            if (filteredClasses.length === 0) {
                classTableBody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }
            
            emptyState.style.display = 'none';
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * classesPerPage;
            const endIndex = startIndex + classesPerPage;
            displayedClasses = filteredClasses.slice(startIndex, endIndex);
            
            classTableBody.innerHTML = displayedClasses.map(cls => {
                // Get class icon based on subject
                const classIcons = {
                    'Matematik': 'fas fa-calculator',
                    'Sains': 'fas fa-flask',
                    'Bahasa Melayu': 'fas fa-book-open',
                    'Bahasa Inggeris': 'fas fa-language',
                    'PJ & Kesihatan': 'fas fa-running',
                    'Pendidikan Islam': 'fas fa-mosque'
                };
                
                // Determine performance class
                let performanceClass = '';
                let performanceWidth = '';
                if (cls.averagePerformance >= 85) {
                    performanceClass = 'performance-excellent';
                    performanceWidth = '90%';
                } else if (cls.averagePerformance >= 70) {
                    performanceClass = 'performance-good';
                    performanceWidth = '75%';
                } else if (cls.averagePerformance >= 60) {
                    performanceClass = 'performance-average';
                    performanceWidth = '60%';
                } else {
                    performanceClass = 'performance-poor';
                    performanceWidth = '40%';
                }
                
                // Determine status badge
                let statusClass = '';
                let statusText = '';
                switch(cls.status) {
                    case 'active':
                        statusClass = 'status-active';
                        statusText = 'AKTIF';
                        break;
                    case 'inactive':
                        statusClass = 'status-inactive';
                        statusText = 'TIDAK AKTIF';
                        break;
                    case 'completed':
                        statusClass = 'status-completed';
                        statusText = 'TAMAT';
                        break;
                }
                
                // Get student avatars (first 5)
                const studentAvatars = cls.students.slice(0, 5).map((student, index) => {
                    const initials = student.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                    return `<div class="student-avatar" title="${student.name}" style="z-index: ${5 - index}">${initials}</div>`;
                }).join('');
                
                // Add more indicator if more than 5 students
                const moreIndicator = cls.students.length > 5 ? 
                    `<div class="student-avatar more" title="${cls.students.length - 5} lagi pelajar">+${cls.students.length - 5}</div>` : '';
                
                return `
                    <tr>
                        <td>
                            <div class="class-info-cell">
                                <div class="class-icon">
                                    <i class="${classIcons[cls.subject] || 'fas fa-chalkboard-teacher'}"></i>
                                </div>
                                <div class="class-details">
                                    <div class="class-name">${cls.name}</div>
                                    <div class="class-subject">${cls.subject} • ${cls.teacher}</div>
                                </div>
                            </div>
                        </td>
                        <td>Tahun ${cls.year}</td>
                        <td>${cls.subject}</td>
                        <td>
                            <div class="student-count-cell">
                                <div class="student-avatars">
                                    ${studentAvatars}
                                    ${moreIndicator}
                                </div>
                                <span style="font-weight: 600; color: var(--dark-gray);">${cls.totalStudents}</span>
                            </div>
                        </td>
                        <td>
                            <div class="performance-cell">
                                <div class="performance-bar">
                                    <div class="performance-fill ${performanceClass}" style="width: ${performanceWidth}"></div>
                                </div>
                                <div class="performance-value">${cls.averagePerformance}%</div>
                            </div>
                        </td>
                        <td>${cls.attendanceRate}%</td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            <div class="action-cell">
                                <button class="action-btn view" onclick="viewClass('${cls.id}')">
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </button>
                                <button class="action-btn manage" onclick="manageClass('${cls.id}')">
                                    <i class="fas fa-cog"></i>
                                    Urus
                                </button>
                                <button class="action-btn edit" onclick="editClass('${cls.id}')">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update statistics
        function updateStats() {
            const totalClasses = classesData.length;
            const totalStudents = classesData.reduce((sum, cls) => sum + cls.totalStudents, 0);
            
            // Calculate average performance
            const totalPerformance = classesData.reduce((sum, cls) => sum + cls.averagePerformance, 0);
            const avgPerformance = totalPerformance / totalClasses;
            
            // Calculate average attendance
            const totalAttendance = classesData.reduce((sum, cls) => sum + cls.attendanceRate, 0);
            const avgAttendance = totalAttendance / totalClasses;
            
            document.getElementById('totalClasses').textContent = totalClasses;
            document.getElementById('totalStudents').textContent = totalStudents;
            document.getElementById('avgPerformance').textContent = avgPerformance.toFixed(1) + '%';
            document.getElementById('avgAttendance').textContent = avgAttendance.toFixed(1) + '%';
        }

        // Search classes
        function searchClasses() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            filteredClasses = classesData.filter(cls => {
                return cls.name.toLowerCase().includes(searchTerm) ||
                       cls.subject.toLowerCase().includes(searchTerm) ||
                       cls.teacher.toLowerCase().includes(searchTerm) ||
                       cls.id.toLowerCase().includes(searchTerm);
            });
            
            currentPage = 1;
            loadClassTable();
            updatePaginationInfo();
        }

        // Filter classes
        function filterClasses() {
            const yearFilter = document.getElementById('filterYear').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const sortFilter = document.getElementById('filterSort').value;
            
            filteredClasses = classesData.filter(cls => {
                // Apply year filter
                if (yearFilter && cls.year !== yearFilter) return false;
                
                // Apply subject filter
                if (subjectFilter && cls.subject !== subjectFilter) return false;
                
                // Apply status filter
                if (statusFilter && cls.status !== statusFilter) return false;
                
                return true;
            });
            
            // Apply sorting
            if (sortFilter === 'name') {
                filteredClasses.sort((a, b) => a.name.localeCompare(b.name));
            } else if (sortFilter === 'performance') {
                filteredClasses.sort((a, b) => b.averagePerformance - a.averagePerformance);
            } else if (sortFilter === 'students') {
                filteredClasses.sort((a, b) => b.totalStudents - a.totalStudents);
            }
            
            currentPage = 1;
            loadClassTable();
            updatePaginationInfo();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterYear').value = '';
            document.getElementById('filterSubject').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterSort').value = 'name';
            
            filteredClasses = [...classesData];
            currentPage = 1;
            loadClassTable();
            updatePaginationInfo();
            showNotification('Semua penapis telah dikembalikan kepada tetapan asal', 'success');
        }

        // Update pagination info
        function updatePaginationInfo() {
            const total = filteredClasses.length;
            const start = Math.min((currentPage - 1) * classesPerPage + 1, total);
            const end = Math.min(currentPage * classesPerPage, total);
            
            document.getElementById('paginationInfo').textContent = 
                `Menunjukkan ${start}-${end} daripada ${total} kelas`;
        }

        // Change page
        function changePage(direction) {
            const totalPages = Math.ceil(filteredClasses.length / classesPerPage);
            
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next' && currentPage < totalPages) {
                currentPage++;
            }
            
            loadClassTable();
            updatePaginationInfo();
            
            // Update active page buttons
            const pageButtons = document.querySelectorAll('.page-btn');
            pageButtons.forEach((btn, index) => {
                if (btn.textContent === currentPage.toString()) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // View class details
        function viewClass(classId) {
            const cls = classesData.find(c => c.id === classId);
            if (cls) {
                // Update modal content
                document.getElementById('classNameDetail').textContent = cls.name;
                document.getElementById('classSubjectDetail').textContent = cls.subject;
                document.getElementById('classTeacherDetail').textContent = cls.teacher;
                document.getElementById('classYearDetail').textContent = `Tahun ${cls.year}`;
                document.getElementById('classPerformanceDetail').textContent = `${cls.averagePerformance}%`;
                document.getElementById('classAttendanceDetail').textContent = `${cls.attendanceRate}%`;
                document.getElementById('classNotesDetail').textContent = cls.notes || 'Tiada catatan';
                
                // Load student list
                const studentListModal = document.getElementById('studentListModal');
                studentListModal.innerHTML = cls.students.map(student => `
                    <div class="student-item">
                        <div class="student-item-info">
                            <div class="student-item-avatar">
                                ${student.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()}
                            </div>
                            <div class="student-item-details">
                                <h4>${student.name}</h4>
                                <p>ID: ${student.id} • Prestasi: ${student.performance}%</p>
                            </div>
                        </div>
                        <button class="action-btn view" onclick="viewStudent('${student.id}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                    </div>
                `).join('');
                
                classModal.classList.add('active');
            }
        }

        // Add new class
        function tambahKelas() {
            isEditingClass = false;
            currentClassId = null;
            document.getElementById('editModalTitle').textContent = 'Tambah Kelas Baru';
            classForm.reset();
            
            // Set default values
            document.getElementById('classYear').value = '6';
            document.getElementById('classSubject').value = 'Matematik';
            document.getElementById('classTeacher').value = 'Cikgu Ahmad';
            
            editClassModal.classList.add('active');
        }

        // Edit class
        function editClass(classId) {
            isEditingClass = true;
            currentClassId = classId;
            
            const cls = classesData.find(c => c.id === classId);
            if (cls) {
                document.getElementById('editModalTitle').textContent = 'Edit Kelas';
                document.getElementById('className').value = cls.name;
                document.getElementById('classYear').value = cls.year;
                document.getElementById('classSubject').value = cls.subject;
                document.getElementById('classTeacher').value = cls.teacher;
                document.getElementById('classNotes').value = cls.notes || '';
                
                editClassModal.classList.add('active');
            }
        }

        // Save class
        function saveClass(event) {
            event.preventDefault();
            
            const className = document.getElementById('className').value;
            const classYear = document.getElementById('classYear').value;
            const classSubject = document.getElementById('classSubject').value;
            const classTeacher = document.getElementById('classTeacher').value;
            const classNotes = document.getElementById('classNotes').value;
            
            // Validate required fields
            if (!className || !classYear || !classSubject || !classTeacher) {
                showNotification('Sila isi semua maklumat yang diperlukan', 'error');
                return;
            }
            
            if (isEditingClass && currentClassId) {
                // Update existing class
                const index = classesData.findIndex(c => c.id === currentClassId);
                if (index !== -1) {
                    classesData[index] = {
                        ...classesData[index],
                        name: className,
                        year: classYear,
                        subject: classSubject,
                        teacher: classTeacher,
                        notes: classNotes
                    };
                    
                    showNotification('Maklumat kelas berjaya dikemaskini', 'success');
                }
            } else {
                // Add new class
                const newClass = {
                    id: 'CLS' + (classesData.length + 1).toString().padStart(3, '0'),
                    name: className,
                    year: classYear,
                    subject: classSubject,
                    teacher: classTeacher,
                    totalStudents: 0,
                    averagePerformance: 0,
                    attendanceRate: 0,
                    completionRate: 0,
                    notes: classNotes,
                    students: [],
                    status: 'active',
                    createdAt: new Date().toISOString().split('T')[0]
                };
                
                classesData.push(newClass);
                showNotification('Kelas baru berjaya ditambah', 'success');
            }
            
            // Update data
            filterClasses();
            updateStats();
            closeEditModal();
        }

        // Manage class (navigation to students management)
        function manageClass(classId) {
            const cls = classesData.find(c => c.id === classId);
            if (cls) {
                showNotification(`Mengurus pelajar untuk ${cls.name}...`, 'info');
                // In a real app, this would navigate to student management for this class
                // window.location.href = `pelajar-kelas.html?class=${classId}`;
            }
        }

        // View student details
        function viewStudent(studentId) {
            // Find student in all classes
            let student = null;
            let studentClass = null;
            
            for (const cls of classesData) {
                const foundStudent = cls.students.find(s => s.id === studentId);
                if (foundStudent) {
                    student = foundStudent;
                    studentClass = cls;
                    break;
                }
            }
            
            if (student && studentClass) {
                alert(`MAKLUMAT PELAJAR\n\n` +
                      `Nama: ${student.name}\n` +
                      `ID: ${student.id}\n` +
                      `Kelas: ${studentClass.name}\n` +
                      `Subjek: ${studentClass.subject}\n` +
                      `Guru: ${studentClass.teacher}\n` +
                      `Prestasi: ${student.performance}%\n` +
                      `Prestasi Kelas: ${studentClass.averagePerformance}%`);
            }
        }

        // Reload data
        function muatSemulaData() {
            filterClasses();
            updateStats();
            showNotification('Data kelas disegarkan', 'success');
        }

        // Close modal
        function closeModal() {
            classModal.classList.remove('active');
        }

        // Close edit modal
        function closeEditModal() {
            editClassModal.classList.remove('active');
            isEditingClass = false;
            currentClassId = null;
        }

        // Show notification
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 100px;
                right: 30px;
                background: ${type === 'success' ? 'var(--success)' : 'var(--danger)'};
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
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
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
                    closeModal();
                    closeEditModal();
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
        `;
        document.head.appendChild(style);

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
            setupEventListeners();
        });
    </script>
</body>
</html>