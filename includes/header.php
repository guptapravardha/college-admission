<?php
// ============================================================
//  includes/header.php  –  Common header + nav for all pages
//  Usage: include '../includes/header.php';  (from subfolders)
//         include 'includes/header.php';     (from root)
// ============================================================

// $page_title should be set before including this file
$title = isset($page_title) ? $page_title . ' | SUAS' : 'SUAS - College Admission';

// Detect if we are inside a subfolder (like admin/)
$prefix = (isset($in_subfolder) && $in_subfolder) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="<?= $prefix ?>css/style.css">
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
    <marquee behavior="scroll" direction="left">
        &#128276; Admissions Open 2026–27 &nbsp;|&nbsp;
        Last Date to Apply: 15th June 2026 &nbsp;|&nbsp;
        For Queries Call: 0731-1234567 &nbsp;|&nbsp;
        New Batch Starting August 2026
    </marquee>
    <div class="top-links">
        <?php if (isset($_SESSION['student_id'])): ?>
            <a href="<?= $prefix ?>status.php">My Application</a>
            <a href="<?= $prefix ?>logout.php">Logout</a>
        <?php else: ?>
            <a href="<?= $prefix ?>login.php">Student Login</a>
            <a href="<?= $prefix ?>register.php">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- HEADER -->
<header>
    <div style="width:80px;height:80px;border-radius:50%;border:3px solid #fff;
                background:#fff;display:flex;align-items:center;justify-content:center;
                color:#003580;font-weight:bold;font-size:13px;text-align:center;flex-shrink:0;">
        SUAS
    </div>
    <div class="header-text">
        <h1>Symbiosis University of Applied Sciences</h1>
        <p>Approved by UGC &nbsp;|&nbsp; Indore, Madhya Pradesh &nbsp;|&nbsp; Affiliated to Symbiosis International (Deemed University)</p>
    </div>
</header>

<!-- NAVIGATION -->
<nav>
    <ul>
        <li><a href="<?= $prefix ?>index.php"   class="<?= basename($_SERVER['PHP_SELF'])=='index.php'   ? 'active' : '' ?>">Home</a></li>
        <li><a href="<?= $prefix ?>courses.php"  class="<?= basename($_SERVER['PHP_SELF'])=='courses.php'  ? 'active' : '' ?>">Courses</a></li>
        <li><a href="<?= $prefix ?>apply.php"    class="<?= basename($_SERVER['PHP_SELF'])=='apply.php'    ? 'active' : '' ?>">Apply Online</a></li>
        <li><a href="<?= $prefix ?>status.php"   class="<?= basename($_SERVER['PHP_SELF'])=='status.php'   ? 'active' : '' ?>">Check Status</a></li>
        <li><a href="<?= $prefix ?>register.php" class="<?= basename($_SERVER['PHP_SELF'])=='register.php' ? 'active' : '' ?>">Register</a></li>
        <li><a href="<?= $prefix ?>login.php"    class="<?= basename($_SERVER['PHP_SELF'])=='login.php'    ? 'active' : '' ?>">Login</a></li>
        <li><a href="<?= $prefix ?>admin/dashboard.php">Admin Panel</a></li>
    </ul>
</nav>
