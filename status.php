<?php
// ============================================================
//  status.php  –  Student dashboard: view application status
// ============================================================
require_once 'config.php';
$page_title = 'Application Status';

if (!isset($_SESSION['student_id'])) {
    header('Location: login.php');
    exit;
}

$student_id = $_SESSION['student_id'];

// Fetch student
$st = mysqli_query($conn, "SELECT * FROM students WHERE student_id=$student_id");
$student = mysqli_fetch_assoc($st);

// Fetch application (if any)
$app_q = mysqli_query($conn,
    "SELECT a.*, c.course_name, c.course_code, c.duration, c.fee_per_year, c.department,
            f.admission_fee, f.exam_fee
     FROM applications a
     JOIN courses c ON a.course_id = c.course_id
     LEFT JOIN fees f ON f.course_id = c.course_id
     WHERE a.student_id = $student_id
     ORDER BY a.applied_on DESC
     LIMIT 1");
$app = mysqli_fetch_assoc($app_q);

include 'includes/header.php';
?>

<div class="page-banner">My Application Dashboard</div>

<div class="container">
    <div class="main-content">

        <!-- Student Info Box -->
        <h2 class="section-title">Welcome, <?= htmlspecialchars($student['full_name']) ?></h2>

        <table style="max-width:550px; margin-bottom:20px;">
            <tr><th>Email</th>        <td><?= htmlspecialchars($student['email']) ?></td></tr>
            <tr><th>Mobile</th>       <td><?= htmlspecialchars($student['phone'] ?: 'Not provided') ?></td></tr>
            <tr><th>10th Marks</th>   <td><?= $student['marks_10th'] ?>%</td></tr>
            <tr><th>12th Marks</th>   <td><?= $student['marks_12th'] ?>%</td></tr>
            <tr><th>Gender</th>       <td><?= htmlspecialchars($student['gender'] ?: '–') ?></td></tr>
            <tr><th>Registered On</th><td><?= date('d M Y', strtotime($student['created_at'])) ?></td></tr>
        </table>

        <h2 class="section-title">Application Status</h2>

        <?php if ($app): ?>
        <!-- Application Found -->
        <table style="max-width:600px; margin-bottom:20px;">
            <tr>
                <th>Application ID</th>
                <td>#<?= str_pad($app['application_id'], 5, '0', STR_PAD_LEFT) ?></td>
            </tr>
            <tr>
                <th>Course Applied</th>
                <td><?= htmlspecialchars($app['course_name']) ?></td>
            </tr>
            <tr>
                <th>Course Code</th>
                <td><?= htmlspecialchars($app['course_code']) ?></td>
            </tr>
            <tr>
                <th>Department</th>
                <td><?= htmlspecialchars($app['department']) ?></td>
            </tr>
            <tr>
                <th>Duration</th>
                <td><?= htmlspecialchars($app['duration']) ?></td>
            </tr>
            <tr>
                <th>Annual Fee</th>
                <td>&#8377; <?= number_format($app['fee_per_year'], 0) ?></td>
            </tr>
            <tr>
                <th>Admission Fee</th>
                <td>&#8377; <?= number_format($app['admission_fee'], 0) ?></td>
            </tr>
            <tr>
                <th>Applied On</th>
                <td><?= date('d M Y, h:i A', strtotime($app['applied_on'])) ?></td>
            </tr>
            <tr>
                <th>Current Status</th>
                <td>
                    <span class="badge badge-<?= strtolower($app['status']) ?>">
                        <?= $app['status'] ?>
                    </span>
                </td>
            </tr>
            <?php if ($app['remarks']): ?>
            <tr>
                <th>Admin Remarks</th>
                <td><?= htmlspecialchars($app['remarks']) ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- Status Tracker -->
        <h3 class="sub-title">Application Progress</h3>
        <div style="display:flex; gap:0; margin-bottom:20px; flex-wrap:wrap;">
            <?php
            $steps = ['Submitted', 'Under Review', 'Decision Made'];
            $step_index = ($app['status'] == 'Pending') ? 1 :
                          (($app['status'] == 'Approved' || $app['status'] == 'Rejected') ? 2 : 0);
            foreach ($steps as $i => $step):
                $done = ($i <= $step_index);
                $bg   = $done ? '#003580' : '#ccc';
                $fg   = $done ? '#fff'    : '#666';
            ?>
            <div style="flex:1; min-width:120px; text-align:center; padding:12px 5px;
                        background:<?= $bg ?>; color:<?= $fg ?>; font-size:13px; font-weight:bold;
                        border-right:2px solid #fff;">
                <?= $i+1 ?>. <?= $step ?>
                <?php if ($i == $step_index && $app['status'] == 'Approved'): ?> &#10004;<?php endif; ?>
                <?php if ($i == $step_index && $app['status'] == 'Rejected'): ?> &#10006;<?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($app['status'] == 'Approved'): ?>
        <div class="alert alert-success">
            &#127881; Congratulations! Your admission has been <strong>Approved</strong>.
            Please visit the college campus with original documents to complete the admission process.
        </div>
        <?php elseif ($app['status'] == 'Rejected'): ?>
        <div class="alert alert-error">
            Your application has been <strong>Rejected</strong>.
            <?= $app['remarks'] ? 'Reason: ' . htmlspecialchars($app['remarks']) : '' ?>
            Please contact the admissions office for more information.
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            Your application is <strong>Pending</strong> review. We will update the status within 3–5 working days.
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- No Application Yet -->
        <div class="alert alert-info">
            You have not applied for any course yet.
            <a href="apply.php" class="btn btn-danger btn-sm" style="margin-left:10px;">Apply Now</a>
        </div>
        <?php endif; ?>

    </div>

    <div class="sidebar">
        <div class="sidebar-widget">
            <h3>Quick Links</h3>
            <ul>
                <?php if (!$app): ?>
                <li><a href="apply.php">Apply for Course</a></li>
                <?php endif; ?>
                <li><a href="courses.php">View Courses</a></li>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Help</h3>
            <ul>
                <li>Call: 0731-1234500</li>
                <li>Email: info@NITM.ac.in</li>
                <li>Mon–Sat: 9AM–5PM</li>
            </ul>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
