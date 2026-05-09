<?php
// ============================================================
//  admin/applications.php  –  View & manage all applications
// ============================================================
$in_subfolder = true;
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$msg   = '';
$error = '';

// --- Handle Approve / Reject action ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_id'])) {
    $app_id = intval($_POST['app_id']);
    $action  = $_POST['action']; // 'Approved' or 'Rejected'
    $remarks = trim(mysqli_real_escape_string($conn, $_POST['remarks']));

    if (in_array($action, ['Approved', 'Rejected'])) {
        mysqli_query($conn,
            "UPDATE applications SET status='$action', remarks='$remarks'
             WHERE application_id=$app_id");
        $msg = "Application #" . str_pad($app_id, 5, '0', STR_PAD_LEFT)
             . " has been <strong>$action</strong>.";
    }
}

// --- Single application detail view ---
$view_app = null;
if (isset($_GET['id'])) {
    $view_id  = intval($_GET['id']);
    $view_q   = mysqli_query($conn,
        "SELECT a.*, s.full_name, s.email, s.phone, s.marks_10th, s.marks_12th, s.gender,
                c.course_name, c.course_code, c.min_marks, c.fee_per_year
         FROM applications a
         JOIN students s ON s.student_id = a.student_id
         JOIN courses   c ON c.course_id  = a.course_id
         WHERE a.application_id=$view_id");
    $view_app = mysqli_fetch_assoc($view_q);
}

// --- List all applications ---
$filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$where  = $filter ? "WHERE a.status='$filter'" : '';

$apps_q = mysqli_query($conn,
    "SELECT a.application_id, s.full_name, s.email, s.marks_12th,
            c.course_name, c.min_marks, a.status, a.applied_on
     FROM applications a
     JOIN students s ON s.student_id = a.student_id
     JOIN courses   c ON c.course_id  = a.course_id
     $where
     ORDER BY a.applied_on DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Applications | NITM Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-wrap">

    <div class="admin-sidebar">
        <h3>&#9776; Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="applications.php" class="active">Applications</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="../index.php">&#8592; Main Website</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="admin-main">

        <?php if ($msg): ?>
        <div class="alert alert-success"><?= $msg ?></div>
        <?php endif; ?>

        <!-- Single Application Detail -->
        <?php if ($view_app): ?>
        <h2>Application Detail – #<?= str_pad($view_app['application_id'], 5, '0', STR_PAD_LEFT) ?></h2>

        <div style="display:flex; gap:20px; flex-wrap:wrap;">

            <div style="flex:1; min-width:260px;">
                <h3 class="sub-title">Student Information</h3>
                <table>
                    <tr><th>Name</th>       <td><?= htmlspecialchars($view_app['full_name']) ?></td></tr>
                    <tr><th>Email</th>      <td><?= htmlspecialchars($view_app['email']) ?></td></tr>
                    <tr><th>Phone</th>      <td><?= htmlspecialchars($view_app['phone'] ?: '–') ?></td></tr>
                    <tr><th>Gender</th>     <td><?= htmlspecialchars($view_app['gender'] ?: '–') ?></td></tr>
                    <tr><th>10th Marks</th> <td><?= $view_app['marks_10th'] ?>%</td></tr>
                    <tr><th>12th Marks</th> <td><?= $view_app['marks_12th'] ?>%</td></tr>
                </table>
            </div>

            <div style="flex:1; min-width:260px;">
                <h3 class="sub-title">Course & Application Info</h3>
                <table>
                    <tr><th>Course</th>      <td><?= htmlspecialchars($view_app['course_name']) ?></td></tr>
                    <tr><th>Code</th>        <td><?= htmlspecialchars($view_app['course_code']) ?></td></tr>
                    <tr><th>Min Required</th><td><?= $view_app['min_marks'] ?>%</td></tr>
                    <tr><th>Applied On</th>  <td><?= date('d M Y, h:i A', strtotime($view_app['applied_on'])) ?></td></tr>
                    <tr>
                        <th>Eligibility</th>
                        <td>
                            <?php if ($view_app['marks_12th'] >= $view_app['min_marks']): ?>
                                <span style="color:green; font-weight:bold;">&#10004; Eligible</span>
                            <?php else: ?>
                                <span style="color:red; font-weight:bold;">&#10006; Not Eligible</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge badge-<?= strtolower($view_app['status']) ?>"><?= $view_app['status'] ?></span></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Approve / Reject Form -->
        <?php if ($view_app['status'] === 'Pending'): ?>
        <h3 class="sub-title" style="margin-top:20px;">Take Action</h3>
        <form method="POST" action="applications.php" style="background:#fff; border:1px solid #ddd; padding:20px; max-width:500px;">
            <input type="hidden" name="app_id" value="<?= $view_app['application_id'] ?>">
            <div class="form-group">
                <label>Remarks (optional) – visible to student</label>
                <textarea name="remarks" placeholder="e.g. Documents verified, Welcome to NITM"></textarea>
            </div>
            <div style="display:flex; gap:10px;">
                <button type="submit" name="action" value="Approved" class="btn btn-success">
                    &#10004; Approve Application
                </button>
                <button type="submit" name="action" value="Rejected" class="btn btn-danger">
                    &#10006; Reject Application
                </button>
            </div>
        </form>
        <?php else: ?>
        <div class="alert alert-info" style="margin-top:15px;">
            This application has already been <strong><?= $view_app['status'] ?></strong>.
            <?php if ($view_app['remarks']): ?>
            Remarks: <?= htmlspecialchars($view_app['remarks']) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <p style="margin-top:15px;"><a href="applications.php">&larr; Back to all applications</a></p>

        <?php else: ?>

        <!-- Applications List -->
        <h2>All Applications</h2>

        <!-- Filter tabs -->
        <div style="margin-bottom:12px; font-size:14px;">
            Filter:
            <a href="applications.php" style="<?= !$filter ? 'font-weight:bold;color:#800000;' : '' ?>">All</a> &nbsp;|&nbsp;
            <a href="applications.php?status=Pending"  style="<?= $filter=='Pending'  ? 'font-weight:bold;color:#800000;' : '' ?>">Pending</a> &nbsp;|&nbsp;
            <a href="applications.php?status=Approved" style="<?= $filter=='Approved' ? 'font-weight:bold;color:#800000;' : '' ?>">Approved</a> &nbsp;|&nbsp;
            <a href="applications.php?status=Rejected" style="<?= $filter=='Rejected' ? 'font-weight:bold;color:#800000;' : '' ?>">Rejected</a>
        </div>

        <table>
            <tr>
                <th>App ID</th>
                <th>Student Name</th>
                <th>Email</th>
                <th>12th %</th>
                <th>Course Applied</th>
                <th>Applied On</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            $count = 0;
            while ($row = mysqli_fetch_assoc($apps_q)):
                $count++;
                $elig = ($row['marks_12th'] >= $row['min_marks']);
            ?>
            <tr>
                <td>#<?= str_pad($row['application_id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($row['email']) ?></td>
                <td style="color:<?= $elig ? '#1a6e1a' : '#800000' ?>; font-weight:bold;">
                    <?= $row['marks_12th'] ?>%
                </td>
                <td style="font-size:13px;"><?= htmlspecialchars($row['course_name']) ?></td>
                <td style="font-size:12px;"><?= date('d M Y', strtotime($row['applied_on'])) ?></td>
                <td><span class="badge badge-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></span></td>
                <td>
                    <a href="applications.php?id=<?= $row['application_id'] ?>"
                       class="btn btn-primary btn-sm">View / Review</a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if ($count === 0): ?>
            <tr><td colspan="8" style="text-align:center; color:#666; padding:20px;">
                No applications found<?= $filter ? " with status '$filter'" : '' ?>.
            </td></tr>
            <?php endif; ?>
        </table>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
