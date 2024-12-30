<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
requireStudent();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Create uploads directories if they don't exist
        $passport_dir = "assets/uploads/passports";
        $waec_dir = "assets/uploads/waec";
        if (!file_exists($passport_dir)) mkdir($passport_dir, 0777, true);
        if (!file_exists($waec_dir)) mkdir($waec_dir, 0777, true);

        // Handle passport photo upload
        $passport_photo = $_FILES['passport_photo'];
        $passport_name = time() . '_' . $passport_photo['name'];
        $passport_path = $passport_dir . '/' . $passport_name;
        
        // Handle WAEC result upload
        $waec_result = $_FILES['waec_result'];
        $waec_name = time() . '_' . $waec_result['name'];
        $waec_path = $waec_dir . '/' . $waec_name;

        // Validate and move uploaded files
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        
        if (!in_array($passport_photo['type'], $allowed_types)) {
            throw new Exception("Invalid passport photo format. Please upload JPG, PNG or PDF");
        }
        
        if (!in_array($waec_result['type'], $allowed_types)) {
            throw new Exception("Invalid WAEC result format. Please upload JPG, PNG or PDF");
        }

        if (!move_uploaded_file($passport_photo['tmp_name'], $passport_path)) {
            throw new Exception("Failed to upload passport photo");
        }

        if (!move_uploaded_file($waec_result['tmp_name'], $waec_path)) {
            throw new Exception("Failed to upload WAEC result");
        }

        // Insert application into database
        $sql = "INSERT INTO applications (
            user_id, passport_photo, full_name, date_of_birth, gender, 
            genotype, state, lga, nationality, email, phone_number, 
            intended_program, jamb_score, waec_result_image, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param(
            "isssssssssssss",
            $_SESSION['user_id'],
            $passport_name,
            $_POST['full_name'],
            $_POST['date_of_birth'],
            $_POST['gender'],
            $_POST['genotype'],
            $_POST['state'],
            $_POST['lga'],
            $_POST['nationality'],
            $_SESSION['email'],
            $_POST['phone'],
            $_POST['intended_program'],
            $_POST['jamb_score'],
            $waec_name
        );

        if ($stmt->execute()) {
            $_SESSION['success'] = "Application submitted successfully!";
            header("Location: dashboard.php");
            exit();
        } else {
            throw new Exception("Error submitting application: " . $stmt->error);
        }

    } catch (Exception $e) {
        error_log($e->getMessage(), 3, 'errors.log');
        $_SESSION['error'] = $e->getMessage();
        header("Location: dashboard.php");
        exit();
    }
}
?>