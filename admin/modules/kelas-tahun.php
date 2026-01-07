<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelas & Tahun - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/kelas-tahun.css">

</head>
<body>
    <!-- Modal for Add/Edit Class -->
    <div class="modal" id="classModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Kelas Baru</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="classForm">
                    <div class="form-group">
                        <label class="form-label required">Nama Kelas</label>
                        <input type="text" class="form-input" id="className" placeholder="Contoh: 6A, 5B, 4C" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Tahun</label>
                        <select class="form-select" id="classYear" required>
                            <option value="">Pilih Tahun</option>
                            <option value="1">Tahun 1</option>
                            <option value="2">Tahun 2</option>
                            <option value="3">Tahun 3</option>
                            <option value="4">Tahun 4</option>
                            <option value="5">Tahun 5</option>
                            <option value="6">Tahun 6</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Guru Kelas</label>
                        <select class="form-select" id="classTeacher" required>
                            <option value="">Pilih Guru Kelas</option>
                            <option value="GU01">Cikgu Admin</option>
                            <option value="GU02">Cikgu Ahmad</option>
                            <option value="GU03">Cikgu Siti</option>
                            <option value="GU04">Cikgu Ali</option>
                            <option value="GU05">Cikgu Fatimah</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Bilik Kelas</label>
                        <input type="text" class="form-input" id="classRoom" placeholder="Contoh: Bilik 101, Makmal 3">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Waktu Kelas</label>
                        <input type="text" class="form-input" id="classTime" placeholder="Contoh: 8:00-10:00 pagi">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-textarea" id="classNotes" placeholder="Catatan tambahan mengenai kelas ini..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
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

    <!-- Modal for Add/Edit Year -->
    <div class="modal" id="yearModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="yearModalTitle">Tambah Tahun Baru</h3>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="yearForm">
                    <div class="form-group">
                        <label class="form-label required">Nama Tahun</label>
                        <input type="text" class="form-input" id="yearName" placeholder="Contoh: Tahun 6, Tahun 5, Tahun 4" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Kod Tahun</label>
                        <input type="text" class="form-input" id="yearCode" placeholder="Contoh: TAHUN6, YEAR6" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Ketua Tahun</label>
                        <select class="form-select" id="yearHead" required>
                            <option value="">Pilih Ketua Tahun</option>
                            <option value="GU01">Cikgu Admin</option>
                            <option value="GU02">Cikgu Ahmad</option>
                            <option value="GU03">Cikgu Siti</option>
                            <option value="GU04">Cikgu Ali</option>
                            <option value="GU05">Cikgu Fatimah</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">Sesi</label>
                        <select class="form-select" id="yearSession" required>
                            <option value="">Pilih Sesi</option>
                            <option value="2023/2024">2023/2024</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026">2025/2026</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Jumlah Pelajar Sasaran</label>
                        <input type="number" class="form-input" id="yearTarget" placeholder="Contoh: 150" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-textarea" id="yearNotes" placeholder="Catatan tambahan mengenai tahun ini..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Tahun
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
                <h2>Kelas & Tahun 🏫</h2>
                <p>Pengurusan kelas, tahun dan struktur akademik</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-info" onclick="cetakStruktur()">
                    <i class="fas fa-print"></i>
                    Cetak Struktur
                </button>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="overview-cards">
            <div class="overview-card primary">
                <div class="card-header">
                    <h4>Jumlah Kelas</h4>
                    <div class="card-icon">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <div class="card-value" id="totalClasses">12</div>
                <div class="card-subtitle">Dari Tahun 1 hingga 6</div>
            </div>
            
            <div class="overview-card success">
                <div class="card-header">
                    <h4>Jumlah Pelajar</h4>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-value" id="totalStudents">245</div>
                <div class="card-subtitle">Purata 20 pelajar setiap kelas</div>
            </div>
            
            <div class="overview-card warning">
                <div class="card-header">
                    <h4>Guru Bertugas</h4>
                    <div class="card-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="card-value" id="totalTeachers">8</div>
                <div class="card-subtitle">2 guru tahun, 6 guru kelas</div>
            </div>
            
            <div class="overview-card info">
                <div class="card-header">
                    <h4>Kapasiti Kelas</h4>
                    <div class="card-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="card-value" id="classCapacity">82%</div>
                <div class="card-subtitle">245/300 slot pelajar terisi</div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-navigation">
            <button class="tab-btn active" onclick="changeTab('classes')">
                <i class="fas fa-school"></i>
                Senarai Kelas
            </button>
            <button class="tab-btn" onclick="changeTab('years')">
                <i class="fas fa-layer-group"></i>
                Pengurusan Tahun
            </button>
            <button class="tab-btn" onclick="changeTab('structure')">
                <i class="fas fa-sitemap"></i>
                Struktur Akademik
            </button>
        </div>

        <!-- Classes Tab -->
        <div class="tab-content active" id="classesTab">
            <div class="classes-section">
                <div class="section-header">
                    <h3>Senarai Kelas</h3>
                    <div>
                        <button class="btn btn-secondary" onclick="searchClasses()">
                            <i class="fas fa-search"></i>
                            Cari Kelas
                        </button>
                        <button class="btn btn-success" onclick="openClassModal()" style="margin-left: 10px;">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Kelas
                        </button>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="classSearch" placeholder="Cari kelas mengikut nama, tahun atau guru..." onkeyup="filterClasses()">
                </div>
                
                <!-- Classes Grid -->
                <div class="class-grid" id="classGrid">
                    <!-- Classes will be loaded here -->
                </div>
                
                <!-- Empty State -->
                <div class="empty-state" id="emptyClasses" style="display: none;">
                    <i class="fas fa-school"></i>
                    <h3>Tiada Kelas Ditemui</h3>
                    <p>Tiada kelas yang sepadan dengan carian anda. Cubalah menukar kata kunci carian.</p>
                    <button class="btn btn-secondary" onclick="resetClassSearch()">
                        <i class="fas fa-redo"></i>
                        Reset Carian
                    </button>
                </div>
            </div>
        </div>

        <!-- Years Tab -->
        <div class="tab-content" id="yearsTab">
            <div class="years-section">
                <div class="section-header">
                    <h3>Pengurusan Tahun</h3>
                    <div>
                        <button class="btn btn-secondary" onclick="searchYears()">
                            <i class="fas fa-search"></i>
                            Cari Tahun
                        </button>
                        <button class="btn btn-success" onclick="openYearModal()" style="margin-left: 10px;">
                            <i class="fas fa-plus-circle"></i>
                            Tambah Tahun
                        </button>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="yearSearch" placeholder="Cari tahun mengikut nama atau kod..." onkeyup="filterYears()">
                </div>
                
                <!-- Years List -->
                <div id="yearsList">
                    <!-- Years will be loaded here -->
                </div>
                
                <!-- Empty State -->
                <div class="empty-state" id="emptyYears" style="display: none;">
                    <i class="fas fa-layer-group"></i>
                    <h3>Tiada Tahun Ditemui</h3>
                    <p>Tiada tahun yang sepadan dengan carian anda. Cubalah menukar kata kunci carian.</p>
                    <button class="btn btn-secondary" onclick="resetYearSearch()">
                        <i class="fas fa-redo"></i>
                        Reset Carian
                    </button>
                </div>
            </div>
        </div>

        <!-- Structure Tab -->
        <div class="tab-content" id="structureTab">
            <div class="classes-section">
                <div class="section-header">
                    <h3>Struktur Akademik Sekolah</h3>
                    <button class="btn btn-info" onclick="generateStructureChart()">
                        <i class="fas fa-chart-bar"></i>
                        Lihat Graf
                    </button>
                </div>
                
                <!-- Academic Structure -->
                <div id="academicStructure">
                    <!-- Structure will be loaded here -->
                </div>
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
        const classModal = document.getElementById('classModal');
        const yearModal = document.getElementById('yearModal');
        const classGrid = document.getElementById('classGrid');
        const yearsList = document.getElementById('yearsList');
        const academicStructure = document.getElementById('academicStructure');
        const emptyClasses = document.getElementById('emptyClasses');
        const emptyYears = document.getElementById('emptyYears');

        // Current state
        let classesData = [];
        let yearsData = [];
        let filteredClasses = [];
        let filteredYears = [];
        let isEditingClass = false;
        let isEditingYear = false;
        let currentClassId = null;
        let currentYearId = null;
        let currentTab = 'classes';

        // Sample data for classes
        const sampleClasses = [
            {
                id: 'CLS001',
                name: '6A',
                year: '6',
                teacherId: 'GU01',
                teacherName: 'Cikgu Admin',
                teacherTitle: 'Guru Kelas & Pentadbir Sistem',
                room: 'Bilik 101',
                time: '8:00-10:00 pagi',
                studentCount: 45,
                subjectCount: 6,
                capacity: 50,
                notes: 'Kelas terakhir tahun 6, fokus peperiksaan UPSR',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'CLS002',
                name: '6B',
                year: '6',
                teacherId: 'GU02',
                teacherName: 'Cikgu Ahmad',
                teacherTitle: 'Guru Matematik',
                room: 'Bilik 102',
                time: '8:00-10:00 pagi',
                studentCount: 42,
                subjectCount: 6,
                capacity: 50,
                notes: 'Kelas sederhana, fokus bimbingan rakan sebaya',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'CLS003',
                name: '6C',
                year: '6',
                teacherId: 'GU03',
                teacherName: 'Cikgu Siti',
                teacherTitle: 'Guru Bahasa Melayu',
                room: 'Bilik 103',
                time: '10:30-12:30 tengah hari',
                studentCount: 38,
                subjectCount: 6,
                capacity: 50,
                notes: 'Kelas bimbingan khas, fokus asas 3M',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'CLS004',
                name: '5A',
                year: '5',
                teacherId: 'GU04',
                teacherName: 'Cikgu Ali',
                teacherTitle: 'Guru Sains',
                room: 'Bilik 201',
                time: '8:00-10:00 pagi',
                studentCount: 40,
                subjectCount: 6,
                capacity: 50,
                notes: 'Kelas cemerlang tahun 5',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'CLS005',
                name: '5B',
                year: '5',
                teacherId: 'GU05',
                teacherName: 'Cikgu Fatimah',
                teacherTitle: 'Guru Pendidikan Islam',
                room: 'Bilik 202',
                time: '10:30-12:30 tengah hari',
                studentCount: 35,
                subjectCount: 6,
                capacity: 50,
                notes: 'Kelas sederhana tahun 5',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'CLS006',
                name: '4A',
                year: '4',
                teacherId: 'GU02',
                teacherName: 'Cikgu Ahmad',
                teacherTitle: 'Guru Matematik',
                room: 'Bilik 301',
                time: '8:00-10:00 pagi',
                studentCount: 45,
                subjectCount: 5,
                capacity: 50,
                notes: 'Kelas terbaik tahun 4',
                status: 'active',
                createdDate: '01 Jan 2023'
            }
        ];

        // Sample data for years
        const sampleYears = [
            {
                id: 'YEAR001',
                name: 'Tahun 6',
                code: 'TAHUN6',
                headId: 'GU01',
                headName: 'Cikgu Admin',
                session: '2023/2024',
                targetStudents: 150,
                currentStudents: 125,
                classCount: 3,
                teacherCount: 3,
                averageMark: 78.5,
                notes: 'Tahun terakhir UPSR, fokus persediaan peperiksaan',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'YEAR002',
                name: 'Tahun 5',
                code: 'TAHUN5',
                headId: 'GU04',
                headName: 'Cikgu Ali',
                session: '2023/2024',
                targetStudents: 150,
                currentStudents: 75,
                classCount: 2,
                teacherCount: 2,
                averageMark: 75.2,
                notes: 'Persediaan awal untuk tahun 6',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'YEAR003',
                name: 'Tahun 4',
                code: 'TAHUN4',
                headId: 'GU02',
                headName: 'Cikgu Ahmad',
                session: '2023/2024',
                targetStudents: 150,
                currentStudents: 45,
                classCount: 1,
                teacherCount: 1,
                averageMark: 72.8,
                notes: 'Tahun peralihan ke pembelajaran lebih serius',
                status: 'active',
                createdDate: '01 Jan 2023'
            },
            {
                id: 'YEAR004',
                name: 'Tahun 3',
                code: 'TAHUN3',
                headId: 'GU03',
                headName: 'Cikgu Siti',
                session: '2023/2024',
                targetStudents: 100,
                currentStudents: 0,
                classCount: 0,
                teacherCount: 0,
                averageMark: 0,
                notes: 'Belum diaktifkan untuk sesi ini',
                status: 'inactive',
                createdDate: '01 Jan 2023'
            }
        ];

        // Initialize page
        function initializePage() {
            classesData = [...sampleClasses];
            yearsData = [...sampleYears];
            filteredClasses = [...classesData];
            filteredYears = [...yearsData];
            
            // Set up form submit handlers
            document.getElementById('classForm').addEventListener('submit', saveClass);
            document.getElementById('yearForm').addEventListener('submit', saveYear);
            
            // Load initial data
            loadClasses();
            loadYears();
            loadAcademicStructure();
            updateOverviewCards();
        }

        // Load classes
        function loadClasses() {
            classGrid.innerHTML = filteredClasses.map(cls => {
                const fillPercentage = Math.round((cls.studentCount / cls.capacity) * 100);
                const fillColor = fillPercentage >= 90 ? 'var(--danger)' : 
                                fillPercentage >= 75 ? 'var(--warning)' : 'var(--success)';
                
                return `
                    <div class="class-card">
                        <div class="class-card-header">
                            <div class="class-info">
                                <h4>Kelas ${cls.name}</h4>
                                <p>Tahun ${cls.year} • ${cls.room} • ${cls.time}</p>
                            </div>
                            <div style="position: relative;">
                                <div style="width: 60px; height: 60px; position: relative;">
                                    <svg width="60" height="60" viewBox="0 0 36 36" style="transform: rotate(-90deg);">
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="#e5e7eb" stroke-width="3"/>
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="${fillColor}" stroke-width="3" 
                                            stroke-dasharray="${fillPercentage}, 100"/>
                                    </svg>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                        <div style="font-size: 12px; font-weight: 700; color: ${fillColor};">${fillPercentage}%</div>
                                        <div style="font-size: 9px; color: var(--medium-gray);">isi</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="class-teacher">
                            <div class="teacher-avatar">${cls.teacherName.charAt(0)}</div>
                            <div class="teacher-info">
                                <h5>${cls.teacherName}</h5>
                                <p>${cls.teacherTitle}</p>
                            </div>
                        </div>
                        
                        <div class="class-stats">
                            <div class="class-stat">
                                <div class="value">${cls.studentCount}</div>
                                <div class="label">Pelajar</div>
                            </div>
                            <div class="class-stat">
                                <div class="value">${cls.subjectCount}</div>
                                <div class="label">Mata Pelajaran</div>
                            </div>
                            <div class="class-stat">
                                <div class="value">${cls.capacity}</div>
                                <div class="label">Kapasiti</div>
                            </div>
                        </div>
                        
                        <div class="class-actions">
                            <button class="action-btn view" onclick="viewClass('${cls.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                            <button class="action-btn edit" onclick="editClass('${cls.id}')">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteClass('${cls.id}')">
                                <i class="fas fa-trash"></i>
                                Padam
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Show/hide empty state
            if (filteredClasses.length === 0) {
                emptyClasses.style.display = 'block';
            } else {
                emptyClasses.style.display = 'none';
            }
        }

        // Load years
        function loadYears() {
            yearsList.innerHTML = filteredYears.map(year => {
                const fillPercentage = year.targetStudents > 0 ? 
                    Math.round((year.currentStudents / year.targetStudents) * 100) : 0;
                const statusClass = year.status === 'active' ? 'status-complete' : 'status-pending';
                const statusText = year.status === 'active' ? 'Aktif' : 'Tidak Aktif';
                
                return `
                    <div class="year-card">
                        <div class="year-card-header">
                            <div class="year-info">
                                <h4>${year.name} (${year.code})</h4>
                                <p>Sesi ${year.session} • Ketua Tahun: ${year.headName}</p>
                            </div>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                        
                        <div class="year-stats">
                            <div class="year-stat">
                                <div class="value">${year.classCount}</div>
                                <div class="label">Kelas</div>
                            </div>
                            <div class="year-stat">
                                <div class="value">${year.currentStudents}</div>
                                <div class="label">Pelajar</div>
                            </div>
                            <div class="year-stat">
                                <div class="value">${year.teacherCount}</div>
                                <div class="label">Guru</div>
                            </div>
                            <div class="year-stat">
                                <div class="value">${year.averageMark}%</div>
                                <div class="label">Purata</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span style="font-size: 12px; color: var(--medium-gray);">Kemasukan Pelajar:</span>
                                <span style="font-size: 12px; font-weight: 600; color: var(--primary);">${fillPercentage}%</span>
                            </div>
                            <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                                <div style="height: 100%; width: ${fillPercentage}%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 4px;"></div>
                            </div>
                            <div style="font-size: 11px; color: var(--medium-gray); margin-top: 5px; text-align: right;">
                                ${year.currentStudents} / ${year.targetStudents} pelajar
                            </div>
                        </div>
                        
                        ${year.notes ? `<p style="font-size: 13px; color: var(--medium-gray); margin-bottom: 15px;">${year.notes}</p>` : ''}
                        
                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button class="action-btn view" onclick="viewYear('${year.id}')">
                                <i class="fas fa-eye"></i>
                                Lihat
                            </button>
                            <button class="action-btn edit" onclick="editYear('${year.id}')">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button class="action-btn delete" onclick="deleteYear('${year.id}')">
                                <i class="fas fa-trash"></i>
                                Padam
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            // Show/hide empty state
            if (filteredYears.length === 0) {
                emptyYears.style.display = 'block';
            } else {
                emptyYears.style.display = 'none';
            }
        }

        // Load academic structure
        function loadAcademicStructure() {
            // Group classes by year
            const yearsWithClasses = yearsData.map(year => {
                const yearClasses = classesData.filter(cls => cls.year === year.name.split(' ')[1]);
                return { ...year, classes: yearClasses };
            }).filter(year => year.status === 'active');
            
            academicStructure.innerHTML = yearsWithClasses.map(year => `
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--primary); margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid var(--light-gray);">
                        ${year.name} (${year.session})
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                        ${year.classes.map(cls => `
                            <div style="background: var(--light-gray); padding: 15px; border-radius: 10px; border-left: 4px solid var(--primary);">
                                <div style="font-weight: 600; color: var(--primary); margin-bottom: 5px;">Kelas ${cls.name}</div>
                                <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 10px;">
                                    Guru: ${cls.teacherName} • ${cls.studentCount} pelajar
                                </div>
                                <div style="font-size: 11px; color: var(--medium-gray);">
                                    ${cls.room} • ${cls.time}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    ${year.classes.length === 0 ? 
                        `<div style="background: var(--light-gray); padding: 20px; border-radius: 10px; text-align: center; color: var(--medium-gray);">
                            <i class="fas fa-school" style="font-size: 24px; margin-bottom: 10px;"></i>
                            <div>Tiada kelas diaktifkan untuk ${year.name}</div>
                        </div>` : ''}
                </div>
            `).join('');
            
            // Add summary at the bottom
            const totalClasses = classesData.length;
            const totalStudents = classesData.reduce((sum, cls) => sum + cls.studentCount, 0);
            const totalTeachers = new Set(classesData.map(cls => cls.teacherId)).size;
            
            academicStructure.innerHTML += `
                <div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid var(--light-gray);">
                    <h3 style="color: var(--primary); margin-bottom: 15px;">Ringkasan Struktur</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                        <div style="background: white; padding: 15px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--primary);">${yearsData.filter(y => y.status === 'active').length}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Tahun Aktif</div>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--success);">${totalClasses}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Jumlah Kelas</div>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--info);">${totalStudents}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Jumlah Pelajar</div>
                        </div>
                        <div style="background: white; padding: 15px; border-radius: 10px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                            <div style="font-size: 24px; font-weight: 700; color: var(--warning);">${totalTeachers}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Jumlah Guru</div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Update overview cards
        function updateOverviewCards() {
            const totalClasses = classesData.length;
            const totalStudents = classesData.reduce((sum, cls) => sum + cls.studentCount, 0);
            const totalTeachers = new Set(classesData.map(cls => cls.teacherId)).size;
            const totalCapacity = classesData.reduce((sum, cls) => sum + cls.capacity, 0);
            const capacityPercentage = Math.round((totalStudents / totalCapacity) * 100);
            
            document.getElementById('totalClasses').textContent = totalClasses;
            document.getElementById('totalStudents').textContent = totalStudents;
            document.getElementById('totalTeachers').textContent = totalTeachers;
            document.getElementById('classCapacity').textContent = `${capacityPercentage}%`;
        }

        // Filter classes
        function filterClasses() {
            const searchTerm = document.getElementById('classSearch').value.toLowerCase();
            
            if (!searchTerm) {
                filteredClasses = [...classesData];
            } else {
                filteredClasses = classesData.filter(cls => {
                    return cls.name.toLowerCase().includes(searchTerm) ||
                           cls.year.includes(searchTerm) ||
                           cls.teacherName.toLowerCase().includes(searchTerm) ||
                           cls.room.toLowerCase().includes(searchTerm);
                });
            }
            
            loadClasses();
        }

        // Filter years
        function filterYears() {
            const searchTerm = document.getElementById('yearSearch').value.toLowerCase();
            
            if (!searchTerm) {
                filteredYears = [...yearsData];
            } else {
                filteredYears = yearsData.filter(year => {
                    return year.name.toLowerCase().includes(searchTerm) ||
                           year.code.toLowerCase().includes(searchTerm) ||
                           year.headName.toLowerCase().includes(searchTerm) ||
                           year.session.includes(searchTerm);
                });
            }
            
            loadYears();
        }

        // Search classes
        function searchClasses() {
            const searchInput = document.getElementById('classSearch');
            searchInput.focus();
        }

        // Search years
        function searchYears() {
            const searchInput = document.getElementById('yearSearch');
            searchInput.focus();
        }

        // Reset class search
        function resetClassSearch() {
            document.getElementById('classSearch').value = '';
            filterClasses();
        }

        // Reset year search
        function resetYearSearch() {
            document.getElementById('yearSearch').value = '';
            filterYears();
        }

        // Change tab
        function changeTab(tabName) {
            currentTab = tabName;
            
            // Update active tab button
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            // Show active tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`${tabName}Tab`).classList.add('active');
        }

        // Open class modal
        function openClassModal(edit = false, classId = null) {
            isEditingClass = edit;
            currentClassId = classId;
            
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('classForm');
            
            if (edit && classId) {
                modalTitle.textContent = 'Edit Kelas';
                const cls = classesData.find(c => c.id === classId);
                if (cls) {
                    document.getElementById('className').value = cls.name;
                    document.getElementById('classYear').value = cls.year;
                    document.getElementById('classTeacher').value = cls.teacherId;
                    document.getElementById('classRoom').value = cls.room;
                    document.getElementById('classTime').value = cls.time;
                    document.getElementById('classNotes').value = cls.notes;
                }
            } else {
                modalTitle.textContent = 'Tambah Kelas Baru';
                form.reset();
            }
            
            classModal.classList.add('active');
        }

        // Open year modal
        function openYearModal(edit = false, yearId = null) {
            isEditingYear = edit;
            currentYearId = yearId;
            
            const modalTitle = document.getElementById('yearModalTitle');
            const form = document.getElementById('yearForm');
            
            if (edit && yearId) {
                modalTitle.textContent = 'Edit Tahun';
                const year = yearsData.find(y => y.id === yearId);
                if (year) {
                    document.getElementById('yearName').value = year.name;
                    document.getElementById('yearCode').value = year.code;
                    document.getElementById('yearHead').value = year.headId;
                    document.getElementById('yearSession').value = year.session;
                    document.getElementById('yearTarget').value = year.targetStudents;
                    document.getElementById('yearNotes').value = year.notes;
                }
            } else {
                modalTitle.textContent = 'Tambah Tahun Baru';
                form.reset();
            }
            
            yearModal.classList.add('active');
        }

        // Close modal
        function closeModal() {
            classModal.classList.remove('active');
            yearModal.classList.remove('active');
            isEditingClass = false;
            isEditingYear = false;
            currentClassId = null;
            currentYearId = null;
        }

        // Save class
        function saveClass(event) {
            event.preventDefault();
            
            const className = document.getElementById('className').value;
            const classYear = document.getElementById('classYear').value;
            const classTeacher = document.getElementById('classTeacher').value;
            const classRoom = document.getElementById('classRoom').value;
            const classTime = document.getElementById('classTime').value;
            const classNotes = document.getElementById('classNotes').value;
            
            // Get teacher info
            const teacherOptions = {
                'GU01': { name: 'Cikgu Admin', title: 'Guru Kelas & Pentadbir Sistem' },
                'GU02': { name: 'Cikgu Ahmad', title: 'Guru Matematik' },
                'GU03': { name: 'Cikgu Siti', title: 'Guru Bahasa Melayu' },
                'GU04': { name: 'Cikgu Ali', title: 'Guru Sains' },
                'GU05': { name: 'Cikgu Fatimah', title: 'Guru Pendidikan Islam' }
            };
            
            const teacherInfo = teacherOptions[classTeacher] || { name: 'Cikgu', title: 'Guru Kelas' };
            
            if (isEditingClass && currentClassId) {
                // Update existing class
                const index = classesData.findIndex(c => c.id === currentClassId);
                if (index !== -1) {
                    classesData[index] = {
                        ...classesData[index],
                        name: className,
                        year: classYear,
                        teacherId: classTeacher,
                        teacherName: teacherInfo.name,
                        teacherTitle: teacherInfo.title,
                        room: classRoom,
                        time: classTime,
                        notes: classNotes
                    };
                    
                    showToast('Kelas Dikemaskini', `Kelas ${className} telah berjaya dikemaskini`, 'success');
                }
            } else {
                // Add new class
                const newClass = {
                    id: 'CLS' + (classesData.length + 1).toString().padStart(3, '0'),
                    name: className,
                    year: classYear,
                    teacherId: classTeacher,
                    teacherName: teacherInfo.name,
                    teacherTitle: teacherInfo.title,
                    room: classRoom,
                    time: classTime,
                    studentCount: 0,
                    subjectCount: 6,
                    capacity: 50,
                    notes: classNotes,
                    status: 'active',
                    createdDate: new Date().toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' })
                };
                
                classesData.push(newClass);
                showToast('Kelas Ditambah', `Kelas ${className} telah berjaya ditambah`, 'success');
            }
            
            // Update data
            filteredClasses = [...classesData];
            loadClasses();
            updateOverviewCards();
            closeModal();
        }

        // Save year
        function saveYear(event) {
            event.preventDefault();
            
            const yearName = document.getElementById('yearName').value;
            const yearCode = document.getElementById('yearCode').value;
            const yearHead = document.getElementById('yearHead').value;
            const yearSession = document.getElementById('yearSession').value;
            const yearTarget = document.getElementById('yearTarget').value;
            const yearNotes = document.getElementById('yearNotes').value;
            
            // Get head teacher info
            const headOptions = {
                'GU01': 'Cikgu Admin',
                'GU02': 'Cikgu Ahmad',
                'GU03': 'Cikgu Siti',
                'GU04': 'Cikgu Ali',
                'GU05': 'Cikgu Fatimah'
            };
            
            const headName = headOptions[yearHead] || 'Cikgu';
            
            if (isEditingYear && currentYearId) {
                // Update existing year
                const index = yearsData.findIndex(y => y.id === currentYearId);
                if (index !== -1) {
                    yearsData[index] = {
                        ...yearsData[index],
                        name: yearName,
                        code: yearCode,
                        headId: yearHead,
                        headName: headName,
                        session: yearSession,
                        targetStudents: parseInt(yearTarget) || 0,
                        notes: yearNotes
                    };
                    
                    showToast('Tahun Dikemaskini', `${yearName} telah berjaya dikemaskini`, 'success');
                }
            } else {
                // Add new year
                const newYear = {
                    id: 'YEAR' + (yearsData.length + 1).toString().padStart(3, '0'),
                    name: yearName,
                    code: yearCode,
                    headId: yearHead,
                    headName: headName,
                    session: yearSession,
                    targetStudents: parseInt(yearTarget) || 0,
                    currentStudents: 0,
                    classCount: 0,
                    teacherCount: 0,
                    averageMark: 0,
                    notes: yearNotes,
                    status: 'active',
                    createdDate: new Date().toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' })
                };
                
                yearsData.push(newYear);
                showToast('Tahun Ditambah', `${yearName} telah berjaya ditambah`, 'success');
            }
            
            // Update data
            filteredYears = [...yearsData];
            loadYears();
            updateOverviewCards();
            closeModal();
        }

        // View class details
        function viewClass(classId) {
            const cls = classesData.find(c => c.id === classId);
            if (cls) {
                alert(`Detail Kelas:\n\n` +
                      `Nama Kelas: ${cls.name}\n` +
                      `Tahun: ${cls.year}\n` +
                      `Guru Kelas: ${cls.teacherName}\n` +
                      `Jawatan: ${cls.teacherTitle}\n` +
                      `Bilik: ${cls.room}\n` +
                      `Waktu: ${cls.time}\n` +
                      `Jumlah Pelajar: ${cls.studentCount} / ${cls.capacity}\n` +
                      `Mata Pelajaran: ${cls.subjectCount}\n` +
                      `Catatan: ${cls.notes}\n` +
                      `Status: ${cls.status === 'active' ? 'Aktif' : 'Tidak Aktif'}\n` +
                      `Ditambah: ${cls.createdDate}`);
            }
        }

        // View year details
        function viewYear(yearId) {
            const year = yearsData.find(y => y.id === yearId);
            if (year) {
                const yearClasses = classesData.filter(cls => cls.year === year.name.split(' ')[1]);
                const classList = yearClasses.map(cls => `• Kelas ${cls.name} (${cls.teacherName})`).join('\n');
                
                alert(`Detail Tahun:\n\n` +
                      `Nama Tahun: ${year.name}\n` +
                      `Kod: ${year.code}\n` +
                      `Ketua Tahun: ${year.headName}\n` +
                      `Sesi: ${year.session}\n` +
                      `Sasaran Pelajar: ${year.targetStudents}\n` +
                      `Pelajar Semasa: ${year.currentStudents}\n` +
                      `Bilangan Kelas: ${year.classCount}\n` +
                      `Bilangan Guru: ${year.teacherCount}\n` +
                      `Purata Markah: ${year.averageMark}%\n` +
                      `Catatan: ${year.notes}\n` +
                      `Status: ${year.status === 'active' ? 'Aktif' : 'Tidak Aktif'}\n` +
                      `Ditambah: ${year.createdDate}\n\n` +
                      `Kelas dalam Tahun ini:\n${classList || 'Tiada kelas'}`);
            }
        }

        // Edit class
        function editClass(classId) {
            openClassModal(true, classId);
        }

        // Edit year
        function editYear(yearId) {
            openYearModal(true, yearId);
        }

        // Delete class
        function deleteClass(classId) {
            const cls = classesData.find(c => c.id === classId);
            if (!cls) return;
            
            if (confirm(`Adakah anda pasti ingin memadam Kelas ${cls.name}?\n\nTahun: ${cls.year}\nGuru: ${cls.teacherName}\n\nSemua data pelajar dalam kelas ini juga akan dipadam.`)) {
                // Remove class
                const index = classesData.findIndex(c => c.id === classId);
                if (index !== -1) {
                    classesData.splice(index, 1);
                    
                    // Update filtered data
                    filterClasses();
                    updateOverviewCards();
                    
                    showToast('Kelas Dipadam', `Kelas ${cls.name} telah berjaya dipadam`, 'success');
                }
            }
        }

        // Delete year
        function deleteYear(yearId) {
            const year = yearsData.find(y => y.id === yearId);
            if (!year) return;
            
            // Check if year has classes
            const yearClasses = classesData.filter(cls => cls.year === year.name.split(' ')[1]);
            
            if (yearClasses.length > 0) {
                alert(`Tidak boleh memadam ${year.name} kerana masih mempunyai ${yearClasses.length} kelas aktif.\n\nSila padam atau pindahkan semua kelas terlebih dahulu.`);
                return;
            }
            
            if (confirm(`Adakah anda pasti ingin memadam ${year.name}?\n\nSesi: ${year.session}\nKetua Tahun: ${year.headName}`)) {
                // Remove year
                const index = yearsData.findIndex(y => y.id === yearId);
                if (index !== -1) {
                    yearsData.splice(index, 1);
                    
                    // Update filtered data
                    filterYears();
                    updateOverviewCards();
                    
                    showToast('Tahun Dipadam', `${year.name} telah berjaya dipadam`, 'success');
                }
            }
        }

        // Generate structure chart
        function generateStructureChart() {
            alert('Membuka paparan graf struktur akademik...\n\nDalam versi penuh, anda akan melihat graf interaktif struktur sekolah.');
            // In a real app, this would open a chart visualization
        }

        // Reload data
        function muatSemulaData() {
            // Reset all searches
            resetClassSearch();
            resetYearSearch();
            
            // Reset to classes tab
            changeTab('classes');
            
            // Reload data (in real app, this would fetch from server)
            showToast('Data Dimuat Semula', 'Semua data kelas dan tahun telah disegarkan', 'success');
        }

        // Print structure
        function cetakStruktur() {
            alert('Menyediakan struktur akademik untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable report
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