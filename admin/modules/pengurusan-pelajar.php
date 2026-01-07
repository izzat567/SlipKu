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
                <h2>Pengurusan Pelajar 📋</h2>
                <p>Urus dan kelola maklumat pelajar sekolah rendah</p>
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
                        <option value="A">Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
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
                <h3>Senarai Pelajar (245 pelajar)</h3>
                <div class="btn-group">
                    <button class="btn btn-secondary" onclick="eksportData()">
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
                            <th>TAHUN/KELAS</th>
                            <th>JANTINA</th>
                            <th>NO. KP</th>
                            <th>STATUS</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                        <!-- Data akan dipenuhi oleh JavaScript -->
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

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const studentsTableBody = document.getElementById('studentsTableBody');

        // Sample data pelajar sekolah rendah
        const pelajarData = [
            { id: 'P001', nama: 'AHMAD BIN ALI', tahun: '1', kelas: 'A', jantina: 'L', nokp: '180101010101', status: 'active' },
            { id: 'P002', nama: 'SITI BINTI ABU', tahun: '1', kelas: 'A', jantina: 'P', nokp: '180102020202', status: 'active' },
            { id: 'P003', nama: 'MOHD AMIR BIN HASSAN', tahun: '1', kelas: 'B', jantina: 'L', nokp: '180103030303', status: 'active' },
            { id: 'P004', nama: 'NOR AISYAH BINTI RAMLI', tahun: '2', kelas: 'A', jantina: 'P', nokp: '170201010101', status: 'active' },
            { id: 'P005', nama: 'ALI BIN KASSIM', tahun: '2', kelas: 'B', jantina: 'L', nokp: '170202020202', status: 'active' },
            { id: 'P006', nama: 'FATIMAH BINTI ZAINAL', tahun: '3', kelas: 'A', jantina: 'P', nokp: '160301010101', status: 'active' },
            { id: 'P007', nama: 'WAN AHMAD BIN WAN', tahun: '3', kelas: 'B', jantina: 'L', nokp: '160302020202', status: 'inactive' },
            { id: 'P008', nama: 'NURUL HIDAYAH BINTI KAMAL', tahun: '4', kelas: 'A', jantina: 'P', nokp: '150401010101', status: 'active' },
            { id: 'P009', nama: 'HASAN BIN HUSIN', tahun: '4', kelas: 'B', jantina: 'L', nokp: '150402020202', status: 'active' },
            { id: 'P010', nama: 'AMIRAH BINTI ISMAIL', tahun: '5', kelas: 'A', jantina: 'P', nokp: '140501010101', status: 'active' },
            { id: 'P011', nama: 'ZULKIFLI BIN ZAINAL', tahun: '5', kelas: 'B', jantina: 'L', nokp: '140502020202', status: 'active' },
            { id: 'P012', nama: 'ROHAYU BINTI RAHIM', tahun: '6', kelas: 'A', jantina: 'P', nokp: '130601010101', status: 'active' },
            { id: 'P013', nama: 'FAIZ BIN FARID', tahun: '6', kelas: 'B', jantina: 'L', nokp: '130602020202', status: 'inactive' },
            { id: 'P014', nama: 'AINA BINTI ADNAN', tahun: '1', kelas: 'C', jantina: 'P', nokp: '180104040404', status: 'active' },
            { id: 'P015', nama: 'HAKIM BIN HALIM', tahun: '2', kelas: 'C', jantina: 'L', nokp: '170203030303', status: 'active' }
        ];

        // Populate students table
        function populateStudentsTable(data = pelajarData) {
            studentsTableBody.innerHTML = data.map(pelajar => `
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${pelajar.nama.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${pelajar.id}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">${formatNoKP(pelajar.nokp)}</div>
                            </div>
                        </div>
                    </td>
                    <td>${pelajar.nama}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--primary);">Tahun ${pelajar.tahun}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">Kelas ${pelajar.kelas}</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 8px; height: 8px; border-radius: 50%; background: ${pelajar.jantina === 'L' ? '#4f46e5' : '#f59e0b'};"></div>
                            <span>${pelajar.jantina === 'L' ? 'Lelaki' : 'Perempuan'}</span>
                        </div>
                    </td>
                    <td>${formatNoKP(pelajar.nokp)}</td>
                    <td>
                        <span class="status-badge ${pelajar.status === 'active' ? 'status-active' : 'status-inactive'}">
                            ${pelajar.status === 'active' ? 'AKTIF' : 'TIDAK AKTIF'}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" title="Lihat" onclick="lihatPelajar('${pelajar.id}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" title="Edit" onclick="editPelajar('${pelajar.id}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" title="Padam" onclick="padamPelajar('${pelajar.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Format No. KP
        function formatNoKP(nokp) {
            return nokp.replace(/(\d{6})(\d{2})(\d{4})/, '$1-$2-$3');
        }

        // Filter pelajar
        function filterPelajar() {
            const tahun = document.getElementById('filterTahun').value;
            const kelas = document.getElementById('filterKelas').value;
            const status = document.getElementById('filterStatus').value;
            const jantina = document.getElementById('filterJantina').value;
            
            let filteredData = pelajarData;
            
            if (tahun) {
                filteredData = filteredData.filter(p => p.tahun === tahun);
            }
            
            if (kelas) {
                filteredData = filteredData.filter(p => p.kelas === kelas);
            }
            
            if (status) {
                filteredData = filteredData.filter(p => p.status === status);
            }
            
            if (jantina) {
                filteredData = filteredData.filter(p => p.jantina === jantina);
            }
            
            populateStudentsTable(filteredData);
        }

        // Search pelajar
        function cariPelajar() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            if (!searchTerm) {
                populateStudentsTable();
                return;
            }
            
            const filteredData = pelajarData.filter(pelajar => 
                pelajar.nama.toLowerCase().includes(searchTerm) || 
                pelajar.id.toLowerCase().includes(searchTerm) ||
                pelajar.nokp.includes(searchTerm)
            );
            
            populateStudentsTable(filteredData);
        }

        // Actions functions
        function lihatPelajar(id) {
            alert(`Lihat maklumat pelajar: ${id}`);
            // Boleh redirect ke halaman detail
        }

        function editPelajar(id) {
            alert(`Edit maklumat pelajar: ${id}`);
            // Boleh redirect ke halaman edit
        }

        function padamPelajar(id) {
            if (confirm(`Adakah anda pasti ingin memadam pelajar ${id}?`)) {
                alert(`Pelajar ${id} telah dipadam (simulasi)`);
                // Dalam realiti, buat API call untuk delete
            }
        }

        function tambahPelajar() {
            alert('Buka modal/form untuk tambah pelajar baru');
            // Boleh buka modal atau redirect ke halaman tambah
        }

        function muatSemula() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterKelas').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterJantina').value = '';
            populateStudentsTable();
        }

        function eksportData() {
            alert('Data pelajar sedang dieksport...');
            // Dalam realiti, buat API call untuk export
        }

        function changePage(page) {
            alert(`Pergi ke muka surat ${page}`);
            // Dalam realiti, handle pagination
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

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set up event listeners
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Populate initial data
            populateStudentsTable();
            
            // Add search input event listener
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    cariPelajar();
                }
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
        });
    </script>
</body>
</html>