<!-- sambungkan  connection/config.php-->
<?php 
    include'./../connection/config.php'
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Pemuka Admin - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
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
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h2>Selamat kembali, Cikgu! 👋</h2>
            <p>Berikut adalah ringkasan pengurusan peperiksaan untuk hari ini</p>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <!-- Total Students -->
            <div class="stat-card" style="animation-delay: 0.1s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>JUMLAH PELAJAR</h3>
                        <div class="stat-value" id="totalStudents">245</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8 dari bulan lepas</span>
                </div>
            </div>

            <!-- Completed Marks -->
            <div class="stat-card" style="animation-delay: 0.2s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>MARKAH LENGKAP</h3>
                        <div class="stat-value" id="completedMarks">198</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>81% telah lengkap</span>
                </div>
            </div>

            <!-- Pending Marks -->
            <div class="stat-card" style="animation-delay: 0.3s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>MARKAH TERTUNGGU</h3>
                        <div class="stat-value" id="pendingMarks">47</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>19% perlu perhatian</span>
                </div>
            </div>

            <!-- Average Marks -->
            <div class="stat-card" style="animation-delay: 0.4s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>PURATA MARKAH</h3>
                        <div class="stat-value" id="averageMarks">76.8</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+1.5 dari penggal lepas</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <!-- Performance Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Prestasi Keseluruhan</h3>
                    <div class="chart-actions">
                        <select class="chart-select" id="chartRange">
                            <option>7 Hari Lalu</option>
                            <option>30 Hari Lalu</option>
                            <option>Penggal Ini</option>
                        </select>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <!-- Subject Distribution -->
            <div class="chart-container">
                <div class="chart-header">
                    <h3>Taburan Mata Pelajaran</h3>
                </div>
                <div style="height: 300px;">
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="section-header">
                <h3>Aktiviti Terkini</h3>
                <a href="#" class="view-all">
                    Lihat Semua
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dimasukkan markah Matematik</h4>
                        <p>ID Pelajar: P001 • Markah: 85/100</p>
                        <div class="activity-time">2 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dikemaskini markah Bahasa Melayu</h4>
                        <p>ID Pelajar: P045 • Markah: 92/100</p>
                        <div class="activity-time">4 jam lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="activity-content">
                        <h4>Dihasilkan laporan prestasi</h4>
                        <p>Kelas 6A ringkasan penggal</p>
                        <div class="activity-time">Semalam</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h3>Tambah Markah Baru</h3>
                <p>Masukkan markah dan gred peperiksaan pelajar</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-list-alt"></i>
                </div>
                <h3>Lihat Semua Rekod</h3>
                <p>Semak pangkalan data markah pelajar</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Kemaskini Rekod</h3>
                <p>Ubah suai markah dan maklumat pelajar sedia ada</p>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <h3>Hasilkan Laporan</h3>
                <p>Cipta laporan peperiksaan menyeluruh</p>
            </a>
        </div>

        <!-- Recent Students Table -->
        <div class="recent-students">
            <div class="section-header">
                <h3>Kemaskini Pelajar Terkini</h3>
                <button class="btn btn-secondary" onclick="refreshStudents()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID PELAJAR</th>
                            <th>NAMA</th>
                            <th>MATA PELAJARAN</th>
                            <th>MARKAH</th>
                            <th>STATUS</th>
                            <th>TARIKH</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTable">
                        <!-- panggil data dari mysql untuk masuk dalam table -->
                         <?php 
                            $papar=mysqli_query($connect,"SELECT * FROM pelajar");
                            while($row=mysqli_fetch_array($papar)){
                            }
                          ?>

                          <tr>
                            <td><?php echo $row['pelajar_id'] ?></td>
                            <td><?php echo $row['nama_penuh'] ?></td>
                            <td><?php echo $row['nokp'] ?></td>
                            <td><?php echo $row['jantina'] ?></td>
                            <td><?php echo $row['pelajar_id'] ?></td>
                            <td><?php echo $row['pelajar_id'] ?></td>
                          </tr>
                         
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Quick Actions (Bottom) -->
        <div class="quick-actions" style="display: none;" id="mobileQuickActions">
            <button class="btn btn-primary" onclick="showNotification('Tindakan berjaya!')">
                <i class="fas fa-plus"></i>
                Tambah Markah
            </button>
            <button class="btn btn-secondary" onclick="refreshStudents()">
                <i class="fas fa-sync-alt"></i>
                Muat Semula
            </button>
        </div>
    </main>
</body>
</html>