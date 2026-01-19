<?php
// Ganti dengan direct values (temporary)
$hostname = 'localhost';
$username = 'root';
$password = 'password';  
$database = 'slipku_db';
try {
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset
    $conn->set_charset("utf8mb4");
    
    
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>