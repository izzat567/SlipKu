<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peranan - SlipKu</title>
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

        .error-icon {
            font-size: 80px;
            color: var(--primary);
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
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

        /* Role Selection */
        .role-selection {
            margin: 30px 0;
        }

        .role-selection h3 {
            font-size: 20px;
            color: var(--dark-gray);
            margin-bottom: 30px;
            font-weight: 600;
        }

        .role-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .role-card {
            background: var(--light-gray);
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            transition: var(--transition);
            border: 2px solid transparent;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .role-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 20px 30px rgba(79, 70, 229, 0.1);
        }

        .role-card i {
            font-size: 48px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 20px;
        }

        .role-card h4 {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .role-card p {
            color: var(--medium-gray);
            font-size: 14px;
            margin-bottom: 25px;
        }

        .btn-role {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            border-radius: 30px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: var(--transition);
            box-shadow: 0 8px 15px rgba(79, 70, 229, 0.2);
            border: none;
            cursor: pointer;
        }

        .btn-role:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(79, 70, 229, 0.3);
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

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            margin-bottom: 30px;
            justify-content: center;
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
            .error-title {
                font-size: 24px;
            }

            .error-message {
                font-size: 16px;
            }

            .error-icon {
                font-size: 60px;
            }

            .role-grid {
                grid-template-columns: 1fr;
                gap: 20px;
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
        }

        @media (max-width: 480px) {
            .logo-text h1 {
                font-size: 22px;
            }

            .role-card {
                padding: 20px;
            }

            .role-card h4 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <!-- Logo -->
        <a href="#" class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h1>SlipKu</h1>
                <p>Sistem Laporan Prestasi Pelajar</p>
            </div>
        </a>

        <!-- Ikon Peranan -->
        <div class="error-icon">
            <i class="fas fa-users-cog"></i>
        </div>

        <!-- Tajuk -->
        <h1 class="error-title">Pilih Peranan Anda</h1>

        <!-- Mesej -->
        <p class="error-message">
            Sila pilih peranan yang sesuai untuk mengakses sistem SlipKu. 
            Setiap peranan mempunyai kebenaran dan fungsi yang berbeza.
        </p>

        <!-- Pilihan Peranan (Hanya Super Admin dan Guru) -->
        <div class="role-selection">
            <div class="role-grid">
                <!-- Super Admin -->
                <div class="role-card">
                    <i class="fas fa-user-shield"></i>
                    <h4>Super Admin</h4>
                    <p>Akses penuh ke seluruh sistem, pengurusan pengguna dan tetapan utama.</p>
                    <a href="../admin/index.php" class="btn-role">Masuk sebagai Super Admin</a>
                </div>
                <!-- Guru -->
                <div class="role-card">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h4>Guru</h4>
                    <p>Urus kelas, rekod pelajar, masukkan markah dan cetak slip.</p>
                    <a href="../guru/login-guru.php" class="btn-role">Masuk sebagai Guru</a>
                </div>
            </div>
        </div>

        <!-- Butang Tindakan -->
        <div class="action-buttons">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i>
                Kembali ke Laman Utama
            </a>
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

    <!-- <script>
        // Fungsi untuk menambah kesan klik pada kad peranan
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Jika yang diklik adalah butang di dalam kad, jangan ganggu
                if (e.target.classList.contains('btn-role')) return;
                
                // Cari butang dalam kad dan simulate klik
                const btn = this.querySelector('.btn-role');
                if (btn) {
                    btn.click();
                }
            });
        });

        // Log untuk debugging
        console.log('Halaman pilihan peranan (Super Admin & Guru) dimuatkan.');

        // Contoh fungsi untuk navigasi (boleh diganti dengan link sebenar)
        document.querySelectorAll('.btn-role, .btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // Buang untuk tujuan demo
                const role = this.innerText.trim();
                if (role.includes('Super Admin')) {
                    alert('Navigasi ke halaman Super Admin (contoh)');
                } else if (role.includes('Guru')) {
                    alert('Navigasi ke halaman Guru (contoh)');
                } else if (role.includes('Kembali')) {
                    alert('Kembali ke laman utama (contoh)');
                } else if (role.includes('Bantuan')) {
                    alert('Halaman bantuan (contoh)');
                }
            });
        });
    </script> -->
</body>
</html>