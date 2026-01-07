<?php
// session_start(); // Pastikan session dimulakan
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];

    $conn = getDBConnection();

    $stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Untuk plain text password
        if ($password === $user['password']) { 
            // Set session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['full_name'];
            $_SESSION['admin_role'] = $user['role'];
            $_SESSION['admin_username'] = $user['username'];

            // Update last login
            $update_stmt = $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
            $update_stmt->bind_param("i", $user['id']);
            $update_stmt->execute();

            // Redirect ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = 'Kata laluan salah.';
        }
    } else {
        $error = 'Username tidak ditemui.';
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SlipKu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
  
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>SlipKu Admin</h1>
                <p>Sistem Pengurusan Peperiksaan Sekolah</p>
            </div>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['message'])): ?>
                <div class="error-message" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-input" placeholder="Masukkan username" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" name="password" class="form-input" placeholder="Masukkan kata laluan" required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Log Masuk
                </button>
            </form>
            
            <div class="footer-text">
                <p>Sekolah Kebangsaan Rantau Panjang</p>
                <p>© 2024 SlipKu System</p>
            </div>
        </div>
    </div>
</body>
</html>