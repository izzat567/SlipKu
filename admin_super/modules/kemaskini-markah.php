<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Markah - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/kemaskini-markah.css">
   
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
                <h2>Kemaskini Markah ✏️</h2>
                <p>Kemaskini markah peperiksaan yang sedia ada</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaData()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-success" onclick="simpanSemuaPerubahan()">
                    <i class="fas fa-save"></i>
                    Simpan Semua
                </button>
                <button class="btn btn-info" onclick="cetakMarkah()">
                    <i class="fas fa-print"></i>
                    Cetak Markah
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-grid">
                <div class="filter-group">
                    <label class="filter-label">Jenis Peperiksaan</label>
                    <select class="filter-select" id="filterExam" onchange="filterData()">
                        <option value="">Semua Peperiksaan</option>
                        <option value="ujian1">Ujian 1</option>
                        <option value="ujian2">Ujian 2</option>
                        <option value="pertengahan">Peperiksaan Pertengahan</option>
                        <option value="akhir" selected>Peperiksaan Akhir</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Tahun</label>
                    <select class="filter-select" id="filterYear" onchange="filterData()">
                        <option value="">Semua Tahun</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6" selected>Tahun 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select class="filter-select" id="filterClass" onchange="filterData()">
                        <option value="">Semua Kelas</option>
                        <option value="A" selected>Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Mata Pelajaran</label>
                    <select class="filter-select" id="filterSubject" onchange="filterData()">
                        <option value="">Semua Mata Pelajaran</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01" selected>PJ & Kesihatan</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <div class="search-section">
            <div class="search-input-group">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari pelajar mengikut nama atau ID..." onkeyup="searchTable()">
            </div>
            <button class="btn btn-secondary" onclick="resetSearch()">
                <i class="fas fa-times"></i>
                Reset Carian
            </button>
            <button class="btn btn-primary" onclick="filterData()">
                <i class="fas fa-filter"></i>
                Gunakan Penapis
            </button>
        </div>

        <!-- Student Marks Table -->
        <div class="table-container">
            <div class="table-header">
                <h3>Markah Peperiksaan <span id="tableCount">(45 pelajar)</span></h3>
                <div class="table-actions">
                    <button class="btn btn-secondary" onclick="bulkEditMode()">
                        <i class="fas fa-edit"></i>
                        Edit Pukal
                    </button>
                    <button class="btn btn-danger" onclick="resetAllChanges()">
                        <i class="fas fa-undo"></i>
                        Batalkan Semua
                    </button>
                </div>
            </div>
            
            <table id="marksTable">
                <thead>
                    <tr>
                        <th width="50px">#</th>
                        <th>NAMA PELAJAR</th>
                        <th>JENIS PEPERIKSAAN</th>
                        <th>MATA PELAJARAN</th>
                        <th>MARKAH SEDIA ADA</th>
                        <th>MARKAH BARU</th>
                        <th>PERUBAHAN</th>
                        <th>STATUS</th>
                        <th width="120px">TINDAKAN</th>
                    </tr>
                </thead>
                <tbody id="marksTableBody">
                    <!-- Data akan dipenuhi oleh JavaScript -->
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination">
                <button class="pagination-btn" onclick="changePage(-1)" id="prevPage">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active" onclick="goToPage(1)">1</button>
                <button class="pagination-btn" onclick="goToPage(2)">2</button>
                <button class="pagination-btn" onclick="goToPage(3)">3</button>
                <span style="padding: 0 10px; color: var(--medium-gray);">...</span>
                <button class="pagination-btn" onclick="goToPage(5)">5</button>
                <button class="pagination-btn" onclick="changePage(1)" id="nextPage">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="filter-section">
            <h3 style="margin-bottom: 15px; color: var(--dark-gray);">Ringkasan Perubahan</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="background: var(--light-gray); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--medium-gray); margin-bottom: 5px;">Jumlah Pelajar</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--primary);" id="summaryTotal">45</div>
                </div>
                <div style="background: rgba(16, 185, 129, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--success); margin-bottom: 5px;">Markah Dikemaskini</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--success);" id="summaryUpdated">0</div>
                </div>
                <div style="background: rgba(245, 158, 11, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--warning); margin-bottom: 5px;">Dalam Pengeditan</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--warning);" id="summaryEditing">0</div>
                </div>
                <div style="background: rgba(59, 130, 246, 0.1); padding: 15px; border-radius: 12px;">
                    <div style="font-size: 12px; color: var(--info); margin-bottom: 5px;">Purata Perubahan</div>
                    <div style="font-size: 24px; font-weight: 700; color: var(--info);" id="summaryAverage">+0.0%</div>
                </div>
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
            <p id="toastMessage">Markah telah berjaya dikemaskini.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const marksTableBody = document.getElementById('marksTableBody');
        const toast = document.getElementById('toast');

        // Current state
        let currentData = [];
        let filteredData = [];
        let editingRowId = null;
        let currentPage = 1;
        const itemsPerPage = 10;

        // Sample data for student marks
        const marksData = [
            { id: 'M001', studentId: 'P001', studentName: 'AHMAD BIN ALI', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 95, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M002', studentId: 'P045', studentName: 'SITI NOR AISYAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 93, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M003', studentId: 'P023', studentName: 'MOHD AMIR BIN HASSAN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 92, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M004', studentId: 'P067', studentName: 'NURUL FATIMAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 91, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M005', studentId: 'P089', studentName: 'WAN AHMAD BIN WAN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 89, newMark: null, grade: 'A', updated: false, editing: false },
            { id: 'M006', studentId: 'P034', studentName: 'NOR HIDAYAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 87, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M007', studentId: 'P078', studentName: 'ALI BIN KASSIM', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 85, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M008', studentId: 'P102', studentName: 'ROHAYU BINTI RAHIM', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 84, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M009', studentId: 'P056', studentName: 'FAIZ BIN FARID', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 82, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M010', studentId: 'P091', studentName: 'AIN NABIHAH', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 80, newMark: null, grade: 'B', updated: false, editing: false },
            { id: 'M011', studentId: 'P112', studentName: 'KAMAL BIN KAMARUDDIN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 78, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M012', studentId: 'P123', studentName: 'ZAHRAH BINTI ZAINAL', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 75, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M013', studentId: 'P134', studentName: 'HASAN BIN HUSSEIN', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 72, newMark: null, grade: 'C', updated: false, editing: false },
            { id: 'M014', studentId: 'P145', studentName: 'NORA BINTI ISMAIL', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 68, newMark: null, grade: 'D', updated: false, editing: false },
            { id: 'M015', studentId: 'P156', studentName: 'FARID BIN FAUZI', examType: 'Peperiksaan Akhir', subject: 'PJ & Kesihatan', year: '6', class: 'A', currentMark: 65, newMark: null, grade: 'D', updated: false, editing: false }
        ];

        // Initialize page
        function initializePage() {
            currentData = [...marksData];
            filteredData = [...currentData];
            
            // Set up event listeners for filters
            document.getElementById('filterExam').addEventListener('change', filterData);
            document.getElementById('filterYear').addEventListener('change', filterData);
            document.getElementById('filterClass').addEventListener('change', filterData);
            document.getElementById('filterSubject').addEventListener('change', filterData);
            
            // Load initial data
            renderTable();
            updateSummary();
        }

        // Render table with current data
        function renderTable() {
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);
            
            // Update table count
            document.getElementById('tableCount').textContent = `(${filteredData.length} pelajar)`;
            
            // Render table rows
            marksTableBody.innerHTML = pageData.map((item, index) => {
                const globalIndex = startIndex + index + 1;
                const change = item.newMark !== null ? item.newMark - item.currentMark : 0;
                const changeClass = change > 0 ? 'change-positive' : change < 0 ? 'change-negative' : '';
                const changeText = change > 0 ? `+${change}` : change < 0 ? change : '';
                
                let statusBadge = '<span style="color: var(--medium-gray);">Tidak Berubah</span>';
                if (item.editing) {
                    statusBadge = '<span style="color: var(--warning); font-weight: 600;">Dalam Pengeditan</span>';
                } else if (item.updated) {
                    statusBadge = '<span style="color: var(--success); font-weight: 600;">Telah Dikemaskini</span>';
                }
                
                return `
                    <tr id="row-${item.id}" class="${item.editing ? 'editing' : ''}">
                        <td>${globalIndex}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="student-avatar">${item.studentName.charAt(0)}</div>
                                <div>
                                    <div style="font-weight: 600;">${item.studentName}</div>
                                    <div style="font-size: 12px; color: var(--medium-gray);">ID: ${item.studentId}</div>
                                </div>
                            </div>
                        </td>
                        <td>${item.examType}</td>
                        <td>
                            <div>${item.subject}</div>
                            <div style="font-size: 12px; color: var(--medium-gray);">Tahun ${item.year}, Kelas ${item.class}</div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--primary);">${item.currentMark}%</div>
                            <span class="grade-badge grade-${item.grade.toLowerCase()}">${item.grade}</span>
                        </td>
                        <td>
                            <div class="mark-input-container">
                                <input type="number" 
                                       class="mark-input ${item.newMark !== null ? 'updated' : ''}" 
                                       id="input-${item.id}" 
                                       value="${item.newMark !== null ? item.newMark : ''}"
                                       ${!item.editing ? 'disabled' : ''}
                                       min="0" 
                                       max="100" 
                                       oninput="validateMarkInput('${item.id}', this.value)"
                                       onblur="updateMark('${item.id}', this.value)"
                                       style="${!item.editing ? 'background: var(--light-gray);' : ''}">
                                <span style="font-size: 12px; color: var(--medium-gray);">%</span>
                            </div>
                        </td>
                        <td>
                            ${item.newMark !== null ? `
                                <div style="display: flex; align-items: center;">
                                    <span style="font-weight: 600;">${item.newMark}%</span>
                                    ${changeText ? `<span class="change-indicator ${changeClass}">${changeText}</span>` : ''}
                                </div>
                            ` : '-'}
                        </td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="action-buttons">
                                ${!item.editing ? `
                                    <button class="btn-icon edit" onclick="editRow('${item.id}')" title="Edit Markah">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                ` : `
                                    <button class="btn-icon save" onclick="saveRow('${item.id}')" title="Simpan Perubahan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-icon cancel" onclick="cancelEdit('${item.id}')" title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                `}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Update pagination controls
            updatePagination();
        }

        // Filter data based on filters
        function filterData() {
            const examFilter = document.getElementById('filterExam').value;
            const yearFilter = document.getElementById('filterYear').value;
            const classFilter = document.getElementById('filterClass').value;
            const subjectFilter = document.getElementById('filterSubject').value;
            
            filteredData = currentData.filter(item => {
                // Apply filters
                if (examFilter && item.examType !== getExamTypeText(examFilter)) return false;
                if (yearFilter && item.year !== yearFilter) return false;
                if (classFilter && item.class !== classFilter) return false;
                if (subjectFilter && item.subject !== getSubjectText(subjectFilter)) return false;
                return true;
            });
            
            // Reset to first page
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Search in table
        function searchTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            
            if (!searchTerm) {
                // If search is empty, show filtered data
                filterData();
                return;
            }
            
            filteredData = currentData.filter(item => {
                return item.studentName.toLowerCase().includes(searchTerm) || 
                       item.studentId.toLowerCase().includes(searchTerm);
            });
            
            currentPage = 1;
            renderTable();
            updateSummary();
        }

        // Reset search
        function resetSearch() {
            document.getElementById('searchInput').value = '';
            filterData();
        }

        // Edit row
        function editRow(rowId) {
            // Cancel any existing edit
            if (editingRowId) {
                cancelEdit(editingRowId);
            }
            
            const item = currentData.find(item => item.id === rowId);
            if (item) {
                item.editing = true;
                editingRowId = rowId;
                
                // Enable input
                const input = document.getElementById(`input-${rowId}`);
                if (input) {
                    input.disabled = false;
                    input.focus();
                }
                
                renderTable();
                updateSummary();
            }
        }

        // Save row
        function saveRow(rowId) {
            const item = currentData.find(item => item.id === rowId);
            if (!item) return;
            
            const input = document.getElementById(`input-${rowId}`);
            if (!input) return;
            
            const newMark = parseInt(input.value);
            
            // Validate mark
            if (isNaN(newMark) || newMark < 0 || newMark > 100) {
                showToast('Ralat', 'Sila masukkan markah antara 0 hingga 100', 'error');
                return;
            }
            
            // Update item
            item.newMark = newMark;
            item.editing = false;
            item.updated = true;
            item.grade = calculateGrade(newMark);
            editingRowId = null;
            
            // Show success message
            showToast('Berjaya!', `Markah ${item.studentName} telah dikemaskini: ${newMark}%`, 'success');
            
            renderTable();
            updateSummary();
        }

        // Cancel edit
        function cancelEdit(rowId) {
            const item = currentData.find(item => item.id === rowId);
            if (item) {
                item.editing = false;
                
                // Reset input to original value
                const input = document.getElementById(`input-${rowId}`);
                if (input) {
                    input.value = item.newMark !== null ? item.newMark : '';
                    input.disabled = true;
                }
                
                editingRowId = null;
                renderTable();
                updateSummary();
            }
        }

        // Update mark directly (for onblur event)
        function updateMark(rowId, value) {
            const item = currentData.find(item => item.id === rowId);
            if (!item || !item.editing) return;
            
            const newMark = parseInt(value);
            
            // Only update if valid
            if (!isNaN(newMark) && newMark >= 0 && newMark <= 100) {
                item.newMark = newMark;
                item.updated = true;
                item.grade = calculateGrade(newMark);
            }
            
            // Re-render to show updated status
            renderTable();
            updateSummary();
        }

        // Validate mark input
        function validateMarkInput(rowId, value) {
            const input = document.getElementById(`input-${rowId}`);
            const markValue = parseInt(value);
            
            if (value === '') {
                input.classList.remove('updated');
                return;
            }
            
            if (isNaN(markValue) || markValue < 0 || markValue > 100) {
                input.style.borderColor = 'var(--danger)';
            } else {
                input.style.borderColor = '';
                input.classList.add('updated');
            }
        }

        // Calculate grade from mark
        function calculateGrade(mark) {
            if (mark >= 90) return 'A';
            if (mark >= 80) return 'B';
            if (mark >= 70) return 'C';
            if (mark >= 60) return 'D';
            return 'F';
        }

        // Bulk edit mode
        function bulkEditMode() {
            // Enable editing for all rows
            currentData.forEach(item => {
                if (!item.editing) {
                    item.editing = true;
                }
            });
            
            showToast('Mod Edit Pukal', 'Semua medan markah telah dibuka untuk pengeditan', 'success');
            renderTable();
            updateSummary();
        }

        // Save all changes
        function simpanSemuaPerubahan() {
            const updatedItems = currentData.filter(item => item.updated || item.newMark !== null);
            
            if (updatedItems.length === 0) {
                showToast('Tiada Perubahan', 'Tiada markah yang perlu disimpan', 'error');
                return;
            }
            
            // Show confirmation
            if (confirm(`Adakah anda pasti ingin menyimpan ${updatedItems.length} perubahan markah?`)) {
                // Simulate API call
                showToast('Menyimpan...', 'Semua perubahan sedang disimpan ke sistem', 'success');
                
                setTimeout(() => {
                    // Mark all as saved (reset editing state)
                    currentData.forEach(item => {
                        if (item.updated) {
                            item.currentMark = item.newMark;
                            item.newMark = null;
                            item.updated = false;
                            item.editing = false;
                        }
                    });
                    
                    showToast('Berjaya Disimpan!', 
                        `${updatedItems.length} markah telah berjaya dikemaskini dalam sistem`, 
                        'success');
                    
                    renderTable();
                    updateSummary();
                }, 1500);
            }
        }

        // Reset all changes
        function resetAllChanges() {
            const changedItems = currentData.filter(item => item.newMark !== null || item.editing);
            
            if (changedItems.length === 0) {
                showToast('Tiada Perubahan', 'Tiada perubahan untuk dibatalkan', 'error');
                return;
            }
            
            if (confirm(`Adakah anda pasti ingin membatalkan semua ${changedItems.length} perubahan?`)) {
                // Reset all items
                currentData.forEach(item => {
                    item.newMark = null;
                    item.editing = false;
                    item.updated = false;
                });
                
                editingRowId = null;
                
                showToast('Dibatal', 'Semua perubahan telah dibatalkan', 'success');
                renderTable();
                updateSummary();
            }
        }

        // Update summary statistics
        function updateSummary() {
            const total = filteredData.length;
            const updated = filteredData.filter(item => item.updated).length;
            const editing = filteredData.filter(item => item.editing).length;
            
            // Calculate average change
            const changedItems = filteredData.filter(item => item.newMark !== null);
            let totalChange = 0;
            changedItems.forEach(item => {
                totalChange += (item.newMark - item.currentMark);
            });
            
            const averageChange = changedItems.length > 0 ? 
                (totalChange / changedItems.length).toFixed(1) : 0;
            
            // Update display
            document.getElementById('summaryTotal').textContent = total;
            document.getElementById('summaryUpdated').textContent = updated;
            document.getElementById('summaryEditing').textContent = editing;
            document.getElementById('summaryAverage').textContent = 
                averageChange > 0 ? `+${averageChange}%` : `${averageChange}%`;
            document.getElementById('summaryAverage').style.color = 
                averageChange > 0 ? 'var(--success)' : averageChange < 0 ? 'var(--danger)' : 'var(--info)';
        }

        // Pagination functions
        function changePage(direction) {
            const newPage = currentPage + direction;
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderTable();
            }
        }

        function goToPage(page) {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderTable();
            }
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / itemsPerPage);
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            
            // Update button states
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            // Update page buttons (simplified for demo)
            // In a real app, you would generate dynamic page buttons
        }

        // Reload data
        function muatSemulaData() {
            // Reset filters
            document.getElementById('filterExam').value = 'akhir';
            document.getElementById('filterYear').value = '6';
            document.getElementById('filterClass').value = 'A';
            document.getElementById('filterSubject').value = 'PJH01';
            document.getElementById('searchInput').value = '';
            
            // Reset data
            currentData = [...marksData];
            filteredData = [...currentData];
            currentPage = 1;
            editingRowId = null;
            
            showToast('Data Dimuat Semula', 'Semua penapis dan perubahan telah diset semula', 'success');
            renderTable();
            updateSummary();
        }

        // Print marks
        function cetakMarkah() {
            const updatedItems = currentData.filter(item => item.updated || item.newMark !== null);
            
            if (updatedItems.length === 0 && filteredData.length === 0) {
                showToast('Tiada Data', 'Tiada data markah untuk dicetak', 'error');
                return;
            }
            
            alert('Menyediakan laporan markah untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable report
        }

        // Helper functions
        function getExamTypeText(code) {
            const examTypes = {
                'ujian1': 'Ujian 1',
                'ujian2': 'Ujian 2',
                'pertengahan': 'Peperiksaan Pertengahan',
                'akhir': 'Peperiksaan Akhir'
            };
            return examTypes[code] || code;
        }

        function getSubjectText(code) {
            const subjects = {
                'MAT01': 'Matematik',
                'BAH01': 'Bahasa Melayu',
                'BI01': 'Bahasa Inggeris',
                'SNS01': 'Sains',
                'PJH01': 'PJ & Kesihatan'
            };
            return subjects[code] || code;
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
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
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