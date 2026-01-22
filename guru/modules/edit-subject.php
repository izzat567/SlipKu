<?php
session_start();
ob_start();
require_once __DIR__ . '/../../config/connect.php';

$error_message = '';
$success_message = '';
$subject = null;
$subject_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika tiada ID, redirect ke halaman utama
if ($subject_id <= 0) {
    header("Location: subjek-saya.php");
    exit();
}

// 1. AMBIL DATA SUBJEK UNTUK DIEDIT
$sql = "SELECT m.*, 
               COALESCE(sd.jenis, 'core') as jenis,
               COALESCE(sd.penerangan, '') as penerangan,
               COALESCE(sd.buku_teks, '') as buku_teks,
               COALESCE(sd.catatan, '') as catatan
        FROM matapelajaran m
        LEFT JOIN subject_details sd ON m.id = sd.id_matapelajaran
        WHERE m.id = ? AND m.status = 1";

$stmt = $database->prepare($sql);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $subject = $result->fetch_assoc();
} else {
    $error_message = "Subjek tidak ditemukan!";
    header("Location: subjek-saya.php?error=" . urlencode($error_message));
    exit();
}
$stmt->close();

// 2. PROSES UPDATE SUBJEK
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_subject') {
    $nama = trim($_POST['subject_name'] ?? '');
    $kod = trim($_POST['subject_code'] ?? '');
    $tahun = trim($_POST['subject_year'] ?? '');
    $jenis = trim($_POST['subject_type'] ?? 'core');
    $penerangan = trim($_POST['subject_description'] ?? '');
    $buku_teks = trim($_POST['subject_textbook'] ?? '');
    $catatan = trim($_POST['subject_notes'] ?? '');
    
    // Validate
    if (empty($nama) || empty($kod) || empty($tahun)) {
        $error_message = "Sila isi semua ruangan yang diperlukan!";
    } else {
        // Check if kod sudah digunakan oleh subjek lain
        $check_sql = "SELECT id FROM matapelajaran WHERE kod = ? AND id != ?";
        $check_stmt = $database->prepare($check_sql);
        $check_stmt->bind_param("si", $kod, $subject_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "Kod subjek '$kod' sudah digunakan oleh subjek lain!";
        } else {
            // Update matapelajaran table
            $update_sql = "UPDATE matapelajaran SET kod = ?, nama = ?, tahun = ? WHERE id = ?";
            $update_stmt = $database->prepare($update_sql);
            $update_stmt->bind_param("sssi", $kod, $nama, $tahun, $subject_id);
            
            if ($update_stmt->execute()) {
                // Check if subject_details exists
                $check_details = "SELECT id FROM subject_details WHERE id_matapelajaran = ?";
                $check_details_stmt = $database->prepare($check_details);
                $check_details_stmt->bind_param("i", $subject_id);
                $check_details_stmt->execute();
                $details_result = $check_details_stmt->get_result();
                
                if ($details_result->num_rows > 0) {
                    // Update existing record
                    $update_details = "UPDATE subject_details SET jenis = ?, penerangan = ?, buku_teks = ?, catatan = ? WHERE id_matapelajaran = ?";
                    $update_details_stmt = $database->prepare($update_details);
                    $update_details_stmt->bind_param("ssssi", $jenis, $penerangan, $buku_teks, $catatan, $subject_id);
                    $update_details_stmt->execute();
                    $update_details_stmt->close();
                } else {
                    // Insert new record
                    $insert_details = "INSERT INTO subject_details (id_matapelajaran, jenis, penerangan, buku_teks, catatan) VALUES (?, ?, ?, ?, ?)";
                    $insert_details_stmt = $database->prepare($insert_details);
                    $insert_details_stmt->bind_param("issss", $subject_id, $jenis, $penerangan, $buku_teks, $catatan);
                    $insert_details_stmt->execute();
                    $insert_details_stmt->close();
                }
                
                $check_details_stmt->close();
                $success_message = "Subjek berjaya dikemaskini!";
                
                // Update local subject data
                $subject['nama'] = $nama;
                $subject['kod'] = $kod;
                $subject['tahun'] = $tahun;
                $subject['jenis'] = $jenis;
                $subject['penerangan'] = $penerangan;
                $subject['buku_teks'] = $buku_teks;
                $subject['catatan'] = $catatan;
                
            } else {
                $error_message = "Gagal mengemaskini subjek: " . $database->error;
            }
            $update_stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subjek - SlipKu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: var(--white);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 20px 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 22px;
        }

        .logo-text h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 2px;
        }

        .logo-text p {
            font-size: 12px;
            color: var(--medium-gray);
            font-weight: 500;
        }

        .back-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: var(--white);
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
        }

        .back-btn:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            max-width: 800px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Page Header */
        .page-header {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--primary);
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .page-title-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .page-title h1 {
            font-size: 28px;
            font-weight: 800;
            color: var(--dark-gray);
        }

        .page-subtitle {
            color: var(--medium-gray);
            font-size: 16px;
            padding-left: 75px;
        }

        /* Form Container */
        .form-container {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        /* Alert Messages */
        .alert-message {
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-message.success {
            background: rgba(16, 185, 129, 0.1);
            border-color: var(--success);
            color: var(--success);
        }

        .alert-message.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: var(--danger);
            color: var(--danger);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .form-label.required:after {
            content: " *";
            color: var(--danger);
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: var(--transition);
            background: var(--white);
            color: var(--dark-gray);
            font-family: 'Poppins', sans-serif;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
            line-height: 1.5;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }

        /* Buttons */
        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--dark-gray);
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: var(--light-gray);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        /* Subject Info Box */
        .subject-info-box {
            background: var(--primary-light);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
            border: 2px solid var(--primary);
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .subject-code-badge {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .subject-info-text h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .subject-info-text p {
            color: var(--medium-gray);
            font-size: 14px;
        }

        /* Loading State */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }
            
            .main-content {
                padding: 0 15px;
                margin: 20px auto;
            }
            
            .page-header {
                padding: 20px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .page-subtitle {
                padding-left: 0;
            }
            
            .subject-info-box {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-container">
            <a href="dashboard.php" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Edit Subjek</p>
                </div>
            </a>
            
            <a href="subjek-saya.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Subjek Saya
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div>
                    <h1>Edit Subjek</h1>
                    <p class="page-subtitle">Kemaskini maklumat subjek <?php echo htmlspecialchars($subject['nama'] ?? ''); ?></p>
                </div>
            </div>
        </div>

        <!-- Subject Info Box -->
        <div class="subject-info-box">
            <div class="subject-code-badge">
                <?php echo htmlspecialchars($subject['kod'] ?? ''); ?>
            </div>
            <div class="subject-info-text">
                <h3><?php echo htmlspecialchars($subject['nama'] ?? ''); ?></h3>
                <p>Tahun <?php echo htmlspecialchars($subject['tahun'] ?? ''); ?> • <?php echo ucfirst($subject['jenis'] ?? 'core'); ?></p>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if ($success_message): ?>
            <div class="alert-message success" id="successMessage">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="alert-message error" id="errorMessage">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <div class="form-container">
            <form id="editSubjectForm" method="POST" action="">
                <input type="hidden" name="action" value="update_subject">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-book"></i> Nama Subjek
                        </label>
                        <input type="text" class="form-input" id="subjectName" name="subject_name" 
                               placeholder="Contoh: Matematik, Sains" required
                               value="<?php echo htmlspecialchars($subject['nama'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-barcode"></i> Kod Subjek
                        </label>
                        <input type="text" class="form-input" id="subjectCode" name="subject_code" 
                               placeholder="Contoh: MAT01, SNS01" required
                               value="<?php echo htmlspecialchars($subject['kod'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-tag"></i> Jenis Subjek
                        </label>
                        <select class="form-select" id="subjectType" name="subject_type" required>
                            <option value="">Pilih Jenis</option>
                            <option value="core" <?php echo ($subject['jenis'] ?? '') == 'core' ? 'selected' : ''; ?>>Teras</option>
                            <option value="elective" <?php echo ($subject['jenis'] ?? '') == 'elective' ? 'selected' : ''; ?>>Elektif</option>
                            <option value="additional" <?php echo ($subject['jenis'] ?? '') == 'additional' ? 'selected' : ''; ?>>Tambahan</option>
                            <option value="extracurricular" <?php echo ($subject['jenis'] ?? '') == 'extracurricular' ? 'selected' : ''; ?>>Kokurikulum</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label required">
                            <i class="fas fa-calendar"></i> Tahun
                        </label>
                        <select class="form-select" id="subjectYear" name="subject_year" required>
                            <option value="">Pilih Tahun</option>
                            <option value="1" <?php echo ($subject['tahun'] ?? '') == '1' ? 'selected' : ''; ?>>Tahun 1</option>
                            <option value="2" <?php echo ($subject['tahun'] ?? '') == '2' ? 'selected' : ''; ?>>Tahun 2</option>
                            <option value="3" <?php echo ($subject['tahun'] ?? '') == '3' ? 'selected' : ''; ?>>Tahun 3</option>
                            <option value="4" <?php echo ($subject['tahun'] ?? '') == '4' ? 'selected' : ''; ?>>Tahun 4</option>
                            <option value="5" <?php echo ($subject['tahun'] ?? '') == '5' ? 'selected' : ''; ?>>Tahun 5</option>
                            <option value="6" <?php echo ($subject['tahun'] ?? '') == '6' ? 'selected' : ''; ?>>Tahun 6</option>
                            <option value="1-6" <?php echo ($subject['tahun'] ?? '') == '1-6' ? 'selected' : ''; ?>>Tahun 1-6</option>
                            <option value="4-6" <?php echo ($subject['tahun'] ?? '') == '4-6' ? 'selected' : ''; ?>>Tahun 4-6</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i> Penerangan Subjek
                    </label>
                    <textarea class="form-textarea" id="subjectDescription" name="subject_description" 
                              placeholder="Penerangan ringkas mengenai subjek..." rows="4"><?php echo htmlspecialchars($subject['penerangan'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-book-open"></i> Buku Teks Utama
                        </label>
                        <input type="text" class="form-input" id="subjectTextbook" name="subject_textbook" 
                               placeholder="Nama buku teks"
                               value="<?php echo htmlspecialchars($subject['buku_teks'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sticky-note"></i> Catatan
                        </label>
                        <input type="text" class="form-input" id="subjectNotes" name="subject_notes" 
                               placeholder="Catatan tambahan"
                               value="<?php echo htmlspecialchars($subject['catatan'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="subjek-saya.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Kemaskini Subjek
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Section -->
        <div class="form-container" style="margin-top: 30px; border-left-color: var(--danger);">
            <h3 style="color: var(--danger); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-exclamation-triangle"></i> Zon Bahaya
            </h3>
            <p style="color: var(--medium-gray); margin-bottom: 20px;">
                <strong>Perhatian:</strong> Memadam subjek akan membuang semua data berkaitan termasuk markah dan laporan. 
                Tindakan ini tidak boleh dipulihkan.
            </p>
            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $subject_id; ?>, '<?php echo htmlspecialchars($subject['nama']); ?>')">
                <i class="fas fa-trash"></i> Padam Subjek Ini
            </button>
        </div>
    </main>

    <script>
        // Form validation
        document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
            const required = this.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--danger)';
                    field.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';
                    valid = false;
                } else {
                    field.style.borderColor = '';
                    field.style.boxShadow = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Sila isi semua ruangan yang diperlukan!');
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="loading"></span> Menyimpan...';
            submitBtn.disabled = true;
            
            // Allow form to submit normally
        });

        // Delete confirmation
        function confirmDelete(subjectId, subjectName) {
            if (confirm(`Adakah anda pasti mahu memadam subjek "${subjectName}"?\n\nTindakan ini akan:\n• Memadam subjek ini\n• Memadam semua data berkaitan\n• TIDAK BOLEH DIPULIHKAN`)) {
                // Show loading
                const deleteBtn = event.target;
                const originalHTML = deleteBtn.innerHTML;
                deleteBtn.innerHTML = '<span class="loading"></span> Memadam...';
                deleteBtn.disabled = true;
                
                // Send delete request
                fetch('delete-subject.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + subjectId + '&confirm=1'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Subjek berjaya dipadam!');
                        window.location.href = 'subjek-saya.php?success=' + encodeURIComponent('Subjek berjaya dipadam!');
                    } else {
                        alert('Gagal memadam: ' + data.error);
                        deleteBtn.innerHTML = originalHTML;
                        deleteBtn.disabled = false;
                    }
                })
                .catch(error => {
                    alert('Ralat: ' + error);
                    deleteBtn.innerHTML = originalHTML;
                    deleteBtn.disabled = false;
                });
            }
        }

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const messages = document.querySelectorAll('.alert-message');
            messages.forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>