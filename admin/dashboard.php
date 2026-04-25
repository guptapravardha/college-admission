<?php
// ============================================================
//  admin/dashboard.php  –  Admin main dashboard
// ============================================================
$in_subfolder = true;
require_once '../config.php';

// Must be logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch counts for dashboard cards
$total_students  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM students"))[0];
$total_apps      = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM applications"))[0];
$pending_apps    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM applications WHERE status='Pending'"))[0];
$approved_apps   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM applications WHERE status='Approved'"))[0];
$rejected_apps   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM applications WHERE status='Rejected'"))[0];
$total_courses   = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM courses"))[0];

// Recent 5 applications
$recent_q = mysqli_query($conn,
    "SELECT a.application_id, s.full_name, c.course_name, a.status, a.applied_on
     FROM applications a
     JOIN students s ON s.student_id = a.student_id
     JOIN courses   c ON c.course_id  = a.course_id
     ORDER BY a.applied_on DESC
     LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SUAS</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-wrap">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">
        <h3>&#9776; Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="applications.php">Applications</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="../index.php">&#8592; Main Website</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        <div style="padding:12px; color:#99b3cc; font-size:12px; margin-top:20px;">
            Logged in as:<br><strong style="color:#fff;"><?= htmlspecialchars($_SESSION['admin_name']) ?></strong>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="admin-main">
        <h2>Dashboard Overview</h2>

        <!-- Stat Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="big-num"><?= $total_students ?></div>
                <p>Registered Students</p>
            </div>
            <div class="stat-card">
                <div class="big-num"><?= $total_apps ?></div>
                <p>Total Applications</p>
            </div>
            <div class="stat-card orange">
                <div class="big-num"><?= $pending_apps ?></div>
                <p>Pending Review</p>
            </div>
            <div class="stat-card green">
                <div class="big-num"><?= $approved_apps ?></div>
                <p>Approved</p>
            </div>
            <div class="stat-card maroon">
                <div class="big-num"><?= $rejected_apps ?></div>
                <p>Rejected</p>
            </div>
            <div class="stat-card">
                <div class="big-num"><?= $total_courses ?></div>
                <p>Courses Offered</p>
            </div>
        </div>

        <!-- Recent Applications Table -->
        <h3 style="color:#003580; margin-bottom:12px;">Recent Applications</h3>
        <table>
            <tr>
                <th>App ID</th>
                <th>Student Name</th>
                <th>Course Applied</th>
                <th>Applied On</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($recent_q)): ?>
            <tr>
                <td>#<?= str_pad($row['application_id'], 5, '0', STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td><?= date('d M Y', strtotime($row['applied_on'])) ?></td>
                <td><span class="badge badge-<?= strtolower($row['status']) ?>"><?= $row['status'] ?></span></td>
                <td><a href="applications.php?id=<?= $row['application_id'] ?>" class="btn btn-primary btn-sm">Review</a></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <p style="margin-top:10px; font-size:13px;">
            <a href="applications.php">View all applications &rarr;</a>
        </p>

    </div><!-- end admin-main -->

</div><!-- end admin-wrap -->

</body>
</html>
