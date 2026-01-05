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
    <link rel="stylesheet" href="../css/semua-rekod.css">
    
</head>
<body>
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