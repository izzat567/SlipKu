<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelajar Saya - SlipKu</title>
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

        .action-btn.add {
            background: var(--success);
            color: white;
        }

        .action-btn.add:hover {
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

        /* Student Table Container */
        .student-table-container {
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

        /* Student Avatar in Table */
        .student-avatar-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
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
            flex-shrink: 0;
        }

        .student-info {
            display: flex;
            flex-direction: column;
        }

        .student-name {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .student-id {
            font-size: 12px;
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

        .status-graduated {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
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
            width: 90%;
        }

        .performance-good {
            background: #3b82f6;
            width: 75%;
        }

        .performance-average {
            background: var(--warning);
            width: 60%;
        }

        .performance-poor {
            background: var(--danger);
            width: 40%;
        }

        .performance-value {
            font-weight: 600;
            color: var(--dark-gray);
            min-width: 40px;
            text-align: right;
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
            max-width: 600px;
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

        /* Bulk Actions */
        .bulk-actions {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .bulk-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bulk-select input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
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
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .bulk-actions {
                flex-direction: column;
                align-items: flex-start;
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
        }
    </style>
</head>
<body>
    <!-- Modal for Add/Edit Student -->
    <div class="modal" id="studentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Pelajar Baru</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="studentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Nama Penuh</label>
                            <input type="text" class="form-input" id="studentName" placeholder="Nama penuh pelajar" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">No. Kad Pengenalan</label>
                            <input type="text" class="form-input" id="studentIC" placeholder="Contoh: 030101-14-1234" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Kelas</label>
                            <select class="form-select" id="studentClass" required>
                                <option value="">Pilih Kelas</option>
                                <option value="6A">Kelas 6A</option>
                                <option value="6B">Kelas 6B</option>
                                <option value="5A">Kelas 5A</option>
                                <option value="5B">Kelas 5B</option>
                                <option value="4A">Kelas 4A</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="studentYear" required>
                                <option value="">Pilih Tahun</option>
                                <option value="1">Tahun 1</option>
                                <option value="2">Tahun 2</option>
                                <option value="3">Tahun 3</option>
                                <option value="4">Tahun 4</option>
                                <option value="5">Tahun 5</option>
                                <option value="6">Tahun 6</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jantina</label>
                            <select class="form-select" id="studentGender" required>
                                <option value="">Pilih Jantina</option>
                                <option value="male">Lelaki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tarikh Lahir</label>
                            <input type="date" class="form-date" id="studentDOB" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">No. Telefon</label>
                            <input type="tel" class="form-input" id="studentPhone" placeholder="Contoh: 012-345 6789" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Emel</label>
                            <input type="email" class="form-input" id="studentEmail" placeholder="pelajar@email.com">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Alamat</label>
                        <textarea class="form-input" id="studentAddress" rows="3" placeholder="Alamat penuh pelajar" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Penjaga</label>
                            <input type="text" class="form-input" id="studentGuardian" placeholder="Nama penjaga">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hubungan</label>
                            <select class="form-select" id="studentRelationship">
                                <option value="">Pilih Hubungan</option>
                                <option value="father">Bapa</option>
                                <option value="mother">Ibu</option>
                                <option value="guardian">Penjaga</option>
                                <option value="sibling">Abang/Kakak</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan Perubatan/Khas</label>
                        <textarea class="form-input" id="studentMedical" rows="2" placeholder="Catatan khas (jika ada)"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Pelajar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Bulk Import -->
    <div class="modal" id="importModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Import Pelajar</h3>
                <button class="modal-close" onclick="closeImportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 32px; margin: 0 auto 15px;">
                        <i class="fas fa-file-import"></i>
                    </div>
                    <h4 style="font-size: 18px; margin-bottom: 10px; color: var(--dark-gray);">Import Data Pelajar</h4>
                    <p style="color: var(--medium-gray); font-size: 14px;">Muat naik fail Excel atau CSV yang mengandungi data pelajar</p>
                </div>
                
                <div style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 40px 20px; text-align: center; margin-bottom: 20px; cursor: pointer;" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: var(--primary-light); margin-bottom: 15px;"></i>
                    <p style="color: var(--medium-gray); margin-bottom: 10px;">Klik untuk muat naik fail atau seret fail ke sini</p>
                    <p style="font-size: 12px; color: var(--medium-gray);">Format yang disokong: .xlsx, .xls, .csv</p>
                </div>
                <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" style="display: none;" onchange="handleFileUpload(this)">
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <h5 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">
                        <i class="fas fa-info-circle" style="color: var(--info);"></i>
                        Panduan Format Data
                    </h5>
                    <ul style="font-size: 13px; color: var(--medium-gray); padding-left: 20px;">
                        <li>Pastikan fail mengandungi kolom: Nama, IC, Kelas, Tahun, Jantina</li>
                        <li>Format tarikh: YYYY-MM-DD</li>
                        <li>Muat turun template untuk format yang betul</li>
                    </ul>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="downloadTemplate()">
                        <i class="fas fa-download"></i>
                        Template
                    </button>
                    <button class="btn btn-primary" onclick="processImport()">
                        <i class="fas fa-upload"></i>
                        Import
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
                    <p>Pelajar Saya</p>
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
            <a href="dashboard-admin.html" class="sidebar-item">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
            <a href="kelas-saya.html" class="sidebar-item">
                <i class="fas fa-users"></i>
                Kelas Saya
                <span class="badge">3</span>
            </a>
            <a href="pelajar-saya.html" class="sidebar-item active">
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
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pelajar Saya 👨‍🎓</h2>
                <p>Urus dan pantau semua pelajar yang anda kendalikan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-info" onclick="openImportModal()">
                    <i class="fas fa-file-import"></i>
                    Import
                </button>
                <button class="btn btn-primary" onclick="tambahPelajar()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Pelajar
                </button>
            </div>
        </div>

        <!-- Class Summary -->
        <div class="class-summary">
            <div class="summary-info">
                <h3>Jumlah Keseluruhan Pelajar</h3>
                <p>Semua kelas yang anda kendalikan</p>
            </div>
            <div class="summary-stats">
                <div class="summary-stat">
                    <div class="stat-number" id="totalStudents">85</div>
                    <div class="stat-label">Pelajar</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="activeStudents">82</div>
                    <div class="stat-label">Aktif</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="averagePerformance">78.9%</div>
                    <div class="stat-label">Prestasi Purata</div>
                </div>
                <div class="summary-stat">
                    <div class="stat-number" id="attendanceRate">92.3%</div>
                    <div class="stat-label">Kehadiran</div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar mengikut nama, ID, atau kelas..." onkeyup="searchStudents()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="filterStudents()">
                        <option value="">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                        <option value="4A">Kelas 4A</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="filterStudents()">
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
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="filterStudents()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="graduated">Tamat</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Prestasi:</label>
                    <select class="filter-select" id="filterPerformance" onchange="filterStudents()">
                        <option value="">Semua Prestasi</option>
                        <option value="excellent">Cemerlang (≥85%)</option>
                        <option value="good">Baik (70-84%)</option>
                        <option value="average">Sederhana (60-69%)</option>
                        <option value="poor">Lemah (≤59%)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <div class="bulk-select">
                <input type="checkbox" id="selectAllBulk" onchange="toggleAllBulk()">
                <label for="selectAllBulk" style="font-weight: 600; color: var(--dark-gray);">
                    <span id="selectedCount">0</span> pelajar dipilih
                </label>
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="action-btn add" onclick="assignClassBulk()">
                    <i class="fas fa-users"></i>
                    Tugaskan Kelas
                </button>
                <button class="action-btn edit" onclick="updateStatusBulk()">
                    <i class="fas fa-edit"></i>
                    Kemaskini Status
                </button>
                <button class="action-btn delete" onclick="deleteStudentsBulk()">
                    <i class="fas fa-trash"></i>
                    Padam
                </button>
            </div>
        </div>

        <!-- Student Table -->
        <div class="student-table-container">
            <table id="studentTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll" onchange="toggleAllSelection()">
                        </th>
                        <th>PELAJAR</th>
                        <th>KELAS</th>
                        <th>TAHUN</th>
                        <th>JANTINA</th>
                        <th>PRESTASI</th>
                        <th>KEHADIRAN</th>
                        <th>STATUS</th>
                        <th style="width: 200px;">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    <!-- Student rows will be loaded here -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-user-graduate"></i>
                <h3>Tiada Pelajar Ditemui</h3>
                <p>Tiada pelajar yang sepadan dengan carian atau penapis anda.</p>
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Penapis
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <div class="pagination-info" id="paginationInfo">
                Menunjukkan 1-10 daripada 85 pelajar
            </div>
            <div class="pagination-controls">
                <button class="page-btn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">5</button>
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
        const studentModal = document.getElementById('studentModal');
        const importModal = document.getElementById('importModal');
        const studentTableBody = document.getElementById('studentTableBody');
        const emptyState = document.getElementById('emptyState');
        const bulkActions = document.getElementById('bulkActions');
        const studentForm = document.getElementById('studentForm');

        // Current state
        let studentsData = [];
        let filteredStudents = [];
        let displayedStudents = [];
        let isEditingStudent = false;
        let currentStudentId = null;
        let selectedStudents = new Set();
        let currentPage = 1;
        const studentsPerPage = 10;

        // Sample data for students
        const sampleStudents = [
            {
                id: 'ST001',
                name: 'Ahmad bin Ali',
                ic: '080101-14-1234',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-01-01',
                phone: '012-345 6789',
                email: 'ahmad.ali@email.com',
                address: 'No. 123, Jalan Merdeka, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Ali bin Abu',
                relationship: 'father',
                medical: 'Tiada',
                performance: 85,
                attendance: 95,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST002',
                name: 'Siti binti Abu',
                ic: '080215-08-5678',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-02-15',
                phone: '013-456 7890',
                email: 'siti.abu@email.com',
                address: 'No. 456, Jalan Damai, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Abu bin Hassan',
                relationship: 'father',
                medical: 'Asma (ringan)',
                performance: 90,
                attendance: 98,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST003',
                name: 'Muhammad bin Hassan',
                ic: '080305-10-9012',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-03-05',
                phone: '014-567 8901',
                email: 'muhammad.hassan@email.com',
                address: 'No. 789, Jalan Sejahtera, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Hassan bin Ismail',
                relationship: 'father',
                medical: 'Tiada',
                performance: 78,
                attendance: 92,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST004',
                name: 'Aisha binti Kamal',
                ic: '080410-06-3456',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-04-10',
                phone: '015-678 9012',
                email: 'aisha.kamal@email.com',
                address: 'No. 101, Jalan Aman, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Kamal bin Yusuf',
                relationship: 'father',
                medical: 'Tiada',
                performance: 92,
                attendance: 96,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST005',
                name: 'Ali bin Omar',
                ic: '080512-12-7890',
                class: '6A',
                year: '6',
                gender: 'male',
                dob: '2008-05-12',
                phone: '016-789 0123',
                email: 'ali.omar@email.com',
                address: 'No. 202, Jalan Bahagia, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Omar bin Ahmad',
                relationship: 'father',
                medical: 'Tiada',
                performance: 76,
                attendance: 89,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST006',
                name: 'Fatimah binti Yusuf',
                ic: '080623-08-1234',
                class: '6A',
                year: '6',
                gender: 'female',
                dob: '2008-06-23',
                phone: '017-890 1234',
                email: 'fatimah.yusuf@email.com',
                address: 'No. 303, Jalan Makmur, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Yusuf bin Ali',
                relationship: 'father',
                medical: 'Tiada',
                performance: 88,
                attendance: 94,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST007',
                name: 'Ravi a/l Kumar',
                ic: '080701-14-5678',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-07-01',
                phone: '018-901 2345',
                email: 'ravi.kumar@email.com',
                address: 'No. 404, Jalan Ceria, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Kumar a/l Raj',
                relationship: 'father',
                medical: 'Tiada',
                performance: 72,
                attendance: 85,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST008',
                name: 'Priya a/p Raj',
                ic: '080815-06-9012',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-08-15',
                phone: '019-012 3456',
                email: 'priya.raj@email.com',
                address: 'No. 505, Jalan Indah, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Raj a/l Muthu',
                relationship: 'father',
                medical: 'Tiada',
                performance: 85,
                attendance: 90,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST009',
                name: 'Kumar a/l Muthu',
                ic: '080920-10-3456',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-09-20',
                phone: '011-123 4567',
                email: 'kumar.muthu@email.com',
                address: 'No. 606, Jalan Permai, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'Muthu a/l Samy',
                relationship: 'father',
                medical: 'Tiada',
                performance: 68,
                attendance: 82,
                status: 'inactive',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST010',
                name: 'Mei Ling',
                ic: '081010-08-7890',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-10-10',
                phone: '012-234 5678',
                email: 'mei.ling@email.com',
                address: 'No. 707, Jalan Seri, Taman Sentosa, 43000 Kajang, Selangor',
                guardian: 'Ling Hock',
                relationship: 'father',
                medical: 'Tiada',
                performance: 90,
                attendance: 96,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST011',
                name: 'Wei Jian',
                ic: '081115-12-1234',
                class: '6B',
                year: '6',
                gender: 'male',
                dob: '2008-11-15',
                phone: '013-345 6789',
                email: 'wei.jian@email.com',
                address: 'No. 808, Jalan Damai, Taman Harmoni, 43000 Kajang, Selangor',
                guardian: 'Jian Min',
                relationship: 'father',
                medical: 'Tiada',
                performance: 79,
                attendance: 88,
                status: 'active',
                createdAt: '2023-01-15'
            },
            {
                id: 'ST012',
                name: 'Sofia binti David',
                ic: '081220-06-5678',
                class: '6B',
                year: '6',
                gender: 'female',
                dob: '2008-12-20',
                phone: '014-456 7890',
                email: 'sofia.david@email.com',
                address: 'No. 909, Jalan Bahagia, Taman Murni, 43000 Kajang, Selangor',
                guardian: 'David bin Joseph',
                relationship: 'father',
                medical: 'Tiada',
                performance: 82,
                attendance: 91,
                status: 'graduated',
                createdAt: '2023-01-15'
            }
        ];

        // Initialize page
        function initializePage() {
            studentsData = [...sampleStudents];
            filteredStudents = [...studentsData];
            
            // Set up form submit handler
            studentForm.addEventListener('submit', saveStudent);
            
            // Load initial data
            updateSummary();
            loadStudentTable();
            updatePaginationInfo();
            
            // Set today's date for date input
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('studentDOB').max = today;
        }

        // Load student table
        function loadStudentTable() {
            if (filteredStudents.length === 0) {
                studentTableBody.innerHTML = '';
                emptyState.style.display = 'block';
                bulkActions.style.display = 'none';
                return;
            }
            
            emptyState.style.display = 'none';
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * studentsPerPage;
            const endIndex = startIndex + studentsPerPage;
            displayedStudents = filteredStudents.slice(startIndex, endIndex);
            
            studentTableBody.innerHTML = displayedStudents.map(student => {
                // Get initials for avatar
                const initials = student.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                
                // Determine performance class
                let performanceClass = '';
                let performanceWidth = '';
                if (student.performance >= 85) {
                    performanceClass = 'performance-excellent';
                    performanceWidth = '90%';
                } else if (student.performance >= 70) {
                    performanceClass = 'performance-good';
                    performanceWidth = '75%';
                } else if (student.performance >= 60) {
                    performanceClass = 'performance-average';
                    performanceWidth = '60%';
                } else {
                    performanceClass = 'performance-poor';
                    performanceWidth = '40%';
                }
                
                // Determine status badge
                let statusClass = '';
                let statusText = '';
                switch(student.status) {
                    case 'active':
                        statusClass = 'status-active';
                        statusText = 'AKTIF';
                        break;
                    case 'inactive':
                        statusClass = 'status-inactive';
                        statusText = 'TIDAK AKTIF';
                        break;
                    case 'graduated':
                        statusClass = 'status-graduated';
                        statusText = 'TAMAT';
                        break;
                }
                
                // Determine gender text
                const genderText = student.gender === 'male' ? 'Lelaki' : 'Perempuan';
                
                return `
                    <tr>
                        <td>
                            <input type="checkbox" class="student-checkbox" value="${student.id}" onchange="toggleStudentSelection('${student.id}')">
                        </td>
                        <td>
                            <div class="student-avatar-cell">
                                <div class="student-avatar">
                                    ${initials}
                                </div>
                                <div class="student-info">
                                    <div class="student-name">${student.name}</div>
                                    <div class="student-id">${student.id}</div>
                                </div>
                            </div>
                        </td>
                        <td>${student.class}</td>
                        <td>Tahun ${student.year}</td>
                        <td>${genderText}</td>
                        <td>
                            <div class="performance-cell">
                                <div class="performance-bar">
                                    <div class="performance-fill ${performanceClass}" style="width: ${performanceWidth}"></div>
                                </div>
                                <div class="performance-value">${student.performance}%</div>
                            </div>
                        </td>
                        <td>${student.attendance}%</td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            <div class="action-cell">
                                <button class="action-btn view" onclick="viewStudent('${student.id}')">
                                    <i class="fas fa-eye"></i>
                                    Lihat
                                </button>
                                <button class="action-btn edit" onclick="editStudent('${student.id}')">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>
                                <button class="action-btn delete" onclick="deleteStudent('${student.id}')">
                                    <i class="fas fa-trash"></i>
                                    Padam
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Update bulk actions visibility
            updateBulkActions();
        }

        // Update summary statistics
        function updateSummary() {
            const totalStudents = studentsData.length;
            const activeStudents = studentsData.filter(s => s.status === 'active').length;
            
            // Calculate average performance
            const totalPerformance = studentsData.reduce((sum, student) => sum + student.performance, 0);
            const averagePerformance = totalPerformance / totalStudents;
            
            // Calculate average attendance
            const totalAttendance = studentsData.reduce((sum, student) => sum + student.attendance, 0);
            const averageAttendance = totalAttendance / totalStudents;
            
            document.getElementById('totalStudents').textContent = totalStudents;
            document.getElementById('activeStudents').textContent = activeStudents;
            document.getElementById('averagePerformance').textContent = averagePerformance.toFixed(1) + '%';
            document.getElementById('attendanceRate').textContent = averageAttendance.toFixed(1) + '%';
        }

        // Search students
        function searchStudents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            filteredStudents = studentsData.filter(student => {
                return student.name.toLowerCase().includes(searchTerm) ||
                       student.id.toLowerCase().includes(searchTerm) ||
                       student.class.toLowerCase().includes(searchTerm) ||
                       student.ic.includes(searchTerm);
            });
            
            currentPage = 1;
            loadStudentTable();
            updatePaginationInfo();
        }

        // Filter students
        function filterStudents() {
            const classFilter = document.getElementById('filterClass').value;
            const yearFilter = document.getElementById('filterYear').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const performanceFilter = document.getElementById('filterPerformance').value;
            
            filteredStudents = studentsData.filter(student => {
                // Apply class filter
                if (classFilter && student.class !== classFilter) return false;
                
                // Apply year filter
                if (yearFilter && student.year !== yearFilter) return false;
                
                // Apply status filter
                if (statusFilter && student.status !== statusFilter) return false;
                
                // Apply performance filter
                if (performanceFilter) {
                    if (performanceFilter === 'excellent' && student.performance < 85) return false;
                    if (performanceFilter === 'good' && (student.performance < 70 || student.performance >= 85)) return false;
                    if (performanceFilter === 'average' && (student.performance < 60 || student.performance >= 70)) return false;
                    if (performanceFilter === 'poor' && student.performance >= 60) return false;
                }
                
                return true;
            });
            
            currentPage = 1;
            loadStudentTable();
            updatePaginationInfo();
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterClass').value = '';
            document.getElementById('filterYear').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterPerformance').value = '';
            
            filteredStudents = [...studentsData];
            currentPage = 1;
            loadStudentTable();
            updatePaginationInfo();
            showNotification('Semua penapis telah dikembalikan kepada tetapan asal', 'success');
        }

        // Update pagination info
        function updatePaginationInfo() {
            const total = filteredStudents.length;
            const start = Math.min((currentPage - 1) * studentsPerPage + 1, total);
            const end = Math.min(currentPage * studentsPerPage, total);
            
            document.getElementById('paginationInfo').textContent = 
                `Menunjukkan ${start}-${end} daripada ${total} pelajar`;
        }

        // Change page
        function changePage(direction) {
            const totalPages = Math.ceil(filteredStudents.length / studentsPerPage);
            
            if (direction === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (direction === 'next' && currentPage < totalPages) {
                currentPage++;
            }
            
            loadStudentTable();
            updatePaginationInfo();
            
            // Update active page buttons (simplified)
            const pageButtons = document.querySelectorAll('.page-btn');
            pageButtons.forEach((btn, index) => {
                btn.classList.remove('active');
                if (index === currentPage) {
                    btn.classList.add('active');
                }
            });
        }

        // Add new student
        function tambahPelajar() {
            isEditingStudent = false;
            currentStudentId = null;
            document.getElementById('modalTitle').textContent = 'Tambah Pelajar Baru';
            studentForm.reset();
            
            // Set default values
            document.getElementById('studentYear').value = '6';
            document.getElementById('studentClass').value = '6A';
            document.getElementById('studentGender').value = 'male';
            
            studentModal.classList.add('active');
        }

        // Edit student
        function editStudent(studentId) {
            isEditingStudent = true;
            currentStudentId = studentId;
            
            const student = studentsData.find(s => s.id === studentId);
            if (student) {
                document.getElementById('modalTitle').textContent = 'Edit Pelajar';
                document.getElementById('studentName').value = student.name;
                document.getElementById('studentIC').value = student.ic;
                document.getElementById('studentClass').value = student.class;
                document.getElementById('studentYear').value = student.year;
                document.getElementById('studentGender').value = student.gender;
                document.getElementById('studentDOB').value = student.dob;
                document.getElementById('studentPhone').value = student.phone;
                document.getElementById('studentEmail').value = student.email || '';
                document.getElementById('studentAddress').value = student.address;
                document.getElementById('studentGuardian').value = student.guardian || '';
                document.getElementById('studentRelationship').value = student.relationship || '';
                document.getElementById('studentMedical').value = student.medical || '';
                
                studentModal.classList.add('active');
            }
        }

        // Save student
        function saveStudent(event) {
            event.preventDefault();
            
            const studentName = document.getElementById('studentName').value;
            const studentIC = document.getElementById('studentIC').value;
            const studentClass = document.getElementById('studentClass').value;
            const studentYear = document.getElementById('studentYear').value;
            const studentGender = document.getElementById('studentGender').value;
            const studentDOB = document.getElementById('studentDOB').value;
            const studentPhone = document.getElementById('studentPhone').value;
            const studentEmail = document.getElementById('studentEmail').value;
            const studentAddress = document.getElementById('studentAddress').value;
            const studentGuardian = document.getElementById('studentGuardian').value;
            const studentRelationship = document.getElementById('studentRelationship').value;
            const studentMedical = document.getElementById('studentMedical').value;
            
            // Validate required fields
            if (!studentName || !studentIC || !studentClass || !studentYear || !studentGender || !studentDOB || !studentPhone || !studentAddress) {
                showNotification('Sila isi semua maklumat yang diperlukan', 'error');
                return;
            }
            
            if (isEditingStudent && currentStudentId) {
                // Update existing student
                const index = studentsData.findIndex(s => s.id === currentStudentId);
                if (index !== -1) {
                    studentsData[index] = {
                        ...studentsData[index],
                        name: studentName,
                        ic: studentIC,
                        class: studentClass,
                        year: studentYear,
                        gender: studentGender,
                        dob: studentDOB,
                        phone: studentPhone,
                        email: studentEmail,
                        address: studentAddress,
                        guardian: studentGuardian,
                        relationship: studentRelationship,
                        medical: studentMedical
                    };
                    
                    showNotification('Maklumat pelajar berjaya dikemaskini', 'success');
                }
            } else {
                // Add new student
                const newStudent = {
                    id: 'ST' + (studentsData.length + 1).toString().padStart(3, '0'),
                    name: studentName,
                    ic: studentIC,
                    class: studentClass,
                    year: studentYear,
                    gender: studentGender,
                    dob: studentDOB,
                    phone: studentPhone,
                    email: studentEmail,
                    address: studentAddress,
                    guardian: studentGuardian,
                    relationship: studentRelationship,
                    medical: studentMedical,
                    performance: Math.floor(Math.random() * 30) + 60, // Random performance 60-90
                    attendance: Math.floor(Math.random() * 20) + 80, // Random attendance 80-100
                    status: 'active',
                    createdAt: new Date().toISOString().split('T')[0]
                };
                
                studentsData.push(newStudent);
                showNotification('Pelajar baru berjaya ditambah', 'success');
            }
            
            // Update data
            filterStudents();
            updateSummary();
            closeModal();
        }

        // View student details
        function viewStudent(studentId) {
            const student = studentsData.find(s => s.id === studentId);
            if (student) {
                // Calculate age from DOB
                const dob = new Date(student.dob);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                // Format DOB
                const formattedDOB = new Date(student.dob).toLocaleDateString('ms-MY', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                alert(`MAKLUMAT PELAJAR\n\n` +
                      `Nama: ${student.name}\n` +
                      `No. KP: ${student.ic}\n` +
                      `Umur: ${age} tahun\n` +
                      `Tarikh Lahir: ${formattedDOB}\n` +
                      `Kelas: ${student.class}\n` +
                      `Tahun: ${student.year}\n` +
                      `Jantina: ${student.gender === 'male' ? 'Lelaki' : 'Perempuan'}\n` +
                      `Telefon: ${student.phone}\n` +
                      `Emel: ${student.email || 'Tiada'}\n` +
                      `Alamat: ${student.address}\n` +
                      `Penjaga: ${student.guardian || 'Tiada'} (${student.relationship || 'Tiada'})\n` +
                      `Catatan Perubatan: ${student.medical || 'Tiada'}\n` +
                      `Prestasi: ${student.performance}%\n` +
                      `Kehadiran: ${student.attendance}%\n` +
                      `Status: ${student.status === 'active' ? 'Aktif' : student.status === 'inactive' ? 'Tidak Aktif' : 'Tamat'}\n` +
                      `Didaftarkan pada: ${student.createdAt}`);
            }
        }

        // Delete student
        function deleteStudent(studentId) {
            const student = studentsData.find(s => s.id === studentId);
            if (!student) return;
            
            if (confirm(`Adakah anda pasti ingin memadam pelajar ini?\n\n${student.name}\n${student.id}\n${student.class}`)) {
                // Remove student
                const index = studentsData.findIndex(s => s.id === studentId);
                if (index !== -1) {
                    studentsData.splice(index, 1);
                    
                    // Remove from selected students
                    selectedStudents.delete(studentId);
                    
                    // Update data
                    filterStudents();
                    updateSummary();
                    updateBulkActions();
                    
                    showNotification('Pelajar berjaya dipadam', 'success');
                }
            }
        }

        // Toggle student selection
        function toggleStudentSelection(studentId) {
            if (selectedStudents.has(studentId)) {
                selectedStudents.delete(studentId);
            } else {
                selectedStudents.add(studentId);
            }
            updateBulkActions();
        }

        // Toggle all selection
        function toggleAllSelection() {
            const selectAll = document.getElementById('selectAll').checked;
            const checkboxes = document.querySelectorAll('.student-checkbox');
            
            if (selectAll) {
                displayedStudents.forEach(student => {
                    selectedStudents.add(student.id);
                });
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            } else {
                displayedStudents.forEach(student => {
                    selectedStudents.delete(student.id);
                });
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
            updateBulkActions();
        }

        // Toggle all bulk
        function toggleAllBulk() {
            const selectAllBulk = document.getElementById('selectAllBulk').checked;
            const selectAll = document.getElementById('selectAll');
            
            if (selectAllBulk) {
                // Select all students across all pages
                filteredStudents.forEach(student => {
                    selectedStudents.add(student.id);
                });
                selectAll.checked = true;
            } else {
                // Deselect all students
                selectedStudents.clear();
                selectAll.checked = false;
            }
            
            // Update all checkboxes on current page
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllBulk;
            });
            
            updateBulkActions();
        }

        // Update bulk actions
        function updateBulkActions() {
            const selectedCount = selectedStudents.size;
            document.getElementById('selectedCount').textContent = selectedCount;
            
            if (selectedCount > 0) {
                bulkActions.style.display = 'flex';
                document.getElementById('selectAllBulk').checked = selectedCount === filteredStudents.length;
            } else {
                bulkActions.style.display = 'none';
            }
            
            // Update select all checkbox
            const allDisplayedSelected = displayedStudents.every(student => selectedStudents.has(student.id));
            document.getElementById('selectAll').checked = allDisplayedSelected && displayedStudents.length > 0;
        }

        // Assign class to selected students
        function assignClassBulk() {
            if (selectedStudents.size === 0) return;
            
            const newClass = prompt('Masukkan kelas baru untuk pelajar terpilih:');
            if (newClass) {
                studentsData.forEach(student => {
                    if (selectedStudents.has(student.id)) {
                        student.class = newClass;
                    }
                });
                
                selectedStudents.clear();
                filterStudents();
                updateBulkActions();
                showNotification(`${selectedStudents.size} pelajar telah ditugaskan ke kelas ${newClass}`, 'success');
            }
        }

        // Update status of selected students
        function updateStatusBulk() {
            if (selectedStudents.size === 0) return;
            
            const newStatus = prompt('Masukkan status baru (active/inactive/graduated):');
            if (newStatus && ['active', 'inactive', 'graduated'].includes(newStatus)) {
                studentsData.forEach(student => {
                    if (selectedStudents.has(student.id)) {
                        student.status = newStatus;
                    }
                });
                
                selectedStudents.clear();
                filterStudents();
                updateSummary();
                updateBulkActions();
                showNotification(`Status ${selectedStudents.size} pelajar telah dikemaskini`, 'success');
            }
        }

        // Delete selected students
        function deleteStudentsBulk() {
            if (selectedStudents.size === 0) return;
            
            if (confirm(`Adakah anda pasti ingin memadam ${selectedStudents.size} pelajar terpilih?`)) {
                // Remove selected students
                studentsData = studentsData.filter(student => !selectedStudents.has(student.id));
                
                selectedStudents.clear();
                filterStudents();
                updateSummary();
                updateBulkActions();
                showNotification(`${selectedStudents.size} pelajar telah dipadam`, 'success');
            }
        }

        // Open import modal
        function openImportModal() {
            importModal.classList.add('active');
        }

        // Close import modal
        function closeImportModal() {
            importModal.classList.remove('active');
        }

        // Handle file upload
        function handleFileUpload(input) {
            const file = input.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(2);
                showNotification(`Fail "${fileName}" (${fileSize} KB) berjaya dimuat naik`, 'success');
            }
        }

        // Download template
        function downloadTemplate() {
            showNotification('Template Excel sedang dimuat turun...', 'info');
            // In a real app, this would download a template file
        }

        // Process import
        function processImport() {
            showNotification('Memproses import data pelajar...', 'info');
            setTimeout(() => {
                showNotification('Import berjaya! 10 rekod pelajar telah ditambah.', 'success');
                closeImportModal();
            }, 2000);
        }

        // Reload data
        function muatSemulaData() {
            filterStudents();
            showNotification('Data pelajar disegarkan', 'success');
        }

        // Close modal
        function closeModal() {
            studentModal.classList.remove('active');
            isEditingStudent = false;
            currentStudentId = null;
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
                    closeImportModal();
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