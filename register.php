<?php
//  register.php  –  New student registration
require_once 'config.php';
$page_title = 'Student Registration';
$error   = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect and sanitize inputs
    $full_name  = trim(mysqli_real_escape_string($conn, $_POST['full_name']));
    $email      = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone      = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $dob        = $_POST['dob'];
    $gender     = $_POST['gender'];
    $address    = trim(mysqli_real_escape_string($conn, $_POST['address']));
    $marks_10th = floatval($_POST['marks_10th']);
    $marks_12th = floatval($_POST['marks_12th']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];

    // Basic validation
    if (empty($full_name) || empty($email) || empty($password)) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($marks_10th < 0 || $marks_10th > 100 || $marks_12th < 0 || $marks_12th > 100) {
        $error = 'Please enter valid percentage (0-100).';
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT student_id FROM students WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'This email is already registered. Please login.';
        } else {
            // Hash password before storing
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO students
                        (full_name, email, password, phone, dob, gender, address, marks_10th, marks_12th)
                    VALUES
                        ('$full_name','$email','$hashed','$phone','$dob','$gender','$address','$marks_10th','$marks_12th')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Registration successful! You can now <a href="login.php">Login here</a>.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="page-banner">Student Registration</div>

<div class="container">
    <div class="main-content">

        <div class="form-wrap" style="max-width:620px;">
            <h2>New Student Registration</h2>

            <?php if ($error):   ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <?php if (!$success): // Hide form after successful registration ?>
            <form method="POST" action="">

                <h3 class="sub-title">Personal Details</h3>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" placeholder="Enter your full name"
                           value="<?= isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" placeholder="Enter your email"
                           value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>

                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="tel" name="phone" placeholder="10-digit mobile number"
                           value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="<?= isset($_POST['dob']) ? $_POST['dob'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">-- Select --</option>
                        <option value="Male"   <?= (isset($_POST['gender']) && $_POST['gender']=='Male')   ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= (isset($_POST['gender']) && $_POST['gender']=='Female') ? 'selected' : '' ?>>Female</option>
                        <option value="Other"  <?= (isset($_POST['gender']) && $_POST['gender']=='Other')  ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" placeholder="Your full address"><?= isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' ?></textarea>
                </div>

                <h3 class="sub-title">Academic Details</h3>

                <div class="form-group">
                    <label>10th Marks (Percentage)</label>
                    <input type="number" name="marks_10th" min="0" max="100" step="0.01"
                           placeholder="e.g. 78.50"
                           value="<?= isset($_POST['marks_10th']) ? $_POST['marks_10th'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>12th Marks (Percentage) *</label>
                    <input type="number" name="marks_12th" min="0" max="100" step="0.01"
                           placeholder="e.g. 65.00" required
                           value="<?= isset($_POST['marks_12th']) ? $_POST['marks_12th'] : '' ?>">
                </div>

                <h3 class="sub-title">Set Password</h3>

                <div class="form-group">
                    <label>Password * (min 6 characters)</label>
                    <input type="password" name="password" placeholder="Create a password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" placeholder="Repeat your password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                    Register Now
                </button>

                <p style="margin-top:12px; font-size:13px; text-align:center;">
                    Already registered? <a href="login.php">Login here</a>
                </p>

            </form>
            <?php endif; ?>
        </div>

    </div>

    <div class="sidebar">
        <div class="sidebar-widget">
            <h3>Steps to Apply</h3>
            <ul>
                <li><strong>Step 1:</strong> Register here</li>
                <li><strong>Step 2:</strong> Login to account</li>
                <li><strong>Step 3:</strong> Fill Apply form</li>
                <li><strong>Step 4:</strong> Check your status</li>
            </ul>
        </div>
        <div class="sidebar-widget">
            <h3>Eligibility</h3>
            <ul>
                <li>Min 50% in 12th for BBA/MBA</li>
                <li>Min 60% for B.Tech</li>
                <li>Min 55% for BSc DS</li>
            </ul>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
