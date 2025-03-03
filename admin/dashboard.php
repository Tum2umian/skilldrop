<?php

include '../includes/header.php';
include '../includes/db.php';

// Ensure only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch user info securely
$stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "../" . htmlspecialchars($user['profile_image']) : '../uploads/profile_images/default.svg';

// Fetch dashboard stats in one query (optimized)
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM professionals) AS total_professionals,
        (SELECT COUNT(*) FROM service_requests WHERE status = 'pending') AS total_pending_requests
";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Store counts for easier access
$users_count = $stats['total_users'];
$professionals_count = $stats['total_professionals'];
$pending_requests = $stats['total_pending_requests'];
?>

<main class="main">
    <h2>Admin Dashboard</h2>

    <nav class="nav-links" id="adminlinks">
        <div class="nav-links-container">
            <a href="manage-users.php" class="nav-link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-requests.php" class="nav-link">
                <i class="fas fa-tasks"></i> Service Requests
            </a>
            <a href="reports.php" class="nav-link">
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
            <p><?php echo number_format($users_count); ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-briefcase"></i> Professionals</h3>
            <p><?php echo number_format($professionals_count); ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Pending Requests</h3>
            <p><?php echo number_format($pending_requests); ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-cog"></i> System Settings</h3>
            <a href="settings.php" class="button">Manage</a>
        </div>
    </div>

    <section class="recent-activities">
    <h3 class="card-title"><i class="fas fa-bell"></i> Recent Activities</h3>
    <ul>
        <?php
        // Fetch recent logs securely with actor names
        $logs_query = "
            SELECT logs.description, logs.created_at, logs.user_id, users.full_name AS actor_name
            FROM logs 
            LEFT JOIN users ON logs.user_id = users.id 
            ORDER BY logs.created_at DESC 
            LIMIT 5
        ";
        $activities = $conn->query($logs_query);

        if ($activities->num_rows > 0) {
            while ($activity = $activities->fetch_assoc()) {
                $actor_name = htmlspecialchars($activity['actor_name'] ?? 'Unknown User'); // Who performed the action
                $description = htmlspecialchars($activity['description']);
                $timestamp = date("F j, Y, g:i a", strtotime($activity['created_at']));
                $affected_user_name = '';

                // Extract affected user ID from the description using regex
                if (preg_match('/user with ID (\d+)/', $description, $matches)) {
                    $affected_user_id = (int) $matches[1];

                    // Fetch affected user's full name from users table
                    $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
                    $stmt->bind_param("i", $affected_user_id);
                    $stmt->execute();
                    $user_data = $stmt->get_result()->fetch_assoc();
                    $stmt->close();

                    $affected_user_name = htmlspecialchars($user_data['full_name'] ?? 'Unknown User');

                    // Replace "user with ID X" with the actual name
                    $description = preg_replace('/user with ID \d+/', "<strong>$affected_user_name</strong>", $description);
                }

                echo "<li><strong>$actor_name</strong> - $description <br><small>On $timestamp</small></li>";
            }
        } else {
            echo "<li>No recent activity.</li>";
        }
        ?>
    </ul>
</section>

</main>

<?php include '../includes/footer.php'; ?>
