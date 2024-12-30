<?php
// admin/get_application.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT applications.*, users.email as user_email 
            FROM applications 
            JOIN users ON applications.user_id = users.id 
            WHERE applications.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    
    if ($application) {
        ?>
        <div class="application-detail">
            <h3>Application Details</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Full Name:</label>
                    <span><?php echo htmlspecialchars($application['full_name']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Email:</label>
                    <span><?php echo htmlspecialchars($application['user_email']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Date of Birth:</label>
                    <span><?php echo htmlspecialchars($application['date_of_birth']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Gender:</label>
                    <span><?php echo htmlspecialchars($application['gender']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Genotype:</label>
                    <span><?php echo htmlspecialchars($application['genotype']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>State:</label>
                    <span><?php echo htmlspecialchars($application['state']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>LGA:</label>
                    <span><?php echo htmlspecialchars($application['lga']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Nationality:</label>
                    <span><?php echo htmlspecialchars($application['nationality']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Phone:</label>
                    <span><?php echo htmlspecialchars($application['phone']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Intended Program:</label>
                    <span><?php echo htmlspecialchars($application['intended_program']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>JAMB Score:</label>
                    <span><?php echo htmlspecialchars($application['jamb_score']); ?></span>
                </div>
                
                <div class="detail-item">
                    <label>Application Date:</label>
                    <span><?php echo date('F j, Y', strtotime($application['created_at'])); ?></span>
                </div>
            </div>
            
            <div class="documents-section">
                <h4>Documents</h4>
                <div class="document-links">
                    <a href="../assets/uploads/passports/<?php echo $application['passport_photo']; ?>" 
                       target="_blank" class="btn btn-primary">View Passport</a>
                    <a href="../assets/uploads/waec/<?php echo $application['waec_result']; ?>" 
                       target="_blank" class="btn btn-primary">View WAEC Result</a>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<p>Application not found.</p>";
    }
}
?>