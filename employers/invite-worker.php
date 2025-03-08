<?php
include '../includes/header.php';
include '../includes/db.php';

// Ensure only employers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: ../auth/login.php");
    exit;
}

$employer_id = $_SESSION['user_id'];

// Ensure a worker_id is provided
if (!isset($_GET['worker_id']) || empty($_GET['worker_id'])) {
    header("Location: search-workers.php"); // Redirect if worker ID is missing
    exit;
}

$worker_id = intval($_GET['worker_id']);
$message = "";

// Fetch worker details
$stmt = $conn->prepare("
    SELECT u.id, u.full_name, u.email, u.profile_image, 
           GROUP_CONCAT(s.skill_name SEPARATOR ', ') AS skills 
    FROM professionals p
    JOIN users u ON p.user_id = u.id
    JOIN skills s ON p.skill_id = s.id
    WHERE u.id = ? AND u.role = 'worker'
    GROUP BY u.id
");
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$worker = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Redirect if worker not found
if (!$worker) {
    header("Location: search-workers.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = intval($_POST['job_id']);
    $custom_message = trim($_POST['custom_message']);

    // Check if the employer owns the job
    $job_check_stmt = $conn->prepare("SELECT id FROM job_posts WHERE id = ? AND employer_id = ?");
    $job_check_stmt->bind_param("ii", $job_id, $employer_id);
    $job_check_stmt->execute();
    $job_exists = $job_check_stmt->get_result()->fetch_assoc();
    $job_check_stmt->close();

    if ($job_exists) {
        // Insert the invitation into the `invitations` table
        $invite_stmt = $conn->prepare("INSERT INTO invitations (employer_id, worker_id, job_id, message, status) VALUES (?, ?, ?, ?, 'pending')");
        $invite_stmt->bind_param("iiis", $employer_id, $worker_id, $job_id, $custom_message);
        
        if ($invite_stmt->execute()) {
            $message = "Invitation sent successfully!";
        } else {
            $message = "Error sending invitation. Please try again.";
        }
        $invite_stmt->close();
    } else {
        $message = "Invalid job selection.";
    }
}

// Fetch employer’s job posts
$jobs_stmt = $conn->prepare("SELECT id, job_title FROM job_posts WHERE employer_id = ?");
$jobs_stmt->bind_param("i", $employer_id);
$jobs_stmt->execute();
$jobs_result = $jobs_stmt->get_result();
$jobs_stmt->close();
?>

<main class="main container">
    <h2 class="page-title">Invite Worker to Apply</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo !empty($worker['profile_image']) ? "../" . htmlspecialchars($worker['profile_image']) : '../uploads/profile_images/default.svg'; ?>" 
                 alt="Worker Profile" class="profile-image">
            <h2><?php echo htmlspecialchars($worker['full_name']); ?></h2>
            <p><strong>Skills:</strong> <?php echo htmlspecialchars($worker['skills']); ?></p>
        </div>
    </div>

    <form method="POST" action="invite-worker.php?worker_id=<?php echo $worker_id; ?>" class="form-container">
        <div class="form-group">
            <label for="job_id" class="form-label">Select Job:</label>
            <select name="job_id" id="job_id" class="form-input" required>
                <option value="">-- Select Job --</option>
                <?php while ($job = $jobs_result->fetch_assoc()): ?>
                    <option value="<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['job_title']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="custom_message" class="form-label">Custom Message (Optional):</label>
            <textarea name="custom_message" id="custom_message" class="form-input" rows="3" placeholder="Write a personalized message..."></textarea>
        </div>

        <button type="submit" class="button green"><i class="fas fa-paper-plane"></i> Send Invitation</button>
        <a href="search-workers.php" class="button secondary"><i class="fas fa-arrow-left"></i> Back to Search</a>
    </form>
</main>

<?php include '../includes/footer.php'; ?>
