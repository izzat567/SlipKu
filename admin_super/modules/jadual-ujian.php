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
    <link rel="stylesheet" href="../css/jadual-ujian.css">
   
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

     <!-- Loading Overlay -->
     <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div style="color: var(--dark-gray); font-size: 18px; font-weight: 600;">Memuatkan papan pemuka...</div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- include Header -->
    <?php include './../includes/header.php'; ?> 

   <!-- include side bar -->
   <?php include './../includes/sidebar.php'; ?>


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