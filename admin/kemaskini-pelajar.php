<?php 

include '../config/connect.php';

$id_pelajar = $_GET['id'];
$pelajar_sql = mysqli_query($database, "SELECT * FROM pelajar WHERE id = '$id_pelajar'");
$pelajar = mysqli_fetch_assoc($pelajar_sql);


?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pelajar - SlipKu</title>
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
                <h2>Kemaskini Pelajar</h2>
                <p>Mengemaskini maklumat pelajar  ke dalam sistem</p>
            </div>
        </div>

        <!-- Success Message (Hidden by default) -->
        <div class="success-message" id="successMessage">
            <div class="success-content">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-text">
                    <h4>Pelajar Berjaya Ditambah!</h4>
                    <p>Pelajar baru telah berjaya ditambahkan ke dalam sistem. Anda boleh lihat pelajar ini dalam senarai pelajar.</p>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <div class="form-title">
                <i class="fas fa-user-plus"></i>
                <span>Maklumat Peribadi Pelajar</span>
            </div>

            <form id="studentForm">

                <!-- Avatar Upload -->
                <!-- <div class="avatar-section">
                    <div class="avatar-preview" id="avatarPreview">
                        <span id="avatarInitials">AL</span>
                    </div>
                    <div class="avatar-upload">
                        <label for="avatarInput">
                            <i class="fas fa-camera"></i>
                            Ubah Gambar Profil
                        </label>
                        <input type="file" id="avatarInput" accept="image/*" onchange="previewAvatar(event)">
                        <p style="font-size: 12px; color: var(--medium-gray); margin-top: 8px;">
                            * Ukuran disyorkan: 300x300 px. Format: JPG, PNG atau GIF.
                        </p>
                    </div>
                </div> -->
                
                <!-- Row 1: ID dan Nama -->
                <div class="form-row">
                    <!-- <div class="form-group">
                        <label class="form-label" for="studentId">ID Pelajar <span class="required">*</span></label>
                        <input type="text" id="studentId" class="form-input" placeholder="Contoh: STU2023001" required>
                        <div class="error-message" id="idError">Sila masukkan ID pelajar</div>
                    </div> -->
                    <div class="form-group">
                        <label class="form-label" for="studentName">Nama Penuh Pelajar <span class="required">*</span></label>
                        <input name="nama" value="<?php echo $pelajar['nama'] ?>" type="text" id="studentName" class="form-input" placeholder="Contoh: Ahmad bin Ali" required>
                        <div class="error-message" id="nameError">Sila masukkan nama penuh pelajar</div>
                    </div>
                </div>

                <!-- Row 2: No KP dan Kelas -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="studentIc">No. Kad Pengenalan <span class="required">*</span></label>
                        <input name="no_kp" value="<?php echo $pelajar['no_kp']?>" type="text" id="studentIc" class="form-input" placeholder="Contoh: 080808-10-1234" required>
                        <div class="error-message" id="icError">Format: 000000-00-0000</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="studentClass">Kelas <span class="required">*</span></label>
                        <select id="studentClass" class="form-select" required>
                            <?php 

                                $kelas_sql = mysqli_query($database, "SELECT * FROM kelas ORDER BY tahun ASC");
                                $kelas = mysqli_fetch_all($kelas_sql, MYSQLI_ASSOC);
                                foreach($kelas as $kls){
                                    $selected = ($pelajar['kelas'] == $kls['nama']) ? 'selected' : '';
                                    echo '<option value="'.$kls['id'].'" '.$selected.'>'.$kls['nama'].'</option>';
                                }
                            ?>
                        </select>
                        <div class="error-message" id="classError">Sila pilih kelas</div>
                    </div>
                </div>

                <!-- Row 3: Jantina dan Status -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jantina <span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="gender" value="L" <?php if($pelajar['jantina'] == 'L') echo 'checked'; ?>>
                                Lelaki
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="gender" value="P" <?php if($pelajar['jantina'] == 'P') echo 'checked'; ?>>
                                Perempuan
                            </label>
                        </div>
                    </div>
                </div>

                 <!-- Form Actions -->
                 <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-redo"></i>
                        Kembali Ke Senarai
                    </button>
                    <button type="button" class="btn btn-primary" onclick="simpanPelajar()">
                        <i class="fas fa-save"></i>
                        Kemaskini Pelajar
                    </button>
                </div>
            </form>
        </div>
        </div> 
    </main>
</body>
</html>