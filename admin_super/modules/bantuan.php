<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bantuan.css">
   
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
   <?php include './../includes/header.php'; ?> 

    <!-- include side bar -->
    <?php include './../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <h2>Pusat Bantuan SlipKu ❓</h2>
                <p>Dapatkan bantuan dan jawapan untuk semua soalan anda</p>
            </div>
            <div class="page-actions">
                <button class="btn btn-secondary" onclick="muatSemulaHalaman()">
                    <i class="fas fa-sync-alt"></i>
                    Muat Semula
                </button>
                <button class="btn btn-primary" onclick="hubungiSokongan()">
                    <i class="fas fa-headset"></i>
                    Hubungi Sokongan
                </button>
            </div>
        </div>

        <!-- Help Sections -->
        <div class="help-sections">
            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Panduan Pengguna</h3>
                        <p>Panduan lengkap untuk sistem SlipKu</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('pengenalan')">Pengenalan kepada SlipKu</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('dashboard')">Panduan Papan Pemuka</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('pelajar')">Pengurusan Pelajar</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('markah')">Tambah & Kemaskini Markah</a></li>
                    <li><i class="fas fa-check-circle"></i> <a href="#" onclick="bukaPanduan('jadual')">Jadual Ujian & Peperiksaan</a></li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Video Tutorial</h3>
                        <p>Panduan visual langkah demi langkah</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('pengenalan')">Pengenalan Sistem SlipKu</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('import-data')">Import Data Pelajar</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('jana-laporan')">Menjana Laporan Prestasi</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('urus-ujian')">Mengurus Jadual Ujian</a></li>
                    <li><i class="fas fa-play-circle"></i> <a href="#" onclick="tontonVideo('analisis')">Analisis Prestasi Pelajar</a></li>
                </ul>
            </div>

            <div class="help-card">
                <div class="help-card-header">
                    <div class="help-card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="help-card-title">
                        <h3>Alatan & Sumber</h3>
                        <p>Sumber berguna untuk pentadbir</p>
                    </div>
                </div>
                <ul class="help-card-list">
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('template-import')">Template Import Data</a></li>
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('manual-pengguna')">Manual Pengguna (PDF)</a></li>
                    <li><i class="fas fa-download"></i> <a href="#" onclick="muatTurun('template-laporan')">Template Laporan</a></li>
                    <li><i class="fas fa-calculator"></i> <a href="#" onclick="bukaKalkulator()">Kalkulator Gred & Purata</a></li>
                    <li><i class="fas fa-calendar-alt"></i> <a href="#" onclick="bukaKalendarAkademik()">Kalendar Akademik 2023/2024</a></li>
                </ul>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="section-header">
                <h3>Soalan Lazim (FAQ)</h3>
                <div class="faq-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="faqSearch" placeholder="Cari soalan..." onkeyup="cariFAQ()">
                </div>
            </div>
            
            <div class="faq-container" id="faqContainer">
                <!-- FAQ items will be loaded here -->
            </div>
        </div>

        <!-- Video Tutorials Section -->
        <div class="videos-section">
            <div class="section-header">
                <h3>Video Tutorial Terkini</h3>
                <button class="btn btn-secondary" onclick="lihatSemuaVideo()">
                    <i class="fas fa-eye"></i>
                    Lihat Semua
                </button>
            </div>
            
            <div class="videos-grid" id="videosGrid">
                <!-- Video cards will be loaded here -->
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3 style="color: white; font-size: 24px; margin-bottom: 10px;">Hubungi Kami</h3>
            <p style="opacity: 0.9; margin-bottom: 20px;">Kami sentiasa sedia membantu anda. Hubungi kami melalui mana-mana saluran berikut:</p>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Hotline Sokongan</h4>
                        <p>03-1234 5678</p>
                        <p>Isnin - Jumaat: 9:00 pagi - 6:00 petang</p>
                        <a href="tel:0312345678" class="contact-link">
                            <i class="fas fa-phone-alt"></i> Hubungi Sekarang
                        </a>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Emel Sokongan</h4>
                        <p>sokongan@slipku.edu.my</p>
                        <p>Masa tindak balas: Dalam 24 jam</p>
                        <a href="mailto:sokongan@slipku.edu.my" class="contact-link">
                            <i class="fas fa-envelope"></i> Hantar Emel
                        </a>
                    </div>
                </div>
                
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="contact-info">
                        <h4>Live Chat</h4>
                        <p>Chat langsung dengan ejen kami</p>
                        <p>Tersedia: 9:00 pagi - 5:00 petang</p>
                        <a href="#" class="contact-link" onclick="bukaLiveChat()">
                            <i class="fas fa-comment-dots"></i> Mulakan Chat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <a href="#" class="quick-link" onclick="laporkanMasalah()">
                <i class="fas fa-bug"></i>
                Laporkan Masalah
            </a>
            <a href="#" class="quick-link" onclick="cadangkanFungsi()">
                <i class="fas fa-lightbulb"></i>
                Cadangkan Fungsi Baru
            </a>
            <a href="#" class="quick-link" onclick="semakStatusSistem()">
                <i class="fas fa-server"></i>
                Semak Status Sistem
            </a>
            <a href="#" class="quick-link" onclick="aksesForum()">
                <i class="fas fa-users"></i>
                Akses Forum Komuniti
            </a>
            <a href="#" class="quick-link" onclick="aksesKb()">
                <i class="fas fa-database"></i>
                Pangkalan Pengetahuan
            </a>
        </div>
    </main>

    <script>
        // DOM Elements
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mainContent = document.getElementById('mainContent');
        const faqContainer = document.getElementById('faqContainer');
        const videosGrid = document.getElementById('videosGrid');

        // Sample FAQ Data
        const faqData = [
            {
                question: "Bagaimana cara menambah pelajar baru ke dalam sistem?",
                answer: "Untuk menambah pelajar baru, pergi ke halaman 'Pengurusan Pelajar' dan klik butang 'Tambah Pelajar'. Isi borang dengan maklumat pelajar yang diperlukan. Anda juga boleh mengimport data pelajar secara pukal menggunakan template Excel yang disediakan."
            },
            {
                question: "Bagaimana sistem mengira gred dan purata pelajar?",
                answer: "Sistem SlipKu menggunakan formula standard KPM untuk mengira gred. Anda boleh tetapkan skala gred anda sendiri melalui Tetapan Sistem. Purata dikira berdasarkan berat markah setiap peperiksaan yang ditetapkan."
            },
            {
                question: "Bolehkah saya jadualkan ujian untuk beberapa kelas sekaligus?",
                answer: "Ya, anda boleh. Di halaman 'Jadual Ujian', pilih 'Tambah Ujian' dan pilih semua kelas yang terlibat. Sistem akan menjana jadual untuk setiap kelas secara automatik."
            },
            {
                question: "Bagaimana cara mencetak slip keputusan peperiksaan?",
                answer: "Pergi ke halaman 'Hasilkan Laporan', pilih kelas dan peperiksaan, kemudian klik 'Jana Slip'. Sistem akan menghasilkan slip keputusan dalam format PDF yang boleh dicetak atau dimuat turun."
            },
            {
                question: "Adakah data saya selamat di dalam sistem SlipKu?",
                answer: "Ya, keselamatan data adalah keutamaan kami. Sistem menggunakan enkripsi SSL, sandaran data harian, dan akses terkawal berdasarkan peranan pengguna. Hanya pengguna yang dibenarkan sahaja boleh mengakses data."
            },
            {
                question: "Bagaimana jika saya terlupa kata laluan?",
                answer: "Di halaman log masuk, klik 'Lupa Kata Laluan'. Sistem akan menghantar pautan reset kata laluan ke emel anda yang didaftarkan. Jika masalah berterusan, hubungi sokongan teknikal."
            },
            {
                question: "Bolehkah saya mengubah format laporan?",
                answer: "Ya, anda boleh pilih antara beberapa template laporan yang tersedia. Untuk penyesuaian lanjut, hubungi pasukan sokongan untuk bantuan."
            },
            {
                question: "Berapa lama data peperiksaan disimpan dalam sistem?",
                answer: "Data peperiksaan disimpan selama 5 tahun secara automatik. Anda boleh memuat turun arkib data untuk simpanan luar jika diperlukan."
            }
        ];

        // Sample Video Data
        const videoData = [
            {
                title: "Pengenalan kepada SlipKu",
                description: "Ketahui ciri-ciri utama sistem pengurusan peperiksaan digital kami",
                duration: "4:32",
                id: "pengenalan"
            },
            {
                title: "Import Data Pelajar Secara Pukal",
                description: "Panduan lengkap untuk mengimport data pelajar menggunakan Excel",
                duration: "6:15",
                id: "import"
            },
            {
                title: "Menjana Laporan Prestasi Kelas",
                description: "Cara menghasilkan laporan analisis prestasi untuk keseluruhan kelas",
                duration: "7:48",
                id: "laporan"
            },
            {
                title: "Mengurus Jadual Ujian & Peperiksaan",
                description: "Panduan menyusun jadual peperiksaan untuk semua kelas",
                duration: "5:23",
                id: "jadual"
            }
        ];

        // Initialize page
        function initializePage() {
            // Load FAQ
            loadFAQ();
            
            // Load videos
            loadVideos();
            
            // Set up event listeners
            setupEventListeners();
        }

        // Load FAQ items
        function loadFAQ() {
            faqContainer.innerHTML = faqData.map((faq, index) => `
                <div class="faq-item" id="faq-${index}">
                    <div class="faq-question" onclick="toggleFAQ(${index})">
                        <span>${faq.question}</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>${faq.answer}</p>
                    </div>
                </div>
            `).join('');
        }

        // Load video cards
        function loadVideos() {
            videosGrid.innerHTML = videoData.map(video => `
                <div class="video-card">
                    <div class="video-thumbnail">
                        <i class="fas fa-play-circle"></i>
                        <div class="video-play-btn" onclick="playVideo('${video.id}')">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="video-info">
                        <h4>${video.title}</h4>
                        <p>${video.description}</p>
                        <div class="video-duration">
                            <i class="fas fa-clock"></i>
                            ${video.duration}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Toggle FAQ item
        function toggleFAQ(index) {
            const faqItem = document.getElementById(`faq-${index}`);
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it was closed
            if (!isActive) {
                faqItem.classList.add('active');
            }
        }

        // Search FAQ
        function cariFAQ() {
            const searchTerm = document.getElementById('faqSearch').value.toLowerCase();
            
            faqData.forEach((faq, index) => {
                const faqItem = document.getElementById(`faq-${index}`);
                const question = faq.question.toLowerCase();
                const answer = faq.answer.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    faqItem.style.display = 'block';
                } else {
                    faqItem.style.display = 'none';
                }
            });
        }

        // Open user guide
        function bukaPanduan(guide) {
            alert(`Membuka panduan: ${guide}\n\nDalam versi sebenar, ini akan membuka PDF atau halaman panduan yang berkaitan.`);
            // In a real app, this would open the corresponding guide
        }

        // Watch video
        function tontonVideo(videoId) {
            alert(`Memainkan video tutorial: ${videoId}\n\nDalam versi sebenar, ini akan memainkan video yang dipilih.`);
            // In a real app, this would open a video player
        }

        // Download resource
        function muatTurun(resource) {
            alert(`Memuat turun: ${resource}\n\nDalam versi sebenar, ini akan memuat turun fail yang dipilih.`);
            // In a real app, this would download the resource
        }

        // Open calculator
        function bukaKalkulator() {
            alert("Membuka kalkulator gred dan purata...");
            // In a real app, this would open a calculator tool
        }

        // Open academic calendar
        function bukaKalendarAkademik() {
            alert("Membuka kalendar akademik 2023/2024...");
            // In a real app, this would open the academic calendar
        }

        // Play video
        function playVideo(videoId) {
            alert(`Memainkan video: ${videoId}\n\nDalam aplikasi sebenar, video akan dipapar dalam pemain video.`);
            // In a real app, this would play the selected video
        }

        // View all videos
        function lihatSemuaVideo() {
            alert("Membuka semua video tutorial...");
            // In a real app, this would navigate to all videos page
        }

        // Contact support
        function hubungiSokongan() {
            alert("Menghubungi pasukan sokongan...\n\nSila pilih saluran:\n1. Telefon: 03-1234 5678\n2. Emel: sokongan@slipku.edu.my\n3. Live Chat (9am-5pm)");
            // In a real app, this would open contact options
        }

        // Open live chat
        function bukaLiveChat() {
            alert("Membuka live chat dengan ejen sokongan...\n\nSila tunggu sebentar untuk dihubungkan.");
            // In a real app, this would open a chat window
        }

        // Report problem
        function laporkanMasalah() {
            const problem = prompt("Sila terangkan masalah yang dihadapi:");
            if (problem) {
                alert(`Terima kasih! Laporan anda telah dihantar:\n\n"${problem}"\n\nNo. Rujukan: SR-${Date.now().toString().slice(-6)}`);
            }
        }

        // Suggest feature
        function cadangkanFungsi() {
            const suggestion = prompt("Cadangkan fungsi baru untuk SlipKu:");
            if (suggestion) {
                alert(`Terima kasih atas cadangan anda!\n\n"${suggestion}"\n\nCadangan anda telah direkodkan.`);
            }
        }

        // Check system status
        function semakStatusSistem() {
            alert("Status Sistem SlipKu:\n\n✅ Semua sistem beroperasi normal\n✅ Server responsif\n✅ Tiada gangguan dilaporkan\n✅ Sandaran terakhir: 2 jam lalu\n\nSemua perkhidmatan berjalan lancar.");
        }

        // Access forum
        function aksesForum() {
            alert("Mengakses forum komuniti SlipKu...\n\nForum komuniti membolehkan anda berkongsi pengalaman dengan pengguna lain dan mendapatkan tips dari pakar.");
        }

        // Access knowledge base
        function aksesKb() {
            alert("Mengakses pangkalan pengetahuan SlipKu...\n\nPangkalan pengetahuan mengandungi artikel teknikal, panduan penyelesaian masalah, dan dokumentasi lengkap.");
        }

        // Reload page
        function muatSemulaHalaman() {
            location.reload();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Toggle sidebar
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', closeSidebar);
            });
            
            // Add window resize listener
            window.addEventListener('resize', function() {
                closeSidebar();
            });
        }

        // Toggle Sidebar
        function toggleSidebar() {
            sidebar.classList.toggle('sidebar-active');
            sidebarOverlay.classList.toggle('active');
            mainContent.classList.toggle('full-width');
            document.body.style.overflow = sidebar.classList.contains('sidebar-active') ? 'hidden' : '';
        }

        // Close Sidebar on Mobile
        function closeSidebar() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('sidebar-active');
                sidebarOverlay.classList.remove('active');
                mainContent.classList.remove('full-width');
                document.body.style.overflow = '';
            }
        }

        // Initialize page when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    </script>
</body>
</html>==========