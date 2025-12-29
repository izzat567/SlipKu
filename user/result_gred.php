<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Keputusan Peperiksaan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/result.css">
    
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Sistem Keputusan Peperiksaan Digital</p>
                </div>
            </div>
            <a href="index.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Semakan</span>
            </a>
        </div>

        <!-- Result Container -->
        <div class="result-container">
            <!-- Result Header -->
            <div class="result-header">
                <h1 class="result-title">Keputusan Peperiksaan Pelajar</h1>
                <p class="result-subtitle">Peperiksaan Akhir Tahun 2023</p>
            </div>

            <!-- Student Info -->
            <div class="student-info">
                <div class="student-details">
                    <h2 id="studentName">Syazrin Hadri Bin Shahrui Nizam</h2>
                    <p><i class="fas fa-graduation-cap"></i> Kelas: 6 Bestari</p>
                    <p><i class="fas fa-id-card"></i> No. KP: 030514100335</p>
                    <p><i class="fas fa-calendar-alt"></i> Tarikh: 15 Disember 2023</p>
                </div>
                <div class="student-photo">
                    <!-- Placeholder for student photo -->
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-light), var(--primary)); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>

            <!-- Subjects Section -->
            <div class="subjects-container">
                <h2 class="subjects-title"><i class="fas fa-book-open"></i> Keputusan Mata Pelajaran</h2>
                
                <div class="subject-cards" id="subjectCards">
                    <!-- Subject cards will be populated by JavaScript -->
                </div>
            </div>

            <!-- Summary Section -->
            <div class="summary-container">
                <h2 class="summary-title"><i class="fas fa-chart-line"></i> Ringkasan Prestasi</h2>
                <div class="summary-content">
                    <div class="summary-item">
                        <div class="summary-value" id="totalSubjects">6</div>
                        <div class="summary-label">Jumlah Mata Pelajaran</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value" id="averageGrade">A-</div>
                        <div class="summary-label">Purata Gred</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value" id="gpaScore">3.67</div>
                        <div class="summary-label">GPA</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value" id="rankPosition">12/45</div>
                        <div class="summary-label">Kedudukan dalam Kelas</div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="actions-container">
                <a href="#" class="download-button" id="downloadBtn">
                    <i class="fas fa-download"></i>
                    Muat Turun Slip Keputusan
                </a>
                <div class="share-options">
                    <a href="#" class="share-button" title="Kongsi melalui WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="share-button" title="Kongsi melalui Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="#" class="share-button" title="Cetak Slip">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>SlipKu &copy; 2023 - Sistem Keputusan Peperiksaan Digital. Semua hakcipta terpelihara.</p>
            <p>Slip ini sah untuk tujuan rujukan rasmi.</p>
        </div>
    </div>

    <script src="./js/result.js">
     
    </script>
</body>
</html>