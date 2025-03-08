<?php
include '../includes/header.php';
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'worker') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch worker details
$stmt = $conn->prepare("SELECT full_name, email, phone, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$profile_image = !empty($user['profile_image']) ? "" . $user['profile_image'] : '../uploads/profile_images/default.svg';

// Fetch worker's pending job invitations
$invitations_stmt = $conn->prepare("
    SELECT i.id, j.job_title, e.full_name AS employer_name, i.status 
    FROM invitations i
    JOIN job_posts j ON i.job_id = j.id
    JOIN users e ON j.employer_id = e.id
    WHERE i.worker_id = ? AND i.status = 'pending'
    ORDER BY i.created_at DESC
");
$invitations_stmt->bind_param("i", $user_id);
$invitations_stmt->execute();
$invitations_result = $invitations_stmt->get_result();

// Fetch worker's pending service requests
$pending_requests_stmt = $conn->prepare("
    SELECT service_name FROM service_requests WHERE professional_id = ? AND status = 'pending'
");
$pending_requests_stmt->bind_param("i", $user_id);
$pending_requests_stmt->execute();
$pending_requests_result = $pending_requests_stmt->get_result();

// Fetch worker's completed service requests
$completed_requests_stmt = $conn->prepare("
    SELECT service_name FROM service_requests WHERE professional_id = ? AND status = 'completed'
");
$completed_requests_stmt->bind_param("i", $user_id);
$completed_requests_stmt->execute();
$completed_requests_result = $completed_requests_stmt->get_result();

// Fetch worker's reviews
$reviews_stmt = $conn->prepare("
    SELECT review_text, rating FROM reviews WHERE professional_id = ? ORDER BY created_at DESC
");
$reviews_stmt->bind_param("i", $user_id);
$reviews_stmt->execute();
$reviews_result = $reviews_stmt->get_result();
?>

<main class="main">
    <h2>Worker Dashboard</h2>

    <!-- Profile Section -->
    <nav class="dashboard-nav profile-header">
        <div class="profile-section">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image" class="profile-icon">
            <span class="profile-name"><?php echo htmlspecialchars($user_name); ?></span>
        </div>
        <div class="dashboard-links">
            <a href="edit-profile.php" class="button secondary">Edit Profile</a>
        </div>
    </nav>

    <!-- Job Invitations Section -->
    <div class="dashboard-card">
        <h3 class="card-title"><i class="fas fa-envelope"></i> Job Invitations</h3>
        <ul class="dashboard-list">
            <?php if ($invitations_result->num_rows > 0): ?>
                <?php while ($invite = $invitations_result->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($invite['job_title']); ?></strong> from <?php echo htmlspecialchars($invite['employer_name']); ?>
                        <form method="POST" action="respond-invitation.php">
                            <input type="hidden" name="invitation_id" value="<?php echo $invite['id']; ?>">
                            <button type="submit" name="response" value="accepted" class="button green"><i class="fas fa-check"></i> Accept</button>
                            <button type="submit" name="response" value="declined" class="button red"><i class="fas fa-times"></i> Decline</button>
                        </form>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="no-data">No job invitations</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Service Requests -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Pending Service Requests</h3>
            <ul class="dashboard-list">
                <?php
                if ($pending_requests_result->num_rows > 0) {
                    while ($request = $pending_requests_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($request['service_name']) . "</li>";
                    }
                } else {
                    echo "<li class='no-data'>No pending requests</li>";
                }
                ?>
            </ul>
        </div>

        <div class="dashboard-card">
            <h3 class="card-title"><i class="fas fa-check-circle"></i> Completed Requests</h3>
            <ul class="dashboard-list">
                <?php
                if ($completed_requests_result->num_rows > 0) {
                    while ($request = $completed_requests_result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($request['service_name']) . "</li>";
                    }
                } else {
                    echo "<li class='no-data'>No completed requests</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Reviews -->
    <section class="reviews-section">
        <h3 class="card-title"><i class="fas fa-star"></i> Client Reviews</h3>
        <ul class="dashboard-list">
            <?php
            if ($reviews_result->num_rows > 0) {
                while ($review = $reviews_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($review['review_text']) . " - Rating: ⭐" . htmlspecialchars($review['rating']) . "/5</li>";
                }
            } else {
                echo "<li class='no-data'>No reviews yet</li>";
            }
            ?>
        </ul>
    </section>
</main>

<script src="../assets/js/dashboard.js"></script>

<?php include '../includes/footer.php'; ?>
