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
    <style>
        
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --black: #000000;
            --dark-gray: #1f2937;
            --medium-gray: #6b7280;
            --light-gray: #f9fafb;
            --white: #ffffff;
            --border-radius: 20px;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--white);
            color: var(--dark-gray);
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Navigation */
        nav {
            padding: 20px 0;
            background: var(--white);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 800;
            font-size: 32px;
            color: var(--dark-gray);
            text-decoration: none;
            z-index: 1001;
        }
        .logo-icon {
            position: relative;
            width: 50px;
            height: 50px;
        }
        .logo-icon-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: var(--white);
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
        }
        .logo-text .highlight { color: var(--primary); }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--medium-gray);
            font-weight: 500;
            font-size: 16px;
            padding: 8px 0;
            transition: var(--transition);
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }
        .nav-links a:hover::after, .nav-links a.active::after {
            width: 100%;
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: var(--primary);
            color: var(--white);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            font-size: 24px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(79,70,229,0.3);
            z-index: 1001;
            align-items: center;
            justify-content: center;
        }
        .mobile-menu-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        /* Mobile Nav Overlay - ditambah dalam HTML */
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .mobile-nav-overlay.active {
            display: block;
            opacity: 1;
        }
        .mobile-nav-content {
            position: absolute;
            top: 0;
            right: 0;
            width: 280px;
            height: 100%;
            background: var(--white);
            padding: 80px 30px 30px;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .mobile-nav-overlay.active .mobile-nav-content {
            transform: translateX(0);
        }
        .mobile-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            color: var(--primary);
            font-size: 24px;
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .mobile-close-btn:hover {
            background: var(--primary-light);
            transform: rotate(90deg);
        }
        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }
        .mobile-nav-links a {
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
            font-size: 18px;
            padding: 15px 0;
            border-bottom: 1px solid var(--light-gray);
            transition: var(--transition);
        }
        .mobile-nav-links a:last-child { border-bottom: none; }
        .mobile-nav-links a:hover, .mobile-nav-links a.active {
            color: var(--primary);
            transform: translateX(10px);
        }

        /* Hero, Features, Footer etc. (ringkas) – kekal sama seperti asal */
        .hero-section { padding: 80px 0 100px; }
        .hero-wrapper { display: flex; align-items: center; gap: 60px; }
        .hero-content { flex: 1; }
        .welcome-text { display: inline-flex; align-items: center; gap: 8px; color: var(--primary); font-weight: 600; background-color: var(--primary-light); padding: 10px 20px; border-radius: 30px; margin-bottom: 25px; }
        h1 { font-size: 56px; font-weight: 800; line-height: 1.1; margin-bottom: 24px; }
        .highlight { color: var(--primary); }
        .hero-description { font-size: 18px; color: var(--medium-gray); margin-bottom: 40px; max-width: 500px; }
        .cta-buttons { display: flex; gap: 20px; flex-wrap: wrap; }
        .btn { padding: 16px 32px; border-radius: var(--border-radius); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; position: relative; overflow: hidden; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), #7c3aed); color: var(--white); }
        .btn-secondary { background: var(--white); color: var(--primary); border: 2px solid var(--primary-light); }
        .model-container { flex: 1; display: flex; justify-content: center; }
        .spline-container { width: 100%; max-width: 550px; height: 550px; background: transparent; border-radius: var(--border-radius); overflow: hidden; border: 2px solid transparent; background: linear-gradient(var(--white), var(--white)) padding-box, linear-gradient(135deg, var(--primary), #7c3aed) border-box; }
        .stats { display: flex; gap: 40px; margin-top: 60px; flex-wrap: wrap; }
        .stat-item { display: flex; flex-direction: column; padding: 20px; border-radius: var(--border-radius); background: var(--white); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .stat-value { font-size: 42px; font-weight: 800; background: linear-gradient(135deg, var(--primary), #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-label { font-size: 14px; color: var(--medium-gray); margin-top: 8px; }

        /* Features Section */
        .features-section { padding: 80px 0; background: var(--light-gray); }
        .section-title { text-align: center; font-size: 36px; font-weight: 700; margin-bottom: 60px; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .feature-card { background: var(--white); padding: 40px 30px; border-radius: var(--border-radius); text-align: center; transition: var(--transition); }
        .feature-icon { width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-light), var(--white)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; color: var(--primary); font-size: 32px; }

        /* Footer */
        footer { background-color: var(--black); color: var(--white); padding: 60px 0 30px; margin-top: auto; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; margin-bottom: 40px; }
        .footer-column h3 { font-size: 20px; font-weight: 700; margin-bottom: 20px; color: var(--white); padding-bottom: 10px; border-bottom: 3px solid var(--primary); }
        .social-icons { display: flex; gap: 16px; margin-top: 20px; }
        .social-icon { width: 45px; height: 45px; background: #333; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; transition: var(--transition); }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid #333; color: #888; }

        /* Responsive */
        @media (max-width: 992px) {
            .mobile-menu-btn { display: flex; }
            .nav-links { display: none; }
            .hero-wrapper { flex-direction: column; text-align: center; }
            .hero-description { margin: 0 auto 40px; }
            .cta-buttons { justify-content: center; }
            .footer-content { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            h1 { font-size: 36px; }
            .spline-container { height: 400px; }
            .stats { justify-content: center; }
            .footer-content { grid-template-columns: 1fr; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
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
                    <a href="pilihan-admin.php" class="btn-secondary" style="padding: 10px 20px;">ADMIN</a>
                </div>
            </div>

            <!-- Mobile Navigation Overlay (ditambah) -->
            <div class="mobile-nav-overlay" id="mobileNavOverlay">
                <div class="mobile-nav-content">
                    <button class="mobile-close-btn" id="mobileCloseBtn">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="mobile-nav-links">
                        <a href="#" class="active">UTAMA</a>
                        <a href="#features">TENTANG KAMI</a>
                        <a href="pilihan-admin.php">ADMIN</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (sama seperti asal) -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-wrapper">
                <div class="hero-content">
                    <span class="welcome-text"><i class="fas fa-graduation-cap"></i> Selamat Datang Ke SlipKu</span>
                    <h1>Sistem Papar <span class="highlight"><br> Slip Peperiksaan </span> Digital</h1>
                    <p class="hero-description">Slipku ialah sebuah sistem paparan slip peperiksaan digital yang inovatif yang dibangunkan untuk memudahkan pelajar, ibu bapa dan guru menyemak prestasi akademik dengan cepat dan mudah.</p>
                    <div class="cta-buttons">
                        <a href="form_student_gred.php" class="btn btn-primary"><i class="fas fa-rocket"></i> Semak Keputusan</a>
                        <a href="error404.php" class="btn btn-secondary"><i class="fas fa-play-circle"></i> Panduan SlipKu</a>
                    </div>
                    <div class="stats">
                        <div class="stat-item"><span class="stat-value">100%</span><span class="stat-label">Slip Digital</span></div>
                        <div class="stat-item"><span class="stat-value">5K+</span><span class="stat-label">Pengguna Aktif</span></div>
                        <div class="stat-item"><span class="stat-value">24/7</span><span class="stat-label">Akses Sistem</span></div>
                    </div>
                </div>
                <div class="model-container">
                    <div class="spline-container">
                        <!-- Spline Viewer (biarkan sama) -->
                        <script type="module" src="https://unpkg.com/@splinetool/viewer@1.12.11/build/spline-viewer.js"></script>
                        <spline-viewer url="https://prod.spline.design/htbgVfHo-4YGOAOH/scene.splinecode" loading="lazy" style="background: transparent !important;"></spline-viewer>
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
                    <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                    <h3>Slip Peperiksaan Digital</h3>
                    <p>Pelajar boleh melihat slip keputusan peperiksaan secara lengkap merangkumi markah setiap mata pelajaran, gred, dan jumlah keseluruhan keputusan pada bila-bila masa melalui peranti masing-masing.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-database"></i></div>
                    <h3>Pengurusan Data Berpusat</h3>
                    <p>Guru dan pihak pentadbir boleh memuat naik, mengemas kini serta mengurus data peperiksaan pelajar dengan lebih sistematik dan efisien melalui papan pemuka yang selamat.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lock"></i></div>
                    <h3>Selamat & Dipercayai</h3>
                    <p>SlipKu menggunakan perlindungan data yang selamat dan kawalan akses pengguna bagi memastikan semua rekod pelajar dirahsiakan dan terpelihara.</p>
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
                    <p>Ialah sistem paparan slip peperiksaan digital yang dibangunkan untuk memudahkan semakan keputusan pelajar secara dalam talian.</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon tiktok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="social-icon maps"><i class="fas fa-map-marker-alt"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Hubungi Kami</h3>
                    <div class="contact-info">
                        <div class="contact-item"><i class="fas fa-phone"></i><div><strong>No Telefon Sekolah</strong><br>+60 3-3271 0330</div></div>
                        <div class="contact-item"><i class="fas fa-envelope"></i><div><strong>Email Sekolah</strong><br>BBA3010@moe.edu.my</div></div>
                        <div class="contact-item"><i class="fas fa-school"></i><div><strong>Alamat Sekolah</strong><br>Sekolah Kebangsaan Rantau Panjang (SK Rantau Panjang)</div></div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="copyright">© 2025 Slipku. Hakcipta terpelihara.</div>
            </div>
        </div>
    </footer>

    <!-- JavaScript untuk Mobile Menu -->
    <script>
        (function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            const body = document.body;

            function openMobileMenu() {
                mobileNavOverlay.classList.add('active');
                body.style.overflow = 'hidden'; // Elak scroll latar belakang
            }

            function closeMobileMenu() {
                mobileNavOverlay.classList.remove('active');
                body.style.overflow = '';
            }

            mobileMenuBtn.addEventListener('click', openMobileMenu);
            mobileCloseBtn.addEventListener('click', closeMobileMenu);

            // Tutup menu jika klik pada latar belakang overlay (bukan content)
            mobileNavOverlay.addEventListener('click', function(e) {
                if (e.target === mobileNavOverlay) {
                    closeMobileMenu();
                }
            });

            // Tutup menu jika pautan dalam mobile diklik (untuk navigasi)
            document.querySelectorAll('.mobile-nav-links a').forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });

            // Optional: update active class based on current page (boleh ditambah kemudian)
        })();
    </script>
</body>
</html>