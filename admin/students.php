<?php
// ============================================================
//  admin/students.php  –  View all registered students
// ============================================================
$in_subfolder = true;
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Search
$search = isset($_GET['q']) ? trim(mysqli_real_escape_string($conn, $_GET['q'])) : '';
$where  = $search ? "WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'" : '';

$result = mysqli_query($conn,
    "SELECT s.*, 
            (SELECT c.course_name FROM applications a JOIN courses c ON c.course_id=a.course_id
             WHERE a.student_id=s.student_id LIMIT 1) AS applied_course,
            (SELECT a.status FROM applications a WHERE a.student_id=s.student_id LIMIT 1) AS app_status
     FROM students s
     $where
     ORDER BY s.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Students | SUAS Admin</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="admin-wrap">

    <div class="admin-sidebar">
        <h3>&#9776; Admin Panel</h3>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="applications.php">Applications</a></li>
            <li><a href="students.php" class="active">Students</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="../index.php">&#8592; Main Website</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="admin-main">
        <h2>Registered Students</h2>

        <!-- Search Form -->
        <form method="GET" action="" style="margin-bottom:15px; display:flex; gap:10px;">
            <input type="text" name="q" placeholder="Search by name or email..."
                   value="<?= htmlspecialchars($search) ?>"
                   style="padding:8px 10px; border:1px solid #aaa; font-size:14px; flex:1; max-width:300px;">
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if ($search): ?>
            <a href="students.php" class="btn btn-danger">Clear</a>
            <?php endif; ?>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>10th %</th>
                <th>12th %</th>
                <th>Applied For</th>
                <th>App Status</th>
                <th>Registered</th>
            </tr>
            <?php
            $count = 0;
            while ($row = mysqli_fetch_assoc($result)):
                $count++;
            ?>
            <tr>
                <td><?= $row['student_id'] ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td style="font-size:12px;"><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone'] ?: '–') ?></td>
                <td><?= $row['marks_10th'] ?: '–' ?>%</td>
                <td><?= $row['marks_12th'] ?: '–' ?>%</td>
                <td style="font-size:12px;">
                    <?= $row['applied_course'] ? htmlspecialchars($row['applied_course']) : '<em style="color:#999;">Not Applied</em>' ?>
                </td>
                <td>
                    <?php if ($row['app_status']): ?>
                    <span class="badge badge-<?= strtolower($row['app_status']) ?>"><?= $row['app_status'] ?></span>
                    <?php else: ?>
                    <em style="color:#999; font-size:12px;">–</em>
                    <?php endif; ?>
                </td>
                <td style="font-size:12px;"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($count === 0): ?>
            <tr><td colspan="9" style="text-align:center; color:#666; padding:20px;">
                No students found<?= $search ? " for '$search'" : '' ?>.
            </td></tr>
            <?php endif; ?>
        </table>

        <p style="margin-top:10px; font-size:13px; color:#666;">
            Total: <strong><?= $count ?></strong> student(s) found.
        </p>

    </div>
</div>

</body>
</html>
