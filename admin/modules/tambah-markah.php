<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Markah - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/tambah-markah.css">
    
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
                <h2>Tambah Markah 📝</h2>
                <p>Masukkan markah peperiksaan untuk pelajar</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="resetForm()">
                    <i class="fas fa-redo"></i>
                    Set Semula
                </button>
                <button class="btn btn-info" onclick="cetakPrestasi()">
                    <i class="fas fa-print"></i>
                    Cetak
                </button>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <h3 style="margin-bottom: 20px; color: var(--dark-gray);">Maklumat Peperiksaan</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label required">Jenis Peperiksaan</label>
                    <select class="form-select" id="examType">
                        <option value="">Sila Pilih</option>
                        <option value="ujian1">Ujian 1</option>
                        <option value="ujian2">Ujian 2</option>
                        <option value="pertengahan">Peperiksaan Pertengahan Tahun</option>
                        <option value="akhir" selected>Peperiksaan Akhir Tahun</option>
                        <option value="lain">Lain-lain</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Mata Pelajaran</label>
                    <select class="form-select" id="subject">
                        <option value="">Sila Pilih</option>
                        <option value="MAT01">Matematik</option>
                        <option value="BAH01">Bahasa Melayu</option>
                        <option value="BI01">Bahasa Inggeris</option>
                        <option value="SNS01">Sains</option>
                        <option value="PJH01" selected>PJ & Kesihatan</option>
                        <option value="PIS01">Pendidikan Islam</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Tahun</label>
                    <select class="form-select" id="year">
                        <option value="">Sila Pilih</option>
                        <option value="1">Tahun 1</option>
                        <option value="2">Tahun 2</option>
                        <option value="3">Tahun 3</option>
                        <option value="4">Tahun 4</option>
                        <option value="5">Tahun 5</option>
                        <option value="6" selected>Tahun 6</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">Kelas</label>
                    <select class="form-select" id="class">
                        <option value="">Sila Pilih</option>
                        <option value="A" selected>Kelas A</option>
                        <option value="B">Kelas B</option>
                        <option value="C">Kelas C</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea class="form-textarea" id="notes" placeholder="Masukkan catatan tentang peperiksaan ini..."></textarea>
            </div>
        </div>

        <!-- Subject Info Card -->
        <div class="subject-info-card" id="subjectInfoCard">
            <div class="subject-info-content">
                <div class="subject-details">
                    <h4 id="subjectNameDisplay">PJ & Kesihatan</h4>
                    <p id="subjectClassDisplay">Tahun 6, Kelas A</p>
                </div>
                <div class="subject-stats">
                    <div class="subject-stat">
                        <div class="value" id="totalStudents">45</div>
                        <div class="label">Jumlah Pelajar</div>
                    </div>
                    <div class="subject-stat">
                        <div class="value" id="marksEntered">0</div>
                        <div class="label">Markah Dimasukkan</div>
                    </div>
                    <div class="subject-stat">
                        <div class="value" id="averageMark">0%</div>
                        <div class="label">Purata Sementara</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Selection -->
        <div class="student-selection">
            <div class="selection-header">
                <h3>Senarai Pelajar</h3>
                <div style="display: flex; gap: 10px;">
                    <button class="btn btn-secondary" onclick="selectAllStudents()">
                        <i class="fas fa-check-square"></i>
                        Pilih Semua
                    </button>
                    <button class="btn btn-secondary" onclick="deselectAllStudents()">
                        <i class="fas fa-square"></i>
                        Nyahpilih Semua
                    </button>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="studentSearch" placeholder="Cari pelajar..." onkeyup="searchStudents()">
            </div>
            
            <div class="student-table-container">
                <table class="student-table" id="studentTable">
                    <thead>
                        <tr>
                            <th width="50px">
                                <input type="checkbox" id="selectAll" onclick="toggleSelectAll()" class="select-checkbox">
                            </th>
                            <th>NAMA PELAJAR</th>
                            <th>NO. KAD PENGENALAN</th>
                            <th>MARKAH TERKINI</th>
                            <th>GRED</th>
                            <th>MARKAH BARU</th>
                            <th>GRED BARU</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Data akan dipenuhi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button class="btn btn-danger" onclick="resetForm()">
                <i class="fas fa-times"></i>
                Batal
            </button>
            <button class="btn btn-success" onclick="saveMarks()">
                <i class="fas fa-save"></i>
                Simpan Semua Markah
            </button>
            <button class="btn btn-primary" onclick="saveAndNext()">
                <i class="fas fa-arrow-right"></i>
                Simpan & Seterusnya
            </button>
        </div>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast-content">
            <h4 id="toastTitle">Berjaya!</h4>
            <p id="toastMessage">Markah telah berjaya disimpan.</p>
        </div>
    </div>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const studentTableBody = document.getElementById('studentTableBody');
        const toast = document.getElementById('toast');
        const subjectInfoCard = document.getElementById('subjectInfoCard');

        // Sample data for students
        const studentData = [
            { id: 'P001', name: 'AHMAD BIN ALI', ic: '080101-01-1234', currentMark: 95, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P045', name: 'SITI NOR AISYAH', ic: '080202-02-2345', currentMark: 93, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P023', name: 'MOHD AMIR BIN HASSAN', ic: '080303-03-3456', currentMark: 92, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P067', name: 'NURUL FATIMAH', ic: '080404-04-4567', currentMark: 91, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P089', name: 'WAN AHMAD BIN WAN', ic: '080505-05-5678', currentMark: 89, grade: 'A', newMark: '', newGrade: '' },
            { id: 'P034', name: 'NOR HIDAYAH', ic: '080606-06-6789', currentMark: 87, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P078', name: 'ALI BIN KASSIM', ic: '080707-07-7890', currentMark: 85, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P102', name: 'ROHAYU BINTI RAHIM', ic: '080808-08-8901', currentMark: 84, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P056', name: 'FAIZ BIN FARID', ic: '080909-09-9012', currentMark: 82, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P091', name: 'AIN NABIHAH', ic: '080101-10-0123', currentMark: 80, grade: 'B', newMark: '', newGrade: '' },
            { id: 'P112', name: 'KAMAL BIN KAMARUDDIN', ic: '080202-11-1234', currentMark: 78, grade: 'C', newMark: '', newGrade: '' },
            { id: 'P123', name: 'ZAHRAH BINTI ZAINAL', ic: '080303-12-2345', currentMark: 75, grade: 'C', newMark: '', newGrade: '' }
        ];

        // Initialize page
        function initializePage() {
            // Populate student table
            populateStudentTable();
            
            // Set up event listeners for form fields
            document.getElementById('subject').addEventListener('change', updateSubjectInfo);
            document.getElementById('year').addEventListener('change', updateSubjectInfo);
            document.getElementById('class').addEventListener('change', updateSubjectInfo);
            
            // Initialize subject info
            updateSubjectInfo();
            
            // Set up mark input listeners
            setTimeout(setupMarkInputListeners, 100);
        }

        // Populate student table
        function populateStudentTable() {
            studentTableBody.innerHTML = studentData.map((student, index) => `
                <tr id="studentRow-${student.id}" class="${student.newMark ? 'selected' : ''}">
                    <td>
                        <input type="checkbox" class="select-checkbox student-checkbox" id="select-${student.id}" onchange="toggleStudentSelection('${student.id}')">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="student-avatar">${student.name.charAt(0)}</div>
                            <div>
                                <div style="font-weight: 600;">${student.name}</div>
                                <div style="font-size: 12px; color: var(--medium-gray);">ID: ${student.id}</div>
                            </div>
                        </div>
                    </td>
                    <td>${student.ic}</td>
                    <td>
                        <div style="font-weight: 700; color: var(--primary);">${student.currentMark}%</div>
                        <span class="grade-badge grade-${student.grade.toLowerCase()}">${student.grade}</span>
                    </td>
                    <td>
                        <div class="mark-input-group">
                            <input type="number" 
                                   class="mark-input" 
                                   id="mark-${student.id}" 
                                   min="0" 
                                   max="100" 
                                   placeholder="0-100" 
                                   value="${student.newMark || ''}"
                                   oninput="updateGrade('${student.id}', this.value)"
                                   onblur="validateMark('${student.id}', this.value)">
                        </div>
                    </td>
                    <td>
                        <div id="gradeDisplay-${student.id}" class="grade-preview">
                            <div class="grade-info">
                                <span id="gradeText-${student.id}" style="font-weight: 600; color: var(--medium-gray);">-</span>
                                <span id="gradeBadge-${student.id}" class="grade-badge"></span>
                            </div>
                            <div id="markChange-${student.id}" style="font-size: 12px; color: var(--medium-gray);"></div>
                        </div>
                    </td>
                </tr>
            `).join('');
            
            // Update grade displays
            studentData.forEach(student => {
                if (student.newMark) {
                    updateGrade(student.id, student.newMark);
                }
            });
        }

        // Update grade based on mark
        function updateGrade(studentId, mark) {
            const student = studentData.find(s => s.id === studentId);
            if (!student) return;
            
            const markValue = parseInt(mark);
            const currentMark = parseInt(student.currentMark);
            
            if (!isNaN(markValue) && markValue >= 0 && markValue <= 100) {
                student.newMark = markValue;
                
                // Calculate grade
                let grade, gradeClass;
                if (markValue >= 90) { grade = 'A'; gradeClass = 'grade-a'; }
                else if (markValue >= 80) { grade = 'B'; gradeClass = 'grade-b'; }
                else if (markValue >= 70) { grade = 'C'; gradeClass = 'grade-c'; }
                else if (markValue >= 60) { grade = 'D'; gradeClass = 'grade-d'; }
                else { grade = 'F'; gradeClass = 'grade-f'; }
                
                student.newGrade = grade;
                
                // Update display
                const gradeText = document.getElementById(`gradeText-${studentId}`);
                const gradeBadge = document.getElementById(`gradeBadge-${studentId}`);
                const markChange = document.getElementById(`markChange-${studentId}`);
                const row = document.getElementById(`studentRow-${studentId}`);
                
                if (gradeText && gradeBadge) {
                    gradeText.textContent = `${markValue}%`;
                    gradeBadge.textContent = grade;
                    gradeBadge.className = `grade-badge ${gradeClass}`;
                    
                    // Show change from previous mark
                    const change = markValue - currentMark;
                    if (change !== 0) {
                        markChange.innerHTML = change > 0 ? 
                            `<span style="color: var(--success);">+${change}</span>` : 
                            `<span style="color: var(--danger);">${change}</span>`;
                    } else {
                        markChange.innerHTML = '';
                    }
                    
                    // Highlight row if mark is entered
                    if (row) {
                        row.classList.add('selected');
                    }
                }
            } else if (mark === '' || isNaN(markValue)) {
                // Clear if empty or invalid
                student.newMark = '';
                student.newGrade = '';
                
                const gradeText = document.getElementById(`gradeText-${studentId}`);
                const gradeBadge = document.getElementById(`gradeBadge-${studentId}`);
                const markChange = document.getElementById(`markChange-${studentId}`);
                const row = document.getElementById(`studentRow-${studentId}`);
                
                if (gradeText && gradeBadge) {
                    gradeText.textContent = '-';
                    gradeBadge.textContent = '';
                    gradeBadge.className = 'grade-badge';
                    markChange.innerHTML = '';
                    
                    if (row) {
                        row.classList.remove('selected');
                    }
                }
            }
            
            // Update marks entered count
            updateMarksEnteredCount();
            
            // Update checkbox
            const checkbox = document.getElementById(`select-${studentId}`);
            if (checkbox) {
                checkbox.checked = !!student.newMark;
            }
        }

        // Validate mark input
        function validateMark(studentId, value) {
            const input = document.getElementById(`mark-${studentId}`);
            const markValue = parseInt(value);
            
            if (value === '') {
                input.classList.remove('full-mark');
                return;
            }
            
            if (isNaN(markValue) || markValue < 0 || markValue > 100) {
                input.style.borderColor = 'var(--danger)';
                showToast('Ralat', 'Sila masukkan markah antara 0 hingga 100', 'error');
            } else if (markValue === 100) {
                input.classList.add('full-mark');
                input.style.borderColor = '';
            } else {
                input.classList.remove('full-mark');
                input.style.borderColor = '';
            }
        }

        // Update subject info based on form selections
        function updateSubjectInfo() {
            const subjectSelect = document.getElementById('subject');
            const yearSelect = document.getElementById('year');
            const classSelect = document.getElementById('class');
            
            const subjectText = subjectSelect.options[subjectSelect.selectedIndex]?.text || 'Sila pilih mata pelajaran';
            const yearText = yearSelect.value ? `Tahun ${yearSelect.value}` : 'Sila pilih tahun';
            const classText = classSelect.value ? `Kelas ${classSelect.value}` : 'Sila pilih kelas';
            
            document.getElementById('subjectNameDisplay').textContent = subjectText;
            document.getElementById('subjectClassDisplay').textContent = `${yearText}, ${classText}`;
            
            // Show/hide subject info card
            if (subjectSelect.value && yearSelect.value && classSelect.value) {
                subjectInfoCard.style.display = 'block';
                
                // Update stats based on selections
                const classFilter = classSelect.value;
                const filteredStudents = classFilter === 'A' ? studentData : 
                                       classFilter === 'B' ? studentData.slice(0, 8) : 
                                       studentData.slice(0, 6);
                
                document.getElementById('totalStudents').textContent = filteredStudents.length;
                
                // Calculate marks entered and average
                updateMarksEnteredCount();
            } else {
                subjectInfoCard.style.display = 'none';
            }
        }

        // Update marks entered count and average
        function updateMarksEnteredCount() {
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            const totalStudents = studentData.length;
            
            document.getElementById('marksEntered').textContent = `${marksEntered}/${totalStudents}`;
            
            // Calculate average
            const marksWithValues = studentData.filter(s => s.newMark !== '');
            if (marksWithValues.length > 0) {
                const total = marksWithValues.reduce((sum, s) => sum + parseInt(s.newMark), 0);
                const average = Math.round(total / marksWithValues.length);
                document.getElementById('averageMark').textContent = `${average}%`;
            } else {
                document.getElementById('averageMark').textContent = '0%';
            }
        }

        // Setup mark input listeners
        function setupMarkInputListeners() {
            studentData.forEach(student => {
                const input = document.getElementById(`mark-${student.id}`);
                if (input) {
                    input.addEventListener('keyup', function(e) {
                        if (e.key === 'Enter') {
                            // Move to next input
                            const inputs = Array.from(document.querySelectorAll('.mark-input'));
                            const currentIndex = inputs.indexOf(this);
                            if (currentIndex < inputs.length - 1) {
                                inputs[currentIndex + 1].focus();
                            }
                        }
                    });
                }
            });
        }

        // Search students
        function searchStudents() {
            const searchTerm = document.getElementById('studentSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#studentTableBody tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const ic = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || ic.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Toggle select all students
        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const isChecked = selectAllCheckbox.checked;
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                const studentId = checkbox.id.replace('select-', '');
                
                if (isChecked) {
                    // Find student and set a default mark if empty
                    const student = studentData.find(s => s.id === studentId);
                    const markInput = document.getElementById(`mark-${studentId}`);
                    
                    if (student && (!student.newMark || student.newMark === '')) {
                        // Set mark to current mark or random between 70-95
                        const newMark = student.currentMark || Math.floor(Math.random() * 26) + 70;
                        markInput.value = newMark;
                        updateGrade(studentId, newMark);
                    }
                } else {
                    // Clear marks for all
                    const markInput = document.getElementById(`mark-${studentId}`);
                    markInput.value = '';
                    updateGrade(studentId, '');
                }
            });
        }

        // Select all students
        function selectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = true;
            toggleSelectAll();
        }

        // Deselect all students
        function deselectAllStudents() {
            const selectAllCheckbox = document.getElementById('selectAll');
            selectAllCheckbox.checked = false;
            toggleSelectAll();
        }

        // Toggle student selection
        function toggleStudentSelection(studentId) {
            const checkbox = document.getElementById(`select-${studentId}`);
            const markInput = document.getElementById(`mark-${studentId}`);
            const student = studentData.find(s => s.id === studentId);
            
            if (checkbox.checked) {
                if (!student.newMark || student.newMark === '') {
                    const newMark = student.currentMark || Math.floor(Math.random() * 26) + 70;
                    markInput.value = newMark;
                    updateGrade(studentId, newMark);
                }
            } else {
                markInput.value = '';
                updateGrade(studentId, '');
            }
        }

        // Save marks
        function saveMarks() {
            const examType = document.getElementById('examType').value;
            const subject = document.getElementById('subject').value;
            const year = document.getElementById('year').value;
            const className = document.getElementById('class').value;
            
            // Validation
            if (!examType || !subject || !year || !className) {
                showToast('Ralat', 'Sila isi semua maklumat peperiksaan terlebih dahulu', 'error');
                return;
            }
            
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            if (marksEntered === 0) {
                showToast('Ralat', 'Sila masukkan markah untuk sekurang-kurangnya seorang pelajar', 'error');
                return;
            }
            
            // Show loading/saving state
            showToast('Menyimpan...', 'Markah sedang disimpan ke sistem', 'success');
            
            // Simulate API call
            setTimeout(() => {
                // Calculate statistics
                const marksWithValues = studentData.filter(s => s.newMark !== '');
                const total = marksWithValues.reduce((sum, s) => sum + parseInt(s.newMark), 0);
                const average = Math.round(total / marksWithValues.length);
                
                showToast('Berjaya!', 
                    `Markah untuk ${marksEntered} pelajar telah disimpan. Purata: ${average}%`, 
                    'success');
                
                // Reset form after success
                setTimeout(() => {
                    if (confirm(`Markah berjaya disimpan!\n\nJumlah pelajar: ${marksEntered}\nPurata markah: ${average}%\n\nAdakah anda ingin set semula borang untuk mata pelajaran seterusnya?`)) {
                        resetForm();
                    }
                }, 1500);
                
            }, 1000);
        }

        // Save and proceed to next
        function saveAndNext() {
            saveMarks();
            
            // In a real app, this would redirect or load next subject/class
            setTimeout(() => {
                alert('Anda akan dibawa ke halaman tambah markah untuk mata pelajaran seterusnya.');
                // Simulate redirect
                window.location.href = 'tambah-markah.html?next=subject';
            }, 2000);
        }

        // Reset form
        function resetForm() {
            if (confirm('Adakah anda pasti ingin set semula semua markah? Tindakan ini tidak boleh dibatalkan.')) {
                // Reset form fields
                document.getElementById('examType').value = 'akhir';
                document.getElementById('subject').value = 'PJH01';
                document.getElementById('year').value = '6';
                document.getElementById('class').value = 'A';
                document.getElementById('notes').value = '';
                document.getElementById('studentSearch').value = '';
                document.getElementById('selectAll').checked = false;
                
                // Clear all marks
                studentData.forEach(student => {
                    student.newMark = '';
                    student.newGrade = '';
                });
                
                // Re-populate table
                populateStudentTable();
                
                // Update subject info
                updateSubjectInfo();
                
                // Reset search
                searchStudents();
                
                showToast('Diset semula', 'Semua markah telah dikosongkan', 'success');
            }
        }

        // Print function
        function cetakPrestasi() {
            const marksEntered = studentData.filter(s => s.newMark !== '').length;
            if (marksEntered === 0) {
                showToast('Ralat', 'Tiada markah untuk dicetak', 'error');
                return;
            }
            
            alert('Menyediakan laporan markah untuk dicetak...\n\nTekan Ctrl+P untuk mencetak.');
            // In a real app, this would generate a printable report
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