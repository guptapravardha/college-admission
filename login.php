<?php
//  login.php  –  Student Login
require_once 'config.php';
$page_title = 'Student Login';
$error = '';
if (isset($_SESSION['student_id'])) {
    header('Location: status.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password.';
    } else {
       
        $result = mysqli_query($conn, "SELECT * FROM students WHERE email='$email'");
        $student = mysqli_fetch_assoc($result);

        if ($student && password_verify($password, $student['password'])) {
            // Login successful – store in session
            $_SESSION['student_id'] = $student['student_id'];
            $_SESSION['student_name'] = $student['full_name'];
            header('Location: status.php');
            exit;
        } else {
            $error = 'Invalid email or password. Please try again.';
        }
    }
}
include 'includes/header.php';
?>
<div class="page-banner">Student Login</div>
<div class="container">
    <div class="main-content">
        <div class="form-wrap">
            <h2>Student Login</h2>
            <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">Registration successful! Please login below.</div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="Enter your registered email" required
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    Login
                </button>
                <p style="margin-top:12px; font-size:13px; text-align:center;">
                    New student? <a href="register.php">Register here</a>
                </p>
            </form>
        </div>
    </div>
    <div class="sidebar">
        <div class="sidebar-widget">
            <h3>Need Help?</h3>
            <ul>
                <li>Call: 0731-1234567</li>
                <li>Email: info@NITM.ac.in</li>
                <li>Mon–Sat: 9AM–5PM</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>New Here?</h3>
            <ul>
                <li><a href="register.php">Create Account</a></li>
                <li><a href="courses.php">View Courses</a></li>
            </ul>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
