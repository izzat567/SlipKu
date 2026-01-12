<!-- sambungkan  connection/config.php-->
<?php 

include '../../config/connect.php';
$sql = "SELECT * FROM pelajar";

$sql = "
SELECT pelajar.nama, kelas.nama AS kelas
FROM pelajar
JOIN kelas ON pelajar.id_kelas = kelas.id
";

$result = mysqli_query($conn, $sql);

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
            <h2>Selamat kembali, Cikgu!</h2>
            <p>Berikut adalah ringkasan pengurusan peperiksaan untuk hari ini</p>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <!-- Total Students -->
            <div class="stat-card" style="animation-delay: 0.1s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>JUMLAH PELAJAR</h3>
                        <div class="stat-value" id="totalStudents"></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>    </span>
                </div>
            </div>

            <!-- total teachers -->
            <div class="stat-card" style="animation-delay: 0.2s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>JUMLAH GURU</h3>
                        <div class="stat-value" id="completedMarks"></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span></span>
                </div>
            </div>

            <!-- Total class-->
            <div class="stat-card" style="animation-delay: 0.3s;">
                <div class="stat-header">
                    <div class="stat-info">
                        <h3>JUMLAH KELAS</h3>
                        <div class="stat-value" id="pendingMarks"></div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-exclamation-circle"></i>
                    <span> </span>
                </div>
            </div>
        </div>

    
        

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Pengurusan Pelajar</h3>
                <p>mengurus maklumat pelajar</p>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Pengurusan Guru</h3>
                <p>mengurus maklumat Guru</p>
            </a>

            <a href="#" class="action-card">
                <div class="action-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Pengurusan Kelas</h3>
                <p>mengurus maklumat Kelas</p>
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
                            <th>KELAS</th>
                            <th>JANTINA</th>
                            <th>STATUS</th>
                            <th>TARIKH</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTable">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['id_kelas']; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['kelas']; ?></td>
                            <td><?= $row['jantina']; ?></td>
                            <td><?= $row['status']; ?></td>
                        </tr>
                        <?php } ?>
                         
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