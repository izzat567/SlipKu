<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjek Saya - SlipKu</title>
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

        /* Subject Cards Grid */
        .subject-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .subject-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .subject-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
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

        .subject-icon.islamic {
            background: linear-gradient(135deg, #8b5cf6, #a78bfa);
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

        /* Subject Stats */
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

        /* Class List */
        .class-list {
            margin-bottom: 20px;
        }

        .class-list-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .class-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .class-tag {
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
        }

        .class-tag:hover {
            background: var(--primary);
            color: white;
        }

        /* Syllabus Progress */
        .syllabus-progress {
            margin-bottom: 20px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .progress-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .progress-value {
            font-size: 14px;
            font-weight: 700;
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
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        /* Subject Actions */
        .subject-actions {
            display: flex;
            gap: 10px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        /* Subject Table Container */
        .subject-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
            display: none;
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

        /* Subject Cell in Table */
        .subject-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .subject-table-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
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
            max-width: 700px;
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

        .form-input, .form-select, .form-date, .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-date:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
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

        /* Syllabus Items */
        .syllabus-items {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .syllabus-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: var(--transition);
        }

        .syllabus-item:hover {
            background: var(--light-gray);
        }

        .syllabus-item:last-child {
            border-bottom: none;
        }

        .syllabus-item-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .syllabus-item-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .syllabus-item-checkbox.checked {
            background: var(--success);
            border-color: var(--success);
            color: white;
        }

        .syllabus-item-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .syllabus-item-details p {
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
            
            .subject-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .subject-actions {
                flex-direction: column;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
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
            
            .subject-card {
                padding: 20px;
            }
            
            .subject-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Modal for Subject Details -->
    <div class="modal" id="subjectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Maklumat Subjek</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="subject-detail-header" style="text-align: center; margin-bottom: 25px;">
                    <div class="subject-icon math" id="subjectDetailIcon" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 id="subjectNameDetail" style="font-size: 24px; color: var(--dark-gray); margin-bottom: 5px;">Matematik</h3>
                    <p id="subjectCodeDetail" style="color: var(--medium-gray); font-size: 16px;">Kod: MAT601</p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px;">
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Guru</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="subjectTeacherDetail">Cikgu Ahmad</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Kelas</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="subjectClassesDetail">3 kelas</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Pelajar</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="subjectStudentsDetail">85 pelajar</div>
                    </div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                        <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">Prestasi</div>
                        <div style="font-weight: 600; color: var(--dark-gray);" id="subjectPerformanceDetail">78.9%</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Kelas yang Mengikuti</h4>
                    <div class="class-tags" id="subjectClassesList">
                        <!-- Class tags will be loaded here -->
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="progress-header">
                        <div class="progress-title">Kemajuan Sukatan Pelajaran</div>
                        <div class="progress-value" id="syllabusProgressDetail">65%</div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="syllabusProgressBar" style="width: 65%"></div>
                    </div>
                </div>
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 12px; margin-bottom: 20px;">
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">Penerangan Subjek</h4>
                    <p id="subjectDescriptionDetail" style="color: var(--medium-gray); font-size: 13px; line-height: 1.6;">Tiada penerangan</p>
                </div>
                
                <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                    <h4 style="font-size: 14px; margin-bottom: 10px; color: var(--dark-gray);">Buku Teks & Rujukan</h4>
                    <p id="subjectBooksDetail" style="color: var(--medium-gray); font-size: 13px; line-height: 1.6;">Tiada maklumat buku</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add/Edit Subject -->
    <div class="modal" id="editSubjectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="editModalTitle">Tambah Subjek Baru</h3>
                <button class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="subjectForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Nama Subjek</label>
                            <input type="text" class="form-input" id="subjectName" placeholder="Contoh: Matematik, Sains" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Kod Subjek</label>
                            <input type="text" class="form-input" id="subjectCode" placeholder="Contoh: MAT601, SNS601" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jenis Subjek</label>
                            <select class="form-select" id="subjectType" required>
                                <option value="">Pilih Jenis</option>
                                <option value="core">Teras</option>
                                <option value="elective">Elektif</option>
                                <option value="additional">Tambahan</option>
                                <option value="extracurricular">Kokurikulum</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="subjectYear" required>
                                <option value="">Pilih Tahun</option>
                                <option value="all">Semua Tahun</option>
                                <option value="6">Tahun 6</option>
                                <option value="5">Tahun 5</option>
                                <option value="4">Tahun 4</option>
                                <option value="3">Tahun 3</option>
                                <option value="2">Tahun 2</option>
                                <option value="1">Tahun 1</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Kelas yang Mengikuti</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; margin-top: 10px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-6A" value="6A">
                                <label for="class-6A" style="font-size: 13px; cursor: pointer;">Kelas 6A</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-6B" value="6B">
                                <label for="class-6B" style="font-size: 13px; cursor: pointer;">Kelas 6B</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-5A" value="5A">
                                <label for="class-5A" style="font-size: 13px; cursor: pointer;">Kelas 5A</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-5B" value="5B">
                                <label for="class-5B" style="font-size: 13px; cursor: pointer;">Kelas 5B</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-4A" value="4A">
                                <label for="class-4A" style="font-size: 13px; cursor: pointer;">Kelas 4A</label>
                            </div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="class-all" value="all">
                                <label for="class-all" style="font-size: 13px; cursor: pointer;">Semua Kelas</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Penerangan Subjek</label>
                        <textarea class="form-textarea" id="subjectDescription" placeholder="Penerangan ringkas mengenai subjek..." rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Buku Teks Utama</label>
                            <input type="text" class="form-input" id="subjectTextbook" placeholder="Nama buku teks">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Jam Kredit</label>
                            <input type="number" class="form-input" id="subjectCredits" placeholder="Contoh: 4" min="1" max="10">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-textarea" id="subjectNotes" placeholder="Catatan tambahan..." rows="2"></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Subjek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Syllabus Management -->
    <div class="modal" id="syllabusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="syllabusModalTitle">Sukatan Pelajaran</h3>
                <button class="modal-close" onclick="closeSyllabusModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div class="subject-icon math" id="syllabusSubjectIcon" style="width: 60px; height: 60px; margin: 0 auto 10px;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h4 id="syllabusSubjectName" style="font-size: 18px; color: var(--dark-gray); margin-bottom: 5px;">Matematik</h4>
                    <p style="color: var(--medium-gray); font-size: 14px;">Kemajuan: <span id="syllabusProgressText">65%</span></p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div class="progress-header">
                        <div class="progress-title">Kemajuan Keseluruhan</div>
                        <div class="progress-value" id="syllabusOverallProgress">65%</div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="syllabusOverallProgressBar" style="width: 65%"></div>
                    </div>
                </div>
                
                <div style="margin-bottom: 15px;">
                    <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark-gray);">Topik & Kandungan</h4>
                    <div class="syllabus-items" id="syllabusItems">
                        <!-- Syllabus items will be loaded here -->
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button class="btn btn-secondary" onclick="addSyllabusItem()">
                        <i class="fas fa-plus"></i>
                        Tambah Topik
                    </button>
                    <button class="btn btn-primary" onclick="saveSyllabus()">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
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
                    <p>Subjek Saya</p>
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
            <a href="pelajar-saya.html" class="sidebar-item">
                <i class="fas fa-user-graduate"></i>
                Pelajar Saya
                <span class="badge">85</span>
            </a>
            <a href="subjek-saya.html" class="sidebar-item active">
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
                <h2>Subjek Saya 📚</h2>
                <p>Urus dan pantau semua subjek yang anda ajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahSubjek()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Subjek
                </button>
            </div>
        </div>

        <!-- Tabs for View Options -->
        <div class="tabs">
            <button class="tab-btn active" onclick="changeView('cards')">
                <i class="fas fa-th-large"></i>
                Paparan Kad
            </button>
            <button class="tab-btn" onclick="changeView('table')">
                <i class="fas fa-table"></i>
                Paparan Jadual
            </button>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-filter-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari subjek mengikut nama, kod, atau jenis..." onkeyup="searchSubjects()">
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Jenis:</label>
                    <select class="filter-select" id="filterType" onchange="filterSubjects()">
                        <option value="">Semua Jenis</option>
                        <option value="core">Teras</option>
                        <option value="elective">Elektif</option>
                        <option value="additional">Tambahan</option>
                        <option value="extracurricular">Kokurikulum</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tahun:</label>
                    <select class="filter-select" id="filterYear" onchange="filterSubjects()">
                        <option value="">Semua Tahun</option>
                        <option value="6">Tahun 6</option>
                        <option value="5">Tahun 5</option>
                        <option value="4">Tahun 4</option>
                        <option value="3">Tahun 3</option>
                        <option value="2">Tahun 2</option>
                        <option value="1">Tahun 1</option>
                        <option value="all">Semua Tahun</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="filterSubjects()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Susunan:</label>
                    <select class="filter-select" id="filterSort" onchange="filterSubjects()">
                        <option value="name">Nama (A-Z)</option>
                        <option value="performance">Prestasi (Tertinggi)</option>
                        <option value="students">Bilangan Pelajar</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Subject Cards View -->
        <div class="subject-cards" id="cardsView">
            <!-- Subject cards will be loaded here -->
        </div>

        <!-- Subject Table View -->
        <div class="subject-table-container" id="tableView">
            <table id="subjectTable">
                <thead>
                    <tr>
                        <th>SUBJEK</th>
                        <th>KOD</th>
                        <th>JENIS</th>
                        <th>TAHUN</th>
                        <th>KELAS</th>
                        <th>PELAJAR</th>
                        <th>PRESTASI</th>
                        <th>SUKATAN</th>
                        <th>STATUS</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="subjectTableBody">
                    <!-- Subject table rows will be loaded here -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-book-open"></i>
                <h3>Tiada Subjek Ditemui</h3>
                <p>Tiada subjek yang sepadan dengan carian atau penapis anda.</p>
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
        const subjectModal = document.getElementById('subjectModal');
        const editSubjectModal = document.getElementById('editSubjectModal');
        const syllabusModal = document.getElementById('syllabusModal');
        const cardsView = document.getElementById('cardsView');
        const tableView = document.getElementById('tableView');
        const subjectTableBody = document.getElementById('subjectTableBody');
        const emptyState = document.getElementById('emptyState');
        const subjectForm = document.getElementById('subjectForm');

        // Current state
        let subjectsData = [];
        let filteredSubjects = [];
        let currentView = 'cards';
        let isEditingSubject = false;
        let currentSubjectId = null;
        let currentSyllabusSubjectId = null;

        // Subject icons mapping
        const subjectIcons = {
            'Matematik': { icon: 'fas fa-calculator', class: 'math' },
            'Sains': { icon: 'fas fa-flask', class: 'science' },
            'Bahasa Melayu': { icon: 'fas fa-book-open', class: 'bm' },
            'Bahasa Inggeris': { icon: 'fas fa-language', class: 'bi' },
            'PJ & Kesihatan': { icon: 'fas fa-running', class: 'pj' },
            'Pendidikan Islam': { icon: 'fas fa-mosque', class: 'islamic' },
            'Sejarah': { icon: 'fas fa-landmark', class: 'history' },
            'Geografi': { icon: 'fas fa-globe-asia', class: 'geography' },
            'Muzik': { icon: 'fas fa-music', class: 'music' },
            'Seni': { icon: 'fas fa-palette', class: 'art' }
        };

        // Sample data for subjects
        const sampleSubjects = [
            {
                id: 'SUB001',
                name: 'Matematik',
                code: 'MAT601',
                type: 'core',
                year: '6',
                teacher: 'Cikgu Ahmad',
                classes: ['6A', '6B', '5A'],
                totalStudents: 85,
                averagePerformance: 78.9,
                attendanceRate: 92.3,
                syllabusProgress: 65,
                description: 'Matematik Tahun 6 merangkumi topik algebra, geometri, pecahan dan perpuluhan. Fokus kepada penyelesaian masalah.',
                books: 'Matematik Tahun 6 (KPM), Latihan Topikal Matematik',
                notes: 'Perlu fokus pada topik algebra yang masih lemah',
                status: 'active',
                syllabus: [
                    { id: 'SYL001', topic: 'Nombor Bulat dan Operasi', completed: true },
                    { id: 'SYL002', topic: 'Pecahan', completed: true },
                    { id: 'SYL003', topic: 'Perpuluhan', completed: true },
                    { id: 'SYL004', topic: 'Peratus', completed: true },
                    { id: 'SYL005', topic: 'Wang', completed: true },
                    { id: 'SYL006', topic: 'Masa dan Waktu', completed: true },
                    { id: 'SYL007', topic: 'Ukuran Panjang', completed: false },
                    { id: 'SYL008', topic: 'Ukuran Berat', completed: false },
                    { id: 'SYL009', topic: 'Isipadu Cecair', completed: false },
                    { id: 'SYL010', topic: 'Ruangan', completed: false },
                    { id: 'SYL011', topic: 'Geometri', completed: false },
                    { id: 'SYL012', topic: 'Koordinat', completed: false },
                    { id: 'SYL013', topic: 'Perwakilan Data', completed: false },
                    { id: 'SYL014', topic: 'Kebarangkalian', completed: false }
                ],
                createdAt: '2023-01-15'
            },
            {
                id: 'SUB002',
                name: 'Sains',
                code: 'SNS601',
                type: 'core',
                year: '6',
                teacher: 'Cikgu Ahmad',
                classes: ['6A', '6B'],
                totalStudents: 58,
                averagePerformance: 75.2,
                attendanceRate: 90.5,
                syllabusProgress: 45,
                description: 'Sains Tahun 6 meliputi biologi, fizik dan kimia asas dengan penekanan kepada eksperimen dan pemerhatian.',
                books: 'Sains Tahun 6 (KPM), Buku Aktiviti Sains',
                notes: 'Perlu lebih banyak sesi makmal untuk topik biologi',
                status: 'active',
                syllabus: [
                    { id: 'SYL101', topic: 'Penyelidikan Sains', completed: true },
                    { id: 'SYL102', topic: 'Manusia', completed: true },
                    { id: 'SYL103', topic: 'Hidupan', completed: true },
                    { id: 'SYL104', topic: 'Pertumbuhan', completed: true },
                    { id: 'SYL105', topic: 'Interaksi Antara Hidupan', completed: true },
                    { id: 'SYL106', topic: 'Pemuliharaan', completed: false },
                    { id: 'SYL107', topic: 'Kekuatan dan Kestabilan', completed: false },
                    { id: 'SYL108', topic: 'Gerakan', completed: false },
                    { id: 'SYL109', topic: 'Kuasa', completed: false },
                    { id: 'SYL110', topic: 'Tenaga', completed: false },
                    { id: 'SYL111', topic: 'Pembolehubah', completed: false },
                    { id: 'SYL112', topic: 'Sistem Suria', completed: false }
                ],
                createdAt: '2023-01-15'
            },
            {
                id: 'SUB003',
                name: 'Bahasa Melayu',
                code: 'BML601',
                type: 'core',
                year: '6',
                teacher: 'Cikgu Siti',
                classes: ['6A', '6B', '5A'],
                totalStudents: 85,
                averagePerformance: 80.5,
                attendanceRate: 93.1,
                syllabusProgress: 70,
                description: 'Bahasa Melayu Tahun 6 fokus kepada kemahiran membaca, menulis, bertutur dan mendengar dengan penekanan pada tatabahasa.',
                books: 'Bahasa Melayu Tahun 6 (KPM), Buku Latihan Karangan',
                notes: 'Perlu bimbingan khas untuk karangan naratif',
                status: 'active',
                syllabus: [
                    { id: 'SYL201', topic: 'Kemahiran Mendengar', completed: true },
                    { id: 'SYL202', topic: 'Kemahiran Bertutur', completed: true },
                    { id: 'SYL203', topic: 'Kemahiran Membaca', completed: true },
                    { id: 'SYL204', topic: 'Kemahiran Menulis', completed: true },
                    { id: 'SYL205', topic: 'Tatabahasa', completed: true },
                    { id: 'SYL206', topic: 'Kosa Kata', completed: true },
                    { id: 'SYL207', topic: 'Karangan Naratif', completed: false },
                    { id: 'SYL208', topic: 'Karangan Deskriptif', completed: false },
                    { id: 'SYL209', topic: 'Karangan Ekspositori', completed: false },
                    { id: 'SYL210', topic: 'Karangan Argumentatif', completed: false },
                    { id: 'SYL211', topic: 'Sastera Kanak-kanak', completed: false },
                    { id: 'SYL212', topic: 'Puisi Tradisional', completed: false }
                ],
                createdAt: '2023-01-15'
            },
            {
                id: 'SUB004',
                name: 'Bahasa Inggeris',
                code: 'ENG601',
                type: 'core',
                year: '6',
                teacher: 'Cikgu Ali',
                classes: ['6A', '6B'],
                totalStudents: 58,
                averagePerformance: 72.8,
                attendanceRate: 89.7,
                syllabusProgress: 55,
                description: 'English Year 6 focuses on reading, writing, speaking and listening skills with emphasis on grammar and vocabulary.',
                books: 'English Year 6 (KPM), English Workbook',
                notes: 'Need more speaking practice sessions',
                status: 'active',
                syllabus: [
                    { id: 'SYL301', topic: 'Listening Skills', completed: true },
                    { id: 'SYL302', topic: 'Speaking Skills', completed: true },
                    { id: 'SYL303', topic: 'Reading Skills', completed: true },
                    { id: 'SYL304', topic: 'Writing Skills', completed: true },
                    { id: 'SYL305', topic: 'Grammar', completed: true },
                    { id: 'SYL306', topic: 'Vocabulary', completed: false },
                    { id: 'SYL307', topic: 'Comprehension', completed: false },
                    { id: 'SYL308', topic: 'Composition', completed: false },
                    { id: 'SYL309', topic: 'Poetry', completed: false },
                    { id: 'SYL310', topic: 'Drama', completed: false },
                    { id: 'SYL311', topic: 'Presentation Skills', completed: false },
                    { id: 'SYL312', topic: 'Project Work', completed: false }
                ],
                createdAt: '2023-01-20'
            },
            {
                id: 'SUB005',
                name: 'PJ & Kesihatan',
                code: 'PJK601',
                type: 'core',
                year: '6',
                teacher: 'Cikgu Ahmad',
                classes: ['6A', '6B', '5A'],
                totalStudents: 85,
                averagePerformance: 88.5,
                attendanceRate: 95.2,
                syllabusProgress: 80,
                description: 'Pendidikan Jasmani dan Kesihatan Tahun 6 meliputi aktiviti fizikal, sukan, dan pendidikan kesihatan.',
                books: 'PJ Tahun 6 (KPM), Buku Panduan Kesihatan',
                notes: 'Aktiviti luar perlu dijadualkan lebih kerap',
                status: 'active',
                syllabus: [
                    { id: 'SYL401', topic: 'Kecergasan Fizikal', completed: true },
                    { id: 'SYL402', topic: 'Kemahiran Motor', completed: true },
                    { id: 'SYL403', topic: 'Permainan Bola', completed: true },
                    { id: 'SYL404', topic: 'Olahraga', completed: true },
                    { id: 'SYL405', topic: 'Gimnastik', completed: true },
                    { id: 'SYL406', topic: 'Renang', completed: true },
                    { id: 'SYL407', topic: 'Pendidikan Kesihatan', completed: false },
                    { id: 'SYL408', topic: 'Pemakanan', completed: false },
                    { id: 'SYL409', topic: 'Keselamatan', completed: false },
                    { id: 'SYL410', topic: 'Pertolongan Cemas', completed: false }
                ],
                createdAt: '2023-01-25'
            }
        ];

        // Initialize page
        function initializePage() {
            subjectsData = [...sampleSubjects];
            filteredSubjects = [...subjectsData];
            
            // Set up form submit handler
            subjectForm.addEventListener('submit', saveSubject);
            
            // Load initial data
            loadSubjectCards();
            loadSubjectTable();
            
            // Set up filter listeners
            document.getElementById('filterType').addEventListener('change', filterSubjects);
            document.getElementById('filterYear').addEventListener('change', filterSubjects);
            document.getElementById('filterStatus').addEventListener('change', filterSubjects);
            document.getElementById('filterSort').addEventListener('change', filterSubjects);
        }

        // Load subject cards
        function loadSubjectCards() {
            if (filteredSubjects.length === 0) {
                cardsView.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }
            
            emptyState.style.display = 'none';
            
            cardsView.innerHTML = filteredSubjects.map(subject => {
                // Get subject icon
                const iconInfo = subjectIcons[subject.name] || { icon: 'fas fa-book', class: 'default' };
                
                // Determine type text
                const typeText = {
                    'core': 'Teras',
                    'elective': 'Elektif',
                    'additional': 'Tambahan',
                    'extracurricular': 'Kokurikulum'
                }[subject.type] || subject.type;
                
                // Determine type color
                const typeColor = {
                    'core': 'var(--primary)',
                    'elective': 'var(--info)',
                    'additional': 'var(--warning)',
                    'extracurricular': 'var(--success)'
                }[subject.type] || 'var(--medium-gray)';
                
                return `
                    <div class="subject-card">
                        <div class="subject-card-header">
                            <div class="subject-icon ${iconInfo.class}">
                                <i class="${iconInfo.icon}"></i>
                            </div>
                            <div class="subject-info">
                                <h3>${subject.name}</h3>
                                <p>${subject.code} • <span style="color: ${typeColor}; font-weight: 600;">${typeText}</span> • Tahun ${subject.year}</p>
                            </div>
                        </div>
                        
                        <div class="subject-stats">
                            <div class="subject-stat">
                                <div class="stat-value">${subject.classes.length}</div>
                                <div class="stat-label">Kelas</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.totalStudents}</div>
                                <div class="stat-label">Pelajar</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.averagePerformance}%</div>
                                <div class="stat-label">Prestasi</div>
                            </div>
                            <div class="subject-stat">
                                <div class="stat-value">${subject.attendanceRate}%</div>
                                <div class="stat-label">Kehadiran</div>
                            </div>
                        </div>
                        
                        <div class="class-list">
                            <div class="class-list-title">Kelas yang Mengikuti:</div>
                            <div class="class-tags">
                                ${subject.classes.map(cls => `
                                    <span class="class-tag">${cls}</span>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div class="syllabus-progress">
                            <div class="progress-header">
                                <div class="progress-title">Kemajuan Sukatan Pelajaran</div>
                                <div class="progress-value">${subject.syllabusProgress}%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: ${subject.syllabusProgress}%"></div>
                            </div>
                        </div>
                        
                        <div class="subject-actions">
                            <button class="action-btn view" onclick="viewSubject('${subject.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                            <button class="action-btn manage" onclick="manageSyllabus('${subject.id}')">
                                <i class="fas fa-tasks"></i>
                                Sukatan
                            </button>
                            <button class="action-btn edit" onclick="editSubject('${subject.id}')">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Load subject table
        function loadSubjectTable() {
            if (filteredSubjects.length === 0) {
                subjectTableBody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 40px; color: var(--medium-gray);">
                            <i class="fas fa-book-open" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                            Tiada subjek ditemui
                        </td>
                    </tr>
                `;
                return;
            }
            
            subjectTableBody.innerHTML = filteredSubjects.map(subject => {
                // Get subject icon
                const iconInfo = subjectIcons[subject.name] || { icon: 'fas fa-book', class: 'default' };
                
                // Determine type text
                const typeText = {
                    'core': 'Teras',
                    'elective': 'Elektif',
                    'additional': 'Tambahan',
                    'extracurricular': 'Kokurikulum'
                }[subject.type] || subject.type;
                
                // Determine status badge
                const statusClass = subject.status === 'active' ? 'status-active' : 'status-inactive';
                const statusText = subject.status === 'active' ? 'AKTIF' : 'TIDAK AKTIF';
                
                return `
                    <tr>
                        <td>
                            <div class="subject-cell">
                                <div class="subject-table-icon ${iconInfo.class}">
                                    <i class="${iconInfo.icon}"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 700; color: var(--dark-gray);">${subject.name}</div>
                                    <div style="font-size: 13px; color: var(--medium-gray);">${subject.teacher}</div>
                                </div>
                            </div>
                        </td>
                        <td>${subject.code}</td>
                        <td>${typeText}</td>
                        <td>Tahun ${subject.year}</td>
                        <td>${subject.classes.length} kelas</td>
                        <td>${subject.totalStudents}</td>
                        <td>${subject.averagePerformance}%</td>
                        <td>${subject.syllabusProgress}%</td>
                        <td>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="action-btn view" onclick="viewSubject('${subject.id}')" style="padding: 6px 12px;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn manage" onclick="manageSyllabus('${subject.id}')" style="padding: 6px 12px;">
                                    <i class="fas fa-tasks"></i>
                                </button>
                                <button class="action-btn edit" onclick="editSubject('${subject.id}')" style="padding: 6px 12px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Search subjects
        function searchSubjects() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            filteredSubjects = subjectsData.filter(subject => {
                return subject.name.toLowerCase().includes(searchTerm) ||
                       subject.code.toLowerCase().includes(searchTerm) ||
                       subject.type.toLowerCase().includes(searchTerm) ||
                       subject.teacher.toLowerCase().includes(searchTerm);
            });
            
            if (currentView === 'cards') {
                loadSubjectCards();
            } else {
                loadSubjectTable();
            }
        }

        // Filter subjects
        function filterSubjects() {
            const typeFilter = document.getElementById('filterType').value;
            const yearFilter = document.getElementById('filterYear').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const sortFilter = document.getElementById('filterSort').value;
            
            filteredSubjects = subjectsData.filter(subject => {
                // Apply type filter
                if (typeFilter && subject.type !== typeFilter) return false;
                
                // Apply year filter
                if (yearFilter && subject.year !== yearFilter && subject.year !== 'all') {
                    if (yearFilter !== 'all') return false;
                }
                
                // Apply status filter
                if (statusFilter && subject.status !== statusFilter) return false;
                
                return true;
            });
            
            // Apply sorting
            if (sortFilter === 'name') {
                filteredSubjects.sort((a, b) => a.name.localeCompare(b.name));
            } else if (sortFilter === 'performance') {
                filteredSubjects.sort((a, b) => b.averagePerformance - a.averagePerformance);
            } else if (sortFilter === 'students') {
                filteredSubjects.sort((a, b) => b.totalStudents - a.totalStudents);
            }
            
            if (currentView === 'cards') {
                loadSubjectCards();
            } else {
                loadSubjectTable();
            }
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterYear').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterSort').value = 'name';
            
            filteredSubjects = [...subjectsData];
            
            if (currentView === 'cards') {
                loadSubjectCards();
            } else {
                loadSubjectTable();
            }
            
            showNotification('Semua penapis telah dikembalikan kepada tetapan asal', 'success');
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
            if (view === 'cards') {
                cardsView.style.display = 'grid';
                tableView.style.display = 'none';
                loadSubjectCards();
            } else if (view === 'table') {
                cardsView.style.display = 'none';
                tableView.style.display = 'block';
                loadSubjectTable();
            }
        }

        // View subject details
        function viewSubject(subjectId) {
            const subject = subjectsData.find(s => s.id === subjectId);
            if (subject) {
                // Update modal content
                const iconInfo = subjectIcons[subject.name] || { icon: 'fas fa-book', class: 'default' };
                
                document.getElementById('subjectDetailIcon').className = `subject-icon ${iconInfo.class}`;
                document.getElementById('subjectDetailIcon').innerHTML = `<i class="${iconInfo.icon}"></i>`;
                document.getElementById('subjectNameDetail').textContent = subject.name;
                document.getElementById('subjectCodeDetail').textContent = `Kod: ${subject.code}`;
                document.getElementById('subjectTeacherDetail').textContent = subject.teacher;
                document.getElementById('subjectClassesDetail').textContent = `${subject.classes.length} kelas`;
                document.getElementById('subjectStudentsDetail').textContent = `${subject.totalStudents} pelajar`;
                document.getElementById('subjectPerformanceDetail').textContent = `${subject.averagePerformance}%`;
                document.getElementById('subjectDescriptionDetail').textContent = subject.description || 'Tiada penerangan';
                document.getElementById('subjectBooksDetail').textContent = subject.books || 'Tiada maklumat buku';
                document.getElementById('syllabusProgressDetail').textContent = `${subject.syllabusProgress}%`;
                document.getElementById('syllabusProgressBar').style.width = `${subject.syllabusProgress}%`;
                
                // Load class tags
                const subjectClassesList = document.getElementById('subjectClassesList');
                subjectClassesList.innerHTML = subject.classes.map(cls => `
                    <span class="class-tag">${cls}</span>
                `).join('');
                
                subjectModal.classList.add('active');
            }
        }

        // Add new subject
        function tambahSubjek() {
            isEditingSubject = false;
            currentSubjectId = null;
            document.getElementById('editModalTitle').textContent = 'Tambah Subjek Baru';
            subjectForm.reset();
            
            // Set default values
            document.getElementById('subjectYear').value = '6';
            document.getElementById('subjectType').value = 'core';
            
            // Uncheck all class checkboxes
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
            
            editSubjectModal.classList.add('active');
        }

        // Edit subject
        function editSubject(subjectId) {
            isEditingSubject = true;
            currentSubjectId = subjectId;
            
            const subject = subjectsData.find(s => s.id === subjectId);
            if (subject) {
                document.getElementById('editModalTitle').textContent = 'Edit Subjek';
                document.getElementById('subjectName').value = subject.name;
                document.getElementById('subjectCode').value = subject.code;
                document.getElementById('subjectType').value = subject.type;
                document.getElementById('subjectYear').value = subject.year;
                document.getElementById('subjectDescription').value = subject.description || '';
                document.getElementById('subjectTextbook').value = subject.books ? subject.books.split(',')[0] : '';
                document.getElementById('subjectNotes').value = subject.notes || '';
                
                // Check class checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    cb.checked = subject.classes.includes(cb.value) || 
                                (cb.value === 'all' && subject.classes.length > 3);
                });
                
                editSubjectModal.classList.add('active');
            }
        }

        // Save subject
        function saveSubject(event) {
            event.preventDefault();
            
            const subjectName = document.getElementById('subjectName').value;
            const subjectCode = document.getElementById('subjectCode').value;
            const subjectType = document.getElementById('subjectType').value;
            const subjectYear = document.getElementById('subjectYear').value;
            const subjectDescription = document.getElementById('subjectDescription').value;
            const subjectTextbook = document.getElementById('subjectTextbook').value;
            const subjectCredits = document.getElementById('subjectCredits').value;
            const subjectNotes = document.getElementById('subjectNotes').value;
            
            // Validate required fields
            if (!subjectName || !subjectCode || !subjectType || !subjectYear) {
                showNotification('Sila isi semua maklumat yang diperlukan', 'error');
                return;
            }
            
            // Get selected classes
            const selectedClasses = [];
            document.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                if (cb.value !== 'all') {
                    selectedClasses.push(cb.value);
                }
            });
            
            // If "all" is selected, add all classes
            const allChecked = document.querySelector('input[type="checkbox"][value="all"]:checked');
            if (allChecked) {
                selectedClasses.push('6A', '6B', '5A', '5B', '4A');
            }
            
            // Remove duplicates
            const uniqueClasses = [...new Set(selectedClasses)];
            
            if (isEditingSubject && currentSubjectId) {
                // Update existing subject
                const index = subjectsData.findIndex(s => s.id === currentSubjectId);
                if (index !== -1) {
                    subjectsData[index] = {
                        ...subjectsData[index],
                        name: subjectName,
                        code: subjectCode,
                        type: subjectType,
                        year: subjectYear,
                        classes: uniqueClasses,
                        description: subjectDescription,
                        books: subjectTextbook,
                        notes: subjectNotes
                    };
                    
                    showNotification('Maklumat subjek berjaya dikemaskini', 'success');
                }
            } else {
                // Add new subject
                const newSubject = {
                    id: 'SUB' + (subjectsData.length + 1).toString().padStart(3, '0'),
                    name: subjectName,
                    code: subjectCode,
                    type: subjectType,
                    year: subjectYear,
                    teacher: 'Cikgu Ahmad',
                    classes: uniqueClasses,
                    totalStudents: uniqueClasses.length * 28, // Estimate
                    averagePerformance: Math.floor(Math.random() * 30) + 60,
                    attendanceRate: Math.floor(Math.random() * 20) + 75,
                    syllabusProgress: 0,
                    description: subjectDescription,
                    books: subjectTextbook,
                    notes: subjectNotes,
                    status: 'active',
                    syllabus: [],
                    createdAt: new Date().toISOString().split('T')[0]
                };
                
                subjectsData.push(newSubject);
                showNotification('Subjek baru berjaya ditambah', 'success');
            }
            
            // Update data
            filterSubjects();
            closeEditModal();
        }

        // Manage syllabus
        function manageSyllabus(subjectId) {
            currentSyllabusSubjectId = subjectId;
            const subject = subjectsData.find(s => s.id === subjectId);
            
            if (subject) {
                const iconInfo = subjectIcons[subject.name] || { icon: 'fas fa-book', class: 'default' };
                
                document.getElementById('syllabusSubjectIcon').className = `subject-icon ${iconInfo.class}`;
                document.getElementById('syllabusSubjectIcon').innerHTML = `<i class="${iconInfo.icon}"></i>`;
                document.getElementById('syllabusSubjectName').textContent = subject.name;
                document.getElementById('syllabusProgressText').textContent = `${subject.syllabusProgress}%`;
                document.getElementById('syllabusOverallProgress').textContent = `${subject.syllabusProgress}%`;
                document.getElementById('syllabusOverallProgressBar').style.width = `${subject.syllabusProgress}%`;
                
                // Load syllabus items
                const syllabusItems = document.getElementById('syllabusItems');
                syllabusItems.innerHTML = subject.syllabus.map(item => `
                    <div class="syllabus-item">
                        <div class="syllabus-item-info">
                            <div class="syllabus-item-checkbox ${item.completed ? 'checked' : ''}" onclick="toggleSyllabusItem('${item.id}')">
                                ${item.completed ? '<i class="fas fa-check"></i>' : ''}
                            </div>
                            <div class="syllabus-item-details">
                                <h4>${item.topic}</h4>
                                <p>${item.completed ? 'Selesai' : 'Belum Selesai'}</p>
                            </div>
                        </div>
                        <button class="action-btn delete" onclick="removeSyllabusItem('${item.id}')" style="padding: 6px 12px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `).join('');
                
                syllabusModal.classList.add('active');
            }
        }

        // Toggle syllabus item
        function toggleSyllabusItem(itemId) {
            const subject = subjectsData.find(s => s.id === currentSyllabusSubjectId);
            if (subject) {
                const item = subject.syllabus.find(i => i.id === itemId);
                if (item) {
                    item.completed = !item.completed;
                    
                    // Update progress
                    const completedItems = subject.syllabus.filter(i => i.completed).length;
                    subject.syllabusProgress = Math.round((completedItems / subject.syllabus.length) * 100);
                    
                    // Update UI
                    manageSyllabus(currentSyllabusSubjectId);
                }
            }
        }

        // Remove syllabus item
        function removeSyllabusItem(itemId) {
            const subject = subjectsData.find(s => s.id === currentSyllabusSubjectId);
            if (subject && confirm('Adakah anda pasti ingin memadam topik ini?')) {
                subject.syllabus = subject.syllabus.filter(i => i.id !== itemId);
                
                // Update progress
                const completedItems = subject.syllabus.filter(i => i.completed).length;
                subject.syllabusProgress = subject.syllabus.length > 0 ? 
                    Math.round((completedItems / subject.syllabus.length) * 100) : 0;
                
                // Update UI
                manageSyllabus(currentSyllabusSubjectId);
                showNotification('Topik sukatan pelajaran telah dipadam', 'success');
            }
        }

        // Add syllabus item
        function addSyllabusItem() {
            const topic = prompt('Masukkan nama topik baru:');
            if (topic && topic.trim() !== '') {
                const subject = subjectsData.find(s => s.id === currentSyllabusSubjectId);
                if (subject) {
                    const newItem = {
                        id: 'SYL' + (subject.syllabus.length + 1).toString().padStart(3, '0'),
                        topic: topic.trim(),
                        completed: false
                    };
                    
                    subject.syllabus.push(newItem);
                    
                    // Update progress
                    const completedItems = subject.syllabus.filter(i => i.completed).length;
                    subject.syllabusProgress = Math.round((completedItems / subject.syllabus.length) * 100);
                    
                    // Update UI
                    manageSyllabus(currentSyllabusSubjectId);
                    showNotification('Topik baru telah ditambah', 'success');
                }
            }
        }

        // Save syllabus
        function saveSyllabus() {
            showNotification('Sukatan pelajaran telah disimpan', 'success');
            closeSyllabusModal();
        }

        // Reload data
        function muatSemulaData() {
            filterSubjects();
            showNotification('Data subjek disegarkan', 'success');
        }

        // Close modal
        function closeModal() {
            subjectModal.classList.remove('active');
        }

        // Close edit modal
        function closeEditModal() {
            editSubjectModal.classList.remove('active');
            isEditingSubject = false;
            currentSubjectId = null;
        }

        // Close syllabus modal
        function closeSyllabusModal() {
            syllabusModal.classList.remove('active');
            currentSyllabusSubjectId = null;
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
                    closeSyllabusModal();
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