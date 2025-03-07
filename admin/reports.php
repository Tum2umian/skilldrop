<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch total users
$total_users = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];

// Fetch active professionals
$total_professionals = $conn->query("SELECT COUNT(*) as total FROM professionals")->fetch_assoc()['total'];

// Fetch service requests by status
$service_requests = $conn->query("SELECT status, COUNT(*) as total FROM service_requests GROUP BY status");

$request_data = [];
while ($row = $service_requests->fetch_assoc()) {
    $request_data[$row['status']] = $row['total'];
}

// Fetch recent logs
$logs = $conn->query("SELECT * FROM logs ORDER BY created_at DESC LIMIT 10");
?>

<main class="main">
    <h2>Reports & Analytics</h2>

    <nav class="nav-links" id="adminlinks">
        <div class="nav-links-container">
            <a href="manage-users.php" class="nav-link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-requests.php" class="nav-link">
                <i class="fas fa-tasks"></i> Service Requests
            </a>
            <a href="reports.php" class="nav-link active">
                <i class="fas fa-chart-line"></i> Reports
            </a>
            <a href="settings.php" class="nav-link">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </nav>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-users"></i> Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-briefcase"></i> Professionals</h3>
            <p><?php echo $total_professionals; ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Service Requests</h3>
            <ul>
                <li>Pending: <?php echo $request_data['pending'] ?? 0; ?></li>
                <li>Approved: <?php echo $request_data['approved'] ?? 0; ?></li>
                <li>Rejected: <?php echo $request_data['rejected'] ?? 0; ?></li>
                <li>Completed: <?php echo $request_data['completed'] ?? 0; ?></li>
            </ul>
        </div>
    </div>

    <section class="recent-activities">
    <h3 class="card-title"><i class="fas fa-bell"></i> Recent Activities</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Message</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch logs with actor's name and affected user's name in one query
            $logs_query = "
                SELECT logs.description, logs.created_at, logs.user_id, 
                    actor.full_name AS actor_name, affected.full_name AS affected_name 
                FROM logs
                LEFT JOIN users AS actor ON logs.user_id = actor.id
                LEFT JOIN users AS affected ON logs.description REGEXP 'user ID: [0-9]+' 
                    AND SUBSTRING_INDEX(logs.description, 'user ID: ', -1) = affected.id
                ORDER BY logs.created_at DESC
                LIMIT 5
            ";

            $logs_result = $conn->query($logs_query);

            if ($logs_result->num_rows > 0):
                while ($log = $logs_result->fetch_assoc()):
                    $actor_name = htmlspecialchars($log['actor_name'] ?? 'Unknown User');
                    $timestamp = date("Y-m-d H:i:s", strtotime($log['created_at']));
                    $affected_name = htmlspecialchars($log['affected_name'] ?? 'Unknown User');

                    // Dynamic replacement of action descriptions
                    $description = htmlspecialchars($log['description']);
                    $description_replacements = [
                        'Approved user ID' => "Approved user: <strong>$affected_name</strong>",
                        'Suspended user ID' => "Suspended user: <strong>$affected_name</strong>",
                        'Unsuspended user ID' => "Unsuspended user: <strong>$affected_name</strong>",
                        'Deleted user ID' => "Deleted user: <strong>$affected_name</strong>",
                        'profile_update' => "Updated their profile"
                    ];

                    foreach ($description_replacements as $key => $replacement) {
                        if (strpos($description, $key) !== false) {
                            $description = $replacement;
                            break;
                        }
                    }
            ?>
                <tr>
                    <td><strong><?php echo $actor_name; ?></strong> - <?php echo $description; ?></td>
                    <td><?php echo $timestamp; ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="2">No recent activity.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

</main>

<?php include '../includes/footer.php'; ?>
