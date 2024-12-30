<?php
// admin/process_application.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];
    
    // Validate status
    if (!in_array($status, ['accepted', 'rejected'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status']);
        exit;
    }
    
    // Update application status
    $sql = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $application_id);
    
    if ($stmt->execute()) {
        // Get student email for notification
        $email_sql = "SELECT users.email 
                     FROM applications 
                     JOIN users ON applications.user_id = users.id 
                     WHERE applications.id = ?";
        $email_stmt = $conn->prepare($email_sql);
        $email_stmt->bind_param("i", $application_id);
        $email_stmt->execute();
        $result = $email_stmt->get_result();
        $user = $result->fetch_assoc();
        
        // Send email notification (you'll need to configure your email settings)
        $to = $user['email'];
        $subject = "Application Status Update";
        $message = "Your application has been " . $status . ".";
        $headers = "From: noreply@school.com";
        
        mail($to, $subject, $message, $headers);
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    exit;
}
?>