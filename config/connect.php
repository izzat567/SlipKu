<?php
// Load environment variables
function loadEnv($path = '.env') {
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if (($value[0] === '"' && $value[strlen($value)-1] === '"') ||
            ($value[0] === "'" && $value[strlen($value)-1] === "'")) {
            $value = substr($value, 1, -1);
        }
        
        // Set as environment variable
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// Load .env file
try {
    loadEnv();
} catch (Exception $e) {
    die('Error loading .env file: ' . $e->getMessage());
}

// Database connection using environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_DATABASE') ?: '';
$port = getenv('DB_PORT') ?: 3306;
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

// Create connection
try {
    // Using MySQLi
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset
    $conn->set_charset($charset);
    
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>