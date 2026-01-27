<?php
session_start();

// Include database functions
require_once __DIR__ . '/includes/db_functions.php';

// Redirect if already logged in
if (isset($_SESSION['guru_id'])) {
    header('Location: dashboard-guru.php');
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($email) || empty($password)) {
        $error_message = 'Sila isi semua ruangan yang diperlukan.';
    } else {
        // Check credentials
        $guru = authenticateGuru($email, $password);
        
        if ($guru) {
            // Set session
            $_SESSION['guru_id'] = $guru['id'];
            $_SESSION['guru_nama'] = $guru['nama'];
            $_SESSION['guru_email'] = $guru['email'];
            $_SESSION['guru_role'] = $guru['role'];
            
            // Redirect to dashboard
            header('Location: dashboard-guru.php');
            exit();
        } else {
            $error_message = 'Email atau kata laluan tidak sah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk Guru - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --danger: #ef4444;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
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
            font-size: 13px;
            color: var(--medium-gray);
            font-weight: 500;
        }

        .login-header h2 {
            font-size: 22px;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .login-header p {
            color: var(--medium-gray);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            background: var(--white);
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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

        .alert {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: var(--medium-gray);
            font-size: 13px;
        }

        .demo-credentials {
            background: var(--light-gray);
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            font-size: 13px;
            text-align: center;
        }

        .demo-credentials h4 {
            color: var(--primary);
            margin-bottom: 8px;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 25px;
            }
            
            .logo {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .logo-text h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="logo-text">
                    <h1>SlipKu</h1>
                    <p>Sistem Pengurusan Sekolah</p>
                </div>
            </div>
            <h2>Log Masuk Guru</h2>
            <p>Akses sistem pengurusan akademik sekolah</p>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <div class="demo-credentials">
            <h4><i class="fas fa-info-circle"></i> CREDENSI DEMO</h4>
            <p>Email: guru@demo.com</p>
            <p>Kata Laluan: demo123</p>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" class="form-input" name="email" required 
                       placeholder="cth: guru@sekolah.edu.my" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Laluan</label>
                <input type="password" class="form-input" name="password" required 
                       placeholder="Masukkan kata laluan anda">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Log Masuk
                </button>
            </div>
        </form>

        <div class="login-footer">
            <p>&copy; <?= date('Y') ?> SlipKu. Hak Cipta Terpelihara.</p>
            <p style="margin-top: 5px; font-size: 12px;">
                <i class="fas fa-shield-alt"></i> Sistem dilindungi dengan enkripsi SSL
            </p>
        </div>
    </div>
</body>
</html>