<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunikasi Ibu Bapa - SlipKu</title>
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

        /* Communication Stats */
        .communication-stats {
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

        .stat-icon.unread {
            background: linear-gradient(135deg, var(--danger), #f87171);
        }

        .stat-icon.sent {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .stat-icon.announcement {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .stat-icon.meeting {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .stat-icon.parents {
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

        /* Communication Cards */
        .communication-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .communication-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
        }

        .communication-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .communication-card.unread {
            border-left: 5px solid var(--danger);
        }

        .communication-card.important {
            border-left: 5px solid var(--warning);
        }

        .communication-card.urgent {
            border-left: 5px solid var(--danger);
        }

        .communication-card.normal {
            border-left: 5px solid var(--info);
        }

        .communication-card.announcement {
            border-left: 5px solid var(--success);
        }

        .communication-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .communication-title h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .communication-type {
            display: inline-block;
            padding: 4px 12px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .communication-priority {
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

        .communication-details {
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

        /* Message Preview */
        .message-preview {
            background: var(--light-gray);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-size: 14px;
            color: var(--medium-gray);
            line-height: 1.5;
            max-height: 100px;
            overflow: hidden;
            position: relative;
        }

        .message-preview::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(to top, var(--light-gray), transparent);
        }

        /* Student Info */
        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
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

        /* Communication Actions */
        .communication-actions {
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

        /* Communication Table */
        .communication-table-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .communication-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .communication-table th {
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

        .communication-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: middle;
        }

        .communication-table tr:hover td {
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

        .status-unread {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .status-read {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-replied {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 100px;
        }

        .type-message {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .type-announcement {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .type-meeting {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .type-report {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Chat Interface */
        .chat-interface {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: none;
        }

        .chat-interface.active {
            display: block;
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .chat-back {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .chat-back:hover {
            color: var(--primary);
        }

        .chat-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-user-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .chat-user-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .chat-user-info p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .chat-status {
            margin-left: auto;
            font-size: 12px;
            color: var(--success);
            font-weight: 600;
        }

        /* Chat Messages */
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: var(--light-gray);
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 70%;
            padding: 15px;
            border-radius: 15px;
            position: relative;
            animation: fadeIn 0.3s ease;
        }

        .message.sent {
            align-self: flex-end;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-bottom-right-radius: 5px;
        }

        .message.received {
            align-self: flex-start;
            background: var(--white);
            color: var(--dark-gray);
            border: 1px solid #e5e7eb;
            border-bottom-left-radius: 5px;
        }

        .message-content {
            font-size: 14px;
            line-height: 1.5;
        }

        .message-time {
            font-size: 11px;
            margin-top: 5px;
            opacity: 0.7;
            text-align: right;
        }

        .message.received .message-time {
            color: var(--medium-gray);
        }

        .message.sent .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Chat Input */
        .chat-input {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .chat-input textarea {
            flex: 1;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            resize: none;
            min-height: 60px;
            max-height: 120px;
            transition: var(--transition);
        }

        .chat-input textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .chat-actions {
            display: flex;
            gap: 10px;
        }

        /* Announcement Section */
        .announcement-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .announcement-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .announcement-title i {
            color: var(--warning);
        }

        /* Announcement Cards */
        .announcement-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .announcement-card {
            background: var(--light-gray);
            padding: 20px;
            border-radius: 15px;
            transition: var(--transition);
            border: 2px solid transparent;
        }

        .announcement-card:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .announcement-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .announcement-card-title h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .announcement-date {
            font-size: 12px;
            color: var(--medium-gray);
            background: var(--white);
            padding: 4px 10px;
            border-radius: 20px;
        }

        .announcement-content {
            font-size: 14px;
            color: var(--medium-gray);
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .announcement-recipients {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
        }

        /* Meeting Schedule */
        .meeting-schedule {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .meeting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .meeting-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .meeting-title i {
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Recipient Selection */
        .recipient-selection {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 15px;
            max-height: 200px;
            overflow-y: auto;
        }

        .recipient-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: var(--transition);
            cursor: pointer;
        }

        .recipient-option:hover {
            background: var(--light-gray);
        }

        .recipient-option input {
            margin: 0;
        }

        .recipient-avatar {
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

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            
            .communication-cards {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }
            
            .announcement-cards {
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
            
            .communication-cards {
                grid-template-columns: 1fr;
            }
            
            .communication-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .chat-messages {
                height: 300px;
            }
            
            .message {
                max-width: 85%;
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
            
            .communication-stats {
                grid-template-columns: 1fr;
            }
            
            .communication-actions {
                flex-wrap: wrap;
            }
            
            .tab-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
            
            .announcement-cards {
                grid-template-columns: 1fr;
            }
            
            .chat-header {
                flex-wrap: wrap;
            }
            
            .chat-status {
                margin-left: 0;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- New Message Modal -->
    <div class="modal" id="newMessageModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Mesej Baharu</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="messageForm" onsubmit="hantarMesej(event)">
                <div class="form-group">
                    <label class="form-label">Kepada</label>
                    <select class="form-select" id="messageRecipientType" onchange="ubahJenisPenerima()">
                        <option value="single">Individu</option>
                        <option value="class">Kelas</option>
                        <option value="all">Semua Ibu Bapa</option>
                        <option value="multiple">Pelbagai Penerima</option>
                    </select>
                </div>
                
                <div id="singleRecipient" class="form-group">
                    <label class="form-label">Pilih Pelajar</label>
                    <select class="form-select" id="messageStudent">
                        <option value="">Pilih Pelajar</option>
                        <!-- Students will be loaded here -->
                    </select>
                </div>
                
                <div id="classRecipient" class="form-group" style="display: none;">
                    <label class="form-label">Pilih Kelas</label>
                    <select class="form-select" id="messageClass">
                        <option value="">Pilih Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                        <option value="all">Semua Kelas</option>
                    </select>
                </div>
                
                <div id="multipleRecipients" class="form-group" style="display: none;">
                    <label class="form-label">Pilih Penerima</label>
                    <div class="recipient-selection">
                        <!-- Recipient options will be loaded here -->
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subjek</label>
                    <input type="text" class="form-input" id="messageSubject" placeholder="Contoh: Perjumpaan Ibu Bapa, Laporan Prestasi..." required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Jenis Mesej</label>
                    <select class="form-select" id="messageType" required>
                        <option value="">Pilih Jenis</option>
                        <option value="message">Mesej Biasa</option>
                        <option value="announcement">Pengumuman</option>
                        <option value="meeting">Jemputan Mesyuarat</option>
                        <option value="report">Laporan Prestasi</option>
                        <option value="reminder">Peringatan</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Keutamaan</label>
                        <select class="form-select" id="messagePriority">
                            <option value="normal">Biasa</option>
                            <option value="important">Penting</option>
                            <option value="urgent">Segera</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Notifikasi</label>
                        <select class="form-select" id="messageNotification">
                            <option value="all">SMS & Aplikasi</option>
                            <option value="app">Aplikasi Sahaja</option>
                            <option value="sms">SMS Sahaja</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kandungan Mesej</label>
                    <textarea class="form-textarea" id="messageContent" placeholder="Tulis mesej anda di sini..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lampiran (Pilihan)</label>
                    <div style="border: 2px dashed #e5e7eb; border-radius: 10px; padding: 20px; text-align: center; cursor: pointer;" onclick="document.getElementById('messageFile').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: var(--primary); margin-bottom: 10px;"></i>
                        <p style="color: var(--medium-gray); margin-bottom: 5px;">Klik untuk muat naik fail</p>
                        <small style="color: var(--medium-gray);">PDF, DOC, JPG (Maks: 5MB)</small>
                    </div>
                    <input type="file" id="messageFile" style="display: none;">
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-paper-plane"></i>
                        Hantar Mesej
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Message Modal -->
    <div class="modal" id="viewMessageModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Butiran Mesej</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="messageDetails">
                <!-- Message details will be loaded here -->
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
                    <p>Komunikasi Ibu Bapa</p>
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
                    <span class="notification-badge">12</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-envelope"></i>
                    Mesej
                    <span class="notification-badge">5</span>
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
            <a href="kehadiran.html" class="sidebar-item">
                <i class="fas fa-clipboard-check"></i>
                Kehadiran
            </a>
            <a href="komunikasi.html" class="sidebar-item active">
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
                <h2>Komunikasi Ibu Bapa 💬</h2>
                <p>Komunikasi dan interaksi dengan ibu bapa pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaKomunikasi()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="hantarMesejModal()">
                    <i class="fas fa-paper-plane"></i>
                    Mesej Baharu
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="ubahTab('inbox')">
                <i class="fas fa-inbox"></i>
                Kotak Masuk
            </button>
            <button class="tab-btn" onclick="ubahTab('sent')">
                <i class="fas fa-paper-plane"></i>
                Dihantar
            </button>
            <button class="tab-btn" onclick="ubahTab('announcements')">
                <i class="fas fa-bullhorn"></i>
                Pengumuman
            </button>
            <button class="tab-btn" onclick="ubahTab('meetings')">
                <i class="fas fa-calendar-check"></i>
                Mesyuarat
            </button>
        </div>

        <!-- Communication Statistics -->
        <div class="communication-stats">
            <div class="stat-card">
                <div class="stat-icon unread">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-value" id="unreadCount">5</div>
                <div class="stat-label">Belum Dibaca</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon sent">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-value" id="sentCount">42</div>
                <div class="stat-label">Telah Dihantar</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon announcement">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-value" id="announcementCount">8</div>
                <div class="stat-label">Pengumuman</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon meeting">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value" id="meetingCount">3</div>
                <div class="stat-label">Mesyuarat</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon parents">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value" id="parentsCount">85</div>
                <div class="stat-label">Ibu Bapa</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Tapis Komunikasi
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="tapilkanKomunikasi()">
                        <option value="all">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Jenis:</label>
                    <select class="filter-select" id="filterType" onchange="tapilkanKomunikasi()">
                        <option value="all">Semua Jenis</option>
                        <option value="message">Mesej</option>
                        <option value="announcement">Pengumuman</option>
                        <option value="meeting">Mesyuarat</option>
                        <option value="report">Laporan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select class="filter-select" id="filterStatus" onchange="tapilkanKomunikasi()">
                        <option value="all">Semua Status</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="read">Telah Dibaca</option>
                        <option value="replied">Telah Dibalas</option>
                        <option value="pending">Belum Dibalas</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Tarikh:</label>
                    <select class="filter-select" id="filterDate" onchange="tapilkanKomunikasi()">
                        <option value="all">Semua Tarikh</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="older">Lebih Lama</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Chat Interface -->
        <div class="chat-interface" id="chatInterface">
            <!-- Chat interface will be loaded here -->
        </div>

        <!-- Inbox Tab -->
        <div id="inboxTab">
            <div class="communication-cards" id="inboxMessages">
                <!-- Inbox messages will be loaded here -->
            </div>
        </div>

        <!-- Sent Tab -->
        <div id="sentTab" style="display: none;">
            <div class="communication-cards" id="sentMessages">
                <!-- Sent messages will be loaded here -->
            </div>
        </div>

        <!-- Announcements Tab -->
        <div id="announcementsTab" style="display: none;">
            <div class="announcement-section">
                <div class="announcement-header">
                    <div class="announcement-title">
                        <i class="fas fa-bullhorn"></i>
                        <span>Pengumuman Terkini</span>
                    </div>
                    <button class="btn btn-primary" onclick="buatPengumumanModal()">
                        <i class="fas fa-plus-circle"></i>
                        Pengumuman Baru
                    </button>
                </div>
                
                <div class="announcement-cards" id="announcementCards">
                    <!-- Announcement cards will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Meetings Tab -->
        <div id="meetingsTab" style="display: none;">
            <div class="meeting-schedule">
                <div class="meeting-header">
                    <div class="meeting-title">
                        <i class="fas fa-calendar-check"></i>
                        <span>Jadual Mesyuarat Ibu Bapa</span>
                    </div>
                    <button class="btn btn-primary" onclick="jadualkanMesyuaratModal()">
                        <i class="fas fa-calendar-plus"></i>
                        Jadual Baru
                    </button>
                </div>
                
                <div class="communication-table-container">
                    <div style="overflow-x: auto;">
                        <table class="communication-table">
                            <thead>
                                <tr>
                                    <th>TARIKH & MASA</th>
                                    <th>TAJUK</th>
                                    <th>KELAS</th>
                                    <th>LOKASI</th>
                                    <th>STATUS</th>
                                    <th>KEHADIRAN</th>
                                    <th>TINDAKAN</th>
                                </tr>
                            </thead>
                            <tbody id="meetingTableBody">
                                <!-- Meeting rows will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table View -->
        <div class="communication-table-container">
            <div class="filter-title">
                <i class="fas fa-table"></i>
                Senarai Terperinci Komunikasi
            </div>
            
            <div style="overflow-x: auto; margin-top: 20px;">
                <table class="communication-table">
                    <thead>
                        <tr>
                            <th>PENERIMA</th>
                            <th>SUBJEK</th>
                            <th>JENIS</th>
                            <th>TARIKH</th>
                            <th>STATUS</th>
                            <th>KEUTAMAAN</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="communicationTableBody">
                        <!-- Communication table rows will be loaded here -->
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
        const newMessageModal = document.getElementById('newMessageModal');
        const viewMessageModal = document.getElementById('viewMessageModal');
        const messageDetails = document.getElementById('messageDetails');
        const chatInterface = document.getElementById('chatInterface');

        // Tabs
        const inboxTab = document.getElementById('inboxTab');
        const sentTab = document.getElementById('sentTab');
        const announcementsTab = document.getElementById('announcementsTab');
        const meetingsTab = document.getElementById('meetingsTab');

        // Message containers
        const inboxMessages = document.getElementById('inboxMessages');
        const sentMessages = document.getElementById('sentMessages');
        const announcementCards = document.getElementById('announcementCards');
        const meetingTableBody = document.getElementById('meetingTableBody');
        const communicationTableBody = document.getElementById('communicationTableBody');

        // Sample data
        const sampleParents = [
            { id: 'PAR001', studentId: 'STU001', name: 'En. Ali bin Hassan', phone: '012-3456789', email: 'ali.hassan@email.com', studentName: 'Ahmad bin Ali', class: '6A' },
            { id: 'PAR002', studentId: 'STU002', name: 'Puan Siti Aminah', phone: '012-9876543', email: 'siti.aminah@email.com', studentName: 'Siti binti Abu', class: '6A' },
            { id: 'PAR003', studentId: 'STU003', name: 'En. Hassan bin Musa', phone: '012-4567890', email: 'hassan.musa@email.com', studentName: 'Muhammad bin Hassan', class: '6A' },
            { id: 'PAR004', studentId: 'STU004', name: 'Puan Aishah bt Omar', phone: '012-6789012', email: 'aishah.omar@email.com', studentName: 'Aisyah binti Musa', class: '6A' },
            { id: 'PAR005', studentId: 'STU005', name: 'En. Abdullah bin Karim', phone: '012-7890123', email: 'abdullah.karim@email.com', studentName: 'Ali bin Abdullah', class: '6A' },
            { id: 'PAR006', studentId: 'STU006', name: 'Puan Fatimah bt Ismail', phone: '012-8901234', email: 'fatimah.ismail@email.com', studentName: 'Fatimah binti Omar', class: '6A' },
            { id: 'PAR007', studentId: 'STU007', name: 'En. Ismail bin Yusof', phone: '012-9012345', email: 'ismail.yusof@email.com', studentName: 'Hassan bin Ismail', class: '6A' },
            { id: 'PAR008', studentId: 'STU008', name: 'Puan Zainab bt Ahmad', phone: '012-0123456', email: 'zainab.ahmad@email.com', studentName: 'Zainab binti Yusuf', class: '6A' }
        ];

        const sampleMessages = [
            {
                id: 'MSG001',
                type: 'message',
                sender: 'En. Ali bin Hassan',
                senderId: 'PAR001',
                recipient: 'Cikgu Ahmad',
                subject: 'Mengenai Prestasi Matematik',
                content: 'Assalamualaikum Cikgu, saya ingin bertanya mengenai prestasi anak saya Ahmad dalam subjek Matematik. Saya perhatikan markah ujian terakhir menurun sedikit.',
                date: new Date(2024, 2, 14, 14, 30),
                status: 'replied',
                priority: 'normal',
                read: true,
                replies: [
                    {
                        id: 'REP001',
                        sender: 'Cikgu Ahmad',
                        content: 'Waalaikumussalam En. Ali. Ahmad sebenarnya menunjukkan peningkatan dalam ujian lepas, cuma ada sedikit kesilapan dalam soalan pecahan. Saya telah beri latihan tambahan.',
                        date: new Date(2024, 2, 14, 15, 45)
                    },
                    {
                        id: 'REP002',
                        sender: 'En. Ali bin Hassan',
                        content: 'Terima kasih Cikgu. Saya akan pastikan dia buat latihan yang diberi. Adakah perlu sesi tambahan?',
                        date: new Date(2024, 2, 15, 9, 20)
                    }
                ]
            },
            {
                id: 'MSG002',
                type: 'message',
                sender: 'Puan Siti Aminah',
                senderId: 'PAR002',
                recipient: 'Cikgu Ahmad',
                subject: 'Permohonan Cuti',
                content: 'Salam Cikgu, anak saya Siti perlu mengambil cuti pada 20 Mac kerana ada urusan keluarga di luar bandar. Mohon kebenaran.',
                date: new Date(2024, 2, 13, 10, 15),
                status: 'read',
                priority: 'normal',
                read: true,
                replies: []
            },
            {
                id: 'MSG003',
                type: 'message',
                sender: 'En. Hassan bin Musa',
                senderId: 'PAR003',
                recipient: 'Cikgu Ahmad',
                subject: 'Kebimbangan Kehadiran',
                content: 'Salam Cikgu, saya perhatikan anak saya Muhammad beberapa kali lewat ke sekolah. Ada masalah kesihatan ke?',
                date: new Date(2024, 2, 12, 16, 45),
                status: 'unread',
                priority: 'important',
                read: false,
                replies: []
            },
            {
                id: 'MSG004',
                type: 'announcement',
                sender: 'Cikgu Ahmad',
                recipient: 'Semua Ibu Bapa Kelas 6A',
                subject: 'Perjumpaan Ibu Bapa-Guru',
                content: 'Dimaklumkan bahawa Perjumpaan Ibu Bapa-Guru akan diadakan pada 25 Mac 2024, jam 2.00 petang di Dewan Sekolah. Kehadiran amat dihargai.',
                date: new Date(2024, 2, 10, 9, 0),
                status: 'sent',
                priority: 'important',
                read: true,
                attachment: 'jadual_perjumpaan.pdf'
            },
            {
                id: 'MSG005',
                type: 'report',
                sender: 'Cikgu Ahmad',
                recipient: 'Puan Aishah bt Omar',
                subject: 'Laporan Prestasi Suku Tahun',
                content: 'Salam sejahtera. Berikut adalah laporan prestasi Aisyah untuk suku tahun pertama. Pencapaian keseluruhan adalah memuaskan dengan purata 78.4%.',
                date: new Date(2024, 2, 8, 11, 30),
                status: 'read',
                priority: 'normal',
                read: true,
                attachment: 'laporan_aisyah.pdf'
            },
            {
                id: 'MSG006',
                type: 'meeting',
                sender: 'Cikgu Ahmad',
                recipient: 'En. Abdullah bin Karim',
                subject: 'Mesyuarat Bimbingan',
                content: 'Jemputan mesyuarat bimbingan untuk membincangkan prestasi Ali dalam Bahasa Inggeris. Dijadualkan pada 18 Mac, jam 10.00 pagi di Bilik Guru.',
                date: new Date(2024, 2, 7, 14, 15),
                status: 'pending',
                priority: 'urgent',
                read: true,
                meetingDate: new Date(2024, 2, 18, 10, 0),
                location: 'Bilik Guru',
                confirmed: false
            },
            {
                id: 'MSG007',
                type: 'announcement',
                sender: 'Cikgu Ahmad',
                recipient: 'Semua Ibu Bapa',
                subject: 'Ujian Pertengahan Tahun',
                content: 'Perhatian kepada semua ibu bapa. Ujian Pertengahan Tahun akan bermula pada 22 Mac 2024. Sila pastikan anak-anak membuat persiapan yang mencukupi.',
                date: new Date(2024, 2, 5, 8, 0),
                status: 'sent',
                priority: 'important',
                read: true
            },
            {
                id: 'MSG008',
                type: 'message',
                sender: 'Puan Fatimah bt Ismail',
                senderId: 'PAR006',
                recipient: 'Cikgu Ahmad',
                subject: 'Terima Kasih atas Bimbingan',
                content: 'Salam Cikgu, terima kasih atas bimbingan dan perhatian yang diberikan kepada Fatimah. Saya nampak peningkatan dalam keyakinan dirinya.',
                date: new Date(2024, 2, 4, 17, 30),
                status: 'replied',
                priority: 'normal',
                read: true,
                replies: [
                    {
                        id: 'REP003',
                        sender: 'Cikgu Ahmad',
                        content: 'Sama-sama Puan Fatimah. Saya gembira melihat peningkatan Fatimah. Teruskan sokongan di rumah.',
                        date: new Date(2024, 2, 5, 9, 15)
                    }
                ]
            }
        ];

        const sampleAnnouncements = [
            {
                id: 'ANN001',
                title: 'Perjumpaan Ibu Bapa-Guru',
                content: 'Dimaklumkan bahawa Perjumpaan Ibu Bapa-Guru akan diadakan pada 25 Mac 2024, jam 2.00 petang di Dewan Sekolah. Kehadiran amat dihargai.',
                date: new Date(2024, 2, 25, 14, 0),
                recipients: 'Semua Ibu Bapa Kelas 6A',
                priority: 'important'
            },
            {
                id: 'ANN002',
                title: 'Ujian Pertengahan Tahun',
                content: 'Ujian Pertengahan Tahun akan bermula pada 22 Mac 2024. Sila pastikan anak-anak membuat persiapan yang mencukupi dan hadir tepat pada masa.',
                date: new Date(2024, 2, 22, 8, 0),
                recipients: 'Semua Ibu Bapa',
                priority: 'urgent'
            },
            {
                id: 'ANN003',
                title: 'Aktiviti Gotong-royong Sekolah',
                content: 'Sekolah akan mengadakan aktiviti gotong-royong pada 30 Mac 2024. Ibu bapa yang berminat untuk menyertai dialu-alukan.',
                date: new Date(2024, 2, 30, 8, 0),
                recipients: 'Semua Ibu Bapa',
                priority: 'normal'
            },
            {
                id: 'ANN004',
                title: 'Pembayaran Yuran Sekolah',
                content: 'Perhatian: Pembayaran yuran sekolah untuk bulan Mac perlu diselesaikan sebelum 20 Mac 2024. Sila hubungi pejabat sekolah untuk maklumat lanjut.',
                date: new Date(2024, 2, 15, 12, 0),
                recipients: 'Semua Ibu Bapa',
                priority: 'important'
            }
        ];

        const sampleMeetings = [
            {
                id: 'MTG001',
                title: 'Perjumpaan Ibu Bapa-Guru',
                date: new Date(2024, 2, 25, 14, 0),
                location: 'Dewan Sekolah',
                class: '6A',
                status: 'scheduled',
                attendance: {
                    total: 30,
                    confirmed: 18,
                    pending: 12
                },
                agenda: 'Perbincangan prestasi pelajar dan pelan peningkatan'
            },
            {
                id: 'MTG002',
                title: 'Mesyuarat Bimbingan Individu',
                date: new Date(2024, 2, 18, 10, 0),
                location: 'Bilik Guru',
                class: '6A',
                status: 'confirmed',
                attendance: {
                    total: 1,
                    confirmed: 1,
                    pending: 0
                },
                agenda: 'Perbincangan prestasi Ali dalam Bahasa Inggeris'
            },
            {
                id: 'MTG003',
                title: 'Taklimat UPSR 2024',
                date: new Date(2024, 2, 28, 15, 0),
                location: 'Makmal Komputer',
                class: 'all',
                status: 'scheduled',
                attendance: {
                    total: 85,
                    confirmed: 45,
                    pending: 40
                },
                agenda: 'Taklimat format peperiksaan UPSR dan strategi pembelajaran'
            }
        ];

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Load messages
            loadInboxMessages();
            loadSentMessages();
            loadAnnouncements();
            loadMeetings();
            loadCommunicationTable();
            
            // Update statistics
            updateCommunicationStatistics();
            
            // Load student options
            loadStudentOptions();
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
            newMessageModal.addEventListener('click', function(e) {
                if (e.target === newMessageModal) {
                    tutupModal();
                }
            });
            
            viewMessageModal.addEventListener('click', function(e) {
                if (e.target === viewMessageModal) {
                    tutupModal();
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
            
            // Hide chat interface
            chatInterface.classList.remove('active');
            
            // Show active tab content
            inboxTab.style.display = 'none';
            sentTab.style.display = 'none';
            announcementsTab.style.display = 'none';
            meetingsTab.style.display = 'none';
            
            if (tab === 'inbox') {
                inboxTab.style.display = 'block';
                loadInboxMessages();
            } else if (tab === 'sent') {
                sentTab.style.display = 'block';
                loadSentMessages();
            } else if (tab === 'announcements') {
                announcementsTab.style.display = 'block';
            } else if (tab === 'meetings') {
                meetingsTab.style.display = 'block';
            }
        }

        // Load inbox messages
        function loadInboxMessages() {
            const inbox = sampleMessages.filter(msg => 
                msg.sender !== 'Cikgu Ahmad' && 
                (msg.type === 'message' || msg.type === 'meeting')
            );
            
            if (inbox.length === 0) {
                inboxMessages.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Tiada Mesej</h3>
                        <p>Tiada mesej dalam kotak masuk anda</p>
                    </div>
                `;
                return;
            }
            
            // Sort by date (newest first)
            const sortedInbox = [...inbox].sort((a, b) => b.date - a.date);
            
            inboxMessages.innerHTML = sortedInbox.map(message => createMessageCard(message, 'inbox')).join('');
        }

        // Load sent messages
        function loadSentMessages() {
            const sent = sampleMessages.filter(msg => 
                msg.sender === 'Cikgu Ahmad'
            );
            
            if (sent.length === 0) {
                sentMessages.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-paper-plane"></i>
                        <h3>Tiada Mesej Dihantar</h3>
                        <p>Anda belum menghantar sebarang mesej</p>
                    </div>
                `;
                return;
            }
            
            // Sort by date (newest first)
            const sortedSent = [...sent].sort((a, b) => b.date - a.date);
            
            sentMessages.innerHTML = sortedSent.map(message => createMessageCard(message, 'sent')).join('');
        }

        // Create message card
        function createMessageCard(message, type) {
            const dateStr = message.date.toLocaleDateString('ms-MY', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const timeAgo = getTimeAgo(message.date);
            
            // Get status badge
            const getStatusBadge = () => {
                switch(message.status) {
                    case 'unread': return '<span class="status-badge status-unread">Belum Dibaca</span>';
                    case 'read': return '<span class="status-badge status-read">Telah Dibaca</span>';
                    case 'replied': return '<span class="status-badge status-replied">Telah Dibalas</span>';
                    case 'pending': return '<span class="status-badge status-pending">Belum Dibalas</span>';
                    case 'sent': return '<span class="status-badge status-read">Telah Dihantar</span>';
                    default: return '';
                }
            };
            
            // Get type badge
            const getTypeBadge = () => {
                switch(message.type) {
                    case 'message': return '<span class="type-badge type-message">Mesej</span>';
                    case 'announcement': return '<span class="type-badge type-announcement">Pengumuman</span>';
                    case 'meeting': return '<span class="type-badge type-meeting">Mesyuarat</span>';
                    case 'report': return '<span class="type-badge type-report">Laporan</span>';
                    default: return '';
                }
            };
            
            // Get priority class
            const priorityClass = message.priority === 'urgent' ? 'urgent' : 
                                 message.priority === 'important' ? 'important' : 'normal';
            
            // Get student info for parent messages
            let studentInfo = '';
            if (type === 'inbox') {
                const parent = sampleParents.find(p => p.name === message.sender);
                if (parent) {
                    studentInfo = `
                        <div class="student-info">
                            <div class="student-avatar">${parent.studentName.split(' ').map(n => n.charAt(0)).join('')}</div>
                            <div class="student-details">
                                <h4>${parent.studentName}</h4>
                                <p>${parent.class}</p>
                            </div>
                        </div>
                    `;
                }
            }
            
            return `
                <div class="communication-card ${priorityClass} ${!message.read && type === 'inbox' ? 'unread' : ''}">
                    <div class="communication-header">
                        <div class="communication-title">
                            <h4>${message.subject}</h4>
                            ${getTypeBadge()}
                            <div class="${message.priority === 'urgent' ? 'priority-high' : message.priority === 'important' ? 'priority-medium' : 'priority-low'} communication-priority">
                                ${message.priority === 'urgent' ? 'Segera' : message.priority === 'important' ? 'Penting' : 'Biasa'}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            ${getStatusBadge()}
                            <div style="font-size: 12px; color: var(--medium-gray); margin-top: 5px;">${timeAgo}</div>
                        </div>
                    </div>
                    
                    ${studentInfo}
                    
                    <div class="communication-details">
                        <div class="detail-item">
                            <i class="fas ${type === 'inbox' ? 'fa-user' : 'fa-users'}"></i>
                            <span>${type === 'inbox' ? 'Daripada:' : 'Kepada:'} <strong>${type === 'inbox' ? message.sender : message.recipient}</strong></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <span>${dateStr}</span>
                        </div>
                    </div>
                    
                    <div class="message-preview">
                        ${message.content}
                    </div>
                    
                    <div class="communication-actions">
                        <button class="action-btn primary" onclick="lihatMesej('${message.id}', '${type}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                        ${type === 'inbox' ? `
                        <button class="action-btn success" onclick="balasMesej('${message.id}')">
                            <i class="fas fa-reply"></i>
                            Balas
                        </button>
                        ` : ''}
                        ${type === 'sent' ? `
                        <button class="action-btn info" onclick="lihatStatus('${message.id}')">
                            <i class="fas fa-chart-bar"></i>
                            Status
                        </button>
                        ` : ''}
                        <button class="action-btn danger" onclick="padamMesej('${message.id}')">
                            <i class="fas fa-trash"></i>
                            Padam
                        </button>
                    </div>
                </div>
            `;
        }

        // Load announcements
        function loadAnnouncements() {
            if (sampleAnnouncements.length === 0) {
                announcementCards.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Tiada Pengumuman</h3>
                        <p>Tiada pengumuman terkini</p>
                    </div>
                `;
                return;
            }
            
            // Sort by date (newest first)
            const sortedAnnouncements = [...sampleAnnouncements].sort((a, b) => b.date - a.date);
            
            announcementCards.innerHTML = sortedAnnouncements.map(announcement => {
                const dateStr = announcement.date.toLocaleDateString('ms-MY', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                return `
                    <div class="announcement-card">
                        <div class="announcement-card-header">
                            <div class="announcement-card-title">
                                <h4>${announcement.title}</h4>
                                <div class="${announcement.priority === 'urgent' ? 'priority-high' : announcement.priority === 'important' ? 'priority-medium' : 'priority-low'}" style="display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px;">
                                    ${announcement.priority === 'urgent' ? 'Segera' : announcement.priority === 'important' ? 'Penting' : 'Biasa'}
                                </div>
                            </div>
                            <div class="announcement-date">${dateStr}</div>
                        </div>
                        
                        <div class="announcement-content">
                            ${announcement.content}
                        </div>
                        
                        <div class="announcement-recipients">
                            <i class="fas fa-users"></i> ${announcement.recipients}
                        </div>
                        
                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <button class="action-btn primary" onclick="editPengumuman('${announcement.id}')" style="flex: 1;">
                                <i class="fas fa-edit"></i>
                                Kemaskini
                            </button>
                            <button class="action-btn danger" onclick="padamPengumuman('${announcement.id}')" style="flex: 1;">
                                <i class="fas fa-trash"></i>
                                Padam
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Load meetings
        function loadMeetings() {
            if (sampleMeetings.length === 0) {
                meetingTableBody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px;">
                            <div class="empty-state" style="padding: 0;">
                                <i class="fas fa-calendar-times"></i>
                                <h3>Tiada Mesyuarat</h3>
                                <p>Tiada mesyuarat dijadualkan</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Sort by date (soonest first)
            const sortedMeetings = [...sampleMeetings].sort((a, b) => a.date - b.date);
            
            meetingTableBody.innerHTML = sortedMeetings.map(meeting => {
                const dateStr = meeting.date.toLocaleDateString('ms-MY', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                
                const daysUntil = Math.ceil((meeting.date - new Date()) / (1000 * 60 * 60 * 24));
                let statusBadge = '';
                
                if (meeting.status === 'scheduled') {
                    statusBadge = '<span class="status-badge status-pending">Dijadualkan</span>';
                } else if (meeting.status === 'confirmed') {
                    statusBadge = '<span class="status-badge status-read">Disahkan</span>';
                } else if (meeting.status === 'completed') {
                    statusBadge = '<span class="status-badge status-replied">Selesai</span>';
                } else if (meeting.status === 'cancelled') {
                    statusBadge = '<span class="status-badge status-unread">Dibatalkan</span>';
                }
                
                const attendancePercent = Math.round((meeting.attendance.confirmed / meeting.attendance.total) * 100);
                
                return `
                    <tr>
                        <td>${dateStr}</td>
                        <td>
                            <div style="font-weight: 600;">${meeting.title}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">${daysUntil} hari lagi</div>
                        </td>
                        <td>${meeting.class === 'all' ? 'Semua Kelas' : meeting.class}</td>
                        <td>${meeting.location}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; background: #e5e7eb; height: 6px; border-radius: 3px;">
                                    <div style="background: var(--success); height: 100%; border-radius: 3px; width: ${attendancePercent}%"></div>
                                </div>
                                <div style="font-size: 12px; font-weight: 600; min-width: 60px;">${meeting.attendance.confirmed}/${meeting.attendance.total}</div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="action-btn info" onclick="lihatMesyuarat('${meeting.id}')" style="padding: 6px 10px; font-size: 12px;">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn warning" onclick="kemaskiniMesyuarat('${meeting.id}')" style="padding: 6px 10px; font-size: 12px;">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn danger" onclick="batalkanMesyuarat('${meeting.id}')" style="padding: 6px 10px; font-size: 12px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load communication table
        function loadCommunicationTable() {
            // Combine messages and announcements
            const allCommunications = [...sampleMessages, ...sampleAnnouncements.map(ann => ({
                ...ann,
                type: 'announcement',
                sender: 'Cikgu Ahmad',
                recipient: ann.recipients,
                date: ann.date,
                status: 'sent',
                priority: ann.priority
            }))];
            
            // Sort by date (newest first)
            const sortedCommunications = [...allCommunications].sort((a, b) => b.date - a.date);
            
            communicationTableBody.innerHTML = sortedCommunications.map(comm => {
                const dateStr = comm.date.toLocaleDateString('ms-MY');
                
                // Get status badge
                const getStatusBadge = () => {
                    switch(comm.status) {
                        case 'unread': return '<span class="status-badge status-unread">Belum Dibaca</span>';
                        case 'read': return '<span class="status-badge status-read">Telah Dibaca</span>';
                        case 'replied': return '<span class="status-badge status-replied">Telah Dibalas</span>';
                        case 'pending': return '<span class="status-badge status-pending">Belum Dibalas</span>';
                        case 'sent': return '<span class="status-badge status-read">Telah Dihantar</span>';
                        default: return '';
                    }
                };
                
                // Get type badge
                const getTypeBadge = () => {
                    switch(comm.type) {
                        case 'message': return '<span class="type-badge type-message">Mesej</span>';
                        case 'announcement': return '<span class="type-badge type-announcement">Pengumuman</span>';
                        case 'meeting': return '<span class="type-badge type-meeting">Mesyuarat</span>';
                        case 'report': return '<span class="type-badge type-report">Laporan</span>';
                        default: return '';
                    }
                };
                
                return `
                    <tr>
                        <td>${comm.recipient}</td>
                        <td>
                            <div style="font-weight: 600;">${comm.subject}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">${comm.type === 'message' ? 'Daripada: ' + comm.sender : ''}</div>
                        </td>
                        <td>${getTypeBadge()}</td>
                        <td>${dateStr}</td>
                        <td>${getStatusBadge()}</td>
                        <td>
                            <span class="${comm.priority === 'urgent' ? 'priority-high' : comm.priority === 'important' ? 'priority-medium' : 'priority-low'}" style="padding: 4px 10px; border-radius: 20px; font-size: 11px;">
                                ${comm.priority === 'urgent' ? 'Segera' : comm.priority === 'important' ? 'Penting' : 'Biasa'}
                            </span>
                        </td>
                        <td>
                            <button class="action-btn primary" onclick="lihatMesej('${comm.id}', '${comm.sender === 'Cikgu Ahmad' ? 'sent' : 'inbox'}')" style="padding: 6px 12px; font-size: 12px;">
                                <i class="fas fa-eye"></i>
                                Tindakan
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update communication statistics
        function updateCommunicationStatistics() {
            const unreadCount = sampleMessages.filter(msg => 
                msg.sender !== 'Cikgu Ahmad' && !msg.read
            ).length;
            
            const sentCount = sampleMessages.filter(msg => 
                msg.sender === 'Cikgu Ahmad'
            ).length;
            
            const announcementCount = sampleAnnouncements.length;
            const meetingCount = sampleMeetings.length;
            const parentsCount = sampleParents.length;
            
            document.getElementById('unreadCount').textContent = unreadCount;
            document.getElementById('sentCount').textContent = sentCount;
            document.getElementById('announcementCount').textContent = announcementCount;
            document.getElementById('meetingCount').textContent = meetingCount;
            document.getElementById('parentsCount').textContent = parentsCount;
        }

        // Get time ago
        function getTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / (1000 * 60));
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
            
            if (diffMins < 1) {
                return 'Baru sahaja';
            } else if (diffMins < 60) {
                return `${diffMins} minit lalu`;
            } else if (diffHours < 24) {
                return `${diffHours} jam lalu`;
            } else if (diffDays === 1) {
                return 'Semalam';
            } else if (diffDays < 7) {
                return `${diffDays} hari lalu`;
            } else {
                return date.toLocaleDateString('ms-MY', {
                    day: 'numeric',
                    month: 'short'
                });
            }
        }

        // Load student options
        function loadStudentOptions() {
            const messageStudent = document.getElementById('messageStudent');
            messageStudent.innerHTML = '<option value="">Pilih Pelajar</option>' +
                sampleParents.map(parent => 
                    `<option value="${parent.id}">${parent.studentName} (${parent.class}) - ${parent.name}</option>`
                ).join('');
        }

        // Change recipient type
        function ubahJenisPenerima() {
            const recipientType = document.getElementById('messageRecipientType').value;
            
            document.getElementById('singleRecipient').style.display = recipientType === 'single' ? 'block' : 'none';
            document.getElementById('classRecipient').style.display = recipientType === 'class' ? 'block' : 'none';
            document.getElementById('multipleRecipients').style.display = recipientType === 'multiple' ? 'block' : 'none';
            
            if (recipientType === 'multiple') {
                loadRecipientOptions();
            }
        }

        // Load recipient options
        function loadRecipientOptions() {
            const recipientSelection = document.querySelector('.recipient-selection');
            recipientSelection.innerHTML = sampleParents.map(parent => `
                <div class="recipient-option">
                    <input type="checkbox" id="recipient-${parent.id}" value="${parent.id}">
                    <div class="recipient-avatar">${parent.studentName.split(' ').map(n => n.charAt(0)).join('')}</div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; font-size: 13px;">${parent.studentName}</div>
                        <div style="font-size: 11px; color: var(--medium-gray);">${parent.class} - ${parent.name}</div>
                    </div>
                </div>
            `).join('');
        }

        // View message
        function lihatMesej(messageId, type) {
            const message = sampleMessages.find(msg => msg.id === messageId);
            if (!message) return;
            
            const dateStr = message.date.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Get student info if available
            let studentInfo = '';
            if (type === 'inbox') {
                const parent = sampleParents.find(p => p.name === message.sender);
                if (parent) {
                    studentInfo = `
                        <div style="margin-bottom: 20px;">
                            <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Maklumat Pelajar</div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="student-avatar" style="width: 40px; height: 40px;">${parent.studentName.split(' ').map(n => n.charAt(0)).join('')}</div>
                                <div>
                                    <div style="font-weight: 600;">${parent.studentName}</div>
                                    <div style="font-size: 13px; color: var(--medium-gray);">${parent.class}</div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Mark as read if it's an inbox message
            if (type === 'inbox' && !message.read) {
                message.read = true;
                updateCommunicationStatistics();
            }
            
            // Get status text
            const statusText = message.status === 'unread' ? 'Belum Dibaca' :
                             message.status === 'read' ? 'Telah Dibaca' :
                             message.status === 'replied' ? 'Telah Dibalas' :
                             message.status === 'pending' ? 'Belum Dibalas' : 'Telah Dihantar';
            
            // Get priority text
            const priorityText = message.priority === 'urgent' ? 'Segera' :
                               message.priority === 'important' ? 'Penting' : 'Biasa';
            
            // Get type text
            const typeText = message.type === 'message' ? 'Mesej' :
                           message.type === 'announcement' ? 'Pengumuman' :
                           message.type === 'meeting' ? 'Mesyuarat' : 'Laporan';
            
            let repliesSection = '';
            if (message.replies && message.replies.length > 0) {
                repliesSection = `
                    <div style="margin-top: 30px;">
                        <div style="font-weight: 600; color: var(--dark-gray); margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb;">Balasan</div>
                        ${message.replies.map(reply => {
                            const replyDate = reply.date.toLocaleDateString('ms-MY', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            
                            return `
                                <div style="background: ${reply.sender === 'Cikgu Ahmad' ? 'var(--primary-light)' : 'var(--light-gray)'}; padding: 15px; border-radius: 10px; margin-bottom: 10px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                        <div style="font-weight: 600;">${reply.sender}</div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">${replyDate}</div>
                                    </div>
                                    <div style="color: var(--dark-gray); line-height: 1.5;">${reply.content}</div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }
            
            messageDetails.innerHTML = `
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 10px; color: var(--dark-gray);">${message.subject}</h3>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px;">
                        <span class="type-badge ${message.type === 'message' ? 'type-message' : message.type === 'announcement' ? 'type-announcement' : message.type === 'meeting' ? 'type-meeting' : 'type-report'}">${typeText}</span>
                        <span style="background: ${message.priority === 'urgent' ? 'rgba(239, 68, 68, 0.1)' : message.priority === 'important' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)'}; color: ${message.priority === 'urgent' ? 'var(--danger)' : message.priority === 'important' ? 'var(--warning)' : 'var(--success)'}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${priorityText}</span>
                        <span style="background: var(--light-gray); color: var(--medium-gray); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">${statusText}</span>
                    </div>
                </div>
                
                ${studentInfo}
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">${type === 'inbox' ? 'Daripada' : 'Kepada'}</div>
                    <div style="color: var(--dark-gray);">${type === 'inbox' ? message.sender : message.recipient}</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Tarikh & Masa</div>
                    <div style="color: var(--dark-gray);">${dateStr}</div>
                </div>
                
                ${message.attachment ? `
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Lampiran</div>
                    <div style="display: flex; align-items: center; gap: 10px; background: var(--light-gray); padding: 10px; border-radius: 8px;">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                        <span style="color: var(--dark-gray);">${message.attachment}</span>
                        <button class="action-btn primary" style="margin-left: auto; padding: 5px 10px; font-size: 12px;">
                            <i class="fas fa-download"></i>
                            Muat Turun
                        </button>
                    </div>
                </div>
                ` : ''}
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Kandungan</div>
                    <div style="background: var(--light-gray); padding: 20px; border-radius: 10px; line-height: 1.6; color: var(--dark-gray);">
                        ${message.content}
                    </div>
                </div>
                
                ${repliesSection}
                
                ${type === 'inbox' ? `
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn success" onclick="balasMesej('${message.id}')" style="flex: 1;">
                        <i class="fas fa-reply"></i>
                        Balas Mesej
                    </button>
                    <button class="action-btn primary" onclick="mulakanChat('${message.senderId}')" style="flex: 1;">
                        <i class="fas fa-comments"></i>
                        Mulakan Chat
                    </button>
                </div>
                ` : ''}
            `;
            
            viewMessageModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Start chat
        function mulakanChat(parentId) {
            const parent = sampleParents.find(p => p.id === parentId);
            if (!parent) return;
            
            // Hide other tabs
            inboxTab.style.display = 'none';
            sentTab.style.display = 'none';
            announcementsTab.style.display = 'none';
            meetingsTab.style.display = 'none';
            
            // Show chat interface
            chatInterface.classList.add('active');
            chatInterface.style.display = 'block';
            
            // Load chat interface
            loadChatInterface(parent);
        }

        // Load chat interface
        function loadChatInterface(parent) {
            const messages = sampleMessages.filter(msg => 
                (msg.sender === parent.name && msg.recipient === 'Cikgu Ahmad') ||
                (msg.sender === 'Cikgu Ahmad' && msg.recipient === parent.name)
            );
            
            // Sort by date (oldest first)
            const sortedMessages = [...messages].sort((a, b) => a.date - b.date);
            
            const chatMessages = sortedMessages.flatMap(msg => {
                const msgItems = [{
                    sender: msg.sender === 'Cikgu Ahmad' ? 'sent' : 'received',
                    content: msg.content,
                    time: msg.date.toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' })
                }];
                
                // Add replies if any
                if (msg.replies && msg.replies.length > 0) {
                    msg.replies.forEach(reply => {
                        msgItems.push({
                            sender: reply.sender === 'Cikgu Ahmad' ? 'sent' : 'received',
                            content: reply.content,
                            time: reply.date.toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' })
                        });
                    });
                }
                
                return msgItems;
            });
            
            const messagesHTML = chatMessages.map(msg => `
                <div class="message ${msg.sender}">
                    <div class="message-content">${msg.content}</div>
                    <div class="message-time">${msg.time}</div>
                </div>
            `).join('');
            
            chatInterface.innerHTML = `
                <div class="chat-header">
                    <button class="chat-back" onclick="tutupChat()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="chat-user">
                        <div class="chat-user-avatar">${parent.studentName.split(' ').map(n => n.charAt(0)).join('')}</div>
                        <div class="chat-user-info">
                            <h4>${parent.name}</h4>
                            <p>${parent.studentName} (${parent.class})</p>
                        </div>
                    </div>
                    <div class="chat-status">
                        <i class="fas fa-circle" style="font-size: 8px;"></i> Online
                    </div>
                </div>
                
                <div class="chat-messages" id="chatMessages">
                    ${messagesHTML}
                </div>
                
                <div class="chat-input">
                    <textarea id="chatInput" placeholder="Tulis mesej anda di sini..." onkeydown="if(event.key === 'Enter' && !event.shiftKey) { hantarChat('${parent.id}'); event.preventDefault(); }"></textarea>
                    <div class="chat-actions">
                        <button class="action-btn primary" onclick="hantarChat('${parent.id}')">
                            <i class="fas fa-paper-plane"></i>
                            Hantar
                        </button>
                        <button class="action-btn secondary">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Scroll to bottom of chat
            const chatMessagesContainer = document.getElementById('chatMessages');
            if (chatMessagesContainer) {
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            }
        }

        // Send chat message
        function hantarChat(parentId) {
            const chatInput = document.getElementById('chatInput');
            const message = chatInput.value.trim();
            
            if (!message) return;
            
            const parent = sampleParents.find(p => p.id === parentId);
            if (!parent) return;
            
            // Add message to chat
            const chatMessages = document.getElementById('chatMessages');
            const time = new Date().toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' });
            
            const messageElement = document.createElement('div');
            messageElement.className = 'message sent';
            messageElement.innerHTML = `
                <div class="message-content">${message}</div>
                <div class="message-time">${time}</div>
            `;
            
            chatMessages.appendChild(messageElement);
            chatInput.value = '';
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Simulate reply after 1 second
            setTimeout(() => {
                const replies = [
                    "Terima kasih Cikgu.",
                    "Saya faham.",
                    "Baik, akan saya ikut nasihat Cikgu.",
                    "Terima kasih atas maklumat.",
                    "Saya setuju dengan cadangan Cikgu."
                ];
                
                const randomReply = replies[Math.floor(Math.random() * replies.length)];
                const replyTime = new Date().toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' });
                
                const replyElement = document.createElement('div');
                replyElement.className = 'message received';
                replyElement.innerHTML = `
                    <div class="message-content">${randomReply}</div>
                    <div class="message-time">${replyTime}</div>
                `;
                
                chatMessages.appendChild(replyElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);
        }

        // Close chat
        function tutupChat() {
            chatInterface.classList.remove('active');
            chatInterface.style.display = 'none';
            inboxTab.style.display = 'block';
        }

        // Reply to message
        function balasMesej(messageId) {
            const message = sampleMessages.find(msg => msg.id === messageId);
            if (!message) return;
            
            // Set up reply in new message modal
            document.getElementById('messageRecipientType').value = 'single';
            ubahJenisPenerima();
            
            const parent = sampleParents.find(p => p.name === message.sender);
            if (parent) {
                document.getElementById('messageStudent').value = parent.id;
            }
            
            document.getElementById('messageSubject').value = `Re: ${message.subject}`;
            document.getElementById('messageType').value = 'message';
            document.getElementById('messagePriority').value = 'normal';
            
            // Show modal
            newMessageModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // View status
        function lihatStatus(messageId) {
            const message = sampleMessages.find(msg => msg.id === messageId);
            if (!message) return;
            
            const details = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Status Penghantaran</h3>
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 5px;">Subjek</div>
                    <div style="color: var(--dark-gray);">${message.subject}</div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Status Penghantaran</div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">Dihantar</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${message.date.toLocaleDateString('ms-MY', { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">Diterima oleh Sistem</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${new Date(message.date.getTime() + 300000).toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            <div style="flex: 1;">
                                <div style="font-weight: 600;">Dibaca oleh Penerima</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${message.read ? new Date(message.date.getTime() + 3600000).toLocaleDateString('ms-MY', { hour: '2-digit', minute: '2-digit' }) : 'Belum dibaca'}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Statistik</div>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                        <div style="text-align: center; background: var(--light-gray); padding: 15px; border-radius: 10px;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--primary);">1</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Penerima</div>
                        </div>
                        <div style="text-align: center; background: var(--light-gray); padding: 15px; border-radius: 10px;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--success);">${message.read ? '1' : '0'}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Telah Dibaca</div>
                        </div>
                        <div style="text-align: center; background: var(--light-gray); padding: 15px; border-radius: 10px;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--info);">${message.replies ? message.replies.length : '0'}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Balasan</div>
                        </div>
                        <div style="text-align: center; background: var(--light-gray); padding: 15px; border-radius: 10px;">
                            <div style="font-size: 24px; font-weight: 800; color: var(--warning);">100%</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Kejayaan</div>
                        </div>
                    </div>
                </div>
            `;
            
            showNotification('Status Penghantaran', 'info', details);
        }

        // Delete message
        function padamMesej(messageId) {
            if (confirm('Adakah anda pasti ingin memadam mesej ini?')) {
                // In a real app, this would remove from database
                showNotification('Mesej berjaya dipadam', 'success');
                
                // Reload messages
                loadInboxMessages();
                loadSentMessages();
                loadCommunicationTable();
                updateCommunicationStatistics();
            }
        }

        // Send new message modal
        function hantarMesejModal() {
            // Reset form
            document.getElementById('messageForm').reset();
            document.getElementById('messageRecipientType').value = 'single';
            ubahJenisPenerima();
            
            // Set default date
            const now = new Date();
            document.getElementById('messageDate').value = now.toISOString().split('T')[0];
            
            // Show modal
            newMessageModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function tutupModal() {
            newMessageModal.classList.remove('active');
            viewMessageModal.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('messageForm').reset();
        }

        // Send message
        function hantarMesej(event) {
            event.preventDefault();
            
            // Get form values
            const recipientType = document.getElementById('messageRecipientType').value;
            const subject = document.getElementById('messageSubject').value;
            const messageType = document.getElementById('messageType').value;
            const priority = document.getElementById('messagePriority').value;
            const content = document.getElementById('messageContent').value;
            
            // Determine recipient
            let recipient = '';
            if (recipientType === 'single') {
                const studentId = document.getElementById('messageStudent').value;
                const parent = sampleParents.find(p => p.id === studentId);
                recipient = parent ? parent.name : 'Ibu Bapa';
            } else if (recipientType === 'class') {
                const className = document.getElementById('messageClass').value;
                recipient = className === 'all' ? 'Semua Ibu Bapa' : `Semua Ibu Bapa Kelas ${className}`;
            } else if (recipientType === 'all') {
                recipient = 'Semua Ibu Bapa';
            } else if (recipientType === 'multiple') {
                recipient = 'Pelbagai Penerima';
            }
            
            // Create new message
            const newMessage = {
                id: 'MSG' + (sampleMessages.length + 1).toString().padStart(3, '0'),
                type: messageType,
                sender: 'Cikgu Ahmad',
                recipient: recipient,
                subject: subject,
                content: content,
                date: new Date(),
                status: 'sent',
                priority: priority,
                read: false,
                replies: []
            };
            
            // Add to sample data
            sampleMessages.push(newMessage);
            
            // Close modal
            tutupModal();
            
            // Update UI
            loadSentMessages();
            loadCommunicationTable();
            updateCommunicationStatistics();
            
            // Show success message
            showNotification('Mesej berjaya dihantar', 'success');
        }

        // Create announcement modal
        function buatPengumumanModal() {
            showNotification('Buat Pengumuman', 'info', 'Fitur buat pengumuman akan dibuka dalam modal berasingan');
        }

        // Edit announcement
        function editPengumuman(announcementId) {
            showNotification('Kemaskini Pengumuman', 'info', 'Fitur kemaskini pengumuman akan dibuka');
        }

        // Delete announcement
        function padamPengumuman(announcementId) {
            if (confirm('Adakah anda pasti ingin memadam pengumuman ini?')) {
                showNotification('Pengumuman berjaya dipadam', 'success');
            }
        }

        // View meeting
        function lihatMesyuarat(meetingId) {
            const meeting = sampleMeetings.find(m => m.id === meetingId);
            if (!meeting) return;
            
            const dateStr = meeting.date.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const details = `
                <h3 style="margin-bottom: 15px; color: var(--dark-gray);">${meeting.title}</h3>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                        <span style="background: var(--primary-light); color: var(--primary); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-calendar"></i> ${dateStr}
                        </span>
                        <span style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-map-marker-alt"></i> ${meeting.location}
                        </span>
                        <span style="background: var(--light-gray); color: var(--medium-gray); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                            <i class="fas fa-users"></i> ${meeting.class === 'all' ? 'Semua Kelas' : meeting.class}
                        </span>
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Agenda</div>
                    <div style="background: var(--light-gray); padding: 15px; border-radius: 10px; color: var(--dark-gray); line-height: 1.6;">
                        ${meeting.agenda}
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <div style="font-weight: 600; color: var(--medium-gray); margin-bottom: 10px;">Kehadiran</div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="text-align: center;">
                            <div style="font-size: 32px; font-weight: 800; color: var(--success);">${meeting.attendance.confirmed}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Disahkan</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 32px; font-weight: 800; color: var(--warning);">${meeting.attendance.pending}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Belum Pasti</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 32px; font-weight: 800; color: var(--danger);">${meeting.attendance.total - meeting.attendance.confirmed - meeting.attendance.pending}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Tidak Hadir</div>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button class="action-btn success" onclick="hantarPeringatanMesyuarat('${meeting.id}')" style="flex: 1;">
                        <i class="fas fa-bell"></i>
                        Hantar Peringatan
                    </button>
                    <button class="action-btn primary" onclick="kemaskiniMesyuarat('${meeting.id}')" style="flex: 1;">
                        <i class="fas fa-edit"></i>
                        Kemaskini
                    </button>
                </div>
            `;
            
            showNotification('Butiran Mesyuarat', 'info', details);
        }

        // Update meeting
        function kemaskiniMesyuarat(meetingId) {
            showNotification('Kemaskini Mesyuarat', 'info', 'Fitur kemaskini mesyuarat akan dibuka');
        }

        // Cancel meeting
        function batalkanMesyuarat(meetingId) {
            if (confirm('Adakah anda pasti ingin membatalkan mesyuarat ini?')) {
                showNotification('Mesyuarat berjaya dibatalkan', 'success');
            }
        }

        // Schedule meeting modal
        function jadualkanMesyuaratModal() {
            showNotification('Jadual Mesyuarat', 'info', 'Fitur jadual mesyuarat akan dibuka dalam modal berasingan');
        }

        // Send meeting reminder
        function hantarPeringatanMesyuarat(meetingId) {
            showNotification('Peringatan Mesyuarat', 'info', 'Peringatan akan dihantar kepada semua ibu bapa yang hadir');
        }

        // Filter communication
        function tapilkanKomunikasi() {
            const classFilter = document.getElementById('filterClass').value;
            const typeFilter = document.getElementById('filterType').value;
            const statusFilter = document.getElementById('filterStatus').value;
            const dateFilter = document.getElementById('filterDate').value;
            
            // In a real app, this would filter data from server
            showNotification('Komunikasi ditapis', 'info');
            
            // Reload based on active tab
            const activeTab = document.querySelector('.tab-btn.active').textContent;
            if (activeTab.includes('Kotak')) {
                loadInboxMessages();
            } else if (activeTab.includes('Dihantar')) {
                loadSentMessages();
            }
            
            loadCommunicationTable();
        }

        // Reload communication
        function muatSemulaKomunikasi() {
            showNotification('Muat semula komunikasi...', 'info');
            
            // Simulate reload
            setTimeout(() => {
                loadInboxMessages();
                loadSentMessages();
                loadAnnouncements();
                loadMeetings();
                loadCommunicationTable();
                updateCommunicationStatistics();
                
                showNotification('Komunikasi berjaya dimuat semula', 'success');
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
                animation: fadeIn 0.3s ease;
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
                notification.style.animation = 'fadeOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes fadeOut {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-10px);
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