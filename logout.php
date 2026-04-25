<?php
// ============================================================
//  logout.php  –  Destroys session and redirects to login
// ============================================================
require_once 'config.php';
session_destroy();
header('Location: login.php');
exit;
?>
