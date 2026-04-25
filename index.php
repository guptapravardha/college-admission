<?php
// ============================================================
//  index.php  –  Home Page
// ============================================================
require_once 'config.php';
$page_title = 'Welcome';
include 'includes/header.php';
?>

<!-- PAGE BANNER -->
<div class="page-banner">Home &rsaquo; Welcome to SUAS</div>

<div class="container">

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- Welcome Box -->
        <div class="welcome-box">
            <h2>Welcome to Symbiosis University of Applied Sciences</h2>
            <p>
                Symbiosis University of Applied Sciences (SUAS), Indore is a forward-looking institution
                committed to providing quality technical and management education. Established under the
                Symbiosis family, SUAS offers industry-aligned programs in Engineering, Management, and Science.
            </p>
            <p style="margin-top:10px;">
                <a href="apply.php" class="btn btn-danger">Apply for Admission 2026–27</a>
                &nbsp;
                <a href="courses.php" class="btn btn-primary">View All Courses</a>
            </p>
        </div>

        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-box">
                <div class="number">10+</div>
                <div class="label">Programs</div>
            </div>
            <div class="stat-box">
                <div class="number">500+</div>
                <div class="label">Students</div>
            </div>
            <div class="stat-box">
                <div class="number">50+</div>
                <div class="label">Faculty</div>
            </div>
            <div class="stat-box">
                <div class="number">100+</div>
                <div class="label">Recruiters</div>
            </div>
        </div>

        <!-- About Section -->
        <h2 class="section-title">About the University</h2>
        <p style="line-height:1.8; font-size:14px; color:#444;">
            SUAS is located at Bada Bangarda, Super Corridor, Near the Airport, Indore-453112 – one of the fastest growing
            educational hubs in central India. The university focuses on applied learning, industry
            partnerships, and skill development to prepare students for real-world challenges.
            It is approved by UGC and affiliated to Symbiosis International (Deemed University), Pune.
        </p>

        <h3 class="sub-title">Why Choose SUAS?</h3>
        <ul style="padding-left:20px; font-size:14px; line-height:2;">
            <li>Industry-integrated curriculum with real projects</li>
            <li>Experienced faculty from industry and academia</li>
            <li>Strong placement support with 100+ recruiters</li>
            <li>Modern campus with labs and library facilities</li>
            <li>Part of the prestigious Symbiosis family</li>
        </ul>

        <!-- Notice Board -->
        <h2 class="section-title" style="margin-top:25px;">Notice Board</h2>
        <div class="notice-box">
            <div class="notice-item"><span>[New]</span> Admission 2026–27 now open for all UG and PG programs</div>
            <div class="notice-item"><span>[Exam]</span> End semester exam schedule – April 2026 published</div>
            <div class="notice-item"><span>[Event]</span> Annual technical fest "SAMAKSH'26" – 15 March 2026</div>
            <div class="notice-item"><span>[Holiday]</span> Summer vacation: 15 May to 14 June 2026</div>
            <div class="notice-item"><span>[Result]</span> Semester 1 results declared – check student portal</div>
        </div>

    </div><!-- end main-content -->

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="sidebar-widget">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="register.php">New Registration</a></li>
                <li><a href="apply.php">Apply Online</a></li>
                <li><a href="status.php">Check Status</a></li>
                <li><a href="courses.php">All Courses</a></li>
                <li><a href="login.php">Student Login</a></li>
            </ul>
        </div>

        <div class="sidebar-widget">
            <h3>Programs</h3>
            <ul>
                <li><a href="courses.php">B.Tech CSIT</a></li>
                <li><a href="courses.php">B.Tech Mechatronics</a></li>
                <li><a href="courses.php">B.Tech Automobile</a></li>
                <li><a href="courses.php">BBA BFSI</a></li>
                <li><a href="courses.php">BSc Data Science</a></li>
                <li><a href="courses.php">MBA BFSI</a></li>
                <li><a href="courses.php">MBA Marketing</a></li>
            </ul>
        </div>

        <div class="sidebar-widget">
            <h3>Important Dates</h3>
            <ul>
                <li>Applications Open: 1 Apr 2025</li>
                <li>Last Date: 31 Jul 2025</li>
                <li>Merit List: Aug 2025</li>
                <li>Classes Start: Aug 2025</li>
            </ul>
        </div>

        <div class="sidebar-widget">
            <h3>Contact Us</h3>
            <ul>
                <li>0731-2970000</li>
                <li>info@suas.ac.in</li>
                <li>Indore, M.P.</li>
            </ul>
        </div>

    </div><!-- end sidebar -->

</div><!-- end container -->

<?php include 'includes/footer.php'; ?>
