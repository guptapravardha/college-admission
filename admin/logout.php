<?php
// admin/logout.php  –  Admin logout
require_once '../config.php';
session_destroy();
header('Location: login.php');
exit;
?>
