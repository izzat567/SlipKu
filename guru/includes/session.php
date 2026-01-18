<?php
// Mulakan session jika belum dimulakan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionManager {
    
    // Set session value
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    // Get session value
    public static function get($key, $default = null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }
    
    // Check if session exists
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    // Remove session
    public static function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    // Destroy all sessions
    public static function destroy() {
        session_destroy();
        $_SESSION = [];
    }
    
    // Check if user is logged in as guru
    public static function isGuruLoggedIn() {
        return isset($_SESSION['guru_id']) && isset($_SESSION['guru_email']);
    }
    
    // Redirect if not logged in
    public static function requireGuruLogin() {
        if (!self::isGuruLoggedIn()) {
            header('Location: ../login.php');
            exit();
        }
    }
    
    // Get guru info
    public static function getGuruInfo() {
        if (self::isGuruLoggedIn()) {
            return [
                'id' => $_SESSION['guru_id'],
                'nama' => $_SESSION['guru_nama'],
                'email' => $_SESSION['guru_email'],
                'telefon' => $_SESSION['guru_telefon'] ?? ''
            ];
        }
        return null;
    }
}
?>