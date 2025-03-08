<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['user_id'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = trim($_POST['job_title']);
    $description = trim($_POST['description']);
    $status = "open"; // Default to open when posting

    // Validate input
    if (empty($job_title) || empty($description)) {
        $message = "All fields are required!";
    } else {
        // Insert job into the database
        $stmt = $conn->prepare("INSERT INTO job_posts (employer_id, job_title, description, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $employer_id, $job_title, $description, $status);
        
        if ($stmt->execute()) {
            $message = "Job posted successfully!";
        } else {
            $message = "Error posting job. Please try again.";
        }
        $stmt->close();
    }
}
?>

<main class="main container">
    <!-- Navigation Links -->
    <nav class="nav-links">
        <div class="nav-links-container">
            <a href="post-job.php" class="nav-link"><i class="fas fa-plus-circle"></i> Post a Job</a>
            <a href="manage-jobs.php" class="nav-link"><i class="fas fa-briefcase"></i> Manage Jobs</a>
            <a href="search-workers.php" class="nav-link"><i class="fas fa-users"></i> Find Workers</a>
           
        </div>
    </nav>
    <h2 class="page-title">Post a New Job</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="post-job.php" method="POST" class="form-container">
        
        <div class="form-group">
            <label for="job_title" class="form-label">Job Title:</label>
            <input type="text" name="job_title" id="job_title" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Job Description:</label>
            <textarea name="description" id="description" class="form-input" rows="5" required></textarea>
        </div>

        <button type="submit" class="button primary">Post Job</button>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
