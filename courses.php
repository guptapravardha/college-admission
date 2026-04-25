<?php
// ============================================================
//  courses.php  –  All available courses
// ============================================================
require_once 'config.php';
$page_title = 'Courses Offered';
include 'includes/header.php';

// Fetch all courses from database
$result = mysqli_query($conn, "SELECT * FROM courses ORDER BY department, course_name");
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Group by department
$departments = [];
foreach ($courses as $c) {
    $departments[$c['department']][] = $c;
}
?>

<div class="page-banner">Courses Offered at SUAS</div>

<div class="container">
    <div class="main-content">

        <h2 class="section-title">Academic Programs 2025–26</h2>
        <p style="font-size:14px; color:#555; margin-bottom:15px;">
            SUAS offers a range of undergraduate and postgraduate programs designed to meet industry demands.
            All programs are approved by UGC and affiliated to Symbiosis International (Deemed University).
        </p>

        <?php foreach ($departments as $dept => $dept_courses): ?>
        <h3 class="sub-title"><?= htmlspecialchars($dept) ?></h3>
        <div class="course-grid">
            <?php foreach ($dept_courses as $course): ?>
            <div class="course-card">
                <h4><?= htmlspecialchars($course['course_name']) ?></h4>
                <p><strong>Code:</strong> <?= htmlspecialchars($course['course_code']) ?></p>
                <p><strong>Duration:</strong> <?= htmlspecialchars($course['duration']) ?></p>
                <p><strong>Total Seats:</strong> <?= $course['total_seats'] ?></p>
                <p><strong>Min Marks (12th):</strong> <?= $course['min_marks'] ?>%</p>
                <p><strong>Annual Fee:</strong> &#8377; <?= number_format($course['fee_per_year'], 0) ?></p>
                <a href="apply.php?course_id=<?= $course['course_id'] ?>" class="apply-link">Apply Now</a>
            </div>
            <?php endforeach; ?>
        </div>
        <br>
        <?php endforeach; ?>

        <!-- Fee Structure Table -->
        <h2 class="section-title" style="margin-top:20px;">Fee Structure Summary</h2>
        <table>
            <tr>
                <th>Course</th>
                <th>Annual Fee (₹)</th>
                <th>Admission Fee (₹)</th>
                <th>Exam Fee (₹)</th>
                <th>Total (1st Year)</th>
            </tr>
            <?php
            $fee_result = mysqli_query($conn,
                "SELECT c.course_name, f.annual_fee, f.admission_fee, f.exam_fee
                 FROM fees f
                 JOIN courses c ON f.course_id = c.course_id
                 ORDER BY c.course_name");
            while ($row = mysqli_fetch_assoc($fee_result)):
                $total = $row['annual_fee'] + $row['admission_fee'] + $row['exam_fee'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td>&#8377; <?= number_format($row['annual_fee'], 0) ?></td>
                <td>&#8377; <?= number_format($row['admission_fee'], 0) ?></td>
                <td>&#8377; <?= number_format($row['exam_fee'], 0) ?></td>
                <td><strong>&#8377; <?= number_format($total, 0) ?></strong></td>
            </tr>
            <?php endwhile; ?>
        </table>

    </div>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-widget">
            <h3>Eligibility</h3>
            <ul>
                <li>B.Tech: 60% in 12th (PCM)</li>
                <li>BBA: 50% in 12th (any stream)</li>
                <li>BSc DS: 55% in 12th (Maths)</li>
                <li>MBA: 50% in Graduation</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Admission Process</h3>
            <ul>
                <li>1. Register on portal</li>
                <li>2. Fill application form</li>
                <li>3. Upload documents</li>
                <li>4. Admin review</li>
                <li>5. Status update</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Apply Now</h3>
            <ul>
                <li><a href="register.php">New Registration</a></li>
                <li><a href="apply.php">Apply Online</a></li>
                <li><a href="status.php">Check Status</a></li>
            </ul>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
