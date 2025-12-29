<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Rekod - SlipKu</title>
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

        .filter-select, .filter-input {
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .filter-select:focus, .filter-input:focus {
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

        /* Advanced Search */
        .advanced-search {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: none;
        }

        .advanced-search.active {
            display: block;
        }

        .advanced-search-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .advanced-search-header h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        /* View Options */
        .view-options {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .view-btn {
            padding: 8px 16px;
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .view-btn:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .view-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* Records Summary */
        .records-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .summary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .summary-card.primary::before {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .summary-card.success::before {
            background: linear-gradient(90deg, var(--success), #0da271);
        }

        .summary-card.warning::before {
            background: linear-gradient(90deg, var(--warning), #d97706);
        }

        .summary-card.info::before {
            background: linear-gradient(90deg, var(--info), #2563eb);
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .summary-header h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .summary-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .primary .summary-icon {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .success .summary-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .warning .summary-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .info .summary-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .summary-subtitle {
            font-size: 13px;
            color: var(--medium-gray);
        }

        /* Records Table */
        .records-table-container {
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
            white-space: nowrap;
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

        /* Student Avatar */
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

        /* Status Badge */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-complete {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-incomplete {
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

        .btn-icon.view {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .btn-icon.view:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .btn-icon.edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .btn-icon.edit:hover {
            background: rgba(245, 158, 11, 0.2);
        }

        .btn-icon.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .btn-icon.delete:hover {
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

        /* Records Grid View */
        .records-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
            display: none;
        }

        .records-grid.active {
            display: grid;
        }

        .record-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .record-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-light);
        }

        .record-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .record-student {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .record-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .record-info p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        .record-grade {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        .record-details {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .detail-label {
            color: var(--medium-gray);
        }

        .detail-value {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .record-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
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

        /* Export Options */
        .export-options {
            position: relative;
        }

        .export-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border-radius: 12px;
            padding: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            min-width: 180px;
            display: none;
            z-index: 100;
        }

        .export-dropdown.active {
            display: block;
        }

        .export-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .export-option:hover {
            background: var(--light-gray);
        }

        .export-option i {
            width: 20px;
            text-align: center;
        }

        /* Bulk Actions */
        .bulk-actions {
            display: none;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--primary-light);
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .bulk-actions.active {
            display: flex;
        }

        .selected-count {
            font-weight: 600;
            color: var(--primary);
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
            
            .records-table-container {
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
            
            .records-grid {
                grid-template-columns: 1fr;
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
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn-icon {
                width: 32px;
                height: 32px;
            }
            
            .records-summary {
                grid-template-columns: 1fr;
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
            <a href="kemaskini-markah.html" class="sidebar-item">
                <i class="fas fa-edit"></i>
                Kemaskini Markah
            </a>
            <a href="hasilkan-laporan.html" class="sidebar-item">
                <i class="fas fa-file-export"></i>
                Hasilkan Laporan
            </a>
            <a href="#" class="sidebar-item active">
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
                <h2>Semua Rekod 📋</h2>
                <p>Senarai lengkap semua rekod markah peperiksaan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-success" onclick="tambahRekodBaru()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Rekod
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterYear" onchange="filterRecords()">
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
                    <select class="filter-select" id="filterClass" onchange="filterRecords()">
                        <option value="">Semua Kelas</option>
                        <option value="A" selected>Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jenis Peperiksaan</label>
                    <select class="filter-select" id="filterExam" onchange="filterRecords()">
                        <option value="">Semua Peperiksaan</option>
                        <option value="ujian1">Ujian 1</option>
                        <option value="ujian2">Ujian 2</option>
                        <option value="pertengahan">Peperiksaan Pertengahan</option>
                        <option value="akhir" selected>Peperiksaan Akhir</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Mata Pelajaran</label>
                    <select class="filter-select" id="filterSubject" onchange="filterRecords()">
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
                <input type="text" class="search-input" id="searchInput" placeholder="Cari rekod mengikut nama pelajar, ID, atau kelas..." onkeyup="searchRecords()">
            </div>
            <button class="btn btn-secondary" onclick="resetSearch()">
                <i class="fas fa-times"></i>
                Reset Carian
            </button>
            <button class="btn btn-primary" onclick="toggleAdvancedSearch()">
                <i class="fas fa-sliders-h"></i>
                Carian Lanjutan
            </button>
        </div>

        <!-- Advanced Search -->
        <div class="advanced-search" id="advancedSearch">
            <div class="advanced-search-header">
                <h3>Carian Lanjutan</h3>
                <button class="btn btn-secondary" onclick="toggleAdvancedSearch()">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Semester</label>
                    <select class="filter-select" id="filterSemester" onchange="filterRecords()">
                        <option value="">Semua Semester</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status Rekod</label>
                    <select class="filter-select" id="filterStatus" onchange="filterRecords()">
                        <option value="">Semua Status</option>
                        <option value="complete">Lengkap</option>
                        <option value="pending">Dalam Proses</option>
                        <option value="incomplete">Tidak Lengkap</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Julat Markah</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" class="filter-input" id="filterMinMark" placeholder="Min" min="0" max="100" style="flex: 1;" onchange="filterRecords()">
                        <span style="display: flex; align-items: center; color: var(--medium-gray);">hingga</span>
                        <input type="number" class="filter-input" id="filterMaxMark" placeholder="Max" min="0" max="100" style="flex: 1;" onchange="filterRecords()">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tarikh</label>
                    <input type="date" class="filter-input" id="filterDate" onchange="filterRecords()">
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <div class="selected-count" id="selectedCount">0 rekod dipilih</div>
            <button class="btn btn-secondary" onclick="bulkDeselectAll()">
                <i class="fas fa-times"></i>
                Nyahpilih Semua
            </button>
            <button class="btn btn-info" onclick="bulkExport()">
                <i class="fas fa-file-export"></i>
                Eksport Terpilih
            </button>
            <button class="btn btn-danger" onclick="bulkDelete()">
                <i class="fas fa-trash"></i>
                Padam Terpilih
            </button>
        </div>

        <!-- Records Summary -->
        <div class="records-summary">
            <div class="summary-card primary">
                <div class="summary-header">
                    <h4>Jumlah Rekod</h4>
                    <div class="summary-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="summary-value" id="totalRecords">1,245</div>
                <div class="summary-subtitle">Semua rekod markah</div>
            </div>
            
            <div class="summary-card success">
                <div class="summary-header">
                    <h4>Rekod Lengkap</h4>
                    <div class="summary-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="summary-value" id="completeRecords">1,120</div>
                <div class="summary-subtitle">90% dari semua rekod</div>
            </div>
            
            <div class="summary-card warning">
                <div class="summary-header">
                    <h4>Dalam Proses</h4>
                    <div class="summary-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="summary-value" id="pendingRecords">85</div>
                <div class="summary-subtitle">Sedang dikemaskini</div>
            </div>
            
            <div class="summary-card info">
                <div class="summary-header">
                    <h4>Rekod Baru (30 hari)</h4>
                    <div class="summary-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
                <div class="summary-value" id="newRecords">40</div>
                <div class="summary-subtitle">Ditambah bulan lepas</div>
            </div>
        </div>

        <!-- View Options -->
        <div class="view-options">
            <button class="view-btn active" onclick="changeView('table')">
                <i class="fas fa-table"></i>
                Paparan Jadual
            </button>
            <button class="view-btn" onclick="changeView('grid')">
                <i class="fas fa-th-large"></i>
                Paparan Grid
            </button>
            <div style="margin-left: auto; display: flex; gap: 10px;">
                <button class="view-btn" onclick="toggleSelectAll()" id="selectAllBtn">
                    <i class="far fa-square"></i>
                    Pilih Semua
                </button>
                <div class="export-options">
                    <button class="view-btn" onclick="toggleExportDropdown()">
                        <i class="fas fa-download"></i>
                        Eksport
                    </button>
                    <div class="export-dropdown" id="exportDropdown">
                        <div class="export-option" onclick="exportData('excel')">
                            <i class="fas fa-file-excel" style="color: var(--success);"></i>
                            <span>Eksport ke Excel</span>
                        </div>
                        <div class="export-option" onclick="exportData('pdf')">
                            <i class="fas fa-file-pdf" style="color: var(--danger);"></i>
                            <span>Eksport ke PDF</span>
                        </div>
                        <div class="export-option" onclick="exportData('csv')">
                            <i class="fas fa-file-csv" style="color: var(--info);"></i>
                            <span>Eksport ke CSV</span>
                        </div>
                        <div class="export-option" onclick="exportData('print')">
                            <i class="fas fa-print" style="color: var(--warning);"></i>
                            <span>Cetak Rekod</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Records Table View -->
        <div class="records-table-container" id="tableView">
            <div class="table-header">
                <h3>Semua Rekod Markah <span id="recordsCount">(1,245 rekod)</span></h3>
                <div class="table-actions">
                    <button class="btn btn-secondary" onclick="refreshData()">
                        <i class="fas fa-redo"></i>
                        Segar Semula
                    </button>
                    <button class="btn btn-info" onclick="filterByToday()">
                        <i class="fas fa-calendar-day"></i>
                        Hari Ini
                    </button>
                </div>
            </div>
            
            <table id="recordsTable">
                <thead>
                    <tr>
                        <th width="50px">
                            <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAll()">
                        </th>
                        <th>PELAJAR</th>
                        <th>MATA PELAJARAN</th>
                        <th>JENIS PEPERIKSAAN</th>
                        <th>TAHUN/KELAS</th>
                        <th>MARKAH</th>
                        <th>GRED</th>
                        <th>STATUS</th>
                        <th>TARIKH</th>
                        <th width="120px">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="recordsTableBody">
                    <!-- Data akan dipenuhi oleh JavaScript -->
                </tbody>
            </table>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>Tiada Rekod Ditemui</h3>
                <p>Cubalah menukar penapis atau kata kunci carian anda.</p>
                <button class="btn btn-secondary" onclick="resetAllFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Semua Penapis
                </button>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" onclick="changePage(-1)" id="prevPage">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active" onclick="goToPage(1)">1</button>
                <button class="pagination-btn" onclick="goToPage(2)">2</button>
                <button class="pagination-btn" onclick="goToPage(3)">3</button>
                <span style="padding: 0 10px; color: var(--medium-gray);">...</span>
                <button class="pagination-btn" onclick="goToPage(10)">10</button>
                <button class="pagination-btn" onclick="changePage(1)" id="nextPage">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Records Grid View -->
        <div class="records-grid" id="gridView">
            <!-- Data akan dipenuhi oleh JavaScript -->
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <h4 id="toastTitle">Berjaya!</h4>
            <p id="toastMessage">Operasi berjaya diselesaikan.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const recordsTableBody = document.getElementById('recordsTableBody');
        const gridView = document.getElementById('gridView');
        const toast = document.getElementById('toast');
        const emptyState = document.getElementById('emptyState');
        const exportDropdown = document.getElementById('exportDropdown');
        const bulkActions = document.getElementById('bulkActions');
        const advancedSearch = document.getElementById('advancedSearch');

        // Current state
        let currentData = [];
        let filteredData = [];
        let selectedRecords = [];
        let currentView = 'table';
        let currentPage = 1;
        const itemsPerPage = 10;

        // Sample data for records
        const recordsData = [
            { 
                id: 'REC001', 
                studentId: 'P001', 
                studentName: 'AHMAD BIN ALI', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 95, 
                grade: 'A', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 10:30'
            },
            { 
                id: 'REC002', 
                studentId: 'P045', 
                studentName: 'SITI NOR AISYAH', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 93, 
                grade: 'A', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 10:32'
            },
            { 
                id: 'REC003', 
                studentId: 'P023', 
                studentName: 'MOHD AMIR BIN HASSAN', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 92, 
                grade: 'A', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 10:35'
            },
            { 
                id: 'REC004', 
                studentId: 'P067', 
                studentName: 'NURUL FATIMAH', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 91, 
                grade: 'A', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 10:40'
            },
            { 
                id: 'REC005', 
                studentId: 'P089', 
                studentName: 'WAN AHMAD BIN WAN', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 89, 
                grade: 'A', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 10:45'
            },
            { 
                id: 'REC006', 
                studentId: 'P034', 
                studentName: 'NOR HIDAYAH', 
                subject: 'Matematik', 
                subjectCode: 'MAT01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 87, 
                grade: 'B', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 11:00'
            },
            { 
                id: 'REC007', 
                studentId: 'P078', 
                studentName: 'ALI BIN KASSIM', 
                subject: 'Bahasa Melayu', 
                subjectCode: 'BAH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 85, 
                grade: 'B', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 11:15'
            },
            { 
                id: 'REC008', 
                studentId: 'P102', 
                studentName: 'ROHAYU BINTI RAHIM', 
                subject: 'Bahasa Inggeris', 
                subjectCode: 'BI01',
                examType: 'Ujian 2', 
                year: '6', 
                class: 'A', 
                mark: 84, 
                grade: 'B', 
                status: 'complete',
                date: '10 Okt 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '10 Okt 2023 14:20'
            },
            { 
                id: 'REC009', 
                studentId: 'P056', 
                studentName: 'FAIZ BIN FARID', 
                subject: 'Sains', 
                subjectCode: 'SNS01',
                examType: 'Peperiksaan Pertengahan', 
                year: '6', 
                class: 'B', 
                mark: 82, 
                grade: 'B', 
                status: 'complete',
                date: '20 Sep 2023',
                semester: '1',
                addedBy: 'Cikgu Admin',
                lastUpdated: '20 Sep 2023 09:45'
            },
            { 
                id: 'REC010', 
                studentId: 'P091', 
                studentName: 'AIN NABIHAH', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Ujian 1', 
                year: '5', 
                class: 'A', 
                mark: 80, 
                grade: 'B', 
                status: 'complete',
                date: '15 Ogos 2023',
                semester: '1',
                addedBy: 'Cikgu Ali',
                lastUpdated: '15 Ogos 2023 16:30'
            },
            { 
                id: 'REC011', 
                studentId: 'P112', 
                studentName: 'KAMAL BIN KAMARUDDIN', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 78, 
                grade: 'C', 
                status: 'pending',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 11:50'
            },
            { 
                id: 'REC012', 
                studentId: 'P123', 
                studentName: 'ZAHRAH BINTI ZAINAL', 
                subject: 'PJ & Kesihatan', 
                subjectCode: 'PJH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'A', 
                mark: 75, 
                grade: 'C', 
                status: 'incomplete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 12:00'
            },
            { 
                id: 'REC013', 
                studentId: 'P134', 
                studentName: 'HASAN BIN HUSSEIN', 
                subject: 'Matematik', 
                subjectCode: 'MAT01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'B', 
                mark: 72, 
                grade: 'C', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 12:15'
            },
            { 
                id: 'REC014', 
                studentId: 'P145', 
                studentName: 'NORA BINTI ISMAIL', 
                subject: 'Bahasa Melayu', 
                subjectCode: 'BAH01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'C', 
                mark: 68, 
                grade: 'D', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 12:30'
            },
            { 
                id: 'REC015', 
                studentId: 'P156', 
                studentName: 'FARID BIN FAUZI', 
                subject: 'Sains', 
                subjectCode: 'SNS01',
                examType: 'Peperiksaan Akhir', 
                year: '6', 
                class: 'C', 
                mark: 65, 
                grade: 'D', 
                status: 'complete',
                date: '15 Nov 2023',
                semester: '2',
                addedBy: 'Cikgu Admin',
                lastUpdated: '15 Nov 2023 12:45'
            }
        ];

        // Initialize page
        function initializePage() {
            currentData = [...recordsData];
            filteredData = [...currentData];
            
            // Set up event listeners for filters
            document.getElementById('filterYear').addEventListener('change', filterRecords);
            document.getElementById('filterClass').addEventListener('change', filterRecords);
            document.getElementById('filterExam').addEventListener('change', filterRecords);
            document.getElementById('filterSubject').addEventListener('change', filterRecords);
            document.getElementById('filterSemester').addEventListener('change', filterRecords);
            document.getElementById('filterStatus').addEventListener('change', filterRecords);
            document.getElementById('filterMinMark').addEventListener('change', filterRecords);
            document.getElementById('filterMaxMark').addEventListener('change', filterRecords);
            document.getElementById('filterDate').addEventListener('change', filterRecords);
            
            // Load initial data
            renderTable();
            updateSummary();
            
            // Set current date for date filter
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filterDate').value = today;
        }

        // Render table view
        function renderTable() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            // Update records count
            document.getElementById('recordsCount').textContent = `(${filteredData.length} rekod)`;
            
            // Show/hide empty state
            if (filteredData.length === 0) {
                emptyState.style.display = 'block';
                document.querySelector('table').style.display = 'none';
                document.querySelector('.pagination').style.display = 'none';
            } else {
                emptyState.style.display = 'none';
                document.querySelector('table').style.display = 'table';
                document.querySelector('.pagination').style.display = 'flex';
            }
            
            // Render table rows
            recordsTableBody.innerHTML = pageData.map((record, index) => {
                const globalIndex = startIndex + index + 1;
                const isSelected = selectedRecords.includes(record.id);
                
                return `
                    <tr id="row-${record.id}" class="${isSelected ? 'selected' : ''}">
                        <td>
                            <input type="checkbox" 
                                   class="record-checkbox" 
                                   id="checkbox-${record.id}"
                                   ${isSelected ? 'checked' : ''}
                                   onchange="toggleRecordSelection('${record.id}')">
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="student-avatar">${record.studentName.charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 600;">${record.studentName}</div>
                                    <div style="font-size: 12px; color: var(--medium-gray);">ID: ${record.studentId}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">${record.subject}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">${record.subjectCode}</div>
                        </td>
                        <td>${record.examType}</td>
                        <td>
                            <div style="font-weight: 600; color: var(--primary);">Tahun ${record.year}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Kelas ${record.class}</div>
                        </td>
                        <td style="font-weight: 700; color: var(--primary);">${record.mark}%</td>
                        <td>
                            <span class="grade-badge grade-${record.grade.toLowerCase()}">${record.grade}</span>
                        </td>
                        <td>
                            <span class="status-badge status-${record.status}">
                                ${record.status === 'complete' ? 'Lengkap' : 
                                  record.status === 'pending' ? 'Dalam Proses' : 'Tidak Lengkap'}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600;">${record.date}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">${record.semester}</div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon view" onclick="viewRecord('${record.id}')" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon edit" onclick="editRecord('${record.id}')" title="Edit Rekod">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon delete" onclick="deleteRecord('${record.id}')" title="Padam Rekod">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Render grid view
            renderGridView(pageData);
            
            // Update pagination controls
            updatePagination();
            
            // Update bulk actions
            updateBulkActions();
        }

        // Render grid view
        function renderGridView(data) {
            gridView.innerHTML = data.map(record => {
                const isSelected = selectedRecords.includes(record.id);
                
                return `
                    <div class="record-card ${isSelected ? 'selected' : ''}" id="card-${record.id}">
                        <div class="record-header">
                            <div class="record-student">
                                <div class="student-avatar">${record.studentName.charAt(0)}</div>
                                <div class="record-info">
                                    <h4>${record.studentName}</h4>
                                    <p>ID: ${record.studentId}</p>
                                </div>
                            </div>
                            <div class="record-grade">${record.mark}%</div>
                        </div>
                        
                        <div class="record-details">
                            <div class="detail-row">
                                <span class="detail-label">Mata Pelajaran:</span>
                                <span class="detail-value">${record.subject}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Jenis Peperiksaan:</span>
                                <span class="detail-value">${record.examType}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Tahun/Kelas:</span>
                                <span class="detail-value">${record.year}/${record.class}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Gred:</span>
                                <span>
                                    <span class="grade-badge grade-${record.grade.toLowerCase()}">${record.grade}</span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span>
                                    <span class="status-badge status-${record.status}">
                                        ${record.status === 'complete' ? 'Lengkap' : 
                                          record.status === 'pending' ? 'Dalam Proses' : 'Tidak Lengkap'}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Tarikh:</span>
                                <span class="detail-value">${record.date}</span>
                            </div>
                        </div>
                        
                        <div class="record-actions">
                            <input type="checkbox" 
                                   class="record-checkbox" 
                                   id="grid-checkbox-${record.id}"
                                   ${isSelected ? 'checked' : ''}
                                   onchange="toggleRecordSelection('${record.id}')"
                                   style="margin-right: auto;">
                            <button class="btn-icon view" onclick="viewRecord('${record.id}')" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon edit" onclick="editRecord('${record.id}')" title="Edit Rekod">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete" onclick="deleteRecord('${record.id}')" title="Padam Rekod">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Filter records
        function filterRecords() {
            const yearFilter = document.getElementById('filterYear').value;
            const classFilter = document.getElementById('filterClass').value;
            const examFilter = document.getElementById('filterExam').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            const semesterFilter = document.getElementById('filterSemester').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const minMark = document.getElementById('filterMinMark').value;
            const maxMark = document.getElementById('filterMaxMark').value;
            const dateFilter = document.getElementById('filterDate').value;
            
            filteredData = currentData.filter(record => {
                // Apply basic filters
                if (yearFilter && record.year !== yearFilter) return false;
                if (classFilter && record.class !== classFilter) return false;
                if (examFilter && record.examType !== getExamText(examFilter)) return false;
                if (subjectFilter && record.subjectCode !== subjectFilter) return false;
                if (semesterFilter && record.semester !== semesterFilter) return false;
                if (statusFilter && record.status !== statusFilter) return false;
                
                // Apply mark range filter
                if (minMark && record.mark < parseInt(minMark)) return false;
                if (maxMark && record.mark > parseInt(maxMark)) return false;
                
                // Apply date filter
                if (dateFilter) {
                    // Simple date filter for demo (in real app, convert to proper date comparison)
                    const filterDate = new Date(dateFilter).toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
                    if (record.date !== filterDate) return false;
                }
                
                return true;
            });
            
            // Reset to first page
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Search records
        function searchRecords() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            if (!searchTerm) {
                // If search is empty, show filtered data
                filterRecords();
                return;
            }
            
            filteredData = currentData.filter(record => {
                return record.studentName.toLowerCase().includes(searchTerm) || 
                       record.studentId.toLowerCase().includes(searchTerm) ||
                       record.subject.toLowerCase().includes(searchTerm) ||
                       (`tahun ${record.year} kelas ${record.class}`).includes(searchTerm);
            });
            
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Reset search
        function resetSearch() {
            document.getElementById('searchInput').value = '';
            filterRecords();
        }

        // Toggle advanced search
        function toggleAdvancedSearch() {
            advancedSearch.classList.toggle('active');
        }

        // Change view (table/grid)
        function changeView(view) {
            currentView = view;
            
            // Update active button
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show/hide views
            if (view === 'table') {
                document.getElementById('tableView').style.display = 'block';
                document.getElementById('gridView').classList.remove('active');
            } else {
                document.getElementById('tableView').style.display = 'none';
                document.getElementById('gridView').classList.add('active');
            }
            
            // Re-render current page
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            if (view === 'grid') {
                renderGridView(pageData);
            }
        }

        // Toggle record selection
        function toggleRecordSelection(recordId) {
            const index = selectedRecords.indexOf(recordId);
            
            if (index === -1) {
                selectedRecords.push(recordId);
            } else {
                selectedRecords.splice(index, 1);
            }
            
            // Update UI
            const row = document.getElementById(`row-${recordId}`);
            const card = document.getElementById(`card-${recordId}`);
            
            if (row) row.classList.toggle('selected');
            if (card) card.classList.toggle('selected');
            
            // Update checkboxes
            const tableCheckbox = document.getElementById(`checkbox-${recordId}`);
            const gridCheckbox = document.getElementById(`grid-checkbox-${recordId}`);
            
            if (tableCheckbox) tableCheckbox.checked = selectedRecords.includes(recordId);
            if (gridCheckbox) gridCheckbox.checked = selectedRecords.includes(recordId);
            
            updateBulkActions();
        }

        // Toggle select all
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const isChecked = selectAllCheckbox.checked;
            const selectAllBtn = document.getElementById('selectAllBtn');
            
            // Get current page records
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageRecords = filteredData.slice(startIndex, endIndex);
            
            if (isChecked) {
                // Select all records on current page
                pageRecords.forEach(record => {
                    if (!selectedRecords.includes(record.id)) {
                        selectedRecords.push(record.id);
                    }
                });
                
                // Update button text
                selectAllBtn.innerHTML = '<i class="far fa-check-square"></i> Nyahpilih Semua';
            } else {
                // Deselect all records on current page
                pageRecords.forEach(record => {
                    const index = selectedRecords.indexOf(record.id);
                    if (index !== -1) {
                        selectedRecords.splice(index, 1);
                    }
                });
                
                // Update button text
                selectAllBtn.innerHTML = '<i class="far fa-square"></i> Pilih Semua';
            }
            
            // Re-render to update UI
            renderTable();
            
            // Update bulk actions
            updateBulkActions();
        }

        // Update bulk actions
        function updateBulkActions() {
            const selectedCount = document.getElementById('selectedCount');
            selectedCount.textContent = `${selectedRecords.length} rekod dipilih`;
            
            if (selectedRecords.length > 0) {
                bulkActions.classList.add('active');
            } else {
                bulkActions.classList.remove('active');
            }
        }

        // Bulk deselect all
        function bulkDeselectAll() {
            selectedRecords = [];
            renderTable();
            showToast('Dinyahpilih', 'Semua rekod telah dinyahpilih', 'success');
        }

        // Bulk export
        function bulkExport() {
            if (selectedRecords.length === 0) {
                showToast('Tiada Rekod Dipilih', 'Sila pilih sekurang-kurangnya satu rekod untuk dieksport', 'error');
                return;
            }
            
            showToast('Mengeksport...', `Sedang mengeksport ${selectedRecords.length} rekod`, 'success');
            
            // Simulate export
            setTimeout(() => {
                showToast('Eksport Berjaya', `${selectedRecords.length} rekod telah dieksport ke fail Excel`, 'success');
                selectedRecords = [];
                renderTable();
            }, 1500);
        }

        // Bulk delete
        function bulkDelete() {
            if (selectedRecords.length === 0) {
                showToast('Tiada Rekod Dipilih', 'Sila pilih sekurang-kurangnya satu rekod untuk dipadam', 'error');
                return;
            }
            
            if (confirm(`Adakah anda pasti ingin memadam ${selectedRecords.length} rekod? Tindakan ini tidak boleh dibatalkan.`)) {
                // Remove selected records from data
                currentData = currentData.filter(record => !selectedRecords.includes(record.id));
                filteredData = filteredData.filter(record => !selectedRecords.includes(record.id));
                
                showToast('Memadam...', `Sedang memadam ${selectedRecords.length} rekod`, 'success');
                
                setTimeout(() => {
                    selectedRecords = [];
                    currentPage = 1;
                    renderTable();
                    updateSummary();
                    showToast('Berjaya Dipadam', `${selectedRecords.length} rekod telah dipadam`, 'success');
                }, 1000);
            }
        }

        // View record details
        function viewRecord(recordId) {
            const record = currentData.find(r => r.id === recordId);
            if (record) {
                alert(`Detail Rekod:\n\n` +
                      `Nama Pelajar: ${record.studentName}\n` +
                      `ID Pelajar: ${record.studentId}\n` +
                      `Mata Pelajaran: ${record.subject} (${record.subjectCode})\n` +
                      `Jenis Peperiksaan: ${record.examType}\n` +
                      `Tahun/Kelas: ${record.year}/${record.class}\n` +
                      `Markah: ${record.mark}%\n` +
                      `Gred: ${record.grade}\n` +
                      `Status: ${record.status === 'complete' ? 'Lengkap' : record.status === 'pending' ? 'Dalam Proses' : 'Tidak Lengkap'}\n` +
                      `Tarikh: ${record.date}\n` +
                      `Semester: ${record.semester}\n` +
                      `Ditambah oleh: ${record.addedBy}\n` +
                      `Kemaskini Terakhir: ${record.lastUpdated}`);
            }
        }

        // Edit record
        function editRecord(recordId) {
            const record = currentData.find(r => r.id === recordId);
            if (record) {
                alert(`Mengedit rekod untuk ${record.studentName}\n\nAnda akan dibawa ke halaman kemaskini markah.`);
                // In real app, redirect to edit page with recordId
                // window.location.href = `kemaskini-markah.html?record=${recordId}`;
            }
        }

        // Delete record
        function deleteRecord(recordId) {
            const record = currentData.find(r => r.id === recordId);
            if (!record) return;
            
            if (confirm(`Adakah anda pasti ingin memadam rekod markah untuk ${record.studentName}?\n\nMata Pelajaran: ${record.subject}\nMarkah: ${record.mark}%`)) {
                // Remove record from data
                const index = currentData.findIndex(r => r.id === recordId);
                if (index !== -1) {
                    currentData.splice(index, 1);
                    
                    // Update filtered data
                    filterRecords();
                    
                    showToast('Rekod Dipadam', `Rekod untuk ${record.studentName} telah dipadam`, 'success');
                }
            }
        }

        // Export data
        function exportData(format) {
            let message = '';
            
            switch(format) {
                case 'excel':
                    message = `Mengeksport ${filteredData.length} rekod ke fail Excel...`;
                    break;
                case 'pdf':
                    message = `Mengeksport ${filteredData.length} rekod ke fail PDF...`;
                    break;
                case 'csv':
                    message = `Mengeksport ${filteredData.length} rekod ke fail CSV...`;
                    break;
                case 'print':
                    message = 'Menyediakan rekod untuk dicetak...';
                    break;
            }
            
            showToast('Mengeksport', message, 'success');
            
            // Hide dropdown
            exportDropdown.classList.remove('active');
            
            // Simulate export
            setTimeout(() => {
                showToast('Eksport Selesai', 'Fail telah berjaya dijana dan sedia untuk dimuat turun', 'success');
                
                if (format === 'print') {
                    window.print();
                }
            }, 1500);
        }

        // Toggle export dropdown
        function toggleExportDropdown() {
            exportDropdown.classList.toggle('active');
        }

        // Filter by today
        function filterByToday() {
            // Reset all filters first
            resetAllFilters();
            
            // Set date filter to today
            const today = new Date().toISOString().split('T')[0];
            const todayFormatted = new Date(today).toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
            
            // Filter records for today
            filteredData = currentData.filter(record => record.date === todayFormatted);
            
            currentPage = 1;
            renderTable();
            updateSummary();
            
            showToast('Ditapis', `Menunjukkan rekod untuk ${todayFormatted}`, 'success');
        }

        // Update summary statistics
        function updateSummary() {
            const total = currentData.length;
            const complete = currentData.filter(r => r.status === 'complete').length;
            const pending = currentData.filter(r => r.status === 'pending').length;
            const incomplete = currentData.filter(r => r.status === 'incomplete').length;
            
            // Calculate new records (last 30 days) - simplified for demo
            const newRecords = Math.floor(total * 0.03); // 3% of total
            
            // Update display
            document.getElementById('totalRecords').textContent = total.toLocaleString();
            document.getElementById('completeRecords').textContent = complete.toLocaleString();
            document.getElementById('pendingRecords').textContent = pending.toLocaleString();
            document.getElementById('newRecords').textContent = newRecords.toLocaleString();
            
            // Update percentages in subtitles
            document.querySelector('.summary-card.success .summary-subtitle').textContent = 
                `${Math.round((complete / total) * 100)}% dari semua rekod`;
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
            // Reset all filters
            resetAllFilters();
            
            // Reload data (in real app, this would fetch from server)
            currentData = [...recordsData];
            filteredData = [...currentData];
            selectedRecords = [];
            currentPage = 1;
            
            showToast('Data Dimuat Semula', 'Semua penapis dan pemilihan telah diset semula', 'success');
            renderTable();
            updateSummary();
        }

        // Reset all filters
        function resetAllFilters() {
            document.getElementById('filterYear').value = '6';
            document.getElementById('filterClass').value = 'A';
            document.getElementById('filterExam').value = 'akhir';
            document.getElementById('filterSubject').value = 'PJH01';
            document.getElementById('filterSemester').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterMinMark').value = '';
            document.getElementById('filterMaxMark').value = '';
            document.getElementById('filterDate').value = '';
            document.getElementById('searchInput').value = '';
            
            // Hide advanced search
            advancedSearch.classList.remove('active');
            
            filterRecords();
        }

        // Refresh data
        function refreshData() {
            // In a real app, this would fetch fresh data from server
            showToast('Menyegar Semula', 'Mengemas kini data dari pelayan...', 'success');
            
            setTimeout(() => {
                showToast('Data Disegar', 'Semua data telah dikemas kini', 'success');
            }, 1000);
        }

        // Add new record
        function tambahRekodBaru() {
            alert('Anda akan dibawa ke halaman tambah markah untuk menambah rekod baru.');
            // In real app, redirect to add marks page
            // window.location.href = 'tambah-markah.html';
        }

        // Helper functions
        function getExamText(code) {
            const exams = {
                'ujian1': 'Ujian 1',
                'ujian2': 'Ujian 2',
                'pertengahan': 'Peperiksaan Pertengahan',
                'akhir': 'Peperiksaan Akhir'
            };
            return exams[code] || code;
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

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.export-options')) {
                exportDropdown.classList.remove('active');
            }
        });

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