<?php
// result_gred.php
session_start();
require_once __DIR__ . '../../../config/connect.php';

// SEMAK 1: Jika tiada data keputusan
if (!isset($_SESSION['result_data'])) {
    // SEMAK 2: Jika ada error, redirect dengan error
    if (isset($_SESSION['error'])) {
        $error_msg = $_SESSION['error'];
        unset($_SESSION['error']);
        header("Location: form_student_gred.php?error=" . urlencode($error_msg));
        exit();
    }
    // Jika tiada data langsung, redirect ke form
    header("Location: form_student_gred.php");
    exit();
}

// SEMAK 3: Validasi struktur data
$data = $_SESSION['result_data'];

if (!isset($data['student']) || !isset($data['subjects']) || empty($data['subjects'])) {
    header("Location: form_student_gred.php?error=Data+keputusan+tidak+lengkap");
    unset($_SESSION['result_data']);
    exit();
}

// SEMAK 4: Pastikan ada sekurang-kurangnya 1 mata pelajaran
if (count($data['subjects']) == 0) {
    header("Location: form_student_gred.php?error=Tiada+rekod+keputusan+ditemui");
    unset($_SESSION['result_data']);
    exit();
}

// Dapatkan data dari session
$student = $data['student'];
$exam = $data['exam'];
$subjects = $data['subjects'];
$summary = $data['summary'];

// Format tarikh
$current_date = date('d F Y');
$exam_year = date('Y');

// Fungsi untuk kelas warna gred
function getGradeColorClass($grade) {
    $gradeColors = [
        'A+' => 'grade-a-plus',
        'A' => 'grade-a',
        'A-' => 'grade-a-minus',
        'B+' => 'grade-b-plus',
        'B' => 'grade-b',
        'B-' => 'grade-b-minus',
        'C+' => 'grade-c-plus',
        'C' => 'grade-c',
        'C-' => 'grade-c-minus',
        'D' => 'grade-d',
        'E' => 'grade-e',
        'F' => 'grade-f'
    ];
    return $gradeColors[$grade] ?? 'grade-default';
}

// Fungsi untuk dapatkan icon berdasarkan gred
function getGradeIcon($grade) {
    $icons = [
        'A+' => 'fa-trophy',
        'A' => 'fa-star',
        'A-' => 'fa-star-half-alt',
        'B+' => 'fa-award',
        'B' => 'fa-certificate',
        'B-' => 'fa-ribbon',
        'C+' => 'fa-medal',
        'C' => 'fa-shield-alt',
        'C-' => 'fa-shield',
        'D' => 'fa-flag',
        'E' => 'fa-exclamation-triangle',
        'F' => 'fa-times-circle'
    ];
    return $icons[$grade] ?? 'fa-question-circle';
}
?>
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
    <style>
        /* Tambahan CSS untuk validation warning */
        .validation-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .validation-warning i {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Validation Warning (akan dipaparkan jika ada issue) -->
        <?php if (!isset($student['id']) || empty($student['id'])): ?>
        <div class="validation-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Perhatian:</strong> Maklumat pelajar tidak lengkap. 
                Sila semak dengan pihak sekolah jika terdapat sebarang kesilapan.
            </div>
        </div>
        <?php endif; ?>

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
                <span>Kembali ke Laman Utama</span>
            </a>
        </div>

        <!-- Result Container -->
        <div class="result-container">
            <!-- Result Header -->
            <div class="result-header">
                <h1 class="result-title">Keputusan Peperiksaan Pelajar</h1>
                <p class="result-subtitle">
                    <?php echo htmlspecialchars($exam['nama_peperiksaan'] ?? 'Peperiksaan'); ?> 
                    <?php echo $exam_year; ?>
                </p>
                <p class="result-id">
                    <small>Rujukan: STU<?php echo str_pad($student['id'] ?? '000', 3, '0', STR_PAD_LEFT); ?>-<?php echo date('Ymd'); ?></small>
                </p>
            </div>

            <!-- Student Info -->
            <div class="student-info">
                <div class="student-details">
                    <h2 id="studentName"><?php echo htmlspecialchars($student['nama']); ?></h2>
                    <p><i class="fas fa-graduation-cap"></i> Kelas: <?php echo htmlspecialchars($student['nama_kelas'] ?? $student['id_kelas']); ?></p>
                    <p><i class="fas fa-id-card"></i> No. KP: <?php echo htmlspecialchars($student['no_kp']); ?></p>
                    <p><i class="fas fa-calendar-alt"></i> Tarikh: <?php echo $current_date; ?></p>
                    <p><i class="fas fa-chart-bar"></i> Jenis: <?php echo htmlspecialchars(ucfirst($exam['jenis'] ?? 'Peperiksaan')); ?></p>
                </div>
                <div class="student-photo">
                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-light), var(--primary)); display: flex; align-items: center; justify-content: center; color: white; font-size: 40px; border-radius: 8px;">
                        <?php if ($student['jantina'] == 'P'): ?>
                            <i class="fas fa-female"></i>
                        <?php else: ?>
                            <i class="fas fa-male"></i>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Subjects Section -->
            <div class="subjects-container">
                <h2 class="subjects-title">
                    <i class="fas fa-book-open"></i> 
                    Keputusan Mata Pelajaran
                    <span class="subject-count">(<?php echo count($subjects); ?> mata pelajaran)</span>
                </h2>
                
                <div class="subject-cards" id="subjectCards">
                    <?php foreach ($subjects as $index => $subject): 
                        $matapelajaran = $subject['matapelajaran'];
                        $markah = $subject['markah'];
                        $grade = $markah['gred'] ?? 'N/A';
                        $score = $markah['markah'] ?? 0;
                        $comment = $markah['catatan'] ?? 'Tiada maklumat.';
                        $grade_icon = getGradeIcon($grade);
                    ?>
                    <div class="subject-card" style="animation-delay: <?php echo ($index * 0.1 + 0.6); ?>s;">
                        <div class="subject-header">
                            <div class="subject-name">
                                <i class="fas <?php echo $grade_icon; ?> grade-icon"></i>
                                <?php echo htmlspecialchars($matapelajaran['nama']); ?>
                                <span class="subject-code">(<?php echo htmlspecialchars($matapelajaran['kod'] ?? 'N/A'); ?>)</span>
                            </div>
                            <div class="subject-grade <?php echo getGradeColorClass($grade); ?>">
                                <?php echo htmlspecialchars($grade); ?>
                                <?php if ($score > 0): ?>
                                <div class="subject-score"><?php echo $score; ?>%</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="subject-comment">
                            <i class="fas fa-comment-dots"></i>
                            <?php echo htmlspecialchars($comment); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Summary Section -->
            <div class="summary-container">
                <h2 class="summary-title"><i class="fas fa-chart-line"></i> Ringkasan Prestasi</h2>
                <div class="summary-content">
                    <div class="summary-item">
                        <div class="summary-value" id="totalSubjects"><?php echo $summary['total_subjects']; ?></div>
                        <div class="summary-label">Jumlah Mata Pelajaran</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value grade-value <?php echo getGradeColorClass($summary['average_grade']); ?>" 
                             id="averageGrade">
                            <?php echo $summary['average_grade']; ?>
                        </div>
                        <div class="summary-label">Purata Gred</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value" id="gpaScore"><?php echo number_format($summary['gpa'], 2); ?></div>
                        <div class="summary-label">GPA</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value" id="rankPosition">
                            <?php echo $summary['rank']; ?>
                            <?php if ($summary['rank'] != 'N/A' && $summary['rank'] <= 3): ?>
                                <i class="fas fa-crown rank-icon" style="color: #FFD700; margin-left: 5px;"></i>
                            <?php endif; ?>
                        </div>
                        <div class="summary-label">Kedudukan dalam Kelas</div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="actions-container">
                <button class="download-button" id="downloadBtn" onclick="downloadSlip()">
                    <i class="fas fa-download"></i>
                    Muat Turun Slip Keputusan
                </button>
                <div class="share-options">
                    <a href="#" class="share-button" title="Kongsi melalui WhatsApp" onclick="shareViaWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="share-button" title="Kongsi melalui Email" onclick="shareViaEmail()">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <a href="#" class="share-button" title="Cetak Slip" onclick="window.print()">
                        <i class="fas fa-print"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>SlipKu &copy; <?php echo date('Y'); ?> - Sistem Keputusan Peperiksaan Digital. Semua hakcipta terpelihara.</p>
            <p>Slip ini sah untuk tujuan rujukan rasmi. Rujukan: STU<?php echo str_pad($student['id'] ?? '000', 3, '0', STR_PAD_LEFT); ?>-<?php echo date('Ymd'); ?></p>
        </div>
    </div>

    <script src="./js/result.js"></script>
    <script>
    // Function untuk download slip
    function downloadSlip() {
        const downloadBtn = document.getElementById('downloadBtn');
        const originalText = downloadBtn.innerHTML;
        
        // Show loading
        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        downloadBtn.disabled = true;
        
        // Simulate download process
        setTimeout(() => {
            // Create a printable version
            const printContent = document.querySelector('.container').innerHTML;
            const originalContent = document.body.innerHTML;
            
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            
            // Reset button
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
            
            // Show success message
            alert('Slip keputusan siap untuk dicetak!');
        }, 1500);
    }
    
    // Function untuk share via WhatsApp
    function shareViaWhatsApp() {
        const studentName = document.getElementById('studentName').textContent;
        const totalSubjects = document.getElementById('totalSubjects').textContent;
        const averageGrade = document.getElementById('averageGrade').textContent;
        const gpaScore = document.getElementById('gpaScore').textContent;
        const rankPosition = document.getElementById('rankPosition').textContent;
        
        const text = `📊 KEPUTUSAN PEPERIKSAAN\n\n` +
                     `Nama: ${studentName}\n` +
                     `Jumlah Mata Pelajaran: ${totalSubjects}\n` +
                     `Purata Gred: ${averageGrade}\n` +
                     `GPA: ${gpaScore}\n` +
                     `Kedudukan: ${rankPosition}\n\n` +
                     `Lihat slip penuh di: ${window.location.href}`;
        
        const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
        window.open(url, '_blank');
    }
    
    // Function untuk share via Email
    function shareViaEmail() {
        const studentName = document.getElementById('studentName').textContent;
        const totalSubjects = document.getElementById('totalSubjects').textContent;
        const averageGrade = document.getElementById('averageGrade').textContent;
        const gpaScore = document.getElementById('gpaScore').textContent;
        
        const subject = `Slip Keputusan Peperiksaan - ${studentName}`;
        const body = `Berikut adalah slip keputusan peperiksaan:\n\n` +
                     `NAMA: ${studentName}\n` +
                     `JUMLAH MATA PELAJARAN: ${totalSubjects}\n` +
                     `PURATA GRED: ${averageGrade}\n` +
                     `GPA: ${gpaScore}\n\n` +
                     `Lihat slip keputusan penuh di: ${window.location.href}\n\n` +
                     `*Slip ini sah untuk tujuan rujukan rasmi*`;
        
        const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        window.location.href = url;
    }
    
    // Auto-scroll untuk subject cards dengan animation
    document.addEventListener('DOMContentLoaded', function() {
        const subjectCards = document.querySelectorAll('.subject-card');
        subjectCards.forEach(card => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    });
    </script>
</body>
</html>
<?php
// Clear session data setelah dipaparkan (optional - boleh comment jika perlu simpan sementara)
// unset($_SESSION['result_data']);
?>