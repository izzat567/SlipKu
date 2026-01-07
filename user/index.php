<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SlipKu - Digital Exam Slip System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.12.11/build/spline-viewer.js"></script>
</head>
<body>

    <!-- Navigation Bar -->
    <nav id="mainNav">
        <div class="container">
            <div class="nav-container">
                <a href="#" class="logo">
                    <div class="logo-icon">
                        <div class="logo-icon-inner">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="logo-text">
                        Slip<span class="highlight">Ku</span>
                    </div>
                </a>

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="nav-links" id="navLinks">
                    <a href="#" class="active">UTAMA</a>
                    <a href="#features">TENTANG KAMI</a>
                    <a href="../admin/" class="btn-secondary" style="padding: 10px 20px;">ADMIN</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-wrapper">

                <!-- Left Content -->
                <div class="hero-content">
                    <span class="welcome-text">
                        <i class="fas fa-graduation-cap"></i> Selamat Datang Ke SlipKu
                    </span>

                    <h1>Sistem Papar <span class="highlight"><br> Slip Peperiksaan </span> Digital</h1>

                    <p class="hero-description">
                    Slipku ialah sebuah sistem paparan slip peperiksaan digital yang inovatif yang 
                    dibangunkan untuk memudahkan pelajar, 
                    ibu bapa dan guru menyemak prestasi akademik dengan cepat dan mudah.
                    </p>

                    <div class="cta-buttons">
                        <a href="form_student_gred.php" class="btn btn-primary">
                            <i class="fas fa-rocket"></i> Semak Keputusan
                        </a>
                        <a href="#learn-more" class="btn btn-secondary">
                            <i class="fas fa-play-circle"></i> Panduan SlipKu
                        </a>
                    </div>

                    <!-- Stats Section -->
                    <div class="stats">
                        <div class="stat-item">
                            <span class="stat-value">100%</span>
                            <span class="stat-label">Slip Digital</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">5K+</span>
                            <span class="stat-label">Pengguna Aktif</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">24/7</span>
                            <span class="stat-label">Akses Sistem</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Spline 3D Model -->
                <div class="model-container">
                    <div class="spline-container">
                        <spline-viewer 
                            url="https://prod.spline.design/htbgVfHo-4YGOAOH/scene.splinecode"
                            loading="lazy"
                            style="background: transparent !important;"
                            id="splineViewer"
                        ></spline-viewer>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="section-title">Tentang SlipKu</h2>

            <div class="features-grid">

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3>Slip Peperiksaan Digital</h3>
                    <p>
                    Pelajar boleh melihat slip keputusan peperiksaan secara lengkap merangkumi markah setiap mata pelajaran, gred, 
                    dan jumlah keseluruhan keputusan pada bila-bila masa melalui peranti masing-masing.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <h3>Pengurusan Data Berpusat</h3>
                    <p>
                    Guru dan pihak pentadbir boleh memuat naik, mengemas kini serta mengurus data peperiksaan pelajar 
                    dengan lebih sistematik dan efisien melalui papan pemuka yang selamat.
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Selamat & Dipercayai</h3>
                    <p>
                    SlipKu menggunakan perlindungan data yang selamat dan kawalan akses 
                    pengguna bagi memastikan semua rekod pelajar dirahsiakan dan terpelihara.
                    </p>
                </div>

            </div>
        </div>
    </section>

    
   

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">

                <div class="footer-column">
                    <h3>SlipKu</h3>
                    <p>
                    Ialah sistem paparan slip peperiksaan digital yang dibangunkan untuk memudahkan s
                    emakan keputusan pelajar secara dalam talian.
                    </p>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon tiktok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a href="#" class="social-icon maps">
                            <i class="fas fa-map-marker-alt"></i>
                        </a>
                    </div>

                </div>

                <div class="footer-column">
                    <h3>Hubungi Kami</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <strong>No Telefon Sekolah</strong><br>
                                +60 3-3271 0330
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <strong>Email Sekolah</strong><br>
                                BBA3010@moe.edu.my
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-school"></i>
                            <div>
                                <strong>Alamat Sekolah</strong><br>
                                Sekolah Kebangsaan Rantau Panjang (SK Rantau Panjang)
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="footer-bottom">
                <div class="copyright">
                © 2025 Slipku. Hakcipta terpelihara.
                </div>
            </div>
        </div>
    </footer>
 
    <script src="./js/home.js"></script>
</body>
</html>
