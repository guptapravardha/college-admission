<?php
// ============================================================
//  admin/courses.php  –  View courses and applicant counts
// ============================================================
$in_subfolder = true;
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$result = mysqli_query($conn,
    "SELECT c.*,
            COUNT(a.application_id) AS total_apps,
            SUM(CASE WHEN a.status='Approved' THEN 1 ELSE 0 END) AS approved,
            SUM(CASE WHEN a.status='Pending'  THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN a.status='Rejected' THEN 1 ELSE 0 END) AS rejected
     FROM courses c
     LEFT JOIN applications a ON a.course_id = c.course_id
     GROUP BY c.course_id
     ORDER BY c.department, c.course_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses | SUAS Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-wrap">

    <div class="admin-sidebar">
        <h3>&#9776; Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="applications.php">Applications</a></li>
            <li><a href="students.php">Students</a></li>
            <li><a href="courses.php" class="active">Courses</a></li>
            <li><a href="../index.php">&#8592; Main Website</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="admin-main">
        <h2>Courses &amp; Applicant Summary</h2>
        <p style="font-size:13px; color:#666; margin-bottom:15px;">
            This table shows all offered courses with the number of applications received.
        </p>

        <table>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Department</th>
                <th>Duration</th>
                <th>Seats</th>
                <th>Min Marks</th>
                <th>Annual Fee (&#8377;)</th>
                <th>Total Apps</th>
                <th>Pending</th>
                <th>Approved</th>
                <th>Rejected</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td style="font-size:12px; font-weight:bold;"><?= htmlspecialchars($row['course_code']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['duration']) ?></td>
                <td><?= $row['total_seats'] ?></td>
                <td><?= $row['min_marks'] ?>%</td>
                <td><?= number_format($row['fee_per_year'], 0) ?></td>
                <td style="font-weight:bold; text-align:center;"><?= $row['total_apps'] ?></td>
                <td style="text-align:center; color:#856404;"><?= $row['pending'] ?: 0 ?></td>
                <td style="text-align:center; color:#155724;"><?= $row['approved'] ?: 0 ?></td>
                <td style="text-align:center; color:#721c24;"><?= $row['rejected'] ?: 0 ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

    </div>
</div>

</body>
</html>
