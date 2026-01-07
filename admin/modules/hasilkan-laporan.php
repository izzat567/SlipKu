<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasilkan Laporan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/hasilkan-laporan.css">
    
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
                <h2>Hasilkan Laporan 📄</h2>
                <p>Hasilkan laporan prestasi akademik dalam pelbagai format</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i>
                    Set Semula
                </button>
                <button class="btn btn-info" onclick="lihatSemuaLaporan()">
                    <i class="fas fa-history"></i>
                    Lihat Sejarah
                </button>
            </div>
        </div>

        <!-- Report Options Section -->
        <div class="report-options">
            <div class="section-title">Pilih Jenis Laporan</div>
            
            <!-- Report Type Selection -->
            <div class="report-type-grid">
                <div class="report-type-card" onclick="selectReportType('slip')">
                    <div class="report-type-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="report-type-title">Slip Peperiksaan</div>
                    <div class="report-type-desc">Slip markah individu untuk setiap pelajar dengan gred dan kedudukan kelas.</div>
                </div>
                
                <div class="report-type-card" onclick="selectReportType('kelas')">
                    <div class="report-type-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="report-type-title">Laporan Kelas</div>
                    <div class="report-type-desc">Prestasi keseluruhan kelas dengan perbandingan dan statistik terperinci.</div>
                </div>
                
                <div class="report-type-card" onclick="selectReportType('prestasi')">
                    <div class="report-type-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="report-type-title">Analisis Prestasi</div>
                    <div class="report-type-desc">Analisis terperinci dengan graf dan carta untuk pemantauan prestasi.</div>
                </div>
                
                <div class="report-type-card" onclick="selectReportType('semua')">
                    <div class="report-type-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="report-type-title">Semua Pelajar</div>
                    <div class="report-type-desc">Senarai lengkap semua pelajar dengan markah dan gred semua mata pelajaran.</div>
                </div>
            </div>

            <!-- Slip Peperiksaan Configuration -->
            <div class="report-config" id="configSlip">
                <div class="section-title">Konfigurasi Slip Peperiksaan</div>
                
                <div class="config-grid">
                    <div class="config-group">
                        <label class="config-label required">Tahun</label>
                        <select class="config-select" id="slipYear">
                            <option value="">Sila Pilih</option>
                            <option value="1">Tahun 1</option>
                            <option value="2">Tahun 2</option>
                            <option value="3">Tahun 3</option>
                            <option value="4">Tahun 4</option>
                            <option value="5">Tahun 5</option>
                            <option value="6" selected>Tahun 6</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Kelas</label>
                        <select class="config-select" id="slipClass">
                            <option value="">Sila Pilih</option>
                            <option value="A" selected>Kelas A</option>
                            <option value="B">Kelas B</option>
                            <option value="C">Kelas C</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Jenis Peperiksaan</label>
                        <select class="config-select" id="slipExam">
                            <option value="">Sila Pilih</option>
                            <option value="ujian1">Ujian 1</option>
                            <option value="ujian2">Ujian 2</option>
                            <option value="pertengahan">Peperiksaan Pertengahan</option>
                            <option value="akhir" selected>Peperiksaan Akhir</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Semester</label>
                        <select class="config-select" id="slipSemester">
                            <option value="">Sila Pilih</option>
                            <option value="1">Semester 1</option>
                            <option value="2" selected>Semester 2</option>
                            <option value="all">Kedua-dua Semester</option>
                        </select>
                    </div>
                </div>
                
                <div class="config-group">
                    <label class="config-label">Nama Pelajar (Kosongkan untuk semua)</label>
                    <input type="text" class="config-input" id="slipStudent" placeholder="Cari atau masukkan nama pelajar...">
                </div>
                
                <div class="checkbox-group">
                    <div class="checkbox-title">Maklumat yang Ditujukkan:</div>
                    <div class="checkbox-grid">
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo1" checked>
                            <label for="slipInfo1">Maklumat Pelajar</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo2" checked>
                            <label for="slipInfo2">Markah & Gred</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo3" checked>
                            <label for="slipInfo3">Kedudukan dalam Kelas</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo4" checked>
                            <label for="slipInfo4">Purata Keseluruhan</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo5" checked>
                            <label for="slipInfo5">Catatan Guru</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="slipInfo6">
                            <label for="slipInfo6">Maklumat Ibu Bapa</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laporan Kelas Configuration -->
            <div class="report-config" id="configKelas">
                <div class="section-title">Konfigurasi Laporan Kelas</div>
                
                <div class="config-grid">
                    <div class="config-group">
                        <label class="config-label required">Tahun</label>
                        <select class="config-select" id="kelasYear">
                            <option value="">Sila Pilih</option>
                            <option value="1">Tahun 1</option>
                            <option value="2">Tahun 2</option>
                            <option value="3">Tahun 3</option>
                            <option value="4">Tahun 4</option>
                            <option value="5">Tahun 5</option>
                            <option value="6" selected>Tahun 6</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Kelas</label>
                        <select class="config-select" id="kelasClass">
                            <option value="">Sila Pilih</option>
                            <option value="A" selected>Kelas A</option>
                            <option value="B">Kelas B</option>
                            <option value="C">Kelas C</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Semester</label>
                        <select class="config-select" id="kelasSemester">
                            <option value="">Sila Pilih</option>
                            <option value="1">Semester 1</option>
                            <option value="2" selected>Semester 2</option>
                            <option value="all">Kedua-dua Semester</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label">Tahun Perbandingan</label>
                        <select class="config-select" id="kelasCompare">
                            <option value="">Tiada Perbandingan</option>
                            <option value="previous">Tahun Lepas</option>
                            <option value="semester">Semester Sebelum</option>
                        </select>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <div class="checkbox-title">Jenis Analisis:</div>
                    <div class="checkbox-grid">
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis1" checked>
                            <label for="kelasAnalysis1">Statistik Keseluruhan</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis2" checked>
                            <label for="kelasAnalysis2">Taburan Gred</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis3" checked>
                            <label for="kelasAnalysis3">Top 10 Pelajar</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis4" checked>
                            <label for="kelasAnalysis4">Perbandingan Mata Pelajaran</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis5">
                            <label for="kelasAnalysis5">Analisis Trend Bulanan</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="kelasAnalysis6">
                            <label for="kelasAnalysis6">Prestasi Berdasarkan Jantina</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analisis Prestasi Configuration -->
            <div class="report-config" id="configPrestasi">
                <div class="section-title">Konfigurasi Analisis Prestasi</div>
                
                <div class="config-grid">
                    <div class="config-group">
                        <label class="config-label">Tahun (Kosongkan untuk semua)</label>
                        <select class="config-select" id="prestasiYear">
                            <option value="">Semua Tahun</option>
                            <option value="1">Tahun 1</option>
                            <option value="2">Tahun 2</option>
                            <option value="3">Tahun 3</option>
                            <option value="4">Tahun 4</option>
                            <option value="5">Tahun 5</option>
                            <option value="6" selected>Tahun 6</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label">Kelas (Kosongkan untuk semua)</label>
                        <select class="config-select" id="prestasiClass">
                            <option value="">Semua Kelas</option>
                            <option value="A">Kelas A</option>
                            <option value="B">Kelas B</option>
                            <option value="C">Kelas C</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Tempoh Analisis</label>
                        <select class="config-select" id="prestasiPeriod">
                            <option value="">Sila Pilih</option>
                            <option value="semester">Semester Semasa</option>
                            <option value="tahun" selected>Sepanjang Tahun</option>
                            <option value="custom">Tempoh Tertentu</option>
                        </select>
                    </div>
                    
                    <div class="config-group" id="customDateGroup" style="display: none;">
                        <label class="config-label">Tempoh Tertentu</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="date" class="config-date" id="prestasiDateFrom" style="flex: 1;">
                            <span style="display: flex; align-items: center;">hingga</span>
                            <input type="date" class="config-date" id="prestasiDateTo" style="flex: 1;">
                        </div>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <div class="checkbox-title">Jenis Analisis dan Graf:</div>
                    <div class="checkbox-grid">
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart1" checked>
                            <label for="prestasiChart1">Graf Trend Prestasi</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart2" checked>
                            <label for="prestasiChart2">Perbandingan Kelas</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart3" checked>
                            <label for="prestasiChart3">Analisis Mata Pelajaran</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart4">
                            <label for="prestasiChart4">Analisis Berdasarkan Jantina</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart5">
                            <label for="prestasiChart5">Analisis Perkembangan Individu</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="prestasiChart6">
                            <label for="prestasiChart6">Ramalan Prestasi</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semua Pelajar Configuration -->
            <div class="report-config" id="configSemua">
                <div class="section-title">Konfigurasi Laporan Semua Pelajar</div>
                
                <div class="config-grid">
                    <div class="config-group">
                        <label class="config-label">Tahun (Kosongkan untuk semua)</label>
                        <select class="config-select" id="semuaYear">
                            <option value="">Semua Tahun</option>
                            <option value="1">Tahun 1</option>
                            <option value="2">Tahun 2</option>
                            <option value="3">Tahun 3</option>
                            <option value="4">Tahun 4</option>
                            <option value="5">Tahun 5</option>
                            <option value="6" selected>Tahun 6</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label">Kelas (Kosongkan untuk semua)</label>
                        <select class="config-select" id="semuaClass">
                            <option value="">Semua Kelas</option>
                            <option value="A">Kelas A</option>
                            <option value="B">Kelas B</option>
                            <option value="C">Kelas C</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label required">Jenis Peperiksaan</label>
                        <select class="config-select" id="semuaExam">
                            <option value="">Sila Pilih</option>
                            <option value="ujian1">Ujian 1</option>
                            <option value="ujian2">Ujian 2</option>
                            <option value="pertengahan">Peperiksaan Pertengahan</option>
                            <option value="akhir" selected>Peperiksaan Akhir</option>
                        </select>
                    </div>
                    
                    <div class="config-group">
                        <label class="config-label">Susunan</label>
                        <select class="config-select" id="semuaSort">
                            <option value="name">Mengikut Nama</option>
                            <option value="rank" selected>Mengikut Kedudukan</option>
                            <option value="class">Mengikut Kelas</option>
                            <option value="average">Mengikut Purata</option>
                        </select>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <div class="checkbox-title">Maklumat yang Ditujukkan:</div>
                    <div class="checkbox-grid">
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo1" checked>
                            <label for="semuaInfo1">Maklumat Asas Pelajar</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo2" checked>
                            <label for="semuaInfo2">Markah Semua Mata Pelajaran</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo3" checked>
                            <label for="semuaInfo3">Gred dan Purata</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo4" checked>
                            <label for="semuaInfo4">Kedudukan dalam Kelas</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo5">
                            <label for="semuaInfo5">Kedudukan dalam Tahun</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox" id="semuaInfo6">
                            <label for="semuaInfo6">Perbandingan Semester</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Format Selection -->
            <div class="format-selection">
                <div class="checkbox-title">Pilih Format Output:</div>
                <div class="format-options">
                    <div class="format-option format-pdf selected" onclick="selectFormat('pdf')">
                        <i class="fas fa-file-pdf format-icon"></i>
                        <span>PDF Document</span>
                    </div>
                    <div class="format-option format-excel" onclick="selectFormat('excel')">
                        <i class="fas fa-file-excel format-icon"></i>
                        <span>Excel Spreadsheet</span>
                    </div>
                    <div class="format-option format-word" onclick="selectFormat('word')">
                        <i class="fas fa-file-word format-icon"></i>
                        <span>Word Document</span>
                    </div>
                </div>
            </div>

            <!-- Generate Button -->
            <div style="margin-top: 30px; text-align: center;">
                <button class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;" onclick="generateReport()">
                    <i class="fas fa-magic"></i>
                    Hasilkan Laporan Sekarang
                </button>
            </div>
        </div>

        <!-- Report Preview -->
        <div class="report-preview" id="reportPreview">
            <div class="preview-header">
                <div class="preview-title">Pratonton Laporan</div>
                <div>
                    <button class="btn btn-secondary" onclick="refreshPreview()">
                        <i class="fas fa-sync-alt"></i>
                        Muat Semula
                    </button>
                    <button class="btn btn-success" onclick="downloadReport()" style="margin-left: 10px;">
                        <i class="fas fa-download"></i>
                        Muat Turun
                    </button>
                </div>
            </div>
            
            <div class="preview-container">
                <div class="preview-placeholder" id="previewPlaceholder">
                    <i class="fas fa-file-alt"></i>
                    <h3>Tiada Prantonton</h3>
                    <p>Hasilkan laporan untuk melihat pratonton di sini</p>
                </div>
                
                <div class="preview-content" id="previewContent">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Generated Reports -->
        <div class="generated-reports">
            <div class="section-title">Laporan Terkini yang Dihasilkan</div>
            <p style="color: var(--medium-gray); margin-bottom: 20px; font-size: 14px;">Berikut adalah laporan yang telah anda hasilkan baru-baru ini:</p>
            
            <div class="reports-list" id="reportsList">
                <!-- Reports will be loaded here -->
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
            <p id="toastMessage">Laporan telah berjaya dihasilkan.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const toast = document.getElementById('toast');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const reportsList = document.getElementById('reportsList');
        const previewContent = document.getElementById('previewContent');
        const previewPlaceholder = document.getElementById('previewPlaceholder');

        // Current state
        let selectedReportType = 'slip';
        let selectedFormat = 'pdf';
        let generatedReports = [];
        let currentReportData = null;

        // Sample generated reports
        const sampleReports = [
            {
                id: 'R001',
                name: 'Slip Peperiksaan Akhir Tahun 6 Kelas A',
                type: 'slip',
                format: 'pdf',
                date: '15 Nov 2023',
                time: '10:30 AM',
                size: '2.4 MB',
                url: '#'
            },
            {
                id: 'R002',
                name: 'Laporan Prestasi Kelas 6A Semester 2',
                type: 'kelas',
                format: 'excel',
                date: '12 Nov 2023',
                time: '2:15 PM',
                size: '1.8 MB',
                url: '#'
            },
            {
                id: 'R003',
                name: 'Analisis Prestasi Tahunan 2023',
                type: 'prestasi',
                format: 'pdf',
                date: '8 Nov 2023',
                time: '9:45 AM',
                size: '3.2 MB',
                url: '#'
            },
            {
                id: 'R004',
                name: 'Senarai Markah Semua Pelajar Tahun 6',
                type: 'semua',
                format: 'word',
                date: '5 Nov 2023',
                time: '4:20 PM',
                size: '1.5 MB',
                url: '#'
            }
        ];

        // Initialize page
        function initializePage() {
            generatedReports = [...sampleReports];
            
            // Set up event listeners
            setupEventListeners();
            
            // Load generated reports
            loadGeneratedReports();
            
            // Show slip report config by default
            selectReportType('slip');
            
            // Set current date for date inputs
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('prestasiDateFrom').value = today;
            document.getElementById('prestasiDateTo').value = today;
        }

        // Set up event listeners
        function setupEventListeners() {
            // Period selection for prestasi report
            document.getElementById('prestasiPeriod').addEventListener('change', function() {
                const customDateGroup = document.getElementById('customDateGroup');
                if (this.value === 'custom') {
                    customDateGroup.style.display = 'block';
                } else {
                    customDateGroup.style.display = 'none';
                }
            });
        }

        // Select report type
        function selectReportType(type) {
            selectedReportType = type;
            
            // Remove selected class from all cards
            document.querySelectorAll('.report-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Hide all config sections
            document.querySelectorAll('.report-config').forEach(config => {
                config.classList.remove('active');
            });
            
            // Show selected config section
            document.getElementById(`config${type.charAt(0).toUpperCase() + type.slice(1)}`).classList.add('active');
        }

        // Select format
        function selectFormat(format) {
            selectedFormat = format;
            
            // Remove selected class from all format options
            document.querySelectorAll('.format-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
        }

        // Generate report
        function generateReport() {
            // Validate required fields
            if (!validateReportConfig()) {
                return;
            }
            
            // Show loading spinner
            loadingSpinner.classList.add('active');
            
            // Get report configuration
            const config = getReportConfig();
            
            // Simulate report generation
            setTimeout(() => {
                // Hide loading spinner
                loadingSpinner.classList.remove('active');
                
                // Generate new report
                const newReport = {
                    id: 'R' + (generatedReports.length + 1).toString().padStart(3, '0'),
                    name: generateReportName(config),
                    type: selectedReportType,
                    format: selectedFormat,
                    date: new Date().toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' }),
                    time: new Date().toLocaleTimeString('ms-MY', { hour: '2-digit', minute: '2-digit' }),
                    size: (Math.random() * 3 + 1).toFixed(1) + ' MB',
                    url: '#',
                    config: config
                };
                
                // Add to beginning of list
                generatedReports.unshift(newReport);
                
                // Update reports list
                loadGeneratedReports();
                
                // Show report preview
                showReportPreview(newReport);
                
                // Show success message
                showToast('Laporan Berjaya Dihasilkan!', 
                    `Laporan "${newReport.name}" telah berjaya dihasilkan dalam format ${selectedFormat.toUpperCase()}.`, 
                    'success');
            }, 2000);
        }

        // Validate report configuration
        function validateReportConfig() {
            let isValid = true;
            let errorMessage = '';
            
            // Get required fields based on report type
            const requiredFields = {
                slip: ['slipYear', 'slipClass', 'slipExam', 'slipSemester'],
                kelas: ['kelasYear', 'kelasClass', 'kelasSemester'],
                prestasi: ['prestasiPeriod'],
                semua: ['semuaExam']
            };
            
            const fields = requiredFields[selectedReportType] || [];
            
            // Check each required field
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field && !field.value) {
                    isValid = false;
                    const fieldName = field.previousElementSibling?.textContent || fieldId;
                    errorMessage += `• ${fieldName.replace('*', '').trim()} diperlukan\n`;
                }
            });
            
            // Check custom date range
            if (selectedReportType === 'prestasi' && 
                document.getElementById('prestasiPeriod').value === 'custom') {
                const dateFrom = document.getElementById('prestasiDateFrom').value;
                const dateTo = document.getElementById('prestasiDateTo').value;
                
                if (!dateFrom || !dateTo) {
                    isValid = false;
                    errorMessage += '• Sila isi kedua-dua tarikh untuk tempoh tertentu\n';
                } else if (new Date(dateTo) < new Date(dateFrom)) {
                    isValid = false;
                    errorMessage += '• Tarikh akhir mesti selepas tarikh mula\n';
                }
            }
            
            // Show error message if any
            if (!isValid) {
                showToast('Ralat dalam Konfigurasi', errorMessage, 'error');
            }
            
            return isValid;
        }

        // Get report configuration
        function getReportConfig() {
            const config = {
                type: selectedReportType,
                format: selectedFormat,
                timestamp: new Date().toISOString()
            };
            
            // Add config based on report type
            switch (selectedReportType) {
                case 'slip':
                    config.year = document.getElementById('slipYear').value;
                    config.class = document.getElementById('slipClass').value;
                    config.exam = document.getElementById('slipExam').value;
                    config.semester = document.getElementById('slipSemester').value;
                    config.student = document.getElementById('slipStudent').value;
                    config.options = getCheckedOptions('slipInfo');
                    break;
                    
                case 'kelas':
                    config.year = document.getElementById('kelasYear').value;
                    config.class = document.getElementById('kelasClass').value;
                    config.semester = document.getElementById('kelasSemester').value;
                    config.compare = document.getElementById('kelasCompare').value;
                    config.analysis = getCheckedOptions('kelasAnalysis');
                    break;
                    
                case 'prestasi':
                    config.year = document.getElementById('prestasiYear').value;
                    config.class = document.getElementById('prestasiClass').value;
                    config.period = document.getElementById('prestasiPeriod').value;
                    if (config.period === 'custom') {
                        config.dateFrom = document.getElementById('prestasiDateFrom').value;
                        config.dateTo = document.getElementById('prestasiDateTo').value;
                    }
                    config.charts = getCheckedOptions('prestasiChart');
                    break;
                    
                case 'semua':
                    config.year = document.getElementById('semuaYear').value;
                    config.class = document.getElementById('semuaClass').value;
                    config.exam = document.getElementById('semuaExam').value;
                    config.sort = document.getElementById('semuaSort').value;
                    config.options = getCheckedOptions('semuaInfo');
                    break;
            }
            
            return config;
        }

        // Get checked options by prefix
        function getCheckedOptions(prefix) {
            const options = [];
            for (let i = 1; i <= 6; i++) {
                const checkbox = document.getElementById(`${prefix}${i}`);
                if (checkbox && checkbox.checked) {
                    options.push(checkbox.nextElementSibling.textContent);
                }
            }
            return options;
        }

        // Generate report name
        function generateReportName(config) {
            let name = '';
            
            switch (config.type) {
                case 'slip':
                    name = `Slip Peperiksaan ${getExamText(config.exam)} `;
                    if (config.student) {
                        name += `- ${config.student}`;
                    } else {
                        name += `Tahun ${config.year} Kelas ${config.class}`;
                    }
                    break;
                    
                case 'kelas':
                    name = `Laporan Prestasi Kelas ${config.class} Tahun ${config.year} `;
                    name += `Semester ${config.semester}`;
                    if (config.compare) {
                        name += ` (Perbandingan ${config.compare})`;
                    }
                    break;
                    
                case 'prestasi':
                    name = `Analisis Prestasi `;
                    if (config.year) {
                        name += `Tahun ${config.year} `;
                    }
                    if (config.class) {
                        name += `Kelas ${config.class} `;
                    }
                    name += `(${getPeriodText(config.period)})`;
                    break;
                    
                case 'semua':
                    name = `Senarai Markah ${getExamText(config.exam)} `;
                    if (config.year) {
                        name += `Tahun ${config.year} `;
                    }
                    if (config.class) {
                        name += `Kelas ${config.class}`;
                    } else {
                        name += `Semua Kelas`;
                    }
                    break;
            }
            
            return name;
        }

        // Helper functions for text conversion
        function getExamText(code) {
            const exams = {
                'ujian1': 'Ujian 1',
                'ujian2': 'Ujian 2',
                'pertengahan': 'Peperiksaan Pertengahan',
                'akhir': 'Peperiksaan Akhir'
            };
            return exams[code] || code;
        }

        function getPeriodText(period) {
            const periods = {
                'semester': 'Semester Semasa',
                'tahun': 'Sepanjang Tahun',
                'custom': 'Tempoh Tertentu'
            };
            return periods[period] || period;
        }

        // Load generated reports
        function loadGeneratedReports() {
            reportsList.innerHTML = generatedReports.map(report => `
                <div class="report-item">
                    <div class="report-info">
                        <div class="report-icon ${report.format}">
                            <i class="fas fa-file-${report.format === 'excel' ? 'excel' : report.format === 'word' ? 'word' : 'pdf'}"></i>
                        </div>
                        <div class="report-details">
                            <h4>${report.name}</h4>
                            <p>Dihasilkan pada ${report.date} ${report.time} • ${report.size} • ${report.format.toUpperCase()}</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button class="action-btn view" onclick="viewReport('${report.id}')">
                            <i class="fas fa-eye"></i>
                            Lihat
                        </button>
                        <button class="action-btn download" onclick="downloadSpecificReport('${report.id}')">
                            <i class="fas fa-download"></i>
                            Muat Turun
                        </button>
                        <button class="action-btn delete" onclick="deleteReport('${report.id}')">
                            <i class="fas fa-trash"></i>
                            Padam
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Show report preview
        function showReportPreview(report) {
            // Show preview section
            document.getElementById('reportPreview').classList.add('active');
            
            // Hide placeholder, show content
            previewPlaceholder.style.display = 'none';
            previewContent.classList.add('active');
            
            // Store current report data
            currentReportData = report;
            
            // Generate preview content based on report type
            let previewHTML = '';
            
            switch (report.type) {
                case 'slip':
                    previewHTML = generateSlipPreview(report);
                    break;
                case 'kelas':
                    previewHTML = generateKelasPreview(report);
                    break;
                case 'prestasi':
                    previewHTML = generatePrestasiPreview(report);
                    break;
                case 'semua':
                    previewHTML = generateSemuaPreview(report);
                    break;
            }
            
            previewContent.innerHTML = previewHTML;
            
            // Scroll to preview
            document.getElementById('reportPreview').scrollIntoView({ behavior: 'smooth' });
        }

        // Generate slip preview
        function generateSlipPreview(report) {
            return `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: var(--primary); margin-bottom: 5px;">SLIP PEPERIKSAAN</h1>
                        <h3 style="color: var(--medium-gray); font-weight: normal;">${getExamText(report.config.exam)}</h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
                        <div>
                            <h3 style="color: var(--primary); margin-bottom: 15px;">MAKLUMAT PELAJAR</h3>
                            <div style="background: var(--light-gray); padding: 20px; border-radius: 10px;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                    <div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">Nama:</div>
                                        <div style="font-weight: 600;">AHMAD BIN ALI</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">No. Kad Pengenalan:</div>
                                        <div style="font-weight: 600;">080101-01-1234</div>
                                    </div>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">Tahun/Kelas:</div>
                                        <div style="font-weight: 600;">${report.config.year}/${report.config.class}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 12px; color: var(--medium-gray);">Semester:</div>
                                        <div style="font-weight: 600;">${report.config.semester}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 style="color: var(--primary); margin-bottom: 15px;">PRESTASI</h3>
                            <div style="background: var(--primary-light); padding: 20px; border-radius: 10px; text-align: center;">
                                <div style="font-size: 12px; color: var(--medium-gray);">Purata Keseluruhan</div>
                                <div style="font-size: 36px; font-weight: 800; color: var(--primary); margin: 10px 0;">95.2%</div>
                                <div style="font-size: 14px; color: var(--medium-gray);">Kedudukan: 1/45</div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 style="color: var(--primary); margin-bottom: 15px;">MARKAH MATA PELAJARAN</h3>
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                        <thead>
                            <tr style="background: var(--light-gray);">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb;">Mata Pelajaran</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb;">Markah</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb;">Gred</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb;">Kedudukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">Matematik</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">98%</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A+</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">1</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">Bahasa Melayu</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">96%</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">2</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">Bahasa Inggeris</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">94%</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">3</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">Sains</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">97%</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A+</td>
                                <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">1</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--light-gray);">
                        <div style="font-size: 12px; color: var(--medium-gray);">Dihasilkan oleh: Cikgu Admin</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">Tarikh: ${report.date} ${report.time}</div>
                    </div>
                </div>
            `;
        }

        // Generate kelas preview
        function generateKelasPreview(report) {
            return `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: var(--primary); margin-bottom: 5px;">LAPORAN PRESTASI KELAS</h1>
                        <h3 style="color: var(--medium-gray); font-weight: normal;">Tahun ${report.config.year} Kelas ${report.config.class} - Semester ${report.config.semester}</h3>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px;">
                        <div style="background: var(--light-gray); padding: 20px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 10px;">Jumlah Pelajar</div>
                            <div style="font-size: 32px; font-weight: 700; color: var(--primary);">45</div>
                        </div>
                        <div style="background: var(--light-gray); padding: 20px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 10px;">Purata Kelas</div>
                            <div style="font-size: 32px; font-weight: 700; color: var(--success);">78.5%</div>
                        </div>
                        <div style="background: var(--light-gray); padding: 20px; border-radius: 10px; text-align: center;">
                            <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 10px;">Pelajar Cemerlang (A)</div>
                            <div style="font-size: 32px; font-weight: 700; color: var(--warning);">12</div>
                        </div>
                    </div>
                    
                    <h3 style="color: var(--primary); margin-bottom: 15px;">STATISTIK PRESTASI</h3>
                    <div style="background: var(--light-gray); padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                            <div>
                                <div style="font-size: 14px; font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Taburan Gred</div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>A: 12 pelajar (26.7%)</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>B: 18 pelajar (40.0%)</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>C: 10 pelajar (22.2%)</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray);">
                                    <span>D/E: 5 pelajar (11.1%)</span>
                                </div>
                            </div>
                            <div>
                                <div style="font-size: 14px; font-weight: 600; color: var(--dark-gray); margin-bottom: 10px;">Perbandingan Mata Pelajaran</div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>Matematik: 82.5% (Tertinggi)</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>Bahasa Melayu: 78.3%</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray); margin-bottom: 5px;">
                                    <span>Bahasa Inggeris: 75.8% (Terendah)</span>
                                </div>
                                <div style="font-size: 13px; color: var(--medium-gray);">
                                    <span>Sains: 80.2%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 style="color: var(--primary); margin-bottom: 15px;">10 PELAJAR TERBAIK</h3>
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: var(--light-gray);">
                                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb;">Ked.</th>
                                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e5e7eb;">Nama Pelajar</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb;">Purata</th>
                                    <th style="padding: 12px; text-align: center; border-bottom: 2px solid #e5e7eb;">Gred</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">1</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">AHMAD BIN ALI</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">95.2%</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A+</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">2</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">SITI NOR AISYAH</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">93.8%</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">3</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #e5e7eb;">MOHD AMIR BIN HASSAN</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">92.5%</td>
                                    <td style="padding: 12px; text-align: center; border-bottom: 1px solid #e5e7eb;">A</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--light-gray); text-align: center;">
                        <div style="font-size: 12px; color: var(--medium-gray);">Laporan ini dihasilkan oleh Sistem SlipKu pada ${report.date} ${report.time}</div>
                    </div>
                </div>
            `;
        }

        // Generate prestasi preview (simplified)
        function generatePrestasiPreview(report) {
            return `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: var(--primary); margin-bottom: 5px;">ANALISIS PRESTASI TERPERINCI</h1>
                        <h3 style="color: var(--medium-gray); font-weight: normal;">${generateReportName(report.config)}</h3>
                    </div>
                    
                    <div style="text-align: center; padding: 40px 20px; background: var(--light-gray); border-radius: 10px; margin-bottom: 30px;">
                        <i class="fas fa-chart-line" style="font-size: 48px; color: var(--primary); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--dark-gray); margin-bottom: 10px;">Analisis Prestasi dengan Graf Interaktif</h3>
                        <p style="color: var(--medium-gray); max-width: 600px; margin: 0 auto;">
                            Laporan ini mengandungi analisis prestasi terperinci dengan pelbagai jenis graf dan carta untuk pemantauan prestasi pelajar.
                            Dalam versi penuh, anda akan melihat:
                        </p>
                        <ul style="text-align: left; display: inline-block; margin-top: 15px; color: var(--medium-gray);">
                            <li>Graf trend prestasi mengikut bulan</li>
                            <li>Perbandingan prestasi antara kelas</li>
                            <li>Analisis mata pelajaran terperinci</li>
                            <li>Prestasi berdasarkan demografi</li>
                            <li>Carta perkembangan individu</li>
                        </ul>
                    </div>
                    
                    <div style="background: var(--primary-light); padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                        <h4 style="color: var(--primary); margin-bottom: 15px;">Ringkasan Konfigurasi Analisis</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                            <div>
                                <div style="font-size: 12px; color: var(--medium-gray);">Tahun:</div>
                                <div style="font-weight: 600;">${report.config.year || 'Semua'}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: var(--medium-gray);">Kelas:</div>
                                <div style="font-weight: 600;">${report.config.class || 'Semua'}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: var(--medium-gray);">Tempoh:</div>
                                <div style="font-weight: 600;">${getPeriodText(report.config.period)}</div>
                            </div>
                            <div>
                                <div style="font-size: 12px; color: var(--medium-gray);">Jumlah Carta:</div>
                                <div style="font-weight: 600;">${report.config.charts.length}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--light-gray); text-align: center;">
                        <div style="font-size: 12px; color: var(--medium-gray);">Analisis dihasilkan oleh Sistem SlipKu pada ${report.date} ${report.time}</div>
                    </div>
                </div>
            `;
        }

        // Generate semua preview
        function generateSemuaPreview(report) {
            return `
                <div style="background: white; padding: 30px; border-radius: 10px; max-width: 800px; margin: 0 auto;">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h1 style="color: var(--primary); margin-bottom: 5px;">SENARAI MARKAH SEMUA PELAJAR</h1>
                        <h3 style="color: var(--medium-gray); font-weight: normal;">${generateReportName(report.config)}</h3>
                    </div>
                    
                    <div style="text-align: center; padding: 30px 20px; background: var(--light-gray); border-radius: 10px; margin-bottom: 30px;">
                        <i class="fas fa-users" style="font-size: 48px; color: var(--primary); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--dark-gray); margin-bottom: 10px;">Senarai Lengkap Markah Pelajar</h3>
                        <p style="color: var(--medium-gray); max-width: 600px; margin: 0 auto;">
                            Laporan ini mengandungi senarai lengkap semua pelajar dengan markah dan gred untuk semua mata pelajaran.
                            Data disusun mengikut ${report.config.sort === 'name' ? 'nama' : report.config.sort === 'rank' ? 'kedudukan' : report.config.sort === 'class' ? 'kelas' : 'purata'}.
                        </p>
                    </div>
                    
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                            <thead>
                                <tr style="background: var(--light-gray);">
                                    <th style="padding: 10px; text-align: left; border-bottom: 2px solid #e5e7eb;">No.</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 2px solid #e5e7eb;">Nama Pelajar</th>
                                    <th style="padding: 10px; text-align: left; border-bottom: 2px solid #e5e7eb;">Kelas</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">Matematik</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">B. Melayu</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">B. Inggeris</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">Sains</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">Purata</th>
                                    <th style="padding: 10px; text-align: center; border-bottom: 2px solid #e5e7eb;">Ked.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">1</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">AHMAD BIN ALI</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">6A</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">98% (A+)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">96% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">94% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">97% (A+)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: var(--primary);">95.2%</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">1</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">2</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">SITI NOR AISYAH</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">6A</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">95% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">94% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">92% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">94% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: var(--primary);">93.8%</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">2</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">3</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">MOHD AMIR BIN HASSAN</td>
                                    <td style="padding: 10px; border-bottom: 1px solid #e5e7eb;">6A</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">93% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">92% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">90% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb;">95% (A)</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: var(--primary);">92.5%</td>
                                    <td style="padding: 10px; text-align: center; border-bottom: 1px solid #e5e7eb; font-weight: 600;">3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid var(--light-gray); text-align: center;">
                        <div style="font-size: 12px; color: var(--medium-gray);">Senarai dihasilkan oleh Sistem SlipKu pada ${report.date} ${report.time}</div>
                        <div style="font-size: 11px; color: var(--medium-gray); margin-top: 5px;">Menunjukkan 3 daripada 45 rekod</div>
                    </div>
                </div>
            `;
        }

        // View report
        function viewReport(reportId) {
            const report = generatedReports.find(r => r.id === reportId);
            if (report) {
                showReportPreview(report);
                showToast('Laporan Dibuka', `Membuka laporan "${report.name}"`, 'success');
            }
        }

        // Download report
        function downloadReport() {
            if (!currentReportData) {
                showToast('Tiada Laporan', 'Sila hasilkan laporan terlebih dahulu', 'error');
                return;
            }
            
            showToast('Muat Turun Bermula', `Memuat turun "${currentReportData.name}"...`, 'success');
            
            // Simulate download
            setTimeout(() => {
                showToast('Muat Turun Selesai', `Laporan telah berjaya dimuat turun`, 'success');
            }, 1000);
        }

        // Download specific report
        function downloadSpecificReport(reportId) {
            const report = generatedReports.find(r => r.id === reportId);
            if (report) {
                showToast('Muat Turun Bermula', `Memuat turun "${report.name}"...`, 'success');
                
                // Simulate download
                setTimeout(() => {
                    showToast('Muat Turun Selesai', `Laporan telah berjaya dimuat turun`, 'success');
                }, 1000);
            }
        }

        // Delete report
        function deleteReport(reportId) {
            if (confirm('Adakah anda pasti ingin memadam laporan ini?')) {
                const index = generatedReports.findIndex(r => r.id === reportId);
                if (index !== -1) {
                    const reportName = generatedReports[index].name;
                    generatedReports.splice(index, 1);
                    
                    // Update reports list
                    loadGeneratedReports();
                    
                    // Hide preview if deleted report was being viewed
                    if (currentReportData && currentReportData.id === reportId) {
                        document.getElementById('reportPreview').classList.remove('active');
                        previewContent.classList.remove('active');
                        previewPlaceholder.style.display = 'block';
                        currentReportData = null;
                    }
                    
                    showToast('Laporan Dipadam', `Laporan "${reportName}" telah dipadam`, 'success');
                }
            }
        }

        // Refresh preview
        function refreshPreview() {
            if (currentReportData) {
                showReportPreview(currentReportData);
                showToast('Pratonton Dimuat Semula', 'Pratonton laporan telah dikemas kini', 'success');
            }
        }

        // Reset form
        function resetForm() {
            if (confirm('Adakah anda pasti ingin set semula semua konfigurasi laporan?')) {
                // Reset all form fields
                document.querySelectorAll('.config-select, .config-input, .config-date').forEach(field => {
                    if (field.id.includes('Year') && field.id !== 'semuaYear' && field.id !== 'prestasiYear') {
                        field.value = '6';
                    } else if (field.id.includes('Class') && field.id !== 'semuaClass' && field.id !== 'prestasiClass') {
                        field.value = 'A';
                    } else if (field.id.includes('Exam') && field.id !== 'semuaExam') {
                        field.value = 'akhir';
                    } else if (field.id.includes('Semester')) {
                        field.value = '2';
                    } else if (field.id.includes('Period')) {
                        field.value = 'tahun';
                    } else if (field.type === 'text' || field.type === 'date') {
                        field.value = '';
                    } else {
                        field.value = '';
                    }
                });
                
                // Reset checkboxes
                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    if (checkbox.id.includes('slipInfo') || checkbox.id.includes('kelasAnalysis') || 
                        checkbox.id.includes('prestasiChart') || checkbox.id.includes('semuaInfo')) {
                        // First 4 checkboxes checked by default
                        const num = parseInt(checkbox.id.replace(/\D/g, ''));
                        checkbox.checked = num <= 4;
                    }
                });
                
                // Reset format selection
                selectedFormat = 'pdf';
                document.querySelectorAll('.format-option').forEach(option => {
                    option.classList.remove('selected');
                    if (option.classList.contains('format-pdf')) {
                        option.classList.add('selected');
                    }
                });
                
                // Hide preview
                document.getElementById('reportPreview').classList.remove('active');
                previewContent.classList.remove('active');
                previewPlaceholder.style.display = 'block';
                currentReportData = null;
                
                // Show success message
                showToast('Form Diset Semula', 'Semua konfigurasi laporan telah dikembalikan kepada tetapan asal', 'success');
            }
        }

        // View all reports history
        function lihatSemuaLaporan() {
            alert('Anda akan dibawa ke halaman sejarah laporan yang mengandungi semua laporan yang pernah dihasilkan.');
            // In a real app, this would redirect to reports history page
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
            
            // Auto hide after 4 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
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