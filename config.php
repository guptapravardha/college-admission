<?php
//  config.php  –  Database connection (using MySQLi)
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'college_admission');
// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
