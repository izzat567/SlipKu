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
    <link rel="stylesheet" href="./css/pengurusan-guru.css">
    
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div style="color: var(--dark-gray); font-size: 18px; font-weight: 600;">Memuatkan data...</div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h4>Berjaya!</h4>
            <p id="notificationMessage">Operasi berjaya disimpan</p>
        </div>
    </div>

    <!-- Modal Overlays -->
    <div class="modal-overlay" id="addGuruModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> Tambah Guru Baru</h3>
                <button class="modal-close" onclick="closeModal('addGuruModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addGuruForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama">Nama Penuh *</label>
                            <input type="text" id="nama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="noKp">No. Kad Pengenalan *</label>
                            <input type="text" id="noKp" name="noKp" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="emel">Emel *</label>
                            <input type="email" id="emel" name="emel" required>
                        </div>
                        <div class="form-group">
                            <label for="telefon">Telefon *</label>
                            <input type="tel" id="telefon" name="telefon" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jantina">Jantina *</label>
                            <select id="jantina" name="jantina" required>
                                <option value="">Pilih Jantina</option>
                                <option value="Lelaki">Lelaki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tarikhLahir">Tarikh Lahir *</label>
                            <input type="date" id="tarikhLahir" name="tarikhLahir" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jawatan">Jawatan *</label>
                            <select id="jawatan" name="jawatan" required>
                                <option value="">Pilih Jawatan</option>
                                <option value="Guru Besar">Guru Besar</option>
                                <option value="Penolong Kanan">Penolong Kanan</option>
                                <option value="Ketua Panitia">Ketua Panitia</option>
                                <option value="Guru Kanan">Guru Kanan</option>
                                <option value="Guru">Guru</option>
                                <option value="Guru Pemulihan">Guru Pemulihan</option>
                                <option value="Guru Pendidikan Khas">Guru Pendidikan Khas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status *</label>
                            <select id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kelayakan">Kelayakan Akademik</label>
                            <input type="text" id="kelayakan" name="kelayakan" placeholder="Contoh: Ijazah Sarjana Muda Pendidikan">
                        </div>
                        <div class="form-group">
                            <label for="tahunMula">Tahun Mula Berkhidmat</label>
                            <input type="number" id="tahunMula" name="tahunMula" min="1980" max="2023">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="mataPelajaran">Mata Pelajaran Diajar</label>
                        <input type="text" id="mataPelajaran" name="mataPelajaran" placeholder="Contoh: Matematik, Sains">
                    </div>
                    
                    <div class="form-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('addGuruModal')">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Guru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="detailGuruModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-user-circle"></i> Maklumat Guru</h3>
                <button class="modal-close" onclick="closeModal('detailGuruModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-view" id="detailContent">
                    <!-- Content will be filled by JavaScript -->
                </div>
                <div class="form-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('detailGuruModal')">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" onclick="editGuruFromDetail()">
                        <i class="fas fa-edit"></i> Edit Maklumat
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="editGuruModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-user-edit"></i> Edit Maklumat Guru</h3>
                <button class="modal-close" onclick="closeModal('editGuruModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editGuruForm">
                    <input type="hidden" id="editId" name="id">
                    <!-- Form fields same as addGuruForm -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editNama">Nama Penuh *</label>
                            <input type="text" id="editNama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="editNoKp">No. Kad Pengenalan *</label>
                            <input type="text" id="editNoKp" name="noKp" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editEmel">Emel *</label>
                            <input type="email" id="editEmel" name="emel" required>
                        </div>
                        <div class="form-group">
                            <label for="editTelefon">Telefon *</label>
                            <input type="tel" id="editTelefon" name="telefon" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editJantina">Jantina *</label>
                            <select id="editJantina" name="jantina" required>
                                <option value="">Pilih Jantina</option>
                                <option value="Lelaki">Lelaki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editTarikhLahir">Tarikh Lahir *</label>
                            <input type="date" id="editTarikhLahir" name="tarikhLahir" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editJawatan">Jawatan *</label>
                            <select id="editJawatan" name="jawatan" required>
                                <option value="">Pilih Jawatan</option>
                                <option value="Guru Besar">Guru Besar</option>
                                <option value="Penolong Kanan">Penolong Kanan</option>
                                <option value="Ketua Panitia">Ketua Panitia</option>
                                <option value="Guru Kanan">Guru Kanan</option>
                                <option value="Guru">Guru</option>
                                <option value="Guru Pemulihan">Guru Pemulihan</option>
                                <option value="Guru Pendidikan Khas">Guru Pendidikan Khas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status *</label>
                            <select id="editStatus" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Tidak Aktif">Tidak Aktif</option>
                                <option value="Cuti">Cuti</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editAlamat">Alamat</label>
                        <textarea id="editAlamat" name="alamat" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editKelayakan">Kelayakan Akademik</label>
                            <input type="text" id="editKelayakan" name="kelayakan">
                        </div>
                        <div class="form-group">
                            <label for="editTahunMula">Tahun Mula Berkhidmat</label>
                            <input type="number" id="editTahunMula" name="tahunMula" min="1980" max="2023">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="editMataPelajaran">Mata Pelajaran Diajar</label>
                        <input type="text" id="editMataPelajaran" name="mataPelajaran">
                    </div>
                    
                    <div class="form-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeModal('editGuruModal')">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="button" class="btn btn-danger" onclick="deleteGuru()" id="deleteBtn">
                            <i class="fas fa-trash"></i> Padam
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kemaskini
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   <!-- side bar -->
   <?php include './includes/header.php'; ?> 

    <!-- include side bar -->
    <?php include './includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2><i class="fas fa-user-tie"></i> Pengurusan Guru</h2>
                <p>Urus maklumat guru di institusi anda</p>
            </div>
            <div class="action-buttons">
                <button class="btn btn-secondary" onclick="exportToExcel()">
                    <i class="fas fa-file-export"></i> Eksport
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-user-plus">
                        <a href="tambah-guru.php"></a>
                    </i> Tambah Guru
                </button>
            </div>
        </div>

        <!-- Search and Filter Bar -->
        <div class="search-filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari guru..." onkeyup="searchGuru()">
            </div>
            <div class="filter-dropdown">
                <button class="filter-btn">
                    <span>Semua Status</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="filter-dropdown-content">
                    <div class="filter-option" onclick="filterGuru('semua')">Semua Status</div>
                    <div class="filter-option" onclick="filterGuru('Aktif')">Aktif</div>
                    <div class="filter-option" onclick="filterGuru('Tidak Aktif')">Tidak Aktif</div>
                    <div class="filter-option" onclick="filterGuru('Cuti')">Cuti</div>
                </div>
            </div>
            <div class="filter-dropdown">
                <button class="filter-btn">
                    <span>Semua Jawatan</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="filter-dropdown-content">
                    <div class="filter-option" onclick="filterJawatan('semua')">Semua Jawatan</div>
                    <div class="filter-option" onclick="filterJawatan('Guru Besar')">Guru Besar</div>
                    <div class="filter-option" onclick="filterJawatan('Penolong Kanan')">Penolong Kanan</div>
                    <div class="filter-option" onclick="filterJawatan('Ketua Panitia')">Ketua Panitia</div>
                    <div class="filter-option" onclick="filterJawatan('Guru')">Guru</div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>NAMA GURU</th>
                        <th>JAWATAN</th>
                        <th>EMEL</th>
                        <th>TELEFON</th>
                        <th>STATUS</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="guruTable">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
            
            <!-- Empty State (akan ditunjukkan jika tiada data) -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-user-slash"></i>
                <p>Tiada rekod guru ditemui</p>
                <button class="btn btn-primary" onclick="openModal('addGuruModal')" style="margin-top: 15px;">
                    <i class="fas fa-user-plus"></i> Tambah Guru Pertama
                </button>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination" id="pagination">
            <!-- Pagination akan diisi oleh JavaScript -->
        </div>
    </main>

    <!-- <script>
        // Data contoh untuk guru
        let guruData = [
            {
                id: 1,
                nama: "Ahmad bin Abdullah",
                noKp: "800101-01-1234",
                emel: "ahmad.abdullah@sekolah.edu.my",
                telefon: "012-3456789",
                jantina: "Lelaki",
                tarikhLahir: "1980-01-01",
                jawatan: "Guru Besar",
                status: "Aktif",
                alamat: "No 123, Jalan Mawar, Taman Melati, 53100 Kuala Lumpur",
                kelayakan: "Ijazah Sarjana Muda Pendidikan (Matematik)",
                tahunMula: 2005,
                mataPelajaran: "Matematik"
            },
            {
                id: 2,
                nama: "Siti binti Mohd Ali",
                noKp: "850505-05-5678",
                emel: "siti.ali@sekolah.edu.my",
                telefon: "013-9876543",
                jantina: "Perempuan",
                tarikhLahir: "1985-05-05",
                jawatan: "Penolong Kanan",
                status: "Aktif",
                alamat: "No 45, Jalan Melur, Taman Kenanga, 43000 Kajang, Selangor",
                kelayakan: "Ijazah Sarjana Pendidikan (Pentadbiran)",
                tahunMula: 2010,
                mataPelajaran: "Pentadbiran Pendidikan"
            },
            {
                id: 3,
                nama: "Rajesh a/l Kumar",
                noKp: "820202-02-9876",
                emel: "rajesh.kumar@sekolah.edu.my",
                telefon: "019-8765432",
                jantina: "Lelaki",
                tarikhLahir: "1982-02-02",
                jawatan: "Ketua Panitia",
                status: "Aktif",
                alamat: "No 78, Jalan Cempaka, Taman Bunga Raya, 50000 Kuala Lumpur",
                kelayakan: "Ijazah Sarjana Muda Sains (Fizik)",
                tahunMula: 2008,
                mataPelajaran: "Fizik, Matematik Tambahan"
            },
            {
                id: 4,
                nama: "Noraini binti Hassan",
                noKp: "880808-08-4321",
                emel: "noraini.hassan@sekolah.edu.my",
                telefon: "011-2233445",
                jantina: "Perempuan",
                tarikhLahir: "1988-08-08",
                jawatan: "Guru",
                status: "Aktif",
                alamat: "No 12, Jalan Anggerik, Taman Mewah, 68000 Ampang, Selangor",
                kelayakan: "Ijazah Sarjana Muda Pendidikan (Sejarah)",
                tahunMula: 2012,
                mataPelajaran: "Sejarah, Pendidikan Moral"
            },
            {
                id: 5,
                nama: "Lim Chen Wei",
                noKp: "830303-03-6543",
                emel: "lim.chenwei@sekolah.edu.my",
                telefon: "016-5544332",
                jantina: "Lelaki",
                tarikhLahir: "1983-03-03",
                jawatan: "Guru",
                status: "Cuti",
                alamat: "No 56, Jalan Dahlia, Taman Suria, 47100 Puchong, Selangor",
                kelayakan: "Ijazah Sarjana Muda Pendidikan (Biologi)",
                tahunMula: 2009,
                mataPelajaran: "Biologi, Sains"
            },
            {
                id: 6,
                nama: "Fatimah binti Yusof",
                noKp: "870707-07-8765",
                emel: "fatimah.yusof@sekolah.edu.my",
                telefon: "017-6655443",
                jantina: "Perempuan",
                tarikhLahir: "1987-07-07",
                jawatan: "Guru",
                status: "Tidak Aktif",
                alamat: "No 34, Jalan Kenanga, Taman Permai, 43200 Cheras, Selangor",
                kelayakan: "Ijazah Sarjana Muda Pendidikan (Geografi)",
                tahunMula: 2011,
                mataPelajaran: "Geografi, Kajian Tempatan"
            },
            {
                id: 7,
                nama: "Mohan a/l Subramaniam",
                noKp: "840404-04-2345",
                emel: "mohan.subra@sekolah.edu.my",
                telefon: "018-7766554",
                jantina: "Lelaki",
                tarikhLahir: "1984-04-04",
                jawatan: "Guru Kanan",
                status: "Aktif",
                alamat: "No 89, Jalan Mutiara, Taman Sri Rampai, 53300 Kuala Lumpur",
                kelayakan: "Ijazah Sarjana Pendidikan (Matematik)",
                tahunMula: 2007,
                mataPelajaran: "Matematik, Matematik Tambahan"
            },
            {
                id: 8,
                nama: "Kartini binti Omar",
                noKp: "890909-09-5432",
                emel: "kartini.omar@sekolah.edu.my",
                telefon: "014-8877665",
                jantina: "Perempuan",
                tarikhLahir: "1989-09-09",
                jawatan: "Guru",
                status: "Aktif",
                alamat: "No 23, Jalan Seroja, Taman Indah, 52100 Kepong, Kuala Lumpur",
                kelayakan: "Ijazah Sarjana Muda Pendidikan (Kimia)",
                tahunMula: 2013,
                mataPelajaran: "Kimia, Sains"
            }
        ];

        // Variabel untuk pagination dan filtering
        let currentPage = 1;
        let itemsPerPage = 5;
        let currentFilter = 'semua';
        let currentJawatanFilter = 'semua';
        let currentSearch = '';

        // Fungsi untuk mendapatkan inisial nama
        function getInitials(nama) {
            return nama.split(' ').map(word => word[0]).join('').toUpperCase();
        }

        // Fungsi untuk format telefon
        function formatPhone(phone) {
            return phone.replace(/(\d{3})-(\d{3})(\d{4})/, '$1-$2$3');
        }

        // Fungsi untuk menunjukkan notifikasi
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notificationMessage');
            const icon = notification.querySelector('.notification-icon i');
            const notificationBar = notification.querySelector('.notification-icon');
            
            if (type === 'success') {
                notification.style.borderLeftColor = 'var(--success)';
                icon.className = 'fas fa-check-circle';
                notificationBar.style.background = 'rgba(16, 185, 129, 0.1)';
                notificationBar.style.color = 'var(--success)';
            } else if (type === 'error') {
                notification.style.borderLeftColor = 'var(--danger)';
                icon.className = 'fas fa-exclamation-circle';
                notificationBar.style.background = 'rgba(239, 68, 68, 0.1)';
                notificationBar.style.color = 'var(--danger)';
            } else if (type === 'info') {
                notification.style.borderLeftColor = 'var(--info)';
                icon.className = 'fas fa-info-circle';
                notificationBar.style.background = 'rgba(59, 130, 246, 0.1)';
                notificationBar.style.color = 'var(--info)';
            }
            
            notificationMessage.textContent = message;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 5000);
        }

        // Fungsi untuk memaparkan data guru dalam jadual
        function renderGuruTable() {
            const tableBody = document.getElementById('guruTable');
            const emptyState = document.getElementById('emptyState');
            
            // Filter data berdasarkan status dan jawatan
            let filteredData = guruData.filter(guru => {
                const statusMatch = currentFilter === 'semua' || guru.status === currentFilter;
                const jawatanMatch = currentJawatanFilter === 'semua' || guru.jawatan === currentJawatanFilter;
                const searchMatch = currentSearch === '' || 
                    guru.nama.toLowerCase().includes(currentSearch) ||
                    guru.emel.toLowerCase().includes(currentSearch) ||
                    guru.jawatan.toLowerCase().includes(currentSearch);
                
                return statusMatch && jawatanMatch && searchMatch;
            });
            
            // Jika tiada data, tunjukkan empty state
            if (filteredData.length === 0) {
                tableBody.innerHTML = '';
                emptyState.style.display = 'flex';
                renderPagination(0);
                return;
            }
            
            emptyState.style.display = 'none';
            
            // Kira pagination
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            const pageData = filteredData.slice(startIndex, endIndex);
            
            // Kosongkan jadual
            tableBody.innerHTML = '';
            
            // Isi data ke dalam jadual
            pageData.forEach(guru => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="guru-avatar">
                            <div class="avatar-circle">${getInitials(guru.nama)}</div>
                            <div class="guru-info">
                                <h4>${guru.nama}</h4>
                                <p>${guru.noKp}</p>
                            </div>
                        </div>
                    </td>
                    <td>${guru.jawatan}</td>
                    <td>${guru.emel}</td>
                    <td>${formatPhone(guru.telefon)}</td>
                    <td>
                        <span class="status-badge ${guru.status === 'Aktif' ? 'status-active' : 
                            guru.status === 'Tidak Aktif' ? 'status-inactive' : 'status-pending'}">
                            ${guru.status}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons-cell">
                            <button class="btn-icon btn-view" onclick="viewGuru(${guru.id})" title="Lihat Maklumat">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon btn-edit" onclick="editGuru(${guru.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="confirmDeleteGuru(${guru.id})" title="Padam">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Render pagination
            renderPagination(filteredData.length);
        }

        // Fungsi untuk memaparkan pagination
        function renderPagination(totalItems) {
            const paginationContainer = document.getElementById('pagination');
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            
            // Butang previous
            paginationHTML += `
                <button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            
            // Nombor halaman
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                        <button class="pagination-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHTML += `<span>...</span>`;
                }
            }
            
            // Butang next
            paginationHTML += `
                <button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;
            
            paginationContainer.innerHTML = paginationHTML;
        }

        // Fungsi untuk menukar halaman
        function changePage(page) {
            currentPage = page;
            renderGuruTable();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Fungsi untuk mencari guru
        function searchGuru() {
            currentSearch = document.getElementById('searchInput').value.toLowerCase();
            currentPage = 1;
            renderGuruTable();
        }

        // Fungsi untuk menapis guru berdasarkan status
        function filterGuru(status) {
            currentFilter = status;
            currentPage = 1;
            document.querySelector('.filter-dropdown:first-child .filter-btn span').textContent = 
                status === 'semua' ? 'Semua Status' : status;
            renderGuruTable();
        }

        // Fungsi untuk menapis guru berdasarkan jawatan
        function filterJawatan(jawatan) {
            currentJawatanFilter = jawatan;
            currentPage = 1;
            document.querySelector('.filter-dropdown:nth-child(3) .filter-btn span').textContent = 
                jawatan === 'semua' ? 'Semua Jawatan' : jawatan;
            renderGuruTable();
        }

        // Fungsi untuk membuka modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fungsi untuk menutup modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Fungsi untuk melihat maklumat guru
        function viewGuru(id) {
            const guru = guruData.find(g => g.id === id);
            if (!guru) return;
            
            const detailContent = document.getElementById('detailContent');
            detailContent.innerHTML = `
                <div class="detail-avatar">
                    <div class="detail-avatar-circle">${getInitials(guru.nama)}</div>
                    <h4>${guru.nama}</h4>
                    <p>${guru.jawatan}</p>
                    <span class="status-badge ${guru.status === 'Aktif' ? 'status-active' : 
                        guru.status === 'Tidak Aktif' ? 'status-inactive' : 'status-pending'}">
                        ${guru.status}
                    </span>
                </div>
                <div class="detail-info">
                    <div class="detail-section">
                        <h5>Maklumat Peribadi</h5>
                        <div class="detail-item">
                            <span class="detail-item-label">No. Kad Pengenalan:</span>
                            <span class="detail-item-value">${guru.noKp}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Jantina:</span>
                            <span class="detail-item-value">${guru.jantina}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Tarikh Lahir:</span>
                            <span class="detail-item-value">${formatDate(guru.tarikhLahir)}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Alamat:</span>
                            <span class="detail-item-value">${guru.alamat}</span>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h5>Maklumat Profesional</h5>
                        <div class="detail-item">
                            <span class="detail-item-label">Jawatan:</span>
                            <span class="detail-item-value">${guru.jawatan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Kelayakan Akademik:</span>
                            <span class="detail-item-value">${guru.kelayakan}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Tahun Mula Berkhidmat:</span>
                            <span class="detail-item-value">${guru.tahunMula}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Mata Pelajaran Diajar:</span>
                            <span class="detail-item-value">${guru.mataPelajaran}</span>
                        </div>
                    </div>
                    
                    <div class="detail-section">
                        <h5>Maklumat Hubungan</h5>
                        <div class="detail-item">
                            <span class="detail-item-label">Emel:</span>
                            <span class="detail-item-value">${guru.emel}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Telefon:</span>
                            <span class="detail-item-value">${formatPhone(guru.telefon)}</span>
                        </div>
                    </div>
                </div>
            `;
            
            // Simpan ID guru untuk edit
            detailContent.dataset.guruId = id;
            
            openModal('detailGuruModal');
        }

        // Fungsi untuk edit guru dari detail view
        function editGuruFromDetail() {
            const guruId = document.getElementById('detailContent').dataset.guruId;
            closeModal('detailGuruModal');
            setTimeout(() => editGuru(parseInt(guruId)), 300);
        }

        // Fungsi untuk edit guru
        function editGuru(id) {
            const guru = guruData.find(g => g.id === id);
            if (!guru) return;
            
            // Isi borang dengan data guru
            document.getElementById('editId').value = guru.id;
            document.getElementById('editNama').value = guru.nama;
            document.getElementById('editNoKp').value = guru.noKp;
            document.getElementById('editEmel').value = guru.emel;
            document.getElementById('editTelefon').value = guru.telefon;
            document.getElementById('editJantina').value = guru.jantina;
            document.getElementById('editTarikhLahir').value = guru.tarikhLahir;
            document.getElementById('editJawatan').value = guru.jawatan;
            document.getElementById('editStatus').value = guru.status;
            document.getElementById('editAlamat').value = guru.alamat;
            document.getElementById('editKelayakan').value = guru.kelayakan;
            document.getElementById('editTahunMula').value = guru.tahunMula;
            document.getElementById('editMataPelajaran').value = guru.mataPelajaran;
            
            // Simpan ID guru untuk delete function
            document.getElementById('deleteBtn').dataset.guruId = id;
            
            openModal('editGuruModal');
        }

        // Fungsi untuk memadam guru
        function deleteGuru() {
            const id = parseInt(document.getElementById('deleteBtn').dataset.guruId);
            if (!confirm(`Adakah anda pasti ingin memadam guru ini?`)) return;
            
            guruData = guruData.filter(g => g.id !== id);
            renderGuruTable();
            closeModal('editGuruModal');
            showNotification('Guru berjaya dipadam', 'success');
        }

        // Fungsi untuk pengesahan sebelum padam
        function confirmDeleteGuru(id) {
            const guru = guruData.find(g => g.id === id);
            if (!guru) return;
            
            if (confirm(`Adakah anda pasti ingin memadam ${guru.nama}?`)) {
                guruData = guruData.filter(g => g.id !== id);
                renderGuruTable();
                showNotification('Guru berjaya dipadam', 'success');
            }
        }

        // Fungsi untuk format tarikh
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = new Date(dateString);
            return date.toLocaleDateString('ms-MY', options);
        }

        // Event listener untuk borang tambah guru
        document.getElementById('addGuruForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Dapatkan data dari borang
            const formData = new FormData(this);
            const newGuru = {
                id: guruData.length > 0 ? Math.max(...guruData.map(g => g.id)) + 1 : 1,
                nama: formData.get('nama'),
                noKp: formData.get('noKp'),
                emel: formData.get('emel'),
                telefon: formData.get('telefon'),
                jantina: formData.get('jantina'),
                tarikhLahir: formData.get('tarikhLahir'),
                jawatan: formData.get('jawatan'),
                status: formData.get('status'),
                alamat: formData.get('alamat') || '',
                kelayakan: formData.get('kelayakan') || '',
                tahunMula: parseInt(formData.get('tahunMula')) || null,
                mataPelajaran: formData.get('mataPelajaran') || ''
            };
            
            // Tambah guru baru
            guruData.unshift(newGuru);
            
            // Reset borang
            this.reset();
            
            // Tutup modal dan refresh jadual
            closeModal('addGuruModal');
            currentPage = 1;
            renderGuruTable();
            
            showNotification('Guru baru berjaya ditambah', 'success');
        });

        // Event listener untuk borang edit guru
        document.getElementById('editGuruForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Dapatkan data dari borang
            const formData = new FormData(this);
            const id = parseInt(formData.get('id'));
            
            // Cari dan update guru
            const guruIndex = guruData.findIndex(g => g.id === id);
            if (guruIndex !== -1) {
                guruData[guruIndex] = {
                    id: id,
                    nama: formData.get('nama'),
                    noKp: formData.get('noKp'),
                    emel: formData.get('emel'),
                    telefon: formData.get('telefon'),
                    jantina: formData.get('jantina'),
                    tarikhLahir: formData.get('tarikhLahir'),
                    jawatan: formData.get('jawatan'),
                    status: formData.get('status'),
                    alamat: formData.get('alamat') || '',
                    kelayakan: formData.get('kelayakan') || '',
                    tahunMula: parseInt(formData.get('tahunMula')) || null,
                    mataPelajaran: formData.get('mataPelajaran') || ''
                };
            }
            
            // Tutup modal dan refresh jadual
            closeModal('editGuruModal');
            renderGuruTable();
            
            showNotification('Maklumat guru berjaya dikemaskini', 'success');
        });

        // Fungsi untuk export ke Excel (demo)
        function exportToExcel() {
            showNotification('Fungsi eksport ke Excel akan dilaksanakan dalam versi penuh', 'info');
        }

        // Toggle sidebar untuk mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        // Tutup sidebar apabila klik overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.remove('active');
            this.classList.remove('active');
        });

        // Tutup modal apabila klik di luar modal
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // Event listener untuk log keluar
        document.querySelector('.logout').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Adakah anda pasti ingin log keluar?')) {
                showNotification('Anda telah log keluar. Sila tunggu...', 'info');
                setTimeout(() => {
                    alert('Anda telah log keluar. Halaman ini hanya demo - dalam aplikasi sebenar, anda akan diarahkan ke halaman log masuk.');
                }, 1000);
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            renderGuruTable();
            
            // Set today's date as default for tarikh lahir
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tarikhLahir').max = today;
            document.getElementById('editTarikhLahir').max = today;
        });
    </script> -->
</body>
</html>