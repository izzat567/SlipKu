<?php
include '../config/connect.php';

// Dapatkan ID guru dari URL
$id_guru = $_GET['id'] ?? 0;

// Ambil data guru
$guru_sql = mysqli_query($conn, "SELECT * FROM guru WHERE id = '$id_guru'");
$guru = mysqli_fetch_assoc($guru_sql);

// Jika guru tidak dijumpai, redirect ke senarai guru
if (!$guru) {
    header('Location: pengurusan-guru.php?error=notfound');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Guru - SlipKu</title>
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
                <h2>Kemaskini Guru</h2>
                <p>Mengemaskini maklumat guru dalam sistem</p>
            </div>
        </div>

        <!-- Success Message (Hidden by default) -->
        <div class="success-message" id="successMessage" style="display: none;">
            <div class="success-content">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-text">
                    <h4>Guru Berjaya Dikemaskini!</h4>
                    <p>Maklumat guru telah berjaya dikemaskini. Anda boleh lihat perubahan dalam senarai guru.</p>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-title">
                <i class="fas fa-user-edit"></i>
                <span>Maklumat Peribadi Guru</span>
            </div>

            <form id="teacherForm" method="POST" action="../backend/admin.php">
                <!-- Hidden input untuk ID guru -->
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($guru['id']); ?>">

                <!-- Row 1: Nama -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherName">Nama Penuh Guru <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="nama" 
                            id="teacherName" 
                            class="form-input" 
                            value="<?php echo htmlspecialchars($guru['nama']); ?>" 
                            placeholder="Contoh: CIK AMINAH BINTI ABDULLAH" 
                            required
                        >
                        <div class="error-message" id="nameError">Sila masukkan nama penuh guru</div>
                    </div>
                </div>

                <!-- Row 2: Email dan No. Telefon -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherEmail">Emel <span class="required">*</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            id="teacherEmail" 
                            class="form-input" 
                            value="<?php echo htmlspecialchars($guru['email']); ?>" 
                            placeholder="Contoh: aminah@skrp.edu.my" 
                            required
                        >
                        <div class="error-message" id="emailError">Sila masukkan emel yang sah</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="teacherPhone">No. Telefon <span class="required">*</span></label>
                        <input 
                            type="text" 
                            name="no_telefon" 
                            id="teacherPhone" 
                            class="form-input" 
                            value="<?php echo htmlspecialchars($guru['no_telefon']); ?>" 
                            placeholder="Contoh: 0134567890" 
                            required
                        >
                        <div class="error-message" id="phoneError">Sila masukkan nombor telefon</div>
                    </div>
                </div>

                <!-- Row 3: Status dan (ruang untuk jantina jika ada) -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="teacherStatus">Status <span class="required">*</span></label>
                        <select name="status" id="teacherStatus" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" <?php echo ($guru['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="tidak aktif" <?php echo ($guru['status'] == 'tidak aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                        <div class="error-message" id="statusError">Sila pilih status</div>
                    </div>
                    <!-- Jika ada medan jantina, boleh ditambah di sini. Buat masa sekarang, kosong. -->
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="pengurusan-guru.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali Ke Senarai
                    </a>
                    <button type="submit" name="kemaskini_guru" class="btn btn-primary" onclick="return validateForm()">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
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

        // Fungsi validasi mudah (client-side)
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

            return isValid; // Jika false, form tidak akan dihantar
        }

        // Optional: Tunjukkan mesej kejayaan selepas redirect (gunakan parameter URL)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('updated') === 'success') {
            document.getElementById('successMessage').style.display = 'block';
            // Auto-hide selepas 5 saat
            setTimeout(() => {
                document.getElementById('successMessage').style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>