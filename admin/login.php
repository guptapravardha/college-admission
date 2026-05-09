<?php
$in_subfolder = true;
require_once '../config.php';
$error = '';
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please enter username and password.';
    } else {
        $result = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
        $admin  = mysqli_fetch_assoc($result);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id']   = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | NITM</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="background:#eef2f8;">
<header>
    <div style="width:60px;height:60px;border-radius:50%;border:3px solid #fff;background:#fff;
                display:flex;align-items:center;justify-content:center;color:#003580;font-weight:bold;">
        NITM
    </div>
    <div class="header-text">
        <h1>NITM Admin Panel</h1>
        <p>College Admission Management System</p>
    </div>
</header>
<div style="display:flex;align-items:center;justify-content:center; margin-top:40px;">
    <div class="form-wrap" style="width:380px;">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Admin username" required
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Admin password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; padding:12px;">
                Login as Admin
            </button>
        </form>
        <p style="margin-top:10px; font-size:13px; text-align:center;">
            <a href="../index.php">&larr; Back to Website</a>
        </p>
    </div>
</div>
</body>
</html>
