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
$stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "" . htmlspecialchars($user['profile_image']) : '../uploads/profile_images/default.svg';

// Fetch employer's job postings
$jobs_stmt = $conn->prepare("
    SELECT id, job_title, status, created_at 
    FROM job_posts 
    WHERE employer_id = ? 
    ORDER BY created_at DESC
");
$jobs_stmt->bind_param("i", $employer_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();

// Fetch applications for employer's jobs
$applications_stmt = $conn->prepare("
    SELECT a.id, a.status, j.job_title, u.full_name AS worker_name 
    FROM applications a
    JOIN job_posts j ON a.job_id = j.id
    JOIN users u ON a.user_id = u.id
    WHERE j.employer_id = ?
    ORDER BY a.created_at DESC
");
$applications_stmt->bind_param("i", $employer_id);
$applications_stmt->execute();
$applications_result = $applications_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>../assets/css/main.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>../assets/css/forms.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>../assets/css/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body>

<main class="main">
   

    <!-- Navigation Links -->
    <nav class="nav-links">
        <div class="nav-links-container">
            <a href="post-job.php" class="nav-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            <a href="manage-jobs.php" class="nav-link"><i class="fas fa-briefcase"></i> Manage Jobs</a>
            <a href="search-workers.php" class="nav-link"><i class="fas fa-users"></i> Find Workers</a>
           
        </div>
    </nav>

    <!-- Job Postings -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-briefcase"></i> Your Job Posts</h3>
            <ul class="dashboard-list">
                <?php
                if ($jobs_result->num_rows > 0) {
                    while ($job = $jobs_result->fetch_assoc()) {
                        echo "<li class='dashboard-item'>
                            <strong>" . htmlspecialchars($job['job_title']) . "</strong> 
                            - <span class='status " . htmlspecialchars($job['status']) . "'>" . ucfirst($job['status']) . "</span>
                            <a href='edit-job.php?id=" . $job['id'] . "' class='edit-link'><i class='fas fa-edit'></i> Edit</a>
                        </li>";
                    }
                } else {
                    echo "<li class='dashboard-item no-data'>No job posts yet</li>";
                }
                ?>
            </ul>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-users'></i> Job Applications</h3>
            <ul class="dashboard-list">
                <?php
                if ($applications_result->num_rows > 0) {
                    while ($app = $applications_result->fetch_assoc()) {
                        echo "<li class='dashboard-item'>
                            <strong>" . htmlspecialchars($app['job_title']) . "</strong> 
                            - Applied by <span class='worker-name'>" . htmlspecialchars($app['worker_name']) . "</span> 
                            - <span class='status " . htmlspecialchars($app['status']) . "'>" . ucfirst($app['status']) . "</span>
                            <a href='view-application.php?id=" . $app['id'] . "' class='view-link'><i class='fas fa-eye'></i> View</a>
                        </li>";
                    }
                } else {
                    echo "<li class='dashboard-item no-data'>No applications yet</li>";
                }
                ?>
            </ul>
        </div>
    </div>


</main>

<script src="../assets/js/dashboard.js"></script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
