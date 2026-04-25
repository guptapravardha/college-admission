<?php
// ============================================================
//  apply.php  –  Course Application Form (students only)
// ============================================================
require_once 'config.php';
$page_title = 'Apply for Admission';

// Must be logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: register.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$error      = '';
$success    = '';

// Fetch student info
$st_result = mysqli_query($conn, "SELECT * FROM students WHERE student_id=$student_id");
$student   = mysqli_fetch_assoc($st_result);

// Check if student already has an application
$app_check = mysqli_query($conn, "SELECT a.application_id, c.course_name, a.status
                                   FROM applications a
                                   JOIN courses c ON a.course_id=c.course_id
                                   WHERE a.student_id=$student_id");
$existing_app = mysqli_fetch_assoc($app_check);

// Fetch all courses for the dropdown
$courses_result = mysqli_query($conn, "SELECT * FROM courses ORDER BY course_name");
$all_courses    = mysqli_fetch_all($courses_result, MYSQLI_ASSOC);

// Pre-select course if coming from courses page
$preselect_course = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$existing_app) {
    $course_id = intval($_POST['course_id']);

    if (!$course_id) {
        $error = 'Please select a course.';
    } else {
        // Fetch selected course details (for eligibility check)
        $crs = mysqli_query($conn, "SELECT * FROM courses WHERE course_id=$course_id");
        $course_info = mysqli_fetch_assoc($crs);

        // Eligibility check based on 12th marks
        if ($student['marks_12th'] < $course_info['min_marks']) {
            $error = "Sorry, you are not eligible for <strong>" . htmlspecialchars($course_info['course_name']) . "</strong>. "
                   . "This course requires minimum <strong>" . $course_info['min_marks'] . "%</strong> in 12th. "
                   . "Your marks: <strong>" . $student['marks_12th'] . "%</strong>.";
        } else {
            // Insert application
            $sql = "INSERT INTO applications (student_id, course_id, status)
                    VALUES ($student_id, $course_id, 'Pending')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Your application has been submitted successfully! '
                         . 'We will review it and update the status within 3-5 working days.';
                // Refresh existing_app
                $app_check2 = mysqli_query($conn, "SELECT a.application_id, c.course_name, a.status
                                                    FROM applications a
                                                    JOIN courses c ON a.course_id=c.course_id
                                                    WHERE a.student_id=$student_id");
                $existing_app = mysqli_fetch_assoc($app_check2);
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="page-banner">Online Admission Application</div>

<div class="container">
    <div class="main-content">

        <!-- Show student info strip -->
        <div class="alert alert-info">
            Logged in as: <strong><?= htmlspecialchars($student['full_name']) ?></strong>
            &nbsp;|&nbsp; 12th Marks: <strong><?= $student['marks_12th'] ?>%</strong>
        </div>

        <?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

        <?php if ($existing_app && !$success): ?>
        <!-- Already applied -->
        <div class="alert alert-info" style="font-size:15px;">
            You have already applied for <strong><?= htmlspecialchars($existing_app['course_name']) ?></strong>.
            Status: <span class="badge badge-<?= strtolower($existing_app['status']) ?>"><?= $existing_app['status'] ?></span>
            &nbsp;<a href="status.php">View full status &rarr;</a>
        </div>

        <?php elseif (!$success): ?>
        <!-- Application Form -->
        <div class="form-wrap" style="max-width:600px; margin:0;">
            <h2>Apply for Admission 2025–26</h2>

            <form method="POST" action="">
                <h3 class="sub-title">Your Details (filled from registration)</h3>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="<?= htmlspecialchars($student['full_name']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="text" value="<?= htmlspecialchars($student['email']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label>12th Percentage</label>
                    <input type="text" value="<?= $student['marks_12th'] ?>%" disabled>
                </div>

                <h3 class="sub-title">Select Course</h3>

                <div class="form-group">
                    <label>Choose Course *</label>
                    <select name="course_id" required onchange="checkEligibility(this)">
                        <option value="">-- Select a Course --</option>
                        <?php foreach ($all_courses as $c): ?>
                        <option value="<?= $c['course_id'] ?>"
                                data-min="<?= $c['min_marks'] ?>"
                                data-name="<?= htmlspecialchars($c['course_name']) ?>"
                            <?= ($preselect_course == $c['course_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['course_name']) ?> (<?= $c['course_code'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Eligibility display (JS) -->
                <div id="elig-msg" style="margin-bottom:12px; font-size:13px; font-weight:bold;"></div>

                <button type="submit" class="btn btn-danger" style="width:100%; padding:12px;" id="submit-btn">
                    Submit Application
                </button>
            </form>
        </div>
        <?php endif; ?>

    </div>

    <div class="sidebar">
        <div class="sidebar-widget">
            <h3>Application Rules</h3>
            <ul>
                <li>Only 1 course per student</li>
                <li>Marks are auto-checked</li>
                <li>Status shown in 3–5 days</li>
                <li>Original docs needed at campus</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Documents Needed</h3>
            <ul>
                <li>10th Marksheet</li>
                <li>12th Marksheet</li>
                <li>Aadhar Card / ID</li>
                <li>Passport Photo</li>
                <li>Transfer Certificate</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Links</h3>
            <ul>
                <li><a href="status.php">Check My Status</a></li>
                <li><a href="courses.php">View Courses</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

</div>

<script>
// Real-time eligibility check in browser (frontend only, PHP does the real check)
var studentMarks = <?= floatval($student['marks_12th']) ?>;

function checkEligibility(select) {
    var option  = select.options[select.selectedIndex];
    var minReq  = parseFloat(option.getAttribute('data-min')) || 0;
    var name    = option.getAttribute('data-name') || '';
    var msgDiv  = document.getElementById('elig-msg');
    var btn     = document.getElementById('submit-btn');

    if (!option.value) {
        msgDiv.innerHTML = '';
        return;
    }

    if (studentMarks >= minReq) {
        msgDiv.style.color = '#1a6e1a';
        msgDiv.innerHTML = '&#10004; You are eligible for ' + name
                         + ' (Required: ' + minReq + '%, Your marks: ' + studentMarks + '%)';
        btn.disabled = false;
    } else {
        msgDiv.style.color = '#800000';
        msgDiv.innerHTML = '&#10006; Not eligible for ' + name
                         + ' (Required: ' + minReq + '%, Your marks: ' + studentMarks + '%)';
        btn.disabled = true;
    }
}

// Trigger on page load if course is pre-selected
window.onload = function() {
    var sel = document.querySelector('select[name="course_id"]');
    if (sel && sel.value) checkEligibility(sel);
};
</script>

<?php include 'includes/footer.php'; ?>
