<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Ujian - SlipKu</title>
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

        /* Calendar Section */
        .calendar-section {
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

        .calendar-day.has-exam .calendar-day-number {
            color: var(--danger);
            position: relative;
        }

        .calendar-day.has-exam .calendar-day-number::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 50%;
        }

        /* Exam Events */
        .exam-events {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .exam-event {
            background: var(--primary-light);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            color: var(--dark-gray);
            border-left: 4px solid var(--primary);
            transition: var(--transition);
            cursor: pointer;
        }

        .exam-event:hover {
            background: var(--primary);
            color: white;
            transform: translateX(2px);
        }

        .exam-event.exam {
            border-left-color: var(--danger);
            background: rgba(239, 68, 68, 0.1);
        }

        .exam-event.exam:hover {
            background: var(--danger);
        }

        .exam-event.quiz {
            border-left-color: var(--warning);
            background: rgba(245, 158, 11, 0.1);
        }

        .exam-event.quiz:hover {
            background: var(--warning);
        }

        .exam-event.midterm {
            border-left-color: var(--info);
            background: rgba(59, 130, 246, 0.1);
        }

        .exam-event.midterm:hover {
            background: var(--info);
        }

        /* Exam List Section */
        .exam-list-section {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .exam-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Exam Cards */
        .exam-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .exam-card {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
            transition: var(--transition);
            position: relative;
        }

        .exam-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .exam-card.upcoming {
            border-left: 5px solid var(--primary);
        }

        .exam-card.ongoing {
            border-left: 5px solid var(--warning);
        }

        .exam-card.completed {
            border-left: 5px solid var(--success);
        }

        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .exam-title h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }

        .exam-type {
            font-size: 12px;
            color: var(--medium-gray);
            background: var(--light-gray);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .exam-date {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--medium-gray);
            font-size: 13px;
            background: var(--light-gray);
            padding: 6px 12px;
            border-radius: 20px;
        }

        .exam-details {
            margin: 15px 0;
        }

        .exam-detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .exam-detail-item i {
            width: 16px;
            color: var(--primary);
        }

        .exam-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        /* Countdown Timer */
        .countdown-timer {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary-light), #e0e7ff);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .countdown-timer i {
            font-size: 24px;
            color: var(--primary);
        }

        .countdown-text h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .countdown-text p {
            font-size: 12px;
            color: var(--medium-gray);
        }

        .countdown-time {
            margin-left: auto;
            font-weight: 700;
            color: var(--danger);
            font-size: 18px;
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

        /* Exam Statistics */
        .exam-stats {
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
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        .stat-icon.upcoming {
            background: linear-gradient(135deg, var(--info), #60a5fa);
        }

        .stat-icon.ongoing {
            background: linear-gradient(135deg, var(--warning), #fbbf24);
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, var(--success), #34d399);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
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
            
            .exam-cards {
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
            
            .exam-cards {
                grid-template-columns: 1fr;
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
            
            .exam-stats {
                grid-template-columns: repeat(2, 1fr);
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
            
            .exam-event {
                padding: 4px 6px;
                font-size: 10px;
            }
            
            .exam-stats {
                grid-template-columns: 1fr;
            }
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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
    </style>
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Add Exam Modal -->
    <div class="modal" id="addExamModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Tambah Ujian Baharu</div>
                <button class="modal-close" onclick="tutupModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="examForm" onsubmit="tambahUjian(event)">
                <div class="form-group">
                    <label class="form-label">Nama Ujian</label>
                    <input type="text" class="form-input" id="examName" placeholder="Contoh: Ujian Matematik Pertengahan Tahun" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jenis Ujian</label>
                        <select class="form-select" id="examType" required>
                            <option value="">Pilih Jenis</option>
                            <option value="exam">Peperiksaan</option>
                            <option value="quiz">Kuiz</option>
                            <option value="midterm">Ujian Pertengahan</option>
                            <option value="assignment">Tugasan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Subjek</label>
                        <select class="form-select" id="examSubject" required>
                            <option value="">Pilih Subjek</option>
                            <option value="matematik">Matematik</option>
                            <option value="sains">Sains</option>
                            <option value="bm">Bahasa Melayu</option>
                            <option value="bi">Bahasa Inggeris</option>
                            <option value="pj">PJ & Kesihatan</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tarikh</label>
                        <input type="date" class="form-input" id="examDate" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Masa</label>
                        <input type="time" class="form-input" id="examTime" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tempoh (minit)</label>
                        <input type="number" class="form-input" id="examDuration" placeholder="120" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kelas</label>
                        <select class="form-select" id="examClass" required>
                            <option value="">Pilih Kelas</option>
                            <option value="6A">Kelas 6A</option>
                            <option value="6B">Kelas 6B</option>
                            <option value="5A">Kelas 5A</option>
                            <option value="5B">Kelas 5B</option>
                            <option value="all">Semua Kelas</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Butiran Ujian</label>
                    <textarea class="form-input" id="examDetails" rows="3" placeholder="Butiran ujian, topik yang diuji, arahan khas..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Lokasi/Bilik</label>
                    <input type="text" class="form-input" id="examLocation" placeholder="Contoh: Bilik 6A, Makmal Sains">
                </div>
                
                <div class="form-group" style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Simpan Ujian
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="tutupModal()" style="flex: 1;">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                </div>
            </form>
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
                    <p>Jadual Ujian</p>
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
            <a href="laporan-prestasi.html" class="sidebar-item">
                <i class="fas fa-chart-bar"></i>
                Laporan Prestasi
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Pengurusan</div>
            <a href="jadual-ujian.html" class="sidebar-item active">
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
                <h2>Jadual Ujian 📅</h2>
                <p>Pengurusan dan jadual peperiksaan pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaJadual()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahUjianModal()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Ujian
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="ubahTab('calendar')">
                <i class="fas fa-calendar-alt"></i>
                Kalendar
            </button>
            <button class="tab-btn" onclick="ubahTab('upcoming')">
                <i class="fas fa-clock"></i>
                Akan Datang
            </button>
            <button class="tab-btn" onclick="ubahTab('all')">
                <i class="fas fa-list"></i>
                Semua Ujian
            </button>
        </div>

        <!-- Countdown Timer -->
        <div class="countdown-timer">
            <i class="fas fa-hourglass-half"></i>
            <div class="countdown-text">
                <h4>Ujian Seterusnya: Matematik Pertengahan Tahun</h4>
                <p>Kelas 6A | 15 Mac 2024 | 9:00 AM</p>
            </div>
            <div class="countdown-time" id="countdownTimer">7:12:45</div>
        </div>

        <!-- Exam Statistics -->
        <div class="exam-stats">
            <div class="stat-card">
                <div class="stat-icon upcoming">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value" id="upcomingCount">8</div>
                <div class="stat-label">Akan Datang</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon ongoing">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-value" id="ongoingCount">2</div>
                <div class="stat-label">Sedang Berlangsung</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon completed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value" id="completedCount">15</div>
                <div class="stat-label">Telah Selesai</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value" id="totalCount">25</div>
                <div class="stat-label">Jumlah Ujian</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Tapis Jadual
            </div>
            
            <div class="filter-options">
                <div class="filter-group">
                    <label class="filter-label">Kelas:</label>
                    <select class="filter-select" id="filterClass" onchange="tapilkanJadual()">
                        <option value="all">Semua Kelas</option>
                        <option value="6A">Kelas 6A</option>
                        <option value="6B">Kelas 6B</option>
                        <option value="5A">Kelas 5A</option>
                        <option value="5B">Kelas 5B</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Jenis Ujian:</label>
                    <select class="filter-select" id="filterType" onchange="tapilkanJadual()">
                        <option value="all">Semua Jenis</option>
                        <option value="exam">Peperiksaan</option>
                        <option value="quiz">Kuiz</option>
                        <option value="midterm">Ujian Pertengahan</option>
                        <option value="assignment">Tugasan</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Subjek:</label>
                    <select class="filter-select" id="filterSubject" onchange="tapilkanJadual()">
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
                    <select class="filter-select" id="filterStatus" onchange="tapilkanJadual()">
                        <option value="all">Semua Status</option>
                        <option value="upcoming">Akan Datang</option>
                        <option value="ongoing">Sedang Berlangsung</option>
                        <option value="completed">Telah Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Calendar Tab -->
        <div id="calendarTab">
            <div class="calendar-section">
                <div class="calendar-header">
                    <div class="calendar-title">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Kalendar Ujian</span>
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

        <!-- Upcoming Exams Tab -->
        <div id="upcomingTab" style="display: none;">
            <div class="exam-list-section">
                <div class="exam-list-header">
                    <div class="calendar-title">
                        <i class="fas fa-clock"></i>
                        <span>Ujian Akan Datang (7 Hari)</span>
                    </div>
                    <div class="calendar-actions">
                        <button class="action-btn primary" onclick="hantarPeringatan()">
                            <i class="fas fa-bell"></i>
                            Hantar Peringatan
                        </button>
                    </div>
                </div>
                
                <div class="exam-cards" id="upcomingExams">
                    <!-- Upcoming exams will be loaded here -->
                </div>
            </div>
        </div>

        <!-- All Exams Tab -->
        <div id="allTab" style="display: none;">
            <div class="exam-list-section">
                <div class="exam-list-header">
                    <div class="calendar-title">
                        <i class="fas fa-list"></i>
                        <span>Semua Ujian</span>
                    </div>
                    <div class="calendar-actions">
                        <button class="action-btn success" onclick="eksportJadual()">
                            <i class="fas fa-file-export"></i>
                            Eksport Jadual
                        </button>
                    </div>
                </div>
                
                <div class="exam-cards" id="allExams">
                    <!-- All exams will be loaded here -->
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
        const calendarTab = document.getElementById('calendarTab');
        const upcomingTab = document.getElementById('upcomingTab');
        const allTab = document.getElementById('allTab');
        const calendarGrid = document.getElementById('calendarGrid');
        const upcomingExams = document.getElementById('upcomingExams');
        const allExams = document.getElementById('allExams');
        const addExamModal = document.getElementById('addExamModal');
        const currentMonthElement = document.getElementById('currentMonth');

        // Calendar state
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        // Sample exam data
        const sampleExams = [
            {
                id: 'EXAM001',
                name: 'Ujian Matematik Pertengahan Tahun',
                type: 'midterm',
                subject: 'matematik',
                date: new Date(2024, 2, 15), // 15 March 2024
                time: '09:00',
                duration: 120,
                class: '6A',
                location: 'Bilik 6A',
                details: 'Topik: Pecahan, Perpuluhan, Peratusan. Bawa kalkulator.',
                status: 'upcoming'
            },
            {
                id: 'EXAM002',
                name: 'Kuiz Sains Bab 5',
                type: 'quiz',
                subject: 'sains',
                date: new Date(2024, 2, 18), // 18 March 2024
                time: '10:30',
                duration: 45,
                class: '6A',
                location: 'Makmal Sains',
                details: 'Tenaga dan Perubahan Bentuk Tenaga',
                status: 'upcoming'
            },
            {
                id: 'EXAM003',
                name: 'Peperiksaan Bahasa Melayu',
                type: 'exam',
                subject: 'bm',
                date: new Date(2024, 2, 22), // 22 March 2024
                time: '08:00',
                duration: 150,
                class: 'all',
                location: 'Dewan Sekolah',
                details: 'Kertas 1: Pemahaman, Kertas 2: Penulisan',
                status: 'upcoming'
            },
            {
                id: 'EXAM004',
                name: 'Ujian Bahasa Inggeris',
                type: 'exam',
                subject: 'bi',
                date: new Date(2024, 2, 25), // 25 March 2024
                time: '11:00',
                duration: 90,
                class: '6A',
                location: 'Bilik 6A',
                details: 'Reading Comprehension and Essay Writing',
                status: 'upcoming'
            },
            {
                id: 'EXAM005',
                name: 'Kuiz PJ Kesihatan',
                type: 'quiz',
                subject: 'pj',
                date: new Date(2024, 2, 28), // 28 March 2024
                time: '14:00',
                duration: 60,
                class: '6A',
                location: 'Gimnasium',
                details: 'Kesihatan Fizikal dan Mental',
                status: 'upcoming'
            },
            {
                id: 'EXAM006',
                name: 'Tugasan Matematik',
                type: 'assignment',
                subject: 'matematik',
                date: new Date(2024, 2, 30), // 30 March 2024
                time: '23:59',
                duration: 0,
                class: '6A',
                location: 'Online',
                details: 'Hantar melalui Google Classroom',
                status: 'upcoming'
            },
            {
                id: 'EXAM007',
                name: 'Ujian Sains Bab 6',
                type: 'exam',
                subject: 'sains',
                date: new Date(2024, 1, 20), // 20 February 2024
                time: '09:00',
                duration: 90,
                class: '6A',
                location: 'Makmal Sains',
                details: 'Pembiakan Tumbuhan',
                status: 'completed'
            },
            {
                id: 'EXAM008',
                name: 'Kuiz Matematik',
                type: 'quiz',
                subject: 'matematik',
                date: new Date(2024, 1, 15), // 15 February 2024
                time: '10:00',
                duration: 45,
                class: '6A',
                location: 'Bilik 6A',
                details: 'Geometri Asas',
                status: 'completed'
            },
            {
                id: 'EXAM009',
                name: 'Peperiksaan Percubaan Matematik',
                type: 'exam',
                subject: 'matematik',
                date: new Date(2024, 0, 25), // 25 January 2024
                time: '08:30',
                duration: 180,
                class: '6A',
                location: 'Dewan Sekolah',
                details: 'Percubaan UPSR Format Baharu',
                status: 'completed'
            },
            {
                id: 'EXAM010',
                name: 'Ujian Lisan Bahasa Melayu',
                type: 'exam',
                subject: 'bm',
                date: new Date(2024, 0, 18), // 18 January 2024
                time: '13:00',
                duration: 60,
                class: '6A',
                location: 'Bilik Guru Bahasa',
                details: 'Ujian Lisan Bersemuka',
                status: 'completed'
            }
        ];

        // Initialize page
        function initializePage() {
            // Set up event listeners
            setupEventListeners();
            
            // Initialize calendar
            generateCalendar(currentMonth, currentYear);
            
            // Load exam data
            loadUpcomingExams();
            loadAllExams();
            
            // Update statistics
            updateExamStatistics();
            
            // Start countdown timer
            startCountdownTimer();
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
            addExamModal.addEventListener('click', function(e) {
                if (e.target === addExamModal) {
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
            
            // Show active tab content
            calendarTab.style.display = 'none';
            upcomingTab.style.display = 'none';
            allTab.style.display = 'none';
            
            if (tab === 'calendar') {
                calendarTab.style.display = 'block';
            } else if (tab === 'upcoming') {
                upcomingTab.style.display = 'block';
            } else if (tab === 'all') {
                allTab.style.display = 'block';
            }
        }

        // Generate calendar
        function generateCalendar(month, year) {
            const monthNames = [
                "Januari", "Februari", "Mac", "April", "Mei", "Jun",
                "Julai", "Ogos", "September", "Oktober", "November", "Disember"
            ];
            
            // Update month display
            currentMonthElement.textContent = `${monthNames[month]} ${year}`;
            
            // Clear calendar
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
                
                // Check for exams on this day
                const currentDate = new Date(year, month, day);
                const examsOnDay = sampleExams.filter(exam => {
                    return exam.date.getDate() === day && 
                           exam.date.getMonth() === month && 
                           exam.date.getFullYear() === year;
                });
                
                // Mark day if has exams
                if (examsOnDay.length > 0) {
                    dayElement.classList.add('has-exam');
                    
                    // Add exam events
                    const examEvents = document.createElement('div');
                    examEvents.className = 'exam-events';
                    
                    examsOnDay.forEach(exam => {
                        const examEvent = document.createElement('div');
                        examEvent.className = `exam-event ${exam.type}`;
                        examEvent.textContent = `${getSubjectName(exam.subject)} ${exam.type === 'exam' ? '📝' : exam.type === 'quiz' ? '📋' : '📚'}`;
                        examEvent.title = `${exam.name}\n${exam.time} | ${exam.class}`;
                        examEvent.onclick = () => lihatButiranUjian(exam.id);
                        examEvents.appendChild(examEvent);
                    });
                    
                    dayElement.appendChild(examEvents);
                }
                
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
            generateCalendar(currentMonth, currentYear);
            showNotification('Kembali ke hari ini', 'info');
        }

        // Load upcoming exams
        function loadUpcomingExams() {
            // Get next 7 days
            const today = new Date();
            const nextWeek = new Date();
            nextWeek.setDate(today.getDate() + 7);
            
            // Filter upcoming exams
            const upcoming = sampleExams.filter(exam => {
                return exam.date >= today && exam.date <= nextWeek;
            }).sort((a, b) => a.date - b.date);
            
            // Display upcoming exams
            if (upcoming.length === 0) {
                upcomingExams.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Tiada Ujian Akan Datang</h3>
                        <p>Tiada ujian dijadualkan untuk 7 hari akan datang</p>
                    </div>
                `;
                return;
            }
            
            upcomingExams.innerHTML = upcoming.map(exam => createExamCard(exam)).join('');
        }

        // Load all exams
        function loadAllExams() {
            // Sort exams by date (newest first)
            const allExamsSorted = [...sampleExams].sort((a, b) => b.date - a.date);
            
            // Display all exams
            if (allExamsSorted.length === 0) {
                allExams.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Tiada Ujian Dijadualkan</h3>
                        <p>Tambahkan ujian baharu untuk bermula</p>
                    </div>
                `;
                return;
            }
            
            allExams.innerHTML = allExamsSorted.map(exam => createExamCard(exam)).join('');
        }

        // Create exam card
        function createExamCard(exam) {
            // Format date
            const dateStr = exam.date.toLocaleDateString('ms-MY', {
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            
            // Get type name
            const typeNames = {
                'exam': 'Peperiksaan',
                'quiz': 'Kuiz',
                'midterm': 'Ujian Pertengahan',
                'assignment': 'Tugasan'
            };
            
            // Get subject name
            const subjectName = getSubjectName(exam.subject);
            
            // Get status class and text
            let statusClass, statusText;
            if (exam.status === 'upcoming') {
                statusClass = 'upcoming';
                statusText = 'Akan Datang';
            } else if (exam.status === 'ongoing') {
                statusClass = 'ongoing';
                statusText = 'Sedang Berlangsung';
            } else {
                statusClass = 'completed';
                statusText = 'Telah Selesai';
            }
            
            // Get type icon
            const typeIcon = exam.type === 'exam' ? 'fa-file-alt' :
                            exam.type === 'quiz' ? 'fa-question-circle' :
                            exam.type === 'midterm' ? 'fa-clipboard-list' : 'fa-tasks';
            
            return `
                <div class="exam-card ${statusClass}">
                    <div class="exam-card-header">
                        <div class="exam-title">
                            <h4>${exam.name}</h4>
                            <span class="exam-type">${typeNames[exam.type]}</span>
                        </div>
                        <div class="exam-date">
                            <i class="fas fa-calendar-day"></i>
                            <span>${dateStr}</span>
                        </div>
                    </div>
                    
                    <div class="exam-details">
                        <div class="exam-detail-item">
                            <i class="fas fa-book"></i>
                            <span>${subjectName}</span>
                        </div>
                        <div class="exam-detail-item">
                            <i class="fas fa-clock"></i>
                            <span>${exam.time} | ${exam.duration} minit</span>
                        </div>
                        <div class="exam-detail-item">
                            <i class="fas fa-users"></i>
                            <span>${exam.class === 'all' ? 'Semua Kelas' : 'Kelas ' + exam.class}</span>
                        </div>
                        <div class="exam-detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${exam.location}</span>
                        </div>
                    </div>
                    
                    <div class="exam-actions">
                        <button class="action-btn info" onclick="lihatButiranUjian('${exam.id}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                        <button class="action-btn warning" onclick="kemaskiniUjian('${exam.id}')">
                            <i class="fas fa-edit"></i>
                            Kemaskini
                        </button>
                        <button class="action-btn danger" onclick="padamUjian('${exam.id}')">
                            <i class="fas fa-trash"></i>
                            Padam
                        </button>
                    </div>
                </div>
            `;
        }

        // Get subject name
        function getSubjectName(subject) {
            const subjectNames = {
                'matematik': 'Matematik',
                'sains': 'Sains',
                'bm': 'Bahasa Melayu',
                'bi': 'Bahasa Inggeris',
                'pj': 'PJ & Kesihatan'
            };
            return subjectNames[subject] || subject;
        }

        // Filter schedule
        function tapilkanJadual() {
            const classFilter = document.getElementById('filterClass').value;
            const typeFilter = document.getElementById('filterType').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            const statusFilter = document.getElementById('filterStatus').value;
            
            // Filter exams
            const filteredExams = sampleExams.filter(exam => {
                if (classFilter !== 'all' && exam.class !== classFilter && exam.class !== 'all') return false;
                if (typeFilter !== 'all' && exam.type !== typeFilter) return false;
                if (subjectFilter !== 'all' && exam.subject !== subjectFilter) return false;
                if (statusFilter !== 'all' && exam.status !== statusFilter) return false;
                return true;
            });
            
            // Update display based on active tab
            const activeTab = document.querySelector('.tab-btn.active').textContent;
            if (activeTab.includes('Kalendar')) {
                // For calendar view, we need to regenerate with filtered data
                // This is simplified - in real app you would filter events
                showNotification('Tapis dikenakan pada kalendar', 'info');
            } else if (activeTab.includes('Akan Datang')) {
                displayFilteredExams(filteredExams.filter(exam => exam.status === 'upcoming'), 'upcomingExams');
            } else {
                displayFilteredExams(filteredExams, 'allExams');
            }
        }

        // Display filtered exams
        function displayFilteredExams(exams, containerId) {
            const container = document.getElementById(containerId);
            
            if (exams.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>Tiada Ujian Ditemui</h3>
                        <p>Cuba ubah tetapan tapis anda</p>
                    </div>
                `;
                return;
            }
            
            // Sort by date
            const sortedExams = [...exams].sort((a, b) => a.date - b.date);
            container.innerHTML = sortedExams.map(exam => createExamCard(exam)).join('');
        }

        // Update exam statistics
        function updateExamStatistics() {
            const upcomingCount = sampleExams.filter(exam => exam.status === 'upcoming').length;
            const ongoingCount = sampleExams.filter(exam => exam.status === 'ongoing').length;
            const completedCount = sampleExams.filter(exam => exam.status === 'completed').length;
            const totalCount = sampleExams.length;
            
            document.getElementById('upcomingCount').textContent = upcomingCount;
            document.getElementById('ongoingCount').textContent = ongoingCount;
            document.getElementById('completedCount').textContent = completedCount;
            document.getElementById('totalCount').textContent = totalCount;
        }

        // Show exam details
        function lihatButiranUjian(examId) {
            const exam = sampleExams.find(e => e.id === examId);
            if (!exam) return;
            
            const dateStr = exam.date.toLocaleDateString('ms-MY', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const typeNames = {
                'exam': 'Peperiksaan',
                'quiz': 'Kuiz',
                'midterm': 'Ujian Pertengahan',
                'assignment': 'Tugasan'
            };
            
            const subjectName = getSubjectName(exam.subject);
            
            let statusText = '';
            if (exam.status === 'upcoming') {
                statusText = 'Akan Datang';
            } else if (exam.status === 'ongoing') {
                statusText = 'Sedang Berlangsung';
            } else {
                statusText = 'Telah Selesai';
            }
            
            const details = `
                <strong>Nama Ujian:</strong> ${exam.name}<br>
                <strong>Jenis:</strong> ${typeNames[exam.type]}<br>
                <strong>Subjek:</strong> ${subjectName}<br>
                <strong>Tarikh:</strong> ${dateStr}<br>
                <strong>Masa:</strong> ${exam.time}<br>
                <strong>Tempoh:</strong> ${exam.duration} minit<br>
                <strong>Kelas:</strong> ${exam.class === 'all' ? 'Semua Kelas' : 'Kelas ' + exam.class}<br>
                <strong>Lokasi:</strong> ${exam.location}<br>
                <strong>Status:</strong> ${statusText}<br>
                <strong>Butiran:</strong> ${exam.details}
            `;
            
            showNotification('Butiran Ujian', 'info', details);
        }

        // Add exam modal
        function tambahUjianModal() {
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            
            document.getElementById('examDate').value = tomorrowStr;
            document.getElementById('examTime').value = '09:00';
            
            // Show modal
            addExamModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function tutupModal() {
            addExamModal.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('examForm').reset();
        }

        // Add exam
        function tambahUjian(event) {
            event.preventDefault();
            
            // Get form values
            const name = document.getElementById('examName').value;
            const type = document.getElementById('examType').value;
            const subject = document.getElementById('examSubject').value;
            const date = new Date(document.getElementById('examDate').value);
            const time = document.getElementById('examTime').value;
            const duration = parseInt(document.getElementById('examDuration').value);
            const examClass = document.getElementById('examClass').value;
            const details = document.getElementById('examDetails').value;
            const location = document.getElementById('examLocation').value;
            
            // Add time to date
            const [hours, minutes] = time.split(':');
            date.setHours(hours, minutes);
            
            // Create new exam object
            const newExam = {
                id: 'EXAM' + (sampleExams.length + 1).toString().padStart(3, '0'),
                name: name,
                type: type,
                subject: subject,
                date: date,
                time: time,
                duration: duration,
                class: examClass,
                location: location || 'Bilik Kelas',
                details: details || 'Tiada butiran tambahan',
                status: 'upcoming'
            };
            
            // Add to sample data
            sampleExams.push(newExam);
            
            // Close modal
            tutupModal();
            
            // Update UI
            generateCalendar(currentMonth, currentYear);
            loadUpcomingExams();
            loadAllExams();
            updateExamStatistics();
            
            // Show success message
            showNotification('Ujian berjaya ditambah', 'success');
        }

        // Update exam
        function kemaskiniUjian(examId) {
            const exam = sampleExams.find(e => e.id === examId);
            if (!exam) return;
            
            // In a real app, this would open a pre-filled form
            showNotification('Kemaskini Ujian', 'info', `Fitur kemaskini akan dibuka untuk: ${exam.name}`);
        }

        // Delete exam
        function padamUjian(examId) {
            if (confirm('Adakah anda pasti ingin memadam ujian ini?')) {
                const index = sampleExams.findIndex(e => e.id === examId);
                if (index !== -1) {
                    sampleExams.splice(index, 1);
                    
                    // Update UI
                    generateCalendar(currentMonth, currentYear);
                    loadUpcomingExams();
                    loadAllExams();
                    updateExamStatistics();
                    
                    showNotification('Ujian berjaya dipadam', 'success');
                }
            }
        }

        // Send reminders
        function hantarPeringatan() {
            showNotification('Peringatan', 'info', 'Peringatan akan dihantar kepada semua pelajar mengenai ujian akan datang.');
        }

        // Export schedule
        function eksportJadual() {
            showNotification('Eksport Jadual', 'info', 'Jadual ujian sedang disediakan untuk eksport...');
            setTimeout(() => {
                showNotification('Jadual berjaya dieksport', 'success');
            }, 1500);
        }

        // Reload schedule
        function muatSemulaJadual() {
            showNotification('Muat semula jadual...', 'info');
            
            // Simulate reload
            setTimeout(() => {
                generateCalendar(currentMonth, currentYear);
                loadUpcomingExams();
                loadAllExams();
                updateExamStatistics();
                
                showNotification('Jadual berjaya dimuat semula', 'success');
            }, 1000);
        }

        // Start countdown timer
        function startCountdownTimer() {
            // Find next exam
            const now = new Date();
            const upcomingExams = sampleExams
                .filter(exam => exam.status === 'upcoming' && exam.date > now)
                .sort((a, b) => a.date - b.date);
            
            if (upcomingExams.length === 0) {
                document.getElementById('countdownTimer').textContent = 'Tiada';
                return;
            }
            
            const nextExam = upcomingExams[0];
            
            // Update countdown every second
            function updateCountdown() {
                const now = new Date();
                const examTime = new Date(nextExam.date);
                examTime.setHours(...nextExam.time.split(':'));
                
                const diff = examTime - now;
                
                if (diff <= 0) {
                    document.getElementById('countdownTimer').textContent = 'Sedang Berlangsung';
                    return;
                }
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                document.getElementById('countdownTimer').textContent = 
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            updateCountdown();
            setInterval(updateCountdown, 1000);
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