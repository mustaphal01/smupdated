<?php
// dashboard.php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireStudent();

// Check if student has already submitted an application
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM applications WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existing_application = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - School Management System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <nav class="dashboard-nav">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </nav>

        <?php if ($existing_application): ?>
            <div class="application-status">
                <h3>Application Status</h3>
                <div class="alert <?php 
                    echo $existing_application['status'] === 'accepted' ? 'alert-success' : 
                        ($existing_application['status'] === 'rejected' ? 'alert-danger' : 'alert-info'); 
                ?>">
                    Your application is currently <strong><?php echo strtoupper($existing_application['status']); ?></strong>
                </div>
                
                <h4>Application Details:</h4>
                <div class="application-details">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($existing_application['full_name']); ?></p>
                    <p><strong>Program:</strong> <?php echo htmlspecialchars($existing_application['intended_program']); ?></p>
                    <p><strong>JAMB Score:</strong> <?php echo htmlspecialchars($existing_application['jamb_score']); ?></p>
                    <p><strong>Submitted on:</strong> <?php echo date('F j, Y', strtotime($existing_application['created_at'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="application-form">
                <h3>Admission Application Form</h3>
                
                <form action="submit_application.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Passport Photograph:</label>
                        <input type="file" name="passport_photo" accept="image/*" required>
                    </div>

                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="date" name="date_of_birth" required>
                    </div>

                    <div class="form-group">
                        <label>Gender:</label>
                        <select name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Genotype:</label>
                        <select name="genotype" required>
                            <option value="">Select Genotype</option>
                            <option value="AA">AA</option>
                            <option value="AS">AS</option>
                            <option value="SS">SS</option>
                            <option value="AC">AC</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>State:</label>
                        <input type="text" name="state" required>
                    </div>

                    <div class="form-group">
                        <label>Local Government Area (LGA):</label>
                        <input type="text" name="lga" required>
                    </div>

                    <div class="form-group">
                        <label>Nationality:</label>
                        <input type="text" name="nationality" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="tel" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label>Intended Program:</label>
                        <select name="intended_program" required>
                            <option value="">Select Program</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Medicine">Medicine</option>
                            <option value="Law">Law</option>
                            <option value="Business Administration">Business Administration</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>JAMB Score:</label>
                        <input type="number" name="jamb_score" min="0" max="400" required>
                    </div>

                    <div class="form-group">
                        <label>WAEC Result:</label>
                        <input type="file" name="waec_result" accept="image/*,.pdf" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>