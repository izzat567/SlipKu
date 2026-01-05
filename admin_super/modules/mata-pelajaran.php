<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Pelajaran - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/mata-pelajaran.css">
   
</head>
<body>
    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check"></i>
        </div>
        <div>
            <div style="font-weight: 600; color: var(--dark-gray);">Berjaya!</div>
            <div style="font-size: 14px; color: var(--medium-gray);" id="notificationMessage">Operasi berjaya disimpan</div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="subjectModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Mata Pelajaran Baru</h3>
                <button class="close-modal" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="subjectForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="subjectId" value="">
                
                <div class="form-group">
                    <label class="form-label">Kod Subjek *</label>
                    <input type="text" name="kod_subjek" id="kod_subjek" class="form-input" placeholder="Contoh: MAT01" required>
                    <div style="font-size: 12px; color: var(--medium-gray); margin-top: 5px;">
                        * Mesti unik (contoh: MAT01 untuk Matematik)
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama Mata Pelajaran *</label>
                    <input type="text" name="nama_subjek" id="nama_subjek" class="form-input" placeholder="Contoh: Matematik" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tahun Berkenaan *</label>
                    <select name="tahun_berkenaan" id="tahun_berkenaan" class="form-select" required>
                        <option value="">Pilih Tahun</option>
                        <option value="1">Tahun 1 Sahaja</option>
                        <option value="2">Tahun 2 Sahaja</option>
                        <option value="3">Tahun 3 Sahaja</option>
                        <option value="4">Tahun 4 Sahaja</option>
                        <option value="5">Tahun 5 Sahaja</option>
                        <option value="6">Tahun 6 Sahaja</option>
                        <option value="1-6">Semua Tahun (1-6)</option>
                        <option value="4-6">Tahun 4-6</option>
                        <option value="1-3">Tahun 1-3</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Jenis Mata Pelajaran *</label>
                    <select name="jenis" id="jenis" class="form-select" required>
                        <option value="">Pilih Jenis</option>
                        <option value="teras">Mata Pelajaran Teras</option>
                        <option value="tambahan">Mata Pelajaran Tambahan</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Sahkan Padam</h3>
                <button class="close-modal" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div style="padding: 20px 0;">
                <p style="color: var(--dark-gray); margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-exclamation-triangle" style="color: var(--warning); font-size: 48px; margin-bottom: 15px; display: block;"></i>
                    Adakah anda pasti ingin memadam mata pelajaran ini?
                </p>
                <p id="deleteSubjectInfo" style="text-align: center; color: var(--medium-gray); font-size: 14px; margin-bottom: 30px;"></p>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i>
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger" style="flex: 1;" id="confirmDeleteBtn">
                        <i class="fas fa-trash"></i>
                        Padam
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                <h2>Mata Pelajaran 📚</h2>
                <p>Urus dan kelola mata pelajaran sekolah rendah</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemula()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="tambahMataPelajaran()">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Mata Pelajaran
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid" id="statsGrid">
            <!-- Stats will be populated by JavaScript -->
        </div>

        <!-- Search and Filter -->
        <div class="search-filter">
            <div class="search-box">
                <input type="text" class="search-input" placeholder="Cari mata pelajaran dengan nama atau kod..." id="searchInput">
                <button class="btn btn-primary" onclick="cariMataPelajaran()">
                    <i class="fas fa-search"></i>
                    Cari
                </button>
                <button class="btn btn-secondary" onclick="resetCarian()">
                    <i class="fas fa-times"></i>
                    Reset
                </button>
            </div>
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Tahun Berkenaan</label>
                    <select class="filter-select" id="filterTahun" onchange="filterSubjects()">
                        <option value="">Semua Tahun</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6">Tahun 6</option>
                        <option value="1-3">Tahun 1-3</option>
                        <option value="4-6">Tahun 4-6</option>
                        <option value="1-6">Semua Tahun (1-6)</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Jenis</label>
                    <select class="filter-select" id="filterJenis" onchange="filterSubjects()">
                        <option value="">Semua Jenis</option>
                        <option value="teras">Teras</option>
                        <option value="tambahan">Tambahan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="subjects-table-container">
            <div class="section-header">
                <h3>Senarai Mata Pelajaran (<span id="subjectCount">0</span> mata pelajaran)</h3>
                <div class="subject-type-tabs">
                    <button class="type-tab active" onclick="filterByType('')">
                        Semua
                    </button>
                    <button class="type-tab" onclick="filterByType('teras')">
                        Teras
                    </button>
                    <button class="type-tab" onclick="filterByType('tambahan')">
                        Tambahan
                    </button>
                </div>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>KOD SUBJEK</th>
                            <th>NAMA MATA PELAJARAN</th>
                            <th>TAHUN BERKENAAN</th>
                            <th>JENIS</th>
                            <th>TARIKH DITAMBAH</th>
                            <th>TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody id="subjectsTableBody">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <i class="fas fa-book-open"></i>
                <h3>Tiada Mata Pelajaran Ditemui</h3>
                <p>Tiada data mata pelajaran yang sepadan dengan carian anda. Sila cuba carian yang berbeza atau tambah mata pelajaran baru.</p>
                <button class="btn btn-primary" onclick="tambahMataPelajaran()" style="margin-top: 20px;">
                    <i class="fas fa-plus-circle"></i>
                    Tambah Mata Pelajaran
                </button>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="paginationContainer">
                <!-- Pagination will be populated by JavaScript -->
            </div>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const subjectModal = document.getElementById('subjectModal');
        const deleteModal = document.getElementById('deleteModal');
        const subjectForm = document.getElementById('subjectForm');
        const subjectsTableBody = document.getElementById('subjectsTableBody');
        const subjectCount = document.getElementById('subjectCount');
        const emptyState = document.getElementById('emptyState');
        const statsGrid = document.getElementById('statsGrid');
        const paginationContainer = document.getElementById('paginationContainer');
        const notification = document.getElementById('notification');
        const notificationMessage = document.getElementById('notificationMessage');

        // Sample data mata pelajaran sekolah rendah
        let subjectsData = [
            { id: 1, kod_subjek: 'MAT01', nama_subjek: 'Matematik', tahun_berkenaan: '1-6', jenis: 'teras', created_at: '2024-01-15' },
            { id: 2, kod_subjek: 'BAH01', nama_subjek: 'Bahasa Melayu', tahun_berkenaan: '1-6', jenis: 'teras', created_at: '2024-01-15' },
            { id: 3, kod_subjek: 'BI01', nama_subjek: 'Bahasa Inggeris', tahun_berkenaan: '1-6', jenis: 'teras', created_at: '2024-01-16' },
            { id: 4, kod_subjek: 'SNS01', nama_subjek: 'Sains', tahun_berkenaan: '1-6', jenis: 'teras', created_at: '2024-01-16' },
            { id: 5, kod_subjek: 'PJH01', nama_subjek: 'Pendidikan Jasmani & Kesihatan', tahun_berkenaan: '1-6', jenis: 'teras', created_at: '2024-01-17' },
            { id: 6, kod_subjek: 'PJA01', nama_subjek: 'Pendidikan Jasmani', tahun_berkenaan: '1-3', jenis: 'teras', created_at: '2024-01-17' },
            { id: 7, kod_subjek: 'PKA01', nama_subjek: 'Pendidikan Kesenian', tahun_berkenaan: '1-3', jenis: 'teras', created_at: '2024-01-18' },
            { id: 8, kod_subjek: 'PDK01', nama_subjek: 'Pendidikan Kesihatan', tahun_berkenaan: '4-6', jenis: 'teras', created_at: '2024-01-18' },
            { id: 9, kod_subjek: 'KAR01', nama_subjek: 'Kemahiran Asas Komputer', tahun_berkenaan: '4-6', jenis: 'tambahan', created_at: '2024-01-19' },
            { id: 10, kod_subjek: 'BAA01', nama_subjek: 'Bahasa Arab', tahun_berkenaan: '4-6', jenis: 'tambahan', created_at: '2024-01-19' },
            { id: 11, kod_subjek: 'PIS01', nama_subjek: 'Pendidikan Islam', tahun_berkenaan: '1-6', jenis: 'tambahan', created_at: '2024-01-20' },
            { id: 12, kod_subjek: 'PMS01', nama_subjek: 'Pendidikan Moral', tahun_berkenaan: '1-6', jenis: 'tambahan', created_at: '2024-01-20' }
        ];

        // Current filter state
        let currentFilter = {
            search: '',
            tahun: '',
            jenis: '',
            typeTab: ''
        };

        // Pagination state
        let currentPage = 1;
        const itemsPerPage = 10;

        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Calculate statistics
        function calculateStatistics() {
            const total = subjectsData.length;
            const terasCount = subjectsData.filter(s => s.jenis === 'teras').length;
            const tambahanCount = subjectsData.filter(s => s.jenis === 'tambahan').length;
            const terasPercentage = total > 0 ? Math.round((terasCount / total) * 100) : 0;
            const tambahanPercentage = total > 0 ? Math.round((tambahanCount / total) * 100) : 0;

            statsGrid.innerHTML = `
                <div class="stats-card">
                    <div class="stats-header">
                        <h4>Jumlah Mata Pelajaran</h4>
                        <div class="stats-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stats-value">${total}</div>
                    <div class="stats-trend">
                        <i class="fas fa-chart-line"></i>
                        <span>Semua Tahun</span>
                    </div>
                </div>
                <div class="stats-card">
                    <div class="stats-header">
                        <h4>Mata Pelajaran Teras</h4>
                        <div class="stats-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="stats-value">${terasCount}</div>
                    <div class="stats-trend">
                        <i class="fas fa-users"></i>
                        <span>${terasPercentage}% daripada jumlah</span>
                    </div>
                </div>
                <div class="stats-card">
                    <div class="stats-header">
                        <h4>Mata Pelajaran Tambahan</h4>
                        <div class="stats-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                    </div>
                    <div class="stats-value">${tambahanCount}</div>
                    <div class="stats-trend">
                        <i class="fas fa-users"></i>
                        <span>${tambahanPercentage}% daripada jumlah</span>
                    </div>
                </div>
            `;
        }

        // Filter subjects based on current filter state
        function filterSubjects() {
            currentFilter.search = document.getElementById('searchInput').value.toLowerCase();
            currentFilter.tahun = document.getElementById('filterTahun').value;
            currentFilter.jenis = document.getElementById('filterJenis').value;
            
            let filteredData = subjectsData;
            
            // Apply search filter
            if (currentFilter.search) {
                filteredData = filteredData.filter(subject => 
                    subject.nama_subjek.toLowerCase().includes(currentFilter.search) || 
                    subject.kod_subjek.toLowerCase().includes(currentFilter.search)
                );
            }
            
            // Apply tahun filter
            if (currentFilter.tahun) {
                filteredData = filteredData.filter(subject => 
                    subject.tahun_berkenaan === currentFilter.tahun ||
                    subject.tahun_berkenaan === '1-6' ||
                    (currentFilter.tahun === '1-3' && subject.tahun_berkenaan === '1-3') ||
                    (currentFilter.tahun === '4-6' && subject.tahun_berkenaan === '4-6') ||
                    (subject.tahun_berkenaan.includes(currentFilter.tahun) && subject.tahun_berkenaan.length === 1)
                );
            }
            
            // Apply jenis filter
            if (currentFilter.jenis) {
                filteredData = filteredData.filter(subject => subject.jenis === currentFilter.jenis);
            }
            
            // Apply type tab filter
            if (currentFilter.typeTab) {
                filteredData = filteredData.filter(subject => subject.jenis === currentFilter.typeTab);
            }
            
            return filteredData;
        }

        // Populate subjects table
        function populateSubjectsTable(data) {
            const filteredData = data || filterSubjects();
            subjectCount.textContent = filteredData.length;
            
            if (filteredData.length === 0) {
                subjectsTableBody.innerHTML = '';
                emptyState.style.display = 'block';
                paginationContainer.innerHTML = '';
                return;
            }
            
            emptyState.style.display = 'none';
            
            // Calculate pagination
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredData.length);
            const pageData = filteredData.slice(startIndex, endIndex);
            
            // Populate table
            subjectsTableBody.innerHTML = pageData.map(subject => `
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--primary);">${subject.kod_subjek}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">ID: ${subject.id}</div>
                    </td>
                    <td>
                        <div style="font-weight: 600;">${subject.nama_subjek}</div>
                        <div style="font-size: 12px; color: var(--medium-gray);">Ditambah: ${formatDate(subject.created_at)}</div>
                    </td>
                    <td>
                        <span class="year-badge">${subject.tahun_berkenaan}</span>
                    </td>
                    <td>
                        <span class="subject-badge badge-${subject.jenis}">
                            <i class="fas fa-${subject.jenis === 'teras' ? 'star' : 'plus-circle'}"></i>
                            ${subject.jenis === 'teras' ? 'Teras' : 'Tambahan'}
                        </span>
                    </td>
                    <td>${formatDate(subject.created_at)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" title="Lihat" onclick="lihatMataPelajaran(${subject.id})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit" title="Edit" onclick="editMataPelajaran(${subject.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" title="Padam" onclick="padamMataPelajaran(${subject.id}, '${subject.kod_subjek}', '${subject.nama_subjek}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            // Generate pagination
            generatePagination(totalPages);
        }

        // Generate pagination buttons
        function generatePagination(totalPages) {
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            
            let paginationHTML = '';
            
            // Previous button
            paginationHTML += `
                <button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                        onclick="changePage('prev')" ${currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    paginationHTML += `
                        <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                                onclick="changePage(${i})">
                            ${i}
                        </button>
                    `;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHTML += `<span class="pagination-info">...</span>`;
                }
            }
            
            // Next button
            paginationHTML += `
                <button class="pagination-btn ${currentPage === totalPages ? 'disabled' : ''}" 
                        onclick="changePage('next')" ${currentPage === totalPages ? 'disabled' : ''}>
                    <i class="fas fa-chevron-right"></i>
                </button>
                <span class="pagination-info">Muka surat ${currentPage} daripada ${totalPages}</span>
            `;
            
            paginationContainer.innerHTML = paginationHTML;
        }

        // Change page
        function changePage(page) {
            if (page === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (page === 'next') {
                const filteredData = filterSubjects();
                const totalPages = Math.ceil(filteredData.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                }
            } else if (typeof page === 'number') {
                currentPage = page;
            }
            
            populateSubjectsTable();
        }

        // Show/Hide Modal
        function openModal() {
            subjectModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            subjectModal.style.display = 'none';
            document.body.style.overflow = '';
            resetForm();
        }

        function openDeleteModal() {
            deleteModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            deleteModal.style.display = 'none';
            document.body.style.overflow = '';
        }

        // Show notification
        function showNotification(message, type = 'success') {
            notificationMessage.textContent = message;
            notification.style.background = type === 'success' ? 'var(--white)' : '#fee2e2';
            notification.querySelector('.notification-icon').style.background = 
                type === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)';
            notification.querySelector('.notification-icon').innerHTML = 
                type === 'success' ? '<i class="fas fa-check"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
            
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Reset form
        function resetForm() {
            document.getElementById('modalTitle').textContent = 'Tambah Mata Pelajaran Baru';
            document.getElementById('formAction').value = 'add';
            document.getElementById('subjectId').value = '';
            document.getElementById('kod_subjek').value = '';
            document.getElementById('nama_subjek').value = '';
            document.getElementById('tahun_berkenaan').value = '';
            document.getElementById('jenis').value = '';
        }

        // Add new subject
        function tambahMataPelajaran() {
            resetForm();
            openModal();
        }

        // Edit subject
        function editMataPelajaran(id) {
            const subject = subjectsData.find(s => s.id === id);
            if (!subject) return;
            
            document.getElementById('modalTitle').textContent = 'Kemaskini Mata Pelajaran';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('subjectId').value = subject.id;
            document.getElementById('kod_subjek').value = subject.kod_subjek;
            document.getElementById('nama_subjek').value = subject.nama_subjek;
            document.getElementById('tahun_berkenaan').value = subject.tahun_berkenaan;
            document.getElementById('jenis').value = subject.jenis;
            
            openModal();
        }

        // Delete subject
        function padamMataPelajaran(id, kod, nama) {
            document.getElementById('deleteSubjectInfo').innerHTML = `
                <strong>${kod}</strong> - ${nama}<br>
                <small style="color: var(--medium-gray);">ID: ${id}</small>
            `;
            
            // Set up delete confirmation
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            confirmDeleteBtn.onclick = () => {
                // Remove subject from data
                subjectsData = subjectsData.filter(s => s.id !== id);
                
                // Close modal
                closeDeleteModal();
                
                // Update UI
                populateSubjectsTable();
                calculateStatistics();
                
                // Show notification
                showNotification(`Mata pelajaran ${kod} berjaya dipadam!`);
            };
            
            openDeleteModal();
        }

        // View subject details
        function lihatMataPelajaran(id) {
            const subject = subjectsData.find(s => s.id === id);
            if (!subject) return;
            
            const message = `
                Kod: ${subject.kod_subjek}
                Nama: ${subject.nama_subjek}
                Tahun Berkenaan: ${subject.tahun_berkenaan}
                Jenis: ${subject.jenis === 'teras' ? 'Teras' : 'Tambahan'}
                ID: ${subject.id}
                Ditambah pada: ${formatDate(subject.created_at)}
            `;
            
            alert(message);
        }

        // Search subjects
        function cariMataPelajaran() {
            currentPage = 1;
            populateSubjectsTable();
        }

        // Reset search
        function resetCarian() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterJenis').value = '';
            
            // Reset type tabs
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector('.type-tab').classList.add('active');
            
            currentFilter = {
                search: '',
                tahun: '',
                jenis: '',
                typeTab: ''
            };
            
            currentPage = 1;
            populateSubjectsTable();
        }

        // Filter by type tab
        function filterByType(jenis) {
            // Update active tab
            document.querySelectorAll('.type-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');
            
            currentFilter.typeTab = jenis;
            currentPage = 1;
            populateSubjectsTable();
        }

        // Reload page
        function muatSemula() {
            currentPage = 1;
            populateSubjectsTable();
            calculateStatistics();
            showNotification('Data telah dimuat semula');
        }

        // Form submission
        subjectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const action = document.getElementById('formAction').value;
            const id = document.getElementById('subjectId').value;
            const kod_subjek = document.getElementById('kod_subjek').value.trim().toUpperCase();
            const nama_subjek = document.getElementById('nama_subjek').value.trim();
            const tahun_berkenaan = document.getElementById('tahun_berkenaan').value;
            const jenis = document.getElementById('jenis').value;
            
            // Validation
            if (!kod_subjek) {
                alert('Sila masukkan kod subjek');
                return;
            }
            
            if (!nama_subjek) {
                alert('Sila masukkan nama mata pelajaran');
                return;
            }
            
            if (!tahun_berkenaan) {
                alert('Sila pilih tahun berkenaan');
                return;
            }
            
            if (!jenis) {
                alert('Sila pilih jenis mata pelajaran');
                return;
            }
            
            // Check for duplicate kod_subjek (except when editing the same subject)
            const duplicate = subjectsData.find(s => 
                s.kod_subjek === kod_subjek && 
                (action === 'add' || (action === 'edit' && s.id !== parseInt(id)))
            );
            
            if (duplicate) {
                alert('Kod subjek sudah wujud. Sila gunakan kod yang berbeza.');
                return;
            }
            
            if (action === 'add') {
                // Add new subject
                const newSubject = {
                    id: subjectsData.length > 0 ? Math.max(...subjectsData.map(s => s.id)) + 1 : 1,
                    kod_subjek,
                    nama_subjek,
                    tahun_berkenaan,
                    jenis,
                    created_at: new Date().toISOString().split('T')[0]
                };
                
                subjectsData.push(newSubject);
                showNotification(`Mata pelajaran ${kod_subjek} berjaya ditambah!`);
            } else if (action === 'edit') {
                // Edit existing subject
                const index = subjectsData.findIndex(s => s.id === parseInt(id));
                if (index !== -1) {
                    subjectsData[index] = {
                        ...subjectsData[index],
                        kod_subjek,
                        nama_subjek,
                        tahun_berkenaan,
                        jenis
                    };
                    showNotification(`Mata pelajaran ${kod_subjek} berjaya dikemaskini!`);
                }
            }
            
            // Close modal and update UI
            closeModal();
            populateSubjectsTable();
            calculateStatistics();
        });

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
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === subjectModal) {
                    closeModal();
                }
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });
            
            // Add search input event listener
            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    cariMataPelajaran();
                }
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
            
            // Initialize data
            populateSubjectsTable();
            calculateStatistics();
        });
    </script>
</body>
</html>