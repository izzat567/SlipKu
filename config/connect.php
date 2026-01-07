<?php
$env = parse_ini_string(file_get_contents(__DIR__.'/.env'));

//@ ENV
$hostname = $env['HOSTNAME'];
$username = $env['USERNAME'];
$password = $env['PASSWORD'];
$database = $env['DBNAME'];

// Create connection
try {
    // Using MySQLi
    $conn = new mysqli($hostname, $username, $password, $database);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>