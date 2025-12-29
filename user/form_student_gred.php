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
                <i class="fas fa-file-alt"></i>
            </div>
            <h1>Semak Keputusan Peperiksaan</h1>
            <p>Isi maklumat di bawah untuk menyemak keputusan peperiksaan terkini</p>
        </div>

        <!-- Form Content -->
        <div class="form-content">
            <form id="studentResultForm">
                <!-- Nama Pelajar -->
                <div class="form-group">
                    <label for="studentName">
                        <i class="fas fa-user-graduate"></i>
                        Nama Penuh Pelajar
                    </label>
                    <div class="input-container">
                        <input 
                            type="text" 
                            id="studentName" 
                            class="form-input"
                            placeholder="Masukkan nama penuh (contoh: AHMAD BIN ALI)"
                            required
                        >
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Kad Pengenalan Pelajar -->
                <div class="form-group">
                    <label for="studentIC">
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
                            id="studentIC" 
                            class="form-input"
                            placeholder="Contoh: 010203045678"
                            maxlength="12"
                            required
                        >
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Kelas Pelajar -->
                <div class="form-group">
                    <label for="studentClass">
                        <i class="fas fa-school"></i>
                        Kelas Pelajar
                    </label>
                    <div class="input-container">
                        <select id="studentClass" class="form-input" required>
                            <option value="">Pilih Kelas</option>
                            <option value="1A">1 A</option>
                            <option value="1B">1 B</option>
                            <option value="2A">2 A</option>
                            <option value="2B">2 B</option>
                            <option value="3A">3 A</option>
                            <option value="3B">3 B</option>
                            <option value="4A">4 A</option>
                            <option value="4B">4 B</option>
                            <option value="5A">5 A</option>
                            <option value="5B">5 B</option>
                            <option value="6A">6 A</option>
                            <option value="6B">6 B</option>
                        </select>
                        <div class="focus-border"></div>
                    </div>
                </div>

                <!-- Tahun Pelajar -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="studentYear">
                            <i class="fas fa-calendar-alt"></i>
                            Tahun Pelajar
                        </label>
                        <div class="input-container">
                            <select id="studentYear" class="form-input" required>
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
                            <select id="examType" class="form-input" required>
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
                <div class="success-message" id="successMessage">
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

    <script src="./js/form.js"></script>
</body>
</html>