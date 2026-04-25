<?php
// ============================================================
//  config.php  –  Database connection (using MySQLi)
//  Keep this file in the root of your project
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // XAMPP default username
define('DB_PASS', '');           // XAMPP default password (empty)
define('DB_NAME', 'college_admission');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Start session (used on all pages for login tracking)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
