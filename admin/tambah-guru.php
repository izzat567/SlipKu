<<<<<<< HEAD
=======
<?php
include '../config/connect.php';
?>

>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<<<<<<< HEAD
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/tambah-guru.css">

=======
    <!-- Font Awesome CDN yang lebih stabil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/tambah-pelajar.css">
>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
<<<<<<< HEAD
        <div style="color: var(--dark-gray); font-size: 18px; font-weight: 600;">Menyimpan data...</div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <h4>Berjaya!</h4>
            <p id="notificationMessage">Guru baru berjaya ditambah</p>
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <!-- Logo -->
            <a href="pengurusan_guru.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Tambah Guru</p>
                </div>
            </a>

            <!-- User Profile -->
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <h4>Pentadbir</h4>
                    <p>Admin Sistem</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="dashboard.php"><i class="fas fa-home"></i> Utama</a>
            <i class="fas fa-chevron-right"></i>
            <a href="pengurusan_guru.php"><i class="fas fa-user-tie"></i> Pengurusan Guru</a>
            <i class="fas fa-chevron-right"></i>
            <span><i class="fas fa-user-plus"></i> Tambah Guru</span>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2><i class="fas fa-user-plus"></i> Tambah Guru Baru</h2>
                <p>Isi borang di bawah untuk menambah guru baru ke dalam sistem</p>
            </div>
            <div class="action-buttons">
                <a href="pengurusan-guru.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
=======
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
                <h2>Tambah Guru Baru</h2>
                <p>Isi borang untuk menambah guru baru ke dalam sistem</p>
            </div>
        </div>

        <!-- Success Message (Hidden by default) -->
        <div class="success-message" id="successMessage" style="display: none;">
            <div class="success-content">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-text">
                    <h4>Guru Berjaya Ditambah!</h4>
                    <p>Guru baru telah berjaya ditambahkan ke dalam sistem. Anda boleh lihat guru ini dalam senarai guru.</p>
                </div>
>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
<<<<<<< HEAD
            <form id="guruForm" method="POST" action="backend/tambah_guru.php">
                <!-- Nama -->
                <div class="form-group">
                    <label for="nama">Nama Penuh <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" 
                           placeholder="Contoh: CIK AMINAH BINTI ABDULLAH" 
                           required
                           pattern="[A-Za-z\s\.\-]+"
                           title="Sila masukkan nama yang sah (huruf, titik, dan sengkang sahaja)">
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i> Masukkan nama penuh seperti dalam rekod rasmi
                    </div>
                    <div class="form-error" id="namaError"></div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Alamat Emel <span class="required">*</span></label>
                    <input type="email" id="email" name="email" 
                           placeholder="Contoh: aminah@skrp.edu.my" 
                           required
                           pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$"
                           title="Sila masukkan alamat emel yang sah">
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i> Format: nama@domain.com
                    </div>
                    <div class="form-error" id="emailError"></div>
                </div>

                <!-- No Telefon -->
                <div class="form-group">
                    <label for="no_telefon">No. Telefon <span class="required">*</span></label>
                    <input type="tel" id="no_telefon" name="no_telefon" 
                           placeholder="Contoh: 0134567890" 
                           required
                           pattern="[0-9]{10,11}"
                           title="Sila masukkan 10-11 digit nombor telefon">
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i> Masukkan 10-11 digit tanpa sengkang
                    </div>
                    <div class="form-error" id="telefonError"></div>
                </div>

                <!-- Password -->
                <div class="form-group password-toggle">
                    <label for="password">Kata Laluan <span class="required">*</span></label>
                    <input type="password" id="password" name="password" 
                           required
                           minlength="6"
                           title="Kata laluan mesti sekurang-kurangnya 6 aksara">
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="form-help">
                        <i class="fas fa-info-circle"></i> Minimum 6 aksara
                    </div>
                    <div class="form-error" id="passwordError"></div>
                </div>

                <!-- Sahkan Kata Laluan -->
                <div class="form-group password-toggle">
                    <label for="confirm_password">Sahkan Kata Laluan <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           required
                           title="Sahkan kata laluan">
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="form-error" id="confirmPasswordError"></div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select id="status" name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                        <option value="cuti">Cuti</option>
                    </select>
                    <div class="form-error" id="statusError"></div>
=======
            <div class="form-title">
                <i class="fas fa-user-plus"></i>
                <span>Maklumat Peribadi Guru</span>
            </div>

            <form id="teacherForm" action="../backend/guru.php" method="post">

                <!-- Row 1: Nama -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherName">Nama Penuh Guru <span class="required">*</span></label>
                        <input name="nama" type="text" id="teacherName" class="form-input" placeholder="Contoh: CIK AMINAH BINTI ABDULLAH" required>
                        <div class="error-message" id="nameError">Sila masukkan nama penuh guru</div>
                    </div>
                </div>

                <!-- Row 2: Email dan No Telefon -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherEmail">Emel <span class="required">*</span></label>
                        <input name="email" type="email" id="teacherEmail" class="form-input" placeholder="Contoh: aminah@skrp.edu.my" required>
                        <div class="error-message" id="emailError">Sila masukkan emel yang sah</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="teacherPhone">No. Telefon <span class="required">*</span></label>
                        <input name="no_telefon" type="text" id="teacherPhone" class="form-input" placeholder="Contoh: 0134567890" required>
                        <div class="error-message" id="phoneError">Sila masukkan nombor telefon</div>
                    </div>
                </div>

                <!-- Row 3: Status dan Password -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherStatus">Status <span class="required">*</span></label>
                        <select name="status" id="teacherStatus" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">Aktif</option>
                            <option value="tidak aktif">Tidak Aktif</option>
                        </select>
                        <div class="error-message" id="statusError">Sila pilih status</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="teacherPassword">Kata Laluan <span class="required">*</span></label>
                        <input name="password" type="password" id="teacherPassword" class="form-input" placeholder="Minimum 6 aksara" required minlength="6">
                        <div class="error-message" id="passwordError">Kata laluan sekurang-kurangnya 6 aksara</div>
                    </div>
                </div>

                <!-- Row 4: Confirm Password -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherConfirmPassword">Sahkan Kata Laluan <span class="required">*</span></label>
                        <input type="password" id="teacherConfirmPassword" class="form-input" placeholder="Taip semula kata laluan" required>
                        <div class="error-message" id="confirmPasswordError">Kata laluan tidak sepadan</div>
                    </div>
>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
<<<<<<< HEAD
                    <div class="left-actions">
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Set Semula
                        </button>
                    </div>
                    <div class="right-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Guru
                        </button>
                    </div>
=======
                    <a href="pengurusan-guru.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali Ke Senarai
                    </a>
                    <button type="submit" name="tambah_guru" class="btn btn-primary" onclick="return validateForm()">
                        <i class="fas fa-save"></i>
                        Simpan Guru
                    </button>
>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
                </div>
            </form>
        </div>
    </main>

<<<<<<< HEAD
    <!-- <script>
        // Fungsi untuk toggle password visibility
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Fungsi untuk reset form
        function resetForm() {
            if (confirm('Adakah anda pasti ingin set semula borang ini? Semua data yang telah diisi akan hilang.')) {
                document.getElementById('guruForm').reset();
                clearErrors();
                showNotification('Borang telah diset semula', 'info');
            }
        }

        // Fungsi untuk clear error messages
        function clearErrors() {
            const errors = document.querySelectorAll('.form-error');
            errors.forEach(error => error.textContent = '');
            
            const inputs = document.querySelectorAll('.form-group input, .form-group select');
            inputs.forEach(input => {
                input.classList.remove('error', 'success');
            });
        }

        // Fungsi untuk validasi form
        function validateForm() {
            let isValid = true;
            clearErrors();

            // Validasi nama
            const nama = document.getElementById('nama').value.trim();
            if (!nama) {
                document.getElementById('namaError').textContent = 'Sila isi nama penuh';
                document.getElementById('nama').classList.add('error');
                isValid = false;
            } else if (nama.length < 3) {
                document.getElementById('namaError').textContent = 'Nama mesti sekurang-kurangnya 3 aksara';
                document.getElementById('nama').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('nama').classList.add('success');
            }

            // Validasi email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                document.getElementById('emailError').textContent = 'Sila isi alamat emel';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Format emel tidak sah';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('email').classList.add('success');
            }

            // Validasi telefon
            const telefon = document.getElementById('no_telefon').value.trim();
            const phoneRegex = /^[0-9]{10,11}$/;
            if (!telefon) {
                document.getElementById('telefonError').textContent = 'Sila isi no. telefon';
                document.getElementById('no_telefon').classList.add('error');
                isValid = false;
            } else if (!phoneRegex.test(telefon)) {
                document.getElementById('telefonError').textContent = 'Format tidak sah. Masukkan 10-11 digit';
                document.getElementById('no_telefon').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('no_telefon').classList.add('success');
            }

            // Validasi password
            const password = document.getElementById('password').value;
            if (!password) {
                document.getElementById('passwordError').textContent = 'Sila isi kata laluan';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('passwordError').textContent = 'Kata laluan mesti sekurang-kurangnya 6 aksara';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('password').classList.add('success');
            }

            // Validasi sahkan password
            const confirmPassword = document.getElementById('confirm_password').value;
            if (!confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Sila sahkan kata laluan';
                document.getElementById('confirm_password').classList.add('error');
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent = 'Kata laluan tidak sepadan';
                document.getElementById('confirm_password').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('confirm_password').classList.add('success');
            }

            // Validasi status
            const status = document.getElementById('status').value;
            if (!status) {
                document.getElementById('statusError').textContent = 'Sila pilih status';
                document.getElementById('status').classList.add('error');
                isValid = false;
            } else {
                document.getElementById('status').classList.add('success');
            }

            return isValid;
        }

        // Fungsi untuk hantar form
        function submitForm(event) {
            event.preventDefault();
            
            if (!validateForm()) {
                showNotification('Sila betulkan ralat dalam borang', 'error');
                return;
            }
            
            const loading = document.getElementById('loadingOverlay');
            loading.style.display = 'flex';
            
            // Simpan ke localStorage untuk simulasi (gantikan dengan AJAX ke backend)
            setTimeout(() => {
                // Dapatkan data dari form
                const formData = new FormData(document.getElementById('guruForm'));
                const guruData = {
                    nama: formData.get('nama'),
                    email: formData.get('email'),
                    no_telefon: formData.get('no_telefon'),
                    password: formData.get('password'),
                    status: formData.get('status')
                };
                
                // Simpan ke localStorage
                const existingGuru = JSON.parse(localStorage.getItem('guruData')) || [];
                const newId = existingGuru.length > 0 ? Math.max(...existingGuru.map(g => g.id)) + 1 : 1;
                
                guruData.id = newId;
                existingGuru.push(guruData);
                localStorage.setItem('guruData', JSON.stringify(existingGuru));
                
                loading.style.display = 'none';
                showNotification(`Guru "${guruData.nama}" berjaya ditambah`, 'success');
                
                // Reset form
                document.getElementById('guruForm').reset();
                clearErrors();
                
                // Redirect ke halaman pengurusan guru selepas 2 saat
                setTimeout(() => {
                    window.location.href = 'pengurusan_guru.php';
                }, 2000);
                
            }, 1500);
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

        // Event listener untuk form submission
        document.getElementById('guruForm').addEventListener('submit', submitForm);

        // Real-time validation
        document.querySelectorAll('#guruForm input, #guruForm select').forEach(input => {
            input.addEventListener('blur', function() {
                validateForm();
            });
        });

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            // Kosongkan localStorage untuk demo (optional)
            // localStorage.removeItem('guruData');
            
            // Tambah data contoh ke localStorage jika kosong
            if (!localStorage.getItem('guruData')) {
                const contohGuru = [
                    {
                        id: 301,
                        nama: "CIK AMINAH BINTI ABDULLAH",
                        email: "aminah@skrp.edu.my",
                        no_telefon: "0134567890",
                        password: "$2y$10$guru2026Aminah",
                        status: "aktif"
                    },
                    {
                        id: 302,
                        nama: "ENCIK RAHIM BIN MAT",
                        email: "rahim@skrp.edu.my",
                        no_telefon: "0145678901",
                        password: "$2y$10$guru2026Rahim",
                        status: "aktif"
                    }
                ];
                localStorage.setItem('guruData', JSON.stringify(contohGuru));
            }
        });
    </script> -->
=======
    <script>
        // Hilangkan loading overlay selepas halaman siap dimuat
        window.addEventListener('load', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        // Fungsi validasi borang di client-side
        function validateForm() {
            let isValid = true;

            // Nama
            const name = document.getElementById('teacherName').value.trim();
            if (name === '') {
                document.getElementById('nameError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('nameError').style.display = 'none';
            }

            // Email
            const email = document.getElementById('teacherEmail').value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === '' || !emailPattern.test(email)) {
                document.getElementById('emailError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('emailError').style.display = 'none';
            }

            // No Telefon
            const phone = document.getElementById('teacherPhone').value.trim();
            if (phone === '') {
                document.getElementById('phoneError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('phoneError').style.display = 'none';
            }

            // Status
            const status = document.getElementById('teacherStatus').value;
            if (status === '') {
                document.getElementById('statusError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('statusError').style.display = 'none';
            }

            // Password
            const password = document.getElementById('teacherPassword').value;
            if (password.length < 6) {
                document.getElementById('passwordError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }

            // Confirm Password
            const confirm = document.getElementById('teacherConfirmPassword').value;
            if (password !== confirm) {
                document.getElementById('confirmPasswordError').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('confirmPasswordError').style.display = 'none';
            }

            return isValid; // Jika false, form tidak akan dihantar
        }

        // Tunjukkan mesej kejayaan jika ada parameter URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('created') === 'success') {
            document.getElementById('successMessage').style.display = 'block';
            // Auto-hide selepas 5 saat
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'none';
            }, 5000);
        }
    </script>
>>>>>>> 43ff998a8910218b439c6f6ffc9faea894d37abd
</body>
</html>