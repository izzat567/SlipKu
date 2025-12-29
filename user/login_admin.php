<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semak Keputusan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/form_admin.css">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memproses maklumat anda...</div>
    </div>

    <!-- Back Button -->
    <a href="index.php" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Laman Utama</span>
    </a>

   <!-- Form Container -->
   <div class="form-container">
        <!-- Form Header -->
        <div class="form-header">
            <div class="form-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <h1>Log Masuk Admin</h1>
            <p>Sistem Pengurusan Keputusan Pelajar - Panel Pentadbir</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form id="adminLoginForm" method="POST" action="#">
                <!-- Error Message -->
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p id="errorText">Nama atau kata laluan tidak tepat</p>
                </div>

                <!-- Nama Admin -->
                <div class="form-group">
                    <label for="adminName">
                        <i class="fas fa-user-tie"></i>
                        Nama Pentadbir
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="adminName" 
                            name="admin_name"
                            class="form-input"
                            placeholder="Masukkan nama pentadbir"
                            required
                        >
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Kad Pengenalan Admin -->
                <div class="form-group">
                    <label for="adminIC">
                        <i class="fas fa-id-card"></i>
                        No. Kad Pengenalan
                        <div class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <div class="tooltip-text">
                                Masukkan 12 digit nombor kad pengenalan tanpa "-"
                            </div>
                        </div>
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="adminIC" 
                            name="admin_ic"
                            class="form-input"
                            placeholder="Contoh: 010203045678"
                            maxlength="12"
                            required
                        >
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Kata Laluan -->
                <div class="form-group">
                    <label for="adminPassword">
                        <i class="fas fa-lock"></i>
                        Kata Laluan
                    </label>
                    <div class="input-container">
                        <input 
                            type="password" 
                            id="adminPassword" 
                            name="admin_password"
                            class="form-input"
                            placeholder="Masukkan kata laluan"
                            required
                        >
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                        <div class="focus-border"></div>
                    </div>
                    <div class="forgot-password">
                        <a href="#" id="forgotPasswordLink">Lupa kata laluan?</a>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="success-message" id="successMessage">
                    <div class="success-content">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="success-text">
                            <h3>Log Masuk Berjaya!</h3>
                            <p>Anda akan dialihkan ke dashboard admin.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt btn-icon"></i>
                        Log Masuk Sebagai Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="./js/form_admin.js"></script>
</body>
</html>