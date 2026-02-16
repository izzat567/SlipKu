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
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-light: #eef2ff;
    --secondary: #7c3aed;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --black: #000000;
    --dark-gray: #1f2937;
    --medium-gray: #6b7280;
    --light-gray: #f9fafb;
    --white: #ffffff;
    --border-radius: 20px;
    --transition: all 0.3s ease;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: var(--dark-gray);
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    text-align: center;
}

.error-container {
    max-width: 800px;
    width: 100%;
    background: var(--white);
    border-radius: var(--border-radius);
    padding: 60px 40px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.error-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 6px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

.error-code {
    font-size: 120px;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    line-height: 1;
    margin-bottom: 10px;
    text-shadow: 0 4px 10px rgba(79, 70, 229, 0.1);
}

.error-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark-gray);
    margin-bottom: 15px;
}

.error-message {
    font-size: 18px;
    color: var(--medium-gray);
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.error-icon {
    font-size: 80px;
    color: var(--primary);
    margin-bottom: 30px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.error-details {
    background: var(--light-gray);
    border-radius: 12px;
    padding: 25px;
    margin: 30px 0;
    text-align: left;
    border-left: 4px solid var(--danger);
}

.error-details h3 {
    color: var(--danger);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
}

.error-details p {
    color: var(--medium-gray);
    margin-bottom: 15px;
    font-size: 15px;
}

.error-details ul {
    padding-left: 20px;
    margin-bottom: 15px;
}

.error-details li {
    margin-bottom: 8px;
    color: var(--medium-gray);
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 40px;
}

.btn {
    padding: 14px 30px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border: none;
    font-family: 'Poppins', sans-serif;
    white-space: nowrap;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: var(--white);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
}

.btn-secondary {
    background: var(--white);
    color: var(--dark-gray);
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    background: var(--light-gray);
    transform: translateY(-3px);
}

.btn-info {
    background: var(--info);
    color: white;
}

.btn-info:hover {
    background: #2563eb;
    transform: translateY(-3px);
}

.logo {
    display: flex;
    align-items: center;
    gap: 15px;
    text-decoration: none;
    margin-bottom: 40px;
}

.logo-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 24px;
}

.logo-text h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 2px;
}

.logo-text p {
    font-size: 14px;
    color: var(--medium-gray);
    font-weight: 500;
}

.search-box {
    max-width: 500px;
    margin: 30px auto;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 16px 20px 16px 50px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 16px;
    font-family: 'Poppins', sans-serif;
    transition: var(--transition);
    background: var(--white);
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--medium-gray);
    font-size: 18px;
}

.quick-links {
    margin-top: 50px;
    padding-top: 30px;
    border-top: 1px solid #e5e7eb;
}

.quick-links h3 {
    font-size: 18px;
    color: var(--dark-gray);
    margin-bottom: 20px;
}

.links-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.link-item {
    background: var(--light-gray);
    padding: 15px;
    border-radius: 10px;
    text-decoration: none;
    color: var(--dark-gray);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: center;
}

.link-item:hover {
    background: var(--primary-light);
    color: var(--primary);
    transform: translateY(-2px);
}

.link-item i {
    color: var(--primary);
}

footer {
    margin-top: 50px;
    color: var(--medium-gray);
    font-size: 14px;
    padding: 20px;
}

footer a {
    color: var(--primary);
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.error-container {
    animation: fadeIn 0.6s ease-out;
}

/* Responsive Design */
@media (max-width: 768px) {
    .error-code {
        font-size: 80px;
    }
    
    .error-title {
        font-size: 24px;
    }
    
    .error-message {
        font-size: 16px;
    }
    
    .error-icon {
        font-size: 60px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
    
    .error-container {
        padding: 40px 20px;
    }
    
    .links-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .error-code {
        font-size: 60px;
    }
    
    .error-title {
        font-size: 20px;
    }
    
    .logo-text h1 {
        font-size: 22px;
    }
    
    .links-grid {
        grid-template-columns: 1fr;
    }
}
    </style>
  
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