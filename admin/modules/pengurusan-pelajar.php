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
    <title>Pengurusan Pelajar - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/pengurusan-pelajar.css">
    
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
                <h2>Pengurusan Pelajar</h2>
                <p>Urus dan kelola maklumat pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" onclick="tambahPelajar()">
                    <i class="fas fa-user-plus"></i>
                    Tambah Pelajar Baru
                </button>
                <button class="btn btn-secondary" onclick="muatSemula()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Cari pelajar dengan nama atau ID..." id="searchInput">
                <button class="btn btn-primary" onclick="cariPelajar()">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterTahun" onchange="filterPelajar()">
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
                    <select class="filter-select" id="filterKelas" onchange="filterPelajar()">
                        <option value="">Semua Kelas</option>
                        <option value="alpha">Kelas ALPHA</option>
                        <option value="beta">Kelas BETA</option>
                        <option value="gamma">Kelas GAMMA</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="filterStatus" onchange="filterPelajar()">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jantina</label>
                    <select class="filter-select" id="filterJantina" onchange="filterPelajar()">
                        <option value="">Semua Jantina</option>
                        <option value="L">Lelaki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>  
        </div>

        <!-- Students Table -->
        <div class="students-table-container">
            <div class="table-header">
                <h3>Senarai Pelajar</h3>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="window.open('Senarai-pelajar.php')" value="Export Excel">
                        <i class="fas fa-download"></i>
                        Eksport
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID PELAJAR</th>
                            <th>NAMA PELAJAR</th>
                            <th>JANTINA</th>
                            <th>NO.KP</th>
                            <th>STATUS</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
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

            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" onclick="changePage('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn" onclick="changePage(2)">2</button>
                <button class="pagination-btn" onclick="changePage(3)">3</button>
                <button class="pagination-btn" onclick="changePage(4)">4</button>
                <button class="pagination-btn" onclick="changePage(5)">5</button>
                <span class="pagination-info">Muka surat 1 daripada 8</span>
                <button class="pagination-btn" onclick="changePage('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </main>

</body>
</html>