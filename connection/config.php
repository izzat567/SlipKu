<?php
// config.php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "slipku_db"; // nama database

$connect = mysqli_connect($host, $user, $pass, $db);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
