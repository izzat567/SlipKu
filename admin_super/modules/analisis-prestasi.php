<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Prestasi - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/analisis-prestasi.css">
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
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
                <h2>Analisis Prestasi 📊</h2>
                <p>Prestasi akademik dan analisis pencapaian pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemula()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="janaLaporan()">
                    <i class="fas fa-file-export"></i>
                    Jana Laporan
                </button>
                <button class="btn btn-info" onclick="cetakAnalisis()">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterTahun" onchange="updateCharts()">
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
                    <label class="filter-label">Kelas</label>
                    <select class="filter-select" id="filterKelas" onchange="updateCharts()">
                        <option value="">Semua Kelas</option>
                        <option value="A">Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Semester</label>
                    <select class="filter-select" id="filterSemester" onchange="updateCharts()">
                        <option value="1">Semester 1</option>
                        <option value="2" selected>Semester 2</option>
                        <option value="all">Kedua-dua Semester</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Mata Pelajaran</label>
                    <select class="filter-select" id="filterSubject" onchange="updateCharts()">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01">PJ & Kesihatan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-card purple">
                <div class="stat-header">
                    <h4>Purata Keseluruhan</h4>
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div class="stat-value" id="overallAverage">78.5%</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+2.3% dari semester lepas</span>
                </div>
            </div>
            
            <div class="stat-card green">
                <div class="stat-header">
                    <h4>Pelajar Cemerlang</h4>
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <div class="stat-value" id="excellentStudents">45</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5 pelajar</span>
                </div>
            </div>
            
            <div class="stat-card blue">
                <div class="stat-header">
                    <h4>Pelajar Perlukan Bimbingan</h4>
                    <div class="stat-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                </div>
                <div class="stat-value" id="needHelpStudents">12</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-3 pelajar</span>
                </div>
            </div>
            
            <div class="stat-card orange">
                <div class="stat-header">
                    <h4>Kadar Kehadiran</h4>
                    <div class="stat-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
                <div class="stat-value" id="attendanceRate">94.2%</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+1.5%</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="charts-grid">
            <!-- Grade Distribution Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Taburan Gred Keseluruhan</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('gradeChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            <!-- Performance Trend Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Tren Prestasi Mengikut Bulan</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('trendChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Subject Comparison Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Perbandingan Mata Pelajaran</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('subjectChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>

            <!-- Class Performance Chart -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Prestasi Mengikut Kelas</h3>
                    <div class="btn btn-secondary" onclick="toggleChartType('classChart')">
                        <i class="fas fa-exchange-alt"></i>
                        Tukar Jenis
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Students -->
        <div class="top-students">
            <div class="section-header">
                <h3>10 Pelajar Terbaik</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaPelajar()">
                    <i class="fas fa-list"></i>
                    Lihat Semua
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>KEDUDUKAN</th>
                            <th>NAMA PELAJAR</th>
                            <th>TAHUN/KELAS</th>
                            <th>PURATA MARKAH</th>
                            <th>GRED</th>
                            <th>PRESTASI</th>
                        </tr>
                    </thead>
                    <tbody id="topStudentsTable">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Subject Performance -->
        <div class="subject-performance">
            <div class="section-header">
                <h3>Prestasi Mengikut Mata Pelajaran</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaMataPelajaran()">
                    <i class="fas fa-book"></i>
                    Semua Mata Pelajaran
                </button>
            </div>
            <div class="subject-list" id="subjectList">
                <!-- Data akan dipenuhi oleh JavaScript -->
            </div>
        </div>
    </main>

   
</body>
</html>