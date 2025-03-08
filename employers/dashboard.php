<?php
include '../includes/header.php';
include '../includes/db.php';

// Ensure only employers can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['user_id'];
$employer_name = $_SESSION['user_name'];

// Fetch employer profile details
$stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "../" . htmlspecialchars($user['profile_image']) : '../uploads/profile_images/default.svg';

// Fetch dashboard stats
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM job_posts WHERE employer_id = ?) AS total_jobs,
        (SELECT COUNT(*) FROM applications a 
            JOIN job_posts j ON a.job_id = j.id 
            WHERE j.employer_id = ?) AS total_applications,
        (SELECT COUNT(*) FROM applications a 
            JOIN job_posts j ON a.job_id = j.id 
            WHERE j.employer_id = ? AND a.status = 'pending') AS pending_applications
";
$stmt = $conn->prepare($stats_query);
$stmt->bind_param("iii", $employer_id, $employer_id, $employer_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

$total_jobs = $stats['total_jobs'];
$total_applications = $stats['total_applications'];
$pending_applications = $stats['pending_applications'];

// Fetch recent job postings
$jobs_stmt = $conn->prepare("
    SELECT id, job_title, status, created_at 
    FROM job_posts 
    WHERE employer_id = ? 
    ORDER BY created_at DESC 
    LIMIT 5
");
$jobs_stmt->bind_param("i", $employer_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();

// Fetch recent applications
$applications_stmt = $conn->prepare("
    SELECT a.id, a.status, j.job_title, u.full_name AS worker_name 
    FROM applications a
    JOIN job_posts j ON a.job_id = j.id
    JOIN users u ON a.user_id = u.id
    WHERE j.employer_id = ?
    ORDER BY a.created_at DESC
    LIMIT 5
");
$applications_stmt->bind_param("i", $employer_id);
$applications_stmt->execute();
$applications_result = $applications_stmt->get_result();
?>

<main class="main">
    <h2>Employer Dashboard</h2>

    <!-- Navigation Links -->
    <nav class="nav-links">
        <div class="nav-links-container">
            <a href="post-job.php" class="nav-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            <a href="manage-jobs.php" class="nav-link"><i class="fas fa-briefcase"></i> Manage Jobs</a>
            <a href="search-workers.php" class="nav-link"><i class="fas fa-users"></i> Find Workers</a>
           
        </div>
    </nav>

    <!-- Dashboard Stats (Shared CSS) -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-briefcase"></i> Total Jobs Posted</h3>
            <p><?php echo number_format($total_jobs); ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-file-alt"></i> Total Applications</h3>
            <p><?php echo number_format($total_applications); ?></p>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-clock"></i> Pending Applications</h3>
            <p><?php echo number_format($pending_applications); ?></p>
        </div>
    </div>

    <!-- Recent Job Posts -->
    <section class="recent-activities">
        <h3 class="card-title"><i class="fas fa-briefcase"></i> Recent Job Posts</h3>
        <ul>
            <?php
            if ($jobs_result->num_rows > 0) {
                while ($job = $jobs_result->fetch_assoc()) {
                    echo "<li>
                        <strong>" . htmlspecialchars($job['job_title']) . "</strong> 
                        - <span class='status " . htmlspecialchars($job['status']) . "'>" . ucfirst($job['status']) . "</span>
                        <a href='edit-job.php?id=" . $job['id'] . "' class='edit-link'><i class='fas fa-edit'></i> Edit</a>
                    </li>";
                }
            } else {
                echo "<li>No job posts yet.</li>";
            }
            ?>
        </ul>
    </section>

    <!-- Recent Applications -->
    <section class="recent-activities">
        <h3 class="card-title"><i class="fas fa-user-check"></i> Recent Applications</h3>
        <ul>
            <?php
            if ($applications_result->num_rows > 0) {
                while ($app = $applications_result->fetch_assoc()) {
                    echo "<li>
                        <strong>" . htmlspecialchars($app['job_title']) . "</strong> 
                        - Applied by <span class='worker-name'>" . htmlspecialchars($app['worker_name']) . "</span> 
                        - <span class='status " . htmlspecialchars($app['status']) . "'>" . ucfirst($app['status']) . "</span>
                        <a href='view-application.php?id=" . $app['id'] . "' class='view-link'><i class='fas fa-eye'></i> View</a>
                    </li>";
                }
            } else {
                echo "<li>No applications yet.</li>";
            }
            ?>
        </ul>
    </section>
</main>

<script src="../assets/js/dashboard.js"></script>

<?php include '../includes/footer.php'; ?>
