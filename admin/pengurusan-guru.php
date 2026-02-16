<!-- sambungkan connection/config.php -->
<?php
include '../config/connect.php';

// Query untuk dapatkan data guru
$sql = "SELECT id, nama, email, no_telefon, status FROM guru WHERE 1";
$result = mysqli_query($conn, $sql);

// Dapatkan senarai status unik untuk dropdown (pilihan)
$status_sql = mysqli_query($conn, "SELECT DISTINCT status FROM guru");
$status_options = [];
while ($s = mysqli_fetch_assoc($status_sql)) {
    $status_options[] = $s['status'];
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengurusan Guru - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/pengurusan-pelajar.css">
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
    <?php include './includes/header.php'; ?>

    <!-- include side bar -->
    <?php include './includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pengurusan Guru</h2>
                <p>Urus dan kelola maklumat guru</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-primary" onclick="tambahGuru()">
                    <i class="fas fa-user-plus"></i>
                    Tambah Guru Baru
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
                <input type="text" class="search-input" placeholder="Cari guru dengan nama atau emel..." id="searchInput">
                <button class="btn btn-primary" onclick="cariGuru()">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </div>
            <div class="filter-grid">
                <!-- Filter Status (Aktif/Tidak Aktif) - dinamik dari DB -->
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="filter-select" id="filterStatus" onchange="filterGuru()">
                        <option value="">Semua Status</option>
                        <?php foreach ($status_options as $status): ?>
                            <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Medan filter lain boleh ditambah kemudian, diabaikan buat masa ini -->
                <div class="filter-group">
                    <label class="filter-label">Jantina</label>
                    <select class="filter-select" id="filterJantina" onchange="filterGuru()">
                        <option value="">Semua Jantina</option>
                        <option value="L">Lelaki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jabatan</label>
                    <select class="filter-select" id="filterJabatan" onchange="filterGuru()">
                        <option value="">Semua Jabatan</option>
                        <option value="akademik">Akademik</option>
                        <option value="kokurikulum">Kokurikulum</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterTahun" onchange="filterGuru()">
                        <option value="">Semua Tahun</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                    </select>
                </div>
            </div>  
        </div>

        <!-- Teachers Table -->
        <div class="students-table-container">
            <div class="table-header">
                <h3>Senarai Guru</h3>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="window.open('./modules/Senarai-guru.php')">
                        <i class="fas fa-download"></i>
                        Eksport
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>NAMA GURU</th>
                            <th>EMAIL</th>
                            <th>NO. TELEFON</th>
                            <th>STATUS</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="teachersTableBody">
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <!-- Simpan data atribut untuk carian dan penapis -->
                        <tr data-nama="<?= htmlspecialchars($row['nama']) ?>"
                            data-email="<?= htmlspecialchars($row['email']) ?>"
                            data-status="<?= htmlspecialchars($row['status']) ?>">
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['no_telefon']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <form action="./kemaskini-guru.php" method="get">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                        Kemaskini
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (placeholder) -->
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

    <script>
        // Hilangkan loading overlay selepas halaman siap dimuat
        window.addEventListener('load', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        // Fungsi untuk memuat semula halaman
        function muatSemula() {
            location.reload();
        }

        // Fungsi untuk pergi ke halaman tambah guru
        function tambahGuru() {
            window.location.href = 'tambah-guru.php'; // atau 'daftar-guru.php' jika berbeza
        }

        // Fungsi utama yang mengaplikasikan carian dan penapis
        function applyFilters() {
            const input = document.getElementById('searchInput').value.toLowerCase().trim();
            const statusDipilih = document.getElementById('filterStatus').value;
            // Filter lain (jantina, jabatan, tahun) tidak digunakan kerana tiada data, tapi kita boleh abaikan
            // atau gunakan untuk penyaringan jika ada data atribut nanti.

            const rows = document.querySelectorAll('#teachersTableBody tr');

            rows.forEach(row => {
                const nama = row.getAttribute('data-nama').toLowerCase();
                const email = row.getAttribute('data-email').toLowerCase();
                const statusRow = row.getAttribute('data-status');

                let show = true;

                // Carian (nama atau email)
                if (input !== '' && !nama.includes(input) && !email.includes(input)) {
                    show = false;
                }

                // Penapis status
                if (statusDipilih !== '' && statusRow !== statusDipilih) {
                    show = false;
                }

                // (Penapis lain boleh ditambah di sini jika ada data atribut)

                row.style.display = show ? '' : 'none';
            });
        }

        // Fungsi carian
        function cariGuru() {
            applyFilters();
        }

        // Fungsi penapisan
        function filterGuru() {
            applyFilters();
        }

        // Optional: tekan Enter dalam kotak carian terus mencari
        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Fungsi pagination (placeholder)
        function changePage(page) {
            alert('Navigasi ke halaman ' + page + ' (akan dilaksanakan kemudian)');
        }
    </script>
</body>
</html>