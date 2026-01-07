<?php


function loginUser($conn, $type, $email, $password) {
    // Escape input to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    
    if ($type == 'admin') {
        // Query for admin user
        $sql = "SELECT `id`, `email`, `nama`, `katalaluan`, `status` 
                FROM `admins` 
                WHERE `email` = '$email' AND `status` = '1'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (assuming katalaluan is hashed)
            if (password_verify($password, $user['katalaluan'])) {
                // Set session variables
                $_SESSION['user_type'] = 'admin';
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['logged_in'] = true;
                
                // Set cookie (30 days expiry)
                $cookie_data = json_encode([
                    'type' => 'admin',
                    'id' => $user['id'],
                    'email' => $user['email']
                ]);
                setcookie('user_login', base64_encode($cookie_data), time() + (86400 * 30), "/");
                
                return ['success' => true, 'message' => 'Admin login successful'];
            }
            else{
                return ['success' => false, 'message' => 'Wrong password'];
            }
        }
        else{
            return ['success' => false, 'message' => 'User not found'];

        }
    } 
    elseif ($type == 'guru') {
        // Query for guru user
        $sql = "SELECT `id_guru`, `nama`, `email`, `password`, `status` 
                FROM `guru` 
                WHERE `email` = '$email' AND `status` = 'active'";
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (assuming password is hashed)
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_type'] = 'guru';
                $_SESSION['user_id'] = $user['id_guru'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['nama'];
                $_SESSION['logged_in'] = true;
                
                // Set cookie (30 days expiry)
                $cookie_data = json_encode([
                    'type' => 'guru',
                    'id' => $user['id_guru'],
                    'email' => $user['email']
                ]);
                setcookie('user_login', base64_encode($cookie_data), time() + (86400 * 30), "/");
                
                return ['success' => true, 'message' => 'Guru login successful'];
            }
        }
    }
    
    // If authentication fails
    return ['success' => false, 'message' => 'Invalid credentials or inactive account'];
}


function isLoggedIn() {
    session_start();
    
    // Check session first
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        return true;
    }
    
    // Check cookie if session is not set
    if (isset($_COOKIE['user_login'])) {
        $cookie_data = json_decode(base64_decode($_COOKIE['user_login']), true);
        
        if ($cookie_data) {
            // Restore session from cookie
            $_SESSION['user_type'] = $cookie_data['type'];
            $_SESSION['user_id'] = $cookie_data['id'];
            $_SESSION['user_email'] = $cookie_data['email'];
            $_SESSION['logged_in'] = true;
            
            // Fetch additional user info from database if needed
            return true;
        }
    }
    
    return false;
}

// Function to logout
function logout() {
    session_start();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Delete session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Delete login cookie
    setcookie('user_login', '', time() - 3600, "/");
    
    // Redirect to login page
    header("Location: login.php");
    exit();
}

// mysqli_close($conn);


?>