<?php
include '../config/connect.php';
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome CDN yang lebih stabil -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/tambah-pelajar.css">
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
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
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
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="pengurusan-guru.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali Ke Senarai
                    </a>
                    <button type="submit" name="tambah_guru" class="btn btn-primary" onclick="return validateForm()">
                        <i class="fas fa-save"></i>
                        Simpan Guru
                    </button>
                </div>
            </form>
        </div>
    </main>

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
</body>
</html>