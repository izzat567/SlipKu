<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Ujian - SlipKu</title>
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

        /* Calendar View */
        .calendar-view {
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
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .calendar-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .current-month {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary);
            min-width: 200px;
            text-align: center;
        }

        /* Calendar Grid */
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .calendar-day-header {
            text-align: center;
            padding: 15px 5px;
            font-weight: 600;
            font-size: 13px;
            color: var(--medium-gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .calendar-day {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            min-height: 120px;
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
        }

        .calendar-day:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .calendar-day.today {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .calendar-day.other-month {
            opacity: 0.4;
            background: #f9fafb;
        }

        .day-number {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 10px;
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            background: white;
        }

        .calendar-day.today .day-number {
            background: var(--primary);
            color: white;
        }

        .exam-event {
            background: white;
            padding: 8px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 11px;
            border-left: 3px solid var(--primary);
            cursor: pointer;
            transition: var(--transition);
        }

        .exam-event:hover {
            transform: translateX(3px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .exam-event.ujian1 {
            border-left-color: var(--info);
        }

        .exam-event.ujian2 {
            border-left-color: var(--warning);
        }

        .exam-event.pertengahan {
            border-left-color: var(--success);
        }

        .exam-event.akhir {
            border-left-color: var(--danger);
        }

        .exam-subject {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 2px;
        }

        .exam-time {
            font-size: 10px;
            color: var(--medium-gray);
        }

        /* Exam List View */
        .exam-list-view {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: none;
        }

        .exam-list-view.active {
            display: block;
        }

        /* Upcoming Exams */
        .upcoming-exams {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .section-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        /* Exam Cards */
        .exam-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .exam-card {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 25px;
            transition: var(--transition);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .exam-card:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.15);
        }

        .exam-card.upcoming {
            border-left: 5px solid var(--warning);
        }

        .exam-card.active {
            border-left: 5px solid var(--success);
        }

        .exam-card.completed {
            border-left: 5px solid var(--info);
        }

        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .exam-info h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .exam-info p {
            font-size: 13px;
            color: var(--medium-gray);
        }

        .exam-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-upcoming {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .status-completed {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .exam-details {
            margin-bottom: 15px;
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

        .exam-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* View Options */
        .view-options {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .view-btn {
            padding: 10px 20px;
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
            gap: 8px;
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

        /* Filter Options */
        .filter-options {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
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

        /* Exam Table */
        .exam-table-container {
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
            min-width: 800px;
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
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tr:hover td {
            background: var(--primary-light);
        }

        /* Form Modal */
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

        .form-input, .form-select, .form-date, .form-time {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus, .form-select:focus, .form-date:focus, .form-time:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background: var(--white);
            transition: var(--transition);
            resize: vertical;
            min-height: 100px;
        }

        .form-textarea:focus {
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

        /* Class Selection */
        .class-selection {
            margin-top: 15px;
        }

        .class-selection-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .class-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
        }

        .class-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .class-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .class-checkbox label {
            font-size: 13px;
            color: var(--dark-gray);
            cursor: pointer;
        }

        /* Countdown Timer */
        .countdown-timer {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--border-radius);
            padding: 25px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .countdown-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .countdown-display {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .countdown-unit {
            text-align: center;
        }

        .countdown-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 5px;
        }

        .countdown-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .next-exam-info {
            font-size: 14px;
            opacity: 0.9;
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
            z-index: 10001;
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
            
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
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
            
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 5px;
            }
            
            .calendar-day {
                padding: 10px;
                min-height: 100px;
            }
            
            .exam-cards {
                grid-template-columns: 1fr;
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
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .class-checkboxes {
                grid-template-columns: repeat(3, 1fr);
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
            
            .calendar-grid {
                grid-template-columns: repeat(7, 1fr);
                gap: 3px;
            }
            
            .calendar-day {
                padding: 8px;
                min-height: 80px;
                font-size: 12px;
            }
            
            .day-number {
                font-size: 12px;
                padding: 2px 6px;
            }
            
            .exam-event {
                padding: 5px;
                font-size: 10px;
            }
            
            .countdown-display {
                gap: 10px;
            }
            
            .countdown-value {
                font-size: 24px;
            }
            
            .class-checkboxes {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .btn {
                padding: 10px 15px;
                font-size: 13px;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 12px;
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
    <!-- Modal for Add/Edit Exam -->
    <div class="modal" id="examModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Ujian Baru</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="examForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Jenis Ujian</label>
                            <select class="form-select" id="examType" required>
                                <option value="">Pilih Jenis Ujian</option>
                                <option value="ujian1">Ujian 1</option>
                                <option value="ujian2">Ujian 2</option>
                                <option value="pertengahan">Peperiksaan Pertengahan Tahun</option>
                                <option value="akhir">Peperiksaan Akhir Tahun</option>
                                <option value="lain">Lain-lain</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Mata Pelajaran</label>
                            <select class="form-select" id="examSubject" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                <option value="MAT01">Matematik</option>
                                <option value="BAH01">Bahasa Melayu</option>
                                <option value="BI01">Bahasa Inggeris</option>
                                <option value="SNS01">Sains</option>
                                <option value="PJH01">PJ & Kesihatan</option>
                                <option value="PIS01">Pendidikan Islam</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Tarikh Ujian</label>
                            <input type="date" class="form-date" id="examDate" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Masa Mula</label>
                            <input type="time" class="form-time" id="examStartTime" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Masa Tamat</label>
                            <input type="time" class="form-time" id="examEndTime" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label required">Tahun</label>
                            <select class="form-select" id="examYear" required>
                                <option value="">Pilih Tahun</option>
                                <option value="1">Tahun 1</option>
                                <option value="2">Tahun 2</option>
                                <option value="3">Tahun 3</option>
                                <option value="4">Tahun 4</option>
                                <option value="5">Tahun 5</option>
                                <option value="6">Tahun 6</option>
                                <option value="all">Semua Tahun</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Bilik/Bangunan</label>
                            <input type="text" class="form-input" id="examRoom" placeholder="Contoh: Dewan Sekolah, Bilik 101">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Guru Pengawas</label>
                        <select class="form-select" id="examTeacher">
                            <option value="">Pilih Guru Pengawas</option>
                            <option value="GU01">Cikgu Admin</option>
                            <option value="GU02">Cikgu Ahmad</option>
                            <option value="GU03">Cikgu Siti</option>
                            <option value="GU04">Cikgu Ali</option>
                            <option value="GU05">Cikgu Fatimah</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Kelas yang Terlibat</label>
                        <div class="class-selection">
                            <div class="class-checkboxes" id="classCheckboxes">
                                <!-- Class checkboxes will be generated here -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-textarea" id="examNotes" placeholder="Catatan tambahan mengenai ujian ini..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Ujian
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
            <a href="semua-rekod.html" class="sidebar-item">
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
            <a href="#" class="sidebar-item active">
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
                <h2>Jadual Ujian 📅</h2>
                <p>Pengurusan jadual dan waktu peperiksaan</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-success" onclick="openExamModal()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Ujian
                </button>
            </div>
        </div>

        <!-- Countdown Timer -->
        <div class="countdown-timer">
            <div class="countdown-title">Ujian Seterusnya</div>
            <div class="countdown-display">
                <div class="countdown-unit">
                    <div class="countdown-value" id="countdownDays">05</div>
                    <div class="countdown-label">Hari</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="countdownHours">12</div>
                    <div class="countdown-label">Jam</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="countdownMinutes">30</div>
                    <div class="countdown-label">Minit</div>
                </div>
                <div class="countdown-unit">
                    <div class="countdown-value" id="countdownSeconds">45</div>
                    <div class="countdown-label">Saat</div>
                </div>
            </div>
            <div class="next-exam-info" id="nextExamInfo">
                Matematik - Peperiksaan Akhir Tahun - 15 Dis 2023, 8:00 pagi
            </div>
        </div>

        <!-- Filter Options -->
        <div class="filter-options">
            <div class="filter-group">
                <label class="filter-label">Jenis Ujian:</label>
                <select class="filter-select" id="filterExamType" onchange="filterExams()">
                    <option value="">Semua Jenis</option>
                    <option value="ujian1">Ujian 1</option>
                    <option value="ujian2">Ujian 2</option>
                    <option value="pertengahan">Pertengahan Tahun</option>
                    <option value="akhir">Akhir Tahun</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Tahun:</label>
                <select class="filter-select" id="filterExamYear" onchange="filterExams()">
                    <option value="">Semua Tahun</option>
                    <option value="1">Tahun 1</option>
                    <option value="2">Tahun 2</option>
                    <option value="3">Tahun 3</option>
                    <option value="4">Tahun 4</option>
                    <option value="5">Tahun 5</option>
                    <option value="6">Tahun 6</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Status:</label>
                <select class="filter-select" id="filterExamStatus" onchange="filterExams()">
                    <option value="">Semua Status</option>
                    <option value="upcoming">Akan Datang</option>
                    <option value="active">Sedang Berlangsung</option>
                    <option value="completed">Telah Tamat</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Bulan:</label>
                <select class="filter-select" id="filterExamMonth" onchange="filterExams()">
                    <option value="">Semua Bulan</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Mac</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Jun</option>
                    <option value="7">Julai</option>
                    <option value="8">Ogos</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Disember</option>
                </select>
            </div>
        </div>

        <!-- View Options -->
        <div class="view-options">
            <button class="view-btn active" onclick="changeView('calendar')">
                <i class="fas fa-calendar-alt"></i>
                Paparan Kalendar
            </button>
            <button class="view-btn" onclick="changeView('list')">
                <i class="fas fa-list"></i>
                Paparan Senarai
            </button>
            <button class="view-btn" onclick="changeView('table')">
                <i class="fas fa-table"></i>
                Paparan Jadual
            </button>
            <div style="margin-left: auto;">
                <button class="btn btn-info" onclick="cetakJadual()">
                    <i class="fas fa-print"></i>
                    Cetak Jadual
                </button>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="calendar-view" id="calendarView">
            <div class="calendar-header">
                <h3>Kalendar Ujian</h3>
                <div class="calendar-nav">
                    <button class="btn btn-secondary" onclick="previousMonth()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="current-month" id="currentMonth">Disember 2023</div>
                    <button class="btn btn-secondary" onclick="nextMonth()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <!-- Calendar Grid -->
            <div class="calendar-grid" id="calendarGrid">
                <!-- Calendar will be generated here -->
            </div>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 15px; height: 15px; background: var(--info); border-radius: 3px;"></div>
                    <span style="font-size: 12px; color: var(--medium-gray);">Ujian 1</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 15px; height: 15px; background: var(--warning); border-radius: 3px;"></div>
                    <span style="font-size: 12px; color: var(--medium-gray);">Ujian 2</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 15px; height: 15px; background: var(--success); border-radius: 3px;"></div>
                    <span style="font-size: 12px; color: var(--medium-gray);">Pertengahan Tahun</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 15px; height: 15px; background: var(--danger); border-radius: 3px;"></div>
                    <span style="font-size: 12px; color: var(--medium-gray);">Akhir Tahun</span>
                </div>
            </div>
        </div>

        <!-- Exam List View -->
        <div class="exam-list-view" id="listView">
            <div class="section-header">
                <h3>Senarai Ujian</h3>
                <button class="btn btn-secondary" onclick="searchExams()">
                    <i class="fas fa-search"></i>
                    Cari Ujian
                </button>
            </div>
            
            <!-- Exam Cards -->
            <div class="exam-cards" id="examCardsList">
                <!-- Exam cards will be loaded here -->
            </div>
            
            <!-- Empty State -->
            <div class="empty-state" id="emptyList" style="display: none;">
                <i class="fas fa-calendar-times"></i>
                <h3>Tiada Ujian Ditemui</h3>
                <p>Tiada ujian yang sepadan dengan penapis anda. Cuba ubah penapis atau tambah ujian baru.</p>
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Reset Penapis
                </button>
            </div>
        </div>

        <!-- Exam Table View -->
        <div class="exam-table-container" id="tableViewContainer" style="display: none;">
            <div class="section-header">
                <h3>Jadual Ujian Terperinci</h3>
                <button class="btn btn-info" onclick="exportJadual()">
                    <i class="fas fa-file-export"></i>
                    Eksport Jadual
                </button>
            </div>
            
            <table id="examTable">
                <thead>
                    <tr>
                        <th>TARIKH</th>
                        <th>MATA PELAJARAN</th>
                        <th>JENIS UJIAN</th>
                        <th>MASA</th>
                        <th>TAHUN</th>
                        <th>KELAS</th>
                        <th>LOKASI</th>
                        <th>STATUS</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="examTableBody">
                    <!-- Exam table rows will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Upcoming Exams -->
        <div class="upcoming-exams">
            <div class="section-header">
                <h3>Ujian Akan Datang (7 Hari)</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaUjian()">
                    <i class="fas fa-eye"></i>
                    Lihat Semua
                </button>
            </div>
            
            <div class="exam-cards" id="upcomingExams">
                <!-- Upcoming exam cards will be loaded here -->
            </div>
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
        const toast = document.getElementById('toast');
        const examModal = document.getElementById('examModal');
        const calendarGrid = document.getElementById('calendarGrid');
        const examCardsList = document.getElementById('examCardsList');
        const examTableBody = document.getElementById('examTableBody');
        const upcomingExams = document.getElementById('upcomingExams');
        const classCheckboxes = document.getElementById('classCheckboxes');

        // Current state
        let examsData = [];
        let filteredExams = [];
        let isEditingExam = false;
        let currentExamId = null;
        let currentView = 'calendar';
        let currentDate = new Date();
        let countdownInterval = null;

        // Sample data for exams
        const sampleExams = [
            {
                id: 'EXM001',
                type: 'akhir',
                typeName: 'Peperiksaan Akhir Tahun',
                subject: 'Matematik',
                subjectCode: 'MAT01',
                date: '2023-12-15',
                startTime: '08:00',
                endTime: '10:00',
                year: '6',
                classes: ['6A', '6B', '6C'],
                room: 'Dewan Sekolah',
                teacherId: 'GU01',
                teacherName: 'Cikgu Admin',
                notes: 'Ujian akhir tahun, semua pelajar wajib hadir',
                status: 'upcoming',
                createdAt: '2023-11-01'
            },
            {
                id: 'EXM002',
                type: 'akhir',
                typeName: 'Peperiksaan Akhir Tahun',
                subject: 'Bahasa Melayu',
                subjectCode: 'BAH01',
                date: '2023-12-16',
                startTime: '08:00',
                endTime: '10:00',
                year: '6',
                classes: ['6A', '6B', '6C'],
                room: 'Dewan Sekolah',
                teacherId: 'GU03',
                teacherName: 'Cikgu Siti',
                notes: 'Kertas 1 - Pemahaman',
                status: 'upcoming',
                createdAt: '2023-11-01'
            },
            {
                id: 'EXM003',
                type: 'akhir',
                typeName: 'Peperiksaan Akhir Tahun',
                subject: 'Bahasa Inggeris',
                subjectCode: 'BI01',
                date: '2023-12-17',
                startTime: '08:00',
                endTime: '10:00',
                year: '6',
                classes: ['6A', '6B', '6C'],
                room: 'Dewan Sekolah',
                teacherId: 'GU02',
                teacherName: 'Cikgu Ahmad',
                notes: 'Paper 1 - Comprehension',
                status: 'upcoming',
                createdAt: '2023-11-01'
            },
            {
                id: 'EXM004',
                type: 'pertengahan',
                typeName: 'Peperiksaan Pertengahan Tahun',
                subject: 'Sains',
                subjectCode: 'SNS01',
                date: '2023-09-15',
                startTime: '10:30',
                endTime: '12:30',
                year: '6',
                classes: ['6A', '6B'],
                room: 'Makmal Sains',
                teacherId: 'GU04',
                teacherName: 'Cikgu Ali',
                notes: 'Bahagian A - Objektif',
                status: 'completed',
                createdAt: '2023-08-01'
            },
            {
                id: 'EXM005',
                type: 'ujian2',
                typeName: 'Ujian 2',
                subject: 'PJ & Kesihatan',
                subjectCode: 'PJH01',
                date: '2023-10-20',
                startTime: '14:00',
                endTime: '15:30',
                year: '6',
                classes: ['6A'],
                room: 'Padang Sekolah',
                teacherId: 'GU01',
                teacherName: 'Cikgu Admin',
                notes: 'Ujian amali - bola tampar',
                status: 'completed',
                createdAt: '2023-09-15'
            },
            {
                id: 'EXM006',
                type: 'ujian1',
                typeName: 'Ujian 1',
                subject: 'Matematik',
                subjectCode: 'MAT01',
                date: '2023-08-10',
                startTime: '08:00',
                endTime: '09:30',
                year: '5',
                classes: ['5A', '5B'],
                room: 'Bilik 201',
                teacherId: 'GU02',
                teacherName: 'Cikgu Ahmad',
                notes: 'Topik: Pecahan dan Perpuluhan',
                status: 'completed',
                createdAt: '2023-07-01'
            },
            {
                id: 'EXM007',
                type: 'ujian2',
                typeName: 'Ujian 2',
                subject: 'Bahasa Melayu',
                subjectCode: 'BAH01',
                date: '2023-11-05',
                startTime: '10:00',
                endTime: '11:30',
                year: '5',
                classes: ['5A'],
                room: 'Bilik 202',
                teacherId: 'GU03',
                teacherName: 'Cikgu Siti',
                notes: 'Kertas Karangan',
                status: 'completed',
                createdAt: '2023-10-01'
            },
            {
                id: 'EXM008',
                type: 'pertengahan',
                typeName: 'Peperiksaan Pertengahan Tahun',
                subject: 'Sains',
                subjectCode: 'SNS01',
                date: '2023-09-20',
                startTime: '08:00',
                endTime: '10:00',
                year: '4',
                classes: ['4A'],
                room: 'Makmal Sains',
                teacherId: 'GU04',
                teacherName: 'Cikgu Ali',
                notes: 'Topik: Sains Hayat',
                status: 'completed',
                createdAt: '2023-08-15'
            }
        ];

        // Initialize page
        function initializePage() {
            examsData = [...sampleExams];
            filteredExams = [...examsData];
            
            // Set up form submit handler
            document.getElementById('examForm').addEventListener('submit', saveExam);
            
            // Set current date for date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('examDate').value = today;
            document.getElementById('examDate').min = today;
            
            // Generate class checkboxes
            generateClassCheckboxes();
            
            // Load initial data
            loadCalendar();
            loadExamCards();
            loadExamTable();
            loadUpcomingExams();
            updateCountdownTimer();
            
            // Start countdown timer
            startCountdownTimer();
            
            // Set up filter listeners
            document.getElementById('filterExamType').addEventListener('change', filterExams);
            document.getElementById('filterExamYear').addEventListener('change', filterExams);
            document.getElementById('filterExamStatus').addEventListener('change', filterExams);
            document.getElementById('filterExamMonth').addEventListener('change', filterExams);
        }

        // Generate class checkboxes
        function generateClassCheckboxes() {
            const classes = [
                { id: '6A', label: 'Kelas 6A' },
                { id: '6B', label: 'Kelas 6B' },
                { id: '6C', label: 'Kelas 6C' },
                { id: '5A', label: 'Kelas 5A' },
                { id: '5B', label: 'Kelas 5B' },
                { id: '4A', label: 'Kelas 4A' },
                { id: 'all', label: 'Semua Kelas' }
            ];
            
            classCheckboxes.innerHTML = classes.map(cls => `
                <div class="class-checkbox">
                    <input type="checkbox" id="class-${cls.id}" value="${cls.id}">
                    <label for="class-${cls.id}">${cls.label}</label>
                </div>
            `).join('');
        }

        // Load calendar
        function loadCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            // Update current month display
            const monthNames = ['Januari', 'Februari', 'Mac', 'April', 'Mei', 'Jun', 'Julai', 'Ogos', 'September', 'Oktober', 'November', 'Disember'];
            document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
            
            // Get first day of month
            const firstDay = new Date(year, month, 1);
            // Get last day of month
            const lastDay = new Date(year, month + 1, 0);
            // Get number of days in month
            const daysInMonth = lastDay.getDate();
            // Get day of week for first day (0 = Sunday, 1 = Monday, etc.)
            const firstDayIndex = firstDay.getDay();
            // Get day of week for last day
            const lastDayIndex = lastDay.getDay();
            
            // Get previous month days
            const prevMonthLastDay = new Date(year, month, 0).getDate();
            
            // Create calendar grid
            let calendarHTML = '';
            
            // Day headers
            const dayNames = ['AHAD', 'ISNIN', 'SELASA', 'RABU', 'KHAMIS', 'JUMAAT', 'SABTU'];
            dayNames.forEach(day => {
                calendarHTML += `<div class="calendar-day-header">${day}</div>`;
            });
            
            // Previous month days
            for (let i = firstDayIndex; i > 0; i--) {
                const day = prevMonthLastDay - i + 1;
                const dateStr = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const prevMonth = month === 0 ? 11 : month - 1;
                const prevYear = month === 0 ? year - 1 : year;
                const fullDateStr = `${prevYear}-${(prevMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                
                calendarHTML += createCalendarDay(day, fullDateStr, true);
            }
            
            // Current month days
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            
            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const isToday = dateStr === todayStr;
                
                calendarHTML += createCalendarDay(day, dateStr, false, isToday);
            }
            
            // Next month days
            const totalCells = 42; // 6 rows * 7 days
            const nextMonthDays = totalCells - (firstDayIndex + daysInMonth);
            
            for (let day = 1; day <= nextMonthDays; day++) {
                const dateStr = `${year}-${(month + 2).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                const nextMonth = month === 11 ? 0 : month + 1;
                const nextYear = month === 11 ? year + 1 : year;
                const fullDateStr = `${nextYear}-${(nextMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
                
                calendarHTML += createCalendarDay(day, fullDateStr, true);
            }
            
            calendarGrid.innerHTML = calendarHTML;
        }

        // Create calendar day element
        function createCalendarDay(day, dateStr, isOtherMonth = false, isToday = false) {
            // Get exams for this date
            const dateExams = filteredExams.filter(exam => exam.date === dateStr);
            
            let dayClasses = 'calendar-day';
            if (isOtherMonth) dayClasses += ' other-month';
            if (isToday) dayClasses += ' today';
            
            let examHTML = '';
            dateExams.forEach(exam => {
                examHTML += `
                    <div class="exam-event ${exam.type}" onclick="viewExam('${exam.id}')">
                        <div class="exam-subject">${exam.subject}</div>
                        <div class="exam-time">${exam.startTime} - ${exam.endTime}</div>
                    </div>
                `;
            });
            
            return `
                <div class="${dayClasses}">
                    <div class="day-number">${day}</div>
                    ${examHTML}
                </div>
            `;
        }

        // Load exam cards
        function loadExamCards() {
            examCardsList.innerHTML = filteredExams.map(exam => {
                const statusClass = exam.status === 'upcoming' ? 'status-upcoming' : 
                                  exam.status === 'active' ? 'status-active' : 'status-completed';
                const statusText = exam.status === 'upcoming' ? 'Akan Datang' : 
                                 exam.status === 'active' ? 'Sedang Berlangsung' : 'Telah Tamat';
                
                // Format date
                const examDate = new Date(exam.date);
                const formattedDate = examDate.toLocaleDateString('ms-MY', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                return `
                    <div class="exam-card ${exam.status}">
                        <div class="exam-card-header">
                            <div class="exam-info">
                                <h4>${exam.subject} - ${exam.typeName}</h4>
                                <p>${formattedDate} • ${exam.startTime} - ${exam.endTime}</p>
                            </div>
                            <span class="exam-status ${statusClass}">${statusText}</span>
                        </div>
                        
                        <div class="exam-details">
                            <div class="detail-row">
                                <span class="detail-label">Tahun:</span>
                                <span class="detail-value">${exam.year}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Kelas:</span>
                                <span class="detail-value">${exam.classes.join(', ')}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Lokasi:</span>
                                <span class="detail-value">${exam.room}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Guru Pengawas:</span>
                                <span class="detail-value">${exam.teacherName}</span>
                            </div>
                        </div>
                        
                        ${exam.notes ? `<p style="font-size: 13px; color: var(--medium-gray); margin-bottom: 15px;">${exam.notes}</p>` : ''}
                        
                        <div class="exam-actions">
                            <button class="action-btn view" onclick="viewExam('${exam.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                            <button class="action-btn edit" onclick="editExam('${exam.id}')">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteExam('${exam.id}')">
                                <i class="fas fa-trash"></i>
                                Padam
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Show/hide empty state
            if (filteredExams.length === 0) {
                document.getElementById('emptyList').style.display = 'block';
            } else {
                document.getElementById('emptyList').style.display = 'none';
            }
        }

        // Load exam table
        function loadExamTable() {
            examTableBody.innerHTML = filteredExams.map(exam => {
                // Format date
                const examDate = new Date(exam.date);
                const formattedDate = examDate.toLocaleDateString('ms-MY', { 
                    day: 'numeric', 
                    month: 'short', 
                    year: 'numeric' 
                });
                
                const statusText = exam.status === 'upcoming' ? 'Akan Datang' : 
                                 exam.status === 'active' ? 'Sedang Berlangsung' : 'Telah Tamat';
                
                return `
                    <tr>
                        <td>${formattedDate}</td>
                        <td><strong>${exam.subject}</strong></td>
                        <td>${exam.typeName}</td>
                        <td>${exam.startTime} - ${exam.endTime}</td>
                        <td>Tahun ${exam.year}</td>
                        <td>${exam.classes.join(', ')}</td>
                        <td>${exam.room}</td>
                        <td>
                            <span class="status-badge status-${exam.status}">${statusText}</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <button class="action-btn view" onclick="viewExam('${exam.id}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit" onclick="editExam('${exam.id}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete" onclick="deleteExam('${exam.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load upcoming exams
        function loadUpcomingExams() {
            const today = new Date();
            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            
            const upcoming = examsData.filter(exam => {
                const examDate = new Date(exam.date);
                return examDate >= today && examDate <= nextWeek && exam.status === 'upcoming';
            }).slice(0, 3); // Show only 3 upcoming exams
            
            upcomingExams.innerHTML = upcoming.map(exam => {
                // Format date
                const examDate = new Date(exam.date);
                const formattedDate = examDate.toLocaleDateString('ms-MY', { 
                    weekday: 'short', 
                    day: 'numeric', 
                    month: 'short' 
                });
                
                return `
                    <div class="exam-card upcoming">
                        <div class="exam-card-header">
                            <div class="exam-info">
                                <h4>${exam.subject}</h4>
                                <p>${formattedDate} • ${exam.startTime} - ${exam.endTime}</p>
                            </div>
                            <span class="exam-status status-upcoming">Akan Datang</span>
                        </div>
                        
                        <div class="exam-details">
                            <div class="detail-row">
                                <span class="detail-label">Jenis:</span>
                                <span class="detail-value">${exam.typeName}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Kelas:</span>
                                <span class="detail-value">${exam.classes.join(', ')}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Lokasi:</span>
                                <span class="detail-value">${exam.room}</span>
                            </div>
                        </div>
                        
                        <div class="exam-actions">
                            <button class="action-btn view" onclick="viewExam('${exam.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat Detail
                            </button>
                            <button class="action-btn edit" onclick="editExam('${exam.id}')">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            if (upcoming.length === 0) {
                upcomingExams.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--medium-gray);">
                        <i class="fas fa-calendar-check" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <h3 style="margin-bottom: 10px;">Tiada Ujian Akan Datang</h3>
                        <p>Tidak ada ujian yang dijadualkan dalam tempoh 7 hari akan datang.</p>
                    </div>
                `;
            }
        }

        // Filter exams
        function filterExams() {
            const typeFilter = document.getElementById('filterExamType').value;
            const yearFilter = document.getElementById('filterExamYear').value;
            const statusFilter = document.getElementById('filterExamStatus').value;
            const monthFilter = document.getElementById('filterExamMonth').value;
            
            filteredExams = examsData.filter(exam => {
                // Apply type filter
                if (typeFilter && exam.type !== typeFilter) return false;
                
                // Apply year filter
                if (yearFilter && exam.year !== yearFilter) return false;
                
                // Apply status filter
                if (statusFilter && exam.status !== statusFilter) return false;
                
                // Apply month filter
                if (monthFilter) {
                    const examMonth = new Date(exam.date).getMonth() + 1; // Months are 0-indexed
                    if (examMonth.toString() !== monthFilter) return false;
                }
                
                return true;
            });
            
            // Update all views
            loadCalendar();
            loadExamCards();
            loadExamTable();
        }

        // Change view
        function changeView(view) {
            currentView = view;
            
            // Update active view button
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active view
            if (view === 'calendar') {
                document.getElementById('calendarView').style.display = 'block';
                document.getElementById('listView').classList.remove('active');
                document.getElementById('tableViewContainer').style.display = 'none';
            } else if (view === 'list') {
                document.getElementById('calendarView').style.display = 'none';
                document.getElementById('listView').classList.add('active');
                document.getElementById('tableViewContainer').style.display = 'none';
            } else if (view === 'table') {
                document.getElementById('calendarView').style.display = 'none';
                document.getElementById('listView').classList.remove('active');
                document.getElementById('tableViewContainer').style.display = 'block';
            }
        }

        // Previous month
        function previousMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            loadCalendar();
        }

        // Next month
        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            loadCalendar();
        }

        // Open exam modal
        function openExamModal(edit = false, examId = null) {
            isEditingExam = edit;
            currentExamId = examId;
            
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('examForm');
            
            if (edit && examId) {
                modalTitle.textContent = 'Edit Ujian';
                const exam = examsData.find(e => e.id === examId);
                if (exam) {
                    document.getElementById('examType').value = exam.type;
                    document.getElementById('examSubject').value = exam.subjectCode;
                    document.getElementById('examDate').value = exam.date;
                    document.getElementById('examStartTime').value = exam.startTime;
                    document.getElementById('examEndTime').value = exam.endTime;
                    document.getElementById('examYear').value = exam.year;
                    document.getElementById('examRoom').value = exam.room;
                    document.getElementById('examTeacher').value = exam.teacherId;
                    document.getElementById('examNotes').value = exam.notes;
                    
                    // Set class checkboxes
                    document.querySelectorAll('.class-checkbox input').forEach(checkbox => {
                        checkbox.checked = exam.classes.includes(checkbox.value) || 
                                         (checkbox.value === 'all' && exam.classes.length > 3);
                    });
                }
            } else {
                modalTitle.textContent = 'Tambah Ujian Baru';
                form.reset();
                
                // Set default time
                document.getElementById('examStartTime').value = '08:00';
                document.getElementById('examEndTime').value = '10:00';
                
                // Uncheck all class checkboxes
                document.querySelectorAll('.class-checkbox input').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
            
            examModal.classList.add('active');
        }

        // Close modal
        function closeModal() {
            examModal.classList.remove('active');
            isEditingExam = false;
            currentExamId = null;
        }

        // Save exam
        function saveExam(event) {
            event.preventDefault();
            
            const examType = document.getElementById('examType').value;
            const examSubject = document.getElementById('examSubject').value;
            const examDate = document.getElementById('examDate').value;
            const examStartTime = document.getElementById('examStartTime').value;
            const examEndTime = document.getElementById('examEndTime').value;
            const examYear = document.getElementById('examYear').value;
            const examRoom = document.getElementById('examRoom').value;
            const examTeacher = document.getElementById('examTeacher').value;
            const examNotes = document.getElementById('examNotes').value;
            
            // Get selected classes
            const selectedClasses = [];
            document.querySelectorAll('.class-checkbox input:checked').forEach(checkbox => {
                if (checkbox.value !== 'all') {
                    selectedClasses.push(checkbox.value);
                }
            });
            
            // If "all" is selected, add all classes for the selected year
            const allChecked = document.querySelector('.class-checkbox input[value="all"]:checked');
            if (allChecked) {
                // Add all classes for the selected year
                const yearClasses = {
                    '1': ['1A', '1B', '1C'],
                    '2': ['2A', '2B', '2C'],
                    '3': ['3A', '3B', '3C'],
                    '4': ['4A', '4B'],
                    '5': ['5A', '5B'],
                    '6': ['6A', '6B', '6C'],
                    'all': ['6A', '6B', '6C', '5A', '5B', '4A']
                };
                selectedClasses.push(...(yearClasses[examYear] || []));
            }
            
            // Remove duplicates
            const uniqueClasses = [...new Set(selectedClasses)];
            
            // Validate required fields
            if (!examType || !examSubject || !examDate || !examStartTime || !examEndTime || !examYear || uniqueClasses.length === 0) {
                showToast('Ralat', 'Sila isi semua maklumat yang diperlukan', 'error');
                return;
            }
            
            // Validate time
            if (examStartTime >= examEndTime) {
                showToast('Ralat', 'Masa mula mesti sebelum masa tamat', 'error');
                return;
            }
            
            // Get subject and teacher info
            const subjectNames = {
                'MAT01': 'Matematik',
                'BAH01': 'Bahasa Melayu',
                'BI01': 'Bahasa Inggeris',
                'SNS01': 'Sains',
                'PJH01': 'PJ & Kesihatan',
                'PIS01': 'Pendidikan Islam'
            };
            
            const typeNames = {
                'ujian1': 'Ujian 1',
                'ujian2': 'Ujian 2',
                'pertengahan': 'Peperiksaan Pertengahan Tahun',
                'akhir': 'Peperiksaan Akhir Tahun',
                'lain': 'Ujian Lain'
            };
            
            const teacherNames = {
                'GU01': 'Cikgu Admin',
                'GU02': 'Cikgu Ahmad',
                'GU03': 'Cikgu Siti',
                'GU04': 'Cikgu Ali',
                'GU05': 'Cikgu Fatimah'
            };
            
            // Determine status based on date
            const today = new Date().toISOString().split('T')[0];
            let status = 'upcoming';
            if (examDate < today) {
                status = 'completed';
            } else if (examDate === today) {
                status = 'active';
            }
            
            if (isEditingExam && currentExamId) {
                // Update existing exam
                const index = examsData.findIndex(e => e.id === currentExamId);
                if (index !== -1) {
                    examsData[index] = {
                        ...examsData[index],
                        type: examType,
                        typeName: typeNames[examType] || 'Ujian',
                        subject: subjectNames[examSubject] || examSubject,
                        subjectCode: examSubject,
                        date: examDate,
                        startTime: examStartTime,
                        endTime: examEndTime,
                        year: examYear,
                        classes: uniqueClasses,
                        room: examRoom,
                        teacherId: examTeacher,
                        teacherName: teacherNames[examTeacher] || 'Cikgu',
                        notes: examNotes,
                        status: status
                    };
                    
                    showToast('Ujian Dikemaskini', 'Jadual ujian telah berjaya dikemaskini', 'success');
                }
            } else {
                // Add new exam
                const newExam = {
                    id: 'EXM' + (examsData.length + 1).toString().padStart(3, '0'),
                    type: examType,
                    typeName: typeNames[examType] || 'Ujian',
                    subject: subjectNames[examSubject] || examSubject,
                    subjectCode: examSubject,
                    date: examDate,
                    startTime: examStartTime,
                    endTime: examEndTime,
                    year: examYear,
                    classes: uniqueClasses,
                    room: examRoom,
                    teacherId: examTeacher,
                    teacherName: teacherNames[examTeacher] || 'Cikgu',
                    notes: examNotes,
                    status: status,
                    createdAt: new Date().toISOString().split('T')[0]
                };
                
                examsData.push(newExam);
                showToast('Ujian Ditambah', 'Jadual ujian baru telah berjaya ditambah', 'success');
            }
            
            // Update data
            filteredExams = [...examsData];
            loadCalendar();
            loadExamCards();
            loadExamTable();
            loadUpcomingExams();
            updateCountdownTimer();
            closeModal();
        }

        // View exam details
        function viewExam(examId) {
            const exam = examsData.find(e => e.id === examId);
            if (exam) {
                const examDate = new Date(exam.date);
                const formattedDate = examDate.toLocaleDateString('ms-MY', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                alert(`Maklumat Ujian:\n\n` +
                      `Mata Pelajaran: ${exam.subject}\n` +
                      `Jenis Ujian: ${exam.typeName}\n` +
                      `Tarikh: ${formattedDate}\n` +
                      `Masa: ${exam.startTime} - ${exam.endTime}\n` +
                      `Tempoh: ${calculateDuration(exam.startTime, exam.endTime)}\n` +
                      `Tahun: ${exam.year}\n` +
                      `Kelas: ${exam.classes.join(', ')}\n` +
                      `Lokasi: ${exam.room}\n` +
                      `Guru Pengawas: ${exam.teacherName}\n` +
                      `Status: ${exam.status === 'upcoming' ? 'Akan Datang' : exam.status === 'active' ? 'Sedang Berlangsung' : 'Telah Tamat'}\n` +
                      `Catatan: ${exam.notes || 'Tiada catatan'}\n` +
                      `Dijadualkan pada: ${exam.createdAt}`);
            }
        }

        // Edit exam
        function editExam(examId) {
            openExamModal(true, examId);
        }

        // Delete exam
        function deleteExam(examId) {
            const exam = examsData.find(e => e.id === examId);
            if (!exam) return;
            
            if (confirm(`Adakah anda pasti ingin memadam ujian ini?\n\n${exam.subject} - ${exam.typeName}\nTarikh: ${exam.date} ${exam.startTime}`)) {
                // Remove exam
                const index = examsData.findIndex(e => e.id === examId);
                if (index !== -1) {
                    examsData.splice(index, 1);
                    
                    // Update filtered data
                    filterExams();
                    loadUpcomingExams();
                    updateCountdownTimer();
                    
                    showToast('Ujian Dipadam', 'Jadual ujian telah berjaya dipadam', 'success');
                }
            }
        }

        // Calculate duration
        function calculateDuration(startTime, endTime) {
            const start = new Date(`2000-01-01T${startTime}:00`);
            const end = new Date(`2000-01-01T${endTime}:00`);
            const diffMs = end - start;
            const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
            const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
            
            if (diffHours === 0) {
                return `${diffMinutes} minit`;
            } else if (diffMinutes === 0) {
                return `${diffHours} jam`;
            } else {
                return `${diffHours} jam ${diffMinutes} minit`;
            }
        }

        // Update countdown timer
        function updateCountdownTimer() {
            const upcomingExams = examsData.filter(exam => exam.status === 'upcoming');
            
            if (upcomingExams.length === 0) {
                document.getElementById('nextExamInfo').textContent = 'Tiada ujian akan datang';
                document.getElementById('countdownDays').textContent = '00';
                document.getElementById('countdownHours').textContent = '00';
                document.getElementById('countdownMinutes').textContent = '00';
                document.getElementById('countdownSeconds').textContent = '00';
                return;
            }
            
            // Get the next exam (closest date)
            const nextExam = upcomingExams.sort((a, b) => new Date(a.date) - new Date(b.date))[0];
            
            // Format exam info
            const examDate = new Date(nextExam.date);
            const formattedDate = examDate.toLocaleDateString('ms-MY', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            document.getElementById('nextExamInfo').textContent = 
                `${nextExam.subject} - ${nextExam.typeName} - ${formattedDate}, ${nextExam.startTime}`;
            
            // Calculate countdown
            const now = new Date();
            const examDateTime = new Date(`${nextExam.date}T${nextExam.startTime}:00`);
            const timeDiff = examDateTime - now;
            
            if (timeDiff <= 0) {
                // Exam has started or passed
                document.getElementById('countdownDays').textContent = '00';
                document.getElementById('countdownHours').textContent = '00';
                document.getElementById('countdownMinutes').textContent = '00';
                document.getElementById('countdownSeconds').textContent = '00';
                return;
            }
            
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
            
            document.getElementById('countdownDays').textContent = days.toString().padStart(2, '0');
            document.getElementById('countdownHours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countdownMinutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countdownSeconds').textContent = seconds.toString().padStart(2, '0');
        }

        // Start countdown timer
        function startCountdownTimer() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            countdownInterval = setInterval(updateCountdownTimer, 1000);
        }

        // Search exams
        function searchExams() {
            const searchInput = prompt('Sila masukkan kata kunci carian (mata pelajaran, jenis ujian, atau lokasi):');
            if (!searchInput) return;
            
            const searchTerm = searchInput.toLowerCase();
            filteredExams = examsData.filter(exam => {
                return exam.subject.toLowerCase().includes(searchTerm) ||
                       exam.typeName.toLowerCase().includes(searchTerm) ||
                       exam.room.toLowerCase().includes(searchTerm) ||
                       exam.teacherName.toLowerCase().includes(searchTerm);
            });
            
            // Update views
            loadExamCards();
            loadExamTable();
            loadCalendar();
            
            showToast('Hasil Carian', `Ditemui ${filteredExams.length} ujian yang sepadan`, 'success');
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('filterExamType').value = '';
            document.getElementById('filterExamYear').value = '';
            document.getElementById('filterExamStatus').value = '';
            document.getElementById('filterExamMonth').value = '';
            
            filteredExams = [...examsData];
            loadCalendar();
            loadExamCards();
            loadExamTable();
            loadUpcomingExams();
            
            showToast('Penapis Direset', 'Semua penapis telah dikembalikan kepada tetapan asal', 'success');
        }

        // Print schedule
        function cetakJadual() {
            alert('Menyediakan jadual ujian untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable schedule
        }

        // Export schedule
        function exportJadual() {
            showToast('Mengeksport Jadual', 'Jadual ujian sedang dieksport ke fail Excel...', 'success');
            
            // Simulate export
            setTimeout(() => {
                showToast('Eksport Berjaya', 'Jadual ujian telah berjaya dieksport', 'success');
            }, 1500);
        }

        // View all exams
        function lihatSemuaUjian() {
            // Reset filters and show all exams
            resetFilters();
            changeView('list');
            
            // Scroll to list view
            document.getElementById('listView').scrollIntoView({ behavior: 'smooth' });
        }

        // Reload data
        function muatSemulaData() {
            // Reset all filters
            resetFilters();
            
            // Reset to calendar view
            changeView('calendar');
            
            // Reset calendar to current month
            currentDate = new Date();
            loadCalendar();
            
            showToast('Data Dimuat Semula', 'Semua data jadual ujian telah disegarkan', 'success');
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

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
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