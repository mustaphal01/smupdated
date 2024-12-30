<?php
// admin/dashboard.php
require_once '../includes/config.php';
require_once '../includes/auth.php';
requireAdmin();

// Get counts for different application statuses
$status_counts = [];
$count_sql = "SELECT status, COUNT(*) as count FROM applications GROUP BY status";
$count_result = $conn->query($count_sql);
while ($row = $count_result->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}

// Get applications with optional status filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$where_clause = $status_filter !== 'all' ? "WHERE status = '$status_filter'" : "";

$sql = "SELECT applications.*, users.email as user_email 
        FROM applications 
        JOIN users ON applications.user_id = users.id 
        $where_clause 
        ORDER BY applications.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - School Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <nav class="dashboard-nav">
            <h2>Admin Dashboard</h2>
            <a href="../logout.php" class="btn btn-danger">Logout</a>
        </nav>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Pending Applications</h3>
                <p class="stat-number"><?php echo $status_counts['pending'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Accepted Applications</h3>
                <p class="stat-number"><?php echo $status_counts['accepted'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Rejected Applications</h3>
                <p class="stat-number"><?php echo $status_counts['rejected'] ?? 0; ?></p>
            </div>
        </div>

        <div class="filter-section">
            <form action="" method="GET" class="status-filter">
                <label>Filter by Status:</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Applications</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="accepted" <?php echo $status_filter === 'accepted' ? 'selected' : ''; ?>>Accepted</option>
                    <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </form>
        </div>

        <div class="applications-list">
            <table class="applications-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Program</th>
                        <th>JAMB Score</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($application = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($application['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($application['intended_program']); ?></td>
                            <td><?php echo htmlspecialchars($application['jamb_score']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $application['status']; ?>">
                                    <?php echo ucfirst($application['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="../assets/uploads/passports/<?php echo $application['passport_photo']; ?>" 
                                   target="_blank" class="btn btn-small">Passport</a>
                                <a href="../assets/uploads/waec/<?php echo htmlspecialchars($application['waec_result_image']); ?>" 
                                   target="_blank" class="btn btn-small">WAEC</a>
                            </td>
                            <td>
                                <?php if ($application['status'] === 'pending'): ?>
                                    <button onclick="viewApplication(<?php echo $application['id']; ?>)" 
                                            class="btn btn-primary btn-small">View</button>
                                    <button onclick="updateStatus(<?php echo $application['id']; ?>, 'accepted')" 
                                            class="btn btn-success btn-small">Accept</button>
                                    <button onclick="updateStatus(<?php echo $application['id']; ?>, 'rejected')" 
                                            class="btn btn-danger btn-small">Reject</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Application Details Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="applicationDetails"></div>
        </div>
    </div>

    <script>
        function viewApplication(id) {
            $.get('get_application.php', { id: id }, function(data) {
                $('#applicationDetails').html(data);
                $('#applicationModal').show();
            });
        }

        function updateStatus(id, status) {
            if (confirm('Are you sure you want to ' + status + ' this application?')) {
                $.post('process_application.php', {
                    application_id: id,
                    status: status
                }, function(response) {
                    if (response.success) {
                        alert('Application ' + status + ' successfully!');
                        location.reload();
                    } else {
                        alert('Error updating application status');
                    }
                }, 'json');
            }
        }

        // Close modal when clicking the x button or outside the modal
        $('.close, .modal').click(function(e) {
            if (e.target === this) {
                $('#applicationModal').hide();
            }
        });
    </script>
</body>
</html>