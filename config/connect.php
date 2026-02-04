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

// Create connection
$database = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Set charset
$database->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Jangan gunakan 'return' jika fail ini hanya untuk sambungan
// Biarkan variable $database tersedia untuk fail lain
?>