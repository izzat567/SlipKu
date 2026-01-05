<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemui - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/error404.css">
  
</head>
<body>
    <div class="error-container">
        <!-- Logo -->
        <a href="dashboard-admin.html" class="logo" style="justify-content: center;">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h1>SlipKu</h1>
                <p>Sistem Laporan Prestasi Pelajar</p>
            </div>
        </a>

        <!-- Error Icon -->
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>

        <!-- Error Code -->
        <div class="error-code">404</div>

        <!-- Error Title -->
        <h1 class="error-title">Halaman Tidak Ditemui</h1>

        <!-- Error Message -->
        <p class="error-message">
            Maaf, halaman yang anda cari tidak dapat ditemui. Halaman ini mungkin telah dialihkan, 
            dipadam, atau sementara tidak boleh diakses.
        </p>

        <!-- Error Details -->
        <div class="error-details">
            <h3>
                <i class="fas fa-info-circle"></i>
                Apa yang mungkin berlaku?
            </h3>
            <ul>
                <li>Alamat URL mungkin mempunyai kesilapan ejaan</li>
                <li>Halaman mungkin telah dialihkan ke lokasi baru</li>
                <li>Halaman mungkin telah dipadamkan</li>
                <li>Anda mungkin tidak mempunyai kebenaran untuk mengakses halaman ini</li>
                <li>Sistem mungkin mengalami masalah teknikal sementara</li>
            </ul>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Cari halaman atau kandungan..." id="searchInput">
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="dashboard-admin.html" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Halaman Sebelum
            </a>
            <a href="bantuan-admin.html" class="btn btn-info">
                <i class="fas fa-question-circle"></i>
                Dapatkan Bantuan
            </a>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <h3>Pautan Pantas</h3>
            <div class="links-grid">
                <a href="kelas-saya.html" class="link-item">
                    <i class="fas fa-users"></i>
                    Kelas Saya
                </a>
                <a href="pelajar-saya.html" class="link-item">
                    <i class="fas fa-user-graduate"></i>
                    Pelajar Saya
                </a>
                <a href="laporan-prestasi.html" class="link-item">
                    <i class="fas fa-chart-bar"></i>
                    Laporan Prestasi
                </a>
                <a href="jadual-ujian.html" class="link-item">
                    <i class="fas fa-calendar-alt"></i>
                    Jadual Ujian
                </a>
                <a href="tugasan.html" class="link-item">
                    <i class="fas fa-tasks"></i>
                    Tugasan
                </a>
                <a href="profil.html" class="link-item">
                    <i class="fas fa-user-cog"></i>
                    Profil Saya
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 SlipKu. Hak Cipta Terpelihara. | 
            <a href="#">Dasar Privasi</a> | 
            <a href="#">Terma Penggunaan</a> | 
            <a href="mailto:bantuan@slipku.edu.my">Hubungi Admin</a>
        </p>
    </footer>

    <script>
        // Function to handle search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    // Simulate search - in a real application, this would redirect to search results
                    alert(`Mencari: "${searchTerm}"\n\nFungsi carian akan diimplementasikan dalam versi akan datang.`);
                    this.value = '';
                }
            }
        });

        // Add click sound effect for buttons
        document.querySelectorAll('.btn, .link-item').forEach(button => {
            button.addEventListener('click', function() {
                // You could add a sound effect here if desired
                console.log('Navigating to:', this.href || 'previous page');
            });
        });

        // Add animation to error code
        const errorCode = document.querySelector('.error-code');
        errorCode.addEventListener('mouseover', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.3s ease';
        });

        errorCode.addEventListener('mouseout', function() {
            this.style.transform = 'scale(1)';
        });

        // Display current URL in console for debugging
        console.log('Halaman 404 diakses dari:', document.referrer || 'Direct access');
        console.log('URL semasa:', window.location.href);

        // Auto-focus on search input
        setTimeout(() => {
            document.getElementById('searchInput').focus();
        }, 1000);
    </script>
</body>
</html>