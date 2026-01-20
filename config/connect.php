<?php
// config/connect.php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'slipku_db'; // Tukar nama variable

// Create connection
$database = new mysqli($host, $user, $password, $dbname); // $database sekarang adalah object mysqli

// Check connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Set charset
$database->set_charset("utf8mb4");

// Optional: Set timezone
date_default_timezone_set('Asia/Kuala_Lumpur');

// Return connection object
return $database;
?>