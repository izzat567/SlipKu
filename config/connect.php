<?php
// config/connect.php

// Show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'root';
$password = 'danialdev'; // Pastikan ini kata laluan yang betul
$dbname = 'slipku_db';

// Create connection - GUNA NAMA VARIABLE YANG SAMA
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');


?>