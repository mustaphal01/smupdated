<?php
// index.php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - School Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <nav class="main-nav">
                <h1 class="site-title">School Management System</h1>
                <div class="nav-links">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?php echo $_SESSION['user_type'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php'; ?>" 
                           class="btn btn-primary">Dashboard</a>
                        <a href="logout.php" class="btn btn-danger">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login</a>
                        <a href="register.php" class="btn btn-success">Register</a>
                    <?php endif; ?>
                </div>
            </nav>

            <div class="hero-content">
                <h2>Welcome to Our School</h2>
                <p>Start your educational journey with us today. Apply for admission and track your application status easily.</p>
            </div>
        </div>
    </div>

    <div class="features-section">
        <div class="container">
            <h3>Key Features</h3>
            <div class="features-grid">
                <div class="feature-card">
                    <h4>Easy Application</h4>
                    <p>Submit your application online with our user-friendly form.</p>
                </div>
                
                <div class="feature-card">
                    <h4>Track Status</h4>
                    <p>Monitor your application status in real-time through your dashboard.</p>
                </div>
                
                <div class="feature-card">
                    <h4>Secure Process</h4>
                    <p>Your information is safe with our secure application process.</p>
                </div>
                
                <div class="feature-card">
                    <h4>Quick Response</h4>
                    <p>Get timely updates about your application status via email.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> School Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>