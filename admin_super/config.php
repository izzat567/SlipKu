<?php
// config.php
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'slipku_db');

// Site Configuration
define('SITE_NAME', 'SlipKu Admin Panel');
define('SITE_URL', 'http://localhost/slipku-admin/');
define('SITE_PATH', __DIR__);

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Database Connection
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Database connection error: " . $e->getMessage());
    }
}

// Check Authentication
function checkAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }
}

// Sanitize Input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// Generate Random ID
function generateID($prefix = 'P') {
    return $prefix . date('Ymd') . rand(1000, 9999);
}

// Redirect Function
function redirect($url, $message = '') {
    if ($message) {
        $_SESSION['message'] = $message;
    }
    header("Location: $url");
    exit();
}

// Get Gred Based on Markah
function getGred($markah) {
    if ($markah >= 90) return 'A+';
    if ($markah >= 80) return 'A';
    if ($markah >= 75) return 'A-';
    if ($markah >= 70) return 'B+';
    if ($markah >= 65) return 'B';
    if ($markah >= 60) return 'B-';
    if ($markah >= 55) return 'C+';
    if ($markah >= 50) return 'C';
    if ($markah >= 45) return 'C-';
    if ($markah >= 40) return 'D';
    if ($markah >= 35) return 'E';
    return 'G';
}
?>