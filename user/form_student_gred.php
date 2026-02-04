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
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
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
                <i class="fas fa-file-alt"></i>
            </div>
            <h1>Semak Keputusan Peperiksaan</h1>
            <p>Isi maklumat di bawah untuk menyemak keputusan peperiksaan terkini</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <?php
            // Tampilkan error message jika ada
            if (isset($_GET['error'])) {
                echo '<div class="error-message" style="background: #fee; color: #c33; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #c33;">
                        <i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($_GET['error']) . '
                      </div>';
            }
            ?>
            
            <form id="studentResultForm" action="proses-semak-keputusan.php" method="post">
                <!-- Nama Pelajar -->
                <div class="form-group">
                    <label for="nama">
                        <i class="fas fa-user-graduate"></i>
                        Nama Penuh Pelajar
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="nama"
                            name="nama"
                            class="form-input"
                            placeholder="Masukkan nama penuh (contoh: AHMAD BIN ALI)"
                            required
                        >
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Kad Pengenalan Pelajar -->
                <div class="form-group">
                    <label for="no_kp">
                        <i class="fas fa-id-card"></i>
                        No. Kad Pengenalan
                        <div class="info-tooltip">
                            <i class="fas fa-info-circle"></i>
                            <div class="tooltip-text">
                                Masukkan 12 digit nombor kad pengenalan (contoh: 010203045678 atau 010203-04-5678)
                            </div>
                        </div>
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="no_kp"
                            name="no_kp"
                            class="form-input"
                            placeholder="Contoh: 010203045678 atau 010203-04-5678"
                            required
                            pattern="(\d{6}-\d{2}-\d{4}|\d{12})"
                            title="Format: 010203045678 atau 010203-04-5678"
                        >
                        <div class="focus-border"></div>
                        <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                            Format: 12 digit tanpa '-' atau dengan '-' (contoh: 010203045678 atau 010203-04-5678)
                        </small>
                    </div>
                </div>

                <!-- Kelas Pelajar -->
                <div class="form-group">
                    <label for="kelas">
                        <i class="fas fa-school"></i>
                        Kelas Pelajar
                    </label>
                    <div class="input-container">
                        <select id="kelas" name="kelas" class="form-input" required>
                            <option value="">Pilih Kelas</option>
                            <option value="1A">1 ALPHA (1A)</option>
                            <option value="1B">1 BETA (1B)</option>
                            <option value="2A">2 ALPHA (2A)</option>
                            <option value="2B">2 BETA (2B)</option>
                            <option value="3A">3 ALPHA (3A)</option>
                            <option value="3B">3 BETA (3B)</option>
                            <option value="4A">4 ALPHA (4A)</option>
                            <option value="4B">4 BETA (4B)</option>
                            <option value="5A">5 ALPHA (5A)</option>
                            <option value="5B">5 BETA (5B)</option>
                            <option value="6A">6 ALPHA (6A)</option>
                            <option value="6B">6 BETA (6B)</option>
                        </select>
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Tahun Pelajar -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="id_kelas">
                            <i class="fas fa-calendar-alt"></i>
                            Tahun Pelajar
                        </label>
                        <div class="input-container">
                            <select id="id_kelas" name="id_kelas" class="form-input" required>
                                <option value="">Pilih Tahun</option>
                                <option value="1">Tahun 1</option>
                                <option value="2">Tahun 2</option>
                                <option value="3">Tahun 3</option>
                                <option value="4">Tahun 4</option>
                                <option value="5">Tahun 5</option>
                                <option value="6">Tahun 6</option>
                            </select>
                            <div class="focus-border"></div>
                        </div>
                    </div>

                    <!-- Jenis Peperiksaan -->
                    <div class="form-group">
                        <label for="examType">
                            <i class="fas fa-file-contract"></i>
                            Jenis Peperiksaan
                        </label>
                        <div class="input-container">
                            <select id="examType" name="examType" class="form-input" required>
                                <option value="">Pilih Peperiksaan</option>
                                <option value="percubaan">Peperiksaan Percubaan</option>
                                <option value="pertengahan">Peperiksaan Pertengahan Tahun</option>
                                <option value="akhir">Peperiksaan Akhir Tahun</option>
                            </select>
                            <div class="focus-border"></div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="success-message" id="successMessage" style="display: none;">
                    <div class="success-content">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="success-text">
                            <h3>Berjaya Dihantar!</h3>
                            <p>Maklumat anda sedang diproses. Keputusan akan dipaparkan sebentar lagi.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search btn-icon"></i>
                        Semak Keputusan Sekarang
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo btn-icon"></i>
                        Set Semula
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('studentResultForm');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const successMessage = document.getElementById('successMessage');
        const kelasSelect = document.getElementById('kelas');
        const tahunSelect = document.getElementById('id_kelas');
        const noKpInput = document.getElementById('no_kp');

        // Format IC number input automatically
        noKpInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            
            // Auto-format with dashes: 010203-04-5678
            if (value.length >= 7) {
                value = value.substring(0, 6) + '-' + value.substring(6);
            }
            if (value.length >= 10) {
                value = value.substring(0, 9) + '-' + value.substring(9);
            }
            
            e.target.value = value;
        });

        // Sync kelas and tahun selection
        kelasSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                // Extract year from class name (e.g., "1A" -> "1")
                const yearMatch = selectedOption.value.match(/^(\d+)/);
                if (yearMatch) {
                    tahunSelect.value = yearMatch[1];
                }
            }
        });

        tahunSelect.addEventListener('change', function() {
            const year = this.value;
            if (year) {
                // Filter and highlight matching classes
                for (let option of kelasSelect.options) {
                    if (option.value) {
                        const yearMatch = option.value.match(/^(\d+)/);
                        if (yearMatch && yearMatch[1] === year) {
                            option.style.fontWeight = 'bold';
                        } else {
                            option.style.fontWeight = 'normal';
                        }
                    }
                }
            }
        });

        // Handle form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            const nama = document.getElementById('nama').value.trim();
            const no_kp = document.getElementById('no_kp').value.trim();
            const kelas = document.getElementById('kelas').value;
            const id_kelas = document.getElementById('id_kelas').value;
            const examType = document.getElementById('examType').value;
            
            if (!nama || !no_kp || !kelas || !id_kelas || !examType) {
                alert('Sila isi semua maklumat yang diperlukan.');
                return;
            }
            
            // Validate IC number format
            const icPattern = /^\d{6}-\d{2}-\d{4}$|^\d{12}$/;
            if (!icPattern.test(no_kp)) {
                alert('Sila masukkan nombor kad pengenalan yang sah.\nFormat: 010203045678 atau 010203-04-5678');
                return;
            }
            
            // Show loading overlay
            loadingOverlay.style.display = 'flex';
            
            // Show success message after 1.5 seconds
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
                successMessage.style.display = 'block';
                
                // Submit form after showing success message
                setTimeout(() => {
                    form.submit();
                }, 2000);
            }, 1500);
        });
        
        // Reset form button
        form.querySelector('button[type="reset"]').addEventListener('click', function() {
            successMessage.style.display = 'none';
            // Reset class highlighting
            for (let option of kelasSelect.options) {
                option.style.fontWeight = 'normal';
            }
        });
    });
    </script>
</body>
</html>