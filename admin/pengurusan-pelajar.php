<!-- sambungkan connection/config.php -->
<?php 
include '../config/connect.php';

$sql = "
SELECT pelajar.id, pelajar.nama, pelajar.jantina, pelajar.no_kp, kelas.nama AS kelas
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
                <input type="text" class="search-input" placeholder="Cari pelajar dengan nama atau No. KP..." id="searchInput">
                <button class="btn btn-primary" onclick="cariPelajar()">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select class="filter-select" id="filterKelas" onchange="filterPelajar()">
                        <option value="">Semua Kelas</option>
                        <?php
                        // Ambil senarai kelas unik dari database untuk dropdown
                        $kelas_sql = mysqli_query($conn, "SELECT DISTINCT nama FROM kelas ORDER BY nama");
                        while ($kelas_row = mysqli_fetch_assoc($kelas_sql)) {
                            echo '<option value="' . htmlspecialchars($kelas_row['nama']) . '">' . htmlspecialchars($kelas_row['nama']) . '</option>';
                        }
                        ?>
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
                <!-- Dropdown Tahun dan Status boleh ditambah kemudian jika diperlukan -->
            </div>  
        </div>

        <!-- Students Table -->
        <div class="students-table-container">
            <div class="table-header">
                <h3>Senarai Pelajar</h3>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="window.open('./modules/Senarai-pelajar.php')">
                        <i class="fas fa-download"></i>
                        Eksport
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>NAMA PELAJAR</th>
                            <th>JANTINA</th>
                            <th>KELAS</th>
                            <th>NO. KP</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <!-- Set data-atribut untuk memudahkan penapisan -->
                        <tr data-nama="<?= htmlspecialchars($row['nama']) ?>" 
                            data-jantina="<?= htmlspecialchars($row['jantina']) ?>" 
                            data-kelas="<?= htmlspecialchars($row['kelas']) ?>" 
                            data-nokp="<?= htmlspecialchars($row['no_kp']) ?>">
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['jantina']) ?></td>
                            <td><?= htmlspecialchars($row['kelas']) ?></td>
                            <td><?= htmlspecialchars($row['no_kp']) ?></td>
                            <td>
                                <form action="./kemaskini-pelajar.php" method="get">
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

        // Fungsi untuk tambah pelajar - arah ke halaman tambah
        function tambahPelajar() {
            window.location.href = 'tambah-pelajar.php';
        }

        // Fungsi untuk muat semula halaman
        function muatSemula() {
            location.reload();
        }

        // Fungsi carian berdasarkan input teks
        function cariPelajar() {
            const input = document.getElementById('searchInput').value.toLowerCase().trim();
            const rows = document.querySelectorAll('#studentsTableBody tr');

            rows.forEach(row => {
                const nama = row.getAttribute('data-nama').toLowerCase();
                const nokp = row.getAttribute('data-nokp').toLowerCase();
                // Jika input kosong atau nama/nokp mengandungi teks carian, tunjukkan baris
                if (input === '' || nama.includes(input) || nokp.includes(input)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Selepas carian, kita juga perlu memastikan filter lain masih diguna pakai?
            // Idea: selepas carian, kita boleh apply filter semula. Atau kita gabungkan.
            // Untuk mudah, kita panggil filterPelajar() supaya filter terkini juga dikenakan.
            filterPelajar();
        }

        // Fungsi penapisan berdasarkan dropdown
        function filterPelajar() {
            const kelasDipilih = document.getElementById('filterKelas').value;
            const jantinaDipilih = document.getElementById('filterJantina').value;

            const rows = document.querySelectorAll('#studentsTableBody tr');

            rows.forEach(row => {
                // Dapatkan nilai dari data-atribut
                const kelasRow = row.getAttribute('data-kelas');
                const jantinaRow = row.getAttribute('data-jantina');

                let show = true;

                // Filter kelas
                if (kelasDipilih !== '' && kelasRow !== kelasDipilih) {
                    show = false;
                }

                // Filter jantina
                if (jantinaDipilih !== '' && jantinaRow !== jantinaDipilih) {
                    show = false;
                }

                // Papar atau sembunyi baris
                row.style.display = show ? '' : 'none';
            });

            // Selepas filter, kita perlu pastikan carian juga diambil kira.
            // Jadi kita panggil semula fungsi cariPelajar()? Itu boleh menyebabkan rekursi.
            // Sebaliknya, kita boleh gabungkan logik carian di sini.
            // Pendekatan mudah: panggil cariPelajar() yang akan set semula display berdasarkan carian,
            // tetapi cariPelajar() juga panggil filterPelajar() -> loop tak terhingga.
            // Jadi kita perlu asingkan.

            // Alternatif: satukan logik dalam satu fungsi, atau gunakan pemboleh ubah.
            // Untuk kesederhanaan, kita akan gabungkan dalam satu fungsi `applyFilterAndSearch`.
            // Tapi kita akan kekalkan dua fungsi berasingan dan pastikan ia tidak saling panggil tanpa kawalan.

            // Kita boleh buat macam ni: Dalam cariPelajar, selepas set paparan berdasarkan carian,
            // kita panggil filterPelajar tanpa parameter, tapi dalam filterPelajar kita perlu tahu
            // baris mana yang telah disembunyikan oleh carian. Jadi kita perlu menyimpan state.

            // Pendekatan terbaik: Satukan dalam satu fungsi yang dipanggil setiap kali carian ATAU filter berubah.
            // Kita akan buat fungsi baru `applyFilters()`.

            // Untuk sekarang, kita akan ubah suai: Dalam cariPelajar, kita hanya set pemboleh ubah,
            // kemudian panggil applyFilters. Dalam filterPelajar, kita juga panggil applyFilters.
            // applyFilters akan membaca semua input dan filter serentak.
        }

        // Fungsi utama yang mengaplikasikan kedua-dua carian dan penapis
        function applyFilters() {
            const input = document.getElementById('searchInput').value.toLowerCase().trim();
            const kelasDipilih = document.getElementById('filterKelas').value;
            const jantinaDipilih = document.getElementById('filterJantina').value;

            const rows = document.querySelectorAll('#studentsTableBody tr');

            rows.forEach(row => {
                const nama = row.getAttribute('data-nama').toLowerCase();
                const nokp = row.getAttribute('data-nokp').toLowerCase();
                const kelasRow = row.getAttribute('data-kelas');
                const jantinaRow = row.getAttribute('data-jantina');

                let show = true;

                // Semak carian
                if (input !== '' && !nama.includes(input) && !nokp.includes(input)) {
                    show = false;
                }

                // Semak penapis kelas
                if (kelasDipilih !== '' && kelasRow !== kelasDipilih) {
                    show = false;
                }

                // Semak penapis jantina
                if (jantinaDipilih !== '' && jantinaRow !== jantinaDipilih) {
                    show = false;
                }

                row.style.display = show ? '' : 'none';
            });
        }

        // Override fungsi sedia ada
        function cariPelajar() {
            applyFilters();
        }

        function filterPelajar() {
            applyFilters();
        }

        // Tambah event listener pada input carian untuk trigger semasa menaip (optional)
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